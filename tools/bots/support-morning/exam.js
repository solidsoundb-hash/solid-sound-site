// Exam for support-morning (docs/team/bots.md): privacy scrub, which orders are urgent, which forum topics are questions.
// node tools/bots/support-morning/exam.js
'use strict';
const f = require('../support-morning.js');
const E = require('./exam.json');
const bad = E.filter(c => JSON.stringify(f[c.f](c.in)) !== JSON.stringify(c.want));
console.log(`Экзамен support-morning: ${E.length - bad.length}/${E.length}`);
bad.forEach(c => console.log(`  ✗ ${c.f}(${JSON.stringify(c.in)}) = ${JSON.stringify(f[c.f](c.in))}, нужно ${JSON.stringify(c.want)}`));
process.exit(bad.length ? 1 : 0);
