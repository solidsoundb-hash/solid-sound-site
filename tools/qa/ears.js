// QA bot "ears" (owner 17.11.2026): listen to every sound preview the site plays.
// The browser part of the bot only sees that a track's time is running; here each preview file is downloaded and
// analysed with ffmpeg: silent file, silence at the start, silent gap in the middle, too quiet, overloaded (clipping),
// too short, file missing. Results come back as bug objects for qa.js.
'use strict';
const { execFileSync, spawnSync } = require('child_process');
const fs = require('fs');
const os = require('os');
const path = require('path');

function hasFfmpeg() { try { execFileSync('ffmpeg', ['-version'], { stdio: 'ignore' }); return true; } catch (e) { return false; } }
const name = (u) => decodeURIComponent(String(u).split('?')[0].split('/').pop());

function analyse(file) {
  // volumedetect: mean/max volume; silencedetect: silent parts (< -50 dB for 1.5 s+); ffprobe: duration
  const r = spawnSync('ffmpeg', ['-hide_banner', '-nostats', '-i', file, '-af', 'silencedetect=n=-50dB:d=1.5,volumedetect', '-f', 'null', '-'], { encoding: 'utf8', timeout: 120000 });
  const out = String(r.stderr || '') + String(r.stdout || '');
  let dur = 0; try { dur = +execFileSync('ffprobe', ['-v', 'error', '-show_entries', 'format=duration', '-of', 'csv=p=0', file], { encoding: 'utf8', timeout: 60000 }).trim() || 0; } catch (e) {}
  const num = (re) => { const m = out.match(re); return m ? +m[1] : null; };
  const sil = []; const re = /silence_start: ([\d.]+)[\s\S]*?silence_end: ([\d.]+)/g; let m;
  while ((m = re.exec(out))) sil.push([+m[1], +m[2]]);
  const starts = [...out.matchAll(/silence_start: ([\d.]+)/g)].map(x => +x[1]);
  if (starts.length > sil.length && dur) sil.push([starts[starts.length - 1], dur]); /* silence until the end of the file */
  return { dur, mean: num(/mean_volume: (-?[\d.]+) dB/), max: num(/max_volume: (-?[\d.]+) dB/), sil };
}

// urls: Map(url -> Set(page where it is used)); get(url) -> Buffer|null
async function run(urls, get, limit) {
  const bugs = [];
  if (!hasFfmpeg()) return { bugs, checked: 0, skipped: 'ffmpeg не установлен' };
  const dir = fs.mkdtempSync(path.join(os.tmpdir(), 'qa-ears-'));
  let checked = 0;
  for (const [u, pages] of [...urls].slice(0, limit)) {
    const where = [...pages][0] || '/';
    const base = { page: where, device: 'звук', steps: [`Открыть https://solid-sound.net${where}`, `Нажать ▶ у трека «${name(u)}»`] };
    let buf = null; try { buf = await get(u); } catch (e) {}
    if (!buf || buf.length < 2000) { bugs.push({ ...base, sev: 'major', type: 'ears-missing', sig: 'ears-missing|' + name(u), title: `Файл превью не скачивается: ${name(u)}`, expected: 'Файл открывается и играет', actual: 'Сервер не отдал файл (или он пустой)' }); continue; }
    const f = path.join(dir, 'a' + checked + path.extname(name(u)).slice(0, 5));
    fs.writeFileSync(f, buf); checked++;
    const a = analyse(f); try { fs.unlinkSync(f); } catch (e) {}
    const t = `«${name(u)}»`;
    const total = a.sil.reduce((n, [s, e]) => n + (e - s), 0);
    if (a.dur && total >= a.dur - 1) { bugs.push({ ...base, sev: 'critical', type: 'ears-silent', sig: 'ears-silent|' + name(u), title: `Превью ${t} — полная тишина`, expected: 'В превью слышна музыка', actual: `Весь файл (${a.dur.toFixed(0)} с) тише −50 дБ` }); continue; }
    const head = a.sil.find(([s]) => s < 0.3);
    if (head && head[1] >= 2) bugs.push({ ...base, sev: 'minor', type: 'ears-start', sig: 'ears-start|' + name(u), title: `Превью ${t} начинается с тишины (${head[1].toFixed(1)} с)`, expected: 'Музыка с первой секунды — покупатель слушает 2–3 секунды', actual: `Первые ${head[1].toFixed(1)} с тишина` });
    const gap = a.sil.find(([s, e]) => s >= 0.3 && e < a.dur - 0.5 && e - s >= 3);
    if (gap) bugs.push({ ...base, sev: 'major', type: 'ears-gap', sig: 'ears-gap|' + name(u), title: `В превью ${t} провал тишины`, expected: 'Звук без пауз', actual: `Тишина с ${gap[0].toFixed(1)} по ${gap[1].toFixed(1)} с` });
    if (a.mean !== null && a.mean < -32) bugs.push({ ...base, sev: 'minor', type: 'ears-quiet', sig: 'ears-quiet|' + name(u), title: `Превью ${t} слишком тихое`, expected: 'Громкость как у остальных превью (около −14…−20 дБ в среднем)', actual: `Средняя громкость ${a.mean} дБ` });
    if (a.max !== null && a.max >= -0.05 && a.mean !== null && a.mean > -9) bugs.push({ ...base, sev: 'minor', type: 'ears-clip', sig: 'ears-clip|' + name(u), title: `Превью ${t} может хрипеть (перегруз)`, expected: 'Пики ниже 0 дБ', actual: `Пик ${a.max} дБ при средней громкости ${a.mean} дБ` });
    if (a.dur && a.dur < 10) bugs.push({ ...base, sev: 'minor', type: 'ears-short', sig: 'ears-short|' + name(u), title: `Превью ${t} слишком короткое (${a.dur.toFixed(0)} с)`, expected: 'Превью 20–60 с', actual: `${a.dur.toFixed(1)} с` });
  }
  try { fs.rmSync(dir, { recursive: true, force: true }); } catch (e) {}
  return { bugs, checked };
}

module.exports = { run, analyse, hasFfmpeg };
