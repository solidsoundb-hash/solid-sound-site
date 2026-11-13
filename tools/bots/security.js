// Security bot: outdated plugins (compared with WordPress.org, free) and known vulnerabilities (WPScan, free token
// in secret WPSCAN_TOKEN — optional). Installed versions: tools/bots/plugins.json (refresh: ask Claude, it reads them
// from the site), and live readme.txt "Stable tag" where the host allows it.
'use strict';
const fs = require('fs');
const path = require('path');
const { metrics, BASE, get, esc } = require('./lib');

const cmp = (a, b) => { const x = String(a).split('.').map(Number), y = String(b).split('.').map(Number); for (let i = 0; i < Math.max(x.length, y.length); i++) { const d = (x[i] || 0) - (y[i] || 0); if (d) return d; } return 0; };

module.exports = async function security() {
  const L = ['## 🔐 Безопасность: плагины', ''];
  const cfg = JSON.parse(fs.readFileSync(path.join(__dirname, 'plugins.json'), 'utf8'));
  const old = [], vul = [], unknown = [];
  for (const p of cfg.plugins) {
    if (!p.slug) { unknown.push(p.name); continue; }
    let have = p.version;
    const rd = await get(`${BASE}/wp-content/plugins/${p.slug}/readme.txt`, { tries: 1 });
    const live = rd.status === 200 && (rd.text.match(/Stable tag:\s*([\d.]+)/i) || [])[1];
    if (live && cmp(live, have) > 0) have = live;
    const info = await get(`https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=${p.slug}&request[fields][versions]=0`, { tries: 2 });
    let latest = null; try { latest = info.json().version; } catch (e) {}
    if (latest && cmp(latest, have) > 0) old.push({ ...p, have, latest });
    if (process.env.WPSCAN_TOKEN) {
      const w = await get(`https://wpscan.com/api/v3/plugins/${p.slug}`, { headers: { Authorization: 'Token token=' + process.env.WPSCAN_TOKEN }, tries: 1 });
      try { const v = (w.json()[p.slug] || {}).vulnerabilities || []; v.filter(x => !x.fixed_in || cmp(x.fixed_in, have) > 0).forEach(x => vul.push({ name: p.name, title: x.title, fixed: x.fixed_in })); } catch (e) {}
    }
  }
  Object.assign(metrics, { plugins_outdated: old.length, plugins_vulnerable: vul.length });
  if (vul.length) { L.push('### 🔴 Известные уязвимости — обновить срочно', ''); vul.forEach(v => L.push(`- **${esc(v.name)}**: ${esc(v.title)}${v.fixed ? ` (исправлено в ${v.fixed})` : ' (исправления нет — подумать о замене)'}`)); L.push(''); }
  else L.push(process.env.WPSCAN_TOKEN ? '✅ Известных уязвимостей в установленных версиях нет.' : '⏸ Проверка уязвимостей не подключена (нужен бесплатный ключ WPScan — `docs/bots.md`). Ниже — только устаревшие версии.', '');
  if (old.length) { L.push('**Устарели (есть новая версия):**', '', '| Плагин | Стоит | Новая |', '|---|---|---|'); old.forEach(p => L.push(`| ${esc(p.name)} | ${p.have} | ${p.latest} |`)); L.push('', '_Обновлять по одному, после бэкапа; после обновления — короткая проверка ботом._'); }
  else L.push('✅ Все плагины с WordPress.org — последних версий.');
  if (unknown.length) L.push('', `Платные/не с WordPress.org (проверять вручную в админке): ${unknown.join(', ')}.`);
  L.push('', `_Список версий: tools/bots/plugins.json, обновлён ${cfg.updated}._`);
  return L.join('\n') + '\n';
};
