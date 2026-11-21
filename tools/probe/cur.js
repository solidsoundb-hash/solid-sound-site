// Probe (21.11): currencies — default EUR, switcher in the header, ?currency=GBP, the choice kept on the next pages
// (WP Super Cache must give the GBP copy), cart in GBP, then back to EUR. Phone A31 + PC. Nothing is bought; the cart is emptied.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-cur'; fs.mkdirSync(OUT, { recursive: true });
const SITE = 'https://solid-sound.net';
const DEV = {
  phone: { viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' },
  pc: { viewport: { width: 1366, height: 768 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' },
};
const log = []; const L = (...a) => { const s = a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '); log.push(s); console.log(s); };
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const [dev, opt] of Object.entries(DEV)) {
    L(`\n=== ${dev} ===`);
    const ctx = await b.newContext({ ...opt, locale: 'en-US' });
    await ctx.addCookies([{ name: 'ss_consent', value: 'essential', domain: 'solid-sound.net', path: '/' }]);
    const p = await ctx.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message.slice(0, 140)));
    const shot = n => p.screenshot({ path: `${OUT}/${dev}_${n}.jpg`, type: 'jpeg', quality: 60 }).catch(() => {});
    const info = async () => p.evaluate(() => ({
      cur: (window.SSCUR || {}).c, sym: (window.SSCUR || {}).s, n: ((window.SSCUR || {}).l || []).length,
      price: ((document.querySelector('.summary .price, .product .price') || {}).innerText || '').replace(/\s+/g, ' ').trim().slice(0, 60),
      btn: !!document.querySelector('#ssg .g-cur'), btnTxt: ((document.querySelector('#ssg .g-cur') || {}).textContent || ''),
      mix: ((document.querySelector('.ss-mixup') || {}).innerText || '').replace(/\s+/g, ' ').trim(),
      cookie: (document.cookie.match(/ss_cur=([A-Z]+)/) || [])[1] || '—',
    }));
    const go = async (path, n) => { await p.goto(SITE + path, { waitUntil: 'domcontentloaded', timeout: 90000 }).catch(() => {}); await p.waitForTimeout(3500); const i = await info(); L(`${n} ${path}:`, i); await shot(n); return i; };
    await go('/product/infinity/', '1_default');
    await go('/en/', '2_home');
    if (dev === 'pc') { const btn = await p.$('#ssg .g-cur'); if (btn) { await btn.click().catch(() => {}); await p.waitForTimeout(800); await shot('3_switcher_open'); } }
    else { const bg = await p.$('#ssg .g-burger'); if (bg) { await bg.click().catch(() => {}); await p.waitForTimeout(800); await p.evaluate(() => { const g = [...document.querySelectorAll('#ssg-sheet .s-g')].find(x => /Currency/.test(x.textContent)); if (g) g.scrollIntoView(); }); await shot('3_sheet_currency'); await p.keyboard.press('Escape').catch(() => {}); } }
    // pick GBP the way a visitor does: the link in the header list
    const href = await p.$eval('a[data-cur="GBP"]', a => a.getAttribute('href')).catch(() => null);
    L('  ссылка GBP:', href);
    await p.evaluate(() => { document.cookie = 'ss_cur=GBP;path=/;max-age=600'; });
    await go('/product/infinity/?currency=GBP', '4_gbp');
    await go('/product/intricate/', '5_gbp_next_page');
    await go('/en/', '6_gbp_home');
    const add = await p.$('.single_add_to_cart_button');
    await p.goto(SITE + '/product/infinity/', { waitUntil: 'domcontentloaded' }).catch(() => {}); await p.waitForTimeout(2500);
    const a2 = await p.$('.single_add_to_cart_button'); if (a2) { await a2.scrollIntoViewIfNeeded().catch(() => {}); await a2.click({ timeout: 8000 }).catch(() => {}); await p.waitForTimeout(3500); }
    await p.goto(SITE + '/en/cart/', { waitUntil: 'domcontentloaded' }).catch(() => {}); await p.waitForTimeout(3000);
    L('  корзина в GBP:', await p.$eval('.order-total, .cart-subtotal', e => e.innerText.replace(/\s+/g, ' ').trim()).catch(() => '—')); await shot('7_cart_gbp');
    for (let i = 0; i < 5; i++) { try { const r = await p.$('.cart_item a.remove'); if (!r) break; await Promise.all([p.waitForLoadState('domcontentloaded').catch(() => {}), r.click()]); await p.waitForTimeout(3000); } catch (e) { await p.waitForTimeout(3000); } }
    await p.evaluate(() => { document.cookie = 'ss_cur=EUR;path=/;max-age=600'; });
    await go('/product/infinity/?currency=EUR', '8_back_eur');
    L('  ошибки JS:', errs.length ? errs : 'нет');
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/cur.txt', log.join('\n') + '\n'); await b.close();
})();
