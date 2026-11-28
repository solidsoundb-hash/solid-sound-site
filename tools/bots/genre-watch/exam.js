// Exam for genre-watch (docs/team/bots.md): run before every change; worse than before = change not accepted.
'use strict';
const { focus, isTemplate, priceVerdict } = require('../genre-watch.js');
const E = require('./exam.json');
let ok = 0, n = 0; const bad = [];
const t = (pass, msg) => { n++; if (pass) ok++; else bad.push(msg); };
for (const c of E.focus) { const f = focus(c.it); t(f === c.want, `focus «${c.it.title}» → ${f}`); }
for (const c of E.template) { const f = isTemplate(c.it); t(f === c.want, `template «${c.it.title}» → ${f}`); }
for (const c of E.price || []) { const r = priceVerdict(c.our, c.med); const v = r ? r.v : null; t(v === c.want, `price ${c.our}/${c.med} → ${v}`); }
console.log(`Экзамен genre-watch: ${ok}/${n}`); bad.forEach(b => console.log('  ✗ ' + b));
process.exit(ok === n ? 0 : 1);
