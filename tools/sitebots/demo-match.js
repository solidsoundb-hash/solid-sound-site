// DEMO ↔ PRODUCT check (26.11.2026, Аналитик → «Задачи» → Тестировщик: at a competitor a product played the demo of another pack).
// For every product (Store API): the demo audio on the product page, the audio of its cards in the catalog and on the home page
// (data-pid ↔ data-src) and its YouTube video (title via oEmbed) must belong to THIS product: a distinctive word of the product
// name must be in the file name / video title, and the card must play the same file as the product page. Read-only, no media changes.
// Used by the night buyer (buy-night.js) and as a probe (tools/probe/demomatch.js). Returns { md, alerts, rows }.
'use strict';
const S = process.env.SITE || 'https://solid-sound.net';
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const GENERIC = new Set(['the', 'and', 'for', 'with', 'techno', 'house', 'tech', 'dark', 'melodic', 'ableton', 'live', 'template', 'templates', 'project',
  'preset', 'presets', 'serum', 'pack', 'demo', 'music', 'royalty', 'free', 'cinematic', 'solid', 'sound', 'kbps', 'watermark', 'track', 'reconstructed']);
const words = (s) => String(s || '').toLowerCase().replace(/&[a-z#0-9]+;/g, ' ').split(/[^a-z0-9]+/).filter(w => w.length >= 3);
const key = (name) => { const w = words(name); const k = w.filter(x => !GENERIC.has(x)); return k.length ? k : w; };
const fits = (name, text) => { const t = new Set(words(text)); return key(name).some(w => t.has(w)); };
const base = (u) => decodeURIComponent(String(u).split('?')[0].split('/').pop());

async function get(url, json) {
  for (let i = 0; i < 3; i++) {
    try { const r = await fetch(url, { headers: { 'User-Agent': UA } }); if (r.status === 200) return json ? r.json() : r.text(); if (r.status === 404) return null; } catch (e) {}
    await new Promise(r => setTimeout(r, 20000));
  }
  return null;
}
function cards(html) { // pid → set of demo files in that page's product cards
  const m = {};
  for (const t of html.match(/<[a-z]+\b[^>]*\.mp3[^>]*>/g) || []) {
    const id = (t.match(/data-(?:pid|id|product[-_]id)="(\d+)"/) || [])[1]; const f = (t.match(/([A-Za-z0-9_.%-]+\.mp3)/) || [])[1];
    if (id && f) (m[id] = m[id] || new Set()).add(base(f));
  }
  return m;
}

module.exports = async function demoMatch() {
  const alerts = []; const rows = [];
  let prods = [];
  for (let pg = 1; pg < 6; pg++) { const d = await get(`${S}/wp-json/wc/store/v1/products?per_page=100&page=${pg}`, true); if (!d || !d.length) break; prods = prods.concat(d); if (d.length < 100) break; }
  if (!prods.length) return { md: '## 🎧 Демо ↔ товар\n\n- ⚠️ список товаров не получен (хостинг) — не проверял\n', alerts, rows };
  const lists = {}; for (const [n, u] of [['каталог', '/en/all-products/'], ['главная', '/en/']]) { const h = await get(S + u); lists[n] = h ? cards(h) : null; }
  const ytTitle = {};
  for (const p of prods) {
    const name = p.name.replace(/&#8211;|&amp;/g, ' '); const bad = [];
    const h = await get(p.permalink);
    if (!h) { rows.push(`| ${name} | ⚠️ страница не открылась | | |`); continue; }
    const own = [...new Set((h.match(/<(?:audio|source)\b[^>]*\.mp3[^>]*>/g) || []).map(t => base((t.match(/([A-Za-z0-9_.%-]+\.mp3)/) || [])[1] || '')))].filter(Boolean);
    const yts = [...new Set((h.match(/(?:youtube(?:-nocookie)?\.com\/(?:embed\/|watch\?v=)|youtu\.be\/|data-yt(?:id)?=")([A-Za-z0-9_-]{11})/g) || []).map(x => x.slice(-11)))];
    for (const f of own) if (!fits(name, f)) bad.push(`аудио на странице «${f}»`);
    for (const [n, m] of Object.entries(lists)) {
      if (!m || !m[p.id]) continue;
      for (const f of m[p.id]) if (own.length ? !own.includes(f) : !fits(name, f)) bad.push(`${n}: карточка играет «${f}»${own.length ? ', на странице «' + own.join(', ') + '»' : ''}`);
    }
    const yt = [];
    for (const id of yts) {
      if (!(id in ytTitle)) { const o = await get(`https://www.youtube.com/oembed?format=json&url=https://www.youtube.com/watch?v=${id}`, true); ytTitle[id] = o ? o.title : null; }
      const t = ytTitle[id];
      if (t == null) yt.push(`${id} (название не получено)`);
      else { yt.push(`«${t}»`); if (!fits(name, t)) bad.push(`видео YouTube «${t}»`); }
    }
    if (bad.length) alerts.push(`Демо не от этого товара — ${name}: ${bad.join('; ')}`);
    rows.push(`| ${bad.length ? '❌' : '✅'} ${name} | ${own.join(', ') || '—'} | ${yt.join(', ') || '—'} | ${bad.join('; ') || 'совпадает'} |`);
  }
  const md = `## 🎧 Демо ↔ товар (${prods.length} товаров, ${alerts.length ? '❌ несовпадений: ' + alerts.length : '✅ всё совпадает'})\n\n` +
    'Слово из названия товара должно быть в имени аудио и в названии видео; карточки в каталоге и на главной играют тот же файл, что страница товара.\n\n' +
    '| Товар | Аудио на странице | Видео YouTube | Итог |\n|---|---|---|---|\n' + rows.join('\n') + '\n';
  return { md, alerts, rows };
};

if (require.main === module) module.exports().then(r => { console.log(r.md); if (r.alerts.length) console.log(r.alerts.join('\n')); });
