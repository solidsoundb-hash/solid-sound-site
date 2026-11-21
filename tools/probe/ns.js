// Probe (21.11): All products → What's new slider after the fix: cover video whole (16:9 on black) and the ▶ without a square.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
const DEV = { pc: { viewport: { width: 1366, height: 860 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' },
  a31: { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' } };
const log = []; const L = (...a) => log.push(a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '));
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const [dn, d] of Object.entries(DEV)) {
    const ctx = await b.newContext({ ...d, locale: 'en-US', deviceScaleFactor: 2 }); await ctx.addCookies([{ name: 'ss_consent', value: 'no', domain: 'solid-sound.net', path: '/' }]);
    const p = await ctx.newPage(); p.on('pageerror', e => L('JS ERROR', e.message.slice(0, 160)));
    await p.goto('https://solid-sound.net/en/all-products/?nc=' + Date.now(), { waitUntil: 'networkidle', timeout: 90000 }).catch(() => {});
    const s = p.locator('.ssn-s').first(); await s.scrollIntoViewIfNeeded().catch(() => {}); await p.waitForTimeout(9000);
    L(dn, await p.evaluate(() => { const c = document.querySelector('.ssn-s .ssn-cov'); const f = document.querySelector('.ssn-cov iframe');
      const btn = document.querySelector('.ssn-wv .ssw-btn'); const bs = btn ? getComputedStyle(btn) : null;
      return { cover: c ? [Math.round(c.getBoundingClientRect().width), Math.round(c.getBoundingClientRect().height)] : null, iframe: f ? { on: f.classList.contains('on'), w: Math.round(f.getBoundingClientRect().width), h: Math.round(f.getBoundingClientRect().height) } : null,
        btn: bs ? { bg: bs.backgroundColor, border: bs.borderTopWidth, radius: bs.borderRadius } : null }; }));
    L(dn, 'specs', await p.evaluate(() => [...document.querySelectorAll('.ssn-s')].map(x => (x.querySelector('.ssn-t') || {}).textContent + ' → ' + [...x.querySelectorAll('.ssn-sp div')].map(d => d.textContent.replace(/\s+/g, ' ').trim()).join(' | '))));
    const box = await s.boundingBox().catch(() => null);
    await p.screenshot({ path: `${OUT}/${dn}-slider.png`, clip: box ? { x: 0, y: Math.max(0, box.y - 10), width: d.viewport.width, height: Math.min(box.height + 20, 900) } : undefined }).catch(() => p.screenshot({ path: `${OUT}/${dn}-slider.png` }));
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/ns.txt', log.join('\n') + '\n'); console.log(log.join('\n')); await b.close();
})();
