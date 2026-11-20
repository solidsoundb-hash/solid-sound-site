// Train the key model on GiantSteps MTG Key (confident tracks) and test it on GiantSteps Key (604, never seen) — the standard protocol.
// Usage: node tools/keydet/train.js <gs dir> <gs audio> <mtg dir> <mtg audio> <cache dir> [out model.json]
// env HOPTS (hpcp blocks), TR ([epochs, lr, l2]), LOG (log features). Prints the test score; writes the model for ss-audio.js.
const { execFileSync } = require('child_process');
const fs = require('fs'), path = require('path'), crypto = require('crypto');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const [, , GS, GSA, MT, MTA, CA, OUT] = process.argv, SR = 22050;
const HOPTS = JSON.parse(process.env.HOPTS || '[{"white":0,"harm":8},{"white":0,"harm":1,"minF":30,"maxF":220}]');
const [EP, LR, L2] = JSON.parse(process.env.TR || '[40,0.005,0.001]'), LOG = +(process.env.LOG || 1);
const NAMES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'], FL = { Db: 'C#', Eb: 'D#', Gb: 'F#', Ab: 'G#', Bb: 'A#' };
function parse(s) { const m = String(s).trim().match(/^([A-G])([#b]?)\s*(major|minor|maj|min|m)?$/i); if (!m) return null; let t = m[1].toUpperCase() + (m[2] || ''); t = FL[t] || t; return { t: NAMES.indexOf(t), m: /^m(in(or)?)?$/i.test(m[3] || '') ? 1 : 0 }; }
function score(g, e) { if (g.t === e.t && g.m === e.m) return [1, 'exact']; const d = (e.t - g.t + 12) % 12; if (g.m === e.m && (d === 7 || d === 5)) return [0.5, 'fifth']; if (g.m !== e.m && ((g.m && (g.t + 3) % 12 === e.t) || (!g.m && (g.t + 9) % 12 === e.t))) return [0.3, 'relative']; if (g.t === e.t) return [0.2, 'parallel']; return [0, 'other']; }
const CHS = HOPTS.map(h => { const tag = crypto.createHash('md5').update(JSON.stringify(h)).digest('hex').slice(0, 8), f = path.join(CA, 'chroma-' + tag + '.json'); return { h, f, d: fs.existsSync(f) ? JSON.parse(fs.readFileSync(f)) : {} }; });
function load(list) { /* list: [{id, key, mp3}] */
  const out = [];
  for (const L of list) {
    const g = parse(L.key); if (!g) continue; let x = null, ok = true; const chs = [];
    for (const B of CHS) {
      if (!B.d[L.id]) {
        if (!x) { if (!fs.existsSync(L.mp3)) { ok = false; break; } const c = path.join(CA, L.id + '.f32'); if (!fs.existsSync(c)) { try { fs.writeFileSync(c, execFileSync('ffmpeg', ['-v', 'quiet', '-i', L.mp3, '-ac', '1', '-ar', String(SR), '-f', 'f32le', '-'], { maxBuffer: 1 << 30 })); } catch (e) { ok = false; break; } } const raw = fs.readFileSync(c); x = new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4); }
        const ch = A.hpcp(x, SR, B.h); if (!ch) { ok = false; break; } B.d[L.id] = ch;
      }
      chs.push(B.d[L.id]);
    }
    if (ok) out.push({ id: L.id, g, chs });
  }
  CHS.forEach(B => fs.writeFileSync(B.f, JSON.stringify(B.d)));
  return out;
}
const gsList = fs.readdirSync(path.join(GS, 'annotations/key')).filter(f => /\.key$/.test(f)).map(f => { const id = f.replace(/\.key$/, ''); return { id, key: fs.readFileSync(path.join(GS, 'annotations/key', f), 'utf8').split('\n')[0], mp3: path.join(GSA, id + '.mp3') }; });
const mtList = fs.readFileSync(path.join(MT, 'annotations/annotations.txt'), 'utf8').trim().split('\n').slice(1).map(l => l.split('\t')).filter(c => c[2] && c[2].trim() === '2').map(c => ({ id: 'mtg' + c[0], key: c[1], mp3: path.join(MTA, c[0] + '.LOFI.mp3') }));
const TE = load(gsList), TRN = load(mtList);
function feat(it, t) { const out = []; it.chs.forEach(ch => { const s = ch.reduce((a, b) => a + b, 0), v = []; for (let i = 0; i < 12; i++) { const x = ch[(i + t) % 12] / s; v.push(LOG ? Math.log(0.01 + x) : x); } const mu = v.reduce((a, b) => a + b, 0) / 12, sd = Math.sqrt(v.reduce((a, b) => a + (b - mu) ** 2, 0) / 12) || 1; v.forEach(x => out.push((x - mu) / sd)); }); return out; }
function train(tr) {
  const D = tr[0].chs.length * 12, W = [new Float64Array(D), new Float64Array(D)], b = [0, 0];
  for (let e = 0; e < EP; e++) for (const it of tr) {
    const F = [], z = []; for (let t = 0; t < 12; t++) { F[t] = feat(it, t); for (let m = 0; m < 2; m++) { let v = b[m]; for (let d = 0; d < D; d++) v += W[m][d] * F[t][d]; z.push({ t, m, v }); } }
    const mx = Math.max(...z.map(q => q.v)); let Z = 0; z.forEach(q => { q.p = Math.exp(q.v - mx); Z += q.p; });
    z.forEach(q => { const g = q.p / Z - (q.t === it.g.t && q.m === it.g.m ? 1 : 0); for (let d = 0; d < D; d++) W[q.m][d] -= LR * (g * F[q.t][d] + L2 * W[q.m][d]); b[q.m] -= LR * g; });
  }
  return { W: W.map(w => Array.from(w)), b };
}
function test(te, M) { let s = 0; const k = {}; te.forEach(it => { let best = null; for (let t = 0; t < 12; t++) { const F = feat(it, t); for (let m = 0; m < 2; m++) { let v = M.b[m]; for (let d = 0; d < F.length; d++) v += M.W[m][d] * F[d]; if (!best || v > best.v) best = { t, m, v }; } } const [sc, kind] = score(it.g, best); s += sc; k[kind] = (k[kind] || 0) + 1; }); return [100 * s / te.length, k]; }
const M = train(TRN), [sc, k] = test(TE, M);
console.log(`train ${TRN.length} (MTG) → test ${TE.length} (GiantSteps): ${sc.toFixed(1)} %  ${JSON.stringify(k)}  blocks ${JSON.stringify(HOPTS)} TR ${JSON.stringify([EP, LR, L2])} LOG ${LOG}`);
if (OUT) fs.writeFileSync(OUT, JSON.stringify({ blocks: HOPTS, log: LOG, W: M.W.map(w => w.map(v => +v.toFixed(4))), b: M.b.map(v => +v.toFixed(4)) }));
