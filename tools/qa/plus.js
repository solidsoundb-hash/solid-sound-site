// QA «plus» robots (owner 23.11.2026: «досоздай всех ботов, которых не хватает для полного тестирования… универсальный тестировщик»).
// What the other robots did not cover. One file, parts chosen by PLUS_PARTS (comma list):
//   browsers — Safari (WebKit, iPhone) and Firefox: script errors, sideways scroll, broken pictures, the ▶ button, menu
//   keys     — keyboard only: Tab goes through the page, focus is visible, no trap, Escape closes what Enter opened
//   monkey   — «chaos tester»: ~40 random safe taps/clicks per page (tabs, chips, buttons, selects), looks for script errors,
//              blank page, sideways scroll; writes the exact steps to repeat
//   visual   — yesterday vs today: a small picture of every key page; a big change is shown side by side (base kept in qa/visual/base)
//   shapes   — screen shapes nobody checked: phone 360, phone sideways, tablet, small laptop, 2560 monitor
// Read-only: forms are not sent, nothing is bought (every non-GET request is answered by the robot itself).
// Output: PLUS_OUT/bugs.json in the QA format (the swarm merges it into qa/latest/report.md) + shots/.
const pw = require('playwright'); const fs = require('fs'); const path = require('path');
const SITE = process.env.PLUS_SITE || 'https://solid-sound.net';
const OUT = process.env.PLUS_OUT || 'qa/sw/plus'; const PARTS = (process.env.PLUS_PARTS || 'browsers,keys,monkey,visual,shapes').split(',');
const BUDGET = Date.now() + (+process.env.PLUS_BUDGET_MIN || 100) * 60000;
const PAGES = (process.env.PLUS_PAGES || '/en/,/en/all-products/,/product/infinity/,/en/services-prices/,/en/studio-calculator/,/en/faq/,/en/sell/,/en/wiki/,/en/forum/,/en/cart/,/en/my-account/').split(',');
const SITE_HOST = new URL(SITE).hostname;
const ALLOWED = /(^|\.)(solid-sound\.net|jsdelivr\.net|ytimg\.com|youtube\.com|fonts\.googleapis\.com|fonts\.gstatic\.com|cdnjs\.cloudflare\.com|unpkg\.com|gravatar\.com|wp\.com)$/;
const UA_PC = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const UA_PH = 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0';
fs.mkdirSync(path.join(OUT, 'shots'), { recursive: true });
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const bugs = new Map(), notChecked = [], stats = { plusChecks: 0 };
let shotN = 0;
function bug(b) {
  const g = bugs.get(b.sig);
  if (g) { if (b.where && !g.where.includes(b.where)) g.where.push(b.where); if (b.device && !g.devices.includes(b.device)) g.devices.push(b.device); return; }
  bugs.set(b.sig, { sev: b.sev, type: b.type, sig: b.sig, title: b.title, steps: b.steps || [], expected: b.expected || '', actual: b.actual || '', where: b.where ? [b.where] : [], devices: b.device ? [b.device] : [], shot: b.shot || '' });
}
async function shot(p, name) { if (++shotN > 120) return ''; const f = `shots/${String(shotN).padStart(3, '0')}_${name.replace(/[^a-z0-9]+/gi, '_').slice(0, 40)}.jpg`; try { await p.screenshot({ path: path.join(OUT, f), type: 'jpeg', quality: 55 }); return f; } catch (e) { return ''; } }
async function ctxFor(browser, opt) {
  const ctx = await browser.newContext({ locale: 'en-US', ...opt });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }, { name: 'ss_consent', value: 'no', domain: 'solid-sound.net', path: '/' }]).catch(() => {});
  await ctx.route('**/*', (route) => { const q = route.request(); let h = ''; try { h = new URL(q.url()).hostname; } catch (e) {}
    if (!ALLOWED.test(h) && h !== SITE_HOST) return route.abort().catch(() => {});
    if (q.method() !== 'GET' && q.method() !== 'HEAD') return route.fulfill({ status: 200, contentType: 'application/json', body: '{"success":true,"data":{}}' }).catch(() => {});
    route.continue().catch(() => {}); });
  return ctx;
}
/* script errors of the site itself (not blocked third parties, not «failed to load» of what the robot blocked) */
function watchErrors(p, list) {
  p.on('pageerror', e => list.push(String(e && e.message || e).slice(0, 160)));
  p.on('console', m => { if (m.type() !== 'error') return; const t = m.text(); const u = (m.location() || {}).url || '';
    if (/Failed to load resource|net::ERR|ERR_FAILED|blocked|CORS|favicon|youtube|ytimg|Content Security|Button failed to load|invalid-placard|Adwaita|ResizeObserver loop/i.test(t)) return; /* the last three: Safari (WebKit on Linux) about its own media-control icons */
    if (u && !/solid-sound\.net/.test(u)) return; list.push(t.slice(0, 160)); });
}
async function open(p, u) {
  for (let i = 0; i < 3; i++) {
    try { const r = await p.goto(SITE + u, { waitUntil: 'load', timeout: 90000 }); if (r && r.status() < 500) { await p.waitForTimeout(2000); return r.status(); } } catch (e) {}
    await sleep(8000 * (i + 1));
  }
  notChecked.push(`Плюс-робот: не открылась ${u}`); return 0;
}
/* shared look at a page: sideways scroll, broken pictures, things sticking out of the screen, cut text */
const look = (p) => p.evaluate(() => {
  const vw = document.documentElement.clientWidth, vis = (e) => { const s = getComputedStyle(e); const r = e.getBoundingClientRect(); return r.width > 0 && r.height > 0 && s.visibility !== 'hidden' && +s.opacity > 0.05; };
  const d = (e) => (e.tagName.toLowerCase() + (e.id ? '#' + e.id : '') + (typeof e.className === 'string' && e.className.trim() ? '.' + e.className.trim().split(/\s+/).slice(0, 2).join('.') : '') + ((e.innerText || e.getAttribute('aria-label') || '').trim() ? ' «' + (e.innerText || e.getAttribute('aria-label')).trim().replace(/\s+/g, ' ').slice(0, 30) + '»' : ''));
  const clipped = (e) => { for (let n = e.parentElement; n && n !== document.body; n = n.parentElement) { const o = getComputedStyle(n); if (o.overflowX !== 'visible' || o.position === 'fixed') return true; } return false; };
  const out = [], cut = [], img = [];
  for (const e of document.body.querySelectorAll('*')) {
    if (out.length > 5 && cut.length > 5) break;
    if (!vis(e) || e.closest('#wpadminbar,.ssp,svg,[aria-hidden=true]')) continue;
    const r = e.getBoundingClientRect();
    if (r.right > vw + 2 && r.width < vw && out.length < 6 && !clipped(e) && getComputedStyle(e).position !== 'fixed') out.push(d(e) + ` (правый край ${Math.round(r.right)} при экране ${vw})`);
    if (!e.children.length && (e.textContent || '').trim().length > 2 && e.scrollWidth > e.clientWidth + 2 && e.clientWidth > 2 && r.height > 2) { const s = getComputedStyle(e); const srOnly = /screen-reader|sr-only|visually-hidden|ss-h1x/.test(typeof e.className === 'string' ? e.className : '') || (s.clip && s.clip !== 'auto') || s.clipPath !== 'none'; if (!srOnly && s.overflow !== 'visible' && s.textOverflow !== 'ellipsis' && cut.length < 6) cut.push(d(e)); }
  }
  document.querySelectorAll('img').forEach(i => { if (i.complete && i.naturalWidth === 0 && vis(i) && img.length < 5 && !/data:/.test(i.currentSrc || i.src)) img.push((i.currentSrc || i.src).split('/').pop().slice(0, 50)); });
  return { sideways: document.documentElement.scrollWidth > vw + 2, sw: document.documentElement.scrollWidth, out, cut, img, text: (document.body.innerText || '').trim().length };
}).catch(() => null);
function lookBugs(L, where, device, tag, sh) {
  if (!L) return;
  if (L.sideways) bug({ sev: 'major', type: 'plus-sideways', sig: `plus-side|${tag}|${where}`, title: `Страница ездит вбок (${device})`, where, device, steps: [`Открыть ${SITE}${where} (${device})`], expected: 'Ширина страницы = ширина экрана', actual: `Ширина ${L.sw} px` + (L.out.length ? '. Вылезает: ' + L.out.join('; ') : ''), shot: sh });
  else if (L.out.length) bug({ sev: 'minor', type: 'plus-out', sig: `plus-out|${where}|${L.out[0].replace(/ \(.*$/, '')}`, title: `Элемент вылезает за край экрана (${device})`, where, device, steps: [`Открыть ${SITE}${where} (${device})`], expected: 'Всё в пределах экрана', actual: L.out.join('; '), shot: sh });
  if (L.cut.length) bug({ sev: 'minor', type: 'plus-cut', sig: `plus-cut|${where}|${L.cut[0]}`, title: `Текст обрезан (${device})`, where, device, steps: [`Открыть ${SITE}${where} (${device})`], expected: 'Текст виден целиком или с «…»', actual: L.cut.join('; '), shot: sh });
  if (L.img.length) bug({ sev: 'major', type: 'plus-img', sig: `plus-img|${where}`, title: 'Картинка не загрузилась', where, device, steps: [`Открыть ${SITE}${where}`], expected: 'Картинка видна', actual: L.img.join(', '), shot: sh });
  if (L.text < 80) bug({ sev: 'critical', type: 'plus-blank', sig: `plus-blank|${tag}|${where}`, title: `Пустая страница (${device})`, where, device, steps: [`Открыть ${SITE}${where} (${device})`], expected: 'Текст и кнопки', actual: `На странице ${L.text} символов текста`, shot: sh });
}
function errBugs(errs, where, device, tag, sh, steps) {
  const u = [...new Set(errs)]; if (!u.length) return;
  bug({ sev: 'major', type: 'plus-js', sig: `plus-js|${tag}|${u[0].replace(/[0-9]+/g, '').slice(0, 50)}`, title: `Ошибка скрипта в ${device}: «${u[0].slice(0, 70)}»`, where, device, steps: steps || [`Открыть ${SITE}${where} (${device})`], expected: 'Без ошибок скриптов', actual: u.slice(0, 3).join(' | '), shot: sh });
}

/* ───────────── browsers: Safari (iPhone) + Firefox */
async function partBrowsers() {
  const sets = [['webkit', 'Safari iPhone', { ...pw.devices['iPhone 13'] }], ['firefox', 'Firefox ПК', { viewport: { width: 1366, height: 768 } }]];
  for (const [eng, device, opt] of sets) {
    let br; try { br = await pw[eng].launch(); } catch (e) { notChecked.push(`${device}: браузер не запустился (${String(e.message).slice(0, 60)})`); continue; }
    const ctx = await ctxFor(br, opt);
    for (const u of PAGES) {
      if (Date.now() > BUDGET) break;
      const p = await ctx.newPage(); const errs = []; watchErrors(p, errs);
      if (!(await open(p, u))) { await p.close(); continue; }
      stats.plusChecks++;
      const sh = await shot(p, `${eng}_${u}`);
      lookBugs(await look(p), u, device, eng, sh);
      // the first ▶ on the page must start a sound (or at least switch to «Pause»)
      const play = p.locator('button[aria-label^="Play" i]:visible').first();
      if (await play.count().catch(() => 0)) {
        await play.scrollIntoViewIfNeeded().catch(() => {}); const a0 = await play.getAttribute('aria-label').catch(() => '');
        await play.click({ timeout: 4000 }).catch(() => {}); await p.waitForTimeout(3500);
        const ok = await p.evaluate(() => [...document.querySelectorAll('audio,video')].some(m => !m.paused) || !!window.__ssPlaying || !!document.querySelector('.is-playing,.playing,.on .sab-p,[aria-pressed=true],[aria-label^="Pause" i]')).catch(() => false);
        if (!ok) bug({ sev: 'check', type: 'plus-play', sig: `plus-play|${eng}|${u}`, title: `${device}: после ▶ не видно, что трек играет`, where: u, device, steps: [`Открыть ${SITE}${u} в ${device}`, `Нажать «${a0}»`], expected: 'Трек играет, кнопка ⏸', actual: 'Ни один звук не играет, кнопка не изменилась (в Safari без звуковой карты бывает ложно — смотреть снимок)', shot: await shot(p, `${eng}_play_${u}`) });
        await p.evaluate(() => document.querySelectorAll('audio,video').forEach(m => m.pause())).catch(() => {});
      }
      // the menu: the bottom bar / header buttons answer
      if (u === '/en/') {
        const menu = p.locator('#ssg-bar button:visible, #ssg button[aria-expanded]:visible').first();
        if (await menu.count().catch(() => 0)) { const before = await p.evaluate(() => document.body.innerText.length).catch(() => 0), y0 = await p.evaluate(() => scrollY).catch(() => 0); await menu.click({ timeout: 4000 }).catch(() => {}); await p.waitForLoadState('load', { timeout: 8000 }).catch(() => {}); await p.waitForTimeout(2500); /* on a PC «Products» goes to the catalog page */
          const after = await p.evaluate(() => document.body.innerText.length).catch(() => 0); const exp = await menu.getAttribute('aria-expanded').catch(() => 'gone'); const open = await p.evaluate(() => !!document.querySelector('#ssg .g-it.open, .ssg-msearch')).catch(() => true);
          const y1 = await p.evaluate(() => scrollY).catch(() => 0); /* on the home page «Products» scrolls to its section */
          if (Math.abs(y1 - y0) < 50 && before === after && exp !== 'true' && exp !== 'gone' && !open && p.url().replace(/[?#].*$/, '') === SITE + u) bug({ sev: 'major', type: 'plus-menu', sig: `plus-menu|${eng}`, title: `${device}: кнопка меню ничего не делает`, where: u, device, steps: [`Открыть ${SITE}${u} в ${device}`, 'Нажать первую кнопку меню'], expected: 'Меню открывается или переходит на страницу', actual: 'Ничего не изменилось', shot: await shot(p, `${eng}_menu`) }); }
      }
      errBugs(errs, u, device, eng, sh);
      await p.close();
    }
    await br.close();
  }
}

/* ───────────── keys: keyboard only */
async function partKeys(browser) {
  const ctx = await ctxFor(browser, { viewport: { width: 1366, height: 768 }, userAgent: UA_PC });
  for (const u of PAGES.slice(0, 8)) {
    if (Date.now() > BUDGET) break;
    const p = await ctx.newPage(); if (!(await open(p, u))) { await p.close(); continue; }
    stats.plusChecks++;
    const hidden = [], noRing = []; let trap = '', last = '', same = 0, moved = 0;
    for (let i = 0; i < 45; i++) {
      await p.keyboard.press('Tab'); await p.waitForTimeout(60);
      const f = await p.evaluate(() => { const e = document.activeElement; if (!e || e === document.body) return null;
        const r = e.getBoundingClientRect(), s = getComputedStyle(e);
        const name = (e.tagName.toLowerCase() + ' «' + (e.getAttribute('aria-label') || e.innerText || e.value || e.name || '').trim().replace(/\s+/g, ' ').slice(0, 30) + '»');
        const ring = (s.outlineStyle !== 'none' && parseFloat(s.outlineWidth) > 0) || (s.boxShadow && s.boxShadow !== 'none');
        let ring2 = ring; if (!ring && /^(INPUT|SELECT|TEXTAREA)$/.test(e.tagName)) { const f1 = s.borderColor + s.backgroundColor; e.blur(); const f0 = getComputedStyle(e).borderColor + getComputedStyle(e).backgroundColor; e.focus({ preventScroll: true }); ring2 = f1 !== f0; } /* fields show focus by the border colour */
        const seen = r.width > 1 && r.height > 1 && r.bottom > 0 && r.top < innerHeight && r.right > 0 && r.left < innerWidth && s.visibility !== 'hidden' && +s.opacity > 0.05;
        const lab = (e.labels && e.labels[0]) || e.closest('label') || (/^(checkbox|radio)$/.test(e.type) ? e.parentElement : null); if (!seen && lab) { const q = lab.getBoundingClientRect(); if (q.width > 1 && q.height > 1) return { name, ring: true, seen: true, key: name + Math.round(q.top + scrollY) }; } /* custom checkbox: the box is hidden, its label shows */
        return { name, ring: ring2, seen, key: name + Math.round(r.top + scrollY) + 'x' + Math.round(r.left) }; }).catch(() => null);
      if (!f) continue;
      if (f.key === last) { if (++same >= 3) { trap = f.name; break; } } else { same = 0; moved++; }
      last = f.key;
      if (!f.seen && hidden.length < 5 && !hidden.includes(f.name)) hidden.push(f.name);
      if (f.seen && !f.ring && noRing.length < 5 && !noRing.includes(f.name)) noRing.push(f.name);
    }
    const sh = await shot(p, `keys_${u}`);
    if (trap) bug({ sev: 'major', type: 'plus-trap', sig: `plus-trap|${u}`, title: 'Клавиатура застревает на одном элементе (Tab не идёт дальше)', where: u, device: 'Клавиатура', steps: [`Открыть ${SITE}${u}`, 'Нажимать Tab'], expected: 'Фокус идёт по кнопкам дальше', actual: `Застрял на ${trap}`, shot: sh });
    if (hidden.length) bug({ sev: 'minor', type: 'plus-focus-hidden', sig: `plus-fh|${u}`, title: 'Tab попадает на невидимые элементы', where: u, device: 'Клавиатура', steps: [`Открыть ${SITE}${u}`, 'Нажимать Tab'], expected: 'Фокус только на видимых кнопках', actual: hidden.join('; '), shot: sh });
    if (noRing.length >= 3) bug({ sev: 'minor', type: 'plus-focus-ring', sig: `plus-ring|${u}`, title: 'Не видно, где фокус клавиатуры (нет рамки)', where: u, device: 'Клавиатура', steps: [`Открыть ${SITE}${u}`, 'Нажимать Tab'], expected: 'У кнопки в фокусе видна рамка', actual: noRing.join('; '), shot: sh });
    // Escape closes what was opened (chat, search, menus with aria-expanded)
    for (const sel of ['.ssb-fab', '#ssg button[aria-expanded]']) {
      const b = p.locator(sel + ':visible').first(); if (!(await b.count().catch(() => 0))) continue;
      await b.focus().catch(() => {}); await p.keyboard.press('Enter'); await p.waitForTimeout(600);
      if ((await b.getAttribute('aria-expanded').catch(() => null)) !== 'true') continue;
      await p.keyboard.press('Escape'); await p.waitForTimeout(500);
      if ((await b.getAttribute('aria-expanded').catch(() => null)) === 'true') bug({ sev: 'minor', type: 'plus-esc', sig: `plus-esc|${sel}`, title: 'Esc не закрывает открытое окно/меню', where: u, device: 'Клавиатура', steps: [`Открыть ${SITE}${u}`, `Enter на ${sel}`, 'Нажать Esc'], expected: 'Окно закрывается', actual: 'Осталось открытым', shot: await shot(p, `esc_${sel}`) });
      await p.keyboard.press('Escape').catch(() => {});
    }
    await p.close();
  }
  await ctx.close();
}

/* ───────────── monkey: random safe actions */
async function partMonkey(browser) {
  for (const [device, opt] of [['телефон', { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, userAgent: UA_PH }], ['компьютер', { viewport: { width: 1366, height: 768 }, userAgent: UA_PC }]]) {
    const ctx = await ctxFor(browser, opt);
    for (const u of PAGES.slice(0, 7)) {
      if (Date.now() > BUDGET) break;
      const p = await ctx.newPage(); const errs = []; watchErrors(p, errs);
      if (!(await open(p, u))) { await p.close(); continue; }
      stats.plusChecks++;
      const L0 = await look(p);
      const steps = [`Открыть ${SITE}${u} (${device})`]; let seed = 7 + u.length;
      const rnd = (n) => { seed = (seed * 9301 + 49297) % 233280; return Math.floor(seed / 233280 * n); }; // same steps every night → repeatable
      for (let i = 0; i < 40; i++) {
        if (!p.url().startsWith(SITE)) { await p.goBack().catch(() => {}); await p.waitForTimeout(800); }
        const cands = await p.evaluate(() => {
          const ok = (e) => { const r = e.getBoundingClientRect(), s = getComputedStyle(e); return r.width > 4 && r.height > 4 && r.bottom > 0 && r.top < innerHeight && s.visibility !== 'hidden' && +s.opacity > .05 && !e.closest('#wpadminbar,form[action*=checkout],.woocommerce-checkout') && !(e.type === 'submit' && e.form) && !/log ?out|sign ?out|delete|remove|pay|checkout|buy now/i.test(e.innerText || e.getAttribute('aria-label') || ''); };
          return [...document.querySelectorAll('button,[role=button],[role=tab],summary,.chips span,label,select,a[href="#"],a[href^="javascript"]')].filter(ok).slice(0, 120).map((e, k) => { e.dataset.qaM = k; const r = e.getBoundingClientRect(); return { k, t: e.tagName.toLowerCase(), n: (e.getAttribute('aria-label') || e.innerText || '').trim().replace(/\s+/g, ' ').slice(0, 30) }; });
        }).catch(() => []);
        if (!cands.length || rnd(6) === 0) { await p.mouse.wheel(0, 300 + rnd(500)).catch(() => {}); steps.push('Прокрутить вниз'); await p.waitForTimeout(300); continue; }
        const c = cands[rnd(cands.length)]; const el = p.locator(`[data-qa-m="${c.k}"]`).first();
        if (c.t === 'select') { const n = await el.evaluate(s => s.options.length).catch(() => 0); if (n > 1) { await el.selectOption({ index: rnd(n) }).catch(() => {}); steps.push(`Выбрать в списке «${c.n}»`); } }
        else { await el.click({ timeout: 2000 }).catch(() => {}); steps.push(`Нажать ${c.t} «${c.n}»`); }
        await p.waitForTimeout(350);
        if (rnd(5) === 0) { await p.keyboard.press('Escape').catch(() => {}); steps.push('Esc'); }
        if (errs.length) { errBugs(errs, u, device, 'monkey', await shot(p, `monkey_${u}`), steps.slice(-8)); break; }
      }
      const L = await look(p); if (L && L0 && ((L.sideways && !L0.sideways) || (L.text < 80 && L0.text >= 80))) lookBugs(L, u, device + ' после 40 случайных нажатий', 'monkey', await shot(p, `monkey_end_${u}`));
      await p.close();
    }
    await ctx.close();
  }
}

/* ───────────── visual: yesterday vs today (small pictures, compared in the browser — no extra packages) */
async function partVisual(browser) {
  const BASE = 'qa/visual/base', NEWB = path.join(OUT, 'base'); fs.mkdirSync(NEWB, { recursive: true });
  for (const [dv, opt] of [['pc', { viewport: { width: 1366, height: 768 }, userAgent: UA_PC }], ['phone', { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, userAgent: UA_PH }]]) {
    const ctx = await ctxFor(browser, { ...opt, reducedMotion: 'reduce' });
    for (const u of PAGES) {
      if (Date.now() > BUDGET) break;
      const p = await ctx.newPage(); if (!(await open(p, u))) { await p.close(); continue; }
      stats.plusChecks++;
      await p.addStyleTag({ content: '*,*:before,*:after{animation:none!important;transition:none!important;caret-color:transparent!important}' }).catch(() => {});
      await p.evaluate(() => { try { window.ssxStop && window.ssxStop(); } catch (e) {} document.querySelectorAll('video,audio').forEach(m => m.pause()); }).catch(() => {});
      await p.waitForTimeout(800);
      const H = await p.evaluate(() => Math.min(document.documentElement.scrollHeight, 6000)).catch(() => 2000);
      let buf; try { buf = await p.screenshot({ fullPage: true, clip: { x: 0, y: 0, width: opt.viewport.width, height: H }, type: 'png', timeout: 30000 }); } catch (e) { await p.close(); continue; }
      const name = `${dv}_${u.replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home'}.png`;
      const old = fs.existsSync(path.join(BASE, name)) ? fs.readFileSync(path.join(BASE, name)).toString('base64') : '';
      const r = await p.evaluate(async ([now, old]) => {
        const load = (s) => new Promise((ok, no) => { const i = new Image(); i.onload = () => ok(i); i.onerror = no; i.src = 'data:image/png;base64,' + s; });
        const W = 240; const a = await load(now); const h = Math.round(a.height * W / a.width);
        const c = document.createElement('canvas'); c.width = W; c.height = h; const x = c.getContext('2d'); x.drawImage(a, 0, 0, W, h);
        const small = c.toDataURL('image/png').split(',')[1];
        if (!old) return { small, diff: -1 };
        const b = await load(old); const hb = b.height, hh = Math.min(h, hb);
        const d1 = x.getImageData(0, 0, W, hh).data; const c2 = document.createElement('canvas'); c2.width = W; c2.height = hb; const y = c2.getContext('2d'); y.drawImage(b, 0, 0); const d2 = y.getImageData(0, 0, W, hh).data;
        let bad = 0; for (let i = 0; i < d1.length; i += 4) if (Math.abs(d1[i] - d2[i]) + Math.abs(d1[i + 1] - d2[i + 1]) + Math.abs(d1[i + 2] - d2[i + 2]) > 90) bad++;
        // side by side: yesterday | today
        const s = document.createElement('canvas'); s.width = W * 2 + 10; s.height = Math.max(h, hb); const z = s.getContext('2d'); z.fillStyle = '#f00'; z.fillRect(0, 0, s.width, s.height); z.drawImage(b, 0, 0); z.drawImage(c, W + 10, 0);
        return { small, diff: bad / (W * hh), hOld: hb, hNew: h, pair: s.toDataURL('image/jpeg', 0.7).split(',')[1] };
      }, [buf.toString('base64'), old]).catch(() => null);
      if (r) {
        fs.writeFileSync(path.join(NEWB, name), Buffer.from(r.small, 'base64'));
        const hj = r.hOld ? Math.abs(r.hNew - r.hOld) / r.hOld : 0;
        if (r.diff >= 0.2 || hj > 0.25) { const f = `shots/${String(++shotN).padStart(3, '0')}_visual_${name.replace('.png', '')}.jpg`; fs.writeFileSync(path.join(OUT, f), Buffer.from(r.pair, 'base64'));
          bug({ sev: 'check', type: 'plus-visual', sig: `plus-vis|${name}`, title: `Страница сильно изменилась со вчера (${dv === 'pc' ? 'компьютер' : 'телефон'}) — так задумано?`, where: u, device: dv === 'pc' ? 'компьютер' : 'телефон', steps: [`Открыть ${SITE}${u}`], expected: 'Изменения только те, что делали', actual: `Изменилось ${Math.round(r.diff * 100)}% картинки, высота ${Math.round(hj * 100)}% (слева вчера, справа сегодня)`, shot: f }); }
      }
      await p.close();
    }
    await ctx.close();
  }
}

/* ───────────── shapes: screens nobody checked */
async function partShapes(browser) {
  const S = [['телефон 360', { viewport: { width: 360, height: 640 }, isMobile: true, hasTouch: true, userAgent: UA_PH }], ['телефон боком', { viewport: { width: 780, height: 360 }, isMobile: true, hasTouch: true, userAgent: UA_PH }],
    ['планшет', { viewport: { width: 820, height: 1180 }, isMobile: true, hasTouch: true, userAgent: UA_PH.replace('SM-A315F', 'SM-X700') }], ['ноутбук 1024', { viewport: { width: 1024, height: 768 }, userAgent: UA_PC }], ['монитор 2560', { viewport: { width: 2560, height: 1440 }, userAgent: UA_PC }]];
  for (const [device, opt] of S) {
    const ctx = await ctxFor(browser, opt);
    for (const u of PAGES.slice(0, 8)) {
      if (Date.now() > BUDGET) break;
      const p = await ctx.newPage(); const errs = []; watchErrors(p, errs);
      if (!(await open(p, u))) { await p.close(); continue; }
      stats.plusChecks++;
      const sh = await shot(p, `${device}_${u}`); lookBugs(await look(p), u, device, 'shape' + opt.viewport.width, sh);
      // fixed bars must not eat the screen (phone sideways: header + bottom bar)
      const eat = await p.evaluate(() => { let s = 0; const l = []; document.querySelectorAll('body *').forEach(e => { const c = getComputedStyle(e); if ((c.position === 'fixed' || c.position === 'sticky') && c.display !== 'none' && c.visibility !== 'hidden') { const r = e.getBoundingClientRect(); if (r.width > innerWidth * .6 && r.height > 20 && r.top < innerHeight && r.bottom > 0 && !e.closest('[role=dialog],.is-open')) { const v = Math.min(r.bottom, innerHeight) - Math.max(r.top, 0); if (v > 0 && !l.some(x => x.contains(e))) { s += v; l.push(e); } } } }); return Math.round(s / innerHeight * 100); }).catch(() => 0);
      if (eat > 45) bug({ sev: 'major', type: 'plus-bars', sig: `plus-bars|${device}|${u}`, title: `Закреплённые панели занимают ${eat}% экрана (${device})`, where: u, device, steps: [`Открыть ${SITE}${u} (${device})`], expected: 'Шапка и нижняя панель — не больше 30–40% экрана', actual: `Под содержимое остаётся ${100 - eat}% экрана`, shot: sh });
      errBugs(errs, u, device, 'shape', sh);
      await p.close();
    }
    await ctx.close();
  }
}

(async () => {
  const chrome = await pw.chromium.launch({ ...(process.env.PW_CHANNEL ? { channel: process.env.PW_CHANNEL } : {}) });
  const run = { browsers: () => partBrowsers(), keys: () => partKeys(chrome), monkey: () => partMonkey(chrome), visual: () => partVisual(chrome), shapes: () => partShapes(chrome) };
  for (const part of PARTS) { if (!run[part]) continue; const t = Date.now(); try { await run[part](); } catch (e) { notChecked.push(`Плюс-робот «${part}» упал: ${String(e.message).slice(0, 100)}`); } console.log(`${part}: ${Math.round((Date.now() - t) / 1000)} s, bugs so far ${bugs.size}`); }
  await chrome.close();
  fs.writeFileSync(path.join(OUT, 'bugs.json'), JSON.stringify({ mode: 'full', date: new Date().toISOString(), stats, bugs: [...bugs.values()], notChecked }, null, 1));
  console.log([...bugs.values()].map(b => `${b.sev} ${b.title} — ${b.where.join(', ')}`).join('\n') || 'no bugs');
})();
