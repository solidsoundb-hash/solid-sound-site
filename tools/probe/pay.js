// Probe (20.11): product page — express pay scripts wait for the first action, then the buttons appear without errors.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const log = []; const L = (...a) => log.push(a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '));
  for (const q of ['', '?ss_pay_now=1']) {
    const ctx = await b.newContext({ viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, locale: 'en-US', userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' }); // same phone as tools/page-shot.js
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    const p = await ctx.newPage(); let n = 0, pay = 0; const errs = [];
    p.on('response', r => { n++; if (/stripe|pay\.google|hcaptcha/.test(r.url())) pay++; });
    p.on('pageerror', e => errs.push(e.message.slice(0, 150)));
    await p.goto('https://solid-sound.net/product/infinity/' + q, { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {});
    await p.waitForTimeout(2500);
    L(q || 'NEW', 'before any action: requests', n, 'payment requests', pay);
    const box = async () => p.evaluate(() => { const e = document.querySelector('#wcpay-express-checkout-element, .wcpay-express-checkout-wrapper, [id*=express-checkout]'); if (!e) return 'no box'; const r = e.getBoundingClientRect(); return { id: e.id || e.className, h: Math.round(r.height), iframes: e.querySelectorAll('iframe').length, shown: getComputedStyle(e).display !== 'none' }; });
    L('  box before', await box());
    await p.evaluate(() => window.dispatchEvent(new Event('scroll'))); await p.mouse.wheel(0, 300); await p.waitForTimeout(9000); L('  url', p.url());
    L('  after a scroll: requests', n, 'payment requests', pay, 'box', await box(), 'errors', errs);
    const el = await p.$('#wcpay-express-checkout-element, .wcpay-express-checkout-wrapper');
    if (el) { await el.scrollIntoViewIfNeeded().catch(() => {}); await p.waitForTimeout(800); await p.screenshot({ path: `${OUT}/buy${q ? '_old' : ''}.jpg`, type: 'jpeg', quality: 65 }); }
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/pay.txt', log.join('\n') + '\n'); console.log(log.join('\n')); await b.close();
})();
