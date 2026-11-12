// Site check robot: logs in as guest / test user / admin, screenshots key pages on phone + desktop,
// records console errors and horizontal scroll. Output: checks/latest/ (report.md + JPEG screenshots).
// Env: TEST_USER, TEST_PASS, TEST_ADMIN_USER, TEST_ADMIN_PASS. Run: node tools/site-check.js
const { chromium } = require('playwright');
const fs = require('fs');
const OUT = 'checks/latest';
const S = 'https://solid-sound.net';
const PAGES = {
  guest: ['/en/', '/en/my-account/', '/product/infinity/', '/en/all-products/', '/en/vendor-register/', '/sell/'],
  user: ['/en/', '/en/my-account/', '/en/my-account/orders/', '/en/my-account/edit-account/', '/en/my-account/edit-address/'],
  admin: ['/en/my-account/', '/en/store-manager/', '/en/store-manager/ss-products/', '/add-product/', '/en/store-manager/ss-earnings/',
    '/en/store-manager/ss-payouts/', '/en/store-manager/ss-reviews/', '/en/store-manager/ss-settings/', '/en/store-manager/ss-help/'],
  // a real author account (secrets TEST_VENDOR_USER / TEST_VENDOR_PASS) — skipped until the owner adds it
  vendor: ['/en/my-account/', '/en/store-manager/', '/en/store-manager/ss-products/', '/add-product/', '/en/store-manager/ss-earnings/', '/en/store-manager/ss-settings/'],
};
const DEVICES = [
  { name: 'phone', viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true, ua: 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' },
  { name: 'desktop', viewport: { width: 1280, height: 900 }, isMobile: false, hasTouch: false, ua: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' },
];
const slug = (p) => p.replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home';

async function throttle(ctx) {
  // the host rate-limits bursts (HTTP 429): one or two same-site requests at a time, retry 429
  let active = 0; const q = [];
  const slot = () => new Promise(r => (active < 2 ? (active++, r()) : q.push(r)));
  const free = () => { const n = q.shift(); n ? n() : active--; };
  await ctx.route(/solid-sound\.net/, async (route) => {
    await slot();
    try {
      let res;
      for (let i = 0; i < 6; i++) { res = await route.fetch(); if (res.status() !== 429) break; await new Promise(z => setTimeout(z, 4000 * (i + 1))); }
      await route.fulfill({ response: res });
    } catch (e) { try { await route.abort(); } catch (_) {} } finally { free(); }
  });
}

async function login(page, user, pass) {
  await page.goto(S + '/en/my-account/?ss_tab=login', { waitUntil: 'domcontentloaded', timeout: 120000 });
  // two login forms exist (a hidden copy in the old theme header): use the visible one
  const form = page.locator('form.login').filter({ has: page.locator('#username:visible') }).first();
  await form.locator('#username').fill(user);
  await form.locator('#password').fill(pass);
  await Promise.all([page.waitForNavigation({ timeout: 120000 }).catch(() => {}), form.locator('button[name=login]').click()]);
  await page.waitForTimeout(1500);
  return page.evaluate(() => document.body.classList.contains('logged-in'));
}

(async () => {
  fs.rmSync(OUT, { recursive: true, force: true }); fs.mkdirSync(OUT, { recursive: true });
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const lines = ['# Site check ' + new Date().toISOString(), ''];
  const who = { guest: null, user: [process.env.TEST_USER, process.env.TEST_PASS], admin: [process.env.TEST_ADMIN_USER, process.env.TEST_ADMIN_PASS], vendor: [process.env.TEST_VENDOR_USER, process.env.TEST_VENDOR_PASS] };
  for (const [role, cred] of Object.entries(who)) {
    if (cred && !(cred[0] && cred[1])) { lines.push(`## ${role}: no credentials, skipped`); continue; }
    for (const d of DEVICES) {
      const ctx = await browser.newContext({ viewport: d.viewport, isMobile: d.isMobile, hasTouch: d.hasTouch, userAgent: d.ua, deviceScaleFactor: 1 });
      await throttle(ctx);
      const page = await ctx.newPage();
      let errs = [];
      page.on('pageerror', e => errs.push('JS: ' + e.message.slice(0, 200)));
      page.on('console', m => { if (m.type() === 'error' && !/Failed to load resource|SSL certificate is not valid|CanMakePayment/.test(m.text())) errs.push('console: ' + m.text().slice(0, 200)); });
      lines.push(`## ${role} · ${d.name}`);
      if (cred) {
        let ok = false; try { ok = await login(page, cred[0], cred[1]); } catch (e) { lines.push('- login error: ' + e.message.split('\n')[0]); }
        lines.push('- logged in: ' + ok);
        if (!ok) { await page.screenshot({ path: `${OUT}/${role}_${d.name}_login.jpg`, type: 'jpeg', quality: 60 }); await ctx.close(); continue; }
      }
      for (const p of PAGES[role]) {
        errs = [];
        let status = 0;
        try {
          const r = await page.goto(S + p, { waitUntil: 'load', timeout: 180000 }); status = r ? r.status() : 0;
          await page.waitForTimeout(3500);
        } catch (e) { lines.push(`- ${p}: load error ${e.message.split('\n')[0]}`); continue; }
        const info = await page.evaluate(() => ({
          hscroll: document.documentElement.scrollWidth > window.innerWidth + 1,
          title: document.title.slice(0, 80),
          fatal: /critical error|Fatal error/i.test(document.body.innerText.slice(0, 5000)),
          inlineKB: Math.round([...document.querySelectorAll('script:not([src]),style')].reduce((n, e) => n + e.textContent.length, 0) / 1024),
          oldPlayer: [...document.querySelectorAll('script:not([src]),style')].some(e => /\.ssp\{|Solid Sound Player/.test(e.textContent)),
          playerFiles: !!document.querySelector('link[href*="theme-player"],script[src*="theme-player"]'),
        }));
        const file = `${role}_${d.name}_${slug(p)}.jpg`;
        await page.screenshot({ path: `${OUT}/${file}`, type: 'jpeg', quality: 60, fullPage: false });
        lines.push(`- ${p} → HTTP ${status}${info.hscroll ? ' · ⚠ horizontal scroll' : ''}${info.fatal ? ' · ❌ PHP ERROR' : ''}${errs.length ? ' · ⚠ ' + errs.length + ' console errors' : ''} · inline ${info.inlineKB} KB${info.oldPlayer ? ' · ⚠ old inline player' : ''}${info.playerFiles ? ' · player files' : ''} · [${file}](${file})`);
        errs.slice(0, 5).forEach(e => lines.push('    - ' + e));
      }
      await ctx.close();
    }
    lines.push('');
  }
  await browser.close();
  fs.writeFileSync(`${OUT}/report.md`, lines.join('\n') + '\n');
  console.log(lines.join('\n'));
})();
