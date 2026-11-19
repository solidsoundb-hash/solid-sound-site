// Solid Sound — robot "photographer" (owner 19.11.2026: «пускай боты все скринят вподряд каждую кнопочку … весь сайт,
// всё что есть в меню»). Read-only: it never sends forms, never buys, never posts (every non-GET request to the site
// is answered by the robot itself).
//
// For every page (sitemap in English + every link of the desktop and phone menus) on a computer (1280) and a phone (390):
//   1) the whole page;  2) every dropdown of the menu (hover on the computer, tap on the phone);
//   3) every button / tab / arrow / dot / toggle on the page: press it → picture of the screen → undo.
// The same button that repeats on every page (header, footer, bottom bar) is photographed once.
// The home page is also photographed at more widths (360…1920) — the slider must look straight everywhere.
//
//   PHOTO_SHARD=i/n   this robot takes every n-th page (the workflow runs 3 robots: the hosting allows ~3 at once)
//   PHOTO_OUT=shots   output folder: <dev>/<page>/00_page.jpg, 01_<button>.jpg …, index.md
//   PHOTO_PAGES=/en/,/en/wiki/   only these pages (quick test)
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE = 'https://solid-sound.net';
const OUT = process.env.PHOTO_OUT || 'shots';
const SHARD = (process.env.PHOTO_SHARD || '0/1').split('/').map(Number);
const BUDGET = (+process.env.PHOTO_BUDGET_MIN || 150) * 60000, T0 = Date.now();
const MAX_CLICKS = +process.env.PHOTO_MAX_CLICKS || 40;
const UA_DESK = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const UA_PHONE = 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0';
const DEV = {
  pc: { name: 'компьютер', viewport: { width: 1280, height: 900 }, isMobile: false, hasTouch: false, userAgent: UA_DESK },
  phone: { name: 'телефон', viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true, userAgent: UA_PHONE },
};
const HOME_WIDTHS = [[360, 740, 1], [412, 780, 1], [768, 1024, 1], [1024, 768, 0], [1366, 768, 0], [1440, 900, 0], [1920, 1080, 0], [2560, 1300, 0]];
// third-party scripts (pixels, chat, analytics, payments) are not loaded: they change nothing in our design
const ALLOWED = /(^|\.)(solid-sound\.net|jsdelivr\.net|ytimg\.com|youtube-nocookie\.com|fonts\.googleapis\.com|fonts\.gstatic\.com|cdnjs\.cloudflare\.com|unpkg\.com)$/;
// buttons never pressed: they buy, send, delete, log out, or start sound/AI (sound is checked by the QA swarm's "ears")
const DANGER = /delete|remove|удал|log ?out|sign ?out|вийти|\bbuy\b|checkout|\bpay\b|place order|subscribe|\bsend\b|submit|надісл|отправ|order now|add to cart|upload|publish|report|unlock|get free|download/i;
const SKIP_SEL = '#wpadminbar,input,select,textarea,#tidio-chat,.ss-cc,[class*="like"],.add_to_cart_button,.single_add_to_cart_button,[type=submit]';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const slug = (u) => (new URL(u, BASE).pathname + new URL(u, BASE).search).replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home';
const isBlock = (t) => /Захищена сторінка|Сторінка захищена|<title>\s*403 Forbidden/.test(t);
const left = () => BUDGET - (Date.now() - T0);

async function newCtx(browser, d) {
  const ctx = await browser.newContext({ ...d, locale: 'en-US' });
  await ctx.route('**/*', async (route) => {
    const req = route.request(); let host = '';
    try { host = new URL(req.url()).hostname; } catch (e) {}
    if (!ALLOWED.test(host)) return route.abort().catch(() => {});
    if (req.method() !== 'GET' && req.method() !== 'HEAD') return route.fulfill({ status: 200, contentType: 'application/json', body: '{"success":true,"data":{}}' }).catch(() => {});
    if (req.resourceType() === 'media') return route.abort().catch(() => {});   // no sound downloads
    route.continue().catch(() => {});
  });
  return ctx;
}

async function open(page, url) {
  for (let i = 0; i < 4; i++) {
    let ok = false;
    try { const r = await page.goto(url, { waitUntil: 'load', timeout: 120000 }); ok = !!r && r.status() < 500; } catch (e) {}
    const html = await page.content().catch(() => '');
    if (ok && !isBlock(html)) break;
    await sleep(15000 * (i + 1));
  }
  await page.waitForTimeout(2500);
  const no = page.locator('.ss-cc-no:visible').first();   // cookie banner: "Reject non-essential", like a visitor
  if (await no.count().catch(() => 0)) { await no.click({ timeout: 3000 }).catch(() => {}); await page.waitForTimeout(300); }
  await page.evaluate(() => { document.querySelectorAll('video').forEach(v => v.pause()); }).catch(() => {});
}

async function fullShot(page, file, w) {
  const h = await page.evaluate(() => Math.min(document.documentElement.scrollHeight, 9000)).catch(() => 2000);
  await page.screenshot({ path: file, type: 'jpeg', quality: 50, fullPage: true, clip: { x: 0, y: 0, width: w, height: h }, timeout: 45000 }).catch(() => {});
}

// every pressable thing on the page, in reading order, with a key that is the same on every page for shared parts
async function pressables(page) {
  return page.evaluate((SKIP) => {
    const vis = (e) => { const r = e.getBoundingClientRect(), s = getComputedStyle(e); return r.width > 3 && r.height > 3 && s.visibility !== 'hidden' && s.display !== 'none' && +s.opacity !== 0; };
    const txt = (e) => (e.getAttribute('aria-label') || e.innerText || e.title || e.getAttribute('data-tip') || '').replace(/\s+/g, ' ').trim().slice(0, 50);
    const shared = (e) => !!e.closest('header,footer,#ssg,.l-header,.l-footer,nav,[class*="bottom-bar"],[class*="ssg"]');
    const sel = 'button,[role=button],[role=tab],summary,a[href="#"],a[href^="#"],a[href^="javascript"],a:not([href]),[onclick],[aria-expanded],[aria-controls],.w-tabs-item,.owl-prev,.owl-next,.owl-dot,[class*="arrow"],[class*="dot"] > *,[class*="prev"],[class*="next"],label[for]';
    const out = []; const seen = new Set();
    document.querySelectorAll(sel).forEach((e, i) => {
      if (e.closest(SKIP) || e.matches(SKIP) || !vis(e)) return;
      if (e.tagName === 'A' && e.getAttribute('href') && /^https?:|^\//.test(e.getAttribute('href'))) return;
      const t = txt(e); const r = e.getBoundingClientRect();
      const key = (shared(e) ? 'shared|' : 'page|') + e.tagName + '|' + (e.className && e.className.baseVal === undefined ? String(e.className).split(/\s+/).slice(0, 2).join('.') : '') + '|' + t;
      if (seen.has(key)) return; seen.add(key);
      e.setAttribute('data-ph', String(i));
      out.push({ id: String(i), text: t, key, shared: shared(e), y: Math.round(r.top + scrollY) });
    });
    return out;
  }, SKIP_SEL).catch(() => []);
}

async function shootPage(browser, url, dk, doneShared, index) {
  const d = DEV[dk];
  const dir = path.join(OUT, dk, slug(url)); fs.mkdirSync(dir, { recursive: true });
  const ctx = await newCtx(browser, d); const page = await ctx.newPage();
  page.on('dialog', x => x.dismiss().catch(() => {})); page.on('popup', p => p.close().catch(() => {}));
  const rec = { url, dev: d.name, shots: [] };
  try {
    await open(page, url);
    await fullShot(page, path.join(dir, '00_page.jpg'), d.viewport.width); rec.shots.push(['вся страница', '00_page.jpg']);
    // menu: computer — hover every top item that has a submenu; phone — open the menu button
    if (dk === 'pc' && !doneShared.has('menu')) {
      doneShared.add('menu');
      const tops = await page.$$('header li:has(ul), #ssg li:has(ul), .l-header li:has(ul), [aria-haspopup]');
      let n = 0;
      for (const t of tops.slice(0, 15)) {
        if (!(await t.isVisible().catch(() => false))) continue;
        await t.hover({ timeout: 3000 }).catch(() => {}); await page.waitForTimeout(700);
        const label = ((await t.innerText().catch(() => '')) || '').split('\n')[0].trim().slice(0, 30);
        const f = `m${String(++n).padStart(2, '0')}_menu.jpg`; await page.screenshot({ path: path.join(dir, f), type: 'jpeg', quality: 50 }).catch(() => {});
        rec.shots.push(['меню: ' + label, f]);
      }
      await page.mouse.move(5, 600);
    }
    const list = await pressables(page);
    let n = 0;
    for (const b of list) {
      if (n >= MAX_CLICKS || left() < 5 * 60000) break;
      if (b.shared && doneShared.has(dk + b.key)) continue;
      if (DANGER.test(b.text)) continue;
      if (b.shared) doneShared.add(dk + b.key);
      const loc = page.locator(`[data-ph="${b.id}"]`).first();
      if (!(await loc.count().catch(() => 0))) continue;
      const before = page.url();
      try {
        await loc.scrollIntoViewIfNeeded({ timeout: 3000 });
        if (dk === 'pc') { await loc.hover({ timeout: 2000 }).catch(() => {}); }
        await loc.click({ timeout: 3000, noWaitAfter: true });
      } catch (e) { continue; }
      await page.waitForTimeout(900);
      const f = `${String(++n).padStart(2, '0')}_${(b.text || 'button').replace(/[^a-z0-9а-яіїє]+/gi, '_').slice(0, 30)}.jpg`;
      await page.screenshot({ path: path.join(dir, f), type: 'jpeg', quality: 50 }).catch(() => {});
      rec.shots.push([(b.shared ? '(общая) ' : '') + 'нажать «' + (b.text || 'без текста') + '»', f]);
      // undo: Escape, and back to the page if the button opened another one
      await page.keyboard.press('Escape').catch(() => {});
      if (page.url().split('#')[0] !== before.split('#')[0]) { await open(page, url); await pressables(page); }
      else { await loc.click({ timeout: 1500, noWaitAfter: true, trial: false }).catch(() => {}); await page.keyboard.press('Escape').catch(() => {}); await page.waitForTimeout(300); }
    }
  } catch (e) { rec.err = String(e.message || e).split('\n')[0]; }
  index.push(rec);
  await ctx.close().catch(() => {});
}

async function pageList(browser) {
  if (process.env.PHOTO_PAGES) return process.env.PHOTO_PAGES.split(',').map(s => BASE + s.trim());
  const ctx = await newCtx(browser, DEV.pc); const set = new Set([BASE + '/en/']);
  const get = async (u) => { for (let i = 0; i < 4; i++) { try { const r = await ctx.request.get(u, { timeout: 60000, headers: { 'User-Agent': UA_DESK } }); const t = await r.text(); if (r.status() === 200 && !isBlock(t)) return t; } catch (e) {} await sleep(6000 * (i + 1)); } return ''; };
  const idx = await get(BASE + '/wp-sitemap.xml');
  for (const sm of (idx.match(/<loc>[^<]+/g) || []).map(s => s.slice(5))) {
    const x = await get(sm);
    (x.match(/<loc>[^<]+/g) || []).map(s => s.slice(5)).forEach(u => set.add(u.split('#')[0]));
    await sleep(1000);
  }
  // menu links (computer and phone menus), English only for now (owner 17.11: translations later)
  for (const dk of ['pc', 'phone']) {
    const c = await newCtx(browser, DEV[dk]); const p = await c.newPage();
    await open(p, BASE + '/en/');
    const links = await p.evaluate(() => [...document.querySelectorAll('a[href]')].map(a => a.href)).catch(() => []);
    links.filter(h => h.startsWith(BASE)).forEach(h => set.add(h.split('#')[0]));
    await c.close();
  }
  await ctx.close();
  const other = /^\/(ua|de|fr|pt|es|it)\//;
  return [...set].filter(u => { const p = new URL(u).pathname; return !other.test(p) && !/\/(wp-|feed|cart|checkout|my-account\/.+|customer-logout|\?add-to-cart)/.test(p + new URL(u).search); }).sort();
}

(async () => {
  const browser = await chromium.launch({ ...(process.env.PW_CHANNEL ? { channel: process.env.PW_CHANNEL } : {}) });
  fs.mkdirSync(OUT, { recursive: true });
  const all = await pageList(browser);
  const mine = all.filter((u, i) => i % SHARD[1] === SHARD[0]);
  console.log(`pages: ${all.length}, this robot: ${mine.length}`);
  const index = []; const doneShared = new Set();
  // the home page at many widths (owner 19.11: «слайдер косой»)
  if (SHARD[0] === 0) {
    const dir = path.join(OUT, 'home-widths'); fs.mkdirSync(dir, { recursive: true });
    const rec = { url: BASE + '/en/', dev: 'разные ширины', shots: [] };
    for (const [w, h, mob] of HOME_WIDTHS) {
      const ctx = await newCtx(browser, { viewport: { width: w, height: h }, isMobile: !!mob, hasTouch: !!mob, userAgent: mob ? UA_PHONE : UA_DESK });
      const p = await ctx.newPage(); await open(p, BASE + '/en/'); await p.waitForTimeout(1500);
      const f = `${w}x${h}.jpg`; await p.screenshot({ path: path.join(dir, f), type: 'jpeg', quality: 60 }).catch(() => {});
      rec.shots.push([`${w}×${h}`, f]); await ctx.close();
    }
    index.push(rec);
  }
  for (const u of mine) {
    if (left() < 5 * 60000) { console.log('time budget reached'); break; }
    for (const dk of ['pc', 'phone']) { await shootPage(browser, u, dk, doneShared, index); console.log(dk, u); }
  }
  fs.writeFileSync(path.join(OUT, `index-${SHARD[0]}.json`), JSON.stringify(index, null, 1));
  await browser.close();
})();
