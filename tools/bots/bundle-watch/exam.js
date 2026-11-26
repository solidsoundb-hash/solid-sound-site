// Exam for bundle-watch (docs/team/bots.md): run before every change; worse than before = change not accepted.
'use strict';
const { isBundle, promised, parts, genresOf, offClaim } = require('../bundle-watch.js');
const E = require('./exam.json');
let ok = 0, n = 0; const bad = [];
const t = (pass, msg) => { n++; if (pass) ok++; else bad.push(msg); };
for (const c of E.isBundle) t(isBundle(c.it) === c.want, `isBundle «${c.it.title}» → ${!c.want}`);
for (const c of E.promised) { const p = promised(c.it); t(p === c.want, `promised «${c.it.title}» → ${p}`); }
const p = parts(E.parts.bundle, E.parts.cat).map(x => x.id).sort(); t(JSON.stringify(p) === JSON.stringify(E.parts.want), `parts → ${p}`);
for (const c of E.genres) { const g = genresOf(c.t); t(JSON.stringify(g.slice().sort()) === JSON.stringify(c.want.slice().sort()), `genres «${c.t}» → ${g}`); }
for (const c of E.offClaim || []) { const o = offClaim(c.t); t(o === c.want, `offClaim «${c.t}» → ${o}`); }
console.log(`Экзамен bundle-watch: ${ok}/${n}`); bad.forEach(b => console.log('  ✗ ' + b));
process.exit(ok === n ? 0 : 1);
