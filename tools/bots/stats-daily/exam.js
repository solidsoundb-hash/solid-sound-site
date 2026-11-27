// Exam for stats-daily: is each page counted at the right funnel step? node tools/bots/stats-daily/exam.js
'use strict';
const f = require('../stats-daily.js');
const E = require('./exam.json');
const step = (p) => f.isThanks(p) ? 'thanks' : f.isCheckout(p) ? 'checkout' : f.isCart(p) ? 'cart' : f.isProduct(p) ? 'product' : 'none';
const bad = E.filter(c => step(c.p) !== c.want);
for (const [d, w] of [['2026-11-23', false], ['2026-11-25', true], ['2026-11-27', true], ['2026-11-28', false]]) if (f.isDirty(d) !== w) bad.push({ p: 'грязный день ' + d, want: w });
console.log(`Экзамен stats-daily: ${E.length + 4 - bad.length}/${E.length + 4}`); bad.forEach(c => console.log(`  ✗ ${c.p}: ${step(c.p)} вместо ${c.want}`));
process.exit(bad.length ? 1 : 0);
