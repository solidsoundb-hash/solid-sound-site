// SEO — FULL CHECK (owner 21.11.2026: «по SEO полный тестировщик» → «да»). Reads the site the way Google does:
//  A. every page of the sitemap + every page found by following internal links (crawl from the home pages of all languages);
//  B. per page: status, title, description, H1, canonical, noindex, og:image, alt texts, thin text, hreflang;
//  C. structured data: Organization/WebSite on home, Product (price, stock, picture, rating), BreadcrumbList, FAQPage, Article;
//  D. site structure: pages missing from the sitemap, orphan pages (no internal link points to them), internal links that
//     redirect (and redirect chains), broken internal links;
//  E. language versions: hreflang links back and forth, x-default, all languages present;
//  F. images: heavy files, old formats, no width/height (page jumps);
//  G. URL order: http→https, www→no-www in one hop, trailing slash, capital letters, ?currency= / ?add-to-cart= duplicates;
//  H. robots.txt and the sitemap itself. Titles/descriptions repeated on several pages.
// What Google itself sees (indexing, queries, positions, real-visitor speed) — tools/bots/seo.js, needs GOOGLE_SA_JSON.
'use strict';
const { BASE, sleep, esc } = require('../bots/lib');
const MAX = +process.env.SEO_MAX || 600;
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundBots/1.0';
const LANGS = ['en', 'ua', 'de', 'fr', 'es', 'it', 'pt'];
const UTIL = /\/(cart|checkout|my-account|store-manager|client-dashboard|add-product|wishlist|order-tracking|login|register|data-deletion|links|sitemap)\b/;
const attr = (tag, a) => { const m = tag.match(new RegExp('\\b' + a + '\\s*=\\s*"([^"]*)"', 'i')) || tag.match(new RegExp('\\b' + a + "\\s*=\\s*'([^']*)'", 'i')); return m ? m[1] : null; };
const dec = s => String(s || '').replace(/&amp;/g, '&').replace(/&#0?39;|&#8217;/g, "'").replace(/&quot;/g, '"').replace(/&#8211;|&ndash;/g, '–').replace(/&#8212;/g, '—').replace(/<[^>]+>/g, '').trim();
const norm = u => { try { const x = new URL(u, BASE); return x.origin + x.pathname.replace(/\/?$/, '/'); } catch (e) { return u; } };
const short = u => String(u).replace(BASE, '') || '/';
const isBlock = t => /Захищена сторінка/.test(t || '');

// GET without following redirects: we want to see every hop
async function hop(url) {
  for (let i = 0; i < 3; i++) {
    try {
      const r = await fetch(url, { redirect: 'manual', headers: { 'User-Agent': UA }, signal: AbortSignal.timeout(45000) });
      const loc = r.headers.get('location'); const t = r.status >= 300 && r.status < 400 ? '' : await r.text();
      if (r.status === 429 || r.status === 503 || isBlock(t)) { await sleep(8000 * (i + 1)); continue; }
      return { st: r.status, loc: loc ? new URL(loc, url).href : null, text: t, type: r.headers.get('content-type') || '', len: +(r.headers.get('content-length') || 0) };
    } catch (e) { await sleep(4000); }
  }
  return { st: 0, text: '' };
}
async function follow(url) { const chain = []; let u = url, r; for (let i = 0; i < 6; i++) { r = await hop(u); if (r.st >= 300 && r.st < 400 && r.loc) { chain.push([r.st, r.loc]); u = r.loc; } else break; } return { ...r, final: u, chain }; }

async function sitemapUrls() {
  const out = new Set(); const seen = new Set();
  const read = async (u, depth) => { if (seen.has(u) || depth > 2) return; seen.add(u); const r = await hop(u); await sleep(600);
    for (const m of (r.text || '').matchAll(/<loc>\s*([^<\s]+)\s*<\/loc>/g)) { const l = dec(m[1]); if (/\.xml(\?|$)/.test(l)) await read(l, depth + 1); else out.add(norm(l)); } };
  for (const u of ['/wp-sitemap.xml', '/sitemap_index.xml', '/sitemap.xml']) { await read(BASE + u, 0); if (out.size) break; }
  return out;
}
// internal page links worth crawling (no files, no admin, no query strings except the ones we judge separately)
function pageLinks(html, from) {
  const out = []; for (const m of html.matchAll(/<a\b[^>]*\bhref\s*=\s*["']([^"'#]+)["']/gi)) {
    let u; try { u = new URL(dec(m[1]), from); } catch (e) { continue; }
    if (!/^(www\.)?solid-sound\.net$/.test(u.hostname) || !/^https?:$/.test(u.protocol)) continue;
    if (/\/(wp-admin|wp-json|wp-content|wp-includes|feed|xmlrpc|wp-login)|\.(jpe?g|png|gif|webp|avif|svg|mp3|wav|zip|pdf|xml|css|js)$/i.test(u.pathname)) continue;
    out.push(u.href);
  } return out;
}

module.exports = async function seoFull() {
  const robots = await hop(BASE + '/robots.txt'); const sm = await sitemapUrls();
  const queue = [...new Set([...sm, ...LANGS.map(l => BASE + '/' + l + '/'), BASE + '/'])]; const seenQ = new Set(queue.map(norm));
  const pages = new Map(); const inbound = {}; const linkTargets = new Map(); const paramLinks = {}; const imgs = new Map();
  const byTitle = {}, byDesc = {};
  while (queue.length && pages.size < MAX) {
    const u = queue.shift(); const r = await follow(u); await sleep(1000);
    const P = { u: norm(u), st: r.st, final: norm(r.final), chain: r.chain, issues: [], hl: [], types: [] }; pages.set(P.u, P);
    const I = (sev, t) => P.issues.push([sev, t]);
    if (!r.st || r.st >= 400) { I('🔴', 'страница не открывается (' + (r.st || 'нет ответа') + ')'); continue; }
    if (P.final !== P.u) { P.redirect = true; if (!seenQ.has(P.final)) { seenQ.add(P.final); queue.push(P.final); } continue; }
    if (!/html/.test(r.type || 'html')) continue;
    const h = r.text || ''; const head = (h.match(/<head[\s\S]*?<\/head>/i) || [''])[0];
    const title = dec((head.match(/<title[^>]*>([\s\S]*?)<\/title>/i) || [])[1]);
    const metas = [...head.matchAll(/<meta\b[^>]*>/gi)].map(m => m[0]); const links = [...head.matchAll(/<link\b[^>]*>/gi)].map(m => m[0]);
    const meta = (k, v) => { const t = metas.find(m => (attr(m, k) || '').toLowerCase() === v); return t ? dec(attr(t, 'content')) : null; };
    const desc = meta('name', 'description'), rob = meta('name', 'robots') || '', og = meta('property', 'og:image');
    const canon = attr(links.find(l => /rel=["']canonical/i.test(l)) || '', 'href');
    P.hl = links.filter(l => /hreflang=/i.test(l)).map(l => [attr(l, 'hreflang'), norm(attr(l, 'href'))]);
    P.noindex = /noindex/i.test(rob); P.canon = canon ? norm(canon) : null; P.inSm = sm.has(P.u); P.util = UTIL.test(P.u);
    const body = h.replace(/<(script|style|noscript|svg|template)[\s\S]*?<\/\1>/gi, ' ').replace(/<(header|footer|nav)\b[\s\S]*?<\/\1>/gi, ' ');
    const words = (body.replace(/<[^>]+>/g, ' ').replace(/&[a-z#0-9]+;/gi, ' ').match(/[A-Za-zА-Яа-яІіЇїЄєҐґÀ-ÿ]{2,}/g) || []).length;
    const h1 = (h.match(/<h1\b/gi) || []).length;
    if (P.noindex && P.inSm) I('🔴', 'закрыта от Google (noindex), а стоит в карте сайта');
    if (!P.noindex && !P.util) {
      if (!title) I('🔴', 'нет заголовка'); else if (title.length > 65) I('🟡', `заголовок длинный (${title.length} зн., Google обрежет после ~60)`); else if (title.length < 20) I('🟡', `заголовок короткий (${title.length} зн.)`);
      if (!desc) I('🟠', 'нет описания (description)'); else if (desc.length > 165) I('🟡', `описание длинное (${desc.length} зн.)`); else if (desc.length < 70) I('🟡', `описание короткое (${desc.length} зн.)`);
      if (!canon) I('🟠', 'нет canonical'); else if (P.canon !== P.u) I('🟠', 'canonical указывает на другую страницу');
      if (!h1) I('🟡', 'нет заголовка H1'); else if (h1 > 1) I('⚪', 'несколько H1');
      if (!og) I('🟡', 'нет картинки для соцсетей (og:image)');
      if (words < 250 && !/\/(product)\//.test(P.u)) I('🟡', `мало текста (${words} слов) — «тонкая» страница`);
      if (!P.inSm && P.canon === P.u) I('🟠', 'нет в карте сайта (Google найдёт её позже или не найдёт)');
    }
    // structured data
    for (const m of h.matchAll(/<script[^>]+application\/ld\+json[^>]*>([\s\S]*?)<\/script>/g)) {
      try { const j = JSON.parse(m[1]); for (const x of [].concat(j['@graph'] || j)) { const t = [].concat(x['@type'] || []); P.types.push(...t);
        if (t.includes('Product')) { const o = [].concat(x.offers || [])[0]; if (!o) I('🔴', 'товар без цены для Google (offers)'); else if (!o.availability) I('🟡', 'товар без «в наличии» (availability)'); if (!x.image) I('🟠', 'товар без картинки для Google'); if (!x.aggregateRating) I('⚪', 'товар без рейтинга (звёзды появятся с отзывами)'); if (!x.brand) I('⚪', 'товар без бренда (brand)'); } } }
      catch (e) { I('🟠', 'данные для Google (JSON-LD) с ошибкой — не читаются'); }
    }
    if (/\/product\//.test(P.u) && !P.types.includes('Product')) I('🔴', 'товар без данных Product — не будет цены в поиске');
    if (/\/(en|ua|de|fr|es|it|pt)\/$/.test(P.u) && !P.types.some(t => /Organization|WebSite/.test(t))) I('🟡', 'главная без данных об организации (Organization/WebSite)');
    if (/\/wiki\/.+/.test(P.u) && !P.types.some(t => /Article|BlogPosting/.test(t))) I('🟡', 'статья вики без разметки Article');
    if (/\/faq\//.test(P.u) && !P.types.includes('FAQPage')) I('🟡', 'FAQ без разметки FAQPage (вопросы могут показываться прямо в Google)');
    if (!P.util && P.u.split('/').filter(Boolean).length > 3 && !P.types.includes('BreadcrumbList')) I('⚪', 'нет хлебных крошек (BreadcrumbList)');
    // links and images
    for (const l of pageLinks(h, r.final)) {
      const n = norm(l); const q = new URL(l).search;
      if (q) { const k = q.replace(/=.*/, ''); (paramLinks[k] = paramLinks[k] || new Set()).add(short(P.u)); if (!/^\?(currency|add-to-cart|orderby|filter_|s=|lang)/.test(q) && !seenQ.has(n)) {} continue; }
      (inbound[n] = inbound[n] || new Set()).add(P.u); if (!linkTargets.has(n)) linkTargets.set(n, P.u);
      if (!seenQ.has(n)) { seenQ.add(n); queue.push(n); }
    }
    let noSize = 0; for (const m of h.matchAll(/<img\b[^>]*>/gi)) { const t = m[0]; const src = attr(t, 'src') || attr(t, 'data-src'); if (!src || /^data:/.test(src)) continue;
      if (!/\bwidth=/.test(t) || !/\bheight=/.test(t)) noSize++; try { const a = new URL(dec(src), r.final).href; if (!imgs.has(a)) imgs.set(a, P.u); } catch (e) {} }
    if (noSize > 3) I('⚪', `картинок без размеров (width/height): ${noSize} — страница «прыгает» при загрузке`);
    P.title = title; if (!P.noindex && title) (byTitle[title] = byTitle[title] || []).push(P.u); if (!P.noindex && desc) (byDesc[desc] = byDesc[desc] || []).push(P.u);
  }
  const live = [...pages.values()].filter(p => p.st && p.st < 400 && !p.redirect);
  // D. structure: sitemap pages nobody links to; links that redirect / are broken
  // a link to an address that redirects counts for the page it ends on
  for (const p of pages.values()) if (p.redirect && inbound[p.u]) { inbound[p.final] = inbound[p.final] || new Set(); inbound[p.u].forEach(f => inbound[p.final].add(f)); }
  const crawlDone = !queue.length;
  if (crawlDone) for (const p of live) if (p.inSm && !p.noindex && !(inbound[p.u] && [...inbound[p.u]].some(f => f !== p.u)) && !/\/(en|ua|de|fr|es|it|pt)\/$/.test(p.u)) p.issues.push(['🟡', 'сирота: ни одна страница сайта на неё не ссылается']);
  for (const p of pages.values()) { if (p.redirect && inbound[p.u]) for (const f of [...inbound[p.u]].slice(0, 1)) { const fp = pages.get(f); if (fp) fp.issues.push([p.chain.length > 1 ? '🟠' : '🟡', `ссылка ведёт через переадресацию${p.chain.length > 1 ? ' (цепочка ' + p.chain.length + ' шагов)' : ''}: ${short(p.u)} → ${short(p.final)}`]); }
    if (p.redirect && p.inSm) p.issues.push(['🟠', 'в карте сайта адрес с переадресацией → ' + short(p.final)]);
    if ((!p.st || p.st >= 400) && inbound[p.u]) for (const f of [...inbound[p.u]].slice(0, 2)) { const fp = pages.get(f); if (fp) fp.issues.push(['🔴', `битая ссылка на ${short(p.u)} (${p.st || 'нет ответа'})`]); } }
  // E. hreflang: back-links, x-default, languages
  const langSeen = new Set(); let hlBad = 0;
  for (const p of live) { if (!p.hl.length) { if (!p.noindex && !p.util && /\/(en|ua|de|fr|es|it|pt)\//.test(p.u)) p.issues.push(['🟡', 'нет ссылок на языковые версии (hreflang)']); continue; }
    p.hl.forEach(([l]) => langSeen.add(l)); if (!p.hl.some(([l]) => l === 'x-default')) p.issues.push(['⚪', 'нет hreflang x-default']);
    for (const [l, t] of p.hl) { if (t === p.u) continue; const tp = pages.get(t); if (tp && tp.hl.length && !tp.hl.some(([, b]) => b === p.u)) { hlBad++; p.issues.push(['🟠', `языковая версия ${l} не ссылается обратно (${short(t)})`]); break; } } }
  // F. images (first 120 distinct)
  let heavy = [], oldFmt = 0, checkedImg = 0;
  for (const [src, from] of [...imgs].slice(0, 120)) { const r = await hop(src); await sleep(300); checkedImg++;
    if (r.len > 250 * 1024) heavy.push([src, Math.round(r.len / 1024), from]); if (/image\/(jpeg|png)/.test(r.type) && r.len > 60 * 1024) oldFmt++; }
  heavy.sort((a, b) => b[1] - a[1]);
  // G. URL order
  const G = [];
  const chk = async (u, want, label) => { const r = await hop(u); const ok = r.st === 301 && norm(r.loc || '') === norm(want); if (!ok) G.push(['🟠', `${label}: ${u} → ${r.st}${r.loc ? ' ' + r.loc : ''} (нужно 301 сразу на ${want})`]); };
  await chk('http://solid-sound.net/en/', BASE + '/en/', 'http → https');
  await chk('https://www.solid-sound.net/en/', BASE + '/en/', 'www → без www');
  await chk('http://www.solid-sound.net/en/faq/', BASE + '/en/faq/', 'http://www → https');
  await chk(BASE + '/en/faq', BASE + '/en/faq/', 'без слэша на конце');
  const up = await hop(BASE + '/EN/FAQ/'); if (up.st === 200) G.push(['🟡', 'адрес заглавными буквами (/EN/FAQ/) открывает страницу без переадресации — дубль']);
  for (const [k, from] of Object.entries(paramLinks)) if (/currency|add-to-cart/.test(k)) G.push(['🟡', `ссылки с параметром ${k}= на ${from.size} страницах (например ${[...from][0]}) — Google видит дубли; закрыть такие адреса от индекса или убрать из ссылок`]);
  const curP = await hop(BASE + '/en/faq/?currency=USD'); if (curP.st === 200) { const c = attr(([...((curP.text || '').match(/<link\b[^>]*canonical[^>]*>/gi) || [])][0]) || '', 'href'); if (!c || norm(c) !== BASE + '/en/faq/') G.push(['🟠', '?currency= страницы без canonical на чистый адрес']); }
  // report
  const all = [...pages.values()].flatMap(p => p.issues.map(i => [i[0], i[1], p.u]));
  const sev = s => all.filter(i => i[0] === s).length + G.filter(g => g[0] === s).length;
  const groups = {}; for (const [s, t, u] of all) { const k = s + ' ' + t.replace(/\(\d+ (зн|слов)[^)]*\)|\d+/g, '#').replace(/: .*/, '').replace(/ \(.*\)$/, ''); (groups[k] = groups[k] || { s, t: t.replace(/: .*/, '').replace(/ \((цепочка[^)]*|\/[^)]*)\)/, ''), u: [] }).u.push(u); }
  const order = { '🔴': 0, '🟠': 1, '🟡': 2, '⚪': 3 };
  const missing = live.filter(p => !p.inSm && !p.noindex && !p.util && p.canon === p.u).map(p => short(p.u));
  let md = `## 🔎 SEO: полная проверка глазами Google\n\nСтраниц обойдено: **${pages.size}** (из карты сайта ${sm.size} + найдены по ссылкам)${pages.size >= MAX ? ', лимит ' + MAX : ''}. Картинок проверено: ${checkedImg}. 🔴 ${sev('🔴')} · 🟠 ${sev('🟠')} · 🟡 ${sev('🟡')} · ⚪ ${sev('⚪')}\n\n`;
  md += `robots.txt: ${robots.st === 200 ? '✅' + (/sitemap/i.test(robots.text) ? ' (карта сайта указана)' : ' ⚠️ нет строки Sitemap') : '🔴 не открывается'} · карта сайта: ${sm.size ? '✅ ' + sm.size + ' адресов' : '🔴 не найдена'} · языки в hreflang: ${[...langSeen].sort().join(', ') || 'нет'}${LANGS.filter(l => !langSeen.has(l) && !langSeen.has(l === 'ua' ? 'uk' : l)).length ? ' (нет: ' + LANGS.filter(l => !langSeen.has(l) && !langSeen.has(l === 'ua' ? 'uk' : l)).join(', ') + ')' : ''}\n\n`;
  md += '### Проблемы на страницах\n\n| | Проблема | Страниц | Где |\n|---|---|---|---|\n' + Object.values(groups).sort((a, b) => order[a.s] - order[b.s] || b.u.length - a.u.length)
    .map(g => `| ${g.s} | ${esc(g.t)} | ${g.u.length} | ${g.u.slice(0, 8).map(short).join(', ')}${g.u.length > 8 ? ' …' : ''} |`).join('\n') + '\n\n';
  if (missing.length) md += `### 🟠 Нет в карте сайта (${missing.length})\nОткрыты для Google, но не перечислены в карте сайта:\n` + missing.slice(0, 40).map(u => '- ' + u).join('\n') + '\n\n';
  if (G.length) md += '### Порядок в адресах\n' + G.map(g => `- ${g[0]} ${esc(g[1])}`).join('\n') + '\n\n';
  if (heavy.length || oldFmt) md += `### Картинки\n- Тяжелее 250 КБ: **${heavy.length}**${heavy.length ? ' — ' + heavy.slice(0, 8).map(x => `${x[0].split('/').pop()} ${x[1]} КБ (${short(x[2])})`).join(', ') : ''}\n- JPEG/PNG больше 60 КБ (в WebP были бы в 2–3 раза легче): **${oldFmt}**\n\n`;
  const dupT = Object.entries(byTitle).filter(([t, l]) => t && l.length > 1), dupD = Object.entries(byDesc).filter(([, l]) => l.length > 1);
  if (dupT.length) md += '### Один заголовок на нескольких страницах\n' + dupT.slice(0, 15).map(([t, l]) => `- «${esc(t)}» — ${l.map(short).join(', ')}`).join('\n') + '\n\n';
  if (dupD.length) md += '### Одно описание на нескольких страницах\n' + dupD.slice(0, 10).map(([t, l]) => `- «${esc(t.slice(0, 70))}…» — ${l.length} стр.: ${l.slice(0, 4).map(short).join(', ')}`).join('\n') + '\n\n';
  if (!crawlDone) md += `_Обход остановлен на лимите ${MAX} страниц — «сироты» не считались (нужен полный обход)._\n\n`;
  md += '_Что видит сам Google (индекс, запросы, позиции, скорость у посетителей) — робот Search Console, ждёт ключ `GOOGLE_SA_JSON` (`docs/keys-todo.md`)._\n';
  return { md, metrics: { seo_pages: pages.size, seo_red: sev('🔴'), seo_orange: sev('🟠'), seo_missing_sitemap: missing.length, seo_hreflang_bad: hlBad, seo_heavy_img: heavy.length, seo_dup_titles: dupT.length },
    alerts: sev('🔴') ? [`SEO: ${sev('🔴')} серьёзных проблем (см. раздел SEO)`] : [] };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
