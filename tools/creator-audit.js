// Creator area audit (owner 19.11.2026: «пройди все вкладки автора … что нужно улучшить»).
// Logs in as admin (sees the creator space), full-page screenshots of every creator tab on phone + desktop
// without the cookie banner, opens "Add your own" on Add product, records JS errors and horizontal scroll.
// Output: checks/creator-audit/ (report.md + JPEG). Env: TEST_ADMIN_USER, TEST_ADMIN_PASS.
const { chromium } = require('playwright');
const fs = require('fs');
const OUT = 'checks/creator-audit';
const S = 'https://solid-sound.net';
const PAGES = ['/en/store-manager/', '/en/store-manager/ss-products/', '/en/store-manager/ss-add-product/', '/en/store-manager/ss-earnings/',
  '/en/store-manager/ss-payouts/', '/en/store-manager/ss-reviews/', '/en/store-manager/ss-settings/', '/en/store-manager/ss-help/', '/en/my-account/', '/sell/'];
const DEVICES = [
  { name: 'phone', viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true, ua: 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' },
  { name: 'desktop', viewport: { width: 1366, height: 900 }, isMobile: false, hasTouch: false, ua: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' },
];
const slug = (p) => p.replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home';
const hideBanners = () => document.querySelectorAll('body *').forEach(z => { const s = getComputedStyle(z); if (s.position === 'fixed' && /privacy|cookie/i.test((z.textContent || '').slice(0, 200)) && (z.textContent || '').length < 900) z.style.display = 'none'; });

async function login(page, user, pass) {
  await page.goto(S + '/en/my-account/?ss_tab=login', { waitUntil: 'domcontentloaded', timeout: 120000 });
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
  const lines = ['# Creator area audit ' + new Date().toISOString(), ''];
  for (const d of DEVICES) {
    const ctx = await browser.newContext({ viewport: d.viewport, isMobile: d.isMobile, hasTouch: d.hasTouch, userAgent: d.ua, deviceScaleFactor: 1 });
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    const page = await ctx.newPage();
    let errs = [];
    page.on('pageerror', e => errs.push('JS: ' + e.message.slice(0, 200)));
    lines.push('## ' + d.name);
    let ok = false; try { ok = await login(page, process.env.TEST_ADMIN_USER, process.env.TEST_ADMIN_PASS); } catch (e) { lines.push('- login error: ' + e.message.split('\n')[0]); }
    if (!ok) { lines.push('- not logged in'); await ctx.close(); continue; }
    for (const p of PAGES) {
      errs = [];
      let st = 0;
      try { const r = await page.goto(S + p, { waitUntil: 'networkidle', timeout: 120000 }); st = r ? r.status() : 0; } catch (e) { lines.push(`- ${p} → ${e.message.split('\n')[0]}`); continue; }
      await page.waitForTimeout(2500);
      await page.evaluate(hideBanners);
      const info = await page.evaluate(() => ({ ovf: document.documentElement.scrollWidth - innerWidth, h: document.documentElement.scrollHeight, h1: (document.querySelector('h1,h2') || {}).textContent || '' }));
      const f = `${d.name}_${slug(p)}.jpg`;
      await page.screenshot({ path: `${OUT}/${f}`, type: 'jpeg', quality: 62, fullPage: true });
      lines.push(`- ${p} → HTTP ${st} · height ${info.h}px${info.ovf > 0 ? ' · ⚠ sideways scroll ' + info.ovf + 'px' : ''} · [${f}](${f})`);
      errs.forEach(e => lines.push('    - ' + e));
      if (/add-product/.test(p)) {
        const add = page.locator('a.acf-icon[data-name=add]').first();
        if (await add.count()) {
          await add.scrollIntoViewIfNeeded().catch(() => {}); await add.click().catch(() => {}); await page.waitForTimeout(1500);
          const g = `${d.name}_add_your_own.jpg`;
          await page.screenshot({ path: `${OUT}/${g}`, type: 'jpeg', quality: 62 });
          lines.push(`    - "Add your own" window: [${g}](${g})`);
          await page.keyboard.press('Escape').catch(() => {});
        }
      }
    }
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/report.md', lines.join('\n') + '\n');
  await browser.close();
})();
