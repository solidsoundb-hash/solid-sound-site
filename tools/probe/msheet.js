// Probe (24.11): phone menu sheet — Language / Currency rows look like the other menu items (icon + text).
// A31 412×700 and 412×780: menu open, then Language opened. Before/after shots of the owner's request.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
const UA = 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36';
const log = []; const L = (...a) => log.push(a.join(' '));
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const [path, h] of [['/en/', 700], ['/en/all-products/', 780], ['/de/', 700]]) {
    const ctx = await b.newContext({ viewport: { width: 412, height: h }, isMobile: true, hasTouch: true, userAgent: UA, locale: 'en-US', deviceScaleFactor: 2 });
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    const p = await ctx.newPage(); p.on('pageerror', e => L('JS ERROR', path, e.message.slice(0, 200)));
    await p.goto('https://solid-sound.net' + path + '?ms=' + Date.now(), { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {}); await p.waitForTimeout(2500);
    await p.getByText('Reject non-essential', { exact: true }).first().click({ timeout: 4000 }).catch(() => {}); await p.waitForTimeout(400);
    const s = path.replace(/\W+/g, '_') + h;
    try { await p.locator('#ssg .g-burger').first().click({ timeout: 8000 }); await p.waitForTimeout(900);
      await p.evaluate(() => { const sp = document.querySelector('#ssg-sheet .s-p'); if (sp) sp.scrollTop = sp.scrollHeight; }); await p.waitForTimeout(400);
      await p.screenshot({ path: `${OUT}/${s}_menu.jpg`, type: 'jpeg', quality: 75 });
      await p.locator('#ssg-sheet .s-dd summary').first().click({ timeout: 5000 }); await p.waitForTimeout(500);
      await p.evaluate(() => { const sp = document.querySelector('#ssg-sheet .s-p'); if (sp) sp.scrollTop = sp.scrollHeight; }); await p.waitForTimeout(300);
      await p.screenshot({ path: `${OUT}/${s}_lang.jpg`, type: 'jpeg', quality: 75 });
      L(path, h, 'ok, summary icons:', await p.evaluate(() => [...document.querySelectorAll('#ssg-sheet .s-dd summary')].map(x => x.querySelectorAll('svg').length + ':' + x.textContent.trim()).join(' | ')));
    } catch (e) { L(path, h, 'FAIL', e.message.slice(0, 120)); }
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/report.md', '# msheet ' + new Date().toISOString() + '\n\n' + log.join('\n') + '\n');
  await b.close();
})();
