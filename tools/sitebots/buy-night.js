// NIGHT BUYER (owner 25.11.2026, docs/team/bots.md, role «Тестировщик»): every night walks the purchase path
// catalog → product → cart → checkout form on a computer and on the Samsung A31 (never pays — tools/sitebots/buyer.js).
// Writes reports/tester/buy-night.md + one line per night in buy-night.csv; exit code 2 = the path is really broken
// (the workflow then opens the GitHub issue «🛒 Покупка сломалась»). Captcha / 503 twice = no alarm, only a note.
'use strict';
const fs = require('fs'); const path = require('path');
const OUT = 'reports/tester';
process.env.SITEBOTS_OUT = OUT;
require('./buyer')().then(async r => {
  let cx = { md: '', alerts: [] }; // 26.11: two-step «Cancel an order» (tools/sitebots/cancel-check.js), never confirms a real one
  try { fs.mkdirSync(OUT, { recursive: true }); cx = await require('./cancel-check')(OUT); } catch (e) { cx.md = '## ↩️ Отмена заказа\n\n- ⚠️ ' + e.message + '\n'; }
  try { const dm = await require('./demo-match')(); cx.md += '\n' + dm.md; cx.alerts.push(...dm.alerts); } catch (e) { cx.md += '\n## 🎧 Демо ↔ товар\n\n- ⚠️ ' + e.message + '\n'; } // 26.11: demo of THIS product
  fs.mkdirSync(OUT, { recursive: true });
  const at = new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC';
  const broken = r.metrics.buyer_broken;
  const md = `# 🛒 Ночная покупка — ${at}\n\n${broken ? '❌ **ПУТЬ ПОКУПКИ СЛОМАН** — тревога: задача «🛒 Покупка сломалась» на GitHub.' : '✅ Путь покупки работает.'}\n\n` +
    (r.alerts.length ? r.alerts.map(a => '- ' + a).join('\n') + '\n\n' : '') + r.md.replace(/^## .*\n\n/, '') + '\n' + (cx.alerts.length ? cx.alerts.map(x => '- ⚠️ ' + x).join('\n') + '\n\n' : '') + cx.md +
    '\n---\nРобот `tools/sitebots/buy-night.js`, каждую ночь 02:37 UTC; вручную — строка в `.github/buy-night.txt`.\n';
  fs.writeFileSync(path.join(OUT, 'buy-night.md'), md);
  const csv = path.join(OUT, 'buy-night.csv');
  if (!fs.existsSync(csv)) fs.writeFileSync(csv, 'at,broken,js_errors\n');
  fs.appendFileSync(csv, `${at},${broken},${r.metrics.buyer_js_errors}\n`);
  console.log(md);
  process.exit(broken ? 2 : 0);
}).catch(e => { console.error('робот упал: ' + e.message); process.exit(1); });
