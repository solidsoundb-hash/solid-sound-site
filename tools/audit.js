// Site audit: for each page and width — HTTP status, JS/console errors, horizontal overflow (and the widest
// offending element), broken images, failed same-site requests, visible "Fatal error". Writes reports/audit.json.
// node tools/audit.js [outdir]
const { chromium } = require('playwright');
const fs = require('fs');
const S = 'https://solid-sound.net';
const PAGES = ['/en/', '/en/all-products/', '/templates', '/presets', '/music', '/product/infinity/', '/product/melodic-techno-presets/',
  '/en/services-prices/', '/Services-Calculator/', '/mastering-album-calculator/', '/en/free/', '/en/free-demo-feedback/', '/en/wiki/',
  '/en/faq/', '/contacts', '/sell/', '/en/vendor-register/', '/en/cart/', '/en/my-account/', '/en/terms/', '/privacy-policy/',
  '/license/', '/refund_returns/', '/en/legal-notice/', '/de/', '/ua/', '/forum/'];
const out = process.argv[2] || 'reports/audit';
const WIDTHS = (process.env.WIDTHS || '390,1280').split(',').map(Number);
const ONLY = process.env.PAGES ? process.env.PAGES.split(',') : null;
fs.mkdirSync(out, { recursive: true });
(async () => {
  const b = await chromium.launch(process.env.HTTPS_PROXY ? { proxy: { server: process.env.HTTPS_PROXY } } : {});
  const res = [];
  for (const w of WIDTHS) {
    const ctx = await b.newContext({ viewport: { width: w, height: 900 }, isMobile: w < 600, hasTouch: w < 600, deviceScaleFactor: 1,
      userAgent: w < 600 ? 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' });
    let active = 0; const q = []; const slot = () => new Promise(r => active < 2 ? (active++, r()) : q.push(r)); const free = () => { const n = q.shift(); n ? n() : active--; };
    await ctx.route(/solid-sound\.net/, async r => { await slot(); try { let x; for (let i = 0; i < 6; i++) { x = await r.fetch(); if (x.status() !== 429) break; await new Promise(z => setTimeout(z, 4000 * (i + 1))); } await r.fulfill({ response: x }); } catch (e) { try { await r.abort(); } catch (_) {} } finally { free(); } });
    for (const p of (ONLY || PAGES)) {
      const page = await ctx.newPage(); const errs = [], bad = [];
      page.on('pageerror', e => errs.push('JS: ' + e.message.slice(0, 180)));
      page.on('console', m => { if (m.type() === 'error' && !/Failed to load resource|SSL certificate|CanMakePayment|ERR_TUNNEL/.test(m.text())) errs.push('console: ' + m.text().slice(0, 180)); });
      page.on('response', r => { const u = r.url(); if (/solid-sound\.net/.test(u) && r.status() >= 400 && r.status() !== 429) bad.push(r.status() + ' ' + u.slice(0, 140)); });
      let status = 0;
      try { const r = await page.goto(S + p, { waitUntil: 'load', timeout: 180000 }); status = r ? r.status() : 0; await page.waitForTimeout(3000); }
      catch (e) { res.push({ w, p, status: 'load error', err: e.message.split('\n')[0] }); await page.close(); continue; }
      const info = await page.evaluate(() => {
        const vw = document.documentElement.clientWidth, over = [];
        if (document.documentElement.scrollWidth > vw + 1) {
          document.querySelectorAll('body *').forEach(el => { const r = el.getBoundingClientRect(); if (r.width && r.right > vw + 2 && getComputedStyle(el).position !== 'fixed') over.push({ w: Math.round(r.right - vw), el: el.tagName.toLowerCase() + (el.id ? '#' + el.id : '') + (el.className && typeof el.className === 'string' ? '.' + el.className.trim().split(/\s+/).slice(0, 3).join('.') : '') }); });
          over.sort((a, b) => b.w - a.w);
        }
        const brokenImg = [...document.images].filter(i => i.complete && i.naturalWidth === 0 && i.src && !/data:/.test(i.src) && i.offsetParent).map(i => i.src.slice(0, 120));
        return { hscroll: document.documentElement.scrollWidth - vw, over: over.slice(0, 4), brokenImg: brokenImg.slice(0, 5), title: document.title.slice(0, 70),
          fatal: /There has been a critical error|Fatal error:/i.test(document.body.innerText.slice(0, 8000)), menu: !!document.querySelector('#ssg, #ss-app header') };
      });
      res.push({ w, p, status, ...info, errs: [...new Set(errs)].slice(0, 6), bad: [...new Set(bad)].slice(0, 6) });
      const s = res[res.length - 1];
      fs.appendFileSync(out + '/audit.jsonl', JSON.stringify(s) + '\n');
      console.log(`${w} ${p} → ${status} ${s.hscroll > 0 ? 'HSCROLL+' + s.hscroll + ' ' + JSON.stringify(s.over.slice(0, 2)) : ''} ${s.errs.length ? 'ERR ' + s.errs.length : ''} ${s.bad.length ? 'BAD ' + s.bad.join(' | ') : ''} ${s.brokenImg.length ? 'IMG ' + s.brokenImg.length : ''} ${s.fatal ? 'FATAL' : ''} ${s.menu ? '' : 'NO-MENU'}`);
      if (w === 390 || s.hscroll > 0 || s.errs.length) await page.screenshot({ path: `${out}/${w}_${p.replace(/\W+/g, '_')}.jpg`, type: 'jpeg', quality: 55 });
      await page.close();
    }
    await ctx.close();
  }
  fs.writeFileSync(out + '/audit.json', JSON.stringify(res, null, 1));
  await b.close();
})();
