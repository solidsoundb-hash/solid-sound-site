// Tempo exam on GiantSteps Tempo (annotations_v2: corrected BPM of Beatport EDM previews).
// Usage: node tools/keydet/gs-tempo.js <dataset dir> <audio dir> [max tracks]   — configs from env CONFIGS (JSON list).
// exact = within ±0.5 BPM (what the form needs), acc1 = within 4 %, acc2 = within 4 % of ×1, ×2, ×½, ×3, ×⅓.
const { execFileSync } = require('child_process');
const fs = require('fs'), path = require('path');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const [, , DS, AU, MAX] = process.argv, SR = 22050;
const dir = path.join(DS, 'annotations_v2/tempo');
const files = fs.readdirSync(dir).filter(f => /\.bpm$/.test(f)).sort();
const CONF = JSON.parse(process.env.CONFIGS || '[{}]');
const res = CONF.map(() => ({ n: 0, ex: 0, a1: 0, a2: 0, half: 0, dbl: 0 }));
let done = 0;
for (const f of files) {
  if (MAX && done >= +MAX) break;
  const id = f.replace(/\.bpm$/, ''), mp3 = path.join(AU, id + '.mp3');
  if (!fs.existsSync(mp3)) continue;
  const g = parseFloat(fs.readFileSync(path.join(dir, f), 'utf8')); if (!(g > 0)) continue;
  let x; try { const raw = execFileSync('ffmpeg', ['-v', 'quiet', '-i', mp3, '-ac', '1', '-ar', String(SR), '-f', 'f32le', '-'], { maxBuffer: 1 << 30 }); x = new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4); } catch (e) { continue; }
  done++;
  CONF.forEach((o, i) => {
    const t = A.tempo(x, SR, o).bpm, r = res[i]; r.n++;
    if (Math.abs(t - g) <= 0.5) r.ex++;
    if (Math.abs(t - g) <= g * 0.04) r.a1++;
    if ([1, 2, 0.5, 3, 1 / 3].some(k => Math.abs(t - g * k) <= g * k * 0.04)) r.a2++;
    if (Math.abs(t - g / 2) <= g * 0.02) r.half++; if (Math.abs(t - g * 2) <= g * 0.08) r.dbl++;
  });
}
const p = (a, n) => (100 * a / Math.max(1, n)).toFixed(1);
const out = ['# Tempo detector on GiantSteps Tempo — ' + new Date().toISOString(), '', `${done} tracks`, '', '| config | exact ±0.5 % | acc1 4 % | acc2 (×2, ×½) % | said half | said double |', '|---|---|---|---|---|---|'];
CONF.forEach((o, i) => { const r = res[i]; out.push(`| \`${JSON.stringify(o)}\` | ${p(r.ex, r.n)} | ${p(r.a1, r.n)} | ${p(r.a2, r.n)} | ${r.half} | ${r.dbl} |`); });
console.log(out.join('\n'));
fs.mkdirSync('checks/keydet', { recursive: true }); fs.writeFileSync('checks/keydet/tempo.md', out.join('\n') + '\n');
