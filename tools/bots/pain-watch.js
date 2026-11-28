// PAIN-WATCH (Analyst, 28.11.2026, owner via «Задачи», docs/team/content-pipeline.md step 1):
// top-20 pains of music producers in the customer's own words -> articles / wiki / posts and which of our products or services helps.
// Sources (public, read-only, no keys): Stack Exchange (Audio-Video Production + Music: titles, views, votes),
// Lemmy (public posts), our client-scout finds (reports/clients/history), competitors' review gists (reports/analyst/reviews.json).
// No people's names or handles are stored: only the question text, a count and the source name; links only when they carry no user name.
//   MODE=probe node tools/bots/pain-watch.js -> 1 source (training, docs/team/bots.md);  MODE=run -> all -> reports/analyst/pains.md + .json
'use strict';
const fs = require('fs'), path = require('path');
const ROOT = path.join(__dirname, '..', '..');
const OUT = path.join(ROOT, 'reports', 'analyst');

// Pain clusters: id, Russian label for the team, English patterns of how producers say it, what of ours helps
const PAINS = [
  ['lowend', 'Грязный низ, саб, бас гудит', /\b(muddy|muddiness|mud|low.?end|sub.?bass|boomy|bass (is|sounds?|gets?) (too )?(loud|muddy|lost|weak|boomy)|bass (and|&) (low|sub))\b/i, 'Сведение (от €69) · бесплатный разбор трека · шаблоны (как разведены kick и bass)'],
  ['kickbass', 'Кик и бас мешают друг другу, сайдчейн', /\b(kick (and|&|vs\.?|with) (the )?bass|bass (and|&) kick|side.?chain\w*|kick.{0,30}(clash|fight|mask)|ducking)\b/i, 'Шаблоны Kratos / Protos (видно связку kick + bass) · статья-гайд'],
  ['loud', 'Трек тихий, громкость, LUFS', /\b(loud(ness|er)?|lufs|limiter|limiting|too quiet|not loud|quiet(er)? (than|compared)|competitive (level|volume)|volume (of|on) (my )?(track|master))\b/i, 'Мастеринг (от €35)'],
  ['translate', 'В машине, клубе, телефоне звучит иначе', /\b(translat\w* (to|on|across|between) (the )?(club|car|speakers|systems|phones?|headphones|monitors)|(mix|track) (doesn'?t|does not|won'?t) translate|sounds? (different|bad|weird|terrible|thin|great) (in|on) (the )?(car|club|phone|headphones|speakers|monitors|earbuds|airpods)|(car|club|phone) test)\b/i, 'Бесплатный разбор трека · мастеринг'],
  ['finish', 'Не могу закончить трек, застрял в лупе', /\b(finish(ing)? (a |my |the )?(tracks?|songs?|projects?)|loop.?itis|8.?bar loops?|stuck (in|on) (a |the )?loop|never finish|unfinished|(from )?loop to (a )?(full )?(track|song|arrangement))\b/i, 'Шаблоны (полная аранжировка) · бесплатный пак'],
  ['arrange', 'Аранжировка: структура, брейк, дроп, переходы', /\b(?!arrangement (view|workflow|window|mode))(arrang(e|ing|ement)|song structure|track structure|breakdowns?|build.?ups?|drops?\b|intros?\b|outros?|transitions?)\b/i, 'Шаблоны (разбор структуры) · статья «структура techno-трека»'],
  ['eq', 'Эквализация: что резать, частоты', /\b(eq\b|eq'?ing|equali[sz]\w*|(cut|boost|clash\w*|mask\w*|problem|harsh|muddy) (the )?frequenc\w*|frequenc\w* (clash\w*|masking|to cut|to boost)|high.?pass|low.?pass|low.?cut|cut (the )?(lows|highs|mids))\b/i, 'Статья-гайд · шаблоны (настройки EQ видны)'],
  ['comp', 'Компрессия: атака, релиз, панч', /\b(compress\w*|attack (and|&) release|ratio|threshold|punch(y|iness)?|transients?|parallel compression|glue)\b/i, 'Статья-гайд · шаблоны'],
  ['space', 'Пространство: ревер, ширина, стерео, фаза', /\b(reverb|delays?|depth|stereo|width|wide(r|ness)?|mono (compat\w*|check)|phase|panning|spatial)\b/i, 'Статья-гайд · сведение'],
  ['vocal', 'Вокал: обработка, сидит в миксе', /\b(vocals?|voice|singer|sibilan\w*|de.?ess\w*|acapella|autotune|tuning)\b/i, 'Сведение · сэмплы вокала у авторов'],
  ['drums', 'Барабаны и грув: хэты, перкуссия, свинг', /\b(drums?|groove|swing|hi.?hats?|hats\b|percussion|snares?|claps?|shuffle|rimshot|toms?|drum (bus|programming|pattern))\b/i, 'Шаблоны (грув и перкуссия) · сэмплы'],
  ['synth', 'Саунд-дизайн: как сделать этот звук, синт, пресет', /\b(sound design|synth\w*|serum|vital|wavetable|patch(es)?|presets?|oscillat\w*|recreate (this|that|the) sound|how (do|to|can) (i |you )?(make|get|create) (this|that|a) (sound|bass|lead|pad))\b/i, 'Пресеты Serum · шаблоны (все звуки внутри)'],
  ['harsh', 'Резко, режет уши, резонансы', /\b(harsh(ness)?|piercing|too bright|shrill|(harsh|ringing|annoying|nasty|remov\w*|tam\w*) resonan\w*|resonan\w* (in|at) (the )?(mids|highs|\d)|ear fatigue|sibilant)\b/i, 'Сведение · бесплатный разбор'],
  ['gain', 'Гейн-стейджинг, запас, клип на мастере', /\b(gain.?stag\w*|headroom|clipping|clips? (on|at) (the )?master|levels? (too )?(hot|high)|red on (the )?master|dbfs)\b/i, 'Статья-гайд · мастеринг'],
  ['monitor', 'Мониторы, наушники, комната, референс', /\b(reference tracks?|referenc(e|ing)|monitors?|monitoring|headphones?|room (treatment|acoustics)|acoustic (panels|treatment)|nearfields?)\b/i, 'Статья «референс за 5 минут» · мастеринг'],
  ['master', 'Мастеринг сам / цепочка мастеринга', /\b(master(ing)? (my|a|the|your) (own )?(track|song|mix)|self.?master\w*|mastering chain|master bus|mastered)\b/i, 'Мастеринг (от €35) · Release Ready (от €99)'],
  ['mix', 'Микс звучит любительски, плоско, нужна обратная связь', /\b(my mix|mix(down)? (sounds?|is|feels) (bad|amateur\w*|unprofessional|flat|thin|weak|cluttered|empty)|professional (sound|mix)|feedback on (my )?(mix|track|song)|mix (critique|review|feedback)|how to mix)\b/i, 'Бесплатный разбор трека · сведение (от €69) · разбор/копродюсирование (от €29)'],
  ['daw', 'Ableton и DAW: роутинг, CPU, рабочий процесс', /\b(ableton|live (10|11|12)|daw|workflow|cpu|latency|freez(e|ing)|crash\w*|routing|audio interface|buffer size)\b/i, 'Шаблоны Ableton (рабочий процесс внутри)'],
  ['theory', 'Мелодия и гармония: аккорды, тональность', /\b(chords?|chord progressions?|melod(y|ies|ic line)|scales?|harmon(y|ic)|music theory|key of|in key)\b/i, 'Шаблоны (MIDI внутри) · статья'],
  ['release', 'Релиз: лейбл, демо, стриминг, продвижение', /\b(releas(e|ing) (my|a|an|the|your) (track|song|single|ep|album|music)|record labels?|(signed|sign) (to|with) a label|send(ing)? demos?|spotify|distribut\w*|promot\w*|soundcloud|beatport|playlist)\b/i, 'Release Ready (от €99) · мастеринг'],
  ['block', 'Нет идей и мотивации, творческий ступор', /\b(inspiration|creative block|writer'?s block|motivat\w*|burn.?out|no ideas|out of ideas|stuck creatively)\b/i, 'Бесплатный пак · шаблоны как старт'],
  ['legal', 'Права на сэмплы, royalty-free, лицензия', /\b(sample clearance|clear (a |the )?samples?|copyright\w*|royalt(y|ies)|royalty.?free|licens\w*|legal(ly)?)\b/i, 'Лицензия royalty-free (наша страница лицензии)'],
];
const NOISE = /\b(for sale|selling my|\[wts\]|\[wtb\]|job offer|we are hiring|giveaway|nsfw|crypto|nft|black friday|cyber monday|% off|discount code)\b/i;

function classify(text) { const t = String(text || ''); if (!t.trim() || NOISE.test(t)) return []; return PAINS.filter(p => p[2].test(t)).map(p => p[0]); }
// Drop anything that names a person: e-mails, @handles, links with a user part
const clean = (s) => String(s || '').replace(/\S+@\S+\.\S+/g, '').replace(/(^|\s)@\w[\w.-]*/g, '$1').replace(/https?:\/\/\S+/g, '').replace(/\s+/g, ' ').trim();
const safeLink = (u) => u && !/@|\/u\/|\/user\/|\/users\/|\/profile/i.test(u) ? u : null;

if (require.main !== module) { module.exports = { classify, clean, safeLink, PAINS }; return; }

const MODE = process.env.MODE || 'run';
const S = require('./lib/shop.js').make({ maxRequests: 70, pauseMs: 2000, ua: 'SolidSoundQA-Analyst/1.0 (+https://solid-sound.net; read-only research bot)' });
const SE = [['sound', 'Sound Design (audio)', ['mixing', 'mastering', 'eq', 'compression', 'ableton-live', 'reverb', 'daw', 'music-production']],
  ['music', 'Music', ['production', 'mixing', 'electronic-music', 'ableton-live', 'synthesizer', 'daw']]];
const getJ = async (u) => { const r = await S.get(u); if (r.error) return { error: r.error }; if (r.status !== 200) return { error: 'HTTP ' + r.status }; return S.json(r) || { error: 'не JSON' }; };
const LEMMY = ['music production', 'mixing help', 'mastering', 'ableton', 'techno production', 'finish track'];

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const now = new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC';
  const items = []; const src = [];
  const add = (it) => { const t = clean(it.text); const ids = classify(t + ' ' + clean(it.extra || '')); if (ids.length) items.push({ ...it, text: t.slice(0, 160), ids }); return ids.length ? 1 : 0; };
  // 1) Stack Exchange: most-voted questions per tag (views = how many people had the same question)
  for (const [site, name, tags] of (MODE === 'probe' ? [[SE[0][0], SE[0][1], SE[0][2].slice(0, 2)]] : SE)) {
    let n = 0, hit = 0, err = '';
    for (const tag of tags) {
      const r = await getJ(`https://api.stackexchange.com/2.3/questions?order=desc&sort=votes&tagged=${encodeURIComponent(tag)}&site=${site}&pagesize=100`);
      if (!r || r.error || !r.items) { err = (r && (r.error || r.error_message)) || 'нет ответа'; continue; }
      for (const q of r.items) { n++; hit += add({ src: 'Stack Exchange — ' + name, text: S.dec(q.title), extra: '', views: q.view_count || 0, score: q.score || 0, url: safeLink(q.link) }); }
    }
    src.push(`${'Stack Exchange — ' + name}: вопросов ${n}, с болью ${hit}${err ? ' (' + err + ')' : ''}`);
  }
  if (MODE !== 'probe') {
    // 2) Lemmy public posts (top of all time for each query)
    let n = 0, hit = 0, err = '';
    for (const q of LEMMY) {
      const r = await getJ(`https://lemmy.world/api/v3/search?q=${encodeURIComponent(q)}&type_=Posts&sort=TopAll&limit=50`);
      if (!r || r.error || !r.posts) { err = (r && r.error) || 'нет ответа'; continue; }
      for (const p of r.posts) { n++; const c = p.counts || {}; hit += add({ src: 'Lemmy', text: (p.post && p.post.name) || '', extra: ((p.post && p.post.body) || '').slice(0, 600), views: 0, score: (c.score || 0) + (c.comments || 0), url: safeLink(p.post && p.post.ap_id) }); }
    }
    src.push(`Lemmy: постов ${n}, с болью ${hit}${err ? ' (' + err + ')' : ''}`);
    // 3) our client-scout finds («😣 Что болит» in reports/clients/history) — text only, no links (they carry user handles)
    let cs = 0; const hdir = path.join(ROOT, 'reports', 'clients', 'history');
    for (const f of (fs.existsSync(hdir) ? fs.readdirSync(hdir) : [])) { let on = false;
      for (const line of fs.readFileSync(path.join(hdir, f), 'utf8').split('\n')) { if (/^## /.test(line)) on = /Что болит/.test(line); else if (on && /^- \[/.test(line)) { const m = line.match(/^- \[(.*?)\]\(/); if (m) cs += add({ src: 'client-scout (Mastodon, Lemmy, Bluesky, SE)', text: m[1], views: 0, score: 1, url: null }); } } }
    src.push(`client-scout, история: с болью ${cs}`);
    // 4) competitors' buyers (reviews robot): what they praise / miss — gist only
    let rv = 0; try { const R = JSON.parse(fs.readFileSync(path.join(OUT, 'reviews.json'), 'utf8'));
      for (const sh of R.shops || []) for (const x of sh.reviews || []) rv += add({ src: 'Отзывы покупателей конкурентов', text: x.gist || '', views: 0, score: x.rating <= 3 ? 2 : 1, url: null }); } catch (e) { /* no reviews yet */ }
    src.push(`отзывы конкурентов: с болью ${rv}`);
  }
  // Rank: how many times a pain is met (each post once per pain), views as the second key
  const by = {}; for (const it of items) for (const id of it.ids) { const b = by[id] || (by[id] = { n: 0, views: 0, src: {}, ex: [] }); b.n++; b.views += it.views; b.src[it.src] = (b.src[it.src] || 0) + 1; b.ex.push(it); }
  const top = PAINS.filter(p => by[p[0]]).map(p => ({ id: p[0], label: p[1], ours: p[3], ...by[p[0]] })).sort((a, b) => b.n - a.n || b.views - a.views).slice(0, 20);
  const L = [`# Боли продюсеров — ${now} (${MODE})`, '', 'Публичные вопросы и посты. «Как говорят» — дословные заголовки вопросов (английский), без имён. «Раз» = сколько вопросов/постов про это; «просмотров» = сколько людей открыли такие вопросы на Stack Exchange.', '',
    '| # | Боль | Как говорят клиенты | Раз | Просмотров | Где | Что из нашего помогает |', '|---|---|---|---|---|---|---|'];
  top.forEach((p, i) => { const ex = p.ex.sort((a, b) => b.views - a.views || b.score - a.score).filter((x, j, a) => a.findIndex(y => y.text === x.text) === j).slice(0, 2);
    L.push(`| ${i + 1} | ${p.label} | ${ex.map(x => x.url ? `[«${x.text.replace(/\|/g, '/')}»](${x.url})` : `«${x.text.replace(/\|/g, '/')}»`).join('<br>')} | ${p.n} | ${p.views ? p.views.toLocaleString('ru-RU') : '—'} | ${Object.entries(p.src).sort((a, b) => b[1] - a[1]).map(([s, n]) => `${s} ${n}`).join('; ')} | ${p.ours} |`); });
  L.push('', '## Источники', ...src.map(s => '- ' + s), '', `_Всего записей с болью: ${items.length}. Запросов: ${S.used()}. Один вопрос может попасть в 2–3 боли._`);
  const name = MODE === 'probe' ? 'pains-probe' : 'pains';
  fs.writeFileSync(path.join(OUT, name + '.md'), L.join('\n') + '\n');
  fs.writeFileSync(path.join(OUT, name + '.json'), JSON.stringify({ date: now.slice(0, 10), mode: MODE, sources: src, top: top.map(p => ({ id: p.id, label: p.label, n: p.n, views: p.views, src: p.src, ours: p.ours, examples: p.ex.slice(0, 5).map(x => ({ t: x.text, src: x.src, views: x.views, url: x.url })) })) }, null, 1));
  console.log(L.join('\n'));
})();
