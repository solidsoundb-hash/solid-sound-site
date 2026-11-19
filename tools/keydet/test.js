// Checks SS Audio key detection on tracks with a known key.
// Usage: node tools/keydet/test.js <list.tsv: name<TAB>key<TAB>file.mp3>   (needs ffmpeg)
const { execFileSync } = require('child_process');
const fs = require('fs');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const SR = 22050;
const norm = k => k.replace('Db', 'C#').replace('Eb', 'D#').replace('Gb', 'F#').replace('Ab', 'G#').replace('Bb', 'A#').replace(/min(or)?$/i, 'm').replace(/maj(or)?$/i, '');
let ok = 0, rel = 0, n = 0;
for (const line of fs.readFileSync(process.argv[2], 'utf8').trim().split('\n')) {
  const [name, want, file] = line.split('\t');
  const raw = execFileSync('ffmpeg', ['-v', 'quiet', '-i', file, '-ac', '1', '-ar', String(SR), '-f', 'f32le', '-'], { maxBuffer: 1 << 30 });
  const x = new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4);
  const t0 = Date.now(); const r = A.key(x, SR, JSON.parse(process.env.OPT || '{}')); const ms = Date.now() - t0;
  const L = A.loudness([x], SR);
  const hit = norm(r.key) === norm(want), isRel = !hit && norm(r.alt) === norm(want);
  n++; if (hit) ok++; if (isRel) rel++;
  if (process.env.CHROMA) console.log('   ' + r.chroma.map((v, i) => ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'][i] + ':' + Math.round(v * 100)).join(' '));
  console.log((hit? '✓' : isRel ? '≈' : '✗') + ' ' + name.padEnd(36) + ' want ' + want.padEnd(4) + ' got ' + r.key.padEnd(4) + ' conf ' + r.confidence + ' score ' + r.score + ' (alt ' + r.alt + ' Δ' + r.alt_close + ') ' + ms + 'ms  ' + L.lufs + ' LUFS');
}
console.log(`\n${ok}/${n} exact, ${rel} relative major/minor`);
