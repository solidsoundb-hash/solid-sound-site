// Shared polite shop reader for the Analyst's watch robots (bundle-watch, genre-watch).
// Same rules as intel.js: one request at a time, pause per host, robots.txt respected, request cap, no protection bypass.
'use strict';
const UA = 'SolidSoundIntel/1.0 (+https://solid-sound.net; polite market research bot)';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const dec = (t) => String(t || '').replace(/&#(\d+);/g, (_, n) => String.fromCharCode(+n)).replace(/&#x([0-9a-f]+);/gi, (_, n) => String.fromCharCode(parseInt(n, 16)))
  .replace(/&amp;/g, '&').replace(/&quot;/g, '"').replace(/&#39;|&apos;/g, "'").replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&nbsp;/g, ' ');
const strip = (h) => dec(String(h || '').replace(/<br\s*\/?>/gi, ' ').replace(/<\/(p|li|div|h\d)>/gi, ' \n').replace(/<[^>]+>/g, ' ')).replace(/[ \t]+/g, ' ').trim();

function make(opts = {}) {
  let budget = opts.maxRequests || 120; const pause = opts.pauseMs || 3000; const lastHit = {}; const robotsCache = {};
  async function rawGet(url) { const r = await fetch(url, { headers: { 'User-Agent': UA, Accept: '*/*' }, redirect: 'follow', signal: AbortSignal.timeout(30000) }); return { status: r.status, text: await r.text() }; }
  async function robotsOk(u) {
    const { origin, pathname, search } = new URL(u);
    if (!(origin in robotsCache)) {
      let rules = [];
      try { const r = await rawGet(origin + '/robots.txt');
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
    const host = new URL(url).host; const wait = (lastHit[host] || 0) + pause - Date.now(); if (wait > 0) await sleep(wait);
    budget--; lastHit[host] = Date.now();
    try { const r = await rawGet(url);
      if (r.status === 429 || r.status === 403 || /cf-chl|captcha|Захищена сторінка/i.test(r.text.slice(0, 3000))) return { status: r.status, error: 'сайт ограничил доступ (не обходим)', text: '' };
      return r; } catch (e) { return { status: 0, error: e.message.slice(0, 80), text: '' }; }
  }
  const json = (r) => { try { return JSON.parse(r.text); } catch (e) { return null; } };
  const shopifyItem = (origin) => (p) => { const v = (p.variants || [])[0] || {};
    return { id: p.handle, title: dec(p.title), price: +v.price || 0, cmp: +v.compare_at_price || 0, url: origin + '/products/' + p.handle, created: (p.published_at || p.created_at || '').slice(0, 10), tags: [].concat(p.tags || [], p.product_type || []).join(' '), body: strip(p.body_html).slice(0, 4000) }; };
  const wooItem = (p) => { const pr = p.prices || {}; const d = Math.pow(10, pr.currency_minor_unit == null ? 2 : pr.currency_minor_unit); const price = (+pr.price || 0) / d, reg = (+pr.regular_price || 0) / d;
    return { id: String(p.id), title: dec(p.name), price, cmp: reg > price ? reg : 0, url: p.permalink, created: '', tags: (p.categories || []).map(c => c.name).concat((p.tags || []).map(t => t.name)).join(' '), body: strip((p.short_description || '') + ' ' + (p.description || '')).slice(0, 4000), rating: +p.average_rating || 0, reviews: p.review_count || 0 }; };
  // Catalog: Shopify /products.json (paged, 250 max) or Woo Store API (paged, 100 max).
  async function catalog(shop, pages) {
    const origin = 'https://' + shop.domain; let all = [], r = {};
    for (let pg = 1; pg <= (pages || 4); pg++) {
      r = await get(shop.type === 'woo' ? `${origin}/wp-json/wc/store/v1/products?per_page=100&page=${pg}&orderby=date&order=desc` : `${origin}/products.json?limit=250&page=${pg}`);
      const j = r.status === 200 && json(r); const list = shop.type === 'woo' ? (Array.isArray(j) ? j : null) : (j && j.products);
      if (!list) break; all = all.concat(list.map(shop.type === 'woo' ? wooItem : shopifyItem(origin)));
      if (list.length < (shop.type === 'woo' ? 100 : 250)) break;
    }
    return all.length ? { items: all } : { error: r.error || ('HTTP ' + r.status) };
  }
  async function wooSearch(shop, q, extra) {
    const r = await get(`https://${shop.domain}/wp-json/wc/store/v1/products?per_page=100&${q ? 'search=' + encodeURIComponent(q) + '&' : ''}${extra || ''}`);
    const j = r.status === 200 && json(r); return Array.isArray(j) ? j.map(wooItem) : [];
  }
  // Top sellers: Shopify public "best-sellers" collection, Woo Store API orderby=popularity.
  async function top(shop, n) {
    if (shop.type === 'woo') return wooSearch(shop, '', 'orderby=popularity&order=desc');
    const origin = 'https://' + shop.domain;
    for (const c of ['best-sellers', 'bestsellers', 'best-selling', 'top-sellers']) {
      const r = await get(`${origin}/collections/${c}/products.json?limit=${n || 30}`); const j = r.status === 200 && json(r);
      if (j && Array.isArray(j.products) && j.products.length) return j.products.map(shopifyItem(origin));
    }
    return [];
  }
  return { get, json, catalog, wooSearch, top, used: () => (opts.maxRequests || 120) - budget };
}
module.exports = { make, dec, strip };
