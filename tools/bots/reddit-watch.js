// REDDIT WATCH — marketer's read-only monitor (owner 25.11.2026, docs/team/bots.md).
// Reads fresh posts in producer communities, keeps the ones where our free pack / feedback / templates
// genuinely help, writes a link list to reports/marketing/. Never posts, votes or logs in.
// Learns: reports/marketing/memory.json keeps seen ids and per-keyword hits; the marketer marks noise weekly.
const fs = require('fs');
const path = require('path');

const OUT = path.join(__dirname, '../../reports/marketing');
const MEM = path.join(OUT, 'memory.json');
// Ladder (docs/team/bots.md): stage 1 = one subreddit, stage 2 = 5–10, stage 3 = all on schedule.
const ALL = ['ableton', 'edmproduction', 'Techno', 'WeAreTheMusicMakers', 'musicproduction', 'mixingmastering', 'synthesizers', 'Serum', 'TechHouse'];
const STAGE = Number(process.env.STAGE || 1);
const SUBS = STAGE === 1 ? ['ableton'] : STAGE === 2 ? ALL.slice(0, 6) : ALL;
const UA = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';

// topic -> what we can honestly offer (link is added by hand by the marketer, with UTM)
const TOPICS = [
  { k: /\b(ableton|live 1[0-2])\b.*\b(template|project file|als)\b|\btemplate\b.*\bableton\b/i, w: 3, offer: 'free Ableton template (/en/free/)' },
  { k: /\b(dark|hard|melodic|peak.?time)?\s*techno\b.*\b(kick|rumble|bass|groove|hats?|sound)\b/i, w: 3, offer: 'techno how-to + free template' },
  { k: /\brumble\b|\bkick\b.*\b(bass|low end|clash)\b|\blow.?end\b/i, w: 2, offer: 'low-end tips + free mix feedback' },
  { k: /\b(feedback|critique|rate|thoughts on)\b.*\b(track|mix|song|wip)\b|\bwip\b/i, w: 3, offer: 'free track feedback (/en/free-demo-feedback/)' },
  { k: /\b(mix|mixing|master|mastering)\b.*\b(muddy|loud|quiet|lufs|thin|harsh|help|how)\b/i, w: 2, offer: 'mix advice + free feedback' },
  { k: /\bserum\b.*\b(presets?|patch(es)?|sounds?)\b|\bpresets?\b.*\bserum\b/i, w: 2, offer: 'free Serum presets in starter pack' },
  { k: /\b(stock|native)\s+(devices|plugins)\b/i, w: 2, offer: 'stock-devices-only templates' },
  { k: /\b(beginner|just started|new to|where to start|first track)\b/i, w: 1, offer: 'starter pack + wiki' },
  { k: /\b(tech.?house|house)\b.*\b(groove|bass|drums|template)\b/i, w: 2, offer: 'Infinity tech house template' },
];
const NOISE = /\b(gear|for sale|selling my|\[wts\]|job|hiring|meme)\b/i;

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
const load = () => { try { return JSON.parse(fs.readFileSync(MEM, 'utf8')); } catch { return { seen: {}, hits: {}, runs: 0 }; } };

async function get(url, json) {
  for (let i = 0; i < 3; i++) {
    try {
      const r = await fetch(url, { headers: { 'User-Agent': UA, Accept: json ? 'application/json' : 'application/atom+xml' } });
      if (r.ok) return json ? r.json() : r.text();
      if (r.status === 429) { await sleep(5000 * (i + 1)); continue; }
      return null;
    } catch { await sleep(2000); }
  }
  return null;
}

const unesc = (s) => s.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"').replace(/&#39;/g, "'").replace(/&amp;/g, '&');

async function fetchSub(sub) {
  // 1) JSON, 2) RSS (often allowed when JSON is blocked for cloud IPs), 3) old.reddit JSON
  for (const host of ['www.reddit.com', 'old.reddit.com']) {
    const j = await get(`https://${host}/r/${sub}/new.json?limit=50&raw_json=1`, true);
    if (j && j.data) return { via: host + ' json', posts: j.data.children.map((c) => ({ id: c.data.id, title: c.data.title, text: c.data.selftext || '', url: 'https://www.reddit.com' + c.data.permalink, t: c.data.created_utc * 1000, n: c.data.num_comments, flair: c.data.link_flair_text || '' })) };
  }
  const x = await get(`https://www.reddit.com/r/${sub}/new/.rss?limit=50`, false);
  if (x) {
    const posts = [...x.matchAll(/<entry>([\s\S]*?)<\/entry>/g)].map((m) => {
      const e = m[1];
      const g = (re) => { const r = e.match(re); return r ? unesc(r[1]) : ''; };
      return { id: g(/<id>t3_([^<]+)<\/id>/), title: g(/<title>([\s\S]*?)<\/title>/), text: g(/<content[^>]*>([\s\S]*?)<\/content>/).replace(/<[^>]+>/g, ' '), url: g(/<link href="([^"]+)"/), t: Date.parse(g(/<updated>([^<]+)<\/updated>/)), n: null, flair: '' };
    });
    return { via: 'rss', posts };
  }
  return { via: 'blocked', posts: [] };
}

function score(p) {
  const s = `${p.title}\n${p.text}`.slice(0, 4000);
  if (NOISE.test(s)) return null;
  let sc = 0; const why = []; const offers = new Set();
  for (const t of TOPICS) if (t.k.test(s)) { sc += t.w; why.push(t.k.source.slice(0, 24)); offers.add(t.offer); }
  if (/\?/.test(p.title)) sc += 1; // a question we can answer
  if (p.n !== null && p.n < 5) sc += 1; // few answers yet -> our reply is seen
  return sc >= 3 ? { sc, offers: [...offers] } : null;
}

// Exam: node tools/bots/reddit-watch.js --exam  (tools/bots/reddit-watch/exam.json, no network)
if (process.argv.includes('--exam')) {
  const ex = JSON.parse(fs.readFileSync(path.join(__dirname, 'reddit-watch/exam.json'), 'utf8'));
  let ok = 0;
  for (const e of ex) { const got = !!score({ title: e.title, text: e.text || '', n: 2 }); if (got === e.useful) ok++; else console.log(`MISS (want ${e.useful}): ${e.title}`); }
  console.log(`exam: ${ok}/${ex.length}`); process.exit(ok === ex.length ? 0 : 1);
}

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const mem = load(); mem.runs = (mem.runs || 0) + 1;
  const now = Date.now(); const DAY = 864e5;
  const found = []; const status = [];
  for (const sub of SUBS) {
    const { via, posts } = await fetchSub(sub);
    let fresh = 0;
    for (const p of posts) {
      if (!p.id || mem.seen[p.id] || !(now - p.t < 2 * DAY)) continue;
      fresh++;
      const r = score(p);
      mem.seen[p.id] = now;
      if (r) { found.push({ sub, ...p, ...r }); for (const o of r.offers) mem.hits[o] = (mem.hits[o] || 0) + 1; }
    }
    status.push(`r/${sub}: ${via}, ${posts.length} read, ${fresh} new`);
    await sleep(1500);
  }
  for (const [id, t] of Object.entries(mem.seen)) if (now - t > 14 * DAY) delete mem.seen[id];
  found.sort((a, b) => b.sc - a.sc);
  const date = new Date().toISOString().slice(0, 10);
  const L = [`# Reddit — где наш товар полезен (${date}, ступень ${STAGE})`, '',
    'Робот только читает. Отвечать вручную: сначала польза, ссылка — если уместно и разрешено правилами сабреддита, с UTM `?utm_source=reddit&utm_medium=community&utm_campaign=black_month_2026&utm_content=<id>`.', '',
    `Найдено: **${found.length}**`, ''];
  for (const f of found.slice(0, 30)) L.push(`- [${f.sc}] r/${f.sub} — [${f.title.replace(/[\[\]]/g, '')}](${f.url})${f.n !== null ? ` · ${f.n} комм.` : ''} → ${f.offers.join('; ')}`);
  L.push('', '## Источники', ...status.map((s) => `- ${s}`));
  const md = L.join('\n') + '\n';
  fs.writeFileSync(path.join(OUT, `reddit-${date}.md`), md);
  fs.writeFileSync(path.join(OUT, 'latest.md'), md);
  fs.writeFileSync(MEM, JSON.stringify(mem, null, 1));
  console.log(md);
  if (status.every((s) => s.includes('blocked'))) { console.log('ALL BLOCKED'); process.exitCode = 1; }
})();
