// Key detector exam on GiantSteps Key (604 Beatport EDM previews, keys checked by musicians — the standard EDM key benchmark).
// Usage: node tools/keydet/gs-eval.js <dataset dir with annotations/key/*.key> <audio dir with *.mp3> [max tracks]
// Score per track (MIREX): exact 1 · fifth up/down 0.5 · relative major/minor 0.3 · parallel 0.2 · other 0.
// Configs come from env CONFIGS (JSON list of option objects); each is scored, the per-error table is printed.
const { execFileSync } = require('child_process');
const fs = require('fs'), path = require('path');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const [, , DS, AU, MAX] = process.argv;
const SR = 22050, NAMES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
const FL = { Db: 'C#', Eb: 'D#', Gb: 'F#', Ab: 'G#', Bb: 'A#' };
function parse(s) { // "C minor", "Eb major", "Am"
  s = s.trim(); let m = s.match(/^([A-G])([#b]?)\s*(major|minor|maj|min|m)?$/i); if (!m) return null;
  let t = m[1].toUpperCase() + (m[2] || ''); t = FL[t] || t; const mi = /^m(in(or)?)?$/i.test(m[3] || '') && !/maj/i.test(m[3] || '');
  return { t: NAMES.indexOf(t), m: mi ? 1 : 0 };
}
function score(g, e) {
  if (!g || !e) return [0, 'none'];
  if (g.t === e.t && g.m === e.m) return [1, 'exact'];
  if (g.m === e.m && ((e.t - g.t + 12) % 12 === 7 || (e.t - g.t + 12) % 12 === 5)) return [0.5, 'fifth'];
  if (g.m !== e.m && ((g.m && (g.t + 3) % 12 === e.t) || (!g.m && (g.t + 9) % 12 === e.t))) return [0.3, 'relative'];
  if (g.t === e.t) return [0.2, 'parallel'];
  return [0, 'other'];
}
const files = fs.readdirSync(path.join(DS, 'annotations/key')).filter(f => /\.key$/.test(f)).sort();
const CONF = JSON.parse(process.env.CONFIGS || '[{}]');
const res = CONF.map(() => ({ s: 0, n: 0, k: {} }));
let done = 0;
for (const f of files) {
  if (MAX && done >= +MAX) break;
  const id = f.replace(/\.key$/, ''), mp3 = path.join(AU, id + '.mp3');
  if (!fs.existsSync(mp3)) continue;
  const g = parse(fs.readFileSync(path.join(DS, 'annotations/key', f), 'utf8').split('\n')[0]);
  if (!g) continue;
  let x; try { const raw = execFileSync('ffmpeg', ['-v', 'quiet', '-i', mp3, '-ac', '1', '-ar', String(SR), '-f', 'f32le', '-'], { maxBuffer: 1 << 30 }); x = new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4); } catch (e) { continue; }
  done++;
  CONF.forEach((o, i) => { let r = null; try { r = A.key(x, SR, o); } catch (e) {} const e = r ? parse(r.key) : null; const [sc, kind] = score(g, e); res[i].s += sc; res[i].n++; res[i].k[kind] = (res[i].k[kind] || 0) + 1; });
}
const out = ['# Key detector on GiantSteps Key — ' + new Date().toISOString(), '', `${done} tracks`, '', '| config | score % | exact | fifth | relative | parallel | other |', '|---|---|---|---|---|---|---|'];
CONF.forEach((o, i) => { const r = res[i], p = (k) => r.k[k] || 0; out.push(`| \`${JSON.stringify(o)}\` | ${(100 * r.s / Math.max(1, r.n)).toFixed(1)} | ${p('exact')} | ${p('fifth')} | ${p('relative')} | ${p('parallel')} | ${p('other') + p('none')} |`); });
console.log(out.join('\n'));
fs.mkdirSync('checks/keydet', { recursive: true }); fs.writeFileSync('checks/keydet/report.md', out.join('\n') + '\n');
