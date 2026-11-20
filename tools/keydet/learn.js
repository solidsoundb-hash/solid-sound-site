// Learn key profiles for our own hearing (SSAudio.hpcp) and test them on tracks that were NOT used for learning.
// Usage: node tools/keydet/learn.js <dataset dir> <audio dir> <cache dir>   env HOPT = hpcp options JSON
// 1) chroma of every track (cached per HOPT), 2) 2-fold cross-validation: learn major/minor profiles from one half
// (each chroma rotated to its tonic, averaged), score the other half (MIREX), and the reverse; print both + the profile.
const { execFileSync } = require('child_process');
const fs = require('fs'), path = require('path'), crypto = require('crypto');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const [, , DS, AU, CA] = process.argv, SR = 22050, HOPTS = JSON.parse(process.env.HOPTS || '[{"white":0,"harm":8}]'), WTS = JSON.parse(process.env.WTS || '[1,1,1]');
const NAMES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'], FL = { Db: 'C#', Eb: 'D#', Gb: 'F#', Ab: 'G#', Bb: 'A#' };
function parse(s) { const m = String(s).trim().match(/^([A-G])([#b]?)\s*(major|minor|maj|min|m)?$/i); if (!m) return null; let t = m[1].toUpperCase() + (m[2] || ''); t = FL[t] || t; return { t: NAMES.indexOf(t), m: /^m(in(or)?)?$/i.test(m[3] || '') ? 1 : 0 }; }
function score(g, e) { if (g.t === e.t && g.m === e.m) return 1; const d = (e.t - g.t + 12) % 12; if (g.m === e.m && (d === 7 || d === 5)) return 0.5; if (g.m !== e.m && ((g.m && (g.t + 3) % 12 === e.t) || (!g.m && (g.t + 9) % 12 === e.t))) return 0.3; if (g.t === e.t) return 0.2; return 0; }
const CHS = HOPTS.map(h => { const tag = crypto.createHash('md5').update(JSON.stringify(h)).digest('hex').slice(0, 8), f = path.join(CA, 'chroma-' + tag + '.json'); return { h, f, d: fs.existsSync(f) ? JSON.parse(fs.readFileSync(f)) : {} }; });
const items = [];
for (const f of fs.readdirSync(path.join(DS, 'annotations/key')).sort()) {
  const id = f.replace(/\.key$/, ''), mp3 = path.join(AU, id + '.mp3'), c = path.join(CA, id + '.f32');
  const g = parse(fs.readFileSync(path.join(DS, 'annotations/key', f), 'utf8').split('\n')[0]); if (!g) continue;
  let x = null, ok = true; const chs = [];
  for (const B of CHS) {
    if (!B.d[id]) {
      if (!x) { if (!fs.existsSync(mp3)) { ok = false; break; } if (!fs.existsSync(c)) { try { fs.writeFileSync(c, execFileSync('ffmpeg', ['-v', 'quiet', '-i', mp3, '-ac', '1', '-ar', String(SR), '-f', 'f32le', '-'], { maxBuffer: 1 << 30 })); } catch (e) { ok = false; break; } } const raw = fs.readFileSync(c); x = new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4); }
      const ch = A.hpcp(x, SR, B.h); if (!ch) { ok = false; break; } B.d[id] = ch;
    }
    chs.push(B.d[id]);
  }
  if (ok) items.push({ id, g, chs });
}
CHS.forEach(B => fs.writeFileSync(B.f, JSON.stringify(B.d)));
const corr = (a, b) => { const n = 12, ma = a.reduce((s, v) => s + v, 0) / n, mb = b.reduce((s, v) => s + v, 0) / n; let s = 0, sa = 0, sb = 0; for (let i = 0; i < n; i++) { s += (a[i] - ma) * (b[i] - mb); sa += (a[i] - ma) ** 2; sb += (b[i] - mb) ** 2; } return sa && sb ? s / Math.sqrt(sa * sb) : 0; };
function learn1(tr, k) { const P = [new Array(12).fill(0), new Array(12).fill(0)], n = [0, 0]; tr.forEach(it => { const ch = it.chs[k], s = ch.reduce((a, b) => a + b, 0); for (let i = 0; i < 12; i++) P[it.g.m][i] += ch[(i + it.g.t) % 12] / s; n[it.g.m]++; }); return P.map((p, m) => p.map(v => v / Math.max(1, n[m]))); }
function learn(tr) { return CHS.map((_, k) => learn1(tr, k)); }
function test(te, P, bias) { let s = 0; te.forEach(it => { let b = null; for (let t = 0; t < 12; t++) for (let m = 0; m < 2; m++) { let v = m ? (bias || 0) : 0; P.forEach((Pk, k) => { const r = []; for (let i = 0; i < 12; i++) r.push(it.chs[k][(i + t) % 12]); v += (WTS[k] == null ? 1 : WTS[k]) * corr(r, Pk[m]); }); if (!b || v > b.v) b = { t, m, v }; } s += score(it.g, b); }); return 100 * s / te.length; }
const A1 = items.filter((_, i) => i % 2 === 0), B1 = items.filter((_, i) => i % 2 === 1);
const PA = learn(A1), PB = learn(B1), PAll = learn(items);
console.log(`${items.length} tracks, blocks ${JSON.stringify(HOPTS)} weights ${JSON.stringify(WTS)}`);
for (const bias of [0, 0.02, 0.05]) console.log(`minor bias ${bias}: learned on A → test B ${test(B1, PA, bias).toFixed(1)} %, learned on B → test A ${test(A1, PB, bias).toFixed(1)} %`);
PAll.forEach((P, k) => console.log('block ' + k + ' major ' + JSON.stringify(P[0].map(v => +v.toFixed(4))) + '\n        minor ' + JSON.stringify(P[1].map(v => +v.toFixed(4)))));

/* ---- discriminative: one weight per (mode, block, pitch-class-from-tonic), softmax over the 24 keys, SGD ---- */
const LOG = +(process.env.LOG || 1);
function feat(it, t) { const out = []; it.chs.forEach(ch => { const s = ch.reduce((a, b) => a + b, 0), v = []; for (let i = 0; i < 12; i++) { const x = ch[(i + t) % 12] / s; v.push(LOG ? Math.log(0.01 + x) : x); } const mu = v.reduce((a, b) => a + b, 0) / 12, sd = Math.sqrt(v.reduce((a, b) => a + (b - mu) ** 2, 0) / 12) || 1; v.forEach(x => out.push((x - mu) / sd)); }); return out; }
function train(tr, ep, lr, l2) {
  const D = tr[0].chs.length * 12, W = [new Float64Array(D), new Float64Array(D)], b = [0, 0];
  for (let e = 0; e < ep; e++) {
    for (const it of tr) {
      const F = [], z = []; for (let t = 0; t < 12; t++) { F[t] = feat(it, t); for (let m = 0; m < 2; m++) { let v = b[m]; for (let d = 0; d < D; d++) v += W[m][d] * F[t][d]; z.push({ t, m, v }); } }
      const mx = Math.max(...z.map(q => q.v)); let Z = 0; z.forEach(q => { q.p = Math.exp(q.v - mx); Z += q.p; });
      z.forEach(q => { const g = q.p / Z - (q.t === it.g.t && q.m === it.g.m ? 1 : 0); for (let d = 0; d < D; d++) W[q.m][d] -= lr * (g * F[q.t][d] + l2 * W[q.m][d]); b[q.m] -= lr * g; });
    }
  }
  return { W, b };
}
function testW(te, M) { let s = 0; te.forEach(it => { let best = null; for (let t = 0; t < 12; t++) { const F = feat(it, t); for (let m = 0; m < 2; m++) { let v = M.b[m]; for (let d = 0; d < F.length; d++) v += M.W[m][d] * F[d]; if (!best || v > best.v) best = { t, m, v }; } } s += score(it.g, best); }); return 100 * s / te.length; }
for (const [ep, lr, l2] of JSON.parse(process.env.TR || '[[20,0.01,0.001],[40,0.005,0.001],[20,0.01,0.01]]')) {
  const MA = train(A1, ep, lr, l2), MB = train(B1, ep, lr, l2);
  console.log(`trained ep ${ep} lr ${lr} l2 ${l2}: A→B ${testW(B1, MA).toFixed(1)} %, B→A ${testW(A1, MB).toFixed(1)} %`);
}
