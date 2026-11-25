// Экзамен юр. робота: каждый случай → ожидаемый уровень по правилам. Перед любым изменением tools/legal/rules.js — прогон; хуже прежнего — не принимаем.
const rules = require('../../legal/rules'); const cases = require('./exam.json'); let ok = 0, all = 0;
for (const c of cases) { const r = Object.fromEntries(rules(c.pages).map(x => [x.id, x.level]));
  for (const [id, want] of Object.entries(c.want)) { all++; if (r[id] === want) ok++; else console.log(`✗ ${c.name}: ${id} = ${r[id]}, нужно ${want}`); } }
console.log(`Экзамен: ${ok}/${all}`); process.exit(ok === all ? 0 : 1);
