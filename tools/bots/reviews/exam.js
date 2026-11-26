// Exam for the reviews robot (docs/team/bots.md): run before every change; worse than before = change not accepted.
'use strict';
const { scrub, themes, parseReviews, wooReviews } = require('../reviews.js');
const E = require('./exam.json');
let ok = 0, n = 0; const bad = [];
const t = (pass, msg) => { n++; if (pass) ok++; else bad.push(msg); };
for (const c of E.scrub) { const s = scrub(c.in);
  if (c.mode === 'eq') t(s === c.want, `scrub «${c.in}» → «${s}»`);
  else if (c.mode === 'nosign') t(!s.includes(c.name), `scrub «${c.in}» → «${s}»`);
  else t(!s.includes(c.noHas), `scrub «${c.in}» → «${s}»`); }
for (const c of E.themes) { const th = themes(c.in); t(c.want.every(w => th.includes(w)), `themes «${c.in}» → ${th}`); }
for (const c of E.parse) { const r = parseReviews(c.html);
  t(r.length === c.count && r[0] && r[0].rating === c.rating && (!c.agg || (r.agg && r.agg.count === c.agg)) && !JSON.stringify(r).includes(c.noAuthor), `parse → ${JSON.stringify(r)} agg ${JSON.stringify(r.agg)}`); }
for (const c of E.woo || []) { const r = wooReviews(c.in); const txt = JSON.stringify(r.map(x => ({ ...x, body: scrub(x.body) })));
  t(r.length === c.count && r[0].product === c.product && c.noHas.every(w => !txt.includes(w)), `woo → ${txt}`); }
console.log(`Экзамен reviews: ${ok}/${n}`); bad.forEach(b => console.log('  ✗ ' + b));
process.exit(ok === n ? 0 : 1);
