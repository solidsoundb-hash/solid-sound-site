// Diagnose "Out of Memory / browser hangs" on the creator area. Read-only: logs in as the test admin, opens pages,
// lets each run for a few seconds, then PAUSES the page with the debugger (works even inside an endless JS loop)
// and prints the stack + code around the line where it was stuck, plus memory/DOM numbers when available.
// Every CDP call has a timeout, so a frozen tab can no longer hang the robot. Output goes to the workflow log.
const { chromium } = require('playwright');
const sleep = ms => new Promise(r => setTimeout(r, ms));
const to = (pr, ms, d) => Promise.race([pr, sleep(ms).then(() => d)]);
const BASE = 'https://solid-sound.net';
const PAGES = (process.env.PAGES || '/en/store-manager/,/en/store-manager/ss-add-product/,/en/store-manager/ss-products/,/en/my-account/,/en/,/en/all-products/').split(',');
const short = u => (u || '').replace(/^https?:\/\/[^/]+/, '').slice(-90);
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined, args: ['--js-flags=--max-old-space-size=1024'] });
  const ctx = await b.newContext({ viewport: { width: 1440, height: 900 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const lp = await ctx.newPage();
  await lp.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 90000 });
  await lp.fill('#user_login', process.env.TEST_ADMIN_USER); await lp.fill('#user_pass', process.env.TEST_ADMIN_PASS);
  await Promise.all([lp.waitForNavigation({ timeout: 90000 }).catch(() => {}), lp.click('#wp-submit')]);
  console.log('login →', lp.url()); await lp.close();
  for (const path of PAGES) {
    console.log(`\n===== ${path} =====`);
    const p = await ctx.newPage(); const cdp = await ctx.newCDPSession(p);
    const req = {}; const errs = []; const scripts = {};
    p.on('request', r => { const u = short(r.url().split('?')[0]); req[u] = (req[u] || 0) + 1; });
    p.on('crash', () => console.log('!! renderer crashed')); p.on('pageerror', e => errs.push(e.message.slice(0, 160)));
    const sp = {}; cdp.on('Debugger.scriptParsed', s => { scripts[s.scriptId] = s.url; sp[s.scriptId] = s; });
    const snip = async (l) => { const src = await to(cdp.send('Debugger.getScriptSource', { scriptId: l.scriptId }), 3000, null); if (!src) return '(no source)'; const s0 = sp[l.scriptId] || { startLine: 0, startColumn: 0 }; const ln = l.lineNumber - (s0.startLine || 0); const c = l.columnNumber - (ln === 0 ? (s0.startColumn || 0) : 0); const line = src.scriptSource.split('\n')[ln] || ''; return line.slice(Math.max(0, c - 350), c + 250).replace(/\s+/g, ' '); };
    let paused = null; cdp.on('Debugger.paused', e => { paused = e; });
    await to(cdp.send('Debugger.enable'), 5000); await to(cdp.send('Performance.enable'), 5000);
    p.goto(BASE + path, { waitUntil: 'commit', timeout: 90000 }).catch(e => console.log('goto', e.message.slice(0, 80)));
    for (let i = 1; i <= 12; i++) {
      await sleep(1000);
      const m = await to(cdp.send('Performance.getMetrics'), 1500, null);
      if (!m) { console.log(`${i}s metrics: NO ANSWER (main thread busy)`); continue; }
      const g = k => (m.metrics.find(x => x.name === k) || {}).value;
      console.log(`${i}s heap=${Math.round(g('JSHeapUsedSize') / 1048576)}MB nodes=${g('Nodes')} listeners=${g('JSEventListeners')} layouts=${g('LayoutCount')} scriptMs=${Math.round(g('ScriptDuration') * 1000)}`);
    }
    // Which DOM changes keep repeating after load (self-feeding observer loops)?
    const muts = await to(p.evaluate(() => new Promise(res => {
      const d = el => el && el.nodeType === 1 ? (el.tagName.toLowerCase() + (el.id ? '#' + el.id : '') + (typeof el.className === 'string' && el.className ? '.' + el.className.trim().split(/\s+/).slice(0, 3).join('.') : '')) : (el && el.nodeType === 3 ? '#text' : String(el && el.nodeName));
      const c = {}; const mo = new MutationObserver(ms => ms.forEach(m => { const k = `${m.type} on ${d(m.target)} <- ${d(m.target.parentNode)}` + (m.addedNodes[0] ? ` +${d(m.addedNodes[0])}` : '') + (m.removedNodes[0] ? ` -${d(m.removedNodes[0])}` : '') + (m.attributeName ? ` @${m.attributeName}` : ''); c[k] = (c[k] || 0) + 1; }));
      mo.observe(document.documentElement, { childList: true, subtree: true, attributes: true, characterData: true });
      setTimeout(() => { mo.disconnect(); res(Object.entries(c).sort((a, b) => b[1] - a[1]).slice(0, 10).map(([k, n]) => `${n}× ${k}`)); }, 3000);
    })), 6000, ['(no answer)']);
    console.log('--- DOM mutations in 3 s after load ---\n' + muts.join('\n'));
    if (/ss-add-product/.test(path)) { // live check of the AI helpers (read-only: nothing is submitted)
      try {
        await p.fill('[name=product_name]', 'Dark Techno Night');
        await p.click('text=✨ Name ideas', { timeout: 5000 }); await p.waitForSelector('.ss-aic-nm .ss-aic-c', { timeout: 40000 });
        console.log('AI names:', (await p.$$eval('.ss-aic-nm .ss-aic-c', b => b.map(x => x.textContent))).join(' | '));
        await p.click('text=✨ Write with AI (3 versions)', { timeout: 5000 }); await p.waitForFunction(() => document.querySelectorAll('.ss-aic-card .use:not([disabled]),.ss-aic-card.bad').length === 3, null, { timeout: 60000 });
        console.log('AI descriptions:\n' + (await p.$$eval('.ss-aic-card', c => c.map(x => '  [' + x.querySelector('b').textContent + '] ' + x.querySelector('.t').textContent.replace(/\s+/g, ' ').slice(0, 160)))).join('\n'));
        console.log('description field still empty (nothing applied without a tap):', (await p.inputValue('[name=product_description]')) === '');
        await p.focus('[name=product_price]'); await p.waitForTimeout(3000);
        console.log('price hint:', await p.$eval('.ss-aic-pr', e => e.textContent).catch(() => '(none)'));
        console.log('autofill panel present:', !!(await p.$('#ss-zf')));
      } catch (e) { console.log('AI check failed:', e.message.slice(0, 160)); }
    }
    // Take 3 snapshots of where JS is running right now.
    for (let k = 0; k < 3; k++) {
      paused = null;
      await to(cdp.send('Debugger.pause'), 3000);
      for (let w = 0; w < 30 && !paused; w++) await sleep(100);
      if (!paused) { console.log(`pause #${k + 1}: page idle (no JS running) or no answer`); await sleep(700); continue; }
      console.log(`--- pause #${k + 1}: stack ---`);
      for (const f of paused.callFrames.slice(0, 8)) {
        const l = f.location; console.log(`  ${f.functionName || '(anon)'} @ ${short(scripts[l.scriptId] || f.url || 'inline')}:${l.lineNumber + 1}:${l.columnNumber + 1}`);
      }
      if (k < 2) { console.log('  code: …' + await snip(paused.callFrames[0].location) + '…'); if (paused.callFrames[1]) console.log('  caller: …' + await snip(paused.callFrames[1].location) + '…'); }
      await to(cdp.send('Debugger.resume'), 3000); await sleep(700);
    }
    const reqTop = Object.entries(req).sort((a, c) => c[1] - a[1]).slice(0, 8).map(([u, n]) => `${n}× ${u}`);
    console.log('--- most repeated requests ---\n' + reqTop.join('\n'));
    console.log('--- page errors ---\n' + errs.slice(0, 5).join('\n'));
    await to(p.close({ runBeforeUnload: false }), 5000);
  }
  await to(b.close(), 10000); process.exit(0);
})();
