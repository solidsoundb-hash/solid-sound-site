// Exam for the intel robot (docs/team/bots.md): run before every change; worse than before = change not accepted.
//   node tools/bots/intel/exam.js   -> prints the score, exit 1 if anything failed
'use strict';
const { scoreNews, parseFeed } = require('../intel.js');
const E = require('./exam.json');
let ok = 0, n = 0; const bad = [];
for (const c of E.news) {
  n++; const s = scoreNews(c.t);
  const pass = c.want === 'skip' ? s === null : c.want === 'top' ? s !== null && s >= 4 : c.want === 'keep' ? s !== null && s > 0 : c.want === 'notop' ? s === null || s < 4 : false;
  if (pass) ok++; else bad.push(`${c.want} ← ${s} : ${c.t}`);
}
const items = parseFeed(E.feed.xml);
E.feed.want.forEach((w, i) => { n++; const it = items[i] || {}; if (it.title === w.title && it.link === w.link) ok++; else bad.push(`feed #${i}: ${JSON.stringify(it.title)} ${it.link}`); });
console.log(`Экзамен intel: ${ok}/${n}`); bad.forEach(b => console.log('  ✗ ' + b));
process.exit(ok === n ? 0 : 1);
