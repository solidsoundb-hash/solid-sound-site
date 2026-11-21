// Probe (21.11, owner: «нужно полностью, чтобы нормально видео было, играло нормально»): the YouTube block on product
// pages (modules/yt-lite.php) — its box, the parent's box, before and after pressing ▶, computer + Samsung A31.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
const URLS = (process.env.YTL_URLS || '/product/melodic-techno-presets/,/product/infinity/').split(',');
const DEV = { pc: { viewport: { width: 1366, height: 860 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' },
  a31: { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' } };
const log = []; const L = (...a) => log.push(a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '));
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const [dn, d] of Object.entries(DEV)) {
    const ctx = await b.newContext({ ...d, locale: 'en-US', deviceScaleFactor: 1 }); await ctx.addCookies([{ name: 'ss_consent', value: 'no', domain: 'solid-sound.net', path: '/' }]);
    for (const u of URLS) {
      const p = await ctx.newPage(); p.on('pageerror', e => L('JS ERROR', e.message.slice(0, 160)));
      await p.goto('https://solid-sound.net' + u + '?nc=' + Date.now(), { waitUntil: 'networkidle', timeout: 90000 }).catch(() => {}); await p.waitForTimeout(2500);
      const tag = dn + '-' + u.split('/').filter(Boolean).pop();
      const n = await p.locator('.ss-ytl').count(); L(tag, 'title', await p.title(), 'videos', n);
      for (let i = 0; i < Math.min(n, 2); i++) {
        const v = p.locator('.ss-ytl').nth(i); await v.scrollIntoViewIfNeeded().catch(() => {}); await p.waitForTimeout(800);
        L(tag, i, 'box', await v.evaluate(e => { const r = e.getBoundingClientRect(), pr = e.parentElement.getBoundingClientRect(), cs = getComputedStyle(e);
          const chain = []; let x = e; for (let k = 0; k < 6 && x; k++, x = x.parentElement) chain.push(x.tagName + '.' + String(x.className).slice(0, 40) + ' ' + Math.round(x.getBoundingClientRect().width) + 'x' + Math.round(x.getBoundingClientRect().height));
          return { w: Math.round(r.width), h: Math.round(r.height), parent: [Math.round(pr.width), Math.round(pr.height)], maxH: cs.maxHeight, ar: cs.aspectRatio, chain }; }));
        await p.screenshot({ path: `${OUT}/${tag}-${i}-before.png`, clip: await v.evaluate(e => { const r = e.parentElement.getBoundingClientRect(); return { x: 0, y: Math.max(0, r.top - 20), width: innerWidth, height: Math.min(innerHeight, r.height + 120) }; }) }).catch(() => p.screenshot({ path: `${OUT}/${tag}-${i}-before.png` }));
        await v.click({ timeout: 5000 }).catch(e => L('click fail', e.message.split('\n')[0])); await p.waitForTimeout(6000);
        L(tag, i, 'after', await v.evaluate(e => { const f = e.querySelector('iframe'); const r = (f || e).getBoundingClientRect(); return { iframe: !!f, w: Math.round(r.width), h: Math.round(r.height), src: f ? f.src.slice(0, 80) : '' }; }));
        await p.screenshot({ path: `${OUT}/${tag}-${i}-after.png` });
      }
      await p.close();
    }
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/ytl.txt', log.join('\n') + '\n'); console.log(log.join('\n')); await b.close();
})();
