// Daily intel robot («Ежедневная разведка», owner 25.11.2026). Every night: competitors (new products, prices, sales,
// bestsellers), search for new competitors, trends of the niche, and us (our shop). Learns: reports/intel/memory.json
// keeps what is already known (no repeats), which sources work / give value / give noise, candidate competitors;
// once a week it improves itself (drops dead/noisy sources, promotes found competitors to the watch list).
//   node tools/bots/intel.js            full run
//   MODE=quick node tools/bots/intel.js 2–3 minutes: 2 shops + 1 feed, writes to reports/intel/quick.md only
//   MODE=probe node tools/bots/intel.js which sources answer (and robots.txt allows) -> reports/intel/probe.md
//   MODE=improve node tools/bots/intel.js only the weekly self-improvement step
// Polite to other sites: one request at a time, pause per host, robots.txt respected, no protection bypass, request cap.
'use strict';
const fs = require('fs');
const path = require('path');

const OUT = process.env.INTEL_OUT || 'reports/intel';
const MODE = process.env.MODE || 'full';
const CFG = JSON.parse(fs.readFileSync(path.join(__dirname, 'intel.json'), 'utf8'));
const UA = 'Mozilla/5.0 (compatible; SolidSoundIntel/1.0; +https://solid-sound.net)';
const BASE = 'https://solid-sound.net';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const today = new Date().toISOString().slice(0, 10);
const esc = (s) => String(s == null ? '' : s).replace(/\|/g, '/').replace(/[\r\n]+/g, ' ').trim();
const median = (a) => { const s = a.filter(x => x > 0).sort((x, y) => x - y); return s.length ? s[Math.floor(s.length / 2)] : 0; };

// ---------- memory ----------
const MEM_FILE = path.join(OUT, 'memory.json');
function loadMem() {
  let m = {}; try { m = JSON.parse(fs.readFileSync(MEM_FILE, 'utf8')); } catch (e) { /* first run */ }
  return Object.assign({ version: 1, seen: {}, shops: {}, sources: {}, candidates: {}, watch: [], keywords: {}, feedback: [], lessons: [], improved: '' }, m);
}
const mem = loadMem();
function saveMem() {
  // forget items older than 120 days so the file stays small
  const cut = new Date(Date.now() - 120 * 864e5).toISOString().slice(0, 10);
  for (const k of Object.keys(mem.seen)) if (mem.seen[k] < cut) delete mem.seen[k];
  mem.updated = new Date().toISOString();
  fs.mkdirSync(OUT, { recursive: true });
  fs.writeFileSync(MEM_FILE, JSON.stringify(mem, null, 1));
}
function src(id) { return mem.sources[id] || (mem.sources[id] = { runs: 0, ok: 0, failStreak: 0, items: 0, useful: 0, noise: 0, off: false }); }
const isNew = (key) => { if (mem.seen[key]) return false; mem.seen[key] = today; return true; };

// ---------- polite fetch ----------
let budget = MODE === 'quick' ? 25 : (CFG.maxRequests || 220);
const lastHit = {}; const robotsCache = {};
async function rawGet(url, timeout) {
  const r = await fetch(url, { headers: { 'User-Agent': UA, Accept: '*/*' }, redirect: 'follow', signal: AbortSignal.timeout(timeout || 30000) });
  return { status: r.status, text: await r.text(), url: r.url };
}
async function robotsOk(u) {
  const { origin, pathname, search } = new URL(u);
  if (!(origin in robotsCache)) {
    let rules = [];
    try {
      const r = await rawGet(origin + '/robots.txt', 15000);
      if (r.status === 200) {
        let mine = false, any = false; const all = [], star = [];
        for (const line of r.text.split(/\r?\n/)) {
          const l = line.replace(/#.*/, '').trim(); if (!l) continue;
          const [k, ...rest] = l.split(':'); const v = rest.join(':').trim(); const key = k.toLowerCase();
          if (key === 'user-agent') { mine = /solidsoundintel/i.test(v); any = v === '*'; continue; }
          if ((key === 'disallow' || key === 'allow') && (mine || any)) (mine ? all : star).push({ allow: key === 'allow', p: v });
        }
        rules = all.length ? all : star;
      }
    } catch (e) { /* no robots.txt = allowed */ }
    robotsCache[origin] = rules;
  }
  const target = pathname + search; let best = null;
  for (const r of robotsCache[origin]) {
    if (!r.p) continue;
    const re = new RegExp('^' + r.p.replace(/[.+?^${}()|[\]\\]/g, '\\$&').replace(/\*/g, '.*').replace(/\\\$$/, '$'));
    if (re.test(target) && (!best || r.p.length > best.p.length || (r.p.length === best.p.length && r.allow))) best = r;
  }
  return !best || best.allow;
}
async function get(url, opts = {}) {
  if (budget <= 0) return { status: 0, error: 'лимит запросов на запуск', text: '' };
  if (!opts.skipRobots && !(await robotsOk(url))) return { status: 0, error: 'robots.txt запрещает', text: '', robots: true };
  const host = new URL(url).host; const wait = (lastHit[host] || 0) + (CFG.pauseMs || 3000) - Date.now();
  if (wait > 0) await sleep(wait);
  budget--; lastHit[host] = Date.now();
  try {
    const r = await rawGet(url, opts.timeout);
    if (r.status === 429 || r.status === 403 || /cf-chl|captcha|Захищена сторінка/i.test(r.text.slice(0, 3000))) return { status: r.status, error: 'сайт ограничил доступ (не обходим)', text: '' };
    return r;
  } catch (e) { return { status: 0, error: e.message.slice(0, 80), text: '' }; }
}
const json = (r) => { try { return JSON.parse(r.text); } catch (e) { return null; } };

// ---------- adapters ----------
// Shopify: /products.json (public). Woo: Store API (public). Returns {items:[{id,title,price,cmp,url,created,tags}]}.
async function readShop(shop) {
  const origin = 'https://' + shop.domain;
  const tries = shop.type === 'woo' ? ['woo'] : shop.type === 'shopify' ? ['shopify'] : ['shopify', 'woo'];
  for (const t of tries) {
    if (t === 'shopify') {
      let all = null, r = {};
      for (let pg = 1; pg <= 4; pg++) { // 250 per page is Shopify's max; up to 1000 products
        r = await get(origin + (shop.path || '') + '/products.json?limit=250&page=' + pg);
        const j = r.status === 200 && json(r);
        if (!j || !Array.isArray(j.products)) break;
        all = (all || []).concat(j.products); if (j.products.length < 250) break;
      }
      if (all) return { type: 'shopify', full: true, items: all.map(p => { const v = (p.variants || [])[0] || {}; return { id: p.handle, title: p.title, price: +v.price || 0, cmp: +v.compare_at_price || 0, url: origin + '/products/' + p.handle, created: (p.published_at || p.created_at || '').slice(0, 10), tags: [].concat(p.tags || [], p.product_type || []).join(' ') }; }) };
      if (r.error && tries.length === 1) return { error: r.error };
    } else {
      const r = await get(origin + '/wp-json/wc/store/v1/products?per_page=100&orderby=date');
      const j = r.status === 200 && json(r);
      if (Array.isArray(j)) return { type: 'woo', items: j.map(p => { const pr = p.prices || {}; const d = Math.pow(10, pr.currency_minor_unit || 2); return { id: String(p.id), title: p.name, price: (+pr.price || 0) / d, cmp: (+pr.regular_price || 0) / d > (+pr.price || 0) / d ? (+pr.regular_price) / d : 0, url: p.permalink, created: '', tags: (p.categories || []).map(c => c.name).join(' '), rating: +p.average_rating || 0, reviews: p.review_count || 0 }; }) };
      return { error: r.error || ('HTTP ' + r.status) };
    }
  }
  return { error: 'нет открытого списка товаров' };
}
// Bestsellers: a public "best sellers" collection if the shop has one (sort_by pages are closed by Shopify robots.txt)
async function bestsellers(shop) {
  if (shop.type !== 'shopify') return [];
  const origin = 'https://' + shop.domain;
  for (const c of (shop.best ? [shop.best] : CFG.bestCollections)) {
    const r = await get(origin + '/collections/' + c + '/products.json?limit=10');
    const j = r.status === 200 && json(r);
    if (j && Array.isArray(j.products) && j.products.length) { shop.best = c; return j.products.map(p => ({ title: p.title, price: +((p.variants || [])[0] || {}).price || 0, url: origin + '/products/' + p.handle })); }
    if (shop.best) break;
  }
  return [];
}
function parseFeed(xml) {
  const items = []; const re = /<(item|entry)[\s>][\s\S]*?<\/\1>/g; let m;
  while ((m = re.exec(xml))) {
    const b = m[0]; const pick = (tag) => { const x = b.match(new RegExp('<' + tag + '[^>]*>([\\s\\S]*?)</' + tag + '>')); return x ? x[1].replace(/<!\[CDATA\[|\]\]>/g, '').replace(/<[^>]+>/g, '').replace(/&amp;/g, '&').replace(/&#0?39;|&apos;/g, "'").replace(/&quot;/g, '"').trim() : ''; };
    let link = pick('link'); if (!link) { const h = b.match(/<link[^>]*href="([^"]+)"/); link = h ? h[1] : ''; }
    items.push({ title: pick('title'), link, date: pick('pubDate') || pick('published') || pick('updated'), text: (pick('description') || pick('summary') || pick('content')).slice(0, 600) });
  }
  return items;
}

// ---------- sections ----------
const R = { news: [], shops: [], best: [], feeds: [], found: [], us: [], trend: [], health: [] };
const genreHits = {}; const allPrices = []; let saleCount = 0, prodCount = 0;
function countGenres(text, w) { const t = String(text).toLowerCase(); for (const g of CFG.genres) if (t.includes(g)) genreHits[g] = (genreHits[g] || 0) + (w || 1); }
function noteDomain(link, from) {
  let h; try { h = new URL(link).host.replace(/^www\./, ''); } catch (e) { return; }
  if (!h || CFG.ignoreDomains.some(d => h === d || h.endsWith('.' + d))) return;
  if (mem.watch.some(s => s.domain.replace(/^www\./, '') === h)) return;
  const c = mem.candidates[h] || (mem.candidates[h] = { count: 0, first: today, from: [] });
  c.count++; c.last = today; if (!c.from.includes(from)) c.from.push(from);
}

async function shopsSection(list) {
  for (const shop of list) {
    const s = src('shop:' + shop.domain); if (s.off) continue; s.runs++;
    const res = await readShop(shop);
    if (res.error) { s.failStreak++; R.shops.push(`- **${shop.name}**: не прочитал — ${res.error}${s.failStreak > 2 ? ` (${s.failStreak}-й день подряд)` : ''}`); continue; }
    s.ok++; s.failStreak = 0; s.lastOk = today; if (!shop.type) shop.type = res.type;
    const old = mem.shops[shop.domain] || {}; const first = !Object.keys(old).length; const cur = {};
    const added = [], price = [], gone = [];
    for (const it of res.items) {
      cur[it.id] = [it.price, it.cmp];
      prodCount++; if (it.cmp > it.price) saleCount++; if (it.price) allPrices.push(it.price);
      const recent = !it.created || it.created >= new Date(Date.now() - 30 * 864e5).toISOString().slice(0, 10);
      if (!old[it.id]) { if (!first && recent && isNew('p:' + it.url)) { added.push(it); countGenres(it.title + ' ' + it.tags, 2); } else mem.seen['p:' + it.url] = mem.seen['p:' + it.url] || today; }
      else if (old[it.id][0] !== it.price) price.push({ it, was: old[it.id][0] });
    }
    for (const id of Object.keys(old)) if (id !== '__sale' && !cur[id]) { if (res.full) gone.push(id); else cur[id] = old[id]; } // Woo shows only the latest 100: keep the rest
    mem.shops[shop.domain] = cur;
    const prices = res.items.map(i => i.price);
    const onSale = res.items.filter(i => i.cmp > i.price).length;
    s.items += added.length + price.length;
    let line = `- **${shop.name}**${shop.isNew ? ' 🆕' : ''}: товаров ${res.items.length}, медиана цены ${median(prices) || '—'}, со скидкой ${onSale}`;
    if (first) line += ' — первый замер, изменения со следующей ночи';
    else if (!added.length && !price.length && !gone.length) line += ' — без изменений';
    R.shops.push(line);
    if (added.length) R.shops.push(`  - 🆕 новинки: ${added.slice(0, 6).map(i => `[${esc(i.title)}](${i.url}) — ${i.price}`).join('; ')}${added.length > 6 ? ` и ещё ${added.length - 6}` : ''}`);
    if (price.length) R.shops.push(`  - 💲 цена: ${price.slice(0, 6).map(x => `${esc(x.it.title)} ${x.was} → ${x.it.price}`).join('; ')}`);
    if (gone.length) R.shops.push(`  - убрали товаров: ${gone.length}`);
    if (added.length) R.news.push({ score: 3 + added.length, key: 'shop:' + shop.domain, text: `${shop.name}: ${added.length} новинк(и) — например «${esc(added[0].title)}» за ${added[0].price}` });
    if (price.length >= 5) R.news.push({ score: 4, key: 'shop:' + shop.domain, text: `${shop.name}: поменяли цены у ${price.length} товаров (возможно, старт акции)` });
    if (!first && onSale - (old.__sale || 0) >= 10) R.news.push({ score: 5, key: 'shop:' + shop.domain, text: `${shop.name}: скидок стало больше на ${onSale - (old.__sale || 0)} — похоже на распродажу` });
    mem.shops[shop.domain].__sale = onSale;
    const best = await bestsellers(shop);
    if (best.length) {
      best.forEach((b, i) => countGenres(b.title, 3 - Math.min(2, Math.floor(i / 4))));
      const key = 'b:' + shop.domain + ':' + best.slice(0, 3).map(b => b.title).join('|');
      R.best.push(`- **${shop.name}** — топ: ${best.slice(0, 5).map(b => `[${esc(b.title)}](${b.url}) (${b.price})`).join(', ')}${isNew(key) ? '' : ' _(как вчера)_'}`);
    }
  }
}

async function feedsSection(list) {
  for (const f of list) {
    const s = src('feed:' + f.id); if (s.off) continue; s.runs++;
    const r = await get(f.url);
    if (r.status !== 200) { s.failStreak++; R.health.push(`- лента «${f.name}»: ${r.error || 'HTTP ' + r.status}`); continue; }
    const items = parseFeed(r.text); if (!items.length) { s.failStreak++; R.health.push(`- лента «${f.name}»: пустая`); continue; }
    s.ok++; s.failStreak = 0; s.lastOk = today;
    const fresh = [];
    for (const it of items.slice(0, 40)) {
      if (!it.link || !isNew('f:' + it.link)) continue;
      const text = (it.title + ' ' + it.text).toLowerCase();
      // score by keywords (weights learn from the owner's / Claude's feedback)
      let score = 0; for (const [k, w] of Object.entries(Object.assign({}, CFG.keywords, mem.keywords))) if (text.includes(k)) score += w;
      countGenres(text, 1);
      for (const l of (it.text.match(/https?:\/\/[^\s"'<)]+/g) || [])) noteDomain(l, f.id);
      if (score > 0) fresh.push({ ...it, score });
    }
    fresh.sort((a, b) => b.score - a.score);
    s.items += fresh.length;
    for (const it of fresh.slice(0, f.max || 4)) {
      R.feeds.push(`- [${esc(it.title)}](${it.link}) — ${f.name}`);
      if (it.score >= 4) R.news.push({ score: it.score, key: 'feed:' + f.id, text: `${esc(it.title)} (${f.name})`, link: it.link });
    }
  }
}

// Search for new competitors: Brave Search API if the owner adds a free key (secret BRAVE_KEY); found domains go to
// candidates; a candidate with an open product list and ≥2 sightings becomes a watched competitor (weekly step).
async function discoverSection() {
  const key = process.env.BRAVE_KEY;
  if (!key) { R.found.push('⏸ Поиск новых конкурентов по Google-подобной выдаче ждёт ключ Brave Search (бесплатно, 2000 запросов в месяц) — секрет `BRAVE_KEY`. Пока новых ищу по ссылкам в новостных лентах.'); }
  else {
    const qs = CFG.queries; const q = qs[new Date().getUTCDate() % qs.length]; const q2 = qs[(new Date().getUTCDate() + 7) % qs.length];
    for (const query of [q, q2]) {
      const s = src('brave'); s.runs++;
      try {
        const r = await fetch('https://api.search.brave.com/res/v1/web/search?count=20&q=' + encodeURIComponent(query), { headers: { 'X-Subscription-Token': key, Accept: 'application/json' }, signal: AbortSignal.timeout(20000) });
        const j = await r.json(); const res = (j.web && j.web.results) || [];
        s.ok++; s.failStreak = 0; res.forEach(x => noteDomain(x.url, 'search:' + query)); s.items += res.length;
      } catch (e) { s.failStreak++; R.health.push('- поиск Brave: ' + e.message.slice(0, 80)); }
    }
  }
  // check up to 4 fresh candidates: do they sell (open product list)?
  const fresh = Object.entries(mem.candidates).filter(([, c]) => !c.checked).sort((a, b) => b[1].count - a[1].count).slice(0, MODE === 'quick' ? 0 : 4);
  for (const [d, c] of fresh) {
    const res = await readShop({ domain: d, name: d });
    c.checked = today; c.type = res.error ? 'нет каталога' : res.type; c.products = res.items ? res.items.length : 0;
    if (!res.error) { c.median = median(res.items.map(i => i.price)); R.found.push(`- 🆕 **${d}** — магазин (${res.type}), товаров ${c.products}, медиана ${c.median}; найден в: ${c.from.slice(0, 2).join(', ')}`); R.news.push({ score: 6, key: 'discover', text: `Новый возможный конкурент: ${d} (${c.products} товаров, медиана цены ${c.median})` }); }
  }
  const waiting = Object.entries(mem.candidates).filter(([, c]) => !c.checked).length;
  if (waiting) R.found.push(`- в очереди на проверку: ${waiting} сайтов`);
}

async function usSection() {
  const r = await get(BASE + '/wp-json/wc/store/v1/products?per_page=100', { skipRobots: true, timeout: 60000 });
  const j = r.status === 200 && json(r);
  if (!Array.isArray(j)) { R.us.push(`- наш магазин не ответил: ${r.error || 'HTTP ' + r.status}`); return; }
  const d = (p) => Math.pow(10, (p.prices || {}).currency_minor_unit || 2);
  const ours = j.map(p => ({ title: p.name, price: (+(p.prices || {}).price || 0) / d(p), reg: (+(p.prices || {}).regular_price || 0) / d(p), reviews: p.review_count || 0, url: p.permalink, stock: p.is_in_stock, desc: (p.short_description || '').replace(/<[^>]+>/g, '').trim().length }));
  const prev = mem.shops.__us || {}; const cur = {};
  ours.forEach(o => { cur[o.url] = o.price; });
  const added = ours.filter(o => !(o.url in prev)); const changed = ours.filter(o => o.url in prev && prev[o.url] !== o.price);
  mem.shops.__us = cur;
  const med = median(ours.map(o => o.price)); const compMed = median(allPrices);
  R.us.push(`- товаров у нас: **${ours.length}**, медиана цены ${med} € (у конкурентов в списке — ${compMed || '—'}), со скидкой ${ours.filter(o => o.reg > o.price).length}, с отзывами ${ours.filter(o => o.reviews).length}`);
  const noDesc = ours.filter(o => o.desc < 50).length; if (noDesc) R.us.push(`- без короткого описания: ${noDesc} из ${ours.length} — карточки хуже продают и хуже ищутся`);
  if (!ours.some(o => o.reviews)) R.us.push('- ни одного отзыва на товарах — у конкурентов отзывы и «бестселлеры» на виду; первые отзывы = доверие');
  if (Object.keys(prev).length && (added.length || changed.length)) R.us.push(`- изменения у нас со вчера: новых ${added.length}, цена поменялась у ${changed.length}`);
  R.us.push('- визиты, источники и путь покупки (Solid Stats) — добавляю утром в чате');
}

function trendSection() {
  const g = Object.entries(genreHits).sort((a, b) => b[1] - a[1]).slice(0, 6);
  if (g.length) R.trend.push(`- жанры в новинках и топах сегодня: ${g.map(([k, v]) => `${k} (${v})`).join(', ')}`);
  const hist = mem.trendHistory || (mem.trendHistory = []);
  hist.push({ d: today, g: genreHits, median: median(allPrices), sale: prodCount ? Math.round(saleCount / prodCount * 100) : 0 });
  while (hist.length > 60) hist.shift();
  if (prodCount) R.trend.push(`- у конкурентов: товаров ${prodCount}, медиана цены ${median(allPrices)}, на скидке ${Math.round(saleCount / prodCount * 100)} %`);
  const wk = hist.slice(-8, -1); if (wk.length >= 3) {
    const avg = Math.round(wk.reduce((n, h) => n + h.sale, 0) / wk.length); const now = hist[hist.length - 1].sale;
    if (Math.abs(now - avg) >= 5) R.news.push({ score: 5, key: 'trend', text: `Доля товаров со скидкой у конкурентов: ${now} % (за неделю было в среднем ${avg} %)` });
  }
}

// ---------- weekly self-improvement ----------
function improve(force) {
  const L = [];
  const monday = new Date().getUTCDay() === 1;
  if (!force && (!monday || mem.improved === today)) return L;
  mem.improved = today;
  // 1) learn keywords from feedback (Claude writes feedback each morning: which news were useful / noise)
  for (const fb of mem.feedback.filter(f => !f.learned)) {
    for (const w of fb.useful_words || []) mem.keywords[w] = Math.min(6, (mem.keywords[w] || 1) + 1);
    for (const w of fb.noise_words || []) mem.keywords[w] = Math.max(-4, (mem.keywords[w] || 0) - 1);
    for (const k of fb.useful || []) src(k).useful++;
    for (const k of fb.noise || []) src(k).noise++;
    fb.learned = true;
  }
  mem.feedback = mem.feedback.slice(-60);
  // 2) switch off dead or noisy sources
  for (const [id, s] of Object.entries(mem.sources)) {
    if (!s.off && s.failStreak >= 7) { s.off = true; s.offWhy = 'не отвечает 7 дней'; L.push(`- выключил источник ${id}: не отвечает 7 дней`); }
    if (!s.off && s.noise >= 6 && s.noise > s.useful * 3) { s.off = true; s.offWhy = 'много шума'; L.push(`- выключил источник ${id}: польза ${s.useful}, шум ${s.noise}`); }
  }
  // 3) promote found competitors (open product list, seen ≥2 times) to the watch list
  for (const [d, c] of Object.entries(mem.candidates)) {
    if ((c.type === 'shopify' || c.type === 'woo') && c.count >= 2 && !mem.watch.some(w => w.domain === d) && mem.watch.length < (CFG.maxWatch || 25)) {
      mem.watch.push({ name: d, domain: d, type: c.type, isNew: true, added: today }); L.push(`- добавил в слежку нового конкурента: ${d}`);
    }
  }
  if (!L.length) L.push('- источники в порядке, менять нечего');
  mem.lessons.push({ d: today, changes: L }); mem.lessons = mem.lessons.slice(-20);
  return L;
}

// ---------- probe ----------
async function probe() {
  const L = ['# Пробный запуск разведки — ' + new Date().toISOString(), '', '| Источник | Ответ | robots.txt | Что получили |', '|---|---|---|---|'];
  for (const shop of CFG.shops.concat(CFG.probeShops || [])) {
    const allow = await robotsOk('https://' + shop.domain + '/products.json');
    const res = await readShop(shop);
    L.push(`| ${shop.name} (${shop.domain}) | ${res.error ? '❌ ' + res.error : '✅ ' + res.type} | ${allow ? 'можно' : 'нельзя'} | ${res.items ? res.items.length + ' товаров' : ''} |`);
    if (!res.error) { const b = await bestsellers(shop); if (b.length) L.push(`| ↳ бестселлеры | ✅ /collections/${shop.best} | можно | ${b.length} |`); }
  }
  for (const f of CFG.feeds.concat(CFG.probeFeeds || [])) {
    const r = await get(f.url); const n = r.status === 200 ? parseFeed(r.text).length : 0;
    L.push(`| лента ${f.name} | ${r.status === 200 && n ? '✅' : '❌ ' + (r.error || 'HTTP ' + r.status)} | ${r.robots ? 'нельзя' : 'можно'} | ${n} записей |`);
  }
  const us = await get(BASE + '/wp-json/wc/store/v1/products?per_page=5', { skipRobots: true, timeout: 60000 });
  L.push(`| наш магазин (Store API) | ${us.status === 200 ? '✅' : '❌ ' + (us.error || 'HTTP ' + us.status)} | — | |`);
  L.push('', `Запросов потрачено: ${(CFG.maxRequests || 220) - budget}`);
  fs.mkdirSync(OUT, { recursive: true }); fs.writeFileSync(path.join(OUT, 'probe.md'), L.join('\n') + '\n'); console.log(L.join('\n'));
}

// ---------- main ----------
(async () => {
  if (MODE === 'probe') return probe();
  if (!mem.watch.length) mem.watch = CFG.shops.map(s => ({ ...s }));
  else for (const s of CFG.shops) if (!mem.watch.some(w => w.domain === s.domain)) mem.watch.push({ ...s }); // config additions
  if (MODE === 'improve') { const L = improve(true); saveMem(); console.log(L.join('\n')); return; }
  const quick = MODE === 'quick';
  const t0 = Date.now();
  await shopsSection(quick ? mem.watch.slice(0, 2) : mem.watch);
  await feedsSection(quick ? CFG.feeds.slice(0, 1) : CFG.feeds);
  if (!quick) await discoverSection();
  await usSection();
  trendSection();
  const imp = quick ? [] : improve(false);
  // top news: best score, at most one per source, no repeats of yesterday's wording
  const top = []; const used = new Set();
  for (const n of R.news.sort((a, b) => b.score - a.score)) { if (used.has(n.key) || !isNew('n:' + n.text)) continue; used.add(n.key); top.push(n); if (top.length >= 5) break; }
  const L = [`# Разведка — ${today}`, '', `_Робот: ${mem.watch.length} конкурентов, ${CFG.feeds.length} лент; запросов ${(quick ? 25 : CFG.maxRequests || 220) - budget}, ${Math.round((Date.now() - t0) / 1000)} с. Память: ${Object.keys(mem.seen).length} известных записей._`, '',
    '## Главное за ночь', ...(top.length ? top.map((n, i) => `${i + 1}. ${n.text}${n.link ? ` — [ссылка](${n.link})` : ''} \`[${n.key}]\``) : ['- ничего нового и важного — рынок спокоен']), '',
    '## Конкуренты', ...R.shops, '',
    ...(R.best.length ? ['## Что у них в топе', ...R.best, ''] : []),
    '## Новости сферы', ...(R.feeds.length ? R.feeds : ['- новых важных новостей нет']), '',
    '## Новые конкуренты', ...(R.found.length ? R.found : ['- новых не нашёл']), '',
    '## Тренды', ...(R.trend.length ? R.trend : ['- мало данных (нужно несколько ночей)']), '',
    '## Мы', ...R.us, '',
    ...(imp.length ? ['## Робот учится (раз в неделю)', ...imp, ''] : []),
    ...(R.health.length ? ['## Что не сработало', ...R.health, ''] : []),
    '_Обратная связь для обучения: утром Claude пишет в memory.json → feedback, какие пункты (`[ключ]`) были полезны, а какие — шум._'];
  const md = L.join('\n') + '\n';
  fs.mkdirSync(path.join(OUT, 'history'), { recursive: true });
  if (quick) fs.writeFileSync(path.join(OUT, 'quick.md'), md);
  else { fs.writeFileSync(path.join(OUT, 'latest.md'), md); fs.writeFileSync(path.join(OUT, 'history', today + '.md'), md); }
  saveMem();
  console.log(md);
})().catch(e => { console.error(e); process.exit(1); });
