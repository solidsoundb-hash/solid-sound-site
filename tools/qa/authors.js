// Author swarm (owner 19.11.2026: «запускай рой, чтобы все инструменты, что с авторами связано, особенно Add Product, было вылизано»).
// One robot per screen (QA_DEV = pc | laptop | phone). Logs in (test vendor, else test admin) and on every creator page:
// JS errors, failed files, sideways scroll, tiny tap targets, light (white) fields, buttons that do nothing; then walks
// Add Product step by step: every format → shown fields; Templates / Reconstructed → every DAW → only its versions.
// Read-only: no form is sent (only login), no "Send for review", no AI buttons (they cost), no logout/delete.
// Output: qa/authors/<dev>/report.md + shots.
const { chromium } = require('playwright');
const fs = require('fs');
const BASE = 'https://solid-sound.net';
const DEV = process.env.QA_DEV || 'pc';
const OUT = `qa/authors/${DEV}`;
const DEVS = { pc: { viewport: { width: 1440, height: 900 } }, laptop: { viewport: { width: 1280, height: 720 } }, phone: { viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true } };
const PAGES = ['/en/store-manager/', '/en/store-manager/ss-add-product/', '/en/store-manager/ss-products/', '/en/store-manager/ss-earnings/', '/en/store-manager/ss-payouts/',
  '/en/store-manager/ss-reviews/', '/en/store-manager/ss-settings/', '/en/store-manager/ss-help/', '/en/sell/', '/creator-guidelines/'];
const SKIP = /log ?out|delete|remove|send for review|submit|save|publish|withdraw|request payout|write with ai|make a cover|ai|✨|pay|buy|checkout|add to cart|copy/i;
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const bugs = []; const md = [];
const bug = (lvl, page, what, detail, shot) => bugs.push({ lvl, page, what, detail: String(detail || '').slice(0, 400), shot });

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const who = process.env.TEST_VENDOR_USER ? ['vendor', process.env.TEST_VENDOR_USER, process.env.TEST_VENDOR_PASS] : ['admin', process.env.TEST_ADMIN_USER, process.env.TEST_ADMIN_PASS];
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const d = DEVS[DEV];
  const ctx = await browser.newContext({ ...d, locale: 'en-US', userAgent: d.isMobile ? 'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  // never send anything but the login form; no AI calls
  await ctx.route('**/*', (route) => { const r = route.request(); const u = r.url();
    if (r.method() === 'POST' && !/wp-login\.php/.test(u) && /admin-ajax|wp-json|admin-post|ss-ai|ai_/.test(u) && /ai|cover|describe|submit|add_product/i.test((r.postData() || '') + u)) return route.fulfill({ status: 200, contentType: 'application/json', body: '{"success":false,"data":"robot"}' });
    if (/facebook|google-analytics|googletagmanager|tidio|hotjar|clarity/.test(u)) return route.abort();
    route.continue(); });
  const page = await ctx.newPage();
  const errs = []; page.on('pageerror', e => errs.push(e.message)); page.on('console', m => { if (m.type() === 'error' && !/pagespeed|favicon|net::ERR_ABORTED|Failed to load resource.*(facebook|google|tidio)/i.test(m.text())) errs.push(m.text()); });
  const bad = []; page.on('response', r => { if (r.status() >= 400 && /solid-sound\.net/.test(r.url()) && !/ss_prof|favicon/.test(r.url())) bad.push(r.status() + ' ' + r.url().replace(BASE, '')); });
  await page.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 90000 });
  await page.fill('#user_login', who[1] || ''); await page.fill('#user_pass', who[2] || '');
  await Promise.all([page.waitForNavigation({ timeout: 90000 }).catch(() => {}), page.click('#wp-submit')]);
  await sleep(2000);
  md.push(`# Рой авторов — ${DEV} (${who[0]})`, '', `Снято: ${new Date().toISOString()}`, '');
  let n = 0; const shot = async (name, full) => { const f = `${String(++n).padStart(2, '0')}_${name.replace(/[^a-z0-9]+/gi, '-').slice(0, 50)}.jpg`; await page.screenshot({ path: `${OUT}/${f}`, type: 'jpeg', quality: 55, fullPage: !!full }).catch(() => {}); return f; };

  for (const p of PAGES) {
    errs.length = 0; bad.length = 0;
    await page.goto(BASE + p, { waitUntil: 'load', timeout: 120000 }).catch(e => bug('🔴', p, 'Страница не открылась', e.message));
    await sleep(2500);
    const f = await shot(p, true);
    const info = await page.evaluate(() => {
      const vis = (e) => { const r = e.getBoundingClientRect(), s = getComputedStyle(e); return r.width > 2 && r.height > 2 && s.visibility !== 'hidden' && s.display !== 'none' && +s.opacity > 0.05; };
      const lab = (e) => (e.innerText || e.value || e.getAttribute('aria-label') || e.title || '').replace(/\s+/g, ' ').trim().slice(0, 40);
      const over = document.documentElement.scrollWidth - innerWidth;
      const wide = over > 2 ? [...document.querySelectorAll('body *')].filter(e => vis(e) && e.getBoundingClientRect().right > innerWidth + 2).slice(0, 4).map(e => (e.id ? '#' + e.id : e.tagName.toLowerCase() + '.' + String(e.className).split(' ')[0]) + ' →' + Math.round(e.getBoundingClientRect().right)) : [];
      const small = [...document.querySelectorAll('a,button,input[type=checkbox],input[type=radio],select,[role=button]')].filter(vis).filter(e => { const r = e.getBoundingClientRect(); return (r.width < 24 || r.height < 24) && !e.closest('label'); }).slice(0, 6).map(e => lab(e) + ' ' + Math.round(e.getBoundingClientRect().width) + '×' + Math.round(e.getBoundingClientRect().height));
      const light = [...document.querySelectorAll('input:not([type=hidden]):not([type=checkbox]):not([type=radio]):not([type=range]),textarea,select,.select2-selection')].filter(vis).filter(e => { const c = getComputedStyle(e).backgroundColor.match(/\d+/g) || [0, 0, 0]; return +c[0] > 200 && +c[1] > 200 && +c[2] > 200 && (c[3] === undefined || +c[3] > 0); }).slice(0, 5).map(e => (e.name || e.id || e.className || e.tagName).toString().slice(0, 40));
      const tech = (document.body.innerText.match(/\b(undefined|NaN|\[object Object\]|Warning:|Notice:|Fatal error)\b|\[[a-z_]+[^\]]*\]/g) || []).slice(0, 4);
      const h1 = [...document.querySelectorAll('h1,h2')].filter(vis).slice(0, 2).map(e => e.textContent.trim().slice(0, 50));
      return { over, wide, small, light, tech, h1 };
    }).catch(e => ({ err: e.message }));
    md.push(`## ${p}`, '', `Заголовок: ${JSON.stringify(info.h1)} · [снимок](${f})`, '');
    if (info.over > 2) bug('🟠', p, 'Страница шире экрана — прокрутка вбок', `на ${info.over}px; вылезает: ${info.wide.join(', ')}`, f);
    if (info.small && info.small.length && DEV === 'phone') bug('🟡', p, 'Мелкие кнопки для пальца (<24px)', info.small.join('; '), f);
    if (info.light && info.light.length) bug('🟡', p, 'Белые поля на тёмной странице', info.light.join('; '), f);
    if (info.tech && info.tech.length) bug('🟠', p, 'Технический текст на странице', info.tech.join(' | '), f);
    // press every safe button / tab once (same page, no navigation away)
    const btns = await page.$$eval('button:not([type=submit]), [role=tab], .ch-tabs button, summary, a[href="#"], a[href^="#"]:not([href="#chat"])', els => els.map((e, i) => ({ i, t: (e.innerText || e.getAttribute('aria-label') || e.title || '').replace(/\s+/g, ' ').trim().slice(0, 40), v: !!(e.offsetWidth && e.offsetHeight) })).filter(x => x.v && x.t)).catch(() => []);
    let pressed = 0;
    for (const b of btns.slice(0, 40)) {
      if (SKIP.test(b.t)) continue;
      const before = errs.length;
      const loc = page.locator('button:not([type=submit]), [role=tab], .ch-tabs button, summary, a[href="#"], a[href^="#"]:not([href="#chat"])').nth(b.i);
      const url0 = page.url(); const h0 = await page.evaluate(() => document.body.innerHTML.length).catch(() => 0);
      await loc.click({ timeout: 2500 }).catch(e => { if (!/intercept|outside|not visible|detached/.test(e.message)) return; if (/intercept/.test(e.message)) bug('🟡', p, `Кнопку «${b.t}» закрывает другой элемент`, e.message.split('\n')[0]); });
      await sleep(500); pressed++;
      if (errs.length > before) bug('🟠', p, `После нажатия «${b.t}» — ошибка в коде`, errs.slice(before).join(' | '), await shot(p + '-err-' + b.t));
      if (page.url() !== url0) { await page.goto(url0, { waitUntil: 'load' }).catch(() => {}); await sleep(1500); }
      else { const h1 = await page.evaluate(() => document.body.innerHTML.length).catch(() => 0); if (h1 === h0 && !/^(❮|❯|‹|›)$/.test(b.t)) md.push(`- «${b.t}»: после нажатия ничего не поменялось`); }
      await page.keyboard.press('Escape').catch(() => {});
    }
    md.push(`Нажато кнопок: ${pressed}`, '');
    if (errs.length) bug('🟠', p, 'Ошибки в коде страницы', [...new Set(errs)].slice(0, 5).join(' | '), f);
    if (bad.length) bug('🟠', p, 'Файлы/запросы не загрузились', [...new Set(bad)].slice(0, 6).join(' | '), f);
  }

  // Add Product step by step
  const AP = '/en/store-manager/ss-add-product/';
  await page.goto(BASE + AP, { waitUntil: 'load', timeout: 120000 }).catch(() => {}); await sleep(3000);
  md.push('## Add Product — по шагам', '');
  const fmts = await page.$$eval('.ss-ap-format input[type=radio]', els => els.map(e => (e.closest('label') || {}).textContent.trim())).catch(() => []);
  if (!fmts.length) bug('🔴', AP, 'Нет выбора формата', 'поле .ss-ap-format не найдено');
  const shown = () => page.evaluate(() => [...document.querySelectorAll('#solid-sound-upload-form .ss-ap-f')].filter(w => w.offsetHeight > 0).map(w => (w.className.match(/ss-ap-(?!f\b|off)([a-z0-9]+)/) || [])[1]).filter(Boolean));
  const opts = (sel) => page.$$eval(sel + ' input[type=radio]', els => els.filter(e => (e.closest('li') || e.closest('label')).offsetHeight > 0).map(e => (e.closest('label') || {}).textContent.trim()));
  for (const fm of fmts) {
    await page.locator('.ss-ap-format label', { hasText: fm }).first().click().catch(() => {}); await sleep(600);
    const sh = await shown();
    md.push(`### ${fm}`, '', `Поля: ${sh.join(', ')}`);
    const isT = /template/i.test(fm);
    if (isT && !sh.includes('daw')) bug('🟠', AP, `${fm}: нет шага DAW`, sh.join(','));
    if (!isT && (sh.includes('daw') || sh.includes('version'))) bug('🟡', AP, `${fm}: показаны DAW/версия, хотя они не нужны`, sh.join(','));
    if (isT) {
      if (sh.includes('version') && !(await page.$('.ss-ap-daw input:checked'))) bug('🟡', AP, `${fm}: версии видны до выбора DAW`, '');
      const daws = await opts('.ss-ap-daw');
      for (const dw of daws) {
        await page.locator('.ss-ap-daw label', { hasText: dw }).first().click().catch(() => {}); await sleep(400);
        const vs = await opts('.ss-ap-version');
        const wrong = vs.filter(v => v.indexOf(dw + ' ') !== 0);
        md.push(`- ${dw}: версии ${vs.join(', ') || '—'}`);
        if (!vs.length) bug('🟠', AP, `${fm} → ${dw}: нет ни одной версии`, '');
        if (wrong.length) bug('🟠', AP, `${fm} → ${dw}: чужие версии`, wrong.join(', '));
      }
      await shot('add-product-' + fm, true);
    } else await shot('add-product-' + fm, false);
    md.push('');
  }
  if (errs.length) bug('🟠', AP, 'Ошибки в коде при выборе формата/DAW', [...new Set(errs)].slice(0, 5).join(' | '));
  await browser.close();
  const order = { '🔴': 0, '🟠': 1, '🟡': 2 };
  bugs.sort((a, b) => order[a.lvl] - order[b.lvl]);
  const head = [`# Рой авторов — ${DEV}`, '', `Найдено: 🔴 ${bugs.filter(b => b.lvl === '🔴').length} · 🟠 ${bugs.filter(b => b.lvl === '🟠').length} · 🟡 ${bugs.filter(b => b.lvl === '🟡').length}`, ''];
  bugs.forEach((b, i) => head.push(`${i + 1}. ${b.lvl} **${b.what}** — ${b.page}${b.detail ? ' — ' + b.detail : ''}${b.shot ? ` ([снимок](${b.shot}))` : ''}`));
  fs.writeFileSync(`${OUT}/report.md`, head.join('\n') + '\n\n---\n\n' + md.join('\n') + '\n');
  fs.writeFileSync(`${OUT}/bugs.json`, JSON.stringify(bugs, null, 1));
  console.log(head.join('\n'));
})();
