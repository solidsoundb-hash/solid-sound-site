// PERSONA ROBOTS (owner 28.11.2026 via «Задачи», role «Тестировщик»): two people walk the site like real users and say how it
// FEELS, not only «works / broken». Every step gets 1–5 for «удобно / быстро / понятно / красиво» computed from measurements
// (seconds, clicks, is the main button on the first screen, small tap targets, contrast, sideways scroll, broken images, errors);
// the first-person quote is derived from the worst measurement. Computer 1366×768 and Samsung A31 412×780 (slow 4G, CPU ×4).
//  • «Mark» (docs/customer-avatar.md: house / melodic techno producer, computer, EN): home → search → catalog → product → demo →
//    cart → checkout up to the pay button (never pays) → services → studio calculator → chat (types, never sends).
//  • «Lena» (creator, uses the dashboard a lot): a throw-away test creator (zz-persona-…@example.com, made by the test admin) logs in →
//    dashboard → fills a new product (cover, demo, ZIP, description, price) → edit of a draft → sales stats → payouts → settings.
//    She never presses «Send for review» (that would send the owner a Telegram card + e-mail every night) and everything is
//    deleted again at the end (user, products, media). Nothing is published.
// Run: node tools/sitebots/persona.js mark|lena|all [pc|a31|both]   ·   node tools/sitebots/persona.js --exam (scoring exam)
// Output: reports/tester/persona-<name>.md + reports/tester/persona/<name>-<dev>-NN.jpg
'use strict';
const fs = require('fs'); const path = require('path'); const crypto = require('crypto');
const BASE = process.env.SITE || 'https://solid-sound.net';
const OUT = 'reports/tester'; const SH = path.join(OUT, 'persona');
const TAG = ' SolidSoundQA/1.0';
const DEV = {
  pc: { label: 'ПК 1366', ctx: { viewport: { width: 1366, height: 768 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' + TAG }, slow: 1 },
  a31: { label: 'Samsung A31 412×780 (медленная сеть, CPU ×4)', ctx: { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, deviceScaleFactor: 2.6, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' + TAG }, slow: 1.6 },
};
const sleep = ms => new Promise(r => setTimeout(r, ms));

/* ---------------- scoring (pure — checked by the exam) ---------------- */
function score(m, mobile) {
  const k = mobile ? 1.6 : 1; const s = m.sec / k;
  const fast = !m.ok ? 1 : s <= 2 ? 5 : s <= 3.5 ? 4 : s <= 5 ? 3 : s <= 8 ? 2 : 1;
  let easy = m.clicks <= 1 ? 5 : m.clicks === 2 ? 4 : m.clicks === 3 ? 3 : m.clicks <= 5 ? 2 : 1;
  if (m.cta && !m.ctaFirst) easy--; if (mobile && m.small > 8) easy--; if (!m.ok) easy = 1;
  let clear = !m.ok ? 1 : !m.cta ? 3 : m.ctaFirst ? 5 : 4; if (m.ok && m.errMsg) clear--;
  let pretty = 5; if (m.hScroll) pretty -= 2; if (m.cta && m.contrast && m.contrast < 4.5) pretty--; if (m.brokenImg) pretty--; if (m.cta && m.covered) pretty--;
  const c = x => Math.max(1, Math.min(5, x));
  return { easy: c(easy), fast: c(fast), clear: c(clear), pretty: c(pretty) };
}
function feel(m, mobile, ctaName) {
  const q = []; const s = m.sec;
  if (!m.ok) return `Не получилось: ${m.note || 'шаг не прошёл'}.`;
  if (s > (mobile ? 8 : 5)) q.push(`долго жду — ${s.toFixed(1)} с`);
  if (m.cta && !m.ctaFirst) q.push(`кнопку «${ctaName}» не видно сразу — пришлось листать`);
  if (m.cta && m.covered) q.push(`кнопку «${ctaName}» что-то перекрывает`);
  if (m.clicks > 3) q.push(`много нажатий (${m.clicks})`);
  if (mobile && m.small > 8) q.push(`пальцем легко промахнуться — ${m.small} мелких кнопок/ссылок`);
  if (m.hScroll) q.push('страница ездит вбок');
  if (m.cta && m.contrast && m.contrast < 4.5) q.push(`кнопка «${ctaName}» блёклая (контраст ${m.contrast.toFixed(1)})`);
  if (m.brokenImg) q.push(`не загрузились картинки (${m.brokenImg})`);
  if (m.errMsg) q.push(`вижу ошибку: «${m.errMsg}»`);
  if (m.extra) q.push(m.extra);
  if (!q.length) return s <= (mobile ? 3.5 : 2) ? 'Быстро и понятно, всё под рукой.' : 'Нормально, всё понятно.';
  return q[0][0].toUpperCase() + q.join('; ').slice(1) + '.';
}

/* ---------------- measuring ---------------- */
async function measure(p, cta) {
  const box = cta ? await cta.boundingBox().catch(() => null) : null;
  const vp = p.viewportSize();
  const info = await p.evaluate(({ box, mobile }) => {
    const lum = c => { const m = (c || '').match(/[\d.]+/g); if (!m) return null; const [r, g, b] = m.slice(0, 3).map(v => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); }); return 0.2126 * r + 0.7152 * g + 0.0722 * b; };
    const bgOf = el => { for (let e = el; e; e = e.parentElement) { const c = getComputedStyle(e).backgroundColor; const a = (c.match(/[\d.]+/g) || [])[3]; if (c && c !== 'transparent' && a !== '0') return c; } return 'rgb(11,15,26)'; };
    let contrast = 0, covered = false;
    if (box) {
      const el = document.elementFromPoint(Math.min(innerWidth - 2, box.x + box.width / 2), Math.min(innerHeight - 2, Math.max(1, box.y + box.height / 2)));
      if (el) {
        const btn = el.closest('a,button,input,label') || el;
        const L1 = lum(getComputedStyle(btn).color), L2 = lum(bgOf(btn));
        if (L1 != null && L2 != null) contrast = (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
        covered = !!window.__ssCta && !(window.__ssCta === el || window.__ssCta.contains(el) || el.contains(window.__ssCta));
      }
    }
    let small = 0;
    if (mobile) for (const e of document.querySelectorAll('a[href],button,input:not([type=hidden]),select,[role=button]')) {
      const r = e.getBoundingClientRect(); if (!r.width || !r.height || r.bottom < 0 || r.top > innerHeight) continue;
      const st = getComputedStyle(e); if (st.visibility === 'hidden' || st.opacity === '0') continue;
      if (Math.min(r.width, r.height) < 32) small++;
    }
    const brokenImg = [...document.images].filter(i => i.complete && i.naturalWidth === 0 && i.getBoundingClientRect().width > 20 && i.src && !/^data:/.test(i.src)).length;
    const err = document.querySelector('.woocommerce-error:not(:empty), .wc-block-components-notice-banner.is-error, .ss-ap-err:not(:empty), .msg.err');
    return { hScroll: document.documentElement.scrollWidth > innerWidth + 4, small, brokenImg, contrast, covered, errMsg: err && err.offsetParent ? err.innerText.trim().slice(0, 80) : '' };
  }, { box, mobile: vp.width < 600 });
  const ctaFirst = !!box && box.y >= 0 && box.y + Math.min(box.height, 40) <= vp.height && box.x >= 0 && box.x + box.width <= vp.width + 2;
  return { cta: !!box, ctaFirst, ...info };
}

/* ---------------- one walk ---------------- */
async function walk(browser, who, devKey, steps, setupCtx) {
  const d = DEV[devKey]; const mobile = devKey === 'a31';
  const ctx = await browser.newContext({ ...d.ctx, locale: 'en-US' });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: new URL(BASE).hostname, path: '/' }]);
  if (setupCtx) await setupCtx(ctx);
  const p = await ctx.newPage();
  if (mobile) { try { const c = await ctx.newCDPSession(p); await c.send('Network.enable'); await c.send('Network.emulateNetworkConditions', { offline: false, latency: 150, downloadThroughput: 1.6 * 1024 * 1024 / 8, uploadThroughput: 750 * 1024 / 8 }); await c.send('Emulation.setCPUThrottlingRate', { rate: 4 }); } catch (e) {} }
  const errs = []; p.on('pageerror', e => errs.push(e.message.slice(0, 140)));
  let n = 0; const rows = []; let clicks = 0; const S = { p, ctx, mobile, BASE };
  S.click = async (loc, opt) => { clicks++; await loc.click({ timeout: 20000, ...opt }); };
  S.go = async url => { const r = await p.goto(BASE + url, { waitUntil: 'domcontentloaded', timeout: 90000 }); if (r && r.status() === 429) { await sleep(45000); return p.goto(BASE + url, { waitUntil: 'domcontentloaded', timeout: 90000 }); } return r; };
  S.settle = async () => { await p.waitForLoadState('load', { timeout: 30000 }).catch(() => {}); await p.waitForTimeout(600); };
  for (const [title, fn] of steps) {
    clicks = 0; const t0 = Date.now(); let ok = true, note = '', cta = null, ctaName = '', extra = '';
    try { const r = (await fn(S)) || {}; cta = r.cta || null; ctaName = r.ctaName || ''; note = r.note || ''; extra = r.extra || ''; }
    catch (e) { ok = false; note = e.message.split('\n')[0].replace(/locator\.|page\./g, '').slice(0, 120); }
    const sec = (Date.now() - t0) / 1000;
    if (/Захищена сторінка/.test(await p.title().catch(() => ''))) { ok = false; note = 'хостинг показал капчу (не ошибка сайта)'; }
    let m = { ok, sec, clicks, note, extra, cta: false };
    try {
      if (cta) { await cta.first().scrollIntoViewIfNeeded({ timeout: 3000 }).catch(() => {}); await cta.first().evaluate(e => { window.__ssCta = e; }).catch(() => {}); }
      if (cta && !(await cta.first().isVisible().catch(() => false))) cta = null;
      // «first screen» = where the person lands, so measure the CTA position before our scroll: re-measure from the top for page loads
      const pre = cta && ctaName && !/^#/.test(ctaName) ? await cta.first().evaluate(e => { const st = document.scrollingElement.scrollTop; return st; }).catch(() => 0) : 0;
      m = { ...m, ...(await measure(p, cta ? cta.first() : null)) };
      if (pre > 30) m.ctaFirst = false;
    } catch (e) {}
    m.ok = ok;
    n++; const file = `${who}-${devKey}-${String(n).padStart(2, '0')}.jpg`;
    await p.screenshot({ path: path.join(SH, file), type: 'jpeg', quality: 45 }).catch(() => {});
    const sc = score(m, mobile);
    rows.push({ title, sc, m, quote: feel(m, mobile, ctaName), file, ctaName });
  }
  await ctx.close();
  return { dev: d.label, devKey, rows, errs: [...new Set(errs)].slice(0, 5) };
}

/* ---------------- Mark: customer ---------------- */
const vis = (p, sel) => p.locator(sel).filter({ visible: true }).first();
function markSteps() {
  let product = '';
  return [
    ['Главная', async S => { await S.go('/en/'); await S.settle(); await S.p.evaluate(() => scrollTo(0, 0));
      const c = S.p.getByRole('button', { name: /show templates/i }).or(S.p.getByRole('link', { name: /show templates|shop the sale|templates/i })).filter({ visible: true }).first();
      return { cta: c, ctaName: 'Show templates' }; }],
    ['Поиск «melodic techno»', async S => {
      let inp = vis(S.p, 'input#top-q, header input[type=search], input[type=search], input[name=s]');
      if (!(await inp.count())) { await S.click(vis(S.p, 'button[aria-label*="search" i], a[aria-label*="search" i], [class*="search" i] button')); await S.p.waitForTimeout(800); inp = vis(S.p, 'input#top-q, input[type=search], input[name=s]'); }
      await S.click(inp); await inp.fill('melodic techno'); await inp.press('Enter'); await S.settle(); await S.p.waitForTimeout(1500);
      const res = vis(S.p, 'a[href*="/product/"]'); const cnt = await S.p.locator('a[href*="/product/"]').count();
      if (!cnt) throw new Error('ничего не нашлось по «melodic techno»');
      return { cta: res, ctaName: 'первый результат', note: `найдено ссылок на товары: ${cnt}` }; }],
    ['Каталог', async S => { await S.go('/en/all-products/'); await S.settle(); await S.p.evaluate(() => scrollTo(0, 0));
      const a = vis(S.p, 'a[href*="/product/"]'); await a.waitFor({ timeout: 30000 }); product = await a.getAttribute('href');
      return { cta: a, ctaName: 'карточка товара', note: 'первый: ' + product.replace(BASE, '') }; }],
    ['Страница товара', async S => { await S.click(vis(S.p, `a[href="${product}"]`)); await S.p.waitForURL(/\/product\//, { timeout: 60000 }); await S.settle();
      await S.p.evaluate(() => scrollTo(0, 0));
      return { cta: S.p.locator('.single_add_to_cart_button, button[name="add-to-cart"]').filter({ visible: true }), ctaName: 'Add to cart', note: (await S.p.title()).slice(0, 50) }; }],
    ['Послушать демо', async S => {
      const play = vis(S.p, '.summary [aria-label*="play" i], .product [aria-label*="play" i], button[aria-label*="play" i], .ss-play, [class*="play" i]:is(button,a)');
      await S.click(play); const t0 = Date.now(); let ok = false;
      while (Date.now() - t0 < 15000) { ok = await S.p.evaluate(() => [...document.querySelectorAll('audio,video')].some(a => !a.paused && a.currentTime > 0.2)); if (ok) break; await S.p.waitForTimeout(300); }
      const t = ((Date.now() - t0) / 1000).toFixed(1);
      if (!ok) throw new Error('нажал ▶ — звука нет 15 с');
      await S.p.evaluate(() => document.querySelectorAll('audio,video').forEach(a => a.pause()));
      return { cta: play, ctaName: '▶', note: `звук через ${t} с`, extra: +t > 3 ? `звук пошёл только через ${t} с` : '' }; }],
    ['В корзину', async S => { const b = S.p.locator('.single_add_to_cart_button, button[name="add-to-cart"]').filter({ visible: true }).first();
      const ans = S.p.waitForResponse(r => /cart\/add-item|add-to-cart|wc-ajax=add_to_cart/.test(r.url()) && r.request().method() === 'POST', { timeout: 30000 }).catch(() => null);
      await S.click(b); const r = await ans; if (r && r.status() >= 400) throw new Error('сайт ответил ошибкой ' + r.status());
      await S.p.waitForTimeout(1200);
      if (!(await S.ctx.cookies()).some(c => c.name === 'woocommerce_items_in_cart')) throw new Error('кнопка нажата, а корзина пустая');
      return { cta: S.p.locator('a[href*="/cart"]').filter({ visible: true }), ctaName: 'корзина', note: 'товар в корзине' }; }],
    ['Корзина → оформление', async S => {
      const cartLink = vis(S.p, 'a[href*="/cart"]'); if (await cartLink.count()) { await S.click(cartLink); await S.settle(); } else { await S.go('/en/cart/'); await S.settle(); }
      if (!/cart|checkout/.test(S.p.url())) { await S.go('/en/cart/'); await S.settle(); }
      if (/\/cart/.test(S.p.url())) { const go = vis(S.p, 'a.checkout-button, .wc-block-cart__submit-button, a[href*="/checkout"]'); await S.click(go); await S.p.waitForURL(/checkout/, { timeout: 60000 }); await S.settle(); }
      await S.p.evaluate(() => scrollTo(0, 0));
      const pay = S.p.locator('#place_order, .wc-block-components-checkout-place-order-button').filter({ visible: true });
      const fields = await S.p.locator('form.checkout input:visible, .wc-block-checkout input:visible').count();
      return { cta: pay, ctaName: 'Place order', note: `полей на форме: ${fields}; оплату не нажимаю`, extra: fields > 8 ? `много полей (${fields})` : '' }; }],
    ['Услуги и цены', async S => { await S.go('/en/services-prices/'); await S.settle(); await S.p.evaluate(() => scrollTo(0, 0));
      const c = S.p.getByRole('link', { name: /calculat|order|start|get .*price|quote/i }).or(S.p.getByRole('button', { name: /calculat|order|start|quote/i })).filter({ visible: true }).first();
      return { cta: c, ctaName: (await c.textContent().catch(() => '') || 'заказать').trim().slice(0, 30) }; }],
    ['Калькулятор студии', async S => { await S.go('/en/studio-calculator/'); await S.settle();
      const before = (await S.p.locator('#sc-total').textContent().catch(() => '')).trim();
      for (const sel of ['[data-s]', '[data-t]', '[data-p]']) { const b = vis(S.p, sel); if (await b.count()) { await S.click(b); await S.p.waitForTimeout(400); } }
      const after = (await S.p.locator('#sc-total').textContent().catch(() => '')).trim();
      if (!/\d/.test(after)) throw new Error('цена не появилась');
      return { cta: S.p.locator('#sc-total'), ctaName: 'цена', note: `цена: ${after.replace(/\s+/g, ' ').slice(0, 40)}${before === after ? ' (не изменилась после выбора)' : ''}` }; }],
    ['Чат: написать вопрос (не отправляю)', async S => {
      const fab = vis(S.p, 'button[aria-label*="chat" i], a[aria-label*="chat" i], [id*="chat" i] > button, button[class*="chat" i], [class*="chat-fab" i], [class*="chat" i][role=button]');
      if (!(await fab.count())) throw new Error('не нашёл кнопку чата');
      await S.click(fab); await S.p.waitForTimeout(1500);
      const box = vis(S.p, '[class*="chat" i] textarea, [id*="chat" i] textarea, [class*="chat" i] input[type=text], [id*="chat" i] input[type=text], textarea');
      await box.waitFor({ timeout: 15000 }); await S.click(box); await box.fill('Hi, can you master a melodic techno track by Friday?');
      const send = S.p.locator('[class*="chat" i] button[type=submit], [id*="chat" i] button[type=submit], [class*="chat" i] button[aria-label*="send" i]').filter({ visible: true });
      return { cta: send, ctaName: 'Send', note: 'текст набран, не отправлял' }; }],
  ];
}

/* ---------------- Lena: creator ---------------- */
async function api(p, method, pth, body) {
  return p.evaluate(async ({ method, pth, body }) => {
    const n = (await (await fetch('/wp-admin/admin-ajax.php?action=rest-nonce', { credentials: 'same-origin' })).text()).trim();
    const r = await fetch('/wp-json/' + pth, { method, credentials: 'same-origin', headers: { 'X-WP-Nonce': n, 'Content-Type': 'application/json' }, body: body ? JSON.stringify(body) : undefined });
    let j = null; try { j = await r.json(); } catch (e) {} return { s: r.status, j };
  }, { method, pth, body });
}
async function admin(browser) {
  const ctx = await browser.newContext({ ...DEV.pc.ctx }); await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: new URL(BASE).hostname, path: '/' }]);
  const p = await ctx.newPage(); await p.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 90000 });
  await p.fill('#user_login', process.env.TEST_ADMIN_USER); await p.fill('#user_pass', process.env.TEST_ADMIN_PASS);
  await Promise.all([p.waitForNavigation({ timeout: 90000 }).catch(() => {}), p.click('#wp-submit')]); await sleep(1500);
  return { ctx, p };
}
async function cleanLena(ad, log) {
  const us = await api(ad.p, 'GET', 'wp/v2/users?search=zz-persona-&context=edit&per_page=20');
  for (const u of (us.j || [])) {
    if (!/^zz-persona-[a-z0-9]+@example\.com$/.test(u.email || '')) continue;
    const ps = await api(ad.p, 'GET', 'wc/v3/products?status=any&per_page=50&search=' + encodeURIComponent('ZZ PERSONA'));
    for (const pr of (ps.j || [])) { for (const im of (pr.images || [])) await api(ad.p, 'DELETE', 'wp/v2/media/' + im.id + '?force=true'); const r = await api(ad.p, 'DELETE', 'wc/v3/products/' + pr.id + '?force=true'); log.push(`удалён товар ${pr.id} → ${r.s}`); }
    const md = await api(ad.p, 'GET', 'wp/v2/media?per_page=50&author=' + u.id);
    for (const m of (md.j || [])) { const r = await api(ad.p, 'DELETE', 'wp/v2/media/' + m.id + '?force=true'); log.push(`удалён файл ${m.id} → ${r.s}`); }
    const me = await api(ad.p, 'GET', 'wp/v2/users/me');
    const r = await api(ad.p, 'DELETE', 'wp/v2/users/' + u.id + '?force=true&reassign=' + (me.j ? me.j.id : 1)); log.push(`удалён тестовый автор ${u.id} → ${r.s}`);
  }
  const left = await api(ad.p, 'GET', 'wc/v3/products?status=any&search=' + encodeURIComponent('ZZ PERSONA'));
  log.push(`осталось товаров «ZZ PERSONA»: ${(left.j || []).length}`);
}
function lenaSteps(acc, A) {
  const go = (S, u) => S.go(u).then(() => S.settle());
  return [
    ['Вход в кабинет', async S => { await go(S, '/en/my-account/?ss_tab=login');
      const f = S.p.locator('form.login').filter({ has: S.p.locator('#username:visible') }).first();
      if (!(await f.count())) throw new Error('нет формы входа');
      await S.click(f.locator('#username')); await f.locator('#username').fill(acc.email);
      await S.click(f.locator('#password')); await f.locator('#password').fill(acc.pass);
      await Promise.all([S.p.waitForNavigation({ timeout: 90000 }).catch(() => {}), S.click(f.locator('button[name=login]'))]); await S.settle();
      if (!(await S.ctx.cookies()).some(c => /^wordpress_logged_in/.test(c.name))) throw new Error('вход не прошёл');
      return { note: 'вошла' }; }],
    ['Кабинет автора', async S => { await go(S, '/en/store-manager/'); await S.p.evaluate(() => scrollTo(0, 0));
      const add = S.p.locator('a[href*="ss-add-product"], a[href*="products-manage"]').filter({ visible: true });
      return { cta: add, ctaName: 'Add product' }; }],
    ['Новый товар: обложка, демо, ZIP, описание, цена (не отправляю)', async S => {
      const add = vis(S.p, 'a[href*="ss-add-product"]'); if (await add.count()) { await S.click(add); await S.settle(); } else await go(S, '/en/store-manager/ss-add-product/');
      const form = S.p.locator('#solid-sound-upload-form'); await form.waitFor({ timeout: 60000 });
      const t0 = Date.now();
      await S.click(form.locator('input[type=file][multiple]').first(), { force: true, trial: true }).catch(() => {});
      await form.locator('input[type=file][multiple]').first().setInputFiles([`${A}/cover.jpg`, `${A}/project.zip`, `${A}/demo.mp3`]);
      // the MP3 goes to the media library first: «ready» = the network is quiet and the file list shows all 3 files
      let ready = false; await S.p.waitForLoadState('networkidle', { timeout: 60000 }).catch(() => {});
      while (Date.now() - t0 < 60000) { ready = await S.p.evaluate(() => { const t = ((document.querySelector('.ss-wm-files') || {}).innerText || '').toLowerCase(); return /cover|\.jpg/.test(t) && /\.zip/.test(t) && /\.mp3|demo/.test(t); }); if (ready) break; await S.p.waitForTimeout(700); }
      const up = ((Date.now() - t0) / 1000).toFixed(1);
      const nm = form.locator('[name=product_name]'); if (await nm.isVisible().catch(() => false)) { await S.click(nm); await nm.fill('ZZ PERSONA Lena — delete me'); }
      const ds = form.locator('[name=product_description]'); if (await ds.isVisible().catch(() => false)) { await S.click(ds); await ds.fill('Dark melodic techno project: full Ableton set, MIDI, stems. Test — deleted automatically.'); }
      const pr = form.locator('[name=product_price]'); if (await pr.isVisible().catch(() => false)) { await S.click(pr); await pr.fill('12'); }
      const pc = (await S.p.locator('.ss-wm-ai .pc').first().textContent().catch(() => '') || '').trim();
      return { cta: S.p.locator('.ss-wm-save').filter({ visible: true }), ctaName: 'Send for review', note: `файлы обработаны за ${up} с${ready ? '' : ' (нет «Ready»)'}${pc ? ', заполнено ' + pc : ''}; «Send for review» не нажимаю`, extra: ready ? (+up > 20 ? `файлы обрабатывались ${up} с` : '') : 'не понятно, загрузились ли файлы — в списке не все 3' }; }],
    ['Мои товары → правка черновика', async S => { await go(S, '/en/store-manager/ss-products/');
      const row = S.p.locator('tr', { hasText: 'ZZ PERSONA' }).first();
      if (!(await row.count())) throw new Error('черновика нет в списке «Products»');
      const ed = row.locator('a[href*="edit"], a[href*="manage"], a[href*="ss-add-product"]').filter({ visible: true }).first();
      await S.click(ed); await S.settle();
      const save = S.p.locator('.ss-wm-save, button[type=submit], input[type=submit]').filter({ visible: true });
      return { cta: save, ctaName: 'сохранить', note: 'форма правки открылась; не сохраняю' }; }],
    ['Статистика продаж', async S => { await go(S, '/en/store-manager/ss-earnings/'); await S.p.evaluate(() => scrollTo(0, 0));
      const has = await S.p.locator('canvas, svg, table, [class*="stat" i]').filter({ visible: true }).count();
      return { note: has ? 'цифры/график есть' : 'нет ни графика, ни таблицы', extra: has ? '' : 'не вижу своих продаж' }; }],
    ['Выплаты', async S => { await go(S, '/en/store-manager/ss-payouts/'); await S.p.evaluate(() => scrollTo(0, 0));
      const c = S.p.getByRole('button', { name: /save|connect|add|request|payout/i }).or(S.p.getByRole('link', { name: /connect|add|payout|paypal|stripe/i })).filter({ visible: true }).first();
      return { cta: c, ctaName: 'способ выплаты' }; }],
    ['Настройки', async S => { await go(S, '/en/store-manager/ss-settings/'); await S.p.evaluate(() => scrollTo(0, 0));
      const c = S.p.getByRole('button', { name: /save/i }).or(S.p.locator('input[type=submit]')).filter({ visible: true }).first();
      return { cta: c, ctaName: 'Save' }; }],
  ];
}

/* ---------------- report ---------------- */
const AVG = r => (r.sc.easy + r.sc.fast + r.sc.clear + r.sc.pretty) / 4;
function report(name, who, intro, walks, extraMd) {
  const at = new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC';
  let md = `# 🧑 Персона «${name}» — ${at}\n\n${intro}\n\nОценки 1–5 считает робот из замеров: **удобно** — нажатия, кнопка на первом экране, мелкие касания; **быстро** — секунды (на A31 норма ×1,6); **понятно** — нашлась ли главная кнопка, нет ли ошибок; **красиво** — нет сдвига вбок, контраст кнопки ≥ 4,5, картинки на месте, кнопку ничего не перекрывает. Ощущение — вывод из худшего замера.\n\n`;
  const all = [];
  for (const w of walks) {
    md += `## ${w.dev}\n\n| Шаг | Удобно | Быстро | Понятно | Красиво | Замеры | Ощущение | Снимок |\n|---|---|---|---|---|---|---|---|\n`;
    for (const r of w.rows) {
      const m = r.m; all.push({ ...r, dev: w.devKey });
      const meas = [`${m.sec.toFixed(1)} с`, `нажатий ${m.clicks}`, m.cta ? (m.ctaFirst ? 'кнопка на 1-м экране' : 'кнопка ниже 1-го экрана') : (r.ctaName ? 'кнопки не нашёл' : ''), m.small ? `мелких касаний ${m.small}` : '', m.contrast ? `контраст ${m.contrast.toFixed(1)}` : '', m.note].filter(Boolean).join(' · ');
      md += `| ${m.ok ? '' : '❌ '}${r.title} | ${r.sc.easy} | ${r.sc.fast} | ${r.sc.clear} | ${r.sc.pretty} | ${meas.replace(/\|/g, '/')} | «${r.quote.replace(/\|/g, '/')}» | [снимок](persona/${r.file}) |\n`;
    }
    const avg = (w.rows.reduce((a, r) => a + AVG(r), 0) / Math.max(1, w.rows.length)).toFixed(1);
    md += `\nСредняя оценка: **${avg}** из 5.${w.errs.length ? ' Ошибки скриптов: ' + w.errs.map(e => '`' + e.replace(/`/g, "'") + '`').join(', ') : ''}\n\n`;
  }
  const bad = [...all].filter(r => AVG(r) < 4.5 || !r.m.ok).sort((a, b) => AVG(a) - AVG(b));
  const seen = new Set(); const top = [];
  for (const r of bad) { if (seen.has(r.title)) continue; seen.add(r.title); top.push(`**${r.title}** (${r.dev === 'pc' ? 'ПК' : 'A31'}, ${AVG(r).toFixed(1)}): «${r.quote}»`); if (top.length === 3) break; }
  const good = [...all].filter(r => r.m.ok).sort((a, b) => AVG(b) - AVG(a) || a.m.sec - b.m.sec); const seen2 = new Set(); const like = [];
  for (const r of good) { if (seen2.has(r.title)) continue; seen2.add(r.title); like.push(`**${r.title}** (${r.dev === 'pc' ? 'ПК' : 'A31'}, ${AVG(r).toFixed(1)}): «${r.quote}»`); if (like.length === 3) break; }
  md += `## 😣 Топ-3 раздражителя\n\n${top.length ? top.map((x, i) => `${i + 1}. ${x}`).join('\n') : 'Нет — везде 4,5+.'}\n\n## 😊 Топ-3 «понравилось»\n\n${like.map((x, i) => `${i + 1}. ${x}`).join('\n')}\n\n${extraMd || ''}---\nРобот \`tools/sitebots/persona.js\` (${who}); вручную — строка в \`.github/persona.txt\` (\`дата ${who}\` или \`дата all\`).\n`;
  fs.writeFileSync(path.join(OUT, `persona-${who}.md`), md);
  return md;
}

/* ---------------- exam: the scoring rules on fixed examples ---------------- */
function exam() {
  const ex = JSON.parse(fs.readFileSync(path.join(__dirname, '../bots/persona/exam.json'), 'utf8'));
  let ok = 0; for (const e of ex) { const s = score(e.m, e.mobile); const good = Object.keys(e.want).every(k => s[k] === e.want[k]); if (good) ok++; else console.log('✗', e.why, JSON.stringify(s), '≠', JSON.stringify(e.want)); }
  console.log(`экзамен: ${ok}/${ex.length}`); return ok === ex.length;
}

if (require.main === module) (async () => {
  if (process.argv[2] === '--exam') process.exit(exam() ? 0 : 1);
  const who = (process.argv[2] || 'all').toLowerCase(); const devs = (process.argv[3] || 'both') === 'both' ? ['pc', 'a31'] : [process.argv[3]];
  fs.mkdirSync(SH, { recursive: true });
  const { chromium } = require('playwright');
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  try {
    if (who === 'mark' || who === 'all') {
      const walks = []; for (const d of devs) walks.push(await walk(browser, 'mark', d, markSteps()));
      console.log(report('Марк', 'mark', 'Покупатель из `docs/customer-avatar.md`: продюсер house / melodic techno, сидит за компьютером, английский. Проходит покупку до кнопки оплаты (не платит), смотрит услуги, калькулятор и чат (пишет, но не отправляет).', walks));
    }
    if (who === 'lena' || who === 'all') {
      const log = []; let extra = '';
      if (!process.env.TEST_ADMIN_USER) { extra = '⚠️ Нет секретов TEST_ADMIN_* — «Лена» не создана, шаги не пройдены.\n\n'; report('Лена', 'lena', 'Автор, активно пользуется кабинетом.', [], extra); }
      else {
        const ad = await admin(browser); await cleanLena(ad, log); // leftovers of a crashed run first
        const ts = Date.now().toString(36); const acc = { email: `zz-persona-${ts}@example.com`, pass: 'Pz!' + crypto.randomBytes(9).toString('base64url') };
        const u = await api(ad.p, 'POST', 'wp/v2/users', { username: 'zzpersona' + ts, email: acc.email, password: acc.pass, first_name: 'Lena', last_name: 'Test', roles: ['wcfm_vendor'] });
        log.push(`тестовый автор: ${u.s === 201 ? 'создан id ' + u.j.id : 'НЕ создан (' + u.s + ' ' + JSON.stringify(u.j && u.j.code) + ')'}`);
        const walks = [];
        if (u.s === 201) {
          const dr = await api(ad.p, 'POST', 'wc/v3/products', { name: 'ZZ PERSONA Lena draft — delete me', status: 'draft', regular_price: '9', description: 'Test draft of the persona robot. Deleted automatically.' });
          if (dr.j && dr.j.id) { const r = await api(ad.p, 'POST', 'wp/v2/product/' + dr.j.id, { author: u.j.id }); log.push(`черновик для правки: id ${dr.j.id}, автор → ${r.s}`); }
          else log.push('черновик НЕ создан (' + dr.s + ')');
          try { for (const d of devs) walks.push(await walk(browser, 'lena', d, lenaSteps(acc, process.env.PERSONA_ASSETS || 'assets-e2e'))); }
          finally { await cleanLena(ad, log); }
        } else await cleanLena(ad, log);
        await ad.ctx.close();
        extra = `## 🧹 Тестовый автор и уборка\n\n${log.map(x => '- ' + x).join('\n')}\n\n`;
        console.log(report('Лена', 'lena', 'Автор, активно пользуется кабинетом. Тестовый аккаунт создаётся на прогон и удаляется в конце; «Send for review» робот не нажимает (иначе владельцу каждую ночь приходили бы Telegram и письмо), на витрину ничего не попадает.', walks, extra));
      }
    }
  } finally { await browser.close(); }
})().catch(e => { console.error('робот упал: ' + e.message); process.exit(1); });

module.exports = { score, feel };
