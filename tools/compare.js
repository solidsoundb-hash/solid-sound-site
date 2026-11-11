// Compare original (?ss_v2=0) vs build (?ss_v2=1): screenshots, pixel diff, console errors.
// node tools/compare.js <outdir> <width> <path...>
const { chromium } = require('playwright');
const { PNG } = require('pngjs'); const pixelmatch = require('pixelmatch');
const fs = require('fs');
const [out, width, ...paths] = process.argv.slice(2);
fs.mkdirSync(out, { recursive: true });
(async () => {
  const browser = await chromium.launch(process.env.HTTPS_PROXY ? { proxy: { server: process.env.HTTPS_PROXY } } : {});
  for (const p of paths) {
    const shots = {};
    for (const v of ['0', '1']) {
      const ctx = await browser.newContext({ viewport: { width: +width, height: 900 }, deviceScaleFactor: 1, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/130.0 Safari/537.36' + (+width < 600 ? ' Mobile' : '') , isMobile: +width < 600, hasTouch: +width < 600 });
      // The host rate-limits datacenter IPs (HTTP 429). Fetch same-site files one-two at a time and retry 429.
      let active = 0; const queue = [];
      const slot = () => new Promise(r => { if (active < 2) { active++; r(); } else queue.push(r); });
      const free = () => { const n = queue.shift(); n ? n() : active--; };
      await ctx.route(/solid-sound\.net/, async route => {
        await slot();
        try {
          let resp;
          for (let i = 0; i < 6; i++) { resp = await route.fetch(); if (resp.status() !== 429) break; await new Promise(r => setTimeout(r, 4000 * (i + 1))); }
          await route.fulfill({ response: resp });
        } catch (e) { console.log("route err", e.message.slice(0,200)); try { await route.abort(); } catch (x) {} } finally { free(); }
      });
      const page = await ctx.newPage(); const errs = [];
      page.on('pageerror', e => errs.push('JS: ' + e.message));
      page.on('console', m => { if (m.type() === 'error' && !/ERR_TUNNEL_CONNECTION_FAILED/.test(m.text())) errs.push('console: ' + m.text().slice(0, 160)); });
      const url = 'https://solid-sound.net' + p + (p.includes('?') ? '&' : '?') + 'ss_v2=' + v;
      let resp; for (let i = 0; i < 5; i++) { resp = await page.goto(url, { waitUntil: 'load', timeout: 240000 }); if (resp.status() !== 429) break; await page.waitForTimeout(20000); }
      await page.waitForTimeout(4000);
      await page.addStyleTag({ content: '*,*::before,*::after{animation:none!important;transition:none!important;caret-color:transparent!important} video,iframe{visibility:hidden!important}' });
      await page.waitForTimeout(500);
      const f = `${out}/${p.replace(/[^a-z0-9]+/gi, '_')}_${width}_v${v}.png`;
      await page.screenshot({ path: f });
      shots[v] = f;
      console.log(`${p} w=${width} v${v} HTTP ${resp.status()} errors=${errs.length}`); errs.slice(0, 6).forEach(e => console.log('    ' + e));
      await ctx.close();
    }
    const a = PNG.sync.read(fs.readFileSync(shots['0'])), b = PNG.sync.read(fs.readFileSync(shots['1']));
    const h = Math.min(a.height, b.height), w = Math.min(a.width, b.width);
    const crop = (img) => { const o = new PNG({ width: w, height: h }); PNG.bitblt(img, o, 0, 0, w, h, 0, 0); return o; };
    const d = new PNG({ width: w, height: h });
    const n = pixelmatch(crop(a).data, crop(b).data, d.data, w, h, { threshold: 0.15 });
    fs.writeFileSync(`${out}/${p.replace(/[^a-z0-9]+/gi, '_')}_${width}_diff.png`, PNG.sync.write(d));
    console.log(`  DIFF ${p} w=${width}: ${(100 * n / (w * h)).toFixed(2)}% pixels differ`);
  }
  await browser.close();
})();
