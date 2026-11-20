// Probe (20.11): the home page with the shared header (?ss_one=1) — is #ssg / #ssg-bar / footer there and visible, the app's own hidden?
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined }); const log = [];
  const ctx = await b.newContext({ viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, locale: 'en-US', userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' }); // same phone as tools/page-shot.js
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message.slice(0, 150)));
  await p.goto('https://solid-sound.net/en/?ss_one=1&r=' + Date.now(), { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {}); await p.waitForTimeout(3000);
  const r = await p.evaluate(() => { const v = (sel) => { const e = document.querySelector(sel); if (!e) return 'нет'; const r = e.getBoundingClientRect(), s = getComputedStyle(e); return s.display === 'none' ? 'скрыт' : `виден ${Math.round(r.width)}×${Math.round(r.height)} @${Math.round(r.top)}`; };
    return { body: document.body.className.split(' ').filter(c => /ss-one|ss-newhome|ssg/.test(c)), ssg: v('#ssg'), bar: v('#ssg-bar'), promo: v('#ssg-promo'), foot: v('.ssf'), appHeader: v('#ss-app header'), appNav: v('#ss-app nav[aria-label="App"]'), appFooter: v('#ss-app footer') }; });
  log.push(JSON.stringify(r), 'errors: ' + JSON.stringify(errs));
  await p.screenshot({ path: OUT + '/top.jpg', type: 'jpeg', quality: 65 });
  await p.evaluate(() => scrollTo(0, document.body.scrollHeight)); await p.waitForTimeout(1200); await p.screenshot({ path: OUT + '/bottom.jpg', type: 'jpeg', quality: 65 });
  fs.writeFileSync(OUT + '/one.txt', log.join('\n') + '\n'); console.log(log.join('\n')); await b.close();
})();
