// WATCHMAN (owner 21.11.2026): every 15 minutes — is the site alive? Home, catalog, a product, cart, the shop API.
// Each check: answer code, time, and a marker that the real page came (not an error page). The hosting's anti-bot page
// counts as «alive» (the server answered). Exit code 1 = something is down → the workflow opens a GitHub issue (the
// owner gets an e-mail from GitHub); when everything is back, it closes it. Output: watch.json for the workflow.
'use strict';
const fs = require('fs');
const BASE = 'https://solid-sound.net';
const UA = 'Mozilla/5.0 (compatible; SolidSoundWatch/1.0; +https://solid-sound.net)';
const CHECKS = [
  ['Главная', '/en/', /ss-app|ssg|Solid Sound/i],
  ['Каталог', '/en/all-products/', /\/product\//],
  ['Товар', '/product/infinity/', /add-to-cart|single_add_to_cart/],
  ['Корзина', '/en/cart/', /woocommerce|wc-block-cart/i],
  ['Магазин (API)', '/wp-json/wc/store/v1/products?per_page=1', /"prices"/],
];
async function one([name, p, marker]) {
  for (let i = 0; i < 2; i++) {
    const t0 = Date.now();
    try {
      const r = await fetch(BASE + p, { headers: { 'User-Agent': UA }, signal: AbortSignal.timeout(30000), redirect: 'follow' });
      const t = await r.text(); const ms = Date.now() - t0;
      if (/Захищена сторінка/.test(t)) return { name, ok: true, ms, note: 'защита хостинга (сервер жив)' };
      if (r.status < 500 && marker.test(t)) return { name, ok: true, ms, note: 'HTTP ' + r.status + (ms > 8000 ? ' — медленно' : '') };
      if (i) return { name, ok: false, ms, note: 'HTTP ' + r.status + (r.status < 500 ? ' — пришла не та страница' : ' — ошибка сервера') };
    } catch (e) { if (i) return { name, ok: false, ms: Date.now() - t0, note: /timeout|abort/i.test(e.message) ? 'не ответил за 30 с' : e.message.slice(0, 80) }; }
    await new Promise(r => setTimeout(r, 45000)); // one retry after 45 s: short blips are not alarms
  }
}
(async () => {
  const res = []; for (const c of CHECKS) res.push(await one(c));
  const down = res.filter(r => !r.ok);
  fs.writeFileSync('watch.json', JSON.stringify({ at: new Date().toISOString(), down: down.length, res }, null, 1));
  console.log(res.map(r => `${r.ok ? '✅' : '❌'} ${r.name}: ${r.note}, ${(r.ms / 1000).toFixed(1)} с`).join('\n'));
  process.exitCode = down.length ? 1 : 0;
})();
