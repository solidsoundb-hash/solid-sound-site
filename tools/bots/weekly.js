// Weekly growth bots — one report for the owner and for Claude: reports/weekly/latest.md (+ dated copy).
//   node tools/bots/weekly.js                 all bots
//   BOTS=seo,products node tools/bots/weekly.js   only these
'use strict';
const fs = require('fs');
const path = require('path');
const { OUT, metrics } = require('./lib');

const ALL = { analytics: require('./analytics'), seo: require('./seo'), products: require('./products'), competitors: require('./competitors'), promos: require('./promos'), security: require('./security') };

(async () => {
  const pick = (process.env.BOTS || Object.keys(ALL).join(',')).split(',').filter(k => ALL[k]);
  const date = new Date().toISOString().slice(0, 10);
  const parts = [`# Еженедельный отчёт ботов — ${date}`, '',
    'Что делать: ⚠️ и 💡 — задачи; ⏸ — бот ждёт доступа (см. `docs/bots.md`). Скорость — `speed/latest.md`, баги — `qa/latest/report.md`.', ''];
  for (const k of pick) {
    try { parts.push(await ALL[k]()); } catch (e) { parts.push(`## ${k}\n\n⚠️ Бот остановился с ошибкой: ${String(e.message).slice(0, 200)}\n`); }
    console.log('done', k);
  }
  fs.mkdirSync(path.join(OUT, 'history'), { recursive: true });
  const md = parts.join('\n');
  fs.writeFileSync(path.join(OUT, 'latest.md'), md);
  fs.writeFileSync(path.join(OUT, 'history', date + '.md'), md);
  fs.writeFileSync(path.join(OUT, 'metrics.json'), JSON.stringify({ date, ran: pick, metrics }, null, 1));
  console.log(md.slice(0, 1500));
})();
