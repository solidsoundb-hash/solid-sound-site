// Nightly purchase robot — report (25.11, «рой роботов у каждой роли», tester's 🆕 robot from docs/team/bots.md).
// Reads one or two tries of tools/probe/buy.js (argv: dirs). A check counts as broken only if it failed in EVERY try
// (hosting hiccups/429 give one-off fails). Writes reports/tester/buy.md, keeps reports/tester/memory.json
// (per check: last result, nights broken in a row, last time OK) and prints BROKEN=<list> for the workflow (GitHub issue).
const fs = require('fs'), path = require('path');
const dirs = process.argv.slice(2); const OUT = 'reports/tester'; fs.mkdirSync(OUT, { recursive: true });
const tries = dirs.map(d => { try { return JSON.parse(fs.readFileSync(path.join(d, 'buy.json'), 'utf8')).checks; } catch (e) { return null; } }).filter(Boolean);
let mem = {}; try { mem = JSON.parse(fs.readFileSync(path.join(OUT, 'memory.json'), 'utf8')); } catch (e) {}
mem.buy = mem.buy || {};
const now = new Date().toISOString().slice(0, 16).replace('T', ' ');
const names = [...new Set(tries.flatMap(t => Object.keys(t)))];
const broken = [], rows = [];
for (const n of names) {
  const good = tries.some(t => t[n] === true);
  const m = mem.buy[n] = mem.buy[n] || { inRow: 0, lastOk: '' };
  if (good) { m.inRow = 0; m.lastOk = now; } else { m.inRow++; broken.push(n); }
  m.last = good;
  rows.push(`| ${n} | ${good ? '✅' : '❌'} | ${good ? '' : `ломается ${m.inRow} ноч. подряд${m.lastOk ? ', последний раз работало ' + m.lastOk : ''}`} |`);
}
const md = [`# Ночная проверка покупки — ${now} UTC`, '',
  tries.length ? (broken.length ? `**❌ Сломано: ${broken.length}** — ${broken.join('; ')}` : '**✅ Путь покупки работает** (товар → звук → корзина → оформление → калькулятор, ПК и Samsung A31). Ничего не оплачивается.') : '**⚠️ Робот не смог пройти путь покупки** (сайт не открылся?) — смотреть журнал запуска.',
  '', `Попыток: ${tries.length} (сломанным считается только то, что не сработало ни в одной).`, '', '| Проверка | Итог | Заметка |', '|---|---|---|', ...rows, '',
  'Снимки и подробный журнал — ветка `shots-ab`, папка `buy-nightly/`. Память робота — `reports/tester/memory.json`.', ''];
fs.writeFileSync(path.join(OUT, 'buy.md'), md.join('\n'));
fs.writeFileSync(path.join(OUT, 'memory.json'), JSON.stringify(mem, null, 1) + '\n');
console.log(md.join('\n'));
fs.appendFileSync(process.env.GITHUB_OUTPUT || '/dev/null', `broken=${tries.length ? broken.join('; ') : 'робот не прошёл путь покупки'}\n`);
