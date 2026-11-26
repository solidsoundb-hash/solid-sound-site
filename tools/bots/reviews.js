// REVIEWS ROBOT (Analyst, 26.11.2026, ok from «Задачи»): what competitors' customers praise and complain about.
// Only PUBLIC product pages, robots.txt respected, pause per host, request cap, no protection bypass.
// NO buyer names in the repo: only the gist of each review (names, e-mails, @handles, links removed) + themes.
//   MODE=probe  node tools/bots/reviews.js   -> which review widgets a shop has (step 1 of docs/team/bots.md)
//   MODE=run    node tools/bots/reviews.js   -> read reviews, count themes -> reports/analyst/reviews.md + .json
// Shops: tools/bots/reviews.json ("shops", ladder step 1 = one shop).
'use strict';
const fs = require('fs'), path = require('path');
const ROOT = path.join(__dirname, '..', '..');
const CFG = JSON.parse(fs.readFileSync(path.join(__dirname, 'reviews.json'), 'utf8'));
const OUT = path.join(ROOT, 'reports', 'analyst');
const UA = 'SolidSoundIntel/1.0 (+https://solid-sound.net; polite market research bot)';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));

// ---------- text helpers (exam: tools/bots/reviews/exam.json) ----------
const dec = (t) => String(t || '').replace(/&#(\d+);/g, (_, n) => String.fromCharCode(+n)).replace(/&#x([0-9a-f]+);/gi, (_, n) => String.fromCharCode(parseInt(n, 16)))
  .replace(/&amp;/g, '&').replace(/&quot;/g, '"').replace(/&#39;|&apos;/g, "'").replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&nbsp;/g, ' ');
const strip = (h) => dec(String(h || '').replace(/<br\s*\/?>/gi, ' ').replace(/<[^>]+>/g, ' ')).replace(/\s+/g, ' ').trim();
// Gist without personal data: no e-mails, links, @handles, phone numbers, sign-offs like "- John" / "Thanks, Mike D."
function scrub(t) {
  let s = strip(t);
  s = s.replace(/[\w.+-]+@[\w-]+\.[\w.]+/g, '[email]').replace(/https?:\/\/\S+|www\.\S+/gi, '[link]').replace(/@\w{2,}/g, '@[user]')
    .replace(/\+?\d[\d ()-]{7,}\d/g, '[phone]')
    .replace(/(?:^|[\s.!])[-–—~]\s*[A-Z][a-z]+(?:\s+[A-Z][a-z]*\.?)?\s*$/, '.')
    .replace(/\b(cheers|thanks|thank you|regards|best|love)\s*,\s*[A-Z][a-z]+(?:\s+[A-Z]\.?)?\s*[.!]?\s*$/i, '$1.');
  s = s.replace(/\s+/g, ' ').trim();
  return s.length > 240 ? s.slice(0, 237).replace(/\s+\S*$/, '') + '…' : s;
}
function themes(text) {
  const t = ' ' + String(text).toLowerCase() + ' '; const out = [];
  for (const [name, words] of Object.entries(CFG.themes)) if (words.some(w => t.includes(w))) out.push(name);
  return out;
}
// Reviews from a product page: schema.org JSON-LD + Judge.me pre-rendered widget. Only rating, title, body, date.
function parseReviews(html) {
  const out = [];
  for (const m of String(html).matchAll(/<script[^>]+application\/ld\+json[^>]*>([\s\S]*?)<\/script>/gi)) {
    let j; try { j = JSON.parse(m[1].trim()); } catch (e) { continue; }
    const stack = Array.isArray(j) ? j.slice() : [j];
    while (stack.length) {
      const o = stack.pop(); if (!o || typeof o !== 'object') continue;
      if (Array.isArray(o)) { stack.push(...o); continue; }
      if (o['@graph']) stack.push(...[].concat(o['@graph']));
      for (const r of [].concat(o.review || o.reviews || [])) {
        if (!r || !r.reviewBody) continue;
        out.push({ rating: +((r.reviewRating || {}).ratingValue) || null, title: strip(r.name || r.headline || ''), body: String(r.reviewBody), date: String(r.datePublished || '').slice(0, 10), src: 'json-ld' });
      }
      if (o.aggregateRating && !out.agg) out.agg = { rating: +o.aggregateRating.ratingValue || null, count: +(o.aggregateRating.reviewCount || o.aggregateRating.ratingCount) || null };
    }
  }
  const parts = String(html).split(/<div[^>]+class=['"](?=[^'"]*\bjdgm-rev\b)(?![^'"]*jdgm-rev__)[^'"]*['"]/i).slice(1);
  for (const b of parts) {
    const body = (b.match(/class=['"][^'"]*jdgm-rev__body[^'"]*['"][^>]*>([\s\S]*?)<\/div>/i) || [])[1];
    if (!body || !strip(body)) continue;
    out.push({ rating: +((b.match(/jdgm-rev__rating[^>]*data-score=['"](\d)/i) || [])[1]) || null, title: strip((b.match(/class=['"][^'"]*jdgm-rev__title[^'"]*['"][^>]*>([\s\S]*?)<\//i) || [])[1]),
      body, date: ((b.match(/jdgm-rev__timestamp[^>]*data-content=['"]([\d-]{10})/i) || [])[1]) || '', src: 'judgeme' });
  }
  return out;
}
const WIDGETS = { judgeme: /judge\.me|jdgm-/i, loox: /loox\.io/i, okendo: /okendo/i, stamped: /stamped\.io/i, yotpo: /yotpo/i, reviewsio: /reviews\.io|reviewsio/i, trustpilot: /trustpilot/i, spr: /shopify-product-reviews|spr-review/i, woo: /woocommerce-Reviews|commentlist/i };
function widgets(html) { return Object.keys(WIDGETS).filter(k => WIDGETS[k].test(html)); }

if (require.main !== module) { module.exports = { scrub, themes, parseReviews, widgets }; return; }

// ---------- polite fetch (same rules as intel.js) ----------
const MODE = process.env.MODE || 'probe';
let budget = CFG.maxRequests || 40; const lastHit = {}; const robotsCache = {};
async function rawGet(url) { const r = await fetch(url, { headers: { 'User-Agent': UA, Accept: '*/*' }, redirect: 'follow', signal: AbortSignal.timeout(30000) }); return { status: r.status, text: await r.text() }; }
async function robotsOk(u) {
  const { origin, pathname, search } = new URL(u);
  if (!(origin in robotsCache)) {
    let rules = [];
    try {
      const r = await rawGet(origin + '/robots.txt');
      if (r.status === 200) { let mine = false, any = false; const all = [], star = [];
        for (const line of r.text.split(/\r?\n/)) { const l = line.replace(/#.*/, '').trim(); if (!l) continue; const [k, ...rest] = l.split(':'); const v = rest.join(':').trim(); const key = k.toLowerCase();
          if (key === 'user-agent') { mine = /solidsoundintel/i.test(v); any = v === '*'; continue; }
          if ((key === 'disallow' || key === 'allow') && (mine || any)) (mine ? all : star).push({ allow: key === 'allow', p: v }); }
        rules = all.length ? all : star; }
    } catch (e) { /* no robots.txt = allowed */ }
    robotsCache[origin] = rules;
  }
  const target = pathname + search; let best = null;
  for (const r of robotsCache[origin]) { if (!r.p) continue;
    const re = new RegExp('^' + r.p.replace(/[.+?^${}()|[\]\\]/g, '\\$&').replace(/\*/g, '.*').replace(/\\\$$/, '$'));
    if (re.test(target) && (!best || r.p.length > best.p.length || (r.p.length === best.p.length && r.allow))) best = r; }
  return !best || best.allow;
}
async function get(url) {
  if (budget <= 0) return { status: 0, error: 'лимит запросов', text: '' };
  if (!(await robotsOk(url))) return { status: 0, error: 'robots.txt запрещает', text: '' };
  const host = new URL(url).host; const wait = (lastHit[host] || 0) + (CFG.pauseMs || 4000) - Date.now(); if (wait > 0) await sleep(wait);
  budget--; lastHit[host] = Date.now();
  try { const r = await rawGet(url);
    if (r.status === 429 || r.status === 403 || /cf-chl|captcha/i.test(r.text.slice(0, 3000))) return { status: r.status, error: 'сайт ограничил доступ (не обходим)', text: '' };
    return r; } catch (e) { return { status: 0, error: e.message.slice(0, 80), text: '' }; }
}
const json = (r) => { try { return JSON.parse(r.text); } catch (e) { return null; } };

async function handles(shop) {
  const o = 'https://' + shop.domain; const list = [];
  const b = json(await get(o + '/collections/best-sellers/products.json?limit=' + (shop.products || 8)));
  for (const p of (b && b.products) || []) list.push({ id: p.id, handle: p.handle, title: dec(p.title), price: +((p.variants || [])[0] || {}).price || null, best: true });
  if (list.length < (shop.products || 8)) { const a = json(await get(o + '/products.json?limit=' + (shop.products || 8)));
    for (const p of (a && a.products) || []) if (!list.some(x => x.handle === p.handle)) list.push({ id: p.id, handle: p.handle, title: dec(p.title), price: +((p.variants || [])[0] || {}).price || null }); }
  return list.slice(0, shop.products || 8);
}

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const shops = CFG.shops.slice(0, CFG.step === 1 ? 1 : CFG.shops.length);
  const now = new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC';
  const L = [`# Отзывы покупателей конкурентов — ${now} (${MODE})`, '', 'Только публичные страницы товаров, без имён покупателей — только суть отзыва и темы.', ''];
  const data = { date: now.slice(0, 10), mode: MODE, shops: [] };
  for (const shop of shops) {
    const hs = await handles(shop); const S = { name: shop.name, products: [], widgets: {}, reviews: [], themes: {}, low: 0 };
    L.push(`## ${shop.name}`, '', '| Товар | Цена | Ответ | Виджеты | Отзывов на странице | Рейтинг (всего) |', '|---|---|---|---|---|---|');
    for (const h of hs) {
      const r = await get(`https://${shop.domain}/products/${h.handle}`);
      const w = r.text ? widgets(r.text) : []; w.forEach(k => S.widgets[k] = (S.widgets[k] || 0) + 1);
      const rv = r.text ? parseReviews(r.text) : [];
      S.products.push({ title: h.title, price: h.price, best: !!h.best, status: r.status, widgets: w, found: rv.length, agg: rv.agg || null });
      L.push(`| ${h.best ? '🔥 ' : ''}${h.title} | ${h.price != null ? '$' + h.price : '—'} | ${r.status === 200 ? '✅' : '❌ ' + (r.error || r.status)} | ${w.join(', ') || '—'} | ${rv.length} | ${rv.agg ? (rv.agg.rating || '—') + ' ★ / ' + (rv.agg.count || '—') : '—'} |`);
      if (MODE === 'probe') {
        if (!S.diag && r.text) { S.diag = 1; L.push('', '<details><summary>Метки отзывов на странице «' + h.title + '»</summary>', '');
          const seen = new Set();
          for (const m of r.text.matchAll(/(?:src|href|data-[\w-]+)=['"]([^'"]*(?:judge\.me|jdgm|loox|okendo|yotpo|stamped|reviews\.io)[^'"]*)['"]/gi)) { const v = m[1].slice(0, 160); if (!seen.has(v) && seen.size < 15) { seen.add(v); L.push('- `' + v.replace(/`/g, '') + '`'); } }
          for (const m of r.text.matchAll(/class=['"]([^'"]*(?:jdgm|loox|oke-|yotpo)[^'"]*)['"]/gi)) { const v = 'class: ' + m[1].slice(0, 100); if (!seen.has(v) && seen.size < 30) { seen.add(v); L.push('- `' + v + '`'); } }
          const lx = r.text.match(/loox\.io\/widget\/([\w-]+)\/loox(?:\.(\d+))?\.js/);
          if (lx && h.id) { const u = `https://loox.io/widget/${lx[1]}/reviews/${h.id}?h=${lx[2] || ''}&page=1`; const q = await get(u);
            L.push('', `Loox: страница отзывов → ${q.status === 200 ? '✅' : '❌ ' + (q.error || q.status)}, ${q.text.length} байт`);
            const cls = {}; for (const m of q.text.matchAll(/class=['"]([^'"]+)['"]/g)) for (const c of m[1].split(/\s+/)) cls[c] = (cls[c] || 0) + 1;
            L.push('', 'Классы разметки (частота): ' + Object.entries(cls).sort((a, b) => b[1] - a[1]).slice(0, 45).map(([k, v]) => k + '×' + v).join(', '));
            const attrs = {}; for (const m of q.text.matchAll(/\s(data-[\w-]+|aria-label)=/g)) attrs[m[1]] = (attrs[m[1]] || 0) + 1;
            L.push('', 'Атрибуты: ' + Object.entries(attrs).map(([k, v]) => k + '×' + v).join(', ')); }
          L.push('', '</details>', ''); }
        continue;
      }
      for (const x of rv) { const gist = scrub((x.title ? x.title + '. ' : '') + x.body); if (!gist) continue; const th = themes(gist);
        th.forEach(t => S.themes[t] = (S.themes[t] || 0) + 1); if (x.rating && x.rating <= 3) S.low++;
        S.reviews.push({ product: h.title, rating: x.rating, date: x.date, gist, themes: th }); }
    }
    if (MODE !== 'probe') {
      const top = Object.entries(S.themes).sort((a, b) => b[1] - a[1]);
      L.push('', `**Всего отзывов прочитано:** ${S.reviews.length}, из них на 1–3 ★: ${S.low}.`, '', '**О чём пишут (темы):** ' + (top.map(([k, v]) => `${k} ${v}`).join(' · ') || '—'), '');
      const lows = S.reviews.filter(x => x.rating && x.rating <= 3).slice(0, 8);
      if (lows.length) { L.push('**Жалобы (суть, по-английски как у покупателя):**'); lows.forEach(x => L.push(`- ${x.rating}★ «${x.gist}» — ${x.product}`)); L.push(''); }
      L.push('**Примеры похвалы:**'); S.reviews.filter(x => x.rating >= 5).slice(0, 6).forEach(x => L.push(`- «${x.gist}» — ${x.product}`)); L.push('');
    }
    data.shops.push(S); L.push('');
  }
  L.push(`_Запросов: ${(CFG.maxRequests || 40) - budget}, пауза ${CFG.pauseMs || 4000} мс на сайт._`);
  const name = MODE === 'probe' ? 'reviews-probe' : 'reviews';
  fs.writeFileSync(path.join(OUT, name + '.md'), L.join('\n') + '\n');
  fs.writeFileSync(path.join(OUT, name + '.json'), JSON.stringify(data, null, 1));
  console.log(L.join('\n'));
})();
