// Probe (21.11): what makes the buying pages slow — server time, requests, weight, heaviest files, scripts.
// Pages: product, cart (with 1 item), checkout. Phone = Samsung A31. Read-only, the cart is emptied at the end.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-weight'; fs.mkdirSync(OUT, { recursive: true });
const SITE = 'https://solid-sound.net';
const log = []; const L = (...a) => { const s = a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '); log.push(s); console.log(s); };
const kb = n => Math.round(n / 1024) + ' КБ';
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await b.newContext({ viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, locale: 'en-US', userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' });
  await ctx.addCookies([{ name: 'ss_consent', value: 'essential', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage();
  const measure = async (path, label, repeat = 2) => {
    for (let k = 0; k < repeat; k++) {
      const reqs = [];
      const onRes = async r => { try { const h = await r.allHeaders(); const len = +(h['content-length'] || 0) || (await r.body().catch(() => Buffer.alloc(0))).length; reqs.push({ url: r.url(), type: r.request().resourceType(), size: len, st: r.status() }); } catch (e) {} };
      p.on('response', onRes);
      await p.goto(SITE + path, { waitUntil: 'load', timeout: 120000 }).catch(() => {});
      await p.waitForTimeout(4000);
      p.off('response', onRes);
      const t = await p.evaluate(() => { const n = performance.getEntriesByType('navigation')[0] || {}; const fcp = (performance.getEntriesByName('first-contentful-paint')[0] || {}).startTime; return { ttfb: Math.round(n.responseStart), dcl: Math.round(n.domContentLoadedEventEnd), load: Math.round(n.loadEventEnd), fcp: Math.round(fcp || 0), html: n.transferSize, dom: document.getElementsByTagName('*').length }; });
      const tot = reqs.reduce((s, r) => s + r.size, 0);
      L(`\n${label} ${path} (заход ${k + 1}): сервер ${t.ttfb} мс | первая отрисовка ${t.fcp} мс | DOM готов ${t.dcl} мс | всё загружено ${t.load} мс | запросов ${reqs.length} | вес ${kb(tot)} | HTML ${kb(t.html || 0)} | элементов ${t.dom}`);
      if (k === repeat - 1) {
        const byType = {}; reqs.forEach(r => { byType[r.type] = byType[r.type] || [0, 0]; byType[r.type][0]++; byType[r.type][1] += r.size; });
        L('  по типам:', Object.entries(byType).map(([k, v]) => `${k} ${v[0]} шт / ${kb(v[1])}`).join(' · '));
        const byHost = {}; reqs.forEach(r => { const h = new URL(r.url).host; byHost[h] = (byHost[h] || 0) + 1; });
        L('  по сайтам:', Object.entries(byHost).sort((a, b) => b[1] - a[1]).map(([h, n]) => `${h} ${n}`).join(' · '));
        L('  самые тяжёлые:'); reqs.sort((a, b) => b.size - a.size).slice(0, 15).forEach(r => L(`    ${kb(r.size).padStart(7)} ${r.type.padEnd(10)} ${r.url.replace(SITE, '').slice(0, 130)}`));
        L('  скрипты:'); reqs.filter(r => r.type === 'script').forEach(r => L(`    ${kb(r.size).padStart(7)} ${r.url.replace(SITE, '').split('?')[0].slice(0, 120)}`));
        L('  стили:'); reqs.filter(r => r.type === 'stylesheet').forEach(r => L(`    ${kb(r.size).padStart(7)} ${r.url.replace(SITE, '').split('?')[0].slice(0, 120)}`));
      }
    }
  };
  await measure('/product/infinity/', 'ТОВАР');
  const add = await p.$('.single_add_to_cart_button'); if (add) { await add.scrollIntoViewIfNeeded().catch(() => {}); await add.click({ timeout: 8000 }).catch(() => {}); await p.waitForTimeout(4000); }
  await measure('/en/cart/', 'КОРЗИНА');
  await measure('/en/checkout/', 'ОФОРМЛЕНИЕ');
  await p.goto(SITE + '/en/cart/', { waitUntil: 'domcontentloaded' }).catch(() => {});
  for (let i = 0; i < 5; i++) { try { const r = await p.$('.cart_item a.remove'); if (!r) break; await Promise.all([p.waitForLoadState('domcontentloaded').catch(() => {}), r.click()]); await p.waitForTimeout(3000); } catch (e) { await p.waitForTimeout(3000); } }
  fs.writeFileSync(OUT + '/weight.txt', log.join('\n') + '\n'); await b.close();
})();
