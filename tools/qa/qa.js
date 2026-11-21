// Solid Sound — QA bot ("автотестировщик").
// Walks the site like a person (phone + computer, guest + test accounts), clicks things and writes a bug list
// that reads as tasks: where, steps, expected, actual, screenshot.
//
//   QA_MODE=quick node tools/qa/qa.js     (~15–25 min: key pages, all languages' home, sound, cart, search)
//   QA_MODE=full  node tools/qa/qa.js     (all pages from the sitemap in all languages, every button)
//
// Output: qa/latest/report.md (Russian, for the owner and for Claude), qa/latest/bugs.json, qa/latest/shots/*.jpg
// Env (optional): TEST_USER/TEST_PASS, TEST_ADMIN_USER/TEST_ADMIN_PASS — logged-in checks; HTTPS_PROXY — local runs;
// QA_PAGES=/en/,/en/wiki/ — only these pages; QA_BUDGET_MIN — stop deep checks after N minutes.
//
// Safe by design: no payments, no form submissions, no AI search (paid), likes/plays/leads/chat requests are
// answered by the bot itself and never reach the site, analytics/pixels/chat widgets are not loaded.
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
let EARS = null; try { EARS = require('./ears'); } catch (e) {}   // listens to every sound preview (ffmpeg)
let KB = null; try { KB = require('./kb'); } catch (e) {}   // common-bug knowledge base (SEO, prices, phone, accessibility…), see docs/qa-knowledge.md

const BASE = 'https://solid-sound.net';
const MODE = (process.env.QA_MODE || 'quick').toLowerCase();
const FULL = MODE === 'full';
const SMOKE = MODE === 'smoke';   // short check after a deploy (~5–8 min): key pages, sound, UA, cart, search
const OUT = process.env.QA_OUT || 'qa/latest';
const PROXY = process.env.HTTPS_PROXY || process.env.https_proxy || '';
const BUDGET_MS = (+(process.env.QA_BUDGET_MIN || (FULL ? 140 : SMOKE ? 8 : 40))) * 60000;
const T0 = (+process.env.QA_STARTED * 1000) || Date.now();   // swarm: the merge counts time from the start of the whole run
/* swarm (17.11.2026, owner: «гит хаб боты неограниченны — грузи по максимуму»): many robots in parallel.
   QA_PARTS=pages,a31,ears,langs,scen,login,links (default: all) — which parts this robot does.
   QA_SHARD=i/n — this robot takes every n-th page of the list (pages and their links).
   QA_MERGE=dir1,dir2,... — no browsing: merge the robots' reports into QA_OUT. */
const PARTS = new Set((process.env.QA_PARTS || 'pages,a31,ears,langs,scen,login,links').split(',').map(x => x.trim()));
const part = (x) => PARTS.has(x);
const SHARD = (process.env.QA_SHARD || '').match(/^(\d+)\/(\d+)$/);
const inShard = (p, i) => !SHARD || i % +SHARD[2] === +SHARD[1];
const timeLeft = () => BUDGET_MS - (Date.now() - T0);

const LANGS = ['en', 'ua', 'de', 'fr', 'pt', 'es', 'it'];
const HREFLANG = { en: 'en', ua: 'uk', de: 'de', fr: 'fr', pt: 'pt', es: 'es', it: 'it' };
const LANG_RU = { en: 'английская', ua: 'украинская', de: 'немецкая', fr: 'французская', pt: 'португальская', es: 'испанская', it: 'итальянская' };
const LANG_SHORT = { en: 'EN', ua: 'UA', de: 'DE', fr: 'FR', pt: 'PT', es: 'ES', it: 'IT' };
const UA_DESK = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const UA_PHONE = 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0';
const DEVICES = {
  desktop: { name: 'компьютер', viewport: { width: 1280, height: 900 }, isMobile: false, hasTouch: false, userAgent: UA_DESK },
  phone: { name: 'телефон', viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true, userAgent: UA_PHONE },
  /* owner's phone: Samsung Galaxy A31 in Chrome — the browser bars leave ~780 px of height */
  a31: { name: 'Samsung A31', viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' },
};

// requests that change data on the site: answered by the bot, never sent
const SIDE_EFFECT = /ss_rt_like|ss_rt_play|ss_calc_send|album_mastering_quote|ss_free_join|ss_free_dl|ss_bot_lead|ss_chat_|ss_fb_submit|mwai|ss_ai|ai_search|wpforo|comment|contact|subscribe|newsletter|lead|sib_|mailin|brevo/i;
// third-party hosts the pages may load (fonts, CDN). Everything else (pixels, analytics, chat, payments) is skipped.
const ALLOWED_HOSTS = /(^|\.)(solid-sound\.net|jsdelivr\.net|ytimg\.com|youtube-nocookie\.com|fonts\.googleapis\.com|fonts\.gstatic\.com|cdnjs\.cloudflare\.com|unpkg\.com)$/;
// buttons the bot never presses on its own
const DANGER = /delete|remove|видал|удал|log ?out|sign ?out|вийти|выйти|abmelden|\bbuy\b|купити|checkout|\bpay\b|оплат|place order|subscribe|підпис|\bsend\b|submit|надісл|отправ|\bjoin\b|download|завантаж|скачат|clear all|reset|скинути|\bai\b|mic|voice|record|upload|accept all|прийняти|google|facebook|apple|create account|log ?in|sign ?in|увійти|register|зареєстр/i;
const SKIP_CLICK_SEL = '.ss-ai,.g-ref,.g-mic,[class*="mic"],#top-q,.ss-cc,.ss-cc-no,.ss-cc-yes,#tidio-chat,.ssp,.ssw,.add_to_cart_button,.single_add_to_cart_button,.ss-like,[class*="like"],[class*="play"],[aria-label*="Play" i],[aria-label*="Pause" i],.g-lang,.g-logo,#wpadminbar,input,select,textarea';

/* ---------------------------------------------------------------- bugs */
const SEV = { critical: '🔴 Критично', major: '🟠 Важно', minor: '🟡 Мелочь', check: '⚪ Проверить глазами' };
const SEV_ORDER = ['critical', 'major', 'minor', 'check'];
const bugs = new Map();
const notChecked = [];
const stats = { pages: 0, clicks: 0, sounds: 0, links: 0, blockedRequests: 0 };
let shotN = 0;
const MAX_SHOTS = FULL ? 160 : 60;
const rel = (u) => { try { const x = new URL(u, BASE); return x.origin === BASE ? x.pathname + x.search : u; } catch (e) { return String(u); } };

/* owner 17.11: translations later — the site is finished in English first. Translation findings are only counted
   (one line in the report), not listed. QA_L10N=1 brings them back. */
const L10N_TYPES = /^(untranslated|menulang|langsw|langmiss)$/;
let l10nSkipped = 0;
async function bug(b, page, el) {
  if (process.env.QA_L10N !== '1' && L10N_TYPES.test(b.type || '')) { l10nSkipped++; return { actual: '', more: 0 }; }
  const sig = b.sig || (b.type + '|' + b.title);
  let g = bugs.get(sig);
  if (!g) { g = { sev: b.sev, type: b.type, title: b.title, steps: b.steps || [], expected: b.expected || '', actual: b.actual || '', sig, where: [], devices: [], shot: '' }; bugs.set(sig, g); }
  const w = b.page ? rel(b.page) : '';
  if (w && !g.where.includes(w)) g.where.push(w);
  if (b.device && !g.devices.includes(b.device)) g.devices.push(b.device);
  if (page && !g.shot && shotN < MAX_SHOTS) {
    try {
      if (el) await el.evaluate(e => { e.scrollIntoView({ block: 'center' }); e.setAttribute('data-qa-mark', e.style.outline || ''); e.style.outline = '4px solid #ff1744'; e.style.outlineOffset = '2px'; }).catch(() => {});
      const f = `shots/${String(++shotN).padStart(3, '0')}.jpg`;
      await page.screenshot({ path: path.join(OUT, f), type: 'jpeg', quality: 55, timeout: 15000 });
      g.shot = f;
      if (el) await el.evaluate(e => { e.style.outline = e.getAttribute('data-qa-mark') || ''; e.removeAttribute('data-qa-mark'); }).catch(() => {});
    } catch (e) {}
  }
  return g;
}

/* ---------------------------------------------------------------- browser */
const cache = new Map();
let active = 0; const waitQ = [];
const slot = () => new Promise(r => (active < 2 ? (active++, r()) : waitQ.push(r)));
const free = () => { const n = waitQ.shift(); n ? n() : active--; };
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const isBlockPage = (body) => /Захищена сторінка|<title>\s*403 Forbidden/.test(body);

async function newContext(browser, deviceKey) {
  const d = DEVICES[deviceKey];
  const ctx = await browser.newContext({ viewport: d.viewport, isMobile: d.isMobile, hasTouch: d.hasTouch, userAgent: d.userAgent, locale: 'en-US' });
  await ctx.addInitScript({ path: path.join(__dirname, 'media-hook.js') });
  await ctx.route('**/*', async (route) => {
    const req = route.request(); const url = req.url(); let host = '';
    try { host = new URL(url).hostname; } catch (e) {}
    if (!ALLOWED_HOSTS.test(host)) return route.abort().catch(() => {});
    if (host.endsWith('solid-sound.net') && req.method() === 'POST') {
      const body = req.postData() || '';
      if ((/admin-ajax\.php|\/wp-json\//.test(url) && SIDE_EFFECT.test(url + ' ' + body)) || /\/wp-json\/mwai/.test(url)) {
        stats.blockedRequests++;
        return route.fulfill({ status: 200, contentType: 'application/json', body: '{"success":true,"data":{}}' }).catch(() => {});
      }
    }
    const cacheable = req.method() === 'GET' && /\.(css|js|woff2?|ttf|otf|svg|png|jpe?g|webp|gif|ico)(\?|$)/i.test(url) && host.endsWith('solid-sound.net');
    if (cacheable && cache.has(url)) return route.fulfill(cache.get(url)).catch(() => {});
    if (!host.endsWith('solid-sound.net')) return route.continue().catch(() => {});
    await slot();
    try {
      let res;
      for (let i = 0; i < 6; i++) {
        res = await route.fetch({ timeout: 90000 });
        if (res.status() === 429) { await sleep(4000 * (i + 1)); continue; }
        if (req.resourceType() === 'document' && (res.status() === 403 || res.status() === 200)) {
          const t = await res.text().catch(() => '');
          if (isBlockPage(t)) { await sleep(15000 * (i + 1)); continue; }
        }
        break;
      }
      if (cacheable && res.status() === 200) { const body = await res.body(); cache.set(url, { status: 200, headers: res.headers(), body }); }
      await route.fulfill({ response: res });
    } catch (e) { try { await route.abort(); } catch (_) {} } finally { free(); }
  });
  return ctx;
}

async function newPage(ctx) {
  const page = await ctx.newPage();
  page.qaErrs = []; page.qaBad = [];
  page.on('pageerror', e => page.qaErrs.push((e.message || String(e)).split('\n')[0].slice(0, 200)));
  page.on('console', m => { if (m.type() === 'error' && !/Failed to load resource|net::ERR|ERR_FAILED|blocked|CanMakePayment|GSI_LOGGER|favicon|preload|Third-party cookie|Content Security Policy/i.test(m.text())) page.qaErrs.push('console: ' + m.text().split('\n')[0].slice(0, 200)); });
  page.on('response', r => { const u = r.url(); if (u.startsWith(BASE) && r.status() >= 400 && r.status() !== 429 && !/admin-ajax|wc-ajax|wp-json/.test(u)) page.qaBad.push(r.status() + ' ' + rel(u)); });
  page.on('dialog', d => d.dismiss().catch(() => {}));
  page.on('popup', p => p.close().catch(() => {}));
  return page;
}

async function open(page, url) {
  page.qaErrs.length = 0; page.qaBad.length = 0;
  let status = 0;
  for (let i = 0; i < 3; i++) {
    try {
      const r = await page.goto(url.startsWith('http') ? url : BASE + url, { waitUntil: 'load', timeout: 150000 });
      status = r ? r.status() : 0;
    } catch (e) { status = -1; }
    const title = await page.title().catch(() => '');
    if (status > 0 && !/Захищена сторінка/.test(title)) break;
    await sleep(20000);
  }
  await page.waitForTimeout(2500);
  // a visitor answers the cookie question once: "Reject non-essential"
  const no = page.locator('.ss-cc-no:visible').first();
  if (await no.count().catch(() => 0)) { await no.click({ timeout: 3000 }).catch(() => {}); await page.waitForTimeout(300); }
  await page.evaluate(() => { window.__qaDoc = window.__qaDoc || Math.random(); }).catch(() => {});
  return status;
}

/* ---------------------------------------------------------------- page facts */
async function facts(page) {
  return page.evaluate(() => {
    const vw = document.documentElement.clientWidth;
    const vis = (e) => { if (!e) return false; const r = e.getBoundingClientRect(); const s = getComputedStyle(e); return r.width > 0 && r.height > 0 && s.visibility !== 'hidden' && s.display !== 'none'; };
    const txt = (e) => ((e.querySelector && e.querySelector('.g-l') ? e.querySelector('.g-l').textContent : '') || e.getAttribute('aria-label') || e.innerText || e.title || '').replace(/\s+/g, ' ').trim().slice(0, 60);
    const abs = (h) => { try { return new URL(h, location.href).href; } catch (e) { return h || ''; } };
    let over = null;
    if (document.documentElement.scrollWidth > vw + 1) {
      let best = null;
      document.querySelectorAll('body *').forEach(el => { const r = el.getBoundingClientRect(); if (r.width && r.right > vw + 2 && getComputedStyle(el).position !== 'fixed') { const w = r.right - vw; if (!best || w > best.w) best = { w, el }; } });
      if (best) { best.el.setAttribute('data-qa-over', '1'); over = { w: Math.round(best.w), d: best.el.tagName.toLowerCase() + (best.el.id ? '#' + best.el.id : '') + (typeof best.el.className === 'string' && best.el.className ? '.' + best.el.className.trim().split(/\s+/).slice(0, 2).join('.') : '') }; }
    }
    const brokenImg = [...document.images].filter(i => i.complete && i.naturalWidth === 0 && i.src && !/^data:/.test(i.src) && vis(i)).map(i => i.src).slice(0, 5);
    const main = document.querySelector('#page-content') || document.body;
    const text = (main.innerText || '').slice(0, 60000);
    const leaks = [];
    [[/\bundefined\b/, 'undefined'], [/\bNaN\b/, 'NaN'], [/\[object Object\]/, '[object Object]'], [/\{\{[^}]{0,40}\}\}/, '{{…}}'], [/\[(vc_|us_|wcfm|woocommerce_)[a-z_]*[^\]]{0,60}\]/, 'шорткод'], [/Lorem ipsum/i, 'Lorem ipsum'], [/(Fatal error|Warning|Notice|Deprecated): .{0,120} in \//, 'ошибка PHP'], [/There has been a critical error/i, 'критическая ошибка WordPress']]
      .forEach(([re, n]) => { const m = text.match(re); if (m) leaks.push(n + ': «' + text.substr(Math.max(0, m.index - 40), 110).replace(/\s+/g, ' ').trim() + '»'); });
    const fp = [];
    main.querySelectorAll('[class]').forEach(e => { if (typeof e.className !== 'string') return; e.className.split(/\s+/).forEach(c => { if (c && c.length < 40 && !/\d{3,}|^(vc_custom|us_custom|is-|active|cur|open|ss-ui|ssp-|loaded|lazy|wpb_animate|animated|start)/.test(c)) fp.push(c); }); });
    const hreflang = {}; document.querySelectorAll('link[rel="alternate"][hreflang]').forEach(l => { hreflang[l.getAttribute('hreflang')] = l.href; });
    const menu = {
      top: [...document.querySelectorAll('#ssg .g-nav > .g-it')].map(it => { const a = it.querySelector(':scope > .g-a'); if (!a) return null; return { label: txt(a), href: (a.getAttribute('href') || a.getAttribute('data-h')) ? abs(a.getAttribute('href') || a.getAttribute('data-h')) : '', cur: a.classList.contains('cur'), sub: [...it.querySelectorAll('.g-dd a[href]')].map(x => ({ label: txt(x), href: x.href, cur: x.classList.contains('cur') })) }; }).filter(Boolean),
      right: [...document.querySelectorAll('#ssg .g-right .g-it > a.g-a, #ssg .g-right .g-it > button.g-a:not(.g-lang)')].map(a => ({ label: txt(a), href: a.href || '', cur: a.classList.contains('cur') })),
      bar: [...document.querySelectorAll('#ssg-bar a[href]')].map(x => ({ label: txt(x), href: x.href, cur: x.classList.contains('cur') })),
      sheet: [...document.querySelectorAll('#ssg-sheet a[href]')].map(x => ({ label: txt(x), href: x.href })),
      lang: (document.querySelector('#ssg .g-lang') || {}).textContent || '',
      langs: [...document.querySelectorAll('#ssg .g-right .g-dd a[hreflang]')].map(a => ({ k: a.getAttribute('hreflang'), label: txt(a), href: a.href })),
      account: txt(document.querySelector('#ssg .g-right .g-it > .g-a[aria-haspopup], #ssg .g-right a.g-a[href*="my-account"]') || document.createElement('i')),
    };
    const links = [...document.querySelectorAll('a[href]')].filter(a => a.href.startsWith(location.origin)).map(a => ({ h: a.href.split('#')[0], t: txt(a).slice(0, 40) }));
    const anchors = [...document.querySelectorAll('a[href^="#"]:not([data-chat]):not([role=button])')].filter(vis).map(a => a.getAttribute('href')).filter(h => h.length > 1 && !/^#(\/|!|top$|0$|account$)/.test(h)).filter(h => { try { return !document.getElementById(decodeURIComponent(h.slice(1))) && !document.querySelector('[name="' + h.slice(1).replace(/"/g, '') + '"]'); } catch (e) { return false; } });
    // button shapes: the house style is a rounded rectangle (corner radius 8–12px), not a "pill" or a circle
    const shapes = [];
    document.querySelectorAll('button, a.button, .w-btn, input[type=submit], a[class*="btn"], [role=button]').forEach(e => {
      const rc = e.getBoundingClientRect(); if (rc.width < 24 || rc.height < 24 || !vis(e)) return;
      const st = getComputedStyle(e);
      const filled = !/rgba\(0, 0, 0, 0\)|transparent/.test(st.backgroundColor) || (st.backgroundImage && st.backgroundImage !== 'none');
      const bordered = parseFloat(st.borderTopWidth) > 0 && st.borderTopStyle !== 'none';
      if (!filled && !bordered) return;
      const r = parseFloat(st.borderTopLeftRadius) || 0;
      const label = (e.innerText || e.getAttribute('aria-label') || e.title || '').trim().replace(/\s+/g, ' ').slice(0, 40);
      const icon = !(e.innerText || '').trim() || rc.width < rc.height * 1.3;
      let kind = '';
      if (r >= rc.height / 2 - 1) kind = icon ? 'circle' : 'pill';
      else if (r < 6 || r > 14) kind = 'odd';
      if (kind) { e.setAttribute('data-qa-shape', kind + '|' + label); shapes.push({ kind, label, r: Math.round(r), h: Math.round(rc.height) }); }
    });
    const au = new Set();
    const isA = (x) => typeof x === 'string' && /\.(mp3|wav|ogg|m4a)(\?|$)/i.test(x);
    document.querySelectorAll('[data-src],audio[src],source[src]').forEach(e => { const v = e.getAttribute('data-src') || e.getAttribute('src'); if (isA(v)) au.add(new URL(v, location.href).href); });
    try { Object.values((window.SS_RT || {}).p || {}).forEach(t => { if (t && isA(t.s)) au.add(t.s); }); } catch (e) {}
    return {
      audio: [...au],
      shapes,
      url: location.href, title: document.title, h1: ((document.querySelector('h1') || {}).innerText || '').trim().slice(0, 80), lang: document.documentElement.lang || '',
      over, brokenImg, leaks, fp: [...new Set(fp)], hreflang, menu, links, anchors: [...new Set(anchors)].slice(0, 5), words: text.split(/\s+/).length,
      textSample: text.slice(0, 4000), hasMenu: !!document.querySelector('#ssg, #ss-app header'), logged: document.body.classList.contains('logged-in'),
    };
  });
}

const urlLang = (u) => { const m = new URL(u, BASE).pathname.match(/^\/(en|ua|de|fr|pt|es|it)(\/|$)/); return m ? m[1] : ''; };
const normPath = (u) => { try { return new URL(u, BASE).pathname.replace(/^\/(en|ua|de|fr|pt|es|it)(?=\/|$)/, '').replace(/\/+$/, '').toLowerCase() || '/'; } catch (e) { return u; } };
const jaccard = (a, b) => { const A = new Set(a), B = new Set(b); let i = 0; A.forEach(x => { if (B.has(x)) i++; }); return A.size + B.size ? i / (A.size + B.size - i) : 1; };
const langLookTitle = (p) => p === '/' ? 'Главная на других языках — другая (старая) страница, а не переведённая новая' : `Страница ${p} на других языках выглядит иначе, чем английская (другая страница)`;
const errKey = (s) => s.replace(/https?:\/\/\S+/g, 'URL').replace(/\d+/g, 'N').slice(0, 120);

/* ---------------------------------------------------------------- per-page checks */
const visited = new Map();   // url -> facts (+status, device)
const linkSources = new Map(); // url -> Set(source page)
const audioUrls = new Map();   // sound preview url -> Set(page)
let refMenu = { desktop: null, phone: null };

async function checkPage(page, url, dev, opts = {}) {
  // pages stop 10 min early so the ears (listening to every preview) always get their turn (owner 17.11)
  if (timeLeft() < (EARS && !SMOKE ? 10 * 60000 : 0)) { notChecked.push(`${rel(url)} (${DEVICES[dev].name}) — не хватило времени`); return null; }
  const status = await open(page, url);
  stats.pages++;
  const devName = DEVICES[dev].name;
  const here = { page: url, device: devName };
  if (status <= 0) { notChecked.push(`${rel(url)} (${devName}) — сайт не ответил / защита от ботов`); return null; }
  let f;
  try { f = await facts(page); } catch (e) { notChecked.push(`${rel(url)} (${devName}) — ${e.message.split('\n')[0]}`); return null; }
  f.status = status; f.device = dev;
  visited.set(rel(url) + '|' + dev, f);
  const L = urlLang(url) || 'en';

  if (status >= 400) await bug({ ...here, sev: 'critical', type: 'http', sig: 'http|' + rel(url), title: `Страница ${rel(url)} не открывается (код ${status})`, steps: [`Открыть ${BASE}${rel(url)}`], expected: 'Страница открывается', actual: `Сервер ответил ошибкой ${status}` }, page);
  for (const l of f.leaks) await bug({ ...here, sev: /PHP|критическая/.test(l) ? 'critical' : 'major', type: 'leak', sig: 'leak|' + l.slice(0, 30) + '|' + rel(url), title: `На странице виден технический текст (${l.split(':')[0]})`, steps: [`Открыть ${BASE}${rel(url)}`], expected: 'Только нормальный текст для покупателя', actual: l }, page);
  for (const e of [...new Set(page.qaErrs)].slice(0, 4)) await bug({ ...here, sev: 'major', type: 'js', sig: 'js|' + errKey(e), title: `Ошибка в коде страницы (JavaScript)`, steps: [`Открыть ${BASE}${rel(url)}`, 'Посмотреть консоль браузера'], expected: 'Без ошибок', actual: e });
  for (const b of [...new Set(page.qaBad)].slice(0, 4)) await bug({ ...here, sev: 'major', type: 'res', sig: 'res|' + b.replace(/\?.*/, ''), title: `Не загружается файл или ресурс на странице`, steps: [`Открыть ${BASE}${rel(url)}`], expected: 'Все файлы загружаются', actual: b });
  if (f.over) await bug({ ...here, sev: 'major', type: 'overflow', sig: 'over|' + dev + '|' + f.over.d, title: `Страница шире экрана (${devName}): её можно сдвинуть вбок`, steps: [`Открыть ${BASE}${rel(url)} на ${devName === 'телефон' ? 'телефоне' : 'компьютере'}`, 'Провести пальцем влево'], expected: 'Страница не двигается вбок', actual: `Элемент ${f.over.d} вылезает за край на ${f.over.w}px` }, page, await page.$('[data-qa-over]'));
  for (const s of f.brokenImg) await bug({ ...here, sev: 'major', type: 'img', sig: 'img|' + s, title: 'Картинка не загружается', steps: [`Открыть ${BASE}${rel(url)}`], expected: 'Картинка видна', actual: rel(s) }, page, await page.$(`img[src="${s.replace(/"/g, '\\"')}"]`));
  for (const a of f.anchors) await bug({ ...here, sev: 'minor', type: 'anchor', sig: 'anchor|' + rel(url) + a, title: `Ссылка «${a}» ведёт в никуда (нет такого раздела на странице)`, steps: [`Открыть ${BASE}${rel(url)}`, `Нажать ссылку ${a}`], expected: 'Прокрутка к разделу', actual: 'Раздела с таким id нет' }, page, await page.$(`a[href="${a.replace(/"/g, '\\"')}"]`));
  if (!f.hasMenu && status < 400) await bug({ ...here, sev: 'major', type: 'nomenu', sig: 'nomenu|' + rel(url), title: 'На странице нет общего меню сайта', steps: [`Открыть ${BASE}${rel(url)}`], expected: 'Сверху меню как на остальных страницах', actual: 'Меню нет' }, page);
  const want = HREFLANG[L];
  if (urlLang(url) && f.lang && !f.lang.toLowerCase().startsWith(want)) await bug({ ...here, sev: 'major', type: 'htmllang', sig: 'htmllang|' + rel(url), title: `Страница ${rel(url)} помечена как язык «${f.lang}», а должна быть «${want}»`, steps: [`Открыть ${BASE}${rel(url)}`], expected: `<html lang="${want}…">`, actual: `<html lang="${f.lang}">` });

  // text language on translated pages
  if (L !== 'en' && f.words > 60) {
    const words = f.textSample.toLowerCase().match(/[a-zа-яіїєґäöüßéèàçñõãáíóúâêô']+/g) || [];
    const en = words.filter(w => /^(the|and|to|of|for|with|your|you|is|are|on|this|that|our|all|from|by|we|it|be|can|will|get|how|what|more)$/.test(w)).length / Math.max(1, words.length);
    const cyr = words.filter(w => /[а-яіїєґ]/.test(w)).length / Math.max(1, words.length);
    if (en > 0.07 || (L === 'ua' && cyr < 0.35)) await bug({ ...here, sev: 'minor', type: 'untranslated', sig: 'untr|' + rel(url), title: `Страница на ${LANG_SHORT[L]} в основном не переведена (много английского текста)`, steps: [`Открыть ${BASE}${rel(url)}`], expected: `Текст на языке ${LANG_SHORT[L]}`, actual: `Доля английских слов ${(en * 100).toFixed(0)}%${L === 'ua' ? `, кириллицы ${(cyr * 100).toFixed(0)}%` : ''}` }, page);
  }

  for (const x of f.shapes || []) {
    const lab = x.label || 'без текста';
    const el = await page.$(`[data-qa-shape="${(x.kind + '|' + x.label).replace(/["\\]/g, '\\$&')}"]`);
    if (x.kind === 'pill') await bug({ ...here, sev: 'minor', type: 'style-pill', sig: 'pill|' + lab, title: `Кнопка «${lab}» круглая («таблетка»), а не в стиле сайта`, steps: [`Открыть ${BASE}${rel(url)}`, `Найти кнопку «${lab}»`], expected: 'Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта', actual: `Углы скруглены полностью (радиус ${x.r}px при высоте ${x.h}px)` }, page, el);
    else if (x.kind === 'circle') await bug({ ...here, sev: 'check', type: 'style-circle', sig: 'circle|' + lab, title: `Круглая кнопка-значок «${lab}» — нужна ли квадратная со скруглёнными углами?`, steps: [`Открыть ${BASE}${rel(url)}`], expected: 'Квадратная кнопка со скруглёнными углами 8–12px', actual: `Круг (радиус ${x.r}px, размер ${x.h}px)` }, page, el);
    else await bug({ ...here, sev: 'check', type: 'style-odd', sig: 'odd|' + lab, title: `Кнопка «${lab}» скруглена не так, как остальные`, steps: [`Открыть ${BASE}${rel(url)}`], expected: 'Скругление углов 8–12px', actual: `Радиус ${x.r}px` }, page, el);
  }
  if (KB) { try { for (const b of await KB.run(page, { url, dev, devName })) { const g = await bug({ page: url, device: devName, ...b, sig: 'kb|' + String(b.type || '').split('|')[0] + '|' + b.title }, page); const extra = String(b.actual || '').slice(0, 120); if (extra && !g.actual.includes(extra) && (g.more = (g.more || 0) + 1) <= 4) g.actual += ' · ' + extra; } } catch (e) {} }
  await checkMenu(page, f, url, dev, L);
  if (dev === 'desktop') await checkLangOptions(page, f, url);
  (f.audio || []).forEach(u => { if (!audioUrls.has(u)) audioUrls.set(u, new Set()); audioUrls.get(u).add(rel(url)); });
  f.links.forEach(l => { const k = l.h; if (!linkSources.has(k)) linkSources.set(k, new Set()); linkSources.get(k).add(rel(url)); });
  return f;
}

async function checkMenu(page, f, url, dev, L) {
  const devName = DEVICES[dev].name; const here = { page: url, device: devName };
  const m = f.menu;
  const topCur = m.top.filter(x => x.cur).map(x => x.label);
  if (topCur.length > 1) await bug({ ...here, sev: 'major', type: 'menucur', sig: 'menucur|' + L + '|' + topCur.join('+'), title: `В меню подсвечены сразу несколько пунктов: ${topCur.join(' и ')}`, steps: [`Открыть ${BASE}${rel(url)}`, 'Посмотреть на верхнее меню'], expected: 'Подсвечен один пункт — раздел, где вы находитесь', actual: `Подсвечены: ${topCur.join(', ')}` }, page, await page.$('#ssg .g-nav'));
  const barCur = m.bar.filter(x => x.cur).map(x => x.label);
  if (barCur.length > 1) await bug({ ...here, sev: 'major', type: 'menucur', sig: 'barcur|' + L + '|' + barCur.join('+'), title: `В нижней панели подсвечены сразу несколько пунктов: ${barCur.join(' и ')}`, steps: [`Открыть ${BASE}${rel(url)} на телефоне`], expected: 'Подсвечен один пункт', actual: `Подсвечены: ${barCur.join(', ')}` }, page, await page.$('#ssg-bar'));

  const all = [...m.top.flatMap(x => [x, ...x.sub]), ...m.bar, ...m.right].filter(x => x.href && x.href.startsWith(BASE));
  const wrongLang = [...new Map(all.filter(x => { const xl = urlLang(x.href); return xl && xl !== L; }).map(x => [x.label + rel(x.href), `«${x.label}» → ${rel(x.href)}`])).values()];
  if (wrongLang.length) await bug({ ...here, sev: 'major', type: 'menulang', sig: 'menulang|' + L, title: `Меню на ${LANG_SHORT[L]} ведёт на страницы на другом языке`, steps: [`Открыть ${BASE}${rel(url)}`, 'Нажать любой из пунктов ниже'], expected: `Каждый пункт открывает страницу на ${LANG_SHORT[L]}`, actual: `${wrongLang.length} пунктов: ` + wrongLang.slice(0, 12).join(', ') + (wrongLang.length > 12 ? ' …' : '') });
  // same menu in every language: compare with English (position by position)
  if (L === 'en' && !refMenu[dev] && (dev === 'phone' ? m.bar.length : m.top.length)) refMenu[dev] = m;
  const ref = refMenu[dev];
  if (ref && L !== 'en' && normPath(url) === '/') {
    const a = dev === 'phone' ? ref.bar : ref.top, b = dev === 'phone' ? m.bar : m.top;
    const diffs = [];
    for (let i = 0; i < Math.max(a.length, b.length); i++) {
      if (!a[i] || !b[i]) { diffs.push(`пункт №${i + 1}: ${b[i] ? `лишний «${b[i].label}»` : `нет пункта «${a[i].label}»`}`); continue; }
      if ((a[i].href || b[i].href) && normPath(a[i].href || '/x') !== normPath(b[i].href || '/x')) diffs.push(`пункт №${i + 1} «${b[i].label}» ведёт на ${normPath(b[i].href)}, а в EN «${a[i].label}» → ${normPath(a[i].href)}`);
      const sa = (a[i].sub || []).map(s => normPath(s.href)), sb = (b[i].sub || []).map(s => normPath(s.href));
      sa.forEach((p, j) => { if (sb[j] !== p) diffs.push(`в выпадающем списке «${b[i].label}» пункт №${j + 1} «${(b[i].sub[j] || {}).label || '—'}» ведёт на ${sb[j] || '—'}, а в EN на ${p}`); });
    }
    if (diffs.length) await bug({ ...here, sev: 'major', type: 'menudiff', sig: 'menudiff|' + dev + '|' + L, title: `Меню на ${LANG_SHORT[L]} (${devName}) отличается от английского: пункты ведут не туда`, steps: [`Открыть ${BASE}/${L}/ и ${BASE}/en/`, 'Сравнить пункты меню'], expected: 'Те же пункты меню, ведут на те же страницы (на своём языке)', actual: diffs.slice(0, 8).join('; ') }, page, await page.$(dev === 'phone' ? '#ssg-bar' : '#ssg .g-nav'));
    const keep = /^(wiki|faq|ai|free|en|ua|de|fr|pt|es|it)$/i;
    const same = []; a.forEach((x, i) => { if (b[i] && x.label && x.label === b[i].label && !keep.test(x.label)) same.push(x.label); (x.sub || []).forEach((s, j) => { const t = (b[i] && b[i].sub && b[i].sub[j]) || {}; if (s.label && s.label === t.label && !keep.test(s.label)) same.push(s.label); }); });
    if (same.length) await bug({ ...here, sev: 'minor', type: 'menutr', sig: 'menutr|' + dev + '|' + L, title: `Меню на ${LANG_SHORT[L]} (${devName}) частично не переведено`, steps: [`Открыть ${BASE}/${L}/`], expected: `Все пункты меню на ${LANG_SHORT[L]}`, actual: 'На английском: ' + [...new Set(same)].join(', ') }, page, await page.$(dev === 'phone' ? '#ssg-bar' : '#ssg .g-nav'));
  }
}

async function checkLangOptions(page, f, url) {
  const here = { page: url, device: 'компьютер' }; const missing = [];
  for (const o of f.menu.langs) {
    const L = Object.keys(HREFLANG).find(k => HREFLANG[k] === o.k) || o.k;
    const alt = f.hreflang[o.k];
    if (alt && rel(o.href) !== rel(alt)) await bug({ ...here, sev: 'major', type: 'langsw', sig: 'langsw|' + rel(url) + '|' + o.k, title: `Переключатель языка: «${o.label}» ведёт не на ту страницу`, steps: [`Открыть ${BASE}${rel(url)}`, 'Нажать на язык вверху справа', `Выбрать «${o.label}»`], expected: `Эта же страница на языке ${LANG_SHORT[L] || o.k}: ${rel(alt)}`, actual: `Открывается ${rel(o.href)}` });
    if (!alt && normPath(o.href) === '/' && normPath(url) !== '/') missing.push(LANG_SHORT[L] || o.k);
  }
  if (missing.length) await bug({ ...here, sev: 'minor', type: 'langmiss', sig: 'langmiss|' + normPath(url), title: `Страница ${normPath(url)} не переведена — переключатель языка выкидывает на главную`, steps: [`Открыть ${BASE}${rel(url)}`, 'Нажать на язык', 'Выбрать другой язык'], expected: 'Эта же страница на выбранном языке', actual: `Нет перевода на: ${missing.join(', ')} — открывается главная` });
}

/* ---------------------------------------------------------------- sound */
async function audibleNow(page) {
  const a = await page.evaluate(() => window.__qaAudible()).catch(() => []);
  await page.waitForTimeout(900);
  const b = await page.evaluate(() => window.__qaAudible()).catch(() => []);
  return b.filter(x => a.some(y => y.src === x.src && y.t < x.t)).map(x => x.src);
}
async function waitAudible(page, ms) { const t = Date.now(); let s = []; while (Date.now() - t < ms) { s = await audibleNow(page); if (s.length) break; } return s; }
const btnState = (h) => h.evaluate(e => [e.getAttribute('aria-label') || '', typeof e.className === 'string' ? e.className.replace(/\s+/g, ' ') : '', e.getAttribute('aria-pressed') || '', ((e.querySelector('svg path') || {}).getAttribute ? e.querySelector('svg path').getAttribute('d') || '' : '').slice(0, 40), (e.innerText || '').slice(0, 20)].join('|')).catch(() => 'gone');
const btnLabel = (h) => h.evaluate(e => (e.getAttribute('aria-label') || e.title || e.innerText || e.className || '').toString().replace(/\s+/g, ' ').trim().slice(0, 60)).catch(() => '?');

async function testSound(page, url, dev, max) {
  const devName = DEVICES[dev].name; const here = { page: url, device: devName };
  const sel = 'button[aria-label^="Play" i]:visible, [role="button"][aria-label^="Play" i]:visible, .ssh-play:visible, .ss-abb:visible, .ssw button:visible';
  let handles = await page.$$(sel);
  // the bottom player bar is tested separately (it shows the last track, not a page button)
  const keep = []; for (const h of handles) { if (!(await h.evaluate(e => !!e.closest('#ssp') || /video|youtube|queue/i.test((e.getAttribute('aria-label') || '') + ' ' + e.className)).catch(() => true))) keep.push(h); }
  handles = keep.slice(0, max);
  if (!handles.length) return;
  await page.evaluate(() => window.__qaStopAll()).catch(() => {});
  let prev = null;
  for (let i = 0; i < handles.length; i++) {
    if (timeLeft() < 0) break;
    const h = handles[i]; const label = await btnLabel(h);
    stats.sounds++;
    await page.evaluate(() => window.__qaStopAll()).catch(() => {}); await page.waitForTimeout(400);
    await h.evaluate(e => e.scrollIntoView({ block: 'center' })).catch(() => {});
    const s0 = await btnState(h);
    const quiet = await audibleNow(page);
    if (quiet.length) await bug({ ...here, sev: 'critical', type: 'sound-self', sig: 'sndself|' + rel(url), title: 'Звук играет сам, когда ничего не нажато', steps: [`Открыть ${BASE}${rel(url)}`, 'Остановить всё и подождать'], expected: 'Тишина', actual: 'Играет: ' + quiet.join(', ') }, page);
    try { await h.click({ timeout: 5000 }); } catch (e) { await bug({ ...here, sev: 'major', type: 'sound-click', sig: 'sndclick|' + rel(url) + '|' + label, title: `Кнопку ▶ «${label}» невозможно нажать (её что-то закрывает)`, steps: [`Открыть ${BASE}${rel(url)}`, `Нажать ▶ «${label}»`], expected: 'Кнопка нажимается', actual: e.message.split('\n')[0].slice(0, 150) }, page, h); continue; }
    const playing = await waitAudible(page, 12000);
    const s1 = await btnState(h);
    const loading = !playing.length && (await page.evaluate(() => window.__qaAudible().length).catch(() => 0)) > 0;
    if (loading) { await bug({ ...here, sev: 'check', type: 'sound-slow', sig: 'sndslow|' + rel(url), title: 'Трек долго не начинает играть (больше 12 секунд)', steps: [`Открыть ${BASE}${rel(url)}`, `Нажать ▶ «${label}»`], expected: 'Звук через 1–3 секунды', actual: 'Плеер запущен, но звук не идёт 12 секунд (медленная загрузка файла)' }, page, h); await page.evaluate(() => window.__qaStopAll()).catch(() => {}); continue; }
    if (!playing.length) { await bug({ ...here, sev: 'major', type: 'sound-none', sig: 'sndnone|' + rel(url) + '|' + label, title: `Кнопка ▶ «${label}» не запускает звук`, steps: [`Открыть ${BASE}${rel(url)}`, `Нажать ▶ «${label}»`, 'Подождать 12 секунд'], expected: 'Играет трек', actual: s1 !== s0 ? 'Кнопка показывает «играет», но звука нет' : 'Ничего не происходит' }, page, h); continue; }
    if (playing.length > 1) await bug({ ...here, sev: 'critical', type: 'sound-many', sig: 'sndmany|' + rel(url) + '|' + label, title: 'Играют сразу несколько треков', steps: [`Открыть ${BASE}${rel(url)}`, `Нажать ▶ «${label}»`], expected: 'Играет один трек', actual: 'Одновременно: ' + playing.join(' + ') }, page, h);
    /* ears: the button must play ITS track (not the neighbour's or the last one) */
    const exp = await h.evaluate(e => { let c = e; for (let k = 0; k < 8 && c; k++, c = c.parentElement) { const d = c.getAttribute && c.getAttribute('data-src'); if (d && /\.(mp3|wav|ogg|m4a)/i.test(d)) return d; const a = c.querySelector && c.querySelector('audio[src],source[src]'); if (a) return a.getAttribute('src'); } return ''; }).catch(() => '');
    const fn = (x) => decodeURIComponent(String(x).split('?')[0].split('/').pop()).toLowerCase();
    if (exp && playing.length === 1 && fn(playing[0]) !== fn(exp)) await bug({ ...here, sev: 'major', type: 'sound-wrong', sig: 'sndwrong|' + rel(url) + '|' + label, title: `Кнопка ▶ «${label}» включает другой трек`, steps: [`Открыть ${BASE}${rel(url)}`, `Нажать ▶ «${label}»`], expected: `Играет «${fn(exp)}»`, actual: `Играет «${fn(playing[0])}»` }, page, h);
    /* the icon may switch a moment after the sound starts: look again after 1 s; say which button it is (17.11) */
    const s1b = s1 === s0 ? (await page.waitForTimeout(1000), await btnState(h)) : s1;
    const who = await h.evaluate(e => e.tagName.toLowerCase() + '.' + String(e.className || '').trim().split(/\s+/).slice(0, 2).join('.') + ' в ' + String((e.closest('[class]') && e.closest('[class]').parentElement || {}).className || '').trim().split(/\s+/)[0]).catch(() => '');
    if (s1b === s0) await bug({ ...here, sev: 'major', type: 'sound-icon', sig: 'sndicon|' + label.replace(/[^a-z]+/gi, '').slice(0, 20) + '|' + rel(url), title: `Кнопка «${label}» не превращается в ⏸ (паузу), когда трек играет`, steps: [`Открыть ${BASE}${rel(url)}`, `Нажать ▶ «${label}»`], expected: 'Значок меняется на ⏸', actual: 'Трек играет, а на кнопке всё ещё ▶' + (who ? ' (кнопка: ' + who + ')' : '') }, page, h);
    // pause with the same button
    let paused = false;
    try { await h.click({ timeout: 5000 }); paused = true; } catch (e) {}
    await page.waitForTimeout(1200);
    const after = await audibleNow(page); const s2 = await btnState(h);
    if (paused && after.length) await bug({ ...here, sev: 'critical', type: 'sound-pause', sig: 'sndpause|' + rel(url) + '|' + label, title: `После нажатия паузы на «${label}» звук продолжает играть`, steps: [`Открыть ${BASE}${rel(url)}`, `Нажать ▶ «${label}»`, 'Нажать её же ещё раз (пауза)'], expected: 'Звук останавливается', actual: 'Продолжает играть: ' + after.join(', ') }, page, h);
    if (paused && !after.length && s1 !== s0 && s2 === s1) await bug({ ...here, sev: 'major', type: 'sound-icon2', sig: 'sndicon2|' + rel(url) + '|' + label, title: `После паузы кнопка «${label}» остаётся в виде ⏸`, steps: [`Нажать ▶ «${label}»`, 'Нажать ещё раз'], expected: 'Значок возвращается в ▶', actual: 'Звука нет, а значок ⏸' }, page, h);
    if (!paused) await bug({ ...here, sev: 'major', type: 'sound-nopause', sig: 'sndnopause|' + rel(url) + '|' + label, title: `Играющий трек «${label}» нельзя поставить на паузу той же кнопкой`, steps: [`Нажать ▶ «${label}»`, 'Нажать её ещё раз'], expected: 'Пауза', actual: 'Кнопку не нажать (пропала или закрыта)' }, page);
    // switching: start this one, then the next one -> only the next one plays
    if (prev && !page.isClosed()) {
      await page.evaluate(() => window.__qaStopAll()).catch(() => {});
      try {
        await prev.h.click({ timeout: 4000 }); await waitAudible(page, 6000);
        await h.click({ timeout: 4000 }); await page.waitForTimeout(1500);
        const both = await audibleNow(page);
        if (both.length > 1) await bug({ ...here, sev: 'critical', type: 'sound-switch', sig: 'sndswitch|' + rel(url), title: 'При запуске другого трека предыдущий не останавливается', steps: [`Открыть ${BASE}${rel(url)}`, `Нажать ▶ «${prev.label}»`, `Нажать ▶ «${label}»`], expected: 'Играет только второй трек', actual: 'Играют оба: ' + both.join(' + ') }, page, h);
        const sp = await btnState(prev.h);
        if (both.length === 1 && sp !== prev.s0 && sp !== 'gone') await bug({ ...here, sev: 'major', type: 'sound-stale', sig: 'sndstale|' + rel(url), title: 'После запуска другого трека прежняя кнопка всё ещё показывает ⏸', steps: [`Нажать ▶ «${prev.label}»`, `Нажать ▶ «${label}»`], expected: `«${prev.label}» снова показывает ▶`, actual: 'Показывает ⏸, хотя этот трек не играет' }, page, prev.h);
      } catch (e) {}
      await page.evaluate(() => window.__qaStopAll()).catch(() => {});
    }
    prev = { h, label, s0 };
  }
  // bottom player bar: play / pause
  const barBtn = await page.$('#ssp .ssp-play:visible');
  if (barBtn) {
    await page.evaluate(() => window.__qaStopAll()).catch(() => {}); await page.waitForTimeout(500);
    const st0 = await btnState(barBtn);
    try {
      await barBtn.click({ timeout: 4000 });
      const pl = await waitAudible(page, 7000);
      const st1 = await btnState(barBtn);
      if (pl.length && st1 === st0) await bug({ ...here, sev: 'major', type: 'bar-icon', sig: 'baricon', title: 'Нижний плеер: кнопка не меняется на ⏸ во время игры', steps: ['Нажать ▶ в нижнем плеере'], expected: 'Значок ⏸', actual: 'Остаётся ▶' }, page, barBtn);
      if (!pl.length && st1 !== st0) await bug({ ...here, sev: 'major', type: 'bar-silent', sig: 'barsilent', title: 'Нижний плеер показывает «играет», но звука нет', steps: ['Нажать ▶ в нижнем плеере'], expected: 'Играет трек', actual: 'Тишина' }, page, barBtn);
      if (pl.length) { await barBtn.click({ timeout: 4000 }); await page.waitForTimeout(1200); const a2 = await audibleNow(page); if (a2.length) await bug({ ...here, sev: 'critical', type: 'bar-pause', sig: 'barpause', title: 'Нижний плеер: после паузы звук продолжает играть', steps: ['Нажать ▶ в нижнем плеере', 'Нажать ⏸'], expected: 'Тишина', actual: 'Играет: ' + a2.join(', ') }, page, barBtn); }
    } catch (e) {}
    await page.evaluate(() => window.__qaStopAll()).catch(() => {});
  }
}

/* ---------------------------------------------------------------- "hands": press every button */
async function pressEverything(page, url, dev, max) {
  const devName = DEVICES[dev].name; const here = { page: url, device: devName };
  const tag = () => page.evaluate(({ skip, danger, max }) => {
    const re = new RegExp(danger, 'i'); let i = 0;
    const vis = (e) => { const r = e.getBoundingClientRect(); const s = getComputedStyle(e); return r.width > 4 && r.height > 4 && s.visibility !== 'hidden' && s.display !== 'none' && +s.opacity > 0.05; };
    document.querySelectorAll('[data-qa-c]').forEach(e => e.removeAttribute('data-qa-c'));
    document.querySelectorAll('button, [role="button"], summary, a[href^="#"], a[href^="javascript"], .w-tabs-item, .w-toggle-header, [onclick]').forEach(e => {
      if (i >= max || !vis(e) || e.closest(skip) || e.matches(skip) || e.closest('form') || e.disabled) return;
      const t = (e.getAttribute('aria-label') || e.innerText || e.title || '').trim();
      if (re.test(t) || re.test(e.className || '')) return;
      e.setAttribute('data-qa-c', String(i++));
    });
    return i;
  }, { skip: SKIP_CLICK_SEL, danger: DANGER.source, max }).catch(() => 0);
  const n = await tag();
  const startUrl = page.url();
  for (let i = 0; i < n; i++) {
    if (timeLeft() < 0) break;
    const h = await page.$(`[data-qa-c="${i}"]`); if (!h) continue;
    if (!(await h.isVisible().catch(() => false))) continue;
    // close popups the previous click opened (video window, dialogs) — otherwise every next button looks "covered" (17.11)
    await page.evaluate(() => { document.querySelectorAll('#ss-ytm.open,.ss-pop,#ss-acc').forEach(m => { const x = m.querySelector('.ss-ytm-x'); if (x) x.click(); else m.remove(); }); document.documentElement.style.overflow = ''; }).catch(() => {});
    await page.keyboard.press('Escape').catch(() => {});
    const label = (await btnLabel(h)) || `кнопка №${i + 1}`;
    stats.clicks++;
    const snap = () => h.evaluate(e => { const p = e.parentElement || e; const t = document.body.innerText || ''; let x = 0; for (let i = 0; i < t.length; i += 7) x = (x * 31 + t.charCodeAt(i)) | 0; return (p.outerHTML || '').slice(0, 4000) + '|' + x + '|' + document.querySelectorAll('[role="dialog"]:not([hidden]), .w-popup-wrap.active, .is-open, .open, [aria-expanded="true"]').length
      /* carousels: horizontal scroll and slide transforms count as a change (Previous / Next) */
      + '|' + [...document.querySelectorAll('*')].filter(el => el.scrollWidth > el.clientWidth + 5 && el.clientWidth > 50).slice(0, 60).map(el => Math.round(el.scrollLeft)).join(',')
      + '|' + [...document.querySelectorAll('[style*="transform"]')].slice(0, 60).map(el => el.style.transform).join(','); }).catch(() => 'gone');
    const before = await snap();
    const e0 = page.qaErrs.length; const y0 = await page.evaluate(() => scrollY).catch(() => 0);
    let clicked = true;
    try { await h.click({ timeout: 3000 }); } catch (e) { clicked = false; }
    if (!clicked) {
      const cov = await h.evaluate(e => { const r = e.getBoundingClientRect(); const t = document.elementFromPoint(r.left + r.width / 2, r.top + r.height / 2); return t && !e.contains(t) && !t.contains(e) ? (t.tagName.toLowerCase() + (t.id ? '#' + t.id : '') + (typeof t.className === 'string' && t.className ? '.' + t.className.trim().split(/\s+/)[0] : '')) : ''; }).catch(() => '');
      if (cov) await bug({ ...here, sev: 'major', type: 'covered', sig: 'covered|' + rel(url) + '|' + label, title: `«${label}» нельзя нажать — сверху лежит другой элемент`, steps: [`Открыть ${BASE}${rel(url)}`, `Нажать «${label}»`], expected: 'Кнопка нажимается', actual: `Клик попадает в ${cov}` }, page, h);
      continue;
    }
    await page.waitForNavigation({ timeout: 2500 }).catch(() => {});
    await page.waitForTimeout(500);
    const now = page.url();
    const y1 = await page.evaluate(() => scrollY).catch(() => 0);
    const after = now === startUrl ? await snap() : 'nav';
    const newErr = page.qaErrs.slice(e0);
    if (newErr.length) await bug({ ...here, sev: 'major', type: 'clickerr', sig: 'clickerr|' + errKey(newErr[0]), title: `После нажатия «${label}» — ошибка в коде`, steps: [`Открыть ${BASE}${rel(url)}`, `Нажать «${label}»`], expected: 'Без ошибок', actual: newErr[0] }, page, h);
    const changed = now !== startUrl || Math.abs(y1 - y0) > 5 || after !== before;
    /* a tab that is already selected (All / For you) correctly does nothing — not a bug */
    const already = await h.evaluate(e => /(^|\s)(on|active|cur|is-active|selected|current)(\s|$)/.test(String(e.className || '')) || e.getAttribute('aria-selected') === 'true' || e.getAttribute('aria-pressed') === 'true' || e.getAttribute('aria-current') === 'true' || e.disabled || e.getAttribute('aria-disabled') === 'true').catch(() => false);
    if (!changed && !already && !newErr.length) await bug({ ...here, sev: 'check', type: 'dead', sig: 'dead|' + rel(url) + '|' + label, title: `Кнопка «${label}» ничего не делает`, steps: [`Открыть ${BASE}${rel(url)}`, `Нажать «${label}»`], expected: 'Что-то открывается/меняется', actual: 'Ничего не происходит' }, page, h);
    await page.keyboard.press('Escape').catch(() => {});
    if (now.split('#')[0] !== startUrl.split('#')[0]) { await open(page, startUrl); await tag(); }
  }
}

/* ---------------------------------------------------------------- scenarios */
async function scLanguageSwitch(page, dev) {
  // what the owner does: home -> language button -> Українська (and every other language)
  const devName = DEVICES[dev].name;
  for (const L of LANGS.filter(x => x !== 'en' && (!SMOKE || x === 'ua'))) {
    if (timeLeft() < 0) return;
    await open(page, '/en/');
    const enFp = (await facts(page)).fp;
    const btn = dev === 'phone' ? page.locator('#ssg-bar .g-sheet-open:visible, #ssg .g-burger:visible, button[aria-label="Main menu"]:visible').first() : page.locator('#ssg .g-lang:visible, button[aria-label^="Language"]:visible').first();
    try { await btn.click({ timeout: 5000 }); } catch (e) { await bug({ page: '/en/', device: devName, sev: 'major', type: 'langbtn', sig: 'langbtn|' + dev, title: `Не нажимается кнопка выбора языка (${devName})`, steps: ['Открыть главную', dev === 'phone' ? 'Нажать «Menu»' : 'Нажать «EN» вверху справа'], expected: 'Открывается список языков', actual: e.message.split('\n')[0].slice(0, 120) }, page); return; }
    await page.waitForTimeout(800);
    const opt = page.locator(`a[hreflang="${HREFLANG[L]}"]:visible, a[href="${BASE}/${L}/"]:visible, a[href="/${L}/"]:visible`).first();
    if (!(await opt.count())) {
      const none = !(await page.locator('a[hreflang]:visible, a[href$="/ua/"]:visible, a[href$="/de/"]:visible').count());
      await bug({ page: '/en/', device: devName, sev: 'major', type: 'langopt', sig: 'langopt|' + dev + (none ? '' : '|' + L), title: none ? `На главной (${devName}) нельзя выбрать язык — в меню нет списка языков` : `В списке языков нет ${LANG_SHORT[L]} (${devName})`, steps: ['Открыть главную', dev === 'phone' ? 'Нажать кнопку меню' : 'Нажать кнопку языка'], expected: 'Список языков: EN, UA, DE, FR, PT, ES, IT', actual: none ? 'Списка языков нет' : `Нет ${LANG_SHORT[L]}` }, page);
      if (none) return; continue;
    }
    const label = (await opt.innerText().catch(() => L)).replace(/\s+/g, ' ').trim();
    await Promise.all([page.waitForNavigation({ timeout: 90000 }).catch(() => {}), opt.click({ timeout: 5000 }).catch(() => {})]);
    await page.waitForTimeout(3000);
    const landed = page.url(); const lf = await facts(page).catch(() => null);
    const steps = ['Открыть главную на английском', dev === 'phone' ? 'Нажать «Menu» внизу' : 'Нажать «EN» вверху справа', `Выбрать «${label}»`];
    if (normPath(landed) !== '/' || urlLang(landed) !== L) await bug({ page: '/en/', device: devName, sev: 'critical', type: 'langhome', sig: 'langhome|' + L + '|' + dev, title: `Выбор языка ${LANG_SHORT[L]} на главной открывает не ту страницу`, steps, expected: `Главная на ${LANG_SHORT[L]}: ${BASE}/${L}/`, actual: `Открылось ${landed}` }, page);
    else if (lf) {
      const sim = jaccard(enFp, lf.fp);
      if (sim < 0.45) { const g = await bug({ page: `/${L}/`, device: devName, sev: 'critical', type: 'langlook', sig: 'langlook|/', title: langLookTitle('/'), steps, expected: 'Та же главная, что на английском, только переведённая', actual: '' }, page); if (!g.actual.includes(LANG_SHORT[L] + ':')) g.actual = (g.actual ? g.actual + '; ' : '') + `${LANG_SHORT[L]}: совпадает с английской на ${Math.round(sim * 100)}%`; }
      if (lf.lang && !lf.lang.toLowerCase().startsWith(HREFLANG[L])) await bug({ page: `/${L}/`, device: devName, sev: 'major', type: 'htmllang', sig: 'htmllang|/' + L + '/', title: `Главная ${LANG_SHORT[L]} помечена как язык «${lf.lang}»`, steps, expected: HREFLANG[L], actual: lf.lang });
    }
  }
}

async function scPlayerAcrossPages(page) {
  const here = { page: '/en/all-products/', device: 'компьютер' };
  await open(page, '/en/all-products/');
  const play = await page.$('.ssw button:visible, button[aria-label^="Play" i]:visible');
  if (!play) { notChecked.push('Сценарий «музыка при переходах» — на «Все товары» нет кнопки ▶'); return; }
  await play.click({ timeout: 5000 }).catch(() => {});
  if (!(await waitAudible(page, 8000)).length) { notChecked.push('Сценарий «музыка при переходах» — трек не запустился'); return; }
  const doc = await page.evaluate(() => window.__qaDoc);
  const steps = ['Открыть «Все товары»', 'Нажать ▶ у любого трека'];
  for (const [sel, name] of [['#ssg .g-nav a[href*="/wiki"]', 'Wiki в меню'], ['#ssg .g-nav a[href*="/free"]', 'Free в меню'], ['#page-content a[href*="/product/"]:visible', 'товар на странице']]) {
    const a = await page.$(sel); if (!a) continue;
    await a.click({ timeout: 5000 }).catch(() => {}); await page.waitForTimeout(6000);
    steps.push(`Нажать «${name}»`);
    const same = (await page.evaluate(() => window.__qaDoc).catch(() => 0)) === doc;
    const snd = await audibleNow(page);
    if (!same || !snd.length) { await bug({ ...here, sev: 'major', type: 'player-nav', sig: 'playernav|' + name, title: `Музыка прерывается при переходе: «${name}»`, steps, expected: 'Трек играет дальше без перерыва (требование владельца)', actual: !same ? 'Страница перезагружается целиком, трек останавливается' : 'Тишина после перехода' }, page); return; }
  }
}

async function scCart(page) {
  const here = { page: '/en/all-products/', device: 'компьютер' };
  await open(page, '/en/all-products/');
  const cnt = async () => +(((await page.$eval('#ssg .g-cnt', e => e.textContent).catch(() => '0')) || '0').replace(/\D/g, '') || 0);
  const c0 = await cnt();
  const add = await page.$('.add_to_cart_button.ajax_add_to_cart:visible, .add_to_cart_button:visible');
  if (!add) { notChecked.push('Корзина — на «Все товары» нет кнопки «в корзину»'); return; }
  const steps = ['Открыть «Все товары»', 'Нажать значок корзины у товара'];
  await Promise.all([page.waitForResponse(r => /wc-ajax=add_to_cart|add-to-cart/.test(r.url()), { timeout: 30000 }).catch(() => {}), add.click({ timeout: 5000 }).catch(() => {})]);
  await page.waitForLoadState('load').catch(() => {}); await page.waitForTimeout(3000);
  // the shop may open the cart by itself after adding (WooCommerce setting) — that is fine
  if (!/\/cart\//.test(page.url())) {
    const c1 = await cnt();
    if (c1 <= c0) {
      await open(page, '/en/all-products/'); const c2 = await cnt();
      if (c2 > c0) await bug({ ...here, sev: 'minor', type: 'cartcount', sig: 'cartcount', title: 'Счётчик корзины в меню не обновляется сразу после добавления товара', steps, expected: 'Цифра у корзины увеличивается сразу', actual: 'Меняется только после перезагрузки страницы' }, page);
    }
  }
  await open(page, '/en/cart/');
  const rows = await page.$$eval('.cart_item, .woocommerce-cart-form__cart-item, .wc-block-cart-items__row', x => x.length).catch(() => 0);
  if (!rows) { await bug({ page: '/en/cart/', device: 'компьютер', sev: 'critical', type: 'cartempty', sig: 'cartempty', title: 'Корзина пустая после добавления товара', steps: [...steps, 'Открыть корзину'], expected: 'Товар в корзине', actual: 'Корзина пустая' }, page); return; }
  const st = await open(page, '/en/checkout/');
  const form = await page.$('form.checkout, #billing_email, .wc-block-checkout').catch(() => null);
  if (st >= 400 || !form) await bug({ page: '/en/checkout/', device: 'компьютер', sev: 'critical', type: 'checkout', sig: 'checkout', title: 'Страница оформления заказа не открывается или без формы', steps: [...steps, 'Открыть корзину', 'Перейти к оформлению'], expected: 'Форма оформления заказа', actual: st >= 400 ? `Ошибка ${st}` : 'Формы нет' }, page);
  await open(page, '/en/cart/');
  for (let i = 0; i < 5; i++) { const r = await page.$('.cart_item a.remove, a.remove'); if (!r) break; await Promise.all([page.waitForNavigation({ timeout: 60000 }).catch(() => {}), r.click().catch(() => {})]); await page.waitForTimeout(1500); }
  const left = await page.$$eval('.cart_item, .woocommerce-cart-form__cart-item', x => x.length).catch(() => 0);
  if (left) await bug({ page: '/en/cart/', device: 'компьютер', sev: 'major', type: 'cartremove', sig: 'cartremove', title: 'Товар не удаляется из корзины', steps: ['Открыть корзину с товаром', 'Нажать ×'], expected: 'Корзина пустеет', actual: `Осталось позиций: ${left}` }, page);
}

async function scSearch(page) {
  await open(page, '/en/');
  const q = page.locator('#top-q:visible').first();
  if (!(await q.count())) { notChecked.push('Поиск — поле поиска не найдено'); return; }
  await q.fill('techno');
  await Promise.all([page.waitForNavigation({ timeout: 90000 }).catch(() => {}), q.press('Enter')]);
  await page.waitForTimeout(3000);
  const n = await page.$$eval('li.product, .w-grid-item, .products .product, article.product, a.it[href*="/product/"]', x => x.length).catch(() => 0); /* 17.11: new search page uses a.it cards */
  if (!n) await bug({ page: '/en/', device: 'компьютер', sev: 'major', type: 'search', sig: 'search', title: 'Поиск по слову «techno» ничего не находит', steps: ['Открыть главную', 'Ввести «techno» в поиск', 'Нажать Enter'], expected: 'Список товаров', actual: `Открылось ${page.url()}, товаров: 0` }, page);
}

async function login(page, user, pass) {
  await open(page, '/en/my-account/?ss_tab=login');
  const form = page.locator('form.login').filter({ has: page.locator('#username:visible') }).first();
  await form.locator('#username').fill(user); await form.locator('#password').fill(pass);
  await Promise.all([page.waitForNavigation({ timeout: 120000 }).catch(() => {}), form.locator('button[name=login]').click()]);
  await page.waitForTimeout(2000);
  return page.evaluate(() => document.body.classList.contains('logged-in')).catch(() => false);
}

/* ---------------------------------------------------------------- links */
async function checkLinks(ctx, limit) {
  const skip = /\/wp-admin|wp-login|logout|customer-logout|add-to-cart|remove_item|[?&]action=|wc-ajax|\/feed|\.(jpe?g|png|webp|gif|svg|mp3|wav|zip|rar|pdf|als)(\?|$)|\?s=|post_type=product&s=|\/page\/\d+|orderby=|filter_|replytocom/i;
  const seen = new Set([...visited.keys()].map(k => k.split('|')[0]));
  const todo = [...linkSources.keys()].filter(u => !skip.test(u) && !seen.has(rel(u))).slice(0, limit);
  const titles = new Map();
  for (const u of todo) {
    if (timeLeft() < -10 * 60000) { notChecked.push(`Ссылки: проверено ${stats.links} из ${todo.length} — не хватило времени`); break; }
    let r, body = '', st = 0;
    for (let i = 0; i < 4; i++) {
      try { r = await ctx.request.get(u, { timeout: 60000, maxRedirects: 6, headers: { 'User-Agent': UA_DESK } }); st = r.status(); body = await r.text().catch(() => ''); } catch (e) { st = -1; }
      if (st === 429 || isBlockPage(body)) { await sleep(8000 * (i + 1)); continue; }
      break;
    }
    stats.links++;
    if (st === 429 || st === -1 || isBlockPage(body)) { notChecked.push(`Ссылка ${rel(u)} — сервер не ответил (защита от частых запросов)`); continue; }
    await sleep(700);
    const src = [...(linkSources.get(u) || [])];
    if (st >= 400) await bug({ page: src[0], sev: 'major', type: 'link404', sig: 'link|' + rel(u), title: `Битая ссылка: ${rel(u)} (код ${st})`, steps: [`Открыть ${BASE}${src[0]}`, `Нажать ссылку на ${rel(u)}`], expected: 'Страница открывается', actual: `Ошибка ${st}. Ссылка есть на страницах: ${src.slice(0, 5).join(', ')}${src.length > 5 ? ` и ещё ${src.length - 5}` : ''}` });
    else if (st > 0 && r && normPath(r.url()) === '/' && normPath(u) !== '/' && !/[?&](s|post_type)=/.test(r.url()))  /* /?s=… is the search page (genre/tag links on purpose), not the home */ await bug({ page: src[0], sev: 'minor', type: 'link2home', sig: 'link2home|' + rel(u), title: `Ссылка ${rel(u)} выкидывает на главную`, steps: [`Нажать ссылку на ${rel(u)}`], expected: 'Нужная страница', actual: `Перенаправляет на ${rel(r.url())}` });
    if (st > 0 && st < 400) titles.set(rel(u), ((body.match(/<title[^>]*>([^<]*)/i) || [])[1] || '') + ' ' + ((body.match(/<h1[^>]*>([\s\S]*?)<\/h1>/i) || [])[1] || '').replace(/<[^>]+>/g, ' '));
  }
  return titles;
}

/* menu item text vs the page it opens (English menu only — wrong or swapped pages) */
async function menuVsTarget(titles) {
  const ref = refMenu.desktop; if (!ref) return;
  const syn = { home: ['solid', 'sound', 'ableton'], shop: ['products', 'product', 'all'], products: ['products', 'product', 'all', 'shop'], studio: ['services', 'service', 'mixing', 'mastering', 'prices'], services: ['services', 'service', 'mixing', 'mastering', 'prices', 'calculator'], sell: ['vendor', 'sell', 'creator', 'store'], saved: ['account', 'favourites', 'favorites', 'wishlist'], favorites: ['account', 'favourites', 'favorites', 'wishlist'], cart: ['cart', 'basket'], 'sign': ['account', 'login'], free: ['free'], faq: ['faq', 'questions'], questions: ['faq'], common: ['faq'], contact: ['contacts', 'contact'], calculator: ['calculator', 'quote', 'price'] };
  const stem = (w) => w.toLowerCase().replace(/[^a-z]/g, '').slice(0, 5);
  const items = ref.top.flatMap(x => [x, ...x.sub]).filter(x => x.href && x.href.startsWith(BASE) && !x.href.includes('#') && x.label);
  for (const x of items) {
    const k = rel(x.href); const v = visited.get(k + '|desktop');
    const t = ((v ? v.title + ' ' + v.h1 : titles.get(k)) || '') + ' ' + normPath(x.href).replace(/[/-]/g, ' ');
    if (!t.trim()) continue;
    const words = x.label.toLowerCase().split(/[^a-z]+/).filter(w => w.length > 2);
    const tw = new Set(t.toLowerCase().split(/[^a-z]+/).filter(w => w.length > 2).map(stem));
    const ok = words.some(w => tw.has(stem(w)) || (syn[w] || []).some(s => tw.has(stem(s))));
    if (!ok && words.length) await bug({ page: '/en/', sev: 'check', type: 'menutarget', sig: 'menutarget|' + x.label, title: `Пункт меню «${x.label}» открывает страницу «${t.trim().slice(0, 70)}» — та ли это страница?`, steps: ['Открыть главную', `Нажать в меню «${x.label}»`], expected: `Страница про «${x.label}»`, actual: `Открывается ${k}` });
  }
}

/* ---------------------------------------------------------------- pages to visit */
async function sitemapPages(ctx) {
  const out = [];
  const get = async (u) => { for (let i = 0; i < 4; i++) { try { const r = await ctx.request.get(u, { timeout: 60000, headers: { 'User-Agent': UA_DESK } }); const t = await r.text(); if (r.status() === 200 && !isBlockPage(t)) return t; } catch (e) {} await sleep(6000 * (i + 1)); } return ''; };
  const idx = await get(BASE + '/wp-sitemap.xml');
  for (const sm of (idx.match(/<loc>[^<]+/g) || []).map(s => s.slice(5))) { const x = await get(sm); (x.match(/<loc>[^<]+/g) || []).forEach(s => out.push(rel(s.slice(5)))); await sleep(1500); }
  return [...new Set(out)];
}

/* ---------------------------------------------------------------- report */
function writeReport(final = true) {
  // (13.11.2026 owner: "не затирать") the memory of the last run belongs to qa/latest only; a run into another folder
  // (QA_OUT=qa/kb-scan etc.) keeps its own memory next to it and never touches the main one
  const mainOut = path.resolve(OUT) === path.resolve('qa/latest');
  const prevFile = mainOut ? path.join(OUT, '..', 'previous-bugs.json') : path.join(OUT, 'previous-bugs.json');
  let prev = null; try { prev = JSON.parse(fs.readFileSync(prevFile, 'utf8')); } catch (e) {}
  const list = [...bugs.values()].sort((a, b) => SEV_ORDER.indexOf(a.sev) - SEV_ORDER.indexOf(b.sev) || b.where.length - a.where.length);
  const prevSigs = prev && prev.mode === MODE ? new Set(prev.bugs.map(b => b.sig)) : null;
  list.forEach(b => { b.isNew = prevSigs ? !prevSigs.has(b.sig) : false; });
  const fixed = prevSigs ? prev.bugs.filter(b => !bugs.has(b.sig)) : [];
  const cnt = (s) => list.filter(b => b.sev === s).length;
  const mins = Math.round((Date.now() - T0) / 60000);
  const L = [];
  L.push(`# Автотестировщик — отчёт ${new Date().toISOString().slice(0, 16).replace('T', ' ')} UTC (${FULL ? 'полная проверка' : SMOKE ? 'короткая проверка после выкладки' : 'ежедневная проверка'})${final ? '' : ' — ⏳ проверка ещё идёт, отчёт неполный'}`, '');
  L.push(`Проверено: страниц ${stats.pages}, кнопок ${stats.clicks}, кнопок звука ${stats.sounds}, файлов звука ${stats.audioFiles || 0}, ссылок ${stats.links}${stats.eyesTiles ? `, нейросеть-глаза: страниц ${stats.eyesPages || 0}, кусков ${stats.eyesTiles}` : ''}${stats.robots ? `, роботов ${stats.robots}` : ''}. Время: ${mins} мин.`);
  if (l10nSkipped) L.push(`Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: ${l10nSkipped}.`);
  L.push(`Найдено: ${SEV.critical} ${cnt('critical')} · ${SEV.major} ${cnt('major')} · ${SEV.minor} ${cnt('minor')} · ${SEV.check} ${cnt('check')}` + (prevSigs ? ` · новых ${list.filter(b => b.isNew).length} · исправлено с прошлого раза ${fixed.length}` : ''), '');
  L.push('Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.', '');
  let n = 0;
  for (const s of SEV_ORDER) {
    const g = list.filter(b => b.sev === s); if (!g.length) continue;
    L.push(`## ${SEV[s]} (${g.length})`, '');
    for (const b of g) {
      n++;
      L.push(`### ${n}. ${b.title}${b.isNew ? ' 🆕' : ''}`);
      L.push(`- **Где:** ${b.where.slice(0, 6).map(w => w.startsWith('/') ? BASE + w : w).join(', ') || '—'}${b.where.length > 6 ? ` и ещё ${b.where.length - 6}` : ''}${b.devices.length ? ` (${b.devices.join(', ')})` : ''}`);
      if (b.steps.length) L.push(`- **Шаги:** ${b.steps.map((x, i) => `${i + 1}) ${x}`).join(' ')}`);
      L.push(`- **Ожидалось:** ${b.expected}`, `- **На деле:** ${b.actual}`);
      if (b.shot) L.push(`- **Скриншот:** [${b.shot}](${b.shot})`);
      L.push('');
    }
  }
  if (!list.length) L.push('Ошибок не найдено. ✅', '');
  if (fixed.length) { L.push(`## ✅ Исправлено с прошлой проверки (${fixed.length})`, ''); fixed.slice(0, 40).forEach(b => L.push(`- ${b.title}`)); L.push(''); }
  if (notChecked.length) { L.push(`## Не удалось проверить (${notChecked.length})`, ''); [...new Set(notChecked)].slice(0, 60).forEach(x => L.push(`- ${x}`)); L.push(''); }
  fs.writeFileSync(path.join(OUT, 'report.md'), L.join('\n') + '\n');
  const json = { mode: MODE, date: new Date().toISOString(), stats, bugs: list, notChecked, l10nSkipped };
  fs.writeFileSync(path.join(OUT, 'bugs.json'), JSON.stringify(json, null, 1));
  if (final && process.env.QA_NOHIST !== '1') fs.writeFileSync(prevFile, JSON.stringify(json));
  // every report is also kept forever in qa/history/<date time mode>/ (report + bug list; screenshots stay only in the latest)
  if (final && process.env.QA_NOHIST !== '1') try {
    const H = path.join(path.dirname(path.resolve(OUT)), 'history', json.date.slice(0, 16).replace(/[T:]/g, '-') + '-' + MODE + (mainOut ? '' : '-' + path.basename(OUT)));
    fs.mkdirSync(H, { recursive: true });
    fs.writeFileSync(path.join(H, 'report.md'), (L.join('\n') + '\n').replace(/\]\(shots\//g, '](../../' + path.basename(OUT) + '/shots/'));
    fs.writeFileSync(path.join(H, 'bugs.json'), JSON.stringify(json, null, 1));
  } catch (e) { console.error('history', e.message); }
  console.log(L.slice(0, 4).join('\n'));
}

/* ---------------------------------------------------------------- eyes + fit */
// full-page pictures of the key pages for "Claude's eyes" (weekly review): qa/latest/eyes/<device>_<page>.jpg
async function eyesShot(page, url, dev) {
  try {
    const h = await page.evaluate(() => Math.min(document.documentElement.scrollHeight, 6000));
    const w = DEVICES[dev].viewport.width;
    const f = path.join(OUT, 'eyes', `${dev}_${rel(url).replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home'}.jpg`);
    await page.screenshot({ path: f, type: 'jpeg', quality: 45, fullPage: true, clip: { x: 0, y: 0, width: w, height: h }, timeout: 30000 });
  } catch (e) {}
}
// home on a phone: the whole "What are you working on?" block must be above the bottom bar for every card
async function fitCheck(page, dev) {
  const res = await page.evaluate(async () => {
    const r = document.querySelector('#ss-app .ssx'); /* (21.11) the visible bottom bar: the shared #ssg-bar once the home's own nav is hidden (body.ss-one) */
    const nav = [document.querySelector('#ssg-bar'), document.querySelector('#ss-app nav[aria-label="App"]')].find(n => n && n.getBoundingClientRect().height > 0);
    if (!r || !nav) return null; const out = [];
    const dots = r.querySelectorAll('.dots i');
    for (let i = 0; i < dots.length; i++) { dots[i].click(); await new Promise(z => setTimeout(z, 700)); out.push(Math.round(nav.getBoundingClientRect().top - r.getBoundingClientRect().bottom)); }
    return out;
  }).catch(() => null);
  if (!res || !res.length) return;
  const bad = res.map((v, i) => [i + 1, v]).filter(([, v]) => v < 0);
  if (bad.length) await bug({ page: '/en/', device: DEVICES[dev].name, sev: 'major', type: 'fit', sig: 'fit|' + dev, title: `Слайдер на главной не помещается на экране (${DEVICES[dev].name})`, steps: ['Открыть главную на телефоне', 'Пролистать карточки слайдера точками'], expected: 'Весь блок «What are you working on?» виден над нижней панелью', actual: 'Уходит под нижнюю панель: ' + bad.map(([i, v]) => `карточка ${i} на ${-v}px`).join(', ') }, page);
}

/* ---------------------------------------------------------------- swarm: merge the robots' reports */
function mergeReports(dirs) {
  fs.rmSync(path.join(OUT, 'shots'), { recursive: true, force: true }); fs.mkdirSync(path.join(OUT, 'shots'), { recursive: true });
  fs.mkdirSync(path.join(OUT, 'eyes'), { recursive: true });
  let k = 0, copied = 0;
  const KEYEYE = /^(a31_|[a-z0-9]+_(en|home|en_all_products|product_infinity|en_wiki|en_free|en_services_prices|en_faq|en_sell|forum)\.jpg$)/;
  for (const d of dirs) {
    let j; try { j = JSON.parse(fs.readFileSync(path.join(d, 'bugs.json'), 'utf8')); } catch (e) { notChecked.push(`Робот ${path.basename(d)} не прислал отчёт`); continue; }
    k++;
    for (const [n, v] of Object.entries(j.stats || {})) stats[n] = (stats[n] || 0) + (+v || 0);
    (j.notChecked || []).forEach(x => notChecked.push(x));
    l10nSkipped += +(j.l10nSkipped || 0);
    const copy = (rel0) => { if (!rel0 || ++copied > 300) return ''; const src = path.join(d, rel0); if (!fs.existsSync(src)) return ''; const dst = 'shots/' + k + '_' + path.basename(rel0); try { fs.copyFileSync(src, path.join(OUT, dst)); return dst; } catch (e) { return ''; } };
    for (const b of j.bugs || []) {
      const g = bugs.get(b.sig);
      if (!g) { bugs.set(b.sig, { ...b, where: [...(b.where || [])], devices: [...(b.devices || [])], shot: copy(b.shot) }); continue; }
      (b.where || []).forEach(w => !g.where.includes(w) && g.where.push(w));
      (b.devices || []).forEach(w => !g.devices.includes(w) && g.devices.push(w));
      if (!g.shot) g.shot = copy(b.shot);
    }
    try { for (const f of fs.readdirSync(path.join(d, 'eyes')).filter(f => KEYEYE.test(f))) fs.copyFileSync(path.join(d, 'eyes', f), path.join(OUT, 'eyes', f)); } catch (e) {}
  }
  stats.robots = k;
  writeReport(true);
}

/* ---------------------------------------------------------------- main */
(async () => {
  if (process.env.QA_MERGE) return mergeReports(process.env.QA_MERGE.split(',').filter(Boolean));
  fs.rmSync(path.join(OUT, 'shots'), { recursive: true, force: true });
  fs.mkdirSync(path.join(OUT, 'shots'), { recursive: true });
  fs.rmSync(path.join(OUT, 'eyes'), { recursive: true, force: true }); fs.mkdirSync(path.join(OUT, 'eyes'), { recursive: true });
  const browser = await chromium.launch({ ...(PROXY ? { proxy: { server: PROXY } } : {}), ...(process.env.PW_CHANNEL ? { channel: process.env.PW_CHANNEL } : {}), args: ['--autoplay-policy=no-user-gesture-required'] });
  const save = () => { try { writeReport(); } catch (e) { console.error('report', e); } };
  process.on('SIGTERM', () => { save(); process.exit(0); });
  // a stray error inside a page handler must not kill the run silently: note it and go on
  process.on('unhandledRejection', (e) => { notChecked.push('Внутренняя ошибка бота: ' + String((e && e.message) || e).split('\n')[0].slice(0, 160)); });
  process.on('uncaughtException', (e) => { notChecked.push('Внутренняя ошибка бота: ' + String((e && e.message) || e).split('\n')[0].slice(0, 160)); });
  // partial report every 2 minutes, so a crash or a time-out never leaves an old report behind
  const draft = setInterval(() => { try { writeReport(false); } catch (e) {} }, 120000);
  // hard stop: whatever is still running, finish 12 min after the budget with a complete report (GitHub kills the job at 160 min)
  setTimeout(() => { notChecked.push('Проверка остановлена по времени — часть ссылок/входов не проверена'); try { writeReport(true); } catch (e) {} process.exit(0); }, BUDGET_MS + 12 * 60000).unref();
  try {
    const desk = await newContext(browser, 'desktop'); const dp = await newPage(desk);
    const phone = await newContext(browser, 'phone'); const pp = await newPage(phone);

    let pages;
    const KEY = ['/en/', '/en/all-products/', '/product/infinity/', '/en/wiki/', '/en/free/', '/en/services-prices/', '/en/faq/', '/en/sell/'];
    if (process.env.QA_PAGES) pages = process.env.QA_PAGES.split(',');
    else if (FULL) { pages = await sitemapPages(desk); if (!pages.length) { pages = KEY; notChecked.push('Карта сайта не загрузилась — проверены только основные страницы'); } }
    else pages = SMOKE ? ['/en/', '/en/all-products/', '/product/infinity/'] : KEY;
    const en = pages.filter(p => !urlLang(p) || urlLang(p) === 'en').filter((p, i) => p === '/en/' ? !SHARD || +SHARD[1] === 0 : inShard(p, i));
    const other = pages.filter(p => urlLang(p) && urlLang(p) !== 'en');
    // home first in every language (reference menu), English pages first
    const homeAll = LANGS.map(l => `/${l}/`);

    // 1) English + product pages: computer and phone, with sound and "hands"
    for (const p of part('pages') ? [...(en.includes('/en/') || !SHARD ? ['/en/'] : []), ...en.filter(x => x !== '/en/')] : []) {
      const deep = FULL || KEY.includes(p) || process.env.QA_PAGES;
      const eye = KEY.includes(p) || process.env.QA_PAGES || process.env.QA_EYES_ALL === '1';
      if (await checkPage(dp, p, 'desktop')) { if (eye) await eyesShot(dp, p, 'desktop'); if (deep) { await testSound(dp, p, 'desktop', FULL ? 10 : SMOKE ? 3 : 5); if (!SMOKE) { await open(dp, p); await pressEverything(dp, p, 'desktop', FULL ? 40 : 20); } } }
      if (SMOKE) { await checkPage(pp, p, 'phone'); continue; }
      if (await checkPage(pp, p, 'phone')) { if (eye) await eyesShot(pp, p, 'phone'); if (p === '/en/') await fitCheck(pp, 'phone'); if (deep && (FULL || p === '/en/' || p === '/en/all-products/')) { await testSound(pp, p, 'phone', 3); await open(pp, p); await pressEverything(pp, p, 'phone', FULL ? 25 : 12); } }
    }
    // 1b) the owner's phone (Samsung A31): key pages, the home slider must fit, pictures for the eyes
    if (!SMOKE && part('a31')) {
      const a31 = await newContext(browser, 'a31'); const ap = await newPage(a31);
      for (const p of ['/en/', '/en/all-products/', '/product/infinity/', '/forum/']) {
        if (await checkPage(ap, p, 'a31')) { await eyesShot(ap, p, 'a31'); if (p === '/en/') await fitCheck(ap, 'a31'); }
      }
      await a31.close();
    }
    // 1c) ears: listen to every preview found — right after the pages (17.11: at the end it never got time)
    const ec = await newContext(browser, 'desktop');
    if (EARS && !SMOKE && part('ears') && (!audioUrls.size || process.env.QA_EARS_ALL === '1')) {
      /* (17.11) every audio file of the media library (public WP API) — does not depend on the anti-bot page of the hosting */
      for (let pg = 1; pg <= 20; pg++) {
        let arr = null;
        for (let i = 0; i < 5 && !arr; i++) { try { const r = await ec.request.get(`${BASE}/wp-json/wp/v2/media?media_type=audio&per_page=100&page=${pg}&_fields=source_url,post`, { timeout: 60000, headers: { 'User-Agent': UA_DESK } }); if (r.status() === 200) arr = await r.json(); else if (r.status() === 400) arr = []; } catch (e) {} if (!arr) await sleep(8000 * (i + 1)); }
        if (!arr || !arr.length) break;
        arr.forEach(m => { const u = m.source_url; if (u && /\.(mp3|wav|ogg|m4a)$/i.test(u) && !audioUrls.has(u)) audioUrls.set(u, new Set([m.post ? `/?p=${m.post}` : '/en/all-products/'])); });
        if (arr.length < 100) break;
      }
      if (!audioUrls.size) notChecked.push('Уши: список звуков не получен (медиатека сайта не ответила)');
    }
    if (EARS && !SMOKE && part('ears')) {
      const r = await EARS.run(audioUrls, async (u) => { for (let i = 0; i < 3; i++) { const x = await ec.request.get(u, { timeout: 120000, headers: { 'User-Agent': UA_DESK } }).catch(() => null); if (x && x.status() === 200) return await x.body(); await sleep(6000 * (i + 1)); } return null; }, +(process.env.QA_EARS_MAX || (FULL ? 80 : 30)));
      stats.audioFiles = r.checked;
      if (r.skipped) notChecked.push('Звук файлов не проверен: ' + r.skipped);
      for (const b of r.bugs) await bug(b);
    }
    await ec.close();
    // 2) other languages: their home always (menu vs English), all pages in full mode
    for (const p of !part('langs') ? [] : [...(SMOKE ? ['/ua/'] : homeAll.filter(h => h !== '/en/')), ...(FULL && process.env.QA_L10N === '1' ? other.filter(x => !homeAll.includes(x)) : [])]) {
      if (await checkPage(dp, p, 'desktop') && homeAll.includes(p)) await eyesShot(dp, p, 'desktop');
      if (homeAll.includes(p)) await checkPage(pp, p, 'phone');
    }
    // 3) translated pages must look like the English one
    for (const [k, f] of visited) {
      if (!k.endsWith('|desktop') || (urlLang(f.url) || 'en') !== 'en') continue;
      for (const L of LANGS.filter(x => x !== 'en')) {
        const alt = f.hreflang[HREFLANG[L]]; if (!alt) continue;
        const t = visited.get(rel(alt) + '|desktop'); if (!t) continue;
        const sim = jaccard(f.fp, t.fp);
        if (sim < 0.45) { const g = await bug({ page: rel(alt), device: 'компьютер', sev: normPath(alt) === '/' ? 'critical' : 'major', type: 'langlook', sig: 'langlook|' + normPath(alt), title: langLookTitle(normPath(alt)), steps: [`Открыть ${BASE}${rel(f.url)}`, `Открыть ${BASE}${rel(alt)}`, 'Сравнить'], expected: 'Та же страница, только переведённая', actual: '' }); g.actual = (g.actual ? g.actual + '; ' : '') + `${LANG_SHORT[L]}: совпадает с английской на ${Math.round(sim * 100)}%`; }
      }
    }
    // 4) scenarios a person would do
    for (const [name, fn] of !part('scen') ? [] : [['Выбор языка (компьютер)', () => scLanguageSwitch(dp, 'desktop')], ...(SMOKE ? [] : [['Выбор языка (телефон)', () => scLanguageSwitch(pp, 'phone')]]), ['Музыка при переходах', () => scPlayerAcrossPages(dp)], ['Поиск', () => scSearch(dp)], ['Корзина', () => scCart(dp)]]) {
      try { await fn(); } catch (e) { notChecked.push(`Сценарий «${name}» прервался: ${e.message.split('\n')[0].slice(0, 150)}`); }
    }
    await desk.close(); await phone.close();

    // 5) logged in: client and admin (pages open, menu shows the account, no errors)
    const roles = [['user', process.env.TEST_USER, process.env.TEST_PASS, ['/en/my-account/', '/en/my-account/orders/', '/en/my-account/edit-account/']],
      ['admin', process.env.TEST_ADMIN_USER, process.env.TEST_ADMIN_PASS, ['/en/my-account/', '/en/store-manager/']]];
    for (const [role, u, pw, list] of SMOKE || !part('login') ? [] : roles) {
      if (!u || !pw) { notChecked.push(`Вход как ${role === 'user' ? 'клиент' : 'админ'} — нет тестового логина в секретах`); continue; }
      for (const dev of ['desktop', 'phone']) {
        if (timeLeft() < 0) break;
        const c = await newContext(browser, dev); const pg = await newPage(c);
        const ok = await login(pg, u, pw).catch(() => false);
        if (!ok) { await bug({ page: '/en/my-account/', device: DEVICES[dev].name, sev: 'critical', type: 'login', sig: 'login|' + role, title: `Не получается войти тестовым ${role === 'user' ? 'клиентом' : 'админом'}`, steps: ['Открыть /en/my-account/', 'Ввести логин и пароль', 'Нажать Log in'], expected: 'Вход выполнен', actual: 'Остаётся на странице входа' }, pg); await c.close(); continue; }
        for (const p of list) {
          const f = await checkPage(pg, p, dev);
          if (f && /^sign in$/i.test(f.menu.account) && dev === 'desktop') await bug({ page: p, device: DEVICES[dev].name, sev: 'major', type: 'acc', sig: 'acc|' + role, title: 'После входа в меню всё ещё написано «Sign in»', steps: ['Войти', `Открыть ${p}`], expected: '«My account»', actual: '«Sign in»' }, pg);
        }
        await c.close();
      }
    }
    // 6) every link found on the way
    if (part('links')) {
    const lc = await newContext(browser, 'desktop');
    const titles = await checkLinks(lc, FULL ? 600 : SMOKE ? 25 : 150);
    await menuVsTarget(titles);
    await lc.close(); }
  } catch (e) {
    notChecked.push('Бот остановился с ошибкой: ' + (e.stack || e.message).split('\n').slice(0, 3).join(' '));
    console.error(e);
  }
  clearInterval(draft); save();
  await browser.close().catch(() => {});
})();
