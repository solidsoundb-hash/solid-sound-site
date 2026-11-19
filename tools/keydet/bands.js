// Per-band chroma of each test track: bass (40–200 Hz), mid (200–1000), high (1000–2500). Shows what each register plays.
const { execFileSync } = require('child_process');
const fs = require('fs');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const SR = 22050, NAMES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
for (const line of fs.readFileSync(process.argv[2], 'utf8').trim().split('\n')) {
  const [name, want, file] = line.split('\t');
  const raw = execFileSync('ffmpeg', ['-v', 'quiet', '-i', file, '-ac', '1', '-ar', String(SR), '-f', 'f32le', '-'], { maxBuffer: 1 << 30 });
  const x = new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4);
  console.log(name + ' (want ' + want + ')');
  for (const [lab, lo, hi] of [['bass', 40, 200], ['mid ', 200, 1000], ['high', 1000, 2500]]) {
    const c = A.chroma(x, SR, { lo, hi, persist: 2, bass: 1 });
    const top = c.map((v, i) => [v, NAMES[i]]).sort((a, b) => b[0] - a[0]).slice(0, 5).map(p => p[1] + Math.round(p[0] * 100)).join(' ');
    console.log('   ' + lab + ': ' + top);
  }
}
