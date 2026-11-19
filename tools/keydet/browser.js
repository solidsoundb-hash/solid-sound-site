// Runs the built ss-audio asset in Chromium on one MP3: decode + key + loudness, with timing.
const { chromium } = require('playwright');
const fs = require('fs'), path = require('path');
(async () => {
  const dir = path.join(__dirname, '../../plugin/solid-sound-custom/build/assets');
  const lib = fs.readdirSync(dir).find(f => /^ss-audio\..*\.js$/.test(f));
  const b = await chromium.launch(); const p = await b.newPage();
  await p.setContent('<html><body></body></html>');
  await p.addScriptTag({ path: path.join(dir, lib) });
  const b64 = fs.readFileSync(process.argv[2]).toString('base64');
  console.log(await p.evaluate(async (b64) => {
    const bin = Uint8Array.from(atob(b64), c => c.charCodeAt(0));
    const t0 = performance.now(); const ctx = new AudioContext(); const buf = await ctx.decodeAudioData(bin.buffer);
    const t1 = performance.now(); let x = SSAudio.mono(buf), sr = buf.sampleRate, max = 180 * sr; if (x.length > max) { const s = Math.floor((x.length - max) / 2); x = x.subarray(s, s + max); }
    const r = SSAudio.key(x, sr); const t2 = performance.now(); const L = SSAudio.loudness([buf.getChannelData(0)], sr);
    return `decode ${Math.round(t1 - t0)} ms, key ${Math.round(t2 - t1)} ms → ${r.key} (conf ${r.confidence}, alt ${r.alt}), ${L.lufs} LUFS`;
  }, b64));
  await b.close();
})();
