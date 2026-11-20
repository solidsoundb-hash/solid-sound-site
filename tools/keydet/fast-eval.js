// Fast local key exam: decodes each GiantSteps track once (cached as .f32), then scores many configs.
// Usage: CONFIGS='[{"fn":"keyH"},{"fn":"keyH","prof":"ed"}]' node tools/keydet/fast-eval.js <dataset dir> <audio dir> <cache dir> [max]
// fn: keyH (HPCP) | key (vote/single). MIREX score: exact 1 · fifth 0.5 · relative 0.3 · parallel 0.2.
const { execFileSync } = require('child_process');
const fs = require('fs'), path = require('path');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const [, , DS, AU, CA, MAX] = process.argv, SR = 22050;
const NAMES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'], FL = { Db: 'C#', Eb: 'D#', Gb: 'F#', Ab: 'G#', Bb: 'A#' };
function parse(s) { const m = String(s).trim().match(/^([A-G])([#b]?)\s*(major|minor|maj|min|m)?$/i); if (!m) return null; let t = m[1].toUpperCase() + (m[2] || ''); t = FL[t] || t; return { t: NAMES.indexOf(t), m: /^m(in(or)?)?$/i.test(m[3] || '') ? 1 : 0 }; }
function score(g, e) {
  if (!g || !e) return [0, 'other'];
  if (g.t === e.t && g.m === e.m) return [1, 'exact'];
  const d = (e.t - g.t + 12) % 12;
  if (g.m === e.m && (d === 7 || d === 5)) return [0.5, 'fifth'];
  if (g.m !== e.m && ((g.m && (g.t + 3) % 12 === e.t) || (!g.m && (g.t + 9) % 12 === e.t))) return [0.3, 'relative'];
  if (g.t === e.t) return [0.2, 'parallel'];
  return [0, 'other'];
}
fs.mkdirSync(CA, { recursive: true });
const items = [];
for (const f of fs.readdirSync(path.join(DS, 'annotations/key')).sort()) {
  if (MAX && items.length >= +MAX) break;
  const id = f.replace(/\.key$/, ''), mp3 = path.join(AU, id + '.mp3'), c = path.join(CA, id + '.f32');
  if (!fs.existsSync(mp3)) continue;
  const g = parse(fs.readFileSync(path.join(DS, 'annotations/key', f), 'utf8').split('\n')[0]); if (!g) continue;
  if (!fs.existsSync(c)) { try { fs.writeFileSync(c, execFileSync('ffmpeg', ['-v', 'quiet', '-i', mp3, '-ac', '1', '-ar', String(SR), '-f', 'f32le', '-'], { maxBuffer: 1 << 30 })); } catch (e) { continue; } }
  items.push({ id, g, c });
}
const CONF = JSON.parse(process.env.CONFIGS || '[{"fn":"keyH"}]');
for (const o of CONF) {
  let s = 0, k = {}, t0 = Date.now();
  for (const it of items) {
    const raw = fs.readFileSync(it.c), x = new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4);
    let r = null; try { r = A[o.fn || 'keyH'](x, SR, o); } catch (e) { }
    const [sc, kind] = score(it.g, r ? parse(r.key) : null); s += sc; k[kind] = (k[kind] || 0) + 1;
  }
  console.log((100 * s / items.length).toFixed(1).padStart(5) + ' %  ' + JSON.stringify(k) + '  ' + Math.round((Date.now() - t0) / items.length) + ' ms/track  ' + JSON.stringify(o));
}
