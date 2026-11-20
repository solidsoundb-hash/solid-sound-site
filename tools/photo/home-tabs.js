// Robot "home slider look" (owner 19.11.2026: «слайдер кривой… компактно и норм, отключи остановку при прокрутке»).
// For 3 screen sizes: every tab of the slider on the home page — picture of the slider block + heights (window vs card,
// empty space under the card); and a check that the slider keeps turning by itself while the page is scrolled.
// Read-only: no forms, no purchases. Run: a line in .github/home-look.txt → branch `shots-home` (report.md + pictures).
const { chromium } = require('playwright');
const fs = require('fs'), path = require('path');
const BASE = process.env.HOME_URL || 'https://solid-sound.net/en/';
const OUT = process.env.PHOTO_OUT || 'shots-home';
const ALLOWED = /(^|\.)(solid-sound\.net|jsdelivr\.net|ytimg\.com|fonts\.googleapis\.com|fonts\.gstatic\.com|cdnjs\.cloudflare\.com|unpkg\.com)$/;
const UA_DESK = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const UA_PHONE = 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0';
const SIZES = process.env.SIZES ? process.env.SIZES.split(',').map(x => { const [w, h, m] = x.split('x').map(Number); return [w, h, m || 0]; }) : [[1920, 848, 0], [1920, 1080, 0], [1366, 768, 0], [412, 780, 1], [412, 700, 1]]; // 1920×848 = owner's laptop: 1080 minus browser bars, taskbar and the admin bar (owner 20.11)
// PREVIEW_CSS (owner 20.11 «делай скрины перед и после, а не выкладывай чепуху»): a CSS file is added on top of the live page —
// every size is shot twice, «before» (live) and «after» (live + the CSS), so a change is seen before it is deployed.
const PREVIEW = process.env.PREVIEW_CSS && fs.existsSync(process.env.PREVIEW_CSS) ? fs.readFileSync(process.env.PREVIEW_CSS, 'utf8').trim() : '';
const PASSES = PREVIEW ? ['before', 'after'] : ['']; // 412×700 ≈ Samsung A31 with browser bars (owner 20.11)
const sleep = (ms) => new Promise(r => setTimeout(r, ms));

async function ctxFor(browser, w, h, mob) {
  const ctx = await browser.newContext({ viewport: { width: w, height: h }, isMobile: !!mob, hasTouch: !!mob, userAgent: mob ? UA_PHONE : UA_DESK, locale: 'en-US', deviceScaleFactor: 1 });
  await ctx.route('**/*', (route) => { const req = route.request(); let host = ''; try { host = new URL(req.url()).hostname; } catch (e) {}
    if (!ALLOWED.test(host) || req.resourceType() === 'media') return route.abort().catch(() => {});
    if (req.method() !== 'GET' && req.method() !== 'HEAD') return route.fulfill({ status: 200, contentType: 'application/json', body: '{"success":true,"data":{}}' }).catch(() => {});
    route.continue().catch(() => {}); });
  return ctx;
}
async function open(p, pass) {
  for (let i = 0; i < 4; i++) {
    let ok = false; try { const r = await p.goto(BASE + (BASE.includes('?') ? '&' : '?') + 'r=' + Date.now(), { waitUntil: 'load', timeout: 120000 }); ok = !!r && r.status() < 500; } catch (e) {}
    if (ok && await p.locator('#ss-app .ssx .card').count().catch(() => 0)) break;
    await sleep(15000 * (i + 1));
  }
  if (pass === 'after') await p.addStyleTag({ content: PREVIEW }).catch(() => {});
  await p.waitForTimeout(2500);
}
const state = (p) => p.evaluate(() => { const r = document.querySelector('#ss-app .ssx'); if (!r) return null;
  const cur = +(r.getAttribute('data-cur') || 0), cards = [...r.querySelectorAll('.card')], vp = r.querySelector('.vp');
  return { cur, vp: vp ? vp.offsetHeight : 0, card: cards[cur] ? cards[cur].offsetHeight : 0, max: Math.max(...cards.map(c => c.offsetHeight)),
    title: cards[cur] ? (cards[cur].querySelector('h2') || {}).textContent : '', tabs: r.querySelectorAll('.tasks button').length,
    overflowX: document.documentElement.scrollWidth > innerWidth + 1,
    cut: cards[cur] ? (() => { const cr = cards[cur].getBoundingClientRect(); return [...cards[cur].querySelectorAll('b,em,span,a,button,small,h2,p,label')].filter(e => e.offsetWidth && e.children.length === 0 && e.textContent.trim() && ((e.scrollWidth > e.clientWidth + 1 && getComputedStyle(e).overflow !== 'visible') || e.getBoundingClientRect().right > cr.right + 1 || e.getBoundingClientRect().left < cr.left - 1)).map(e => e.textContent.trim().slice(0, 20)).slice(0, 5); })() : [],
    rowOut: cards[cur] ? [...cards[cur].querySelectorAll('.chips')].filter(c => c.scrollWidth > c.clientWidth + 2 || c.getBoundingClientRect().right > cards[cur].getBoundingClientRect().right + 2).length : 0 }; });

(async () => {
  const browser = await chromium.launch({ ...(process.env.PW_CHANNEL ? { channel: process.env.PW_CHANNEL } : {}) });
  fs.mkdirSync(OUT, { recursive: true });
  const md = ['# Слайдер главной — вкладки и автолистание', '', `Снято: ${new Date().toISOString()} · ${BASE}`, ''];
  for (const pass of PASSES) for (const [w, h, mob] of SIZES) {
    const PFX = pass ? pass + '-' : '';
    // 1) auto-turn while scrolling: fresh page, nobody clicks, the page is scrolled a little, wait > 7 s
    let ctx = await ctxFor(browser, w, h, mob), p = await ctx.newPage(); await open(p, pass);
    const a0 = await state(p);
    await p.mouse.wheel(0, 250).catch(() => {}); await p.evaluate(() => scrollBy(0, 150)); await sleep(9500);
    const a1 = await state(p);
    await ctx.close();
    md.push(`## ${pass ? (pass === 'before' ? 'ДО — ' : 'ПОСЛЕ — ') : ''}${w}×${h}${mob ? ' (телефон)' : ''}`, '', `Автолистание при прокрутке: было «${a0 && a0.cur}», через 9,5 с «${a1 && a1.cur}» → ${a0 && a1 && a0.cur !== a1.cur ? '✅ листается' : '❌ стоит'}`, '');
    // 2) every tab
    ctx = await ctxFor(browser, w, h, mob); p = await ctx.newPage(); await open(p, pass);
    const n = (await state(p) || { tabs: 0 }).tabs; const vps = [];
    md.push('| Вкладка | Окно | Карточка | Пусто снизу | Самая высокая | Снимок |', '|---|---|---|---|---|---|');
    for (let i = 0; i < n; i++) {
      await p.locator('#ss-app .ssx .tasks button').nth(i).click({ timeout: 5000 }).catch(() => {}); await sleep(900);
      const s = await state(p); const f = `${PFX}${w}x${h}-${i}.jpg`;
      if (!mob) { await p.evaluate(() => scrollTo(0, 0)); await sleep(300); const fit = await p.evaluate(() => { const r = document.querySelector('#ss-app .ssx'); return r ? Math.round(r.getBoundingClientRect().bottom - innerHeight) : 0; }); s.title += fit > 0 ? ` ❌ не влезает на ${fit} px` : ` ✅ влезает (запас ${-fit} px)`; vps.push(s.vp);
        await p.screenshot({ path: path.join(OUT, f), type: 'jpeg', quality: 60 }).catch(() => {}); }
      else await p.locator('#ss-app .ssx').first().screenshot({ path: path.join(OUT, f), type: 'jpeg', quality: 60 }).catch(() => {});
      md.push(`| ${i} ${s.title} | ${s.vp} | ${s.card} | ${s.vp - s.card} | ${s.max} | [снимок](${f})${s.rowOut ? ' ❌ ряд кнопок вылезает: ' + s.rowOut : ''}${s.cut && s.cut.length ? ' ❌ обрезано/вылезает: ' + s.cut.join(' · ') : ''} |`);
    }
    if (vps.length) md.push(`Высота окна по вкладкам: ${Math.min(...vps)}–${Math.max(...vps)} px → ${Math.max(...vps) - Math.min(...vps) > 4 ? '❌ скачет при перелистывании' : '✅ не скачет'}`);
    // drag a card 70 px to the left (mouse; the same handler serves fingers) — it must turn
    { await p.locator('#ss-app .ssx .tasks button').first().click().catch(() => {}); await sleep(800);
      const bx = await p.locator('#ss-app .ssx .vp').first().boundingBox().catch(() => null);
      if (bx) { const y = bx.y + Math.min(60, bx.height / 3), x = bx.x + bx.width / 2; const c0 = (await state(p)).cur;
        await p.mouse.move(x, y); await p.mouse.down(); for (let k = 1; k <= 7; k++) { await p.mouse.move(x - k * 10, y); await sleep(16); } await p.mouse.up(); await sleep(800);
        const c1 = (await state(p)).cur; md.push(`Свайп 70 px: было «${c0}», стало «${c1}» → ${c1 !== c0 ? '✅ листается' : '❌ туго (не перелистнулось)'}`); } }
    await p.evaluate(() => scrollTo(0, 0)); await p.locator('#ss-app .ssx .tasks button').first().click().catch(() => {}); await sleep(900);
    await p.screenshot({ path: path.join(OUT, `${PFX}${w}x${h}-screen.jpg`), type: 'jpeg', quality: 60 }).catch(() => {});
    const s = await state(p); md.push('', `Первый экран: [снимок](${PFX}${w}x${h}-screen.jpg)${s && s.overflowX ? ' · ❌ есть прокрутка вбок' : ''}`, '');
    if (mob) { // (20.11 owner: «слайдер не влазит») does the whole slider fit above the bottom bar on the first screen?
      const fitr = await p.evaluate(() => { const r = document.querySelector('#ss-app .ssx'); const nav = document.querySelector('#ss-app nav[aria-label="App"]'); if (!r) return null; const b = r.getBoundingClientRect().bottom, lim = nav ? nav.getBoundingClientRect().top : innerHeight; return { b: Math.round(b), lim: Math.round(lim) }; }).catch(() => null);
      if (fitr) md.push(`Слайдер на первом экране: низ ${fitr.b} px, нижняя панель с ${fitr.lim} px → ${fitr.b <= fitr.lim ? '✅ влезает целиком' : '❌ не влезает на ' + (fitr.b - fitr.lim) + ' px'}`, '');
    }
    await ctx.close();
  }
  fs.writeFileSync(path.join(OUT, 'report.md'), md.join('\n') + '\n');
  console.log(md.join('\n'));
  await browser.close();
})();
