// Joins the photographer robots' lists into one readable index: shots/index.md (Russian, grouped by page).
const fs = require('fs'); const path = require('path');
const OUT = process.argv[2] || 'shots';
let all = [];
for (const f of fs.readdirSync(OUT).filter(f => /^index-\d+\.json$/.test(f))) all = all.concat(JSON.parse(fs.readFileSync(path.join(OUT, f), 'utf8')));
const rel = (u) => u.replace('https://solid-sound.net', '') || '/';
const slug = (u) => (new URL(u).pathname + new URL(u).search).replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home';
const devDir = { 'компьютер': 'pc', 'телефон': 'phone', 'разные ширины': 'home-widths' };
const pages = new Set(all.map(r => r.url)); let shots = 0;
const L = [`# Робот-фотограф — ${new Date().toISOString().slice(0, 16).replace('T', ' ')} UTC`, '',
  `Страниц: ${pages.size}, снимков: ${all.reduce((s, r) => s + r.shots.length, 0)}. Компьютер 1280 px, телефон 390 px. Общие кнопки (шапка, меню, подвал) сняты один раз.`, ''];
all.sort((a, b) => a.url.localeCompare(b.url) || a.dev.localeCompare(b.dev));
for (const r of all) {
  const dir = r.dev === 'разные ширины' ? 'home-widths' : devDir[r.dev] + '/' + slug(r.url);
  L.push(`## ${rel(r.url)} — ${r.dev}${r.err ? ' ⚠️ ' + r.err : ''}`);
  for (const [t, f] of r.shots) { L.push(`- [${t}](${dir}/${f})`); shots++; }
  L.push('');
}
fs.writeFileSync(path.join(OUT, 'index.md'), L.join('\n'));
console.log('pages', pages.size, 'shots', shots);
