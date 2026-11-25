// Probe (25.11, «Тестировщик» за Разработчика): mobile cart. Catalog → first product → add to cart → /cart/.
// Pictures: A31 412×700 and 412×780 (top of the page + whole page), PC 1366, A31 with html.ss-lite; DOM outline of the
// cart and where «Proceed to checkout» is (is it on the first screen? is it covered by the cookie banner / bottom bar?).
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-cart'; fs.mkdirSync(OUT, { recursive: true });
const SITE = 'https://solid-sound.net';
/* preview mode (PREVIEW=1, .github/workflows/cart-probe.yml) → inject tools/probe/cart-inject.json (new CSS + JS) */
const CO = process.env.PAGE === 'checkout'; // (25.11) same walk, but ends on /en/checkout/
let INJ = null; try { if (process.env.PREVIEW) INJ = JSON.parse(fs.readFileSync(CO ? 'tools/probe/co-inject.json' : 'tools/probe/cart-inject.json', 'utf8')); } catch (e) { /* no preview */ }
const A31 = { isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' };
const DEV = {
  a31_700: { ...A31, viewport: { width: 412, height: 700 } },
  a31_780: { ...A31, viewport: { width: 412, height: 780 } },
  a31_lite: { ...A31, viewport: { width: 412, height: 780 }, lite: true },
  pc: { viewport: { width: 1366, height: 768 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' },
};
const log = []; const L = (...a) => { const s = a.join(' '); log.push(s); console.log(s); };
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  let product = '';
  for (const [dev, o] of Object.entries(DEV).filter(([k]) => !process.env.DEVS || process.env.DEVS.split(',').includes(k))) {
    const { lite, ...opt } = o;
    const ctx = await b.newContext({ ...opt, locale: 'en-US' });
    const p = await ctx.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message.slice(0, 140)));
    try {
      if (!product) { await p.goto(SITE + '/en/all-products/', { waitUntil: 'domcontentloaded', timeout: 60000 });
        const a = p.locator('a[href*="/product/"]').first(); await a.waitFor({ timeout: 30000 }); product = await a.getAttribute('href'); L('product', product); }
      await p.goto(product, { waitUntil: 'domcontentloaded', timeout: 60000 });
      const btn = p.locator('.single_add_to_cart_button, button[name="add-to-cart"]').first(); await btn.waitFor({ timeout: 30000 });
      const ans = p.waitForResponse(r => /cart\/add-item|add-to-cart|wc-ajax=add_to_cart/.test(r.url()) && r.request().method() === 'POST', { timeout: 25000 }).catch(() => null);
      await btn.click(); await ans;
      for (let i = 0; i < 20; i++) { if ((await ctx.cookies()).some(c => c.name === 'woocommerce_items_in_cart')) break; await p.waitForTimeout(500); }
      if (CO) { await p.goto(SITE + '/en/checkout/', { waitUntil: 'domcontentloaded', timeout: 60000 }); await p.waitForSelector('form.checkout, .wc-block-checkout', { timeout: 30000 }); }
      else { await p.goto(SITE + '/en/cart/', { waitUntil: 'domcontentloaded', timeout: 60000 });
      await p.waitForSelector('.woocommerce-cart-form, .wc-block-cart, .cart-empty', { timeout: 30000 }); }
      if (lite) await p.evaluate(() => document.documentElement.classList.add('ss-lite'));
      if (INJ) { await p.addStyleTag({ content: INJ.css }); await p.evaluate(js => { (0, eval)('(function(){' + js + '})()'); }, INJ.js); } // preview of the fix before deploy
      await p.waitForTimeout(2500);
      await p.screenshot({ path: `${OUT}/${dev}.jpg`, type: 'jpeg', quality: 65 });
      await p.screenshot({ path: `${OUT}/${dev}-full.jpg`, type: 'jpeg', quality: 55, fullPage: true });
      const info = await p.evaluate(() => {
        const r = s => { const e = document.querySelector(s); if (!e) return 'нет'; const b = e.getBoundingClientRect(); const cs = getComputedStyle(e); return `top ${Math.round(b.top)} h ${Math.round(b.height)} w ${Math.round(b.width)} ${cs.display === 'none' || !b.height ? '(скрыт)' : ''} pos ${cs.position}`; };
        const out = { vh: innerHeight, html: document.documentElement.className, body: document.body.className.slice(0, 200) };
        for (const s of ['.ss-gsi-co', '.woocommerce-form-login-toggle', '.woocommerce-form-coupon-toggle', '.woocommerce-billing-fields', '#billing_first_name, #billing_email', '#place_order', '#order_review', '.checkout-button', '.wc-proceed-to-checkout', '.cart_totals', '.coupon', 'button[name=update_cart]', '.woocommerce-cart-form', '.cart-collaterals', '#ss-cc, .ss-cc, [id*=consent], [class*=consent]', '#ssg-bar, .ssg-bar, nav.ssg-tab, .ssg-tabbar', '.ss-bonus, [class*=bonus]']) out[s] = r(s);
        const walk = (el, d) => d > 6 ? '' : [...el.children].map(c => '  '.repeat(d) + c.tagName.toLowerCase() + (c.id ? '#' + c.id : '') + (typeof c.className === 'string' && c.className.trim() ? '.' + c.className.trim().split(/\s+/).join('.') : '') + '\n' + walk(c, d + 1)).join('');
        const m = document.querySelector('form.checkout') || document.querySelector('.woocommerce'); out.dom = m ? walk(m, 0).slice(0, 5000) : 'нет .woocommerce';
        const fixed = [...document.querySelectorAll('body *')].filter(e => { const cs = getComputedStyle(e); return (cs.position === 'fixed' || cs.position === 'sticky') && e.getBoundingClientRect().height > 20; }).map(e => e.tagName.toLowerCase() + (e.id ? '#' + e.id : '') + '.' + String(e.className).trim().split(/\s+/).slice(0, 3).join('.') + ' top ' + Math.round(e.getBoundingClientRect().top) + ' h ' + Math.round(e.getBoundingClientRect().height));
        out.fixed = fixed.join(' | ');
        return out;
      });
      L(`\n=== ${dev} ===`); for (const [k, v] of Object.entries(info)) L(k + ': ' + v);
    } catch (e) { L(dev, 'ОШИБКА', e.message.split('\n')[0]); await p.screenshot({ path: `${OUT}/${dev}-err.jpg`, type: 'jpeg', quality: 60 }).catch(() => {}); }
    L(`${dev}: JS errors: ${errs.length ? errs.join(' | ') : 0}`);
    await ctx.close();
  }
  fs.writeFileSync(`${OUT}/cart.txt`, log.join('\n') + '\n');
  await b.close();
})();
