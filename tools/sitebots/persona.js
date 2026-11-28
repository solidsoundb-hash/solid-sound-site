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
        if (L1 != null && L2 != null && (btn.innerText || btn.value || '').trim()) contrast = (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
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
  // the site plays demos with new Audio() (not in the page): remember every media element that was started
  await ctx.addInitScript(() => { window.__ssMedia = []; const pl = HTMLMediaElement.prototype.play; HTMLMediaElement.prototype.play = function () { if (!window.__ssMedia.includes(this)) window.__ssMedia.push(this); return pl.apply(this, arguments); }; });
  if (setupCtx) await setupCtx(ctx);
  const p = await ctx.newPage();
  if (mobile) { try { const c = await ctx.newCDPSession(p); await c.send('Network.enable'); await c.send('Network.emulateNetworkConditions', { offline: false, latency: 150, downloadThroughput: 1.6 * 1024 * 1024 / 8, uploadThroughput: 750 * 1024 / 8 }); await c.send('Emulation.setCPUThrottlingRate', { rate: 4 }); } catch (e) {} }
  const errs = []; p.on('pageerror', e => errs.push(e.message.slice(0, 120) + ' (' + (p.url().replace(/^https?:\/\/[^/]+/, '').split('?')[0] || '/') + ')'));
  let n = 0; const rows = []; let clicks = 0; const S = { p, ctx, mobile, BASE };
  S.click = async (loc, opt) => { clicks++; await loc.click({ timeout: 20000, ...opt }); };
  // tap a text field: a field the finger cannot reach is a finding, not a crash — note what covers it, then type anyway
  S.tapField = async loc => { clicks++; try { await loc.click({ timeout: 6000 }); } catch (e) {
    const who = await loc.evaluate(el => { el.scrollIntoView({ block: 'center' }); const r = el.getBoundingClientRect(); const t = document.elementFromPoint(r.x + r.width / 2, r.y + r.height / 2); return !t ? 'вне экрана' : t === el || el.contains(t) ? 'поле не ответило на касание' : (t.tagName.toLowerCase() + (t.id ? '#' + t.id : '') + (t.className && typeof t.className === 'string' ? '.' + t.className.trim().split(/\s+/).slice(0, 2).join('.') : '')); }).catch(() => '?');
    S.note2 = (S.note2 ? S.note2 + '; ' : '') + 'поле не нажалось обычным касанием (' + who + ')'; await loc.focus(); } };
  S.go = async url => { const U = /^https?:/.test(url) ? url : BASE + url; const r = await p.goto(U, { waitUntil: 'domcontentloaded', timeout: 90000 }); if (r && r.status() === 429) { await sleep(45000); return p.goto(U, { waitUntil: 'domcontentloaded', timeout: 90000 }); } return r; };
  S.settle = async () => { await p.waitForLoadState('load', { timeout: 30000 }).catch(() => {}); await p.waitForTimeout(600);
    // a person answers the cookie bar once («Reject non-essential»); not counted as a step click
    const no = p.locator('.ss-cc-no').filter({ visible: true }).first(); if (await no.count().catch(() => 0)) await no.click({ timeout: 3000 }).catch(() => {}); };
  for (const [title, fn] of steps) {
    clicks = 0; S.note2 = ''; const t0 = Date.now(); let ok = true, note = '', cta = null, ctaName = '', extra = '', r_dw = null;
    try { const r = (await fn(S)) || {}; cta = r.cta || null; ctaName = r.ctaName || ''; note = r.note || ''; extra = r.extra || ''; r_dw = r.dw || null; if (r.fail) { ok = false; note = r.fail; } }
    catch (e) { ok = false; note = e.message.split('\n')[0].replace(/locator\.|page\./g, '').slice(0, 120); }
    const sec = (Date.now() - t0) / 1000;
    if (/Захищена сторінка|Just a moment|Attention Required/.test(await p.title().catch(() => ''))) { ok = false; note = 'капча/защита хостинга (не ошибка сайта)'; }
    let m = { ok, sec, clicks, note, extra, cta: false }; if (r_dw) m.dw = r_dw;
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
      const Q = 'input#top-q, header input[type=search], input[type=search], input[name=s]';
      const shown = () => S.p.evaluate(q => [...document.querySelectorAll(q)].some(e => { const r = e.getBoundingClientRect(); return r.width > 20 && r.height > 10 && r.bottom > 0 && r.top < innerHeight; }), Q);
      if (!(await shown())) { const sb = vis(S.p, '#ssg .g-sbtn, .g-sbtn, button[aria-label="Search"]'); try { await S.click(sb, { timeout: 8000 }); } catch (e) { await sb.click({ force: true, timeout: 5000 }).catch(() => {}); S.note2 = 'кнопку поиска что-то перекрывает — нажал силой'; } }
      // a second tap if the first one came before the header script was ready
      if (!(await S.p.waitForFunction(q => [...document.querySelectorAll(q)].some(e => e.getBoundingClientRect().width > 20), Q, { timeout: 1500 }).then(() => 1, () => 0))) {
        await S.p.waitForTimeout(1500); const sb2 = vis(S.p, '#ssg .g-sbtn, .g-sbtn'); if (await sb2.count()) { await sb2.click({ timeout: 5000 }).catch(() => {}); S.note2 = (S.note2 ? S.note2 + '; ' : '') + 'лупа открылась только со 2-го нажатия'; } }
      // the page has two input#top-q (one 0×0) — mark the one a person actually sees
      await S.p.waitForFunction(q => [...document.querySelectorAll(q)].some(e => { const r = e.getBoundingClientRect(); return r.width > 20 && r.height > 10; }), Q, { timeout: 10000 }).catch(() => {});
      const got = await S.p.evaluate(q => { const all = [...document.querySelectorAll(q)]; const e = all.find(e => { const r = e.getBoundingClientRect(); return r.width > 20 && r.height > 10; }); if (e) { e.setAttribute('data-persona-q', '1'); return ''; } const b = document.querySelector('.g-sbtn'); return all.map(e => { const r = e.getBoundingClientRect(); return (e.id || e.name || e.type) + ' ' + Math.round(r.width) + '×' + Math.round(r.height); }).join(', ') + ' · лупа: ' + (b ? 'aria-expanded=' + b.getAttribute('aria-expanded') + ', ssg-msearch=' + document.documentElement.classList.contains('ssg-msearch') : 'нет .g-sbtn'); }, Q);
      if (got) throw new Error('поле поиска не появилось после нажатия на лупу (' + got + ')');
      const inp = S.p.locator('[data-persona-q]').first();
      await S.tapField(inp); await inp.fill('melodic techno'); await inp.press('Enter'); await S.settle(); await S.p.waitForTimeout(1500);
      const res = vis(S.p, 'a[href*="/product/"]'); const cnt = await S.p.locator('a[href*="/product/"]').count();
      if (!cnt) throw new Error('ничего не нашлось по «melodic techno»');
      const ex = S.note2 || ''; S.note2 = ''; return { cta: res, ctaName: 'первый результат', note: `найдено ссылок на товары: ${cnt}`, extra: ex }; }],
    ['Каталог', async S => { await S.go('/en/all-products/'); await S.settle(); await S.p.evaluate(() => scrollTo(0, 0));
      const a = vis(S.p, 'a[href*="/product/"]'); await a.waitFor({ timeout: 30000 }); product = await a.getAttribute('href');
      return { cta: a, ctaName: 'карточка товара', note: 'первый: ' + product.replace(BASE, '') }; }],
    ['Страница товара', async S => { await S.click(vis(S.p, `a[href="${product}"]`)); await S.p.waitForURL(/\/product\//, { timeout: 60000 }); await S.settle();
      await S.p.evaluate(() => scrollTo(0, 0));
      return { cta: S.p.locator('.single_add_to_cart_button, button[name="add-to-cart"]').filter({ visible: true }), ctaName: 'Add to cart', note: (await S.p.title()).slice(0, 50) }; }],
    ['Послушать демо', async S => {
      const play = vis(S.p, '.summary [aria-label*="play" i], .product [aria-label*="play" i], button[aria-label*="play" i], .ss-play, :is(button,a)[class*="play-" i], :is(button,a)[class*="-play" i]');
      await S.click(play); const t0 = Date.now(); let ok = false;
      while (Date.now() - t0 < 15000) { ok = await S.p.evaluate(() => [...document.querySelectorAll('audio,video')].concat(window.__ssMedia || []).some(a => !a.paused && a.currentTime > 0.2)); if (ok) break; await S.p.waitForTimeout(300); }
      const t = ((Date.now() - t0) / 1000).toFixed(1);
      if (!ok) throw new Error('нажал ▶ — звука нет 15 с');
      await S.p.evaluate(() => [...document.querySelectorAll('audio,video')].concat(window.__ssMedia || []).forEach(a => a.pause()));
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
      if (/\/cart/.test(S.p.url())) { await S.p.mouse.move(300, 600); await S.p.keyboard.press('Escape').catch(() => {}); await S.p.waitForTimeout(500); // the header mini-cart opens on hover
        let go = vis(S.p, 'a.checkout-button, .wc-block-cart__submit-button'); if (!(await go.count())) go = vis(S.p, 'main a[href*="/checkout"], a[href*="/checkout"]'); await S.click(go); await S.p.waitForURL(/checkout/, { timeout: 60000 }); await S.settle(); }
      await S.p.evaluate(() => scrollTo(0, 0));
      const pay = S.p.locator('#place_order, .wc-block-components-checkout-place-order-button').filter({ visible: true });
      const fields = await S.p.locator('form.checkout input:visible, .wc-block-checkout input:visible').count();
      return { cta: pay, ctaName: 'Place order', note: `полей на форме: ${fields}; оплату не нажимаю`, extra: fields > 8 ? `много полей (${fields})` : '' }; }],
    ['Услуги и цены', async S => { await S.go('/en/services-prices/'); await S.settle(); await S.p.evaluate(() => scrollTo(0, 0));
      const c = S.p.getByRole('link', { name: /calculat|get (a |your )?(quote|price)|order now/i }).or(S.p.getByRole('button', { name: /calculat|get (a |your )?quote|order now/i })).filter({ visible: true }).first();
      return { cta: c, ctaName: (await c.textContent().catch(() => '') || 'заказать').trim().slice(0, 30) }; }],
    ['Калькулятор студии', async S => { await S.go('/en/studio-calculator/'); await S.settle();
      const before = (await S.p.locator('#sc-total').textContent().catch(() => '')).trim();
      const miss = []; for (const sel of ['[data-s]:not(.on):not(.active)', 'button[data-p], [data-p]:not(input)']) { const b = vis(S.p, sel); if (await b.count()) { try { await S.click(b, { timeout: 6000 }); await S.p.waitForTimeout(400); } catch (e) { miss.push(sel); } } }
      const after = (await S.p.locator('#sc-total').textContent().catch(() => '')).trim();
      if (!/\d/.test(after)) throw new Error('цена не появилась');
      return { cta: S.p.locator('#sc-total'), ctaName: 'цена', note: `цена: ${after.replace(/\s+/g, ' ').slice(0, 40)}${before === after ? ' (не изменилась после выбора)' : ''}${miss.length ? '; не нажалось: ' + miss.join(' ') : ''}`, extra: miss.length ? 'часть кнопок калькулятора не нажимается' : '' }; }],
    ['Чат: написать вопрос (не отправляю)', async S => {
      // the chat launcher = a round fixed button in the bottom-right corner (it has no stable id)
      await S.p.evaluate(() => { const vw = innerWidth, vh = innerHeight; const c = [...document.querySelectorAll('button, a, [role=button], div')].filter(e => { const r = e.getBoundingClientRect(); if (r.width < 36 || r.width > 90 || Math.abs(r.width - r.height) > 8 || r.right < vw - 60 || r.bottom < vh - 260 || r.bottom > vh) return false;
        let f = false; for (let n = e; n; n = n.parentElement) if (getComputedStyle(n).position === 'fixed') { f = true; break; } return f && parseFloat(getComputedStyle(e).borderRadius) >= r.width * 0.3 && !e.closest('[class*=player i], [id*=player i]'); }); const top = c.sort((a, b) => b.getBoundingClientRect().width - a.getBoundingClientRect().width)[0]; if (top) top.setAttribute('data-persona-fab', '1'); });
      const fab = S.p.locator('[data-persona-fab]').first();
      if (!(await fab.count())) throw new Error('не нашёл кнопку чата');
      await S.click(fab); await S.p.waitForTimeout(1500);
      const box = S.p.getByPlaceholder(/question|message|write|type/i).filter({ visible: true }).or(vis(S.p, 'textarea')).first();
      await box.waitFor({ timeout: 15000 }); await S.tapField(box); await box.fill('Hi, can you master a melodic techno track by Friday?'); const ex = S.note2 || ''; S.note2 = '';
      const send = box.locator('xpath=following::button[1]');
      return { cta: send, ctaName: 'Send', note: 'текст набран, не отправлял', extra: ex }; }],
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
  const p = await ctx.newPage();
  // same login as the night buyer (My account form) — wp-login.php gave «rest_cookie_invalid_nonce» on 28.11
  for (let i = 0; i < 2; i++) {
    await p.goto(BASE + '/en/my-account/?ss_tab=login', { waitUntil: 'domcontentloaded', timeout: 90000 });
    const f = p.locator('form.login').filter({ has: p.locator('#username:visible') }).first();
    if (await f.count()) { await f.locator('#username').fill(process.env.TEST_ADMIN_USER); await f.locator('#password').fill(process.env.TEST_ADMIN_PASS);
      await Promise.all([p.waitForNavigation({ timeout: 120000 }).catch(() => {}), f.locator('button[name=login]').click()]); await sleep(1500); }
    if ((await ctx.cookies()).some(c => /^wordpress_logged_in/.test(c.name))) break; await sleep(20000);
  }
  return { ctx, p, ok: (await ctx.cookies()).some(c => /^wordpress_logged_in/.test(c.name)) };
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

/* ---------------- «у них / у нас»: the same people on competitors' public pages ---------------- */
// Mark: home → catalog → product → demo → add to cart (no checkout, no forms, no account). Lena: the public «sell with us» page.
// Polite: robots.txt for SolidSoundQA, a pause between pages, cookie/e-mail popups hidden with CSS (never clicked).
const DW = require('../bots/design-watch.js');
const CMP = ['Scraps Audio', 'Production Music Live', 'Cymatics', 'Black Octopus', 'We Make Dance Music'];
const robotsCache = {};
async function allowed(url) {
  const u = new URL(url); if (!(u.host in robotsCache)) { try { const r = await fetch(u.origin + '/robots.txt', { headers: { 'User-Agent': DEV.pc.ctx.userAgent } }); robotsCache[u.host] = r.ok ? await r.text() : ''; } catch (e) { robotsCache[u.host] = ''; } }
  return DW.robotsAllowed(robotsCache[u.host], u.pathname);
}
function cmpSteps(site, ours) {
  let product = site.product || ''; const pause = () => sleep(ours ? 500 : 4000);
  const open = async (S, url) => { if (!(await allowed(url))) throw new Error('robots.txt запрещает ' + new URL(url).pathname); await pause(); await S.go(url); await S.settle(); await S.p.evaluate(DW.hideLayers).catch(() => {}); await S.p.evaluate(() => scrollTo(0, 0)); };
  const meas = (S, kind) => S.p.evaluate(DW.pageMeasure, { buy: DW.BUY_RE.source, notBuy: DW.BUY_NOT_RE.source, price: DW.PRICE_RE.source, kind }).catch(() => ({}));
  return [
    ['Главная', async S => { await open(S, site.home);
      const c = S.p.locator('main a, main button, a, button').filter({ hasText: /^\s*(shop( now| all| the sale)?|browse|show templates|all (products|templates)|explore|templates|view (all|collection))\s*$/i }).filter({ visible: true }).first();
      return { cta: c, ctaName: 'в магазин', dw: await meas(S, 'home') }; }],
    ['Каталог', async S => { await open(S, site.catalog); const host = new URL(site.catalog).host;
      if (!product) product = await S.p.evaluate(h => { const a = [...document.querySelectorAll('a[href]')].find(a => { try { const u = new URL(a.href); return u.host === h && /\/products?\/[^/?#]+/.test(u.pathname) && a.querySelector('img') && a.getBoundingClientRect().width > 60; } catch (e) { return false; } }); return a ? a.href : ''; }, host);
      if (!product) throw new Error('не нашёл карточку товара');
      return { cta: S.p.locator(`a[href="${new URL(product).pathname}"], a[href="${product}"]`).filter({ visible: true }), ctaName: 'карточка', dw: await meas(S, 'catalog'), note: new URL(product).pathname.slice(0, 50) }; }],
    ['Товар', async S => { await open(S, product); const dw = await meas(S, 'product');
      const tips = [];
      if (dw.pricePx && dw.pricePx < (S.mobile ? 18 : 20)) tips.push(`цена мелкая (${dw.pricePx} px)`);
      if (dw.buyH && dw.buyH < (S.mobile ? 48 : 44)) tips.push(`кнопка «купить» низкая (${dw.buyH} px)`);
      if (dw.priceFold === false) tips.push('цену не видно без прокрутки');
      return { cta: S.p.locator('[data-dw-buy]'), ctaName: dw.buyText || 'купить', dw, extra: tips.join('; ') }; }],
    ['Демо', async S => {
      // «display-…» classes contain «play»: only aria/title «play», play-/‑play/play_ classes, or a button whose text is ▶/play/listen
      let el = null; for (const sel of ['[aria-label*="play" i]:not([aria-label*="display" i]):not([aria-label*="playlist" i])', '[title*="play" i]:not([title*="display" i])', ':is(button,a,div,span)[class*="play-" i]:not([class*="display" i])', ':is(button,a,div,span)[class*="-play" i]:not([class*="display" i])', ':is(button,a)[class*="play_" i]']) { const l = S.p.locator(sel).filter({ visible: true }).first(); if (await l.count()) { el = l; break; } }
      if (!el) { const c = S.p.locator('button, a, [role=button]').filter({ visible: true }).filter({ hasText: /^\s*(▶|►|play|listen|preview)\s*$/i }).first(); if (await c.count()) el = c; }
      if (!el) return { fail: 'не нашёл кнопку ▶' };
      await S.click(el).catch(() => {}); const t0 = Date.now(); let ok = false;
      while (Date.now() - t0 < 15000) { ok = await S.p.evaluate(() => [...document.querySelectorAll('audio,video')].concat(window.__ssMedia || []).some(a => !a.paused && a.currentTime > 0.2)); if (ok) break; await S.p.waitForTimeout(300); }
      const t = ((Date.now() - t0) / 1000).toFixed(1); await S.p.evaluate(() => [...document.querySelectorAll('audio,video')].concat(window.__ssMedia || []).forEach(a => a.pause())).catch(() => {});
      return ok ? { cta: el, ctaName: '▶', note: `звук через ${t} с`, extra: +t > 3 ? `звук пошёл только через ${t} с` : '' } : { fail: 'нажал ▶ — звука нет 15 с (возможно, плеер во фрейме)' }; }],
    ['В корзину (без оформления)', async S => { const b = S.p.locator('[data-dw-buy]').first(); if (!(await b.count())) return { fail: 'нет кнопки «купить»' };
      const before = await S.p.evaluate(() => (document.querySelector('header') || document.body).innerText.match(/\d+/g) || []).catch(() => []);
      const u0 = S.p.url(); await S.click(b); await S.p.waitForTimeout(5000);
      const after = await S.p.evaluate(() => ({ t: ((document.querySelector('header') || document.body).innerText.match(/\d+/g) || []), drawer: [...document.querySelectorAll('[class*=cart i], [id*=cart i]')].some(e => { const r = e.getBoundingClientRect(); return r.width > 200 && r.height > 200 && getComputedStyle(e).visibility !== 'hidden'; }) }));
      const cookie = (await S.ctx.cookies()).some(c => /items_in_cart|^cart$|cart_ts|cart/.test(c.name));
      const ok = after.drawer || /cart|checkout/.test(S.p.url()) && S.p.url() !== u0 || after.t.join() !== before.join() || cookie;
      return ok ? { note: after.drawer ? 'открылась корзина сбоку' : /cart/.test(S.p.url()) ? 'перешёл в корзину' : 'счётчик корзины изменился' } : { fail: 'нажал — не видно, что товар в корзине' }; }],
  ];
}
function lenaPublicSteps(site, ours) {
  return [['Страница «продавать у нас»', async S => {
    if (!(await allowed(site.home))) throw new Error('robots.txt запрещает'); await sleep(ours ? 500 : 4000);
    let url = ours ? BASE + '/en/sell/' : '';
    if (!url) { await S.go(site.home); await S.settle();
      url = await S.p.evaluate(() => { const a = [...document.querySelectorAll('a[href]')].find(a => /\b(sell (with|on) us|become an? (artist|creator|seller|vendor|partner)|submit (your )?(music|sounds|demo)|artists? wanted|work with us|join (the|our) team|for (artists|creators))\b/i.test(a.innerText + ' ' + (a.getAttribute('aria-label') || ''))); return a ? a.href : ''; });
      if (!url) return { fail: 'на главной нет ссылки для авторов («sell with us», «become an artist»…)' };
      if (!(await allowed(url))) return { fail: 'robots.txt запрещает страницу авторов' }; }
    await S.go(url); await S.settle(); await S.p.evaluate(DW.hideLayers).catch(() => {}); await S.p.evaluate(() => scrollTo(0, 0));
    const info = await S.p.evaluate(() => { const t = document.body.innerText; return { words: t.split(/\s+/).length, pct: /\d{1,2}\s?%/.test(t), payout: /payout|paypal|stripe|royalt|commission|revenue share|earn/i.test(t) }; });
    const c = S.p.locator('a, button').filter({ hasText: /^\s*(apply|sign up|submit|get started|join|become|start selling|register|contact)\b/i }).filter({ visible: true }).first();
    return { cta: c, ctaName: 'Apply', note: `${new URL(url).pathname.slice(0, 40)} · слов ${info.words}${info.pct ? ' · есть % автору' : ' · % автору не назван'}${info.payout ? ' · про выплаты есть' : ''}`, extra: info.pct ? '' : 'не понял, сколько я получу с продажи' }; }]];
}
async function compare(browser, devs) {
  const CFG = JSON.parse(fs.readFileSync(path.join(__dirname, '../bots/design/sites.json'), 'utf8'));
  const sites = [{ ...CFG.ours, ours: true }].concat(CFG.competitors.filter(c => CMP.includes(c.name)));
  const res = [];
  for (const site of sites) for (const d of devs) {
    const mk = await walk(browser, 'cmp-' + slug(site.name) + '-mark', d, cmpSteps(site, site.ours));
    const le = await walk(browser, 'cmp-' + slug(site.name) + '-lena', d, lenaPublicSteps(site, site.ours));
    res.push({ site: site.name, ours: !!site.ours, dev: d, rows: mk.rows.concat(le.rows) });
  }
  const at = new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC';
  const titles = [...new Set(res.flatMap(r => r.rows.map(x => x.title)))];
  let md = `# 🆚 Персоны «у них / у нас» — ${at}\n\nТе же «Марк» и «Лена» на публичных страницах ${sites.length - 1} конкурентов (\`tools/bots/design/sites.json\`): главная → каталог → товар → демо → в корзину (без оформления, форм и аккаунта); у «Лены» — страница для авторов, без регистрации. robots.txt, пауза 4 с, UA SolidSoundQA. Оценка = среднее четырёх (удобно/быстро/понятно/красиво), 1–5, из замеров.\n\n`;
  for (const d of devs) {
    md += `## ${DEV[d].label}\n\n| Шаг | ${res.filter(r => r.dev === d).map(r => r.ours ? '**Мы**' : r.site).join(' | ')} |\n|---|${res.filter(r => r.dev === d).map(() => '---').join('|')}|\n`;
    for (const t of titles) md += `| ${t} | ${res.filter(r => r.dev === d).map(r => { const x = r.rows.find(y => y.title === t); return x ? `${x.m.ok ? AVG(x).toFixed(1) : '❌'} · ${x.m.sec.toFixed(1)} с` : '—'; }).join(' | ')} |\n`;
    const dw = k => res.filter(r => r.dev === d).map(r => { const x = r.rows.find(y => y.title === 'Товар'); const v = x && x.m.dw && x.m.dw[k]; return v == null ? '—' : v === true ? 'да' : v === false ? 'нет' : v; }).join(' | ');
    md += `| Цена, px | ${dw('pricePx')} |\n| Кнопка «купить», высота px | ${dw('buyH')} |\n| Цена на 1-м экране | ${dw('priceFold')} |\n| Кнопка на 1-м экране | ${dw('buyFold')} |\n| Шапка, px | ${dw('hdrH')} |\n| Текст, px | ${dw('bodyPx')} |\n| Шрифтов | ${dw('families')} |\n\n`;
    md += '<details><summary>Что почувствовали (цитаты)</summary>\n\n' + res.filter(r => r.dev === d).map(r => `**${r.ours ? 'Мы' : r.site}:** ` + r.rows.map(x => `${x.title} — «${x.quote}» ([снимок](persona/${x.file}))`).join(' · ')).join('\n\n') + '\n\n</details>\n\n';
  }
  md += `---\nРобот \`tools/sitebots/persona.js compare\`; вручную — строка \`дата compare\` в \`.github/persona.txt\`. Выводы «нравится / красиво» по снимкам пишет Тестировщик в утренней смене (правило §0в).\n`;
  fs.writeFileSync(path.join(OUT, 'persona-compare.md'), md); return md;
}
const slug = s => s.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '').slice(0, 20);

if (require.main === module) (async () => {
  if (process.argv[2] === '--exam') process.exit(exam() ? 0 : 1);
  const who = (process.argv[2] || 'all').toLowerCase(); const devs = (process.argv[3] || 'both') === 'both' ? ['pc', 'a31'] : [process.argv[3]];
  fs.mkdirSync(SH, { recursive: true });
  const { chromium } = require('playwright');
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  try {
    if (who === 'compare') console.log(await compare(browser, devs));
    if (who === 'mark' || who === 'all') {
      const walks = []; for (const d of devs) walks.push(await walk(browser, 'mark', d, markSteps()));
      console.log(report('Марк', 'mark', 'Покупатель из `docs/customer-avatar.md`: продюсер house / melodic techno, сидит за компьютером, английский. Проходит покупку до кнопки оплаты (не платит), смотрит услуги, калькулятор и чат (пишет, но не отправляет).', walks));
    }
    if (who === 'lena' || who === 'all') {
      const log = []; let extra = '';
      if (!process.env.TEST_ADMIN_USER) { extra = '⚠️ Нет секретов TEST_ADMIN_* — «Лена» не создана, шаги не пройдены.\n\n'; report('Лена', 'lena', 'Автор, активно пользуется кабинетом.', [], extra); }
      else {
        const ad = await admin(browser); if (!ad.ok) log.push('⚠️ тестовый админ не вошёл (капча хостинга или пароль)'); await cleanLena(ad, log); // leftovers of a crashed run first
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
