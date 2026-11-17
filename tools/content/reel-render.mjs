// Renders one vertical Reel (1080x1920, 30 fps) from reels.json: voice-over (Piper, free, offline), the product cover
// with a slow zoom on a blurred background, burned-in captions, the product's own audio preview under the voice,
// the price, and an end card. Owner 17.11.2026: «рилсы с голосом».
//   node tools/content/reel-render.mjs <reels.json> <index> <out.mp4>     (env: PIPER, PIPER_VOICE, FONT)
import fs from 'node:fs';
import path from 'node:path';
import { execFileSync } from 'node:child_process';

const [PLAN, IDX, OUT] = process.argv.slice(2);
const r = JSON.parse(fs.readFileSync(PLAN, 'utf8'))[+IDX];
if (!r) { console.log('no reel', IDX); process.exit(0); }
const PIPER = process.env.PIPER || 'piper/piper', VOICE = process.env.PIPER_VOICE || 'voice.onnx';
const FONT = process.env.FONT || '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
const W = fs.mkdtempSync('reel-'), F = (n) => path.join(W, n);
const run = (cmd, args, input) => execFileSync(cmd, args, { stdio: input ? ['pipe', 'inherit', 'inherit'] : 'inherit', input });
const dur = (f) => +execFileSync('ffprobe', ['-v', 'error', '-show_entries', 'format=duration', '-of', 'csv=p=0', f], { encoding: 'utf8' }).trim();
const UA = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/130 Safari/537.36';
async function dl(u, f) { if (u.startsWith('/')) { fs.copyFileSync(u, f); return; } /* local file (tests) */ for (let i = 0; i < 6; i++) { try { const x = await fetch(u, { headers: { 'User-Agent': UA } }); if (x.ok) { fs.writeFileSync(f, Buffer.from(await x.arrayBuffer())); return; } } catch { /* retry */ } await new Promise(z => setTimeout(z, 8000 * (i + 1))); } throw new Error('download ' + u); }
const wrap = (t, n) => { const out = []; let cur = ''; for (const w of String(t).split(/\s+/)) { if ((cur + ' ' + w).trim().length > n) { if (cur) out.push(cur); cur = w; } else cur = (cur + ' ' + w).trim(); } if (cur) out.push(cur); return out.join('\n'); };

await dl(r.img, F('cover.jpg'));
await dl(r.audio, F('music.mp3'));

/* voice: hook + lines + call to action, one wav each, with small pauses */
const said = [r.hook, ...r.lines, r.cta];
const segs = []; let t = 0.6;                                       // a short music intro first
const parts = [];
said.forEach((s, i) => {
  const wav = F(`v${i}.wav`); run(PIPER, ['--model', VOICE, '--output_file', wav, '--length_scale', '1.0'], Buffer.from(s.replace(/solid-sound dot net/i, 'solid sound dot net')));
  const d = dur(wav); segs.push({ text: s.replace(/solid-sound dot net/i, 'solid-sound.net'), a: t, b: t + d }); parts.push(wav); t += d + 0.35;
});
const END = 2.8, T = t + END;                                       // end card after the voice
/* voice track with exact timing */
const vin = parts.flatMap(p => ['-i', p]);
const delays = parts.map((p, i) => `[${i}:a]adelay=${Math.round(segs[i].a * 1000)}|${Math.round(segs[i].a * 1000)}[d${i}]`).join(';');
run('ffmpeg', ['-y', '-v', 'error', ...vin, '-filter_complex', `${delays};${parts.map((p, i) => `[d${i}]`).join('')}amix=inputs=${parts.length}:normalize=0,apad=whole_dur=${T.toFixed(2)}[v]`, '-map', '[v]', '-ar', '44100', '-ac', '2', F('voice.wav')]);
/* music: from 25% into the preview (usually past the intro), under the voice, fades */
const md = dur(F('music.mp3')); const ss = Math.max(0, Math.min(md * 0.25, md - T - 1));
run('ffmpeg', ['-y', '-v', 'error', '-ss', ss.toFixed(2), '-t', T.toFixed(2), '-i', F('music.mp3'), '-i', F('voice.wav'), '-filter_complex',
  `[0:a]volume=0.32,afade=t=in:d=0.6,afade=t=out:st=${(T - 1.5).toFixed(2)}:d=1.5,aresample=44100[m];[1:a]volume=1.6[v];[m][v]amix=inputs=2:normalize=0:duration=longest,alimiter=limit=0.95[a]`, '-map', '[a]', F('mix.wav')]);

/* text files (drawtext reads them, no escaping problems) */
const tf = (n, s) => { fs.writeFileSync(F(n), s); return n; };
tf('brand.txt', 'SOLID SOUND');
const nameL = wrap(r.name, 22).split('\n'); nameL.forEach((l, j) => tf(`n${j}.txt`, l));
tf('price.txt', r.regText ? `${r.priceText}  (was ${r.regText})` : r.priceText);
tf('end1.txt', 'solid-sound.net'); tf('end2.txt', 'Link in bio');
const capL = segs.map((s, i) => wrap(s.text, 26).split('\n').map((l, j) => tf(`c${i}_${j}.txt`, l)));
const dt = (file, extra) => `drawtext=fontfile=${FONT}:textfile=${file}:fontcolor=white:${extra}`;
const fr = Math.ceil(T * 30);
const vf = [
  `[0:v]scale=1080:1920:force_original_aspect_ratio=increase,crop=1080:1920,boxblur=28:4,eq=brightness=-0.28:saturation=1.2[bg]`,
  `[0:v]scale=1600:1600,zoompan=z='min(1+on/${fr}*0.12,1.12)':x='iw/2-(iw/zoom/2)':y='ih/2-(ih/zoom/2)':d=${fr}:s=940x940:fps=30[fg]`,
  `[bg][fg]overlay=70:330:shortest=1[b0]`,
  `[b0]${dt('brand.txt', `fontsize=40:fontcolor=0xF59E0B:x=(w-tw)/2:y=210`)}[b1]`,
  /* every line is its own drawtext, so each one is centred */
  `[b1]${nameL.map((l, j) => dt(`n${j}.txt`, `fontsize=64:x=(w-tw)/2:y=${1310 + j * 78}`)).join(',')}[b2]`,
  `[b2]${dt('price.txt', `fontsize=58:fontcolor=0xF59E0B:x=(w-tw)/2:y=${1310 + nameL.length * 78 + 14}`)}[b3]`,
  ...segs.map((s, i) => `[b${3 + i}]${capL[i].map((f, j) => dt(f, `fontsize=54:x=(w-tw)/2:y=${1580 + j * 76}:box=1:boxcolor=black@0.62:boxborderw=16:enable='between(t,${s.a.toFixed(2)},${(s.b + 0.3).toFixed(2)})'`)).join(',')}[b${4 + i}]`),
  `[b${3 + segs.length}]drawbox=x=0:y=0:w=iw:h=ih:color=black@0.72:t=fill:enable='gte(t,${(T - END).toFixed(2)})'[e0]`,
  `[e0]${dt('end1.txt', `fontsize=88:fontcolor=0xF59E0B:x=(w-tw)/2:y=820:enable='gte(t,${(T - END).toFixed(2)})'`)}[e1]`,
  `[e1]${dt('end2.txt', `fontsize=56:x=(w-tw)/2:y=960:enable='gte(t,${(T - END).toFixed(2)})'`)},format=yuv420p[v]`,
].join(';');
process.chdir(W);
run('ffmpeg', ['-y', '-v', 'error', '-loop', '1', '-framerate', '30', '-t', T.toFixed(2), '-i', 'cover.jpg', '-i', 'mix.wav', '-filter_complex', vf,
  '-map', '[v]', '-map', '1:a', '-c:v', 'libx264', '-preset', 'medium', '-crf', '21', '-profile:v', 'high', '-r', '30', '-c:a', 'aac', '-b:a', '192k', '-shortest', '-movflags', '+faststart', path.resolve('..', OUT)]);
console.log('ok', OUT, T.toFixed(1) + ' s');
