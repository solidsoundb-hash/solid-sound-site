// Robot "creator area speed" (owner 19.11.2026: «всё, что касается авторов, косо-криво грузится… облегчи всё»).
// Logs in as the test admin (or vendor), opens the creator pages with ?ss_prof=1 and writes, per page:
// server time / DB queries / slowest plugins (speed probe JSON), browser timings, how much the page jumps (CLS),
// what the browser downloads (scripts / styles by size and by plugin). Read-only: only the login form is sent.
// Output: checks/sm-speed/report.md + prof-*.json (no personal data inside).
const { chromium } = require('playwright');
const fs = require('fs');
const OUT = process.env.SM_OUT || 'checks/sm-speed';
const BASE = 'https://solid-sound.net';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const PAGES = (process.env.SM_PAGES || '/en/store-manager/,/en/store-manager/ss-add-product/,/en/store-manager/ss-products/,/en/store-manager/ss-earnings/,/en/sell/,/en/vendor-register/').split(',');
const kb = (b) => Math.round(b / 1024);

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const md = ['# Кабинет автора — скорость', '', `Снято: ${new Date().toISOString()}`, ''];
  const who = process.env.TEST_VENDOR_USER ? { n: 'vendor', u: process.env.TEST_VENDOR_USER, p: process.env.TEST_VENDOR_PASS } : { n: 'admin', u: process.env.TEST_ADMIN_USER, p: process.env.TEST_ADMIN_PASS };
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const dev of [{ n: 'ПК', o: { viewport: { width: 1440, height: 900 } } }, { n: 'телефон', o: { viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true } }]) {
    const ctx = await browser.newContext({ ...dev.o, userAgent: dev.n === 'телефон' ? 'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' });
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    await ctx.addInitScript(() => { window.__cls = 0; window.__shifts = []; try { new PerformanceObserver((l) => { for (const e of l.getEntries()) if (!e.hadRecentInput) { window.__cls += e.value; if (e.value > 0.01) window.__shifts.push({ v: +e.value.toFixed(3), t: Math.round(e.startTime), who: (e.sources || []).map(s => s.node ? (s.node.id ? '#' + s.node.id : (s.node.className && s.node.className.baseVal === undefined ? '.' + String(s.node.className).split(' ')[0] : s.node.nodeName)) : '').join(' ') }); } }).observe({ type: 'layout-shift', buffered: true }); } catch (e) {} });
    const page = await ctx.newPage();
    await page.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 90000 });
    await page.fill('#user_login', who.u || ''); await page.fill('#user_pass', who.p || '');
    await Promise.all([page.waitForNavigation({ timeout: 90000 }).catch(() => {}), page.click('#wp-submit')]);
    await sleep(2000);
    md.push(`## ${dev.n} (${who.n})`, '');
    for (const p of PAGES) {
      const reqs = [];
      const onResp = async (r) => { try { const t = r.request().resourceType(); if (!['script', 'stylesheet', 'font', 'image', 'xhr', 'fetch', 'document'].includes(t)) return; const len = +(r.headers()['content-length'] || 0) || (await r.body().catch(() => Buffer.alloc(0))).length; reqs.push({ t, u: r.url(), len }); } catch (e) {} };
      page.on('response', onResp);
      const url = BASE + p + (p.includes('?') ? '&' : '?') + 'ss_prof=1&r=' + Date.now();
      await page.goto(url, { waitUntil: 'load', timeout: 120000 }).catch(() => {});
      await sleep(4000);
      page.off('response', onResp);
      const t = await page.evaluate(() => { const n = performance.getEntriesByType('navigation')[0] || {}; const lt = performance.getEntriesByType('longtask') || [];
        return { ttfb: Math.round(n.responseStart), dcl: Math.round(n.domContentLoadedEventEnd), load: Math.round(n.loadEventEnd), html: Math.round((n.transferSize || 0) / 1024), cls: +window.__cls.toFixed(3), shifts: window.__shifts.slice(0, 6), dom: document.getElementsByTagName('*').length,
          bottom: [...document.body.children].filter(e => e.offsetHeight > 30 && getComputedStyle(e).position !== 'fixed').slice(-4).map(e => (e.id ? '#' + e.id : e.tagName.toLowerCase() + '.' + String(e.className).split(' ')[0]) + ' ' + e.offsetHeight + 'px') }; }).catch(e => ({ err: e.message }));
      const slug = p.replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '');
      let prof = null; try { const r = await page.request.get(BASE + '/wp-content/uploads/ss-prof/' + slug + '.json?r=' + Date.now()); if (r.ok()) { prof = await r.json(); fs.writeFileSync(`${OUT}/prof-${slug}.json`, JSON.stringify(prof, null, 1)); } } catch (e) {}
      const by = (k) => reqs.filter(r => r.t === k);
      const sum = (a) => kb(a.reduce((s, r) => s + r.len, 0));
      const origin = (u) => { const m = u.match(/\/wp-content\/(plugins|themes)\/([^/]+)/) || u.match(/\/wp-includes\//) && ['', '', 'wp-includes']; if (m) return m[2]; try { return new URL(u).hostname; } catch (e) { return '?'; } };
      const grp = {}; reqs.filter(r => r.t === 'script' || r.t === 'stylesheet').forEach(r => { const o = origin(r.u); grp[o] = grp[o] || { n: 0, kb: 0 }; grp[o].n++; grp[o].kb += r.len / 1024; });
      md.push(`### ${p}`, '',
        `Сервер: ${prof ? prof.total_ms + ' мс, запросов к базе ' + prof.queries + ', память ' + prof.mem_mb + ' МБ' : '—'} · Браузер: первый байт ${t.ttfb} мс, DOM ${t.dcl} мс, загрузка ${t.load} мс · HTML ${t.html} КБ · элементов ${t.dom}`,
        `Скрипты: ${by('script').length} шт / ${sum(by('script'))} КБ · Стили: ${by('stylesheet').length} шт / ${sum(by('stylesheet'))} КБ · Шрифты ${sum(by('font'))} КБ · Картинки ${sum(by('image'))} КБ · Запросы xhr ${by('xhr').length + by('fetch').length}`,
        `Прыжки (CLS): ${t.cls} ${JSON.stringify(t.shifts)} · Внизу страницы: ${JSON.stringify(t.bottom)}`, '',
        'Скрипты и стили по плагинам: ' + Object.entries(grp).sort((a, b) => b[1].kb - a[1].kb).map(([k, v]) => `${k} ${v.n}/${Math.round(v.kb)}КБ`).join(' · '), '');
      if (prof) md.push('Сервер по плагинам (мс): ' + Object.entries(prof.plugins || {}).slice(0, 12).map(([k, v]) => `${k} ${v}`).join(' · '),
        'Запросы к базе по плагинам: ' + Object.entries(prof.queries_by || {}).slice(0, 12).map(([k, v]) => `${k} ${v}`).join(' · '),
        'Тяжёлые хуки (мс): ' + Object.entries(prof.hooks || {}).slice(0, 12).map(([k, v]) => `${k} ${v}`).join(' · '), '');
      md.push('Самые тяжёлые файлы: ' + reqs.filter(r => r.t === 'script' || r.t === 'stylesheet').sort((a, b) => b.len - a.len).slice(0, 15).map(r => `${r.u.replace(BASE, '').replace(/\?.*/, '')} ${kb(r.len)}КБ`).join(' · '), '');
      if (dev.n === 'ПК') await page.screenshot({ path: `${OUT}/${slug}.jpg`, type: 'jpeg', quality: 55 }).catch(() => {});
    }
    await ctx.close();
  }
  await browser.close();
  fs.writeFileSync(`${OUT}/report.md`, md.join('\n') + '\n');
  console.log(md.join('\n'));
})();
