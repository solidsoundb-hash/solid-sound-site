// Merge two QA bot reports into one list without duplicates (owner 13.11.2026: "scan again, keep the old report,
// then combine them"). Same bug (same sig) in both = one entry with all pages/devices; screenshot from the newer run.
//   node tools/qa/merge.js qa/latest qa/kb-scan qa/combined
'use strict';
const fs = require('fs');
const path = require('path');

const [A, B, OUT] = process.argv.slice(2);
if (!A || !B || !OUT) { console.log('usage: node tools/qa/merge.js <old dir> <new dir> <out dir>'); process.exit(1); }
const load = (d) => JSON.parse(fs.readFileSync(path.join(d, 'bugs.json'), 'utf8'));
const a = load(A), b = load(B);
const SEV = { critical: '🔴 Критично', major: '🟠 Важно', minor: '🟡 Мелочь', check: '⚪ Проверить глазами' };
const ORDER = ['critical', 'major', 'minor', 'check'];
const BASE = 'https://solid-sound.net';

fs.rmSync(path.join(OUT, 'shots'), { recursive: true, force: true });
fs.mkdirSync(path.join(OUT, 'shots'), { recursive: true });
const copyShot = (dir, shot, tag) => {
  if (!shot) return '';
  const src = path.join(dir, shot); if (!fs.existsSync(src)) return '';
  const name = 'shots/' + tag + '-' + path.basename(shot);
  fs.copyFileSync(src, path.join(OUT, name)); return name;
};

const all = new Map();
for (const [rep, dir, tag] of [[a, A, 'old'], [b, B, 'new']]) {
  for (const x of rep.bugs) {
    const g = all.get(x.sig);
    if (!g) { all.set(x.sig, { ...x, where: [...x.where], devices: [...x.devices], src: [tag], shot: copyShot(dir, x.shot, tag) }); continue; }
    x.where.forEach(w => !g.where.includes(w) && g.where.push(w));
    x.devices.forEach(d => !g.devices.includes(d) && g.devices.push(d));
    g.src.push(tag);
    if (tag === 'new') { g.actual = x.actual || g.actual; const s = copyShot(dir, x.shot, tag); if (s) g.shot = s; }
  }
}
const list = [...all.values()].sort((x, y) => ORDER.indexOf(x.sev) - ORDER.indexOf(y.sev) || (y.type.startsWith('kb-') ? 0 : 1) - (x.type.startsWith('kb-') ? 0 : 1) || y.where.length - x.where.length);
const cnt = (s) => list.filter(x => x.sev === s).length;
const kbN = list.filter(x => x.type.startsWith('kb-')).length;
const onlyOld = list.filter(x => x.src.length === 1 && x.src[0] === 'old');
const L = [];
L.push(`# Автотестировщик — общий отчёт (две проверки вместе)`, '');
L.push(`- Старая проверка: ${a.date.slice(0, 16).replace('T', ' ')} UTC (${a.mode}), без базы знаний — ${a.bugs.length} пунктов.`);
L.push(`- Новая проверка: ${b.date.slice(0, 16).replace('T', ' ')} UTC (${b.mode}), с базой знаний общих багов — ${b.bugs.length} пунктов.`);
L.push(`- Вместе без повторов: **${list.length}** · ${ORDER.map(s => `${SEV[s]} ${cnt(s)}`).join(' · ')} · из базы знаний: ${kbN} 📚`);
L.push(`- Пометки: 📚 — общий баг из базы знаний (\`docs/qa-knowledge.md\`); ⏳ — был только в старой проверке (возможно, уже исправлен или страница не проверялась заново).`, '');
let n = 0;
for (const s of ORDER) {
  const g = list.filter(x => x.sev === s); if (!g.length) continue;
  L.push(`## ${SEV[s]} (${g.length})`, '');
  for (const x of g) {
    n++;
    const mark = (x.type.startsWith('kb-') ? ' 📚' : '') + (x.src.length === 1 && x.src[0] === 'old' ? ' ⏳' : '');
    L.push(`### ${n}. ${x.title}${mark}`);
    L.push(`- **Где:** ${x.where.slice(0, 6).map(w => w.startsWith('/') ? BASE + w : w).join(', ') || '—'}${x.where.length > 6 ? ` и ещё ${x.where.length - 6}` : ''}${x.devices.length ? ` (${x.devices.join(', ')})` : ''}`);
    if (x.steps && x.steps.length) L.push(`- **Шаги:** ${x.steps.map((t, i) => `${i + 1}) ${t}`).join(' ')}`);
    L.push(`- **Ожидалось:** ${x.expected}`, `- **На деле:** ${x.actual}`);
    if (x.shot) L.push(`- **Скриншот:** [${x.shot}](${x.shot})`);
    L.push('');
  }
}
const nc = [...new Set([...(a.notChecked || []), ...(b.notChecked || [])])];
if (nc.length) { L.push(`## Не удалось проверить (${nc.length})`, ''); nc.slice(0, 60).forEach(x => L.push(`- ${x}`)); L.push(''); }
fs.writeFileSync(path.join(OUT, 'report.md'), L.join('\n') + '\n');
fs.writeFileSync(path.join(OUT, 'bugs.json'), JSON.stringify({ merged: [A, B], dates: [a.date, b.date], bugs: list, notChecked: nc }, null, 1));
console.log(L.slice(0, 6).join('\n'));
console.log('only in the old run:', onlyOld.length);
