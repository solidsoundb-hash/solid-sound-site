// Probe (21.11): the buying path, read-only — nothing is paid or sent.
// Product: sound plays → Add to cart → cart → checkout (form, total, payment methods) → cart emptied.
// Services: /studio-calculator/ shows prices, the request form is there (not submitted).
// Phone = Samsung A31 (412×700), PC = 1366×768. Output: PROBE_OUT/buy.txt + screenshots.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-buy'; fs.mkdirSync(OUT, { recursive: true });
const SITE = 'https://solid-sound.net';
const DEV = {
  phone: { viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' },
  pc: { viewport: { width: 1366, height: 768 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' }, // headless UA gets 403 from the host
};
const log = []; const L = (...a) => { const s = a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '); log.push(s); console.log(s); };
const T = () => Date.now();
/* (25.11) verdicts for the nightly purchase robot (.github/workflows/buy-nightly.yml): name → true/false */
const V = {}; const ok = (dev, k, v, what) => { V[dev + ' · ' + what] = !!v; };
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const [dev, opt] of Object.entries(DEV)) {
    L(`\n=== ${dev} ===`);
    const ctx = await b.newContext({ ...opt, locale: 'en-US' });
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    await ctx.addInitScript(() => { window.__media = []; const o = HTMLMediaElement.prototype.play; HTMLMediaElement.prototype.play = function () { window.__media.push(this); return o.apply(this, arguments); }; });
    const p = await ctx.newPage(); const errs = [];
    p.on('pageerror', e => errs.push(e.message.slice(0, 140)));
    const shot = n => p.screenshot({ path: `${OUT}/${dev}_${n}.jpg`, type: 'jpeg', quality: 60 }).catch(() => {});
    const go = async (path, n) => { const t = T(); const r = await p.goto(SITE + path, { waitUntil: 'domcontentloaded', timeout: 90000 }).catch(e => null); const cc = await p.$('.ss-cc-no'); if (cc && await cc.isVisible().catch(() => false)) { await cc.click().catch(() => {}); L('  (cookie-баннер: нажал Reject non-essential)'); } await p.waitForTimeout(3000); L(`${n}: ${path} → ${r ? r.status() : 'ERR'} за ${((T() - t) / 1000).toFixed(1)} с`); await shot(n); return r; };

    // 1. product
    await go('/product/infinity/', '1_product');
    const price = await p.$eval('.summary .price, .product .price', e => e.innerText.replace(/\s+/g, ' ').trim()).catch(() => '—');
    L('  цена на товаре:', price);
    L('  кнопки play:', await p.evaluate(() => [...document.querySelectorAll('button, [role=button]')].filter(e => /play/i.test((e.getAttribute('aria-label') || '') + ' ' + e.className) && !e.closest('[class*="yt"], [class*="youtube"]')).slice(0, 4).map(e => (e.getAttribute('aria-label') || '') + ' .' + String(e.className).slice(0, 40))));
    const playBtn = (await p.$('.ssw-btn:visible, .summary button[aria-label^="Play" i]:visible')) || /* (25.11) the product's own ▶ — not the empty bottom player */ await p.evaluateHandle(() => [...document.querySelectorAll('button, [role=button]')].find(e => /play/i.test((e.getAttribute('aria-label') || '') + ' ' + e.className) && !e.closest('[class*="yt"], [class*="youtube"]') && e.getBoundingClientRect().height > 0) || null).then(h => h.asElement());
    if (playBtn) {
      await playBtn.click({ timeout: 5000 }).catch(() => {}); await p.waitForTimeout(3500);
      const playing = await p.evaluate(() => [...document.querySelectorAll('audio,video'), ...window.__media].some(a => !a.paused && a.currentTime > 0.2));
      ok(dev, 'sound', playing, 'звук ▶ на товаре');
      L('  звук после ▶:', playing ? 'играет ✅' : 'НЕ играет ❌', await p.evaluate(() => window.__media.map(a => (a.currentSrc || a.src || '').split('/').pop() + ' t=' + a.currentTime.toFixed(1) + (a.error ? ' err' + a.error.code : '')).slice(0, 3)));
      await p.evaluate(() => [...document.querySelectorAll('audio,video'), ...window.__media].forEach(a => a.pause()));
    } else L('  кнопка ▶ не найдена');
    const add = await p.$('.single_add_to_cart_button');
    ok(dev, 'add', add, 'кнопка Add to cart');
    if (!add) { L('  кнопки Add to cart нет ❌'); }
    else {
      await add.scrollIntoViewIfNeeded().catch(() => {});
      const t0 = T(); const ans = p.waitForResponse(r => /cart\/add-item|add-to-cart|wc-ajax=add_to_cart/.test(r.url()) && r.request().method() === 'POST', { timeout: 25000 }).catch(() => null);
      await add.click({ timeout: 8000 }).catch(e => L('  клик Add to cart:', e.message.slice(0, 80))); const r = await ans; /* (25.11) wait for the site's answer: it once took > 5 s */
      L('  товар добавлен за', ((T() - t0) / 1000).toFixed(1), 'с', r ? r.status() : 'нет ответа'); await p.waitForTimeout(1500); await shot('2_added'); L('  после Add to cart:', p.url());
    }
    // 2. cart
    await go('/en/cart/', '3_cart');
    const rows = await p.$$eval('.cart_item, .woocommerce-cart-form__cart-item, .wc-block-cart-items__row', x => x.length).catch(() => 0);
    const tot = await p.$eval('.order-total, .wc-block-components-totals-footer-item', e => e.innerText.replace(/\s+/g, ' ').trim()).catch(() => '—');
    ok(dev, 'cart', rows > 0, 'товар попал в корзину');
    L('  позиций в корзине:', rows, rows ? '✅' : '❌', '| итог:', tot);
    // 3. checkout
    await go('/en/checkout/', '4_checkout');
    const ck = await p.evaluate(() => ({
      form: !!document.querySelector('form.checkout, .wc-block-checkout'),
      email: !!document.querySelector('#billing_email, input[type=email]'),
      pay: [...document.querySelectorAll('.wc_payment_method label, .wc-block-components-radio-control__label, .wc-block-components-payment-method-label')].map(e => e.innerText.trim()).filter(Boolean).slice(0, 6),
      express: !!document.querySelector('[id*=express-checkout], .wcpay-express-checkout-wrapper'),
      btn: (document.querySelector('#place_order, .wc-block-components-checkout-place-order-button') || {}).innerText || '',
      total: ((document.querySelector('.order-total, .wc-block-components-totals-footer-item') || {}).innerText || '').replace(/\s+/g, ' ').trim(),
    }));
    ok(dev, 'form', ck.form && ck.email, 'форма оформления заказа'); ok(dev, 'pay', ck.pay.length || ck.express, 'способы оплаты видны');
    L('  оформление:', ck.form ? 'форма есть ✅' : 'ФОРМЫ НЕТ ❌', '| e-mail:', ck.email ? 'да' : 'нет', '| способы оплаты:', ck.pay.length ? ck.pay.join(', ') : 'НЕ ВИДНЫ', '| быстрая оплата:', ck.express ? 'да' : 'нет', '| кнопка:', ck.btn.trim() || 'нет', '| итог:', ck.total);
    await p.waitForTimeout(4000); await p.evaluate(() => window.scrollTo(0, document.body.scrollHeight / 2)); await shot('5_checkout_pay');
    // 4. empty cart
    await go('/en/cart/', '6_cart_clean');
    for (let i = 0; i < 5; i++) { try { const r = await p.$('.cart_item a.remove, a.remove, .wc-block-cart-item__remove-link'); if (!r) break; await Promise.all([p.waitForLoadState('domcontentloaded').catch(() => {}), r.click().catch(() => {})]); await p.waitForTimeout(3000); } catch (e) { await p.waitForTimeout(3000); } }
    L('  корзина после ×:', await p.$$eval('.cart_item', x => x.length).catch(() => '?'), 'позиций');
    // 5. services
    await go('/studio-calculator/', '7_calc');
    const calc = await p.evaluate(() => ({ svc: document.querySelectorAll('[data-s]').length, euros: (document.body.innerText.match(/€\s?\d+/g) || []).slice(0, 6), form: !!document.querySelector('form[action*="admin-post"] input[name=email], input[name=email]') }));
    ok(dev, 'calc', calc.form && calc.svc > 0, 'калькулятор услуг и форма заявки');
    L('  калькулятор: услуг', calc.svc, '| цены на экране:', calc.euros.join(' '), '| форма заявки:', calc.form ? 'есть ✅' : 'НЕТ ❌');
    await p.evaluate(() => { const f = document.querySelector('input[name=email]'); if (f) f.scrollIntoView({ block: 'center' }); }); await shot('8_calc_form');
    ok(dev, 'js', !errs.length, 'без ошибок скриптов');
    L('  ошибки JS:', errs.length ? errs : 'нет');
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/buy.txt', log.join('\n') + '\n'); fs.writeFileSync(OUT + '/buy.json', JSON.stringify({ date: new Date().toISOString(), checks: V }, null, 1)); await b.close();
})();
