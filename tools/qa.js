// QA walk: opens pages on desktop / iPhone / Samsung A31, screenshots them, finds layout problems
// (sideways overflow, tiny text, small tap targets, broken images, JS errors) and clicks every button
// on the page, logging anything that unexpectedly starts audio or navigates away.
// Run: node tools/qa.js [out-dir] [pages comma-separated] [devices comma-separated]
// Optional env SS_SWAP_DIR: serve plugin assets from a local build (test before deploy).
const { chromium, devices } = require('playwright');
const fs = require('fs');
const OUT = process.argv[2] || 'checks/qa';
const S = 'https://solid-sound.net';
const ALL_PAGES = ['/en/', '/en/all-products/', '/en/templates/', '/en/presets/', '/en/music/', '/product/infinity/', '/product/melodic-techno-presets/',
  '/en/services-prices/', '/Services-Calculator/', '/mastering-album-calculator/', '/en/free/', '/en/wiki/', '/en/faq/', '/en/cart/', '/en/my-account/',
  '/en/vendor-register/', '/contacts/', '/license/'];
const PAGES = process.argv[3] ? process.argv[3].split(',') : ALL_PAGES;
const DEV = {
  desktop: { viewport: { width: 1440, height: 900 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' },
  iphone: { ...devices['iPhone 13'] },
  iphonese: { ...devices['iPhone SE'] },
  a31: { viewport: { width: 411, height: 914 }, deviceScaleFactor: 2.625, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' },
};
const DEVS = process.argv[4] ? process.argv[4].split(',') : ['desktop', 'a31', 'iphone'];
const slug = (p) => p.replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));

async function throttle(ctx) {
  let active = 0; const q = [];
  const slot = () => new Promise(r => (active < 2 ? (active++, r()) : q.push(r)));
  const free = () => { const n = q.shift(); n ? n() : active--; };
  const pend = new Set();
  const done = (r) => { if (pend.delete(r)) free(); };
  ctx.on('requestfinished', done); ctx.on('requestfailed', done);
  const swap = process.env.SS_SWAP_DIR ? fs.readdirSync(process.env.SS_SWAP_DIR) : null;
  const RX = /\/([a-z0-9-]+)\.[0-9a-f]{10}\.(js|css)/;
  await ctx.route(/solid-sound\.net/, async (route) => {
    const u = route.request().url();
    if (swap && /\/solid-sound-custom\/build\/assets\//.test(u) && !/pagespeed\.jc/.test(u)) {
      const m = u.match(RX); const f = m && swap.find(x => x.startsWith(m[1] + '.') && x.endsWith('.' + m[2]));
      if (f) return route.fulfill({ path: process.env.SS_SWAP_DIR + '/' + f, contentType: m[2] === 'js' ? 'application/javascript' : 'text/css' });
    }
    if (process.env.QA_DIRECT) {
      /* sandbox without direct DNS (proxy only): queue the request, then let the browser send it itself */
      await slot(); route.request()._qa = 1; pend.add(route.request());
      return route.continue().catch(() => { pend.delete(route.request()); free(); });
    }
    await slot();
    try {
      let res;
      for (let i = 0; i < 6; i++) { res = await route.fetch(); if (res.status() !== 429) break; await sleep(5000 * (i + 1)); }
      await route.fulfill({ response: res });
    } catch (e) { try { await route.abort(); } catch (_) {} } finally { free(); }
  });
}

const AUDIT = () => {
  const W = document.documentElement.clientWidth, out = { overflow: [], tiny: [], small: [], broken: [], hidden: [] };
  const name = (e) => (e.tagName.toLowerCase() + (e.id ? '#' + e.id : '') + (typeof e.className === 'string' && e.className.trim() ? '.' + e.className.trim().split(/\s+/).slice(0, 2).join('.') : '')).slice(0, 60);
  const vis = (e) => { const r = e.getBoundingClientRect(); const s = getComputedStyle(e); return r.width > 0 && r.height > 0 && s.visibility !== 'hidden' && s.display !== 'none' && +s.opacity > 0.05; };
  const clipped = (e) => { for (let a = e.parentElement; a && a !== document.body; a = a.parentElement) { const o = getComputedStyle(a).overflowX; if (o !== 'visible') return true; } return false; };
  document.querySelectorAll('body *').forEach((e) => {
    if (!vis(e)) return;
    const r = e.getBoundingClientRect();
    if ((r.right > W + 2 || r.left < -2) && !clipped(e) && getComputedStyle(e).position !== 'fixed') out.overflow.push(name(e) + ' L' + Math.round(r.left) + ' R' + Math.round(r.right));
    const t = [...e.childNodes].some(n => n.nodeType === 3 && n.textContent.trim().length > 2);
    if (t) { const fs = parseFloat(getComputedStyle(e).fontSize); if (fs < 11) out.tiny.push(name(e) + ' ' + fs + 'px "' + e.textContent.trim().slice(0, 30) + '"'); }
    if (e.matches('a,button,[role=button],input[type=checkbox],select') && (r.width < 28 || r.height < 28) && r.width * r.height > 0) out.small.push(name(e) + ' ' + Math.round(r.width) + 'x' + Math.round(r.height) + ' "' + (e.textContent || e.getAttribute('aria-label') || '').trim().slice(0, 20) + '"');
    if (e.tagName === 'IMG' && e.complete && e.naturalWidth === 0 && e.getAttribute('src')) out.broken.push((e.getAttribute('src') || '').slice(-60));
  });
  for (const k in out) out[k] = [...new Set(out[k])].slice(0, 25);
  out.sw = document.documentElement.scrollWidth; out.W = W; out.blocked = /захищена/.test(document.body.innerText);
  return out;
};

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined, args: ['--autoplay-policy=no-user-gesture-required'] });
  const report = [];
  for (const dn of DEVS) {
    const ctx = await browser.newContext({ ...DEV[dn] });
    await throttle(ctx);
    await ctx.addInitScript(() => {
      const o = HTMLMediaElement.prototype.play;
      HTMLMediaElement.prototype.play = function () { window.__plays = (window.__plays || 0) + 1; window.__lastPlay = (this.currentSrc || this.src || '').split('/').pop(); return o.apply(this, arguments); };
      try { document.cookie = 'ss_consent=no;path=/'; } catch (e) {}
    });
    for (const p of PAGES) {
      const page = await ctx.newPage(); const errs = [];
      page.on('pageerror', e => errs.push('JS: ' + e.message.slice(0, 160)));
      page.on('console', m => { if (m.type() === 'error' && !/Failed to load resource|GSI_LOGGER|CanMakePayment|mod_pagespeed/.test(m.text())) errs.push('console: ' + m.text().slice(0, 160)); });
      let st = 0;
      try { const r = await page.goto(S + p, { waitUntil: 'load', timeout: 180000 }); st = r ? r.status() : 0; } catch (e) { report.push(`## ${dn} ${p}: load error ${e.message.split('\n')[0]}`); await page.close(); continue; }
      await page.waitForTimeout(4000);
      const a = await page.evaluate(AUDIT);
      const plays0 = await page.evaluate(() => window.__plays || 0);
      await page.screenshot({ path: `${OUT}/${dn}_${slug(p)}.jpg`, type: 'jpeg', quality: 55, fullPage: true }).catch(() => {});
      report.push(`## ${dn} ${p} → ${st}${a.blocked ? ' BLOCKED' : ''} · sw ${a.sw}/${a.W}${plays0 ? ' · ⚠ AUDIO PLAYED ON LOAD ' + plays0 : ''}`);
      for (const k of ['overflow', 'tiny', 'small', 'broken']) if (a[k].length) report.push(`- ${k}: ` + a[k].join(' | '));
      if (process.env.QA_CLICK && !a.blocked) {
        // click every visible button-like control that does not leave the page; log unexpected audio / navigation
        const n = await page.evaluate(() => { const L = [...document.querySelectorAll('button,[role=button],[role=tab],.ssx .chips span,summary')].filter(e => { const r = e.getBoundingClientRect(); return r.width && r.height && getComputedStyle(e).visibility !== 'hidden' && !e.closest('#ssg,#ssg-bar,#ssp,.ssp,.ss-cc,#tidio-chat,form[action*=checkout]'); }); L.forEach((e, i) => e.setAttribute('data-qa', i)); return L.length; });
        const url0 = page.url();
        for (let i = 0; i < Math.min(n, 80); i++) {
          const before = await page.evaluate(() => window.__plays || 0).catch(() => -1);
          const label = await page.evaluate((i) => { const e = document.querySelector('[data-qa="' + i + '"]'); return e ? (e.getAttribute('aria-label') || e.textContent || '').trim().replace(/\s+/g, ' ').slice(0, 40) : null; }, i).catch(() => null);
          if (label === null) continue;
          try { await page.locator('[data-qa="' + i + '"]').click({ timeout: 2500, force: false }); } catch (e) { continue; }
          await page.waitForTimeout(700);
          const after = await page.evaluate(() => [window.__plays || 0, window.__lastPlay || '']).catch(() => [-1, '']);
          if (page.url().split('#')[0] !== url0.split('#')[0]) { report.push(`- click "${label}" → navigated to ${page.url()}`); await page.goto(url0, { waitUntil: 'load' }).catch(() => {}); await page.waitForTimeout(2500); await page.evaluate(() => { [...document.querySelectorAll('button,[role=button],[role=tab],.ssx .chips span,summary')].filter(e => { const r = e.getBoundingClientRect(); return r.width && r.height && getComputedStyle(e).visibility !== 'hidden' && !e.closest('#ssg,#ssg-bar,#ssp,.ssp,.ss-cc,#tidio-chat,form[action*=checkout]'); }).forEach((e, j) => e.setAttribute('data-qa', j)); }).catch(() => {}); continue; }
          if (after[0] > before && before >= 0) report.push(`- click "${label}" → AUDIO started (${after[1]})`);
          await page.keyboard.press('Escape').catch(() => {});
        }
      }
      if (errs.length) report.push('- errors: ' + [...new Set(errs)].slice(0, 5).join(' | '));
      await page.close();
      await sleep(6000);
    }
    await ctx.close();
  }
  await browser.close();
  fs.writeFileSync(`${OUT}/report.md`, report.join('\n') + '\n');
  console.log(report.join('\n'));
})();
