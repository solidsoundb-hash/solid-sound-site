// Copy-protection check: every track vs a disguised copy of itself (64 kbps, start cut by 7.3 s, −6 dB, mono 44.1k)
// and vs every other track. Copies must score high, different tracks low.
const { execFileSync } = require('child_process');
const fs = require('fs');
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const SR = 22050;
const dec = (args) => { const raw = execFileSync('ffmpeg', ['-v', 'quiet'].concat(args, ['-ac', '1', '-ar', String(SR), '-f', 'f32le', '-']), { maxBuffer: 1 << 30 }); return new Float32Array(raw.buffer, raw.byteOffset, raw.length / 4); };
const rows = fs.readFileSync(process.argv[2], 'utf8').trim().split('\n').map(l => l.split('\t'));
const T = [];
for (const [name, , file] of rows) {
  const orig = A.fingerprint(dec(['-i', file]), SR);
  const tmp = '/tmp/claude-0/-home-user-solid-sound-site/5199c965-d6aa-5d4c-9455-28dd7a5cf06b/scratchpad/fpcopy.mp3';
  execFileSync('ffmpeg', ['-v', 'quiet', '-y', '-ss', '7.3', '-i', file, '-af', 'volume=-6dB', '-b:a', '64k', tmp]);
  const copy = A.fingerprint(dec(['-i', tmp]), SR);
  T.push({ name, orig, copy });
}
let worstCopy = 1, bestOther = 0;
for (const a of T) {
  const self = A.fpMatch(a.copy, a.orig); worstCopy = Math.min(worstCopy, self);
  const others = T.filter(b => b !== a).map(b => [b.name, A.fpMatch(a.copy, b.orig)]).sort((x, y) => y[1] - x[1]);
  bestOther = Math.max(bestOther, others[0][1]);
  console.log(a.name.padEnd(34), 'hashes', String(a.orig.length).padStart(4), '| copy vs itself', self.toFixed(2), '| closest other', others[0][0], others[0][1].toFixed(2));
}
console.log(`\nweakest copy ${worstCopy.toFixed(2)}, strongest false match ${bestOther.toFixed(2)}`);
