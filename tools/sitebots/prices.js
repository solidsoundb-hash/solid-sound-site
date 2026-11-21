// PRICES (owner 21.11.2026): one product = one price everywhere. Compares, for every product, the shop price
// (WooCommerce Store API), the price shown on the product page, and the price in the page data Google reads (JSON-LD).
// Also watches the sale: a sale banner on the site while no price is reduced (or the other way round), and a sale left on
// after its end date.
'use strict';
const { BASE, get, sleep, esc } = require('../bots/lib');
const SALE_END = Date.parse('2026-11-30T23:59:59+01:00'); // the BLACK MONTH banner in src/ss-global.js (PROMO_END)
const money = s => { const m = String(s || '').replace(/&nbsp;|\s/g, '').match(/(\d+(?:[.,]\d{1,2})?)/); return m ? +m[1].replace(',', '.') : null; };

module.exports = async function prices() {
  const r = await get(BASE + '/wp-json/wc/store/v1/products?per_page=100');
  if (!r.status) return { md: '## 💶 Цены\n\n⏸ Магазин не ответил: ' + r.error + '\n', metrics: {}, alerts: [] };
  const now0 = Date.now(); const list = r.json(); const rows = []; let bad = 0, onSale = 0;
  for (const p of list) {
    const unit = 10 ** (p.prices.currency_minor_unit || 0); const api = +p.prices.price / unit, reg = +p.prices.regular_price / unit;
    if (p.on_sale) onSale++;
    const pg = await get(p.permalink); await sleep(1500);
    const html = pg.text || '';
    let ld = null; for (const m of html.matchAll(/<script[^>]+application\/ld\+json[^>]*>([\s\S]*?)<\/script>/g)) {
      try { const j = JSON.parse(m[1]); const all = [].concat(j['@graph'] || j); for (const x of all) if (/Product/.test(x['@type'])) { const o = [].concat(x.offers || [])[0] || {}; ld = +(o.price || (o.priceSpecification && [].concat(o.priceSpecification)[0].price) || o.lowPrice); } } catch (e) {}
    }
    const shown = (html.match(/<p class="price">([\s\S]*?)<\/p>/) || html.match(/class="[^"]*summary[^"]*"[\s\S]{0,4000}?woocommerce-Price-amount amount"><bdi>([\s\S]*?)<\/bdi>/) || [])[1];
    const vis = money((String(shown || '').match(/<ins[\s\S]*?<\/ins>/) || [shown])[0]);
    const issues = [];
    if (!pg.status || pg.status >= 400) issues.push('страница не открылась (' + (pg.status || pg.error) + ')');
    if (ld == null) issues.push('нет цены в данных для Google'); else if (Math.abs(ld - api) > 0.01) issues.push(`Google видит ${ld}, магазин ${api}`);
    if (vis != null && Math.abs(vis - api) > 0.01) issues.push(`на странице ${vis}, магазин ${api}`);
    if (!api) issues.push('цена 0');
    if (now0 < SALE_END && p.on_sale && reg && Math.abs(api / reg - 0.5) > 0.01) issues.push(`скидка ${Math.round((1 - api / reg) * 100)}%, а баннер обещает −50%`);
    if (issues.length) bad++;
    rows.push([p.name, p.permalink, reg, api, ld, vis, issues]);
  }
  const now = Date.now(), saleOn = now < SALE_END;
  const alerts = [];
  let md = `## 💶 Цены: одна цена везде\n\nТоваров: **${list.length}**, со скидкой в магазине: **${onSale}**, с расхождениями: **${bad}**.\n\n`;
  if (saleOn && !onSale) md += `⚠️ На сайте баннер **BLACK MONTH −50%** (до 30.11), но в магазине ни у одного товара нет скидки — скидка, видимо, считается своим модулем (\`modules/sale.php\`). Проверить, что клиент видит −50% в корзине (робот «Покупатель», шаг «Корзина»).\n\n`;
  if (!saleOn && onSale) { md += `🔴 Акция закончилась 30.11, а у ${onSale} товаров всё ещё скидка.\n\n`; alerts.push('Скидка осталась после конца акции'); }
  md += '| Товар | Обычная | Магазин | Google | На странице | Что не так |\n|---|---|---|---|---|---|\n' +
    rows.map(x => `| [${esc(x[0])}](${x[1]}) | ${x[2]} | ${x[3]} | ${x[4] ?? '—'} | ${x[5] ?? '—'} | ${x[6].length ? '⚠️ ' + esc(x[6].join('; ')) : '✅'} |`).join('\n') + '\n';
  if (bad) alerts.push(`Цены: расхождения у ${bad} товаров`);
  return { md, metrics: { prices_products: list.length, prices_mismatch: bad, prices_on_sale: onSale }, alerts };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
