// WEB SECURITY (owner 21.11.2026), from the outside, read-only: protection headers, HTTPS, service files reachable from
// the web (.git, debug.log, wp-config copies, backups), folder listing, user names revealed (author pages, REST users),
// xmlrpc, WordPress version shown. (Outdated plugins: tools/bots/security.js.)
'use strict';
const { BASE, get, sleep } = require('../bots/lib');
const PROBE = [
  ['/.git/HEAD', /ref:/, 'папка .git открыта — можно скачать весь код'], ['/.env', /=/, 'файл .env открыт'],
  ['/wp-content/debug.log', /PHP (Warning|Notice|Fatal)|Stack trace/i, 'debug.log открыт — видны пути и ошибки'],
  ['/wp-config.php.bak', /DB_NAME|DB_PASSWORD/, 'копия wp-config открыта — ПАРОЛЬ БАЗЫ'], ['/wp-config.php~', /DB_NAME/, 'копия wp-config открыта'], ['/wp-config.old', /DB_NAME/, 'копия wp-config открыта'],
  ['/backup.zip', /^PK/, 'архив backup.zip открыт'], ['/wp-content/uploads/', /Index of \//i, 'список файлов папки uploads открыт'],
  ['/wp-content/plugins/', /Index of \//i, 'список плагинов открыт'], ['/phpinfo.php', /PHP Version/i, 'phpinfo открыт'],
  ['/readme.html', /WordPress/i, 'readme.html показывает, что это WordPress (мелочь)'],
];
module.exports = async function websec() {
  const F = [];
  const home = await fetch(BASE + '/en/', { headers: { 'User-Agent': 'Mozilla/5.0 SolidSoundBots/1.0' }, signal: AbortSignal.timeout(60000) }).catch(e => null);
  if (!home) return { md: '## 🛡 Безопасность сайта снаружи\n\n⏸ Сайт не ответил.\n', metrics: {}, alerts: [] };
  const h = n => home.headers.get(n) || '';
  const H = [['strict-transport-security', 'HSTS (только HTTPS)', '🟠'], ['x-content-type-options', 'X-Content-Type-Options', '🟡'], ['x-frame-options', 'защита от встраивания в чужой сайт (X-Frame-Options)', '🟡'],
    ['referrer-policy', 'Referrer-Policy', '⚪'], ['content-security-policy', 'Content-Security-Policy', '⚪'], ['permissions-policy', 'Permissions-Policy', '⚪']];
  for (const [k, t, s] of H) if (!h(k) && !(k === 'x-frame-options' && /frame-ancestors/.test(h('content-security-policy')))) F.push([s, 'нет заголовка ' + t]);
  if (/PHP\/\d/.test(h('x-powered-by'))) F.push(['🟡', 'сервер показывает версию PHP: ' + h('x-powered-by')]);
  const http = await fetch('http://solid-sound.net/', { redirect: 'manual', signal: AbortSignal.timeout(30000) }).catch(() => null);
  if (http && !(http.status >= 300 && http.status < 400 && /^https:/.test(http.headers.get('location') || ''))) F.push(['🟠', 'http:// не переводит на https:// (код ' + http.status + ')']);
  const body = await home.text(); const gen = body.match(/<meta name="generator" content="WordPress ([\d.]+)/); if (gen) F.push(['🟡', 'на странице видна версия WordPress ' + gen[1]]);
  for (const [p, re, t] of PROBE) { const r = await get(BASE + p, { tries: 2 }); await sleep(1200);
    if (r.status === 200 && re.test(r.text)) F.push([/ПАРОЛЬ|\.git|\.env|backup/.test(t) ? '🔴' : /мелочь/.test(t) ? '⚪' : '🟠', t + ' (`' + p + '`)']); }
  const users = await get(BASE + '/wp-json/wp/v2/users?per_page=100');
  if (users.status === 200) { try { const u = users.json(); if (u.length) F.push(['🟠', `список пользователей открыт через /wp-json/wp/v2/users (${u.length} имён; сами имена в отчёт не пишу — репозиторий публичный) — облегчает подбор паролей`]); } catch (e) {} }
  const au = await fetch(BASE + '/?author=1', { redirect: 'manual', headers: { 'User-Agent': 'Mozilla/5.0 SolidSoundBots/1.0' } }).catch(() => null);
  const loc = au && au.headers.get('location') || ''; if (/\/author\/[^/]+/.test(loc)) F.push(['🟡', '/?author=1 раскрывает логин автора (адрес /author/…)']);
  const x = await fetch(BASE + '/xmlrpc.php', { method: 'POST', body: '<?xml version="1.0"?><methodCall><methodName>system.listMethods</methodName></methodCall>', headers: { 'Content-Type': 'text/xml' } }).catch(() => null);
  if (x && x.status === 200 && /methodResponse/.test(await x.text())) F.push(['🟠', 'xmlrpc.php включён — через него подбирают пароли (если Jetpack не нужен — выключить)']);
  const order = { '🔴': 0, '🟠': 1, '🟡': 2, '⚪': 3 }; F.sort((a, b) => order[a[0]] - order[b[0]]);
  const md = '## 🛡 Безопасность сайта снаружи\n\nТо, что видит любой посетитель или взломщик (без входа). Устаревшие плагины — в недельном отчёте `reports/weekly`.\n\n' +
    (F.length ? F.map(f => `- ${f[0]} ${f[1]}`).join('\n') : '✅ Проблем не найдено') + '\n';
  const red = F.filter(f => f[0] === '🔴').length;
  return { md, metrics: { websec_red: red, websec_orange: F.filter(f => f[0] === '🟠').length, websec_all: F.length }, alerts: red ? ['Безопасность: ' + F.filter(f => f[0] === '🔴').map(f => f[1]).join('; ')] : [] };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
