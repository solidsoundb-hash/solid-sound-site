// Product data bot: every product is ready for Google (Shopping / free listings via "Google for WooCommerce",
// rich results with price and rating): price, picture, description, category, sound preview, schema on the page.
// Uses the public WooCommerce Store API — no key needed.
'use strict';
const { metrics, BASE, get, esc, loadState, saveState } = require('./lib');

module.exports = async function products() {
  const L = ['## 🛍 Товары: готовность для Google и покупателей', ''];
  const all = [];
  for (let page = 1; page <= 10; page++) {
    const r = await get(`${BASE}/wp-json/wc/store/v1/products?per_page=100&page=${page}`);
    if (r.status !== 200) { L.push(`⚠️ Список товаров не загрузился: ${r.error || 'HTTP ' + r.status}`); return L.join('\n') + '\n'; }
    const j = r.json(); all.push(...j); if (j.length < 100) break;
  }
  const strip = (h) => String(h || '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
  const issues = [];
  for (const p of all) {
    const pr = p.prices || {}; const price = +(pr.price || 0) / Math.pow(10, pr.currency_minor_unit || 2);
    const desc = strip(p.description), short = strip(p.short_description);
    const prob = [];
    if (!price && !/free/i.test(p.name + ' ' + (p.categories || []).map(c => c.name).join(' '))) prob.push('цена 0');
    if (!(p.images || []).length) prob.push('нет картинки');
    if (desc.length < 300) prob.push(`короткое описание (${desc.length} знаков, нужно от 300 — Google и покупателю мало)`);
    if (!short) prob.push('нет краткого описания');
    if (!(p.categories || []).length) prob.push('нет категории');
    if (p.name.length > 70) prob.push('слишком длинное название для Google (>70)');
    if (!/\.mp3|\.wav|audio|waveform|ssw|soundcloud|youtube/i.test(p.description || '') && !(p.extensions && JSON.stringify(p.extensions).match(/mp3|audio/i))) prob.push('нет звукового превью в описании (проверить плеер на странице)');
    if (pr.sale_price && pr.regular_price && +pr.sale_price < +pr.regular_price && !p.on_sale) prob.push('скидка указана, но не активна');
    if (!p.is_purchasable || !p.is_in_stock) prob.push('нельзя купить (не в наличии / не покупаемый)');
    if (prob.length) issues.push({ name: p.name, url: p.permalink, prob });
  }
  // schema on a few product pages (price + availability for Google rich results)
  const sample = all.slice(0, 5); const noSchema = [];
  for (const p of sample) {
    const r = await get(p.permalink);
    if (r.status === 200 && !/"@type"\s*:\s*"Product"/.test(r.text)) noSchema.push(p.name);
  }
  Object.assign(metrics, { products: all.length, products_with_issues: issues.length });
  L.push(`Товаров в магазине: **${all.length}**. С замечаниями: **${issues.length}**.`, '');
  if (noSchema.length) L.push(`⚠️ На страницах товаров нет разметки Product для Google (цена/наличие в поиске): ${noSchema.join(', ')}`, '');
  if (issues.length) { L.push('| Товар | Что поправить |', '|---|---|'); issues.slice(0, 40).forEach(i => L.push(`| [${esc(i.name)}](${i.url}) | ${esc(i.prob.join('; '))} |`)); L.push(''); }
  const st = loadState('products'); const was = new Set((st && st.ids) || []);
  const fresh = all.filter(p => !was.has(p.id));
  if (st && fresh.length) L.push('🆕 Новые товары за неделю: ' + fresh.map(p => `[${p.name}](${p.permalink})`).join(', ') + ' — повод для письма подписчикам и поста в соцсетях.', '');
  saveState('products', { ids: all.map(p => p.id) });
  return L.join('\n') + '\n';
};
