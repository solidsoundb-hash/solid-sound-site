// Exam for content-watch (docs/team/bots.md): run before every change; worse than before = change not accepted.
'use strict';
const { format, measure } = require('../content-watch.js');
const E = require('./exam.json');
let ok = 0, n = 0; const bad = [];
const t = (pass, msg) => { n++; if (pass) ok++; else bad.push(msg); };
for (const c of E.format) { const f = format(c.t); t(f === c.want, `format «${c.t}» → ${f}`); }
for (const c of E.measure) { const m = measure(c.html); for (const k of ['words', 'img', 'video', 'audio', 'h2']) t(m[k] === c[k], `measure ${k} → ${m[k]} (ждали ${c[k]})`); }
console.log(`Экзамен content-watch: ${ok}/${n}`); bad.forEach(b => console.log('  ✗ ' + b));
process.exit(ok === n ? 0 : 1);
