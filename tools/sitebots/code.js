// CODE ANALYST (owner 21.11.2026: «боты, которые ищут мёртвый код и анализируют весь код»). No network, reads the repo.
// Checks: dead PHP / JS functions, modules not loaded, assets never used, our CSS classes nobody uses, globals shadowed by
// local variables (the 21.11 bug: a local `var SSP` hid the player → covers did not play), project rules (setInterval,
// console.log, scripts without defer), syntax (php -l, node --check), copy-paste blocks, the biggest files.
'use strict';
const fs = require('fs'); const path = require('path'); const { execFileSync } = require('child_process');
const ROOT = path.resolve(__dirname, '../..');
const SRC = path.join(ROOT, 'plugin/solid-sound-custom/src');
const rel = f => path.relative(ROOT, f);
const walk = (d, out = []) => { for (const n of fs.readdirSync(d)) { const f = path.join(d, n); const s = fs.statSync(f);
  if (s.isDirectory()) { if (!/^(vendor|node_modules|i18n)$/.test(n)) walk(f, out); } else out.push(f); } return out; };
const lineOf = (t, i) => t.slice(0, i).split('\n').length;
const hideMail = s => String(s).replace(/[\w.+-]+@[\w-]+\.[\w.]+/g, '…@…');

module.exports = async function code() {
  const files = walk(SRC).filter(f => /\.(php|js|css|html|mjs)$/.test(f));
  const text = Object.fromEntries(files.map(f => [f, fs.readFileSync(f, 'utf8')]));
  // the plugin bootstrap and the build script also reference things
  for (const f of ['plugin/solid-sound-custom/solid-sound-custom.php', 'tools/build.mjs']) { const p = path.join(ROOT, f); if (fs.existsSync(p)) text[p] = fs.readFileSync(p, 'utf8'); }
  const all = Object.values(text).join('\n');
  const count = (re) => (all.match(re) || []).length;
  const esc = s => s.replace(/[$]/g, '\\$');
  const F = { deadPhp: [], deadJs: [], modules: [], assets: [], css: [], shadow: [], interval: [], consoleLog: [], noDefer: [], syntax: [], dup: [], big: [] };

  // 1. PHP functions defined but never mentioned again (calls, hooks by name, function_exists)
  for (const [f, t] of Object.entries(text)) if (f.endsWith('.php')) {
    for (const m of t.matchAll(/^\s*function\s+([a-zA-Z_]\w*)\s*\(/gm)) {
      const n = m[1]; if (t.lastIndexOf('<script', m.index) > t.lastIndexOf('</script', m.index)) continue; /* JS inside a PHP file */ if (count(new RegExp('\\b' + n + '\\b', 'g')) <= 1) F.deadPhp.push([rel(f), lineOf(t, m.index), n]);
    }
  }
  // 2. JS named functions used only where they are defined (also inside <script> in PHP)
  for (const [f, t] of Object.entries(text)) if (/\.(js|php|html)$/.test(f)) {
    for (const m of t.matchAll(/function\s+([a-zA-Z_$][\w$]*)\s*\(/g)) {
      if (f.endsWith('.php') && /^\s*function/.test(t.slice(t.lastIndexOf('\n', m.index) + 1, m.index + 9))) continue; // PHP function, done above
      const n = m[1]; if (n.length < 3) continue;
      const inFile = (t.match(new RegExp('(^|[^\\w$.])' + esc(n) + '(?![\\w$])', 'g')) || []).length;
      if (inFile <= 1 && count(new RegExp('(^|[^\\w$])' + esc(n) + '(?![\\w$])', 'g')) <= 1) F.deadJs.push([rel(f), lineOf(t, m.index), n]);
    }
  }
  // 3. modules that are never loaded
  for (const f of fs.readdirSync(path.join(SRC, 'modules'))) if (f.endsWith('.php') && count(new RegExp('[/\'"]' + f.replace('.', '\\.'), 'g')) === 0) F.modules.push(f);
  // 4. assets in the build list that nothing loads with ss_asset('name')
  const build = text[path.join(ROOT, 'tools/build.mjs')] || '';
  for (const m of build.matchAll(/\[\s*'([a-z0-9-]+)',\s*'(js|css)'/g)) {
    const n = m[1]; const used = new RegExp("['\"]" + n + "['\"]", 'g'); const c = (all.replace(build, '').match(used) || []).length;
    if (!c) F.assets.push(n);
  }
  // 5. our own CSS classes (ss-, ssw-, ssx-, ssg-, ssp-, ssf-, ssh-) that only appear in selectors
  const classes = new Set(); for (const m of all.matchAll(/\.((?:ss|ssw|ssx|ssg|ssp|ssf|ssh)-[a-z0-9-]+)/g)) classes.add(m[1]);
  for (const c of classes) { const n = count(new RegExp('(^|[^.\\w-])' + c + '(?![\\w-])', 'g')); if (!n) F.css.push(c); }
  // 6. a global (window.X = …) hidden by a local `var/let/const X` in another script
  const globals = new Set(); for (const m of all.matchAll(/window\.([A-Za-z_$][\w$]{2,})\s*=(?!=)/g)) globals.add(m[1]);
  for (const g of globals) for (const [f, t] of Object.entries(text)) {
    for (const m of t.matchAll(new RegExp('\\b(var|let|const)\\s+' + esc(g) + '\\b', 'g'))) {
      const own = new RegExp('window\\.' + esc(g) + '\\s*=(?!=)').test(t); // the file that creates the global may keep a local copy
      const usesGlobal = new RegExp('window\\.' + esc(g) + '\\b').test(t) || new RegExp('\\b' + esc(g) + '\\.(play|toggle|state|open|close|go)\\b').test(t);
      if (!own && usesGlobal) F.shadow.push([rel(f), lineOf(t, m.index), g]);
    }
  }
  // 7. project rules
  for (const [f, t] of Object.entries(text)) {
    for (const m of t.matchAll(/setInterval\s*\(/g)) F.interval.push([rel(f), lineOf(t, m.index), hideMail(t.slice(m.index, m.index + 90).split('\n')[0])]);
    for (const m of t.matchAll(/console\.log\s*\(/g)) F.consoleLog.push([rel(f), lineOf(t, m.index)]);
    if (f.endsWith('.php')) for (const m of t.matchAll(/<script\s+src=[^>]*>/g)) if (!/\b(defer|async|type=["']module)/.test(m[0])) F.noDefer.push([rel(f), lineOf(t, m.index)]);
  }
  // 8. syntax
  for (const f of Object.keys(text)) {
    try { if (f.endsWith('.php')) execFileSync('php', ['-l', f], { stdio: 'pipe' }); else if (/\.(js|mjs)$/.test(f) && !f.endsWith('build.mjs')) execFileSync(process.execPath, ['--check', f], { stdio: 'pipe' }); }
    catch (e) { F.syntax.push([rel(f), hideMail(String(e.stderr || e.message).split('\n').find(l => l.trim()) || '')]); }
  }
  // 9. copy-paste: identical 6-line blocks (normalized) in different places
  const seen = new Map();
  for (const [f, t] of Object.entries(text)) {
    const L = t.split('\n').map(s => s.trim());
    for (let i = 0; i + 6 <= L.length; i++) {
      const blk = L.slice(i, i + 6); if (blk.join('').length < 240 || blk.some(s => !s)) continue;
      const k = blk.join('\n'); const prev = seen.get(k);
      if (prev && !(prev[0] === f && i - prev[1] < 6)) { F.dup.push([rel(prev[0]), prev[1] + 1, rel(f), i + 1]); i += 5; } else if (!prev) seen.set(k, [f, i]);
    }
  }
  // 10. biggest files
  F.big = files.map(f => [rel(f), Math.round(fs.statSync(f).size / 1024), text[f].split('\n').length]).sort((a, b) => b[1] - a[1]).slice(0, 8);

  const n = k => F[k].length;
  const list = (rows, fmt, max = 25) => rows.slice(0, max).map(r => '- ' + fmt(r)).join('\n') + (rows.length > max ? `\n- … и ещё ${rows.length - max}` : '');
  let md = `## 🧹 Код: мёртвый код и ошибки\n\nПроверено файлов: **${files.length}** (${Math.round(all.length / 1024)} КБ).\n\n`;
  md += '| Проверка | Найдено |\n|---|---|\n' + [
    ['🔴 Ошибки синтаксиса', 'syntax'], ['🔴 Глобальное имя перекрыто (как баг с плеером 21.11)', 'shadow'], ['🟠 Модули, которые не подключены', 'modules'],
    ['🟠 Файлы сборки, которые никто не грузит', 'assets'], ['🟡 PHP-функции без вызовов', 'deadPhp'], ['🟡 JS-функции без вызовов', 'deadJs'],
    ['🟡 Наши CSS-классы, которых нет в разметке', 'css'], ['🟡 setInterval (правило: только MutationObserver/rAF)', 'interval'],
    ['🟡 Скрипты без defer', 'noDefer'], ['⚪ console.log в коде', 'consoleLog'], ['⚪ Повторы кода (6+ одинаковых строк)', 'dup']].map(([t, k]) => `| ${t} | ${n(k)} |`).join('\n') + '\n\n';
  if (n('syntax')) md += '### 🔴 Ошибки синтаксиса\n' + list(F.syntax, r => `\`${r[0]}\`: ${r[1]}`) + '\n\n';
  if (n('shadow')) md += '### 🔴 Перекрытые глобальные имена\nЛокальная переменная с тем же именем прячет общий объект — код думает, что работает с плеером/меню, а работает с другим.\n' + list(F.shadow, r => `\`${r[0]}:${r[1]}\` — \`${r[2]}\``) + '\n\n';
  if (n('modules')) md += '### 🟠 Модули не подключены (мёртвые файлы)\n' + list(F.modules, r => '`modules/' + r + '`') + '\n\n';
  if (n('assets')) md += '### 🟠 Файлы сборки без использования\n' + list(F.assets, r => '`' + r + '`') + '\n\n';
  if (n('deadPhp')) md += '### 🟡 PHP-функции, которые нигде не вызываются\n' + list(F.deadPhp, r => `\`${r[0]}:${r[1]}\` — ${r[2]}()`) + '\n\n';
  if (n('deadJs')) md += '### 🟡 JS-функции, которые нигде не вызываются\n' + list(F.deadJs, r => `\`${r[0]}:${r[1]}\` — ${r[2]}()`) + '\n\n';
  if (n('css')) md += '### 🟡 CSS-классы без разметки (кандидаты на удаление — проверить, не ставит ли их чужой код)\n' + list(F.css, r => '`.' + r + '`', 60) + '\n\n';
  if (n('interval')) md += '### 🟡 setInterval\n' + list(F.interval, r => `\`${r[0]}:${r[1]}\` — \`${r[2].replace(/`/g, "'")}\``) + '\n\n';
  if (n('noDefer')) md += '### 🟡 Скрипты без defer\n' + list(F.noDefer, r => `\`${r[0]}:${r[1]}\``) + '\n\n';
  if (n('dup')) md += '### ⚪ Повторы кода\n' + list(F.dup, r => `\`${r[0]}:${r[1]}\` = \`${r[2]}:${r[3]}\``, 15) + '\n\n';
  md += '### Самые большие файлы\n| Файл | КБ | Строк |\n|---|---|---|\n' + F.big.map(r => `| \`${r[0]}\` | ${r[1]} | ${r[2]} |`).join('\n') + '\n';
  return { md, metrics: Object.fromEntries(Object.keys(F).filter(k => k !== 'big').map(k => ['code_' + k, n(k)])), alerts: n('syntax') + n('shadow') ? [`Код: ${n('syntax')} ошибок синтаксиса, ${n('shadow')} перекрытых имён`] : [] };
};
if (require.main === module) module.exports().then(r => { console.log(r.md); console.error(JSON.stringify(r.metrics)); });
