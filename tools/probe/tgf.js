// Probe (25.11): «Night buyer» says Add to cart fails on /product/time-goes-fast-documentary-piano/ — repeat by hand, log everything.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-tgf'; fs.mkdirSync(OUT, { recursive: true });
const SITE = 'https://solid-sound.net'; const log = []; const L = (...a) => { const s = a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '); log.push(s); console.log(s); };
const DEV = { phone: { viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' },
  pc: { viewport: { width: 1366, height: 768 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' } };
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const [dev, opt] of Object.entries(DEV)) for (const u of ['/product/time-goes-fast-documentary-piano/', '/product/infinity/']) {
    L(`\n=== ${dev} ${u}`);
    const ctx = await b.newContext({ ...opt, locale: 'en-US' }); const p = await ctx.newPage(); const errs = [];
    p.on('pageerror', e => errs.push(e.message.slice(0, 120)));
    p.on('request', r => { if (r.method() !== 'GET' || /add-to-cart|wc-ajax|cart/.test(r.url())) L('  запрос:', r.method(), r.url().replace(SITE, '').slice(0, 110)); });
    p.on('response', r => { if (/add-to-cart|wc-ajax=add|\/cart\//.test(r.url())) L('  ответ:', r.status(), r.url().replace(SITE, '').slice(0, 90)); });
    await p.goto(SITE + u, { waitUntil: 'domcontentloaded', timeout: 90000 }).catch(e => L('  goto', e.message.slice(0, 80)));
    await p.waitForTimeout(3000); const cc = await p.$('.ss-cc-no'); if (cc) await cc.click().catch(() => {});
    const btn = p.locator('.single_add_to_cart_button, button[name="add-to-cart"]').first();
    L('  кнопка:', await btn.evaluate(e => ({ tag: e.tagName, text: e.innerText.trim(), cls: e.className, type: e.type, form: !!e.form, action: e.form && e.form.action, name: e.name, val: e.value, disabled: e.disabled })).catch(e => 'нет: ' + e.message.slice(0, 60)));
    await btn.scrollIntoViewIfNeeded().catch(() => {}); await p.screenshot({ path: `${OUT}/${dev}_${u.split('/')[2].slice(0, 12)}_before.jpg`, type: 'jpeg', quality: 60 });
    await btn.click({ timeout: 15000 }).catch(e => L('  клик:', e.message.slice(0, 120)));
    await p.waitForTimeout(5000);
    L('  после клика адрес:', p.url().replace(SITE, ''), '| cookies:', (await ctx.cookies()).map(c => c.name).filter(n => /woo|cart|wp_/.test(n)));
    await p.screenshot({ path: `${OUT}/${dev}_${u.split('/')[2].slice(0, 12)}_after.jpg`, type: 'jpeg', quality: 60 });
    await p.goto(SITE + '/en/cart/', { waitUntil: 'domcontentloaded', timeout: 90000 }).catch(() => {}); await p.waitForTimeout(2500);
    L('  в корзине позиций:', await p.$$eval('.cart_item, .wc-block-cart-items__row', x => x.length).catch(() => '?'), '| ошибки JS:', errs);
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/tgf.txt', log.join('\n') + '\n'); await b.close();
})();
