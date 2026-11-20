// Probe (20.11): what happens to a home «Play <name>» button and the audio when it is pressed twice.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await b.newContext({ viewport: { width: 1366, height: 900 }, locale: 'en-US' });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage(); const log = []; const L = (...a) => log.push(a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '));
  p.on('pageerror', e => L('JS ERROR', e.message.slice(0, 200)));
  await p.goto('https://solid-sound.net/en/', { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {}); await p.waitForTimeout(3000);
  await p.evaluate(() => { window.__ev = []; document.addEventListener('ssp:state', e => window.__ev.push(JSON.stringify(e.detail))); });
  const st = () => p.evaluate(() => { const b = document.querySelector('#ss-app button[aria-label$="Infinity"]'); if (!b) return 'NO BUTTON';
    const art = b.closest('article'); const w = art && art.querySelector('.ss-pw .ssw');
    const au = [...document.querySelectorAll('audio,video')].map(a => ({ src: (a.currentSrc || a.src || '').split('/').pop(), paused: a.paused, t: Math.round(a.currentTime) }));
    return { label: b.getAttribute('aria-label'), cls: b.className, pressed: b.getAttribute('aria-pressed'), path: ((b.querySelector('svg path') || {}).getAttribute ? b.querySelector('svg path').getAttribute('d') : '').slice(0, 30),
      ssw: w ? { src: (w.getAttribute('data-src') || '').split('/').pop(), cls: w.className } : null, ssp: (document.querySelector('#ssp') || {}).className, audio: au, ev: window.__ev.slice(-4), html: b.outerHTML.slice(0, 300) }; });
  L('0 before', await st());
  const btn = p.locator('#ss-app button[aria-label$="Infinity"]').first();
  await btn.scrollIntoViewIfNeeded().catch(() => {}); await btn.click({ timeout: 5000 }).catch(e => L('click1 fail', e.message.split('\n')[0]));
  await p.waitForTimeout(4000); L('1 after first click', await st());
  await btn.click({ timeout: 5000 }).catch(e => L('click2 fail', e.message.split('\n')[0]));
  await p.waitForTimeout(1500); L('2 after second click', await st());
  fs.writeFileSync(OUT + '/play.txt', log.join('\n') + '\n'); console.log(log.join('\n')); await b.close();
})();
