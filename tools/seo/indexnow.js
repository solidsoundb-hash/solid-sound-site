// IndexNow (role «SEO», 28.11.2026): send every address of the sitemap to Bing/Yandex/Seznam via api.indexnow.org.
// The key is public by design; the site serves it at /<key>.txt (plugin modules/seo.php). Read-only for the site.
// Runs after seo-weekly (Monday) and by a line in .github/seo.txt. Result: reports/seo/indexnow.md
'use strict';
const fs = require('fs');
const BASE = 'https://solid-sound.net', HOST = 'solid-sound.net', KEY = '7e6856bc252d7ea66e6b2ac06afd1b4c';
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundBots/1.0';
const sleep = ms => new Promise(r => setTimeout(r, ms));
async function get(u) {
  for (let i = 0; i < 4; i++) {
    try { const r = await fetch(u, { headers: { 'User-Agent': UA }, signal: AbortSignal.timeout(45000) }); const t = await r.text(); if (r.status === 429 || /Перевірка безпеки/.test(t.slice(0, 3000))) { await sleep(10000 * (i + 1)); continue; } return { st: r.status, t }; } catch (e) { await sleep(5000); }
  }
  return { st: 0, t: '' };
}
const locs = t => (t.match(/<loc>([^<]+)<\/loc>/g) || []).map(x => x.replace(/<\/?loc>/g, '').trim());
(async () => {
  const out = [];
  const kf = await get(BASE + '/' + KEY + '.txt');
  out.push('Ключ на сайте: ' + (kf.st === 200 && kf.t.trim() === KEY ? '✅' : '❌ (' + kf.st + ') — IndexNow не примет адреса'));
  const idx = await get(BASE + '/wp-sitemap.xml');
  let urls = [];
  for (const sm of locs(idx.t)) { const r = await get(sm); urls.push(...locs(r.t)); await sleep(2000); }
  urls = [...new Set(urls)].filter(u => u.startsWith(BASE));
  out.push('Адресов в карте сайта: ' + urls.length);
  if (urls.length && kf.st === 200) {
    const r = await fetch('https://api.indexnow.org/indexnow', { method: 'POST', headers: { 'Content-Type': 'application/json; charset=utf-8' }, body: JSON.stringify({ host: HOST, key: KEY, keyLocation: BASE + '/' + KEY + '.txt', urlList: urls.slice(0, 10000) }) }).catch(e => ({ status: 'ошибка ' + e.message }));
    out.push('Ответ IndexNow: ' + r.status + ' (200/202 — принято; 403 — ключ не найден; 422 — адреса не с этого сайта; 429 — слишком часто)');
  }
  fs.mkdirSync('reports/seo', { recursive: true });
  fs.writeFileSync('reports/seo/indexnow.md', '# IndexNow — ' + new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC\n\n' + out.map(x => '- ' + x).join('\n') + '\n');
  console.log(out.join('\n'));
})();
