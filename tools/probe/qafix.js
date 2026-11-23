// Probe (23.11): the night-QA fixes, read-only. Chat position on the catalog, the A/B ▶ button on Services & Prices says Pause,
// canonical of /my-account/ sub-pages, the home slider package items colour, sort box font on the phone. Output: PROBE_OUT/qafix.txt + pictures.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-qafix'; fs.mkdirSync(OUT, { recursive: true });
const SITE = 'https://solid-sound.net';
const DEV = {
  phone: { viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' },
  pc: { viewport: { width: 1366, height: 768 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' },
};
const log = []; const L = (...a) => { const s = a.join(' '); log.push(s); console.log(s); };
async function go(p, u) { for (let i = 0; i < 3; i++) { try { const r = await p.goto(SITE + u + (u.includes('?') ? '&' : '?') + 'r=' + Date.now(), { waitUntil: 'load', timeout: 90000 }); if (r && r.status() < 500) { await p.waitForTimeout(2500); return r.status(); } } catch (e) {} await p.waitForTimeout(10000); } return 0; }
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const [dev, opt] of Object.entries(DEV)) {
    L(`\n=== ${dev} ===`);
    const ctx = await b.newContext({ ...opt, locale: 'en-US' }); const p = await ctx.newPage();
    // 1) chat on the catalog
    await go(p, '/en/all-products/');
    const ch = await p.evaluate(() => { const e = document.querySelector('.ssb-fab'); if (!e) return null; const r = e.getBoundingClientRect(); return { left: Math.round(r.left), right: Math.round(innerWidth - r.right) }; });
    L('Каталог, чат:', ch ? (ch.right < ch.left ? `справа ✅ (до правого края ${ch.right} px)` : `слева ❌ (до левого края ${ch.left} px)`) : 'кнопки чата нет');
    const sf = await p.evaluate(() => { const s = document.querySelector('.w-order select, select[name=orderby]'); return s ? getComputedStyle(s).fontSize : '—'; });
    L('Каталог, шрифт сортировки:', sf);
    await p.screenshot({ path: `${OUT}/${dev}-catalog.jpg`, type: 'jpeg', quality: 60 });
    // 2) Services & Prices A/B button
    await go(p, '/en/services-prices/');
    const btn = p.locator('#examples .sab-p').first();
    if (await btn.count()) {
      await btn.scrollIntoViewIfNeeded().catch(() => {});
      const a0 = await btn.getAttribute('aria-label'); await btn.click().catch(() => {}); await p.waitForTimeout(4000);
      const a1 = await btn.getAttribute('aria-label'), pr = await btn.getAttribute('aria-pressed');
      L('Цены, кнопка A/B:', `до «${a0}» → после нажатия «${a1}», aria-pressed=${pr}`, /^Pause/.test(a1 || '') ? '✅' : '❌');
      await p.locator('#examples').first().screenshot({ path: `${OUT}/${dev}-examples.jpg`, type: 'jpeg', quality: 60 }).catch(() => {});
      await btn.click().catch(() => {});
    } else L('Цены, кнопка A/B: не найдена (блок закрыт?)');
    // 3) canonical of account sub-pages (as a guest)
    for (const u of ['/en/my-account/orders/', '/en/my-account/edit-account/']) {
      await go(p, u);
      const c = await p.evaluate(() => [(document.querySelector('link[rel=canonical]') || {}).href || '—', (document.querySelector('meta[name=robots]') || {}).content || '—']);
      L(`Кабинет ${u}: canonical ${c[0]} · robots ${c[1]}`, c[0].replace(/\/$/, '').endsWith(u.replace(/\/$/, '')) || c[0] === '—' ? '✅' : '❌');
    }
    // 4) home: package items colour
    await go(p, '/en/');
    const col = await p.evaluate(() => { const e = document.querySelector('#ss-app .ssx .ssw-rel .ri:not(.on)'); return e ? getComputedStyle(e).color : '—'; });
    L('Главная, цвет невыбранных пунктов пакета:', col, col === 'rgb(143, 155, 177)' ? '✅' : '(пакет на первой вкладке не виден — смотреть снимок)');
    await ctx.close();
  }
  fs.writeFileSync(`${OUT}/qafix.txt`, log.join('\n') + '\n'); await b.close();
})();
