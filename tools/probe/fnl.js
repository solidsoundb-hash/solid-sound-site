// Probe (25.11): Solid Stats purchase funnel — the beacon script is on the page, robots (UTC) are skipped, and a real
// visitor's add to cart sends «add_to_cart» once. Beacons are intercepted (answered 204 here), so live stats stay clean.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-fnl'; fs.mkdirSync(OUT, { recursive: true });
const SITE = 'https://solid-sound.net';
const log = []; const L = (...a) => { const s = a.join(' '); log.push(s); console.log(s); };
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const tz of ['Europe/Paris', 'UTC']) {
    const ctx = await b.newContext({ timezoneId: tz, viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, locale: 'en-US', userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' });
    await ctx.addInitScript(() => { Object.defineProperty(navigator, 'webdriver', { get: () => false }); });
    const p = await ctx.newPage(); const sent = []; const errs = []; p.on('pageerror', e => errs.push(e.message));
    await p.route('**/admin-ajax.php*', r => { const d = r.request().postData() || ''; if (d.includes('ss_hit')) { sent.push((d.match(/name="(e|p)"\r\n\r\n([^\r]*)/g) || []).map(x => x.replace(/name="|"\r\n\r\n/g, ' ').trim()).join(',')); return r.fulfill({ status: 204 }); } r.continue(); });
    await p.goto(SITE + '/product/infinity/', { waitUntil: 'domcontentloaded', timeout: 90000 }).catch(e => L('goto', e.message));
    await p.waitForTimeout(5000);
    L(`\n=== ${tz} === ssEv: ${await p.evaluate(() => typeof window.ssEv)}`);
    const btn = await p.$('form.cart button[type=submit], .single_add_to_cart_button');
    if (btn) { await btn.click().catch(() => {}); await p.waitForTimeout(7000); }
    L('beacons:', JSON.stringify(sent)); L('js errors:', JSON.stringify(errs.slice(0, 5)));
    await p.screenshot({ path: `${OUT}/${tz.replace('/', '-')}.png` });
    /* empty the cart again */
    await p.evaluate(async () => { try { const r = await fetch('/wp-json/wc/store/v1/cart'); const c = await r.json(); const n = r.headers.get('Nonce'); for (const it of c.items || []) await fetch('/wp-json/wc/store/v1/cart/remove-item', { method: 'POST', headers: { 'Content-Type': 'application/json', Nonce: n }, body: JSON.stringify({ key: it.key }) }); } catch (e) {} });
    await ctx.close();
  }
  fs.writeFileSync(`${OUT}/report.txt`, log.join('\n')); await b.close();
})();
