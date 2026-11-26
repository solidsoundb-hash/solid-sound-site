// CLIENT SCOUT — Analyst's daily scout for the main customer avatar «Mark» (docs/customer-avatar.md, owner 26.11.2026):
// an adult hobbyist / gigging DJ-producer of club electronic music with a home studio.
// Reads PUBLIC posts (no login, never posts or votes) and sorts them:
//   lead    — someone asks for mastering / mixing / feedback / help finishing a track (manual, helpful answer only);
//   pain    — what hurts (club sound, muddy low end, loudness, unfinished loops) → content and offer ideas;
//   product — asks for templates / presets / samples → what to make and sell;
// plus genre counts. Sources need no keys: Stack Exchange API, Mastodon tags, Lemmy search, Bluesky public search.
// Ladder (docs/team/bots.md): STAGE=1 one source, 2 = all once by hand, 3 = daily schedule. `--exam` checks the sorter.
'use strict';
const fs = require('fs');
const path = require('path');

const OUT = path.join(__dirname, '../../reports/clients');
const MEM = path.join(OUT, 'memory.json');
const STAGE = Number(process.env.STAGE || 1);
const UA = 'SolidSoundClientScout/1.0 (+https://solid-sound.net; read-only research bot)';
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

const NICHE = /\b(techno|house|tech.?house|afro.?house|trance|edm|club|dj|ableton|serum|drum and bass|dnb|minimal|electronic|producer|track|mixdown|mastering|mixing|daw|kick|bassline|synth)\b/i;
const CLASSES = [
  { id: 'lead', w: 3, re: /\b(need|looking for|recommend|hire|anyone (do|offer)|who can|where can i get)\b[^.?!]{0,60}\b(master(ing)?|mix(ing)?|engineer|feedback|critique|someone to finish)\b|\b(mastering|mixing) (engineer|service)s?\b.*\?|\b(feedback|critique) (on|for) my (track|mix|song)\b|\bcan (someone|anyone) (listen|give feedback|check my mix)\b/i },
  { id: 'pain', w: 2, re: /\b(muddy|muddiness|thin|harsh|not loud|too quiet|lufs|loudness|low.?end|sub.?bass|kick and bass|clash|translat\w*|sounds? (bad|weak|flat) (in|on) (the )?(club|car|speakers|phone)|disappear\w* (in|on) the club|club (system|speakers|sound system)|can'?t finish|never finish|unfinished|8.?bar loop|loop ?(hell|itis)|stuck on|writer'?s block|mix (sounds|is) (bad|amateur)|amateur sound|compare (to|with) (pro|reference))\b/i },
  { id: 'product', w: 2, re: /\b(ableton (template|project)|project file|\.als\b|template for|serum (preset|patch)|preset pack|sample pack|drum kit|one.?shots?|midi pack|stems? (for|of))\b/i },
];
const NOISE = /\b(for sale|selling my|\[wts\]|\[wtb\]|job offer|we are hiring|giveaway|nsfw|crypto|nft)\b/i;
// (26.11 stage 2) vendor posts and tips are not customers: promo words, "one of ours", product launches, statements of advice
const PROMO = /\b(one of ours|our (new )?(plugin|pack|product|course)|black friday|cyber monday|on sale|\d+ ?% off|discount|deal|buy now|out now|available now|offers? (a|an|the|alternatives)|puts the|gathers|ist ein|loudness enhancer|mixing tip|production tip|pro tip|a small habit)\b/i;
// someone speaks about THEIR OWN problem or asks: first person + question/complaint
const ASKS = /\?|\b(help|any(one|body)? (know|recommend|tips)|how (do|can|should) (i|you|we)|why (does|is|do) my|please|what am i doing wrong|struggl\w*|can'?t|cannot|stuck)\b/i;
const MINE = /\b(i|i'?m|i'?ve|my|me|we|our track)\b/i;
const GENRES = ['melodic techno', 'hard techno', 'peak time', 'minimal', 'tech house', 'afro house', 'deep house', 'progressive house', 'trance', 'drum and bass', 'dubstep', 'house', 'techno'];

function classify(text) {
  const t = String(text || '');
  if (NOISE.test(t) || PROMO.test(t)) return { cls: 'none', score: 0 };
  if (!ASKS.test(t) && !MINE.test(t)) return { cls: 'none', score: 0 };   // only real people: a question or their own music
  let best = { cls: 'none', score: 0 };
  for (const c of CLASSES) if (c.re.test(t) && c.w > best.score) best = { cls: c.id, score: c.w };
  if (best.cls !== 'none' && NICHE.test(t)) best.score += 1;      // clearly about electronic production
  if (best.cls === 'lead' && !NICHE.test(t) && !/\b(track|song|mix|master)\b/i.test(t)) return { cls: 'none', score: 0 };
  return best;
}

const strip = (s) => String(s || '').replace(/<[^>]+>/g, ' ').replace(/&quot;/g, '"').replace(/&#0?39;|&apos;/g, "'").replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/\s+/g, ' ').trim();

async function getJson(url) {
  for (let i = 0; i < 3; i++) {
    try {
      const r = await fetch(url, { headers: { 'User-Agent': UA, Accept: 'application/json' }, signal: AbortSignal.timeout(30000) });
      if (r.ok) return { ok: true, j: await r.json() };
      if (r.status === 429 || r.status >= 500) { await sleep(4000 * (i + 1)); continue; }
      return { ok: false, err: 'HTTP ' + r.status };
    } catch (e) { if (i === 2) return { ok: false, err: e.message }; await sleep(3000); }
  }
  return { ok: false, err: 'retries' };
}

// Each source returns [{id, title, text, link, date}]
const SOURCES = [
  { id: 'se:avp', name: 'Stack Exchange — Audio-Video Production', fn: async () => {
    const r = await getJson('https://api.stackexchange.com/2.3/questions?order=desc&sort=creation&site=avp&pagesize=50&filter=withbody');
    if (!r.ok) throw new Error(r.err);
    return (r.j.items || []).map((q) => ({ id: 'se-avp-' + q.question_id, title: strip(q.title), text: strip(q.title + ' ' + q.body).slice(0, 600), link: q.link, date: q.creation_date * 1000 }));
  } },
  { id: 'se:music', name: 'Stack Exchange — Music (production tags)', fn: async () => {
    const r = await getJson('https://api.stackexchange.com/2.3/questions?order=desc&sort=creation&site=music&tagged=production;mixing;electronic-music;ableton-live;mastering&pagesize=50&filter=withbody');
    if (!r.ok) throw new Error(r.err);
    return (r.j.items || []).map((q) => ({ id: 'se-mu-' + q.question_id, title: strip(q.title), text: strip(q.title + ' ' + q.body).slice(0, 600), link: q.link, date: q.creation_date * 1000 }));
  } },
  ...['musicproduction', 'techno', 'ableton', 'mixing', 'edmproduction'].map((tag) => ({ id: 'mastodon:' + tag, name: 'Mastodon #' + tag, fn: async () => {
    const r = await getJson(`https://mastodon.social/api/v1/timelines/tag/${tag}?limit=40`);
    if (!r.ok) throw new Error(r.err);
    return (r.j || []).map((s) => ({ id: 'md-' + s.id, title: strip(s.content).slice(0, 120), text: strip(s.content).slice(0, 600), link: s.url, date: Date.parse(s.created_at) }));
  } })),
  ...['mastering engineer', 'mixing feedback', 'ableton template', 'techno track'].map((q) => ({ id: 'lemmy:' + q, name: 'Lemmy «' + q + '»', fn: async () => {
    const r = await getJson(`https://lemmy.world/api/v3/search?q=${encodeURIComponent(q)}&type_=Posts&sort=New&limit=30`);
    if (!r.ok) throw new Error(r.err);
    return (r.j.posts || []).map((p) => ({ id: 'lm-' + p.post.ap_id, title: strip(p.post.name), text: strip(p.post.name + ' ' + (p.post.body || '')).slice(0, 600), link: p.post.ap_id, date: Date.parse(p.post.published) }));
  } })),
  // Bluesky public search answered HTTP 403 (needs login) on 26.11 — removed; Reddit needs an official API key (secret REDDIT_*)
];

function exam() {
  const cases = JSON.parse(fs.readFileSync(path.join(__dirname, 'client-scout/exam.json'), 'utf8'));
  let ok = 0; const bad = [];
  for (const c of cases) { const got = classify(c.text).cls; if (got === c.expect) ok++; else bad.push(`  ✗ ждали ${c.expect}, получили ${got}: ${c.text.slice(0, 80)}`); }
  console.log(`Экзамен: ${ok}/${cases.length}`); bad.forEach((b) => console.log(b));
  process.exit(ok === cases.length ? 0 : 1);
}

async function main() {
  if (process.argv.includes('--exam')) return exam();
  fs.mkdirSync(OUT, { recursive: true });
  let mem; try { mem = JSON.parse(fs.readFileSync(MEM, 'utf8')); } catch { mem = { seen: {}, runs: 0, sources: {}, totals: {} }; }
  const list = STAGE === 1 ? SOURCES.slice(0, 1) : SOURCES;
  const found = { lead: [], pain: [], product: [] }; const genres = {}; const health = [];
  const maxAge = Date.now() - 30 * 864e5;
  for (const s of list) {
    const st = mem.sources[s.id] || (mem.sources[s.id] = { runs: 0, ok: 0, items: 0, hits: 0, failStreak: 0 });
    st.runs++;
    try {
      const items = await s.fn(); st.ok++; st.failStreak = 0; let fresh = 0, hits = 0;
      for (const it of items) {
        if (!it.link || mem.seen[it.id] || (it.date && it.date < maxAge)) continue;
        mem.seen[it.id] = Date.now(); fresh++;
        const low = it.text.toLowerCase(); for (const g of GENRES) if (low.includes(g)) { genres[g] = (genres[g] || 0) + 1; break; }
        const c = classify(it.text);
        if (c.cls !== 'none') { hits++; found[c.cls].push({ ...it, score: c.score, src: s.name }); }
      }
      st.items += fresh; st.hits += hits;
      health.push(`- ${s.name}: ок, новых ${fresh}, по делу ${hits}`);
    } catch (e) { st.failStreak++; health.push(`- ${s.name}: ❌ ${e.message}`); }
    await sleep(1500);
  }
  // forget ids older than 60 days
  const cut = Date.now() - 60 * 864e5; for (const k of Object.keys(mem.seen)) if (mem.seen[k] < cut) delete mem.seen[k];
  mem.runs++; for (const k of Object.keys(found)) mem.totals[k] = (mem.totals[k] || 0) + found[k].length;
  fs.writeFileSync(MEM, JSON.stringify(mem, null, 1));

  const today = new Date().toISOString().slice(0, 10);
  const row = (x) => `- [${x.title.replace(/[\[\]|]/g, ' ').slice(0, 110) || 'пост'}](${x.link}) — ${x.src}`;
  const sec = (title, why, arr) => [`## ${title} (${arr.length})`, `_${why}_`, '', ...(arr.length ? arr.sort((a, b) => b.score - a.score).slice(0, 15).map(row) : ['- сегодня нет']), ''];
  const L = [`# Поиск клиентов — ${today} (ступень ${STAGE})`, '',
    '_Робот «Поиск клиентов» (Аналитик). Портрет клиента — `docs/customer-avatar.md`. Робот только читает; отвечать вручную, сначала польза, без спама._', '',
    ...sec('🎯 Ищут услугу', 'просят мастеринг, сведение, отзыв на трек — можно помочь советом и предложить бесплатный отзыв', found.lead),
    ...sec('😣 Что болит', 'идеи для роликов, статей wiki и текстов на сайте', found.pain),
    ...sec('🛒 Спрос на товары', 'что делать и продавать: шаблоны, пресеты, сэмплы', found.product),
    '## 🎵 Жанры в свежих постах', Object.keys(genres).length ? Object.entries(genres).sort((a, b) => b[1] - a[1]).map(([g, n]) => `${g} ${n}`).join(' · ') : '- мало данных', '',
    '## Источники', ...health, '',
    `_Всего за все прогоны: ищут услугу ${mem.totals.lead || 0}, боли ${mem.totals.pain || 0}, товары ${mem.totals.product || 0}; прогонов ${mem.runs}._`];
  fs.writeFileSync(path.join(OUT, 'latest.md'), L.join('\n') + '\n');
  fs.mkdirSync(path.join(OUT, 'history'), { recursive: true });
  fs.writeFileSync(path.join(OUT, 'history', today + '.md'), L.join('\n') + '\n');
  console.log(L.slice(0, 6).join('\n')); console.log(health.join('\n'));
}
main().catch((e) => { console.error(e); process.exit(1); });
