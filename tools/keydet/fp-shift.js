// Copy protection vs disguised copies (owner 20.11 «важно»): pitch ±1–2 semitones, tempo ±5 %, speed (pitch+tempo) +6 %.
// Usage: node tools/keydet/fp-shift.js <dir with mp3> [n]   — prints copy scores and the strongest false match.
const { execFileSync } = require('child_process'); const fs = require('fs'), path = require('path');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js'); const SR = 22050;
const dec = (f, af) => { const raw = execFileSync('ffmpeg', ['-v', 'quiet', '-t', '150', '-i', f].concat(af ? ['-af', af] : [], ['-ac', '1', '-ar', String(SR), '-f', 'f32le', '-']), { maxBuffer: 1 << 30 }); return new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4); };
const st = (n) => Math.pow(2, n / 12);
const MODS = { 'pitch+1': `asetrate=44100*${st(1)},aresample=44100,atempo=${1 / st(1)}`, 'pitch-2': `asetrate=44100*${st(-2)},aresample=44100,atempo=${1 / st(-2)}`, 'tempo+5%': 'atempo=1.05', 'tempo-4%': 'atempo=0.96', 'speed+6%': `asetrate=44100*${st(1)},aresample=44100`, 'cut+mp3': 'volume=-6dB,atrim=start=7.3' };
const dir = process.argv[2], N = +(process.argv[3] || 16);
const files = fs.readdirSync(dir).filter(f => /\.mp3$/.test(f)).slice(0, N).map(f => path.join(dir, f));
const T = files.map(f => ({ f, o: A.fingerprint(dec(f), SR), m: Object.fromEntries(Object.entries(MODS).map(([k, af]) => [k, A.fingerprint(dec(f, 'aresample=44100,' + af), SR)])) }));
const worst = {}; let falseMax = 0;
for (const a of T) { for (const k in MODS) { const s = A.fpMatch(a.m[k], a.o); worst[k] = Math.min(worst[k] ?? 1, s); }
  for (const b of T) if (b !== a) falseMax = Math.max(falseMax, A.fpMatch(a.o, b.o), ...Object.keys(MODS).map(k => A.fpMatch(a.m[k], b.o))); }
console.log('weakest copy score per disguise:', Object.entries(worst).map(([k, v]) => k + ' ' + (100 * v).toFixed(0) + '%').join(' · '));
console.log('strongest false match between different tracks:', (100 * falseMax).toFixed(0) + '%', '(alarm at 15 %)');
