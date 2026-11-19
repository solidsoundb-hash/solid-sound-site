// Synthetic sanity check: chord progressions with a known key (saw-like tones with overtones + a four-on-the-floor kick).
const A = require('../../plugin/solid-sound-custom/src/audio/ss-audio.js');
const SR = 22050;
const NAMES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
function render(chords, bars, kick, detune) {
  const sec = bars * chords.length * 2, x = new Float32Array(sec * SR);
  for (let c = 0; c < bars * chords.length; c++) {
    const notes = chords[c % chords.length], t0 = c * 2 * SR;
    for (const m of notes) { const f = 440 * Math.pow(2, (m - 69 + detune) / 12);
      for (let i = 0; i < 2 * SR; i++) { let v = 0; for (let h = 1; h <= 6; h++) v += Math.sin(2 * Math.PI * f * h * i / SR) / h; x[t0 + i] += 0.08 * v; } }
  }
  if (kick) for (let b = 0; b < sec * 2; b++) { const t0 = Math.round(b * SR / 2); for (let i = 0; i < 0.25 * SR && t0 + i < x.length; i++) { const f = 50 + 100 * Math.exp(-i / (0.02 * SR)); x[t0 + i] += 0.9 * Math.exp(-i / (0.08 * SR)) * Math.sin(2 * Math.PI * f * i / SR); } }
  return x;
}
const tri = (r, minor) => [r, r + (minor ? 3 : 4), r + 7];
const cases = [
  ['Am  i-VI-III-VII', [tri(57, 1).concat([45]), tri(53).concat([41]), tri(48).concat([48]), tri(55).concat([43])]],
  ['C   I-V-vi-IV', [tri(60).concat([48]), tri(55).concat([43]), tri(57, 1).concat([45]), tri(53).concat([41])]],
  ['Bm  i-VI-VII', [tri(59, 1).concat([47]), tri(55).concat([43]), tri(57).concat([45])]],
  ['G   I-IV-V', [tri(55).concat([43]), tri(60).concat([48]), tri(62).concat([50])]],
];
const opt = JSON.parse(process.env.OPT || '{}');
for (const [name, ch] of cases) for (const kick of [false, true]) for (const det of [0, 0.3]) {
  const r = A.key(render(ch, 4, kick, det), SR, opt);
  console.log(name.padEnd(18), kick ? 'kick' : '    ', 'detune ' + det, '→', r.key.padEnd(4), 'conf', r.confidence, '| chroma', r.chroma.map((v, i) => NAMES[i] + Math.round(v * 100)).join(' '));
}
