// Exam for pain-watch (docs/team/bots.md): run before every change; worse than before = change not accepted.
'use strict';
const { classify, clean, safeLink } = require('../pain-watch.js');
const E = require('./exam.json');
let ok = 0, n = 0; const bad = [];
const t = (pass, msg) => { n++; if (pass) ok++; else bad.push(msg); };
for (const c of E.classify) { const ids = classify(c.t); t(c.none ? ids.length === 0 : c.notHas ? c.notHas.every(h => !ids.includes(h)) : c.has.every(h => ids.includes(h)), `classify «${c.t}» → ${ids.join(',') || '—'}`); }
for (const c of E.clean) { const s = clean(c.t); t(c.notHas.every(x => !s.includes(x)), `clean «${c.t}» → «${s}»`); }
for (const c of E.safeLink) { const s = !!safeLink(c.u); t(s === c.ok, `safeLink ${c.u} → ${s}`); }
console.log(`Экзамен pain-watch: ${ok}/${n}`); bad.forEach(b => console.log('  ✗ ' + b));
process.exit(ok === n ? 0 : 1);
