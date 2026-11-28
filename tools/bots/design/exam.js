// Exam for design-watch (docs/team/bots.md): run before every change; worse than before = change not accepted.
'use strict';
const D = require('../design-watch.js');
const E = require('./exam.json');
let ok = 0, n = 0; const bad = [];
const t = (pass, msg) => { n++; if (pass) ok++; else bad.push(msg); };
for (const c of E.buy) t(D.isBuyText(c.t) === c.want, `купить «${c.t}» → ${!c.want}`);
for (const c of E.price) t(D.isPriceText(c.t) === c.want, `цена «${c.t}» → ${!c.want}`);
for (const c of E.contrast) { const v = D.contrast(c.fg, c.bg); t(Math.abs(v - c.want) <= 0.15, `контраст ${c.fg} / ${c.bg} → ${v}, нужно ${c.want}`); }
for (const c of E.median) { const v = D.median(c.a); t(v === c.want, `медиана ${JSON.stringify(c.a)} → ${v}`); }
for (const c of E.verdict) { const v = D.verdict(c.type, c.comp, c.ours, c.better); t(v === c.want, `отметка ${JSON.stringify(c)} → «${v}»`); }
for (const c of E.robots) { const v = D.robotsAllowed(c.txt, c.path); t(v === c.want, `robots ${c.path} → ${v}`); }
// report is built and every metric row has a group name
const fake = { date: 'x', mode: 'exam', minutes: 0, log: [], sites: { Ours: { ours: true, pages: { 'product/pc': { buyH: 30, buyText: 'Add to cart', buyW: 100, kb: 900 } } },
  A: { pages: { 'product/pc': { buyH: 48, kb: 2000 } } }, B: { pages: { 'product/pc': { buyH: 52, kb: 3000 } } }, C: { pages: { 'product/pc': { error: 'robots.txt запрещает' } } } } };
const md = D.report(fake, D.remember({ runs: [], noise: [], last: {} }, fake));
t(/Кнопка «купить»: высота, px \| ПК \| медиана 50 .*\| 30 \| ⚠️ меньше/.test(md), 'отчёт: строка высоты кнопки');
t(/C product\/pc: robots\.txt запрещает/.test(md), 'отчёт: пропущенные страницы названы');
console.log(`Экзамен design-watch: ${ok}/${n}`); bad.forEach(b => console.log('  ✗ ' + b));
process.exit(ok === n ? 0 : 1);
