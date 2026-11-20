// Autotune (runs on GitHub — owner 20.11: «если это делают роботы на гитхабе — делай, пока не получится»).
// KEY: hearing blocks (HPCP variants) × block combinations × training settings → train on GiantSteps MTG Key (confident),
//      score on GiantSteps Key (604, never seen). The best model is retrained on ALL data and saved for ss-audio.js.
// TEMPO: prior centre/width × onset settings on GiantSteps Tempo, exact = producer BPM (v1) or v2 within ±0.5.
// Usage: node tools/keydet/autotune.js <gs> <gsAudio> <mtg> <mtgAudio> <gst> <gstAudio> <cache> <outDir>
const { execFileSync } = require('child_process');
const fs = require('fs'), path = require('path'), crypto = require('crypto');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const [, , GS, GSA, MT, MTA, GT, GTA, CA, OUT] = process.argv, SR = 22050, T0 = Date.now();
fs.mkdirSync(CA, { recursive: true }); fs.mkdirSync(OUT, { recursive: true });
const log = []; const say = (s) => { console.log(s); log.push(s); fs.writeFileSync(path.join(OUT, 'autotune.md'), '# Autotune ' + new Date().toISOString() + '\n\n' + log.join('\n') + '\n'); };
const NAMES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'], FL = { Db: 'C#', Eb: 'D#', Gb: 'F#', Ab: 'G#', Bb: 'A#' };
function parse(s) { const m = String(s).trim().match(/^([A-G])([#b]?)\s*(major|minor|maj|min|m)?$/i); if (!m) return null; let t = m[1].toUpperCase() + (m[2] || ''); t = FL[t] || t; return { t: NAMES.indexOf(t), m: /^m(in(or)?)?$/i.test(m[3] || '') ? 1 : 0 }; }
function score(g, e) { if (g.t === e.t && g.m === e.m) return 1; const d = (e.t - g.t + 12) % 12; if (g.m === e.m && (d === 7 || d === 5)) return 0.5; if (g.m !== e.m && ((g.m && (g.t + 3) % 12 === e.t) || (!g.m && (g.t + 9) % 12 === e.t))) return 0.3; if (g.t === e.t) return 0.2; return 0; }
function pcm(id, mp3) { /* decoded in memory only — 2300 tracks as raw audio would not fit the runner's disk */ if (!fs.existsSync(mp3)) return null; try { const raw = execFileSync('ffmpeg', ['-v', 'quiet', '-i', mp3, '-ac', '1', '-ar', String(SR), '-f', 'f32le', '-'], { maxBuffer: 1 << 30 }); return new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4); } catch (e) { return null; } }

/* ================= KEY ================= */
const POOL = [
  { white: 0, harm: 8 }, { white: 0, harm: 1, minF: 30, maxF: 220 }, { white: 0, harm: 4, minF: 220, maxF: 1000 }, { white: 0, harm: 4, minF: 1000, maxF: 5000 },
  { white: 0, harm: 4 }, { white: 0, harm: 2, minF: 40, maxF: 300 }, { white: 0, harm: 4, minF: 150, maxF: 600 }, { white: 0, harm: 8, N: 8192, H: 4096 },
  { white: 0, harm: 1, minF: 25, maxF: 120 }, { white: 0, harm: 6, minF: 100, maxF: 2500 },
];
const gsList = fs.readdirSync(path.join(GS, 'annotations/key')).filter(f => /\.key$/.test(f)).map(f => { const id = f.replace(/\.key$/, ''); return { id, key: fs.readFileSync(path.join(GS, 'annotations/key', f), 'utf8').split('\n')[0], mp3: path.join(GSA, id + '.mp3') }; });
const mtList = fs.readFileSync(path.join(MT, 'annotations/annotations.txt'), 'utf8').trim().split('\n').slice(1).map(l => l.split('\t')).filter(c => c[2] && c[2].trim() === '2').map(c => ({ id: 'mtg' + c[0], key: c[1], mp3: path.join(MTA, c[0] + '.LOFI.mp3') }));
const chFile = path.join(CA, 'pool-chroma.json'); const CH = fs.existsSync(chFile) ? JSON.parse(fs.readFileSync(chFile)) : {};
function build(list) {
  const out = [];
  for (const L of list) {
    const g = parse(L.key); if (!g) continue;
    if (!CH[L.id] || CH[L.id].length !== POOL.length) { const x = pcm(L.id, L.mp3); if (!x) continue; CH[L.id] = POOL.map(h => { const c = A.hpcp(x, SR, h); return c ? c.map(v => +v.toFixed(5)) : null; }); }
    if (CH[L.id].some(c => !c)) continue;
    out.push({ id: L.id, g, all: CH[L.id] });
  }
  fs.writeFileSync(chFile, JSON.stringify(CH));
  return out;
}
const LIM = +(process.env.LIMIT || 0), cut = (a) => LIM ? a.slice(0, LIM) : a;
const TE = build(cut(gsList)), TR = build(cut(mtList));
say(`## Key\n\ntrain ${TR.length} (GiantSteps MTG, confident) → test ${TE.length} (GiantSteps Key, never seen) · hearing ready in ${Math.round((Date.now() - T0) / 60000)} min\n`);
function feat(chs, t, LOG) { const out = []; chs.forEach(ch => { let s = 0; for (let i = 0; i < 12; i++) s += ch[i]; const v = []; for (let i = 0; i < 12; i++) { const x = ch[(i + t) % 12] / s; v.push(LOG ? Math.log(0.01 + x) : x); } const mu = v.reduce((a, b) => a + b, 0) / 12, sd = Math.sqrt(v.reduce((a, b) => a + (b - mu) ** 2, 0) / 12) || 1; v.forEach(x => out.push((x - mu) / sd)); }); return out; }
function train(items, blocks, [EP, LR, L2], LOG) {
  const D = blocks.length * 12, W = [new Float64Array(D), new Float64Array(D)], b = [0, 0];
  const pre = items.map(it => { const chs = blocks.map(k => it.all[k]); const F = []; for (let t = 0; t < 12; t++) F.push(feat(chs, t, LOG)); return { g: it.g, F }; });
  for (let e = 0; e < EP; e++) {
    const lr = LR * (1 - 0.7 * e / EP);
    for (const it of pre) {
      const z = []; for (let t = 0; t < 12; t++) for (let m = 0; m < 2; m++) { let v = b[m]; const F = it.F[t]; for (let d = 0; d < D; d++) v += W[m][d] * F[d]; z.push({ t, m, v }); }
      let mx = -1e9; z.forEach(q => { if (q.v > mx) mx = q.v; }); let Z = 0; z.forEach(q => { q.p = Math.exp(q.v - mx); Z += q.p; });
      z.forEach(q => { const gr = q.p / Z - (q.t === it.g.t && q.m === it.g.m ? 1 : 0); const F = it.F[q.t]; for (let d = 0; d < D; d++) W[q.m][d] -= lr * (gr * F[d] + L2 * W[q.m][d]); b[q.m] -= lr * gr; });
    }
  }
  return { W: W.map(w => Array.from(w)), b };
}
function test(items, blocks, M, LOG) { let s = 0, ex = 0; for (const it of items) { const chs = blocks.map(k => it.all[k]); let best = null; for (let t = 0; t < 12; t++) { const F = feat(chs, t, LOG); for (let m = 0; m < 2; m++) { let v = M.b[m]; for (let d = 0; d < F.length; d++) v += M.W[m][d] * F[d]; if (!best || v > best.v) best = { t, m, v }; } } const sc = score(it.g, best); s += sc; if (sc === 1) ex++; } return { mirex: 100 * s / items.length, exact: 100 * ex / items.length }; }
const COMBOS = LIM ? [[0, 1, 2, 3]] : [[0, 1, 2, 3], [0, 1, 2, 3, 4], [0, 1, 2, 3, 5, 6], [0, 1, 2, 3, 8, 9], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [0, 1, 3, 6, 8], [4, 1, 2, 3], [7, 1, 2, 3, 5]];
const SETS = LIM ? [[2, 0.003, 0.003]] : [[60, 0.003, 0.003], [100, 0.002, 0.003], [60, 0.003, 0.01]];
let bestK = null;
say('| hearing blocks | training | MIREX % | exact % |\n|---|---|---|---|');
for (const c of COMBOS) for (const st of SETS) for (const LOG of [1]) {
  const M = train(TR, c, st, LOG), r = test(TE, c, M, LOG);
  say(`| ${JSON.stringify(c)} | ${JSON.stringify(st)} | ${r.mirex.toFixed(1)} | ${r.exact.toFixed(1)} |`);
  if (!bestK || r.mirex > bestK.r.mirex) bestK = { c, st, LOG, r };
}
say(`\n**Best key: ${bestK.r.mirex.toFixed(1)} % MIREX (${bestK.r.exact.toFixed(1)} % exact) — blocks ${JSON.stringify(bestK.c)}, training ${JSON.stringify(bestK.st)}.** Final model retrained on all ${TR.length + TE.length} tracks.\n`);
const MF = train(TR.concat(TE), bestK.c, bestK.st, bestK.LOG);
fs.writeFileSync(path.join(OUT, 'best-key-model.json'), JSON.stringify({ blocks: bestK.c.map(k => POOL[k]), log: bestK.LOG, W: MF.W.map(w => w.map(v => +v.toFixed(4))), b: MF.b.map(v => +v.toFixed(4)), test: bestK.r }));

/* ================= TEMPO ================= */
const tdir = path.join(GT, 'annotations_v2/tempo');
const TGRID = []; for (const center of [130, 135, 140, 145]) for (const sd of [0.8, 1, 1.3]) for (const kick of [1, 2]) TGRID.push({ center, sd, kick });
const RT = TGRID.map(() => ({ ex: 0, a1: 0, a2: 0 })); let nT = 0;
for (const f of cut(fs.readdirSync(tdir).filter(f => /\.bpm$/.test(f)).sort())) { /* each track decoded once, every setting scored on it */
  const id = f.replace(/\.bpm$/, ''), g = parseFloat(fs.readFileSync(path.join(tdir, f), 'utf8')), v1f = path.join(GT, 'annotations/tempo', f), g1 = fs.existsSync(v1f) ? parseFloat(fs.readFileSync(v1f, 'utf8')) : g;
  if (!(g > 0)) continue; const x = pcm('t' + id, path.join(GTA, id + '.mp3')); if (!x) continue; nT++;
  TGRID.forEach((o, i) => { const t = A.tempo(x, SR, o).bpm, r = RT[i]; if (Math.abs(t - g) <= 0.5 || Math.abs(t - g1) <= 0.5) r.ex++; if (Math.abs(t - g) <= g * 0.04) r.a1++; if ([1, 2, 0.5, 3, 1 / 3].some(k => Math.abs(t - g * k) <= g * k * 0.04)) r.a2++; });
}
say(`## Tempo\n\n${nT} tracks (GiantSteps Tempo); exact = producer BPM (v1) or corrected (v2) within ±0.5\n\n| settings | exact % | within 4 % | ×2/½ ok % |\n|---|---|---|---|`);
let bestT = null;
TGRID.forEach((o, i) => { const R = RT[i], r = { exact: 100 * R.ex / nT, acc1: 100 * R.a1 / nT, acc2: 100 * R.a2 / nT }; say(`| ${JSON.stringify(o)} | ${r.exact.toFixed(1)} | ${r.acc1.toFixed(1)} | ${r.acc2.toFixed(1)} |`); if (!bestT || r.exact > bestT.r.exact) bestT = { o, r }; });
say(`\n**Best tempo: ${bestT.r.exact.toFixed(1)} % exact — ${JSON.stringify(bestT.o)}.** Total ${Math.round((Date.now() - T0) / 60000)} min.`);
fs.writeFileSync(path.join(OUT, 'best-tempo.json'), JSON.stringify(bestT));
