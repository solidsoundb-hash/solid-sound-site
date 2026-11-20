// Creator area audit (owner 19.11.2026: «пройди все вкладки автора … что нужно улучшить»).
// Logs in as admin (sees the creator space), full-page screenshots of every creator tab on phone + desktop
// without the cookie banner, opens "Add your own" on Add product, records JS errors and horizontal scroll.
// Output: checks/creator-audit/ (report.md + JPEG). Env: TEST_ADMIN_USER, TEST_ADMIN_PASS.
const { chromium } = require('playwright');
const fs = require('fs');
const OUT = 'checks/creator-audit';
const S = 'https://solid-sound.net';
const PAGES = ['/en/store-manager/', '/en/store-manager/ss-products/', '/en/store-manager/ss-add-product/', '/en/store-manager/ss-add-product/?ss_ff3=1', '/en/store-manager/ss-earnings/',
  '/en/store-manager/ss-payouts/', '/en/store-manager/ss-reviews/', '/en/store-manager/ss-settings/', '/en/store-manager/ss-help/', '/en/my-account/', '/sell/'];
const DEVICES = [
  { name: 'phone', viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true, ua: 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' },
  { name: 'desktop', viewport: { width: 1366, height: 900 }, isMobile: false, hasTouch: false, ua: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' },
];
const slug = (p) => p.replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home';
const hideBanners = () => document.querySelectorAll('body *').forEach(z => { const s = getComputedStyle(z); if (s.position === 'fixed' && /privacy|cookie/i.test((z.textContent || '').slice(0, 200)) && (z.textContent || '').length < 900) z.style.display = 'none'; });

async function login(page, user, pass) {
  await page.goto(S + '/en/my-account/?ss_tab=login', { waitUntil: 'domcontentloaded', timeout: 120000 });
  const form = page.locator('form.login').filter({ has: page.locator('#username:visible') }).first();
  await form.locator('#username').fill(user);
  await form.locator('#password').fill(pass);
  await Promise.all([page.waitForNavigation({ timeout: 120000 }).catch(() => {}), form.locator('button[name=login]').click()]);
  await page.waitForTimeout(1500);
  return page.evaluate(() => document.body.classList.contains('logged-in'));
}

(async () => {
  fs.rmSync(OUT, { recursive: true, force: true }); fs.mkdirSync(OUT, { recursive: true });
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const lines = ['# Creator area audit ' + new Date().toISOString(), ''];
  for (const d of DEVICES) {
    const ctx = await browser.newContext({ viewport: d.viewport, isMobile: d.isMobile, hasTouch: d.hasTouch, userAgent: d.ua, deviceScaleFactor: 1 });
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    const page = await ctx.newPage();
    let errs = [];
    page.on('pageerror', e => errs.push('JS: ' + e.message.slice(0, 200)));
    lines.push('## ' + d.name);
    let ok = false; try { ok = await login(page, process.env.TEST_ADMIN_USER, process.env.TEST_ADMIN_PASS); } catch (e) { lines.push('- login error: ' + e.message.split('\n')[0]); }
    if (!ok) { lines.push('- not logged in'); await ctx.close(); continue; }
    for (const p of PAGES) {
      errs = [];
      let st = 0;
      try { const r = await page.goto(S + p, { waitUntil: 'networkidle', timeout: 120000 }); st = r ? r.status() : 0; } catch (e) { lines.push(`- ${p} → ${e.message.split('\n')[0]}`); continue; }
      await page.waitForTimeout(2500);
      await page.evaluate(hideBanners);
      const info = await page.evaluate(() => ({ ovf: document.documentElement.scrollWidth - innerWidth, h: document.documentElement.scrollHeight, h1: (document.querySelector('h1,h2') || {}).textContent || '' }));
      const f = `${d.name}_${slug(p)}.jpg`;
      await page.screenshot({ path: `${OUT}/${f}`, type: 'jpeg', quality: 62, fullPage: true });
      lines.push(`- ${p} → HTTP ${st} · height ${info.h}px${info.ovf > 0 ? ' · ⚠ sideways scroll ' + info.ovf + 'px' : ''} · [${f}](${f})`);
      errs.forEach(e => lines.push('    - ' + e));
      if (d.name === 'phone') { const who = await page.evaluate(() => { const o = []; for (const y of [330, 345, 360]) { const e = document.elementFromPoint(60, y); if (e) { let n = e, path = []; for (let k = 0; k < 5 && n && n !== document.body; k++, n = n.parentElement) path.push(n.tagName.toLowerCase() + (n.id ? '#' + n.id : '') + (n.className && typeof n.className === 'string' ? '.' + n.className.trim().split(/\s+/).slice(0, 3).join('.') : '')); o.push(y + ': ' + path.join(' < ')); } } return o; }); who.forEach(w => lines.push('    - at ' + w)); }
      if (/add-product/.test(p)) {
        // (19.11) why is a block hidden: every child of the form body with its computed display and the CSS rules that set display:none
        const dbg = await page.evaluate(() => {
          const out = []; const f = document.getElementById('solid-sound-upload-form'); if (!f) return ['no form'];
          out.push('form.class=' + f.className + ' data=' + JSON.stringify(Object.assign({}, f.dataset)));
          const body = f.querySelector('.ss-wm-body'); if (!body) return out.concat('no .ss-wm-body');
          const rulesFor = (el) => { const r = []; for (const sh of document.styleSheets) { let cr; try { cr = sh.cssRules; } catch (e) { continue; } for (const ru of cr || []) { if (ru.style && ru.style.display === 'none' && ru.selectorText) { try { if (el.matches(ru.selectorText)) r.push(ru.selectorText.slice(0, 160)); } catch (e) {} } } } return r; };
          [...body.children].forEach(c => { const s = getComputedStyle(c); out.push(`${c.tagName.toLowerCase()}${c.id ? '#' + c.id : ''}.${String(c.className).trim().split(/\s+/).join('.')} display=${s.display}${c.getAttribute('style') ? ' inline="' + c.getAttribute('style').slice(0, 80) + '"' : ''}${s.display === 'none' ? ' ← ' + rulesFor(c).join(' | ') : ''}`); });
          return out;
        }).catch(e => ['dbg error ' + e.message]);
        dbg.forEach(x => lines.push('    - ' + x));
        // (19.11 owner: «мейк кавер не работает») computer, plain Add product: choose a type, press «Make a cover with AI», wait, record
        if (d.name === 'desktop' && !/ss_ff3/.test(p)) {
          { await page.evaluate(() => { if (window.__ssFFopen) window.__ssFFopen(); else { const b = document.querySelector('.ss-ff-man'); if (b) b.click(); } }).catch(() => {}); await page.waitForTimeout(1200); } // 19.11: only «Your files» shows first
          await page.locator('.ss-ap-format input[type=radio]').first().check({ force: true }).catch(() => {});
          await page.waitForTimeout(800);
          // (20.11 owner «причесать, кустарно»): the whole open form — «before»; with tools/photo/preview-ap.css on top — «after» (not deployed)
          await page.evaluate(hideBanners).catch(() => {});
          await page.screenshot({ path: `${OUT}/${d.name}_add_open_before.jpg`, type: 'jpeg', quality: 62, fullPage: true }).catch(() => {});
          lines.push(`- open form before: [${d.name}_add_open_before.jpg](${d.name}_add_open_before.jpg)`);
          { const pc = require('fs').existsSync('tools/photo/preview-ap.css') ? require('fs').readFileSync('tools/photo/preview-ap.css', 'utf8').trim() : '';
            if (pc) { await page.addStyleTag({ content: pc }).catch(() => {}); await page.waitForTimeout(500);
              await page.screenshot({ path: `${OUT}/${d.name}_add_open_after.jpg`, type: 'jpeg', quality: 62, fullPage: true }).catch(() => {});
              lines.push(`- open form after (preview CSS): [${d.name}_add_open_after.jpg](${d.name}_add_open_after.jpg)`); } }
          const cb = page.locator('button.ss-aic-b', { hasText: /cover/i }).first();
          if (!(await cb.count())) lines.push('    - AI cover: no button on the page');
          else {
            const vis = await cb.isVisible().catch(() => false);
            await cb.scrollIntoViewIfNeeded().catch(() => {}); await cb.click({ force: true }).catch(e => lines.push('    - AI cover click error: ' + e.message.split('\n')[0]));
            const t0 = Date.now();
            await page.waitForFunction(() => { const b = document.querySelector('.ss-aic-cv'); const n = document.querySelector('.ss-aic-n'); return (b && b.classList.contains('on')) || (n && n.textContent.trim()); }, null, { timeout: 120000 }).catch(() => {});
            await page.waitForTimeout(1500);
            const res = await page.evaluate(() => ({ on: !!document.querySelector('.ss-aic-cv.on'), thumbs: document.querySelectorAll('.ss-aic-cv .th .tb').length, notes: [...document.querySelectorAll('.ss-aic-n')].map(n => n.textContent.trim()).filter(Boolean).slice(0, 4) }));
            lines.push(`    - AI cover: button visible=${vis}, after ${Math.round((Date.now() - t0) / 1000)} s → preview ${res.on ? 'shown' : 'NOT shown'}, pictures ${res.thumbs}; messages: ${res.notes.join(' / ') || '—'}`);
            const g = `${d.name}_ai_cover.jpg`; await page.screenshot({ path: `${OUT}/${g}`, type: 'jpeg', quality: 62, fullPage: true }); lines.push(`    - [${g}](${g})`);
          }
        }
        const add = page.locator('a.acf-icon[data-name=add]').first();
        if (await add.count()) {
          await add.scrollIntoViewIfNeeded().catch(() => {}); await add.click().catch(() => {}); await page.waitForTimeout(1500);
          const g = `${d.name}_add_your_own.jpg`;
          await page.screenshot({ path: `${OUT}/${g}`, type: 'jpeg', quality: 62 });
          lines.push(`    - "Add your own" window: [${g}](${g})`);
          await page.keyboard.press('Escape').catch(() => {});
        }
      }
    }
    // (19.11 copy protection) desktop: Tools → Fingerprints → «Build missing», wait until it finishes, record the log
    if (d.name === 'desktop') {
      try {
        await page.goto(S + '/wp-admin/tools.php?page=ss-fp', { waitUntil: 'domcontentloaded', timeout: 120000 });
        const head = await page.locator('.wrap p').first().textContent().catch(() => '');
        lines.push('- Fingerprints: ' + (head || '').trim());
        const go = page.locator('#ss-fp-go');
        if (await go.isEnabled().catch(() => false)) {
          await go.click();
          await page.waitForFunction(() => document.getElementById('ss-fp-go').dataset.done === '1', null, { timeout: 900000 }).catch(() => {});
          const log = await page.$$eval('#ss-fp-log li', x => x.map(l => l.textContent));
          lines.push('    - ' + ((await page.locator('#ss-fp-st').textContent().catch(() => '')) || ''));
          log.filter(t => /⚠|✗/.test(t)).forEach(t => lines.push('    - ' + t));
        }
      } catch (e) { lines.push('- Fingerprints error: ' + e.message.split('\n')[0]); }
    }
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/report.md', lines.join('\n') + '\n');
  await browser.close();
})();
