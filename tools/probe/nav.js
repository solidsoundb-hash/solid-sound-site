// Probe (21.11): home — header Products / Services and the phone bar's Shop / Studio scroll to #shop / #services;
// the «N templates match» covers start the main player (no jump to the product page).
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
const UA = 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0';
const log = []; const L = (...a) => log.push(a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '));
async function open(b, vp) {
  const ctx = await b.newContext({ viewport: vp, isMobile: true, hasTouch: true, userAgent: UA, locale: 'en-US', deviceScaleFactor: 1 });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage(); p.on('pageerror', e => L('JS ERROR', e.message.slice(0, 200)));
  await p.goto('https://solid-sound.net/en/?nv=' + Date.now(), { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {}); await p.waitForTimeout(3000);
  L('page', vp.width, await p.title()); return p;
}
const pos = p => p.evaluate(() => { const r = id => { const e = document.getElementById(id); return e ? Math.round(e.getBoundingClientRect().top) : null; };
  return { url: location.pathname, y: Math.round(scrollY), shopTop: r('shop'), servicesTop: r('services') }; });
async function tap(p, sel, name, shot) {
  const n = await p.locator(sel).count(); if (!n) { L(name, 'NOT FOUND', sel); return; }
  await p.locator(sel).first().click({ timeout: 8000 }).catch(e => L(name, 'click fail', e.message.split('\n')[0]));
  await p.waitForTimeout(1800); L(name, await pos(p)); await p.screenshot({ path: OUT + '/' + shot + '.png' });
}
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const m = await open(b, { width: 412, height: 780 });
  await m.screenshot({ path: OUT + '/phone-0-top.png' });
  await tap(m, '#ssg-bar a[href$="/all-products/"]', 'phone Shop', 'phone-1-shop');
  await tap(m, '#ssg-bar a[href$="/services-prices/"]', 'phone Studio', 'phone-2-studio');
  await m.evaluate(() => scrollTo(0, 0)); await m.waitForTimeout(800);
  const c = m.locator('.ssw-match .mx').first();
  if (await c.count()) { await c.scrollIntoViewIfNeeded().catch(() => {}); await c.click({ timeout: 8000 }).catch(e => L('cover click fail', e.message.split('\n')[0])); await m.waitForTimeout(4000);
    L('phone cover', await m.evaluate(() => ({ url: location.pathname, playerOpen: document.body.classList.contains('ssp-open'), track: ((document.querySelector('.ssp-title') || {}).textContent || '').trim(), playing: !!document.querySelector('.ssp.is-playing') })));
    await m.screenshot({ path: OUT + '/phone-3-cover.png' }); } else L('phone cover NOT FOUND');
  const t = await open(b, { width: 1280, height: 800 });
  await t.screenshot({ path: OUT + '/wide-0-top.png' });
  await tap(t, '#ssg .g-it > button.g-a[data-h$="/all-products/"]', 'wide Products', 'wide-1-products');
  await tap(t, '#ssg .g-it > button.g-a[data-h$="/services-prices/"]', 'wide Services', 'wide-2-services');
  fs.writeFileSync(OUT + '/nav.txt', log.join('\n') + '\n'); console.log(log.join('\n')); await b.close();
})();
