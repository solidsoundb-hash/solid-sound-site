// Probe (20.11): what happens to a home «Play <name>» button and the audio when it is pressed twice.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await b.newContext({ viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, locale: 'en-US', userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' }); // same phone as tools/page-shot.js
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage(); const log = []; const L = (...a) => log.push(a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '));
  p.on('pageerror', e => L('JS ERROR', e.message.slice(0, 200)));
  await p.goto('https://solid-sound.net/en/', { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {}); await p.waitForTimeout(3000);
  L('page', await p.evaluate(() => ({ title: document.title, n: document.querySelectorAll('[aria-label*="Infinity" i]').length, list: [...document.querySelectorAll('[aria-label*="Infinity" i]')].slice(0, 6).map(e => e.tagName + '[' + e.getAttribute('aria-label') + '] in ' + (e.closest('[id]') || {}).id) })));
  await p.evaluate(() => { window.__ev = []; document.addEventListener('ssp:state', e => window.__ev.push(JSON.stringify(e.detail))); });
  const st = () => p.evaluate(() => { const b = document.querySelector('[aria-label="Play Infinity"],[aria-label="Pause Infinity"]'); if (!b) return 'NO BUTTON';
    const art = b.closest('article'); const w = art && art.querySelector('.ss-pw .ssw');
    const au = [...document.querySelectorAll('audio,video')].map(a => ({ src: (a.currentSrc || a.src || '').split('/').pop(), paused: a.paused, t: Math.round(a.currentTime) }));
    return { label: b.getAttribute('aria-label'), cls: b.className, pressed: b.getAttribute('aria-pressed'), path: ((b.querySelector('svg path') || {}).getAttribute ? b.querySelector('svg path').getAttribute('d') : '').slice(0, 30),
      ssw: w ? { src: (w.getAttribute('data-src') || '').split('/').pop(), cls: w.className } : null, ssp: (document.querySelector('#ssp') || {}).className, audio: au, ev: window.__ev.slice(-4), html: b.outerHTML.slice(0, 300) }; });
  L('0 before', await st());
  const btn = p.locator('[aria-label="Play Infinity"],[aria-label="Pause Infinity"]').first();
  L('cover', await p.evaluate(() => { const b = document.querySelector('[aria-label="Play Infinity"],[aria-label="Pause Infinity"]'); if (!b) return 'no'; b.scrollIntoView({ block: 'center' }); const r = b.getBoundingClientRect(); const t = document.elementFromPoint(r.left + r.width / 2, r.top + r.height / 2); return { rect: [Math.round(r.left), Math.round(r.top), Math.round(r.width), Math.round(r.height)], top: t ? t.tagName + '.' + String(t.className).slice(0, 60) + ' < ' + (t.parentElement ? t.parentElement.tagName + '.' + String(t.parentElement.className).slice(0, 60) : '') : null, same: t === b || b.contains(t) }; }));
  await btn.scrollIntoViewIfNeeded().catch(() => {}); await btn.click({ timeout: 5000 }).catch(e => L('click1 fail', e.message.split('\n')[0]));
  await p.waitForTimeout(4000); L('1 after first click', await st());
  await btn.click({ timeout: 5000 }).catch(e => L('click2 fail', e.message.split('\n')[0]));
  await p.waitForTimeout(1500); L('2 after second click', await st());
  fs.writeFileSync(OUT + '/play.txt', log.join('\n') + '\n'); console.log(log.join('\n')); await b.close();
})();
