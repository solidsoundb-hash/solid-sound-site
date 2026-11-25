// BUYER (owner 21.11.2026): walks the purchase path like a customer — home → catalog → product → add to cart → cart →
// checkout form — on a computer and on the owner's phone (Samsung A31). Never pays. Times every step, catches JS errors,
// and saves a screenshot where the path breaks.
'use strict';
const fs = require('fs'); const path = require('path');
const { chromium } = require('playwright');
const { BASE } = require('../bots/lib');
const OUT = process.env.SITEBOTS_OUT || 'reports/sitebots';
const DEV = {
  'компьютер': { viewport: { width: 1280, height: 900 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' },
  'Samsung A31': { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' },
};
const blocked = async p => /Захищена сторінка/.test(await p.title().catch(() => ''));

async function walk(browser, name, d) {
  const ctx = await browser.newContext({ ...d, locale: 'en-US' });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message.slice(0, 160)));
  let n429 = 0; p.on('response', r => { if (r.status() === 429 && r.url().includes('solid-sound.net')) n429++; }); // hosting rate limit, not a site bug
  const steps = []; let broken = '';
  const step = async (title, fn) => {
    if (broken) { steps.push([title, '—', 'пропущено']); return; }
    const t0 = Date.now(); let ok = true, note = ''; const c429 = n429;
    const busy = async () => /Service Unavailable|503 Service/i.test(await p.title().catch(() => '') + ' ' + await p.locator('h1').first().textContent({ timeout: 1000 }).catch(() => ''));
    for (let a = 0; a < 2; a++) { ok = true; note = '';
      try { note = (await fn()) || ''; } catch (e) { ok = false; note = e.message.split('\n')[0].slice(0, 160); }
      if (!ok && await busy()) { note = 'сервер перегружен (503)' + (a ? ' и через 30 с тоже' : ''); if (!a) { await p.waitForTimeout(30000); continue; } }
      break; }
    if (await blocked(p)) { ok = false; note = 'хостинг показал капчу (не ошибка сайта)'; }
    else if (!ok && n429 > c429) note += ' — хостинг ограничил частоту запросов (429), сервер перегружен, не ошибка сайта';
    const s = ((Date.now() - t0) / 1000).toFixed(1);
    steps.push([title, s + ' с', (ok ? '✅ ' : '❌ ') + note]);
    if (!ok) { broken = title; fs.mkdirSync(path.join(OUT, 'shots'), { recursive: true }); await p.screenshot({ path: path.join(OUT, 'shots', `buyer-${name === 'компьютер' ? 'pc' : 'a31'}.png`) }).catch(() => {}); }
  };
  let product = '';
  await step('Главная', async () => { await p.goto(BASE + '/en/', { waitUntil: 'domcontentloaded', timeout: 60000 }); await p.waitForSelector('#ss-app, #ssg', { timeout: 30000 }); });
  await step('Каталог', async () => { await p.goto(BASE + '/en/all-products/', { waitUntil: 'domcontentloaded', timeout: 60000 });
    const a = p.locator('a[href*="/product/"]').first(); await a.waitFor({ timeout: 30000 }); product = await a.getAttribute('href'); return 'первый товар: ' + product.replace(BASE, ''); });
  await step('Страница товара', async () => { await p.goto(product, { waitUntil: 'domcontentloaded', timeout: 60000 });
    await p.locator('.single_add_to_cart_button, button[name="add-to-cart"]').first().waitFor({ timeout: 30000 }); return (await p.title()).slice(0, 60); });
  await step('Добавить в корзину', async () => { const b = p.locator('.single_add_to_cart_button, button[name="add-to-cart"]').first();
    await b.scrollIntoViewIfNeeded();
    /* (25.11 tester) adding is AJAX (Store API add-item) and waits for Stripe's check first — once it took > 5 s; wait for the answer, not a fixed 4 s */
    const t0 = Date.now(); const ans = p.waitForResponse(r => /cart\/add-item|add-to-cart|wc-ajax=add_to_cart/.test(r.url()) && r.request().method() === 'POST', { timeout: 25000 }).catch(() => null);
    await b.click({ timeout: 15000 }); const r = await ans; const sec = ((Date.now() - t0) / 1000).toFixed(1);
    if (!r) await p.waitForTimeout(3000);
    if (r && r.status() >= 400) throw new Error(`сайт ответил ошибкой ${r.status()} через ${sec} с`);
    if (!(await ctx.cookies()).some(c => c.name === 'woocommerce_items_in_cart')) throw new Error(`кнопка нажата, товар не лёг в корзину за ${sec} с`);
    return `товар добавлен за ${sec} с` + (+sec > 3 ? ' ⚠️ медленно (покупатель видит паузу)' : ''); });
  await step('Корзина', async () => { await p.goto(BASE + '/en/cart/', { waitUntil: 'domcontentloaded', timeout: 60000 }); await p.waitForTimeout(2500);
    const n = await p.locator('.cart_item, .wc-block-cart-items__row, tr.woocommerce-cart-form__cart-item').count();
    if (!n) throw new Error('товар не попал в корзину'); const tot = await p.locator('.order-total .amount, .wc-block-components-totals-footer-item .wc-block-components-totals-item__value').first().textContent().catch(() => '');
    return `товаров: ${n}, итого ${String(tot).trim()}`; });
  await step('Оформление (форма видна)', async () => { await p.goto(BASE + '/en/checkout/', { waitUntil: 'domcontentloaded', timeout: 60000 });
    await p.locator('form.checkout, .wc-block-checkout').first().waitFor({ state: 'attached', timeout: 30000 });
    if (!await p.locator('#billing_email:visible, form.checkout input:visible, .wc-block-checkout input:visible').count()) throw new Error('форма оформления есть, но поля не видны');
    const pay = await p.locator('#payment, .wc-block-checkout__payment-method, iframe[name*="stripe" i], [id*="wcpay" i]').count(); const ex = await p.locator('#wcpay-express-checkout-element, #wcpay-express-checkout-wrapper, [id*="amazon" i], .ppc-button-wrapper').count();
    return (pay ? 'есть блок оплаты' : 'нет блока оплаты!') + (ex ? ', быстрые кнопки оплаты есть' : '') + '. Саму оплату робот не делает — проходит ли она, видно только по реальному заказу'; });
  await ctx.close();
  return { name, steps, errs: [...new Set(errs)], broken };
}

module.exports = async function buyer() {
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const res = []; for (const [n, d] of Object.entries(DEV)) res.push(await walk(browser, n, d)); await browser.close();
  let md = '## 🛒 Покупатель: путь до оформления заказа\n\nРобот проходит покупку как клиент (без оплаты). ❌ — здесь путь ломается, снимок в `' + OUT + '/shots/`.\n\n';
  for (const r of res) {
    md += `**${r.name}** — ${r.broken ? '❌ сломано на шаге «' + r.broken + '»' : '✅ весь путь пройден'}\n\n| Шаг | Время | Итог |\n|---|---|---|\n` + r.steps.map(s => `| ${s.join(' | ')} |`).join('\n') + '\n\n';
    if (r.errs.length) md += 'Ошибки скриптов: ' + r.errs.map(e => '`' + e.replace(/`/g, "'") + '`').join(', ') + '\n\n';
  }
  const bad = res.filter(r => r.broken && !/капчу|перегружен/.test(r.steps.find(s => s[0] === r.broken)[2]));
  const busy = res.filter(r => r.broken && /перегружен/.test(r.steps.find(s => s[0] === r.broken)[2]));
  return { md, metrics: { buyer_broken: bad.length, buyer_js_errors: res.reduce((a, r) => a + r.errs.length, 0) }, alerts: bad.map(r => `Покупка ломается (${r.name}) на шаге «${r.broken}»`).concat(busy.map(r => `Покупка: сервер не выдержал на шаге «${r.broken}» (${r.name}) — 503 дважды; если повторится без других роботов, это настоящая проблема хостинга`)) };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
