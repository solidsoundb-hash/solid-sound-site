// CONTENT-WATCH (Analyst + Marketer, 28.11.2026, owner via «Задачи», docs/team/content-pipeline.md step 2):
// what competitors and top niche authors publish — blog articles and YouTube videos: topic (pain from pain-watch), format,
// length, media inside, date and the public response (YouTube views / likes; blogs usually show none).
// Public pages only (tools/bots/lib/shop.js: robots.txt, pause per host, request cap, no protection bypass). No people's names stored.
//   MODE=probe node tools/bots/content-watch.js -> 1 shop (training);  MODE=run -> all -> reports/analyst/content.md + .json
'use strict';
const fs = require('fs'), path = require('path');
const CFG = JSON.parse(fs.readFileSync(path.join(__dirname, 'watch.json'), 'utf8'));
const OUT = path.join(__dirname, '..', '..', 'reports', 'analyst');
const { classify, PAINS } = require('./pain-watch.js');

// Format of a piece by its title (first match wins)
const FORMATS = [
  ['новости/релиз/реклама', /\b(out now|new release|introducing|announc\w*|giveaway|sale|% off|black friday|cyber monday|free download|launch\w*|now available|bundle)\b/i],
  ['разбор трека / «как у X»', /\b(how (to|i) make|in the style of|like (\w+ ){0,3}(does|did)|remake|recreate|breakdown of|making of|track breakdown|from scratch|start to finish|in \d+ minutes?|in 1 hour)\b/i],
  ['«N ошибок / советов»', /^\s*\d+\s|\b\d+\s+(tips|tricks|mistakes|ways|things|reasons|secrets|steps|essential|best)\b/i],
  ['чек-лист', /\b(checklist|cheat ?sheet|step.by.step)\b/i],
  ['до / после', /\b(before (and|&|\/) after|before.after|vs\.?|versus|a\/b)\b/i],
  ['гайд / how-to', /\b(how to|guide|tutorial|explained|beginner\w*|ultimate|complete|learn|masterclass|lesson|tips? for)\b/i],
  ['интервью / артист', /\b(interview|q&a|in conversation|artist spotlight|meet the)\b/i],
  ['подборка / обзор', /\b(best|top \d+|review|roundup|compared|picks)\b/i],
];
function format(title) { const f = FORMATS.find(x => x[1].test(title)); return f ? f[0] : 'другое'; }
function topics(title, body) { return classify(title + ' ' + String(body || '').slice(0, 800)); }
// Article page -> words, headings, images, audio, video (embeds), lists
function measure(html) {
  const main = (String(html).match(/<article[\s\S]*?<\/article>/i) || String(html).match(/<main[\s\S]*?<\/main>/i) || [String(html)])[0];
  const text = main.replace(/<script[\s\S]*?<\/script>|<style[\s\S]*?<\/style>|<nav[\s\S]*?<\/nav>|<footer[\s\S]*?<\/footer>/gi, ' ').replace(/<[^>]+>/g, ' ').replace(/&\w+;/g, ' ');
  return { words: (text.match(/[A-Za-zÀ-ÿ0-9']+/g) || []).length, h2: (main.match(/<h2\b/gi) || []).length, img: (main.match(/<img\b/gi) || []).length,
    audio: (main.match(/<audio\b|soundcloud\.com\/player|w\.soundcloud|bandcamp\.com\/EmbeddedPlayer/gi) || []).length,
    video: (main.match(/youtube(-nocookie)?\.com\/embed|player\.vimeo|<video\b/gi) || []).length, lists: (main.match(/<li\b/gi) || []).length };
}
const med = (a) => { const s = a.filter(x => x != null).sort((x, y) => x - y); return s.length ? s[Math.floor(s.length / 2)] : null; };

if (require.main !== module) { module.exports = { format, topics, measure }; return; }

const MODE = process.env.MODE || 'run';
const S = require('./lib/shop.js').make({ maxRequests: MODE === 'probe' ? 30 : 170, pauseMs: 2500, ua: 'SolidSoundQA-Analyst/1.0 (+https://solid-sound.net; read-only research bot)' });
const PER = 8; // newest articles measured per blog
const label = Object.fromEntries(PAINS.map(p => [p[0], p[1]]));
const loc = (xml) => [...String(xml).matchAll(/<url>\s*<loc>([^<]+)<\/loc>(?:\s*<lastmod>([^<]+)<\/lastmod>)?/g)].map(m => ({ u: S.dec(m[1]), d: m[2] || '' }));

async function shopifyBlog(origin) {
  const idx = await S.get(origin + '/sitemap.xml'); if (idx.error || idx.status !== 200) return { error: idx.error || 'sitemap ' + idx.status };
  const maps = [...idx.text.matchAll(/<loc>([^<]*sitemap_blogs[^<]*)<\/loc>/g)].map(m => S.dec(m[1]));
  if (!maps.length) return { error: 'блога нет в sitemap' };
  let urls = []; for (const m of maps.slice(0, 2)) { const r = await S.get(m); if (r.status === 200) urls.push(...loc(r.text).filter(x => /\/blogs\/[^/]+\/[^/]+/.test(x.u))); }
  urls.sort((a, b) => b.d.localeCompare(a.d)); const total = urls.length; const out = [];
  for (const x of urls.slice(0, PER)) { const r = await S.get(x.u); if (r.status !== 200) continue;
    const title = S.dec((r.text.match(/<meta property="og:title" content="([^"]+)"/) || r.text.match(/<title>([^<]+)<\/title>/) || [, x.u])[1]).replace(/\s*[–|-]\s*[^–|-]+$/, '').trim();
    out.push({ title, url: x.u, date: (x.d || '').slice(0, 10), ...measure(r.text) }); }
  return { total, items: out };
}
async function wpBlog(origin) {
  const r = await S.get(origin + '/wp-json/wp/v2/posts?per_page=' + PER + '&_fields=date,link,title,content'); const j = r.status === 200 && S.json(r);
  if (!Array.isArray(j)) return { error: r.error || 'нет WordPress-блога (' + r.status + ')' };
  return { total: null, items: j.map(p => ({ title: S.strip(p.title && p.title.rendered), url: p.link, date: (p.date || '').slice(0, 10), ...measure((p.content && p.content.rendered) || '') })) };
}
async function youtube(origin, homeHtml) {
  const m = String(homeHtml).match(/youtube\.com\/(channel\/UC[\w-]{22}|@[\w.-]+|c\/[\w.-]+|user\/[\w.-]+)/i); if (!m) return { error: 'ссылки на YouTube на главной нет' };
  // youtube.com closes its pages and feeds to robots in robots.txt — we do not go around it. Official YouTube Data API (free key) only.
  const KEY = process.env.YOUTUBE_KEY; if (!KEY) return { error: 'ждёт ключ YOUTUBE_KEY (бесплатный, YouTube Data API; robots.txt YouTube закрывает ленты)', handle: m[1] };
  const api = async (q) => { const r = await S.get('https://www.googleapis.com/youtube/v3/' + q + '&key=' + KEY); return r.status === 200 ? S.json(r) : null; };
  const uc = (m[1].match(/UC[\w-]{22}/) || [])[0]; const h = (m[1].match(/^@([\w.-]+)/) || [])[1];
  const ch = await api('channels?part=contentDetails' + (uc ? '&id=' + uc : h ? '&forHandle=' + encodeURIComponent('@' + h) : '&forUsername=' + encodeURIComponent(m[1].split('/').pop())));
  const up = ch && ch.items && ch.items[0] && ch.items[0].contentDetails.relatedPlaylists.uploads; if (!up) return { error: 'канал не найден через API' };
  const pl = await api('playlistItems?part=contentDetails&maxResults=15&playlistId=' + up); const ids = ((pl && pl.items) || []).map(x => x.contentDetails.videoId);
  const vs = ids.length ? await api('videos?part=snippet,statistics,contentDetails&id=' + ids.join(',')) : null;
  const dur = (d) => { const x = String(d || '').match(/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/) || []; return (+x[1] || 0) * 60 + (+x[2] || 0) + Math.round((+x[3] || 0) / 60); };
  const items = ((vs && vs.items) || []).map(v => ({ title: v.snippet.title, url: 'https://www.youtube.com/watch?v=' + v.id, date: (v.snippet.publishedAt || '').slice(0, 10),
    views: +(v.statistics.viewCount || 0), likes: +(v.statistics.likeCount || 0), comments: +(v.statistics.commentCount || 0), min: dur(v.contentDetails.duration) }));
  return { channel: 'https://www.youtube.com/channel/' + ch.items[0].id, items };
}

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const now = new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC';
  const sources = (MODE === 'probe' ? CFG.shops.slice(0, 1) : CFG.shops).map(s => ({ ...s, kind: 'shop' })).concat(MODE === 'probe' ? [] : (CFG.contentAuthors || []).map(a => ({ ...a, kind: 'author' })));
  const L = [`# Контент конкурентов и авторов ниши — ${now} (${MODE})`, '', 'Публичные блоги (последние статьи) и YouTube (лента канала: просмотры и лайки — публичные). Тема = боль из pain-watch. Без имён людей.', ''];
  const data = { date: now.slice(0, 10), sources: [] }; const allA = [], allV = [];
  for (const s of sources) {
    const origin = 'https://' + s.domain; const home = await S.get(origin + '/');
    const blog = s.type === 'shopify' ? await shopifyBlog(origin) : await wpBlog(origin);
    const yt = s.youtube === false ? { error: 'не смотрим' } : await youtube(origin, home.text || '');
    const A = (blog.items || []).map(a => ({ ...a, src: s.name, fmt: format(a.title), top: topics(a.title) }));
    const V = (yt.items || []).map(v => ({ ...v, src: s.name, fmt: format(v.title), top: topics(v.title) }));
    allA.push(...A); allV.push(...V); data.sources.push({ name: s.name, kind: s.kind, blog: { error: blog.error, total: blog.total, items: A }, youtube: { error: yt.error, channel: yt.channel, items: V } });
    L.push(`## ${s.name}${s.kind === 'author' ? ' (автор ниши)' : ''}`, '');
    if (!blog.error && !A.length) blog.error = 'статей нет';
    if (blog.error) L.push(`- Блог: ❌ ${blog.error}`); else {
      L.push(`- Блог: статей ${blog.total ?? 'много'}, разобрал ${A.length}: медиана ${med(A.map(a => a.words)) ?? '—'} слов, картинок ${med(A.map(a => a.img)) ?? '—'}, с аудио ${A.filter(a => a.audio).length}, с видео ${A.filter(a => a.video).length}.`, '',
        '| Дата | Статья | Формат | Тема (боль) | Слов | Подзаг. | Карт. | Аудио | Видео |', '|---|---|---|---|---|---|---|---|---|');
      A.forEach(a => L.push(`| ${a.date} | [${a.title.replace(/\|/g, '/').slice(0, 90)}](${a.url}) | ${a.fmt} | ${a.top.map(t => label[t]).slice(0, 2).join('; ') || '—'} | ${a.words} | ${a.h2} | ${a.img} | ${a.audio} | ${a.video} |`)); }
    L.push('');
    if (yt.error) L.push(`- YouTube: ❌ ${yt.error}`, ''); else {
      L.push(`- YouTube: [канал](${yt.channel}), последних видео ${V.length}, медиана просмотров ${med(V.map(v => v.views)) ?? '—'}.`, '', '| Дата | Видео | Формат | Тема (боль) | Мин. | Просмотры | Лайки | Коммент. |', '|---|---|---|---|---|---|---|---|');
      V.slice().sort((a, b) => b.views - a.views).slice(0, 8).forEach(v => L.push(`| ${v.date} | [${v.title.replace(/\|/g, '/').slice(0, 90)}](${v.url}) | ${v.fmt} | ${v.top.map(t => label[t]).slice(0, 2).join('; ') || '—'} | ${v.min ?? '—'} | ${v.views.toLocaleString('ru-RU')} | ${v.likes.toLocaleString('ru-RU')} | ${v.comments ?? '—'} |`)); L.push(''); }
  }
  // What works: formats by YouTube response (median views relative to the channel's own median, so big channels don't dominate)
  const chMed = {}; for (const v of allV) (chMed[v.src] = chMed[v.src] || []).push(v.views);
  for (const k in chMed) chMed[k] = med(chMed[k]) || 1;
  const fmtStat = {}; for (const v of allV) { const f = fmtStat[v.fmt] || (fmtStat[v.fmt] = { n: 0, rel: [] }); f.n++; f.rel.push(v.views / chMed[v.src]); }
  const aFmt = {}; for (const a of allA) { const f = aFmt[a.fmt] || (aFmt[a.fmt] = { n: 0, words: [] }); f.n++; f.words.push(a.words); }
  const topicN = {}; for (const x of allA.concat(allV)) for (const t of x.top) topicN[t] = (topicN[t] || 0) + 1;
  L.push('## Итог для Маркетолога (шаг 3: работающий формат)', '',
    '**YouTube — какой формат набирает больше обычного для своего канала** (×1 = медиана канала):', '', '| Формат | Видео | Просмотры к медиане канала |', '|---|---|---|');
  Object.entries(fmtStat).sort((a, b) => med(b[1].rel) - med(a[1].rel)).forEach(([f, s]) => L.push(`| ${f} | ${s.n} | ×${(med(s.rel) || 0).toFixed(2)} |`));
  L.push('', '**Блоги — что пишут:**', '', '| Формат | Статей | Медиана слов |', '|---|---|---|');
  Object.entries(aFmt).sort((a, b) => b[1].n - a[1].n).forEach(([f, s]) => L.push(`| ${f} | ${s.n} | ${med(s.words)} |`));
  L.push('', '**Темы (боли), о которых уже пишут и снимают:** ' + (Object.entries(topicN).sort((a, b) => b[1] - a[1]).map(([t, n]) => `${label[t]} ${n}`).join(' · ') || '—'),
    '', '_Сравнить с `reports/analyst/pains.md`: боль есть у клиентов, а контента по ней мало — это наша тема._', '', `_Запросов: ${S.used()}._`);
  const name = MODE === 'probe' ? 'content-probe' : 'content';
  fs.writeFileSync(path.join(OUT, name + '.md'), L.join('\n') + '\n'); fs.writeFileSync(path.join(OUT, name + '.json'), JSON.stringify(data, null, 1));
  console.log(L.join('\n'));
})();
