// BUNDLE-WATCH (Analyst, 26.11.2026, owner via «Задачи»): competitors' bundles — what's inside, price,
// discount vs buying the parts one by one, genres. Public catalogs only (tools/bots/lib/shop.js rules).
//   MODE=probe node tools/bots/bundle-watch.js  -> 1 shop (training, docs/team/bots.md step 1)
//   MODE=run   node tools/bots/bundle-watch.js  -> all shops in tools/bots/watch.json -> reports/analyst/bundles.md + .json
'use strict';
const fs = require('fs'), path = require('path');
const CFG = JSON.parse(fs.readFileSync(path.join(__dirname, 'watch.json'), 'utf8'));
const OUT = path.join(__dirname, '..', '..', 'reports', 'analyst');

const BUNDLE = new RegExp(CFG.bundleWords.join('|'), 'i');
const NOT = new RegExp(CFG.notBundle.join('|'), 'i');
function isBundle(it) { const t = it.title + ' ' + (it.tags || ''); return BUNDLE.test(t) && !NOT.test(it.title) && it.price > 1; }
// How many items the title/description promises ("5 templates", "10-in-1", "x4 projects")
function promised(it) {
  const t = (it.title + ' ' + (it.body || '').slice(0, 600)).toLowerCase();
  const m = t.match(/(\d{1,3})\s*(?:-|\s)?(?:in\s*-?\s*1\b|(?:ableton |fl studio |logic )?(?:templates?|projects?|packs?|sample packs?|construction kits?|kits?|preset packs?|products?)\b)/) || t.match(/\bx\s?(\d{1,2})\b/);
  const n = m ? +m[1] : 0; return n >= 2 && n <= 300 ? n : 0;
}
const norm = (s) => String(s).toLowerCase().replace(/&/g, 'and').replace(/[^a-z0-9]+/g, ' ').trim();
// Parts = other catalog items whose title appears in the bundle's description.
function parts(b, cat) {
  const body = ' ' + norm(b.body) + ' ', out = [];
  for (const it of cat) { if (it.id === b.id || isBundle(it) || it.price <= 1) continue; const t = norm(it.title);
    if (t.length >= 6 && t.split(' ').length >= 2 && body.includes(' ' + t + ' ') && !out.some(o => o.id === it.id)) out.push(it); }
  return out;
}
function genresOf(text) { const t = ' ' + String(text).toLowerCase() + ' '; const g = CFG.genres.filter(x => t.includes(x)); return g.filter(x => !g.some(y => y !== x && y.includes(x))); }
// Genres of a product: title first; shop tags / description only when they are specific (shops add long SEO genre lists)
// Discount the shop itself claims in the title: "(75% OFF)", "SAVE 70%", "-50%"
function offClaim(title) { const m = String(title).match(/(\d{1,2})\s?%\s?off|save\s?(\d{1,2})\s?%|[-\u2212\u2013]\s?(\d{1,2})\s?%/i); return m ? +(m[1] || m[2] || m[3]) : null; }
function genresBest(it) { const t = genresOf(it.title); if (t.length) return t; const g = genresOf(it.tags || ''); if (g.length && g.length <= 2) return g; const b = genresOf((it.body || '').slice(0, 1500)); return b.length && b.length <= 3 ? b : []; }
function kind(text) { const t = String(text).toLowerCase(); return /template|project|\.als|ableton live set|remake|track in/.test(t) ? 'шаблоны/проекты' : /preset|serum|vital|sylenth/.test(t) ? 'пресеты' : /course|masterclass|lesson/.test(t) ? 'курсы' : 'сэмплы/паки'; }

if (require.main !== module) { module.exports = { isBundle, promised, parts, genresOf, genresBest, kind, offClaim }; return; }

const MODE = process.env.MODE || 'run';
const S = require('./lib/shop.js').make({ maxRequests: CFG.maxRequests, pauseMs: CFG.pauseMs });
(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const shops = MODE === 'probe' ? CFG.shops.slice(0, 1) : CFG.shops;
  const now = new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC';
  const L = [`# Бандлы конкурентов — ${now} (${MODE})`, '', 'Публичные каталоги. «Скидка к частям» = 1 − цена бандла / сумма цен найденных частей (если части нашлись в описании).', ''];
  const data = { date: now.slice(0, 10), shops: [] }; const all = [];
  for (const shop of shops) {
    const c = await S.catalog(shop, shop.type === 'woo' ? 3 : 4);
    if (c.error) { L.push(`## ${shop.name}`, '', `❌ ${c.error}`, ''); data.shops.push({ name: shop.name, error: c.error }); continue; }
    let cat = c.items;
    if (shop.type === 'woo') for (const q of ['bundle', 'collection']) for (const it of await S.wooSearch(shop, q)) if (!cat.some(x => x.id === it.id)) cat.push(it);
    const bundles = cat.filter(isBundle).map(b => {
      const p = parts(b, cat), sum = p.reduce((a, x) => a + x.price, 0), txt = b.title + ' ' + b.tags + ' ' + b.body.slice(0, 1500);
      return { shop: shop.name, title: b.title, url: b.url, price: b.price, was: b.cmp || null, created: b.created, items: p.length || promised(b) || null,
        parts: p.slice(0, 12).map(x => x.title + ' $' + x.price), sumParts: p.length >= 2 ? +sum.toFixed(2) : null,
        offParts: p.length >= 2 && sum > b.price ? Math.round((1 - b.price / sum) * 100) : null, offWas: b.cmp > b.price ? Math.round((1 - b.price / b.cmp) * 100) : offClaim(b.title),
        genres: genresBest(b), kind: kind(txt) };
    }).sort((a, b) => (b.created || '').localeCompare(a.created || '') || b.price - a.price);
    all.push(...bundles); data.shops.push({ name: shop.name, catalog: cat.length, bundles });
    L.push(`## ${shop.name} — бандлов ${bundles.length} из ${cat.length} товаров`, '');
    if (bundles.length) { L.push('| Бандл | Что | Цена | Было | Частей | Сумма частей | Скидка к частям | Жанры |', '|---|---|---|---|---|---|---|---|');
      bundles.slice(0, MODE === 'probe' ? 40 : 25).forEach(b => L.push(`| [${b.title.replace(/\|/g, '/')}](${b.url}) | ${b.kind} | $${b.price} | ${b.was ? '$' + b.was + ' (−' + b.offWas + '%)' : '—'} | ${b.items || '?'} | ${b.sumParts ? '$' + b.sumParts : '—'} | ${b.offParts != null ? '−' + b.offParts + '%' : '—'} | ${b.genres.join(', ') || '—'} |`));
      L.push(''); }
  }
  // Summary for the owner's bundle decision
  const tpl = all.filter(b => b.kind === 'шаблоны/проекты');
  const med = (a) => { const s = a.filter(x => x != null).sort((x, y) => x - y); return s.length ? s[Math.floor(s.length / 2)] : null; };
  const perItem = tpl.filter(b => b.items).map(b => +(b.price / b.items).toFixed(2));
  const FOCUS = CFG.focus; const fb = all.filter(b => b.genres.some(g => FOCUS.some(f => g.includes(f)))).sort((a, b) => a.price - b.price);
  L.push(`## Бандлы house / techno (${fb.length}) — от дешёвых к дорогим`, '', '| Магазин | Бандл | Что | Цена | Скидка | Частей | Жанры |', '|---|---|---|---|---|---|---|');
  fb.slice(0, 40).forEach(b => L.push(`| ${b.shop} | [${b.title.replace(/\|/g, '/')}](${b.url}) | ${b.kind} | $${b.price} | ${b.offParts != null ? '−' + b.offParts + '% к частям' : b.offWas != null ? '−' + b.offWas + '%' : '—'} | ${b.items || '?'} | ${b.genres.join(', ')} |`));
  L.push('');
  L.push('## Итог', '', `- Бандлов всего: ${all.length}, из них шаблонов/проектов: ${tpl.length}.`,
    `- Цена бандла шаблонов: медиана $${med(tpl.map(b => b.price)) ?? '—'}, частей в бандле: медиана ${med(tpl.map(b => b.items)) ?? '—'}, цена за 1 шаблон в бандле: медиана $${med(perItem) ?? '—'}.`,
    `- Скидка к сумме частей (где посчитать удалось): медиана −${med(all.map(b => b.offParts)) ?? '—'}% (бандлов с расчётом: ${all.filter(b => b.offParts != null).length}).`,
    `- Скидка к «было» / заявленная в названии: медиана −${med(all.map(b => b.offWas)) ?? '—'}%; в house/techno: −${med(fb.map(b => b.offWas ?? b.offParts)) ?? '—'}%, цена бандла house/techno: медиана $${med(fb.map(b => b.price)) ?? '—'}.`,
    '- Жанры бандлов: ' + Object.entries(all.flatMap(b => b.genres).reduce((a, g) => (a[g] = (a[g] || 0) + 1, a), {})).sort((a, b) => b[1] - a[1]).slice(0, 10).map(([g, n]) => `${g} ${n}`).join(' · '), '',
    `_Запросов: ${S.used()}._`);
  data.summary = { bundles: all.length, templateBundles: tpl.length, medPrice: med(tpl.map(b => b.price)), medItems: med(tpl.map(b => b.items)), medPerItem: med(perItem), medOffParts: med(all.map(b => b.offParts)) };
  const name = MODE === 'probe' ? 'bundles-probe' : 'bundles';
  fs.writeFileSync(path.join(OUT, name + '.md'), L.join('\n') + '\n'); fs.writeFileSync(path.join(OUT, name + '.json'), JSON.stringify(data, null, 1));
  console.log(L.join('\n'));
})();
