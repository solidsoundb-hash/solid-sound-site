// Read-only look at our own creator area (WCFM store manager) as the test admin and the test vendor.
// Logs in on /wp-login.php with secrets TEST_ADMIN_USER/PASS and TEST_VENDOR_USER/PASS, then ONLY opens pages,
// takes screenshots and notes what is on them (menu items, errors, light/white fields). No form is submitted
// except the login form. Output: sm-out/ -> private workflow artifact (7 days), never committed.
const { chromium } = require('playwright');
const fs = require('fs');
const OUT = 'sm-out';
const BASE = 'https://solid-sound.net';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const PAGES = ['/en/store-manager/', '/en/store-manager/ss-add-product/', '/en/store-manager/ss-products/', '/en/store-manager/ss-earnings/',
  '/en/store-manager/ss-payouts/', '/en/store-manager/ss-reviews/', '/en/store-manager/ss-settings/', '/en/store-manager/ss-help/', '/en/my-account/'];

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const log = [];
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const who of [{ n: 'vendor', u: process.env.TEST_VENDOR_USER, p: process.env.TEST_VENDOR_PASS }, { n: 'admin', u: process.env.TEST_ADMIN_USER, p: process.env.TEST_ADMIN_PASS }]) {
    if (!who.u || !who.p) { log.push(`## ${who.n}: no secrets set — skipped`); continue; }
    for (const dev of [{ n: 'desktop', o: { viewport: { width: 1440, height: 900 } } }, { n: 'phone', o: { viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true } }]) {
      const ctx = await browser.newContext({ ...dev.o, userAgent: dev.n === 'phone' ? 'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' });
      await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
      const page = await ctx.newPage();
      const errs = []; page.on('pageerror', e => errs.push(e.message));
      await page.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 90000 });
      await page.fill('#user_login', who.u); await page.fill('#user_pass', who.p);
      await Promise.all([page.waitForNavigation({ timeout: 90000 }).catch(() => {}), page.click('#wp-submit')]);
      await sleep(2500);
      const logged = await page.evaluate(() => document.body.classList.contains('logged-in')).catch(() => false) || !/wp-login/.test(page.url());
      log.push(`## ${who.n} / ${dev.n}: after login → ${page.url()} (${logged ? 'logged in' : 'NOT logged in'})`);
      if (!logged) { await page.screenshot({ path: `${OUT}/${who.n}_${dev.n}_login.jpg`, type: 'jpeg', quality: 70 }); await ctx.close(); continue; }
      let i = 0;
      for (const path of (who.n === 'admin' && dev.n === 'phone') ? PAGES.slice(0, 2) : PAGES) {
        errs.length = 0;
        const r = await page.goto(BASE + path, { waitUntil: 'networkidle', timeout: 90000 }).catch(e => null);
        await sleep(2500);
        const info = await page.evaluate(() => {
          const menu = [...document.querySelectorAll('#wcfm_menu .wcfm_menu_item, #wcfm_menu a.wcfm_menu_item, #wcfm_menu .wcfm_menu_items a')].map(a => (a.innerText || a.title || '').replace(/\s+/g, ' ').trim()).filter(Boolean);
          const h = (document.querySelector('h1, h2') || {}).textContent || '';
          const light = [...document.querySelectorAll('input:not([type=hidden]):not([type=checkbox]):not([type=radio]), textarea, select, .select2-selection')].filter(e => e.offsetParent).filter(e => { const c = getComputedStyle(e).backgroundColor.match(/\d+/g); return c && (+c[0] + +c[1] + +c[2]) / 3 > 200; }).map(e => e.name || e.id || e.className).slice(0, 15);
          const small = [...document.querySelectorAll('body *')].filter(e => e.offsetParent && e.children.length === 0 && e.textContent.trim() && getComputedStyle(e).color.match(/\d+/g) && (() => { const c = getComputedStyle(e).color.match(/\d+/g); return (+c[0] + +c[1] + +c[2]) / 3 > 200 && (() => { let x = e; while (x && getComputedStyle(x).backgroundColor === 'rgba(0, 0, 0, 0)') x = x.parentElement; const b = x ? getComputedStyle(x).backgroundColor.match(/\d+/g) : null; return b && (+b[0] + +b[1] + +b[2]) / 3 > 200; })(); })()).map(e => e.textContent.trim().slice(0, 30)).slice(0, 10);
          return { title: document.title, h: h.trim().slice(0, 80), kudos: ['.ss-kd-win', '.ss-kd-lad', '.ss-kd-list'].filter(q => document.querySelector(q)), menu, light, lowContrast: small, overflow: document.documentElement.scrollWidth > innerWidth + 2 };
        }).catch(e => ({ err: e.message }));
        i++;
        const f = `${who.n}_${dev.n}_${String(i).padStart(2, '0')}_${path.replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '') || 'home'}.jpg`;
        await page.screenshot({ path: `${OUT}/${f}`, type: 'jpeg', quality: 72, fullPage: true }).catch(() => {});
        log.push(`### ${path} → ${page.url()} [${r ? r.status() : 'ERR'}] ${f}\n${JSON.stringify(info)}\nerrors: ${JSON.stringify(errs.filter(x => !/pagespeed/.test(x)).slice(0, 5))}`);
      }
      await ctx.close();
    }
  }
  await browser.close();
  fs.writeFileSync(`${OUT}/report.txt`, log.join('\n\n') + '\n');
  console.log(log.join('\n'));
})();
