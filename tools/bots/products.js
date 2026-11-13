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
  const strip = (h) => String(h || '').replace(/<script[\s\S]*?<\/script>|<style[\s\S]*?<\/style>/g, ' ').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
  // the Store API on this site can return empty descriptions/categories (page builder + languages) — so the
  // product PAGE is the source of truth for description, sound preview and Google markup
  const allNoCat = all.length && all.every(p => !(p.categories || []).length);
  const issues = []; const noSchema = [];
  for (const p of all.slice(0, 40)) {
    const pr = p.prices || {}; const price = +(pr.price || 0) / Math.pow(10, pr.currency_minor_unit || 2);
    const pg = await get(p.permalink); const html = pg.status === 200 ? pg.text : '';
    const tab = (html.match(/id="tab-description"[\s\S]*?<\/div>\s*<\/div>/) || [])[0] || '';
    const desc = strip(tab) || strip(p.description);
    const prob = [];
    if (!price && !/free/i.test(p.name)) prob.push('цена 0');
    if (!(p.images || []).length) prob.push('нет картинки');
    if (html && desc.length < 300) prob.push(`короткое описание на странице (${desc.length} знаков, нужно от 300 — Google и покупателю мало)`);
    if (!allNoCat && !(p.categories || []).length) prob.push('нет категории');
    if (p.name.length > 70) prob.push('слишком длинное название для Google (>70)');
    if (html && !/\.mp3|\.wav|<audio|ssw|wp-audio|waveform/i.test(html)) prob.push('на странице нет звукового превью');
    if (html && !/"@type"\s*:\s*"Product"/.test(html)) noSchema.push(p.name);
    if (!p.is_purchasable || !p.is_in_stock) prob.push('нельзя купить (не в наличии / не покупаемый)');
    if (!html) prob.push('страница товара не открылась для проверки');
    if (prob.length) issues.push({ name: p.name, url: p.permalink, prob });
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
