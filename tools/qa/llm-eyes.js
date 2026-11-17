// "Neural eyes" (17.11.2026, owner: «навороченный тестировщик… с локальной ЛЛМ»).
// A local vision model (Ollama on the GitHub robot, free) looks at the page screenshots the tester made
// and lists visible defects: overlapping elements, cut text, broken layout, empty holes, broken pictures.
//   node tools/qa/llm-eyes.js <dir with eyes/*.jpg> <out dir>      (env: OLLAMA_MODEL, EYES_MAX, EYES_MIN)
// Output: <out>/bugs.json in the tester's format (all as "Проверить глазами"), merged into the main report.
'use strict';
const fs = require('fs');
const path = require('path');
const os = require('os');
const { execFileSync } = require('child_process');

const [SRC, OUT] = process.argv.slice(2);
const MODEL = process.env.OLLAMA_MODEL || 'qwen2.5vl:3b';
const HOST = process.env.OLLAMA_HOST_URL || 'http://127.0.0.1:11434';
const MAX = +(process.env.EYES_MAX || 40);            // pictures per robot
const DEADLINE = Date.now() + (+(process.env.EYES_MIN || 120)) * 60000;
const TILE = 1100;                                    // tile height, px (one screen and a bit)

const bugs = [], notChecked = [];
const stats = { eyesTiles: 0, eyesPages: 0 };
const DEV = { desktop: 'компьютер', phone: 'телефон', a31: 'Samsung A31' };

function size(f) { const o = execFileSync('ffprobe', ['-v', 'error', '-select_streams', 'v:0', '-show_entries', 'stream=width,height', '-of', 'csv=p=0', f], { encoding: 'utf8' }).trim().split(','); return { w: +o[0], h: +o[1] }; }
function tiles(f, dir) {
  const { w, h } = size(f); const out = [];
  for (let y = 0, i = 0; y < h && i < 5; y += TILE, i++) {
    const t = path.join(dir, `${path.basename(f, '.jpg')}_${i}.jpg`);
    const ch = Math.min(TILE, h - y); if (ch < 200) break;
    // wide desktop shots are scaled down: the model sees ~1000 px anyway
    execFileSync('ffmpeg', ['-v', 'error', '-y', '-i', f, '-vf', `crop=${w}:${ch}:0:${y}${w > 1000 ? ',scale=1000:-2' : ''}`, '-q:v', '4', t]);
    out.push([t, y]);
  }
  return out;
}
const PROMPT = 'Ты строгий дизайнер-тестировщик. Это скриншот части страницы интернет-магазина музыки (тёмная тема — это нормально). ' +
  'Найди ТОЛЬКО явные визуальные ошибки: элементы налезают друг на друга, текст обрезан или вылезает за край, сломанная вёрстка, ' +
  'картинка не загрузилась (пустой прямоугольник/значок битой картинки), огромная пустая дыра посреди страницы, текст не читается на фоне, ' +
  'кнопки разного размера в одном ряду, что-то уходит за правый край экрана. Не придумывай. Если ошибок нет — пустой список. ' +
  'Ответь JSON: {"defects":[{"what":"коротко по-русски, что не так","where":"где на картинке (вверху/посередине/внизу, какой блок)","sure":0-100}]}';

async function ask(file) {
  const img = fs.readFileSync(file).toString('base64');
  const r = await fetch(HOST + '/api/chat', { method: 'POST', headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ model: MODEL, stream: false, format: 'json', options: { temperature: 0 }, messages: [{ role: 'user', content: PROMPT, images: [img] }] }) });
  const j = await r.json();
  try { return (JSON.parse(j.message.content).defects || []).filter(d => d && d.what); } catch (e) { return []; }
}

(async () => {
  fs.mkdirSync(path.join(OUT, 'shots'), { recursive: true });
  const dir = fs.mkdtempSync(path.join(os.tmpdir(), 'eyes-'));
  let files = []; try { files = fs.readdirSync(path.join(SRC, 'eyes')).filter(f => f.endsWith('.jpg')).map(f => path.join(SRC, 'eyes', f)); } catch (e) {}
  // owner's phone and the key pages first
  files.sort((a, b) => (/a31_|_home|en\.jpg|all_products|infinity/.test(b) ? 1 : 0) - (/a31_|_home|en\.jpg|all_products|infinity/.test(a) ? 1 : 0));
  for (const f of files.slice(0, MAX)) {
    if (Date.now() > DEADLINE) { notChecked.push(`Нейросеть-глаза: не хватило времени, просмотрено ${stats.eyesTiles} кусков`); break; }
    const name = path.basename(f, '.jpg'); const dev = name.split('_')[0];
    const page = '/' + name.split('_').slice(1).join('/').replace(/^home$/, '') + (name.endsWith('home') ? '' : '/');
    let parts = []; try { parts = tiles(f, dir); } catch (e) { continue; }
    for (const [t, y] of parts) {
      if (Date.now() > DEADLINE) break;
      let d = []; try { d = await ask(t); } catch (e) { notChecked.push('Нейросеть-глаза: модель не ответила'); continue; }
      stats.eyesTiles++;
      for (const x of d.filter(x => (+x.sure || 60) >= 70).slice(0, 3)) {
        const shot = 'shots/' + path.basename(t); try { fs.copyFileSync(t, path.join(OUT, shot)); } catch (e) {}
        const what = String(x.what).slice(0, 140);
        bugs.push({ sig: 'llm-eyes|' + name + '|' + y + '|' + what.slice(0, 40), type: 'llm-eyes', sev: 'check',
          title: `Нейросеть-глаза: ${what}`, where: [page], devices: [DEV[dev] || dev],
          steps: [`Открыть https://solid-sound.net${page} (${DEV[dev] || dev})`, `Прокрутить до ${y} px`],
          expected: 'Аккуратная вёрстка без наложений и обрезков', actual: `${what} (${String(x.where || '').slice(0, 80)}; уверенность ${x.sure || '?'}%)`, shot });
      }
    }
    stats.eyesPages++;
  }
  fs.writeFileSync(path.join(OUT, 'bugs.json'), JSON.stringify({ mode: 'full', date: new Date().toISOString(), stats, bugs, notChecked }, null, 1));
  console.log(`Нейросеть-глаза: кусков ${stats.eyesTiles}, замечаний ${bugs.length}`);
})();
