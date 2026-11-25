// Exam for stats-daily: is each page counted at the right funnel step? node tools/bots/stats-daily/exam.js
'use strict';
const f = require('../stats-daily.js');
const E = require('./exam.json');
const step = (p) => f.isThanks(p) ? 'thanks' : f.isCheckout(p) ? 'checkout' : f.isCart(p) ? 'cart' : f.isProduct(p) ? 'product' : 'none';
const bad = E.filter(c => step(c.p) !== c.want);
console.log(`Экзамен stats-daily: ${E.length - bad.length}/${E.length}`); bad.forEach(c => console.log(`  ✗ ${c.p}: ${step(c.p)} вместо ${c.want}`));
process.exit(bad.length ? 1 : 0);
