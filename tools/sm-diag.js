// Diagnose "Out of Memory / browser hangs" on the creator area. Read-only: logs in as the test admin, opens pages,
// and for 15 s records: JS heap growth, DOM node count, repeated network requests and the hottest JS functions (CPU profile).
// Everything is printed to the workflow log (no artifacts needed).
const { chromium } = require('playwright');
const sleep = ms => new Promise(r => setTimeout(r, ms));
const BASE = 'https://solid-sound.net';
const PAGES = (process.env.PAGES || '/en/store-manager/,/en/store-manager/?ss_cs=0,/en/store-manager/ss-products/,/en/my-account/').split(',');
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined, args: ['--js-flags=--max-old-space-size=1024'] });
  const ctx = await b.newContext({ viewport: { width: 1440, height: 900 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const lp = await ctx.newPage();
  await lp.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 90000 });
  await lp.fill('#user_login', process.env.TEST_ADMIN_USER); await lp.fill('#user_pass', process.env.TEST_ADMIN_PASS);
  await Promise.all([lp.waitForNavigation({ timeout: 90000 }).catch(() => {}), lp.click('#wp-submit')]);
  console.log('login →', lp.url()); await lp.close();
  for (const path of PAGES) {
    const p = await ctx.newPage(); const cdp = await ctx.newCDPSession(p);
    const req = {}; let crashed = false; const errs = [];
    p.on('request', r => { const u = r.url().split('?')[0].replace(/^https?:\/\/[^/]+/, ''); req[u] = (req[u] || 0) + 1; });
    p.on('crash', () => { crashed = true; }); p.on('pageerror', e => errs.push(e.message.slice(0, 160)));
    await cdp.send('Profiler.enable'); await cdp.send('Profiler.setSamplingInterval', { interval: 500 });
    await cdp.send('Performance.enable');
    const t0 = Date.now();
    await p.goto(BASE + path, { waitUntil: 'commit', timeout: 90000 }).catch(e => console.log('goto', e.message.slice(0, 80)));
    await cdp.send('Profiler.start');
    const mem = [];
    for (let i = 0; i < 15 && !crashed; i++) {
      await sleep(1000);
      try { const m = await cdp.send('Performance.getMetrics'); const g = k => (m.metrics.find(x => x.name === k) || {}).value; mem.push(`${i + 1}s heap=${Math.round(g('JSHeapUsedSize') / 1048576)}MB nodes=${g('Nodes')} listeners=${g('JSEventListeners')} layouts=${g('LayoutCount')} recalcs=${g('RecalcStyleCount')}`); } catch (e) { mem.push(`${i + 1}s metrics failed: ${e.message.slice(0, 60)}`); break; }
    }
    let top = [];
    try {
      const { profile } = await cdp.send('Profiler.stop');
      const self = {}; const total = profile.samples.length || 1; const byId = {};
      profile.nodes.forEach(n => byId[n.id] = n);
      const cnt = {}; profile.samples.forEach(id => cnt[id] = (cnt[id] || 0) + 1);
      Object.keys(cnt).forEach(id => { const n = byId[id]; if (!n) return; const cf = n.callFrame; const k = `${cf.functionName || '(anon)'} @ ${(cf.url || '').replace(/^https?:\/\/[^/]+/, '').slice(-70)}:${cf.lineNumber + 1}:${cf.columnNumber + 1}`; self[k] = (self[k] || 0) + cnt[id]; });
      top = Object.entries(self).sort((a, c) => c[1] - a[1]).slice(0, 15).map(([k, v]) => `${Math.round(v / total * 100)}% ${k}`);
    } catch (e) { top = ['profile failed: ' + e.message.slice(0, 80)]; }
    const reqTop = Object.entries(req).sort((a, c) => c[1] - a[1]).slice(0, 10).map(([u, n]) => `${n}× ${u}`);
    console.log(`\n===== ${path}  (${Math.round((Date.now() - t0) / 1000)}s, crashed=${crashed}) =====`);
    console.log(mem.join('\n'));
    console.log('--- hottest JS (self time) ---\n' + top.join('\n'));
    console.log('--- most repeated requests ---\n' + reqTop.join('\n'));
    console.log('--- page errors ---\n' + errs.slice(0, 5).join('\n'));
    await p.close().catch(() => {});
  }
  await b.close();
})();
