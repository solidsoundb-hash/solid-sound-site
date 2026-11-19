// Robot "film" (owner 19.11.2026: «эд продакт коряво грузится»): how a page appears while it loads — a picture every 300 ms
// for 6 s (PC 1440 and phone 390), logged in with the owner's SOLID_SOUND_* secrets (or the test admin). Read-only.
// Output: checks/film/<page>-<dev>-NN.jpg + index.md (time of each frame and how much the layout jumped up to then).
const { chromium } = require('playwright');
const fs = require('fs');
const BASE = 'https://solid-sound.net', OUT = 'checks/film';
const PAGES = (process.env.FILM_PAGES || '/en/store-manager/ss-add-product/').split(',');
const E = process.env, U = E.SS_L1 || E.SS_L2 || E.SS_L3 || E.SS_L4 || E.TEST_ADMIN_USER, P = E.SS_P1 || E.SS_P2 || E.SS_P3 || E.SS_P4 || E.TEST_ADMIN_PASS;
(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const md = ['# Как грузится страница — кадры каждые 300 мс', '', `Снято: ${new Date().toISOString()}`, ''];
  const b = await chromium.launch({ channel: E.PW_CHANNEL || undefined });
  for (const dev of [['pc', { viewport: { width: 1440, height: 900 } }], ['phone', { viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true }]]) {
    const ctx = await b.newContext({ ...dev[1], locale: 'en-US', deviceScaleFactor: 1 });
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    const p = await ctx.newPage();
    for (let i = 0; i < 5; i++) { await p.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 90000 }).catch(() => {}); if (await p.locator('#user_login').count().catch(() => 0)) break; await new Promise(r => setTimeout(r, 15000 * (i + 1))); }
    await p.fill('#user_login', U || '', { timeout: 60000 }); await p.fill('#user_pass', P || '');
    await Promise.all([p.waitForNavigation({ timeout: 90000 }).catch(() => {}), p.click('#wp-submit')]);
    await ctx.addInitScript(() => { window.__cls = 0; try { new PerformanceObserver(l => { for (const e of l.getEntries()) if (!e.hadRecentInput) window.__cls += e.value; }).observe({ type: 'layout-shift', buffered: true }); } catch (e) {} });
    for (const pg of PAGES) {
      const slug = pg.replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '');
      md.push(`## ${pg} — ${dev[0]}`, '');
      const t0 = Date.now(); p.goto(BASE + pg + '?r=' + t0, { waitUntil: 'load', timeout: 120000 }).catch(() => {});
      const row = [];
      for (let i = 0; i < 22; i++) {
        await new Promise(r => setTimeout(r, i ? 300 : 150));
        const f = `${slug}-${dev[0]}-${String(i).padStart(2, '0')}.jpg`;
        await p.screenshot({ path: `${OUT}/${f}`, type: 'jpeg', quality: 45 }).catch(() => {});
        const cls = await p.evaluate(() => (window.__cls || 0).toFixed(3)).catch(() => '?');
        row.push(`[${((Date.now() - t0) / 1000).toFixed(1)} с · прыжки ${cls}](${f})`);
      }
      md.push(row.join(' · '), '');
    }
    await ctx.close();
  }
  await b.close();
  fs.writeFileSync(`${OUT}/index.md`, md.join('\n') + '\n'); console.log(md.join('\n'));
})();
