// SEO PAGES (owner 21.11.2026): every page in the sitemap, the way Google reads it — status, title, description, H1,
// canonical, language versions (hreflang), noindex, social picture, pictures without alt text, product data (price,
// stock, rating) — plus robots.txt and the sitemap itself, and titles/descriptions repeated on several pages.
// (The Search Console bot tools/bots/seo.js shows what people search; this one checks the pages themselves.)
'use strict';
const { BASE, get, sleep, esc } = require('../bots/lib');
const MAX = +process.env.SEO_MAX || 160;
const attr = (tag, a) => { const m = tag.match(new RegExp(a + '\\s*=\\s*"([^"]*)"', 'i')) || tag.match(new RegExp(a + "\\s*=\\s*'([^']*)'", 'i')); return m ? m[1] : null; };
const dec = s => String(s || '').replace(/&amp;/g, '&').replace(/&#0?39;|&#8217;/g, "'").replace(/&quot;/g, '"').replace(/&#8211;|&ndash;/g, '–').replace(/&#8212;/g, '—').replace(/<[^>]+>/g, '').trim();
const norm = u => { try { const x = new URL(u, BASE); return x.origin + x.pathname.replace(/\/?$/, '/'); } catch (e) { return u; } };

async function sitemapUrls() {
  const out = new Set(); const seen = new Set();
  const read = async (u, depth) => { if (seen.has(u) || depth > 2) return; seen.add(u); const r = await get(u); await sleep(800);
    for (const m of (r.text || '').matchAll(/<loc>\s*([^<\s]+)\s*<\/loc>/g)) { const l = dec(m[1]); if (/\.xml(\?|$)/.test(l)) await read(l, depth + 1); else out.add(l); } };
  for (const u of ['/wp-sitemap.xml', '/sitemap_index.xml', '/sitemap.xml']) { await read(BASE + u, 0); if (out.size) break; }
  return [...out];
}

module.exports = async function seoPages() {
  const robots = await get(BASE + '/robots.txt');
  const urls = (await sitemapUrls()).slice(0, MAX);
  const pages = []; const byTitle = {}, byDesc = {};
  for (const u of urls) {
    const r = await get(u, { tries: 3 }); await sleep(1200);
    const h = r.text || ''; const head = (h.match(/<head[\s\S]*?<\/head>/i) || [''])[0]; const P = { u, st: r.status, issues: [] };
    const I = (sev, t) => P.issues.push([sev, t]);
    if (!r.status || r.status >= 400) { I('🔴', 'страница не открывается (' + (r.status || r.error) + ')'); pages.push(P); continue; }
    if (norm(r.url) !== norm(u)) I('🟠', 'в карте сайта адрес с переадресацией → ' + r.url.replace(BASE, ''));
    const title = dec((head.match(/<title[^>]*>([\s\S]*?)<\/title>/i) || [])[1]);
    const metas = [...head.matchAll(/<meta\b[^>]*>/gi)].map(m => m[0]); const links = [...head.matchAll(/<link\b[^>]*>/gi)].map(m => m[0]);
    const meta = (k, v) => { const t = metas.find(m => (attr(m, k) || '').toLowerCase() === v); return t ? dec(attr(t, 'content')) : null; };
    const desc = meta('name', 'description'), robotsMeta = meta('name', 'robots') || '', og = meta('property', 'og:image');
    const can = (links.find(l => /rel="canonical"/i.test(l)) || ''); const canon = attr(can, 'href');
    const hl = links.filter(l => /hreflang=/i.test(l)).map(l => [attr(l, 'hreflang'), attr(l, 'href')]);
    const h1 = (h.match(/<h1\b/gi) || []).length;
    const imgs = [...h.matchAll(/<img\b[^>]*>/gi)].map(m => m[0]); const noAlt = imgs.filter(i => !/\balt\s*=/.test(i)).length;
    if (/noindex/i.test(robotsMeta)) I('🔴', 'закрыта от Google (noindex), а стоит в карте сайта');
    if (!title) I('🔴', 'нет заголовка'); else if (title.length > 65) I('🟡', `заголовок длинный (${title.length} зн., Google обрежет после ~60)`); else if (title.length < 20) I('🟡', `заголовок короткий (${title.length} зн.)`);
    if (!desc) I('🟠', 'нет описания (description) — Google возьмёт случайный кусок'); else if (desc.length > 165) I('🟡', `описание длинное (${desc.length} зн.)`); else if (desc.length < 70) I('🟡', `описание короткое (${desc.length} зн.)`);
    if (!canon) I('🟠', 'нет canonical'); else if (norm(canon) !== norm(r.url)) I('🟠', 'canonical указывает на другую страницу: ' + canon.replace(BASE, ''));
    if (!h1) I('🟡', 'нет заголовка H1'); else if (h1 > 1) I('⚪', `H1 несколько (${h1})`);
    if (!og) I('🟡', 'нет картинки для соцсетей (og:image)');
    if (noAlt > 2) I('⚪', `картинок без подписи (alt): ${noAlt}`);
    if (!hl.length && /\/(en|ua|de|fr|es|it|pt)\//.test(u)) I('🟡', 'нет ссылок на языковые версии (hreflang)');
    if (/\/product\//.test(u)) {
      let prod = null; for (const m of h.matchAll(/<script[^>]+application\/ld\+json[^>]*>([\s\S]*?)<\/script>/g)) { try { const j = JSON.parse(m[1]); for (const x of [].concat(j['@graph'] || j)) if (/Product/.test(x['@type'])) prod = x; } catch (e) { I('🟠', 'ошибка в данных для Google (JSON-LD не читается)'); } }
      if (!prod) I('🔴', 'товар без данных для Google (нет Product) — не будет цены и звёзд в поиске');
      else { const o = [].concat(prod.offers || [])[0]; if (!o) I('🔴', 'у товара нет цены для Google (offers)'); else if (!o.availability) I('🟡', 'нет «в наличии» (availability)');
        if (!prod.image) I('🟠', 'нет картинки товара для Google'); if (!prod.aggregateRating) I('⚪', 'нет рейтинга (звёзды в Google появятся только с отзывами)'); }
    }
    P.title = title; P.desc = desc; (byTitle[title] = byTitle[title] || []).push(u); if (desc) (byDesc[desc] = byDesc[desc] || []).push(u);
    pages.push(P);
  }
  const dupT = Object.entries(byTitle).filter(([t, l]) => t && l.length > 1), dupD = Object.entries(byDesc).filter(([, l]) => l.length > 1);
  const all = pages.flatMap(p => p.issues.map(i => [i[0], i[1], p.u]));
  const sev = s => all.filter(i => i[0] === s).length;
  let md = `## 🔎 SEO: страницы глазами Google\n\nПроверено страниц из карты сайта: **${pages.length}**${urls.length >= MAX ? ' (первые ' + MAX + ')' : ''}. 🔴 ${sev('🔴')} · 🟠 ${sev('🟠')} · 🟡 ${sev('🟡')} · ⚪ ${sev('⚪')}\n\n`;
  md += `robots.txt: ${robots.status === 200 ? '✅' + (/sitemap/i.test(robots.text) ? ' (карта сайта указана)' : ' ⚠️ нет строки Sitemap') : '🔴 не открывается'} · карта сайта: ${urls.length ? '✅ ' + urls.length + ' адресов' : '🔴 не найдена'}\n\n`;
  // group the same problem across pages
  const groups = {}; for (const [s, t, u] of all) { const k = s + ' ' + t.replace(/\(\d+ зн\.[^)]*\)|\d+/g, '#').replace(/: .*/, ''); (groups[k] = groups[k] || { s, t, u: [] }).u.push(u); }
  const order = { '🔴': 0, '🟠': 1, '🟡': 2, '⚪': 3 };
  md += '| | Проблема | Страниц | Примеры |\n|---|---|---|---|\n' + Object.values(groups).sort((a, b) => order[a.s] - order[b.s] || b.u.length - a.u.length)
    .map(g => `| ${g.s} | ${esc(g.t)} | ${g.u.length} | ${g.u.slice(0, 8).map(u => u.replace(BASE, '')).join(', ')}${g.u.length > 8 ? ' …' : ''} |`).join('\n') + '\n\n';
  if (dupT.length) md += '### Один заголовок на нескольких страницах\n' + dupT.slice(0, 15).map(([t, l]) => `- «${esc(t)}» — ${l.map(u => u.replace(BASE, '')).join(', ')}`).join('\n') + '\n\n';
  if (dupD.length) md += '### Одно описание на нескольких страницах\n' + dupD.slice(0, 10).map(([t, l]) => `- «${esc(t.slice(0, 70))}…» — ${l.length} стр.: ${l.slice(0, 4).map(u => u.replace(BASE, '')).join(', ')}`).join('\n') + '\n\n';
  return { md, metrics: { seo_pages: pages.length, seo_red: sev('🔴'), seo_orange: sev('🟠'), seo_dup_titles: dupT.length }, alerts: sev('🔴') ? [`SEO: ${sev('🔴')} серьёзных проблем на страницах`] : [] };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
