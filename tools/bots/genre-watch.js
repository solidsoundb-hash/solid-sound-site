// GENRE-WATCH (Analyst, 26.11.2026, owner via «Задачи»): what is NEW and what is in the TOP for house / melodic techno
// at competitors (public catalogs, best-seller collections, Woo "popularity" order = the shop's public chart) + our own
// templates in the same genres (bundle candidates). Public pages only (tools/bots/lib/shop.js rules).
//   MODE=probe node tools/bots/genre-watch.js -> 1 shop (training);  MODE=run -> all -> reports/analyst/genres.md + .json
'use strict';
const fs = require('fs'), path = require('path');
const CFG = JSON.parse(fs.readFileSync(path.join(__dirname, 'watch.json'), 'utf8'));
const OUT = path.join(__dirname, '..', '..', 'reports', 'analyst');
const { genresOf, kind } = require('./bundle-watch.js');

// Focus genre of an item (most specific first), or null if not house / techno family
function focus(it) { const g = genresOf(it.title + ' ' + (it.tags || '')); return CFG.focus.find(f => g.some(x => x.includes(f))) || null; }
const isTemplate = (it) => kind(it.title + ' ' + (it.tags || '')) === 'шаблоны/проекты';
const daysAgo = (d) => d ? (Date.now() - Date.parse(d)) / 864e5 : Infinity;

if (require.main !== module) { module.exports = { focus, isTemplate }; return; }

const MODE = process.env.MODE || 'run';
const S = require('./lib/shop.js').make({ maxRequests: CFG.maxRequests, pauseMs: CFG.pauseMs });
(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const prevFile = path.join(OUT, 'genres.json'); let prev = {}; try { prev = JSON.parse(fs.readFileSync(prevFile, 'utf8')).seen || {}; } catch (e) { /* first run */ }
  const shops = MODE === 'probe' ? CFG.shops.slice(0, 1) : CFG.shops;
  const now = new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC';
  const L = [`# House / melodic techno у конкурентов — ${now} (${MODE})`, '', 'Публичные каталоги и топы магазинов (Shopify «best-sellers», WooCommerce «по популярности»). 🆕 = появилось с прошлого запуска.', ''];
  const seen = {}; const data = { date: now.slice(0, 10), shops: [], seen };
  const byGenre = {};
  for (const shop of shops) {
    const c = await S.catalog(shop, shop.type === 'woo' ? 3 : 4);
    if (c.error) { L.push(`## ${shop.name}`, '', `❌ ${c.error}`, ''); data.shops.push({ name: shop.name, error: c.error }); continue; }
    const cat = c.items.filter(it => it.price > 1 || /free/i.test(it.title));
    const mine = cat.map(it => ({ ...it, g: focus(it), tpl: isTemplate(it) })).filter(it => it.g);
    for (const it of mine) { seen[shop.name + '|' + it.id] = 1; const b = byGenre[it.g] || (byGenre[it.g] = { n: 0, tpl: 0, prices: [], tplPrices: [] }); b.n++; b.prices.push(it.price); if (it.tpl) { b.tpl++; b.tplPrices.push(it.price); } }
    const fresh = mine.filter(it => shop.type === 'woo' ? true : daysAgo(it.created) <= 30).slice(0, shop.type === 'woo' ? 12 : 30);
    const newSince = Object.keys(prev).length ? mine.filter(it => !prev[shop.name + '|' + it.id]) : [];
    const top = (await S.top(shop, 30)).map((it, i) => ({ ...it, rank: i + 1, g: focus(it), tpl: isTemplate(it) })).filter(it => it.g);
    data.shops.push({ name: shop.name, catalog: cat.length, focus: mine.length, fresh: fresh.map(x => ({ t: x.title, p: x.price, g: x.g, tpl: x.tpl, d: x.created })), top: top.map(x => ({ r: x.rank, t: x.title, p: x.price, g: x.g, tpl: x.tpl })), newSince: newSince.map(x => x.title) });
    L.push(`## ${shop.name} — house/techno: ${mine.length} из ${cat.length} (шаблонов ${mine.filter(x => x.tpl).length})`, '');
    if (top.length) { L.push('**В топе магазина:**'); top.slice(0, 10).forEach(x => L.push(`- #${x.rank} ${x.tpl ? '🎛 ' : ''}${x.title} — $${x.price} (${x.g})`)); L.push(''); }
    if (newSince.length) { L.push('**🆕 Новое с прошлого запуска:**'); newSince.slice(0, 10).forEach(x => L.push(`- ${x.tpl ? '🎛 ' : ''}${x.title} — $${x.price} (${x.g})`)); L.push(''); }
    if (fresh.length) { L.push(shop.type === 'woo' ? '**Последние по дате:**' : '**Новое за 30 дней:**'); fresh.slice(0, 10).forEach(x => L.push(`- ${x.tpl ? '🎛 ' : ''}${x.title} — $${x.price} (${x.g}${x.created ? ', ' + x.created : ''})`)); L.push(''); }
  }
  const med = (a) => { const s = a.slice().sort((x, y) => x - y); return s.length ? s[Math.floor(s.length / 2)] : '—'; };
  L.push('## Рынок по жанрам (все магазины)', '', '| Жанр | Товаров | Из них шаблонов | Медиана цены | Медиана цены шаблона |', '|---|---|---|---|---|');
  Object.entries(byGenre).sort((a, b) => b[1].n - a[1].n).forEach(([g, b]) => L.push(`| ${g} | ${b.n} | ${b.tpl} | $${med(b.prices)} | ${b.tplPrices.length ? '$' + med(b.tplPrices) : '—'} |`));
  data.byGenre = Object.fromEntries(Object.entries(byGenre).map(([g, b]) => [g, { n: b.n, tpl: b.tpl, med: med(b.prices), medTpl: med(b.tplPrices) }]));
  // Our own templates in the focus genres = bundle candidates (public Store API of our shop)
  if (MODE !== 'probe' && CFG.us) {
    const u = await S.catalog(CFG.us, 2);
    const ours = (u.items || []).map(it => ({ ...it, g: focus(it) }));
    data.ours = ours.map(x => ({ t: x.title, p: x.price, was: x.cmp || null, g: x.g, rating: x.rating, reviews: x.reviews, url: x.url }));
    L.push('', `## Наши шаблоны (${ours.length}, из них house/techno: ${ours.filter(x => x.g).length}) — кандидаты в бандл`, '');
    if (u.error) L.push('❌ ' + u.error);
    ours.filter(x => x.g).sort((a, b) => CFG.focus.indexOf(a.g) - CFG.focus.indexOf(b.g)).forEach(x => L.push(`- ${x.title} — $${x.price}${x.cmp ? ' (было $' + x.cmp + ')' : ''} · ${x.g}`));
    const other = ours.filter(x => !x.g); if (other.length) L.push('', 'Другие жанры: ' + other.map(x => `${x.title} $${x.price}`).join(' · '));
  }
  L.push('', `_Запросов: ${S.used()}._`);
  const name = MODE === 'probe' ? 'genres-probe' : 'genres';
  fs.writeFileSync(path.join(OUT, name + '.md'), L.join('\n') + '\n');
  if (MODE !== 'probe') fs.writeFileSync(prevFile, JSON.stringify(data, null, 1));
  console.log(L.join('\n'));
})();
