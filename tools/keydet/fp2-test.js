// fingerprint2 (pitch/tempo-proof copy protection) on disguised copies. Usage: node tools/keydet/fp2-test.js <mp3 dir> [n]
const { execFileSync } = require('child_process'); const fs = require('fs'), path = require('path');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js'); const SR = 22050;
const dec = (f, af) => { const raw = execFileSync('ffmpeg', ['-v', 'quiet', '-i', f].concat(af ? ['-af', af] : [], ['-ac', '1', '-ar', String(SR), '-f', 'f32le', '-']), { maxBuffer: 1 << 30 }); return new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4); };
const st = (n) => Math.pow(2, n / 12);
const MODS = { 'key+1': `asetrate=44100*${st(1)},aresample=44100,atempo=${1 / st(1)}`, 'key-2': `asetrate=44100*${st(-2)},aresample=44100,atempo=${1 / st(-2)}`, 'tempo+5%': 'atempo=1.05', 'tempo-4%': 'atempo=0.96', 'speed+6%': `asetrate=44100*${st(1)},aresample=44100`, 'cut+quiet': 'volume=-6dB,atrim=start=7.3' };
const dir = process.argv[2], N = +(process.argv[3] || 12);
const files = fs.readdirSync(dir).filter(f => /\.mp3$/.test(f)).slice(0, N).map(f => path.join(dir, f));
const T = files.map(f => ({ o: A.fingerprint2(dec(f), SR), m: Object.fromEntries(Object.entries(MODS).map(([k, af]) => [k, A.fingerprint2(dec(f, 'aresample=44100,' + af), SR)])) }));
console.log('hashes per track ~', Math.round(T.reduce((a, t) => a + t.o.length / 2, 0) / T.length));
const sc = {}; const fl = [];
for (const a of T) { for (const k in MODS) (sc[k] = sc[k] || []).push(A.fpMatch2(a.m[k], a.o)); for (const b of T) if (b !== a) { fl.push(A.fpMatch2(a.o, b.o)); for (const k in MODS) fl.push(A.fpMatch2(a.m[k], b.o)); } }
const worst = (v) => v.reduce((p, q) => q.n < p.n ? q : p);
console.log(Object.entries(sc).map(([k, v]) => `${k}: weakest ${worst(v).n} hits (${(100 * worst(v).s).toFixed(2)} %)`).join(' · '));
fl.sort((p, q) => q.n - p.n); console.log(`different tracks: most ${fl[0].n} hits (${(100 * fl[0].s).toFixed(2)} %), next ${fl[1].n}`);
