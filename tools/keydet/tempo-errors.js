// Tempo error breakdown on GiantSteps Tempo (local): how far off are the "almost right" answers, and which octave errors happen.
// Usage: CONFIGS='[{}]' node tools/keydet/tempo-errors.js <tempo dataset dir> <audio dir> <cache dir> [max]
const { execFileSync } = require('child_process');
const fs = require('fs'), path = require('path');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const [, , DS, AU, CA, MAX] = process.argv, SR = 22050;
fs.mkdirSync(CA, { recursive: true });
const dir = path.join(DS, 'annotations_v2/tempo'), items = [];
for (const f of fs.readdirSync(dir).filter(f => /\.bpm$/.test(f)).sort()) {
  if (MAX && items.length >= +MAX) break;
  const id = f.replace(/\.bpm$/, ''), g = parseFloat(fs.readFileSync(path.join(dir, f), 'utf8')), v1f = path.join(DS, 'annotations/tempo', f), g1 = fs.existsSync(v1f) ? parseFloat(fs.readFileSync(v1f, 'utf8')) : g, mp3 = path.join(AU, id + '.mp3'), c = path.join(CA, 't' + id + '.f32');
  if (!(g > 0) || !fs.existsSync(mp3)) continue;
  if (!fs.existsSync(c)) { try { fs.writeFileSync(c, execFileSync('ffmpeg', ['-v', 'quiet', '-i', mp3, '-ac', '1', '-ar', String(SR), '-f', 'f32le', '-'], { maxBuffer: 1 << 30 })); } catch (e) { continue; } }
  items.push({ id, g, g1, c });
}
for (const o of JSON.parse(process.env.CONFIGS || '[{}]')) {
  let ex = 0, ex1 = 0, a1 = 0, a2 = 0; const off = {}, oct = {};
  for (const it of items) {
    const raw = fs.readFileSync(it.c), x = new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4), r = A.tempo(x, SR, o), t = r.bpm, g = it.g;
    if (Math.abs(t - g) <= 0.5) ex++;
    if (Math.abs(t - it.g1) <= 0.5 || (Math.abs(t - g) <= 0.5)) ex1++;
    if (Math.abs(t - g) <= g * 0.04) { a1++; const d = (r.raw - g).toFixed(1); off[d] = (off[d] || 0) + 1; }
    else { const ratio = t / g, k = [2, 0.5, 1.5, 2 / 3, 3, 1 / 3, 4 / 3, 0.75].find(q => Math.abs(ratio - q) < 0.04); oct[k ? (+k.toFixed(2)) : 'other'] = (oct[k ? (+k.toFixed(2)) : 'other'] || 0) + 1; }
    if ([1, 2, 0.5, 3, 1 / 3].some(k => Math.abs(t - g * k) <= g * k * 0.04)) a2++;
  }
  const n = items.length, p = v => (100 * v / n).toFixed(1);
  console.log(`exact v2 ${p(ex)} % · exact (v1 producer tempo or v2) ${p(ex1)} % · acc1 ${p(a1)} % · acc2 ${p(a2)} %  ${JSON.stringify(o)}`);
  console.log('  near-miss raw offsets (raw − truth): ' + Object.entries(off).sort((a, b) => b[1] - a[1]).slice(0, 12).map(([k, v]) => k + ':' + v).join(' '));
  console.log('  wrong-octave ratios: ' + JSON.stringify(oct));
}
