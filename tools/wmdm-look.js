// Read-only look at the owner's own creator dashboard on We Make Dance Music (for designing our author panel).
// Logs in with secrets WMDM_USER / WMDM_PASS, then ONLY opens pages and takes screenshots:
// no clicks on buttons, no form submits (except the login form), nothing is changed on that site.
// Output goes to wmdm-out/ and is uploaded as a private workflow artifact (kept 7 days), never committed.
const { chromium } = require('playwright');
const fs = require('fs');
const OUT = 'wmdm-out';
const BASE = 'https://www.wemakedancemusic.com';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const log = [];
  const visited = []; // desktop pages, re-used for the phone pass
  const user = process.env.WMDM_USER, pass = process.env.WMDM_PASS;
  if (!user || !pass) { fs.writeFileSync(`${OUT}/report.txt`, 'no WMDM_USER / WMDM_PASS secrets\n'); return; }
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const dev of [{ n: 'desktop', o: { viewport: { width: 1440, height: 900 } } }, { n: 'phone', o: { viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true } }]) {
    const ctx = await browser.newContext({ ...dev.o, userAgent: dev.n === 'phone' ? 'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' });
    const page = await ctx.newPage();
    await page.goto(BASE + '/en/customer/account/login/', { waitUntil: 'domcontentloaded', timeout: 90000 });
    await sleep(2500);
    // accept/close cookie bar only if present (no other clicks)
    try { await page.getByRole('button', { name: /^(Accept|Decline)$/ }).first().click({ timeout: 2000 }); } catch (e) {}
    const email = page.locator('#login-form #login-email, #login-form input[name="login[username]"]').first();
    const pw = page.locator('#login-form #login-password, #login-form input[name="login[password]"]').first();
    await email.fill(user); await pw.fill(pass);
    await Promise.all([page.waitForNavigation({ timeout: 90000 }).catch(() => {}), pw.press('Enter')]);
    await sleep(4000);
    const logged = !/login/i.test(page.url());
    log.push(`## ${dev.n}: after login → ${page.url()} (${logged ? 'logged in' : 'NOT logged in — check the secrets / captcha / 2FA'})`);
    await page.screenshot({ path: `${OUT}/${dev.n}_00_after_login.jpg`, type: 'jpeg', quality: 70, fullPage: true }).catch(() => {});
    if (!logged) { await ctx.close(); continue; }
    // collect the creator/account menu links (same site, account / seller / marketplace areas), visit each once
    const seen = new Set([page.url().split('#')[0]]);
    const queue = dev.n === 'desktop' ? [BASE + '/en/customer/account/', BASE + '/en/marketplace/account/dashboard/'] : visited.slice(0, 12);
    const collect = async () => (await page.evaluate(() => [...document.querySelectorAll('a[href]')].map(a => a.href)))
      .filter(h => /wemakedancemusic\.com\/(en\/)?(customer|marketplace|seller|creator|vendor|account|dashboard|sales|payout|withdraw|product|upload|report|review|coupon|message|bundle|album|video|statistic)/i.test(h))
      .filter(h => !/logout|delete|remove|cancel|cart|checkout|wishlist\/add|\?.*(action|delete)=/i.test(h))
      .filter(h => !/\/marketplace\/seller\/profile\/shop\//i.test(h));
    let n = 1;
    while (queue.length && n <= 30) {
      const u = queue.shift().split('#')[0];
      if (seen.has(u)) continue; seen.add(u);
      try {
        await page.goto(u, { waitUntil: 'domcontentloaded', timeout: 90000 }); await sleep(3000);
        const title = (await page.title()).slice(0, 80);
        const file = `${dev.n}_${String(n).padStart(2, '0')}.jpg`;
        await page.screenshot({ path: `${OUT}/${file}`, type: 'jpeg', quality: 70, fullPage: true }).catch(() => {});
        log.push(`- ${file} · ${u} · ${title}`);
        if (dev.n === 'desktop') visited.push(u);
        n++;
        if (dev.n === 'desktop') for (const h of await collect()) if (!seen.has(h.split('#')[0]) && !queue.includes(h)) queue.push(h);
      } catch (e) { log.push(`- ${u}: ${e.message.split('\n')[0]}`); }
    }
    await ctx.close();
  }
  await browser.close();
  fs.writeFileSync(`${OUT}/report.txt`, log.join('\n') + '\n');
  console.log(log.join('\n'));
})();
