// Content combine on GitHub robots (17.11.2026, owner: «контент комбайн с помощью клод и локальной ллм и гит хаб ботов»).
// A local text model (Ollama, free) writes drafts from REAL site data; Claude then picks the best and polishes them.
//   node tools/content/llm-content.mjs <task> <out.md>     tasks: reels, captions, youtube, email, community, hooks
// Facts come only from: the public store API (names, prices, sale), docs/offers.md, docs/customer-needs.md,
// docs/robots-summary.md. The model is told not to invent prices, dates or facts.
import fs from 'node:fs';

const [TASK, OUT] = process.argv.slice(2);
const MODEL = process.env.OLLAMA_MODEL || 'qwen2.5:7b';
const HOST = process.env.OLLAMA_HOST_URL || 'http://127.0.0.1:11434';
const read = (f) => { try { return fs.readFileSync(f, 'utf8').slice(0, 6000); } catch { return ''; } };

async function products() {
  for (let i = 0; i < 5; i++) {
    try {
      const r = await fetch('https://solid-sound.net/wp-json/wc/store/v1/products?per_page=60&orderby=popularity', { headers: { 'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/130 Safari/537.36 SolidSoundQA/1.0' } });
      if (r.ok) {
        const j = await r.json();
        return j.map(p => {
          const m = 10 ** (p.prices?.currency_minor_unit ?? 2), cur = p.prices?.currency_symbol || '$';
          const price = +p.prices?.price / m, reg = +p.prices?.regular_price / m;
          const tags = (p.tags || []).map(t => t.name).slice(0, 6).join(', ');
          return `- ${p.name}: ${cur}${price}${reg > price ? ` (was ${cur}${reg})` : ''}${tags ? ` · ${tags}` : ''} · ${p.permalink}`;
        }).join('\n');
      }
    } catch { /* retry */ }
    await new Promise(z => setTimeout(z, 8000 * (i + 1)));
  }
  return '(store list did not load)';
}

const RULES = `Rules: use ONLY the facts below (product names, prices, the sale). Never invent prices, discounts, dates, reviews or numbers.
Do not mention the owner by name and do not write about him personally. Warm, friendly "family of producers" tone, no hype words like "insane".
Brand: Solid Sound — Ableton Live templates, Serum presets, mixing & mastering studio. Site: solid-sound.net.`;

const TASKS = {
  reels: `Write 5 short vertical video (Reels/TikTok/Shorts, 15–30 s) scripts that sell the products below.
For each: a title in Russian (for the owner), the hook for the first 2 seconds (English, on-screen text), 4–6 shots with what is on screen and the on-screen text (English),
which product and its real price, a call to action, and the caption (English, max 2 lines + 5 hashtags). Answer in Markdown.`,
  captions: `Write ready-to-post Instagram captions (English) for the 8 most interesting products below: 2–4 short lines, one emoji max per line, the real price
and the sale if there is one, a soft call to action ("link in bio"), 6–10 relevant hashtags. Before each caption, one line in Russian: what picture/video to post with it. Markdown.`,
  youtube: `Suggest 4 YouTube videos (tutorial or breakdown) that would bring buyers to the products below. For each: a click-worthy but honest title (English),
the description (English, 5–8 lines with the product link), 8 tags, 5 chapter timestamps, and one line in Russian for the owner: what to film and why it sells. Markdown.`,
  email: `Write one newsletter email (English) for the current sale: subject (3 variants), preview text, body (short, warm, scannable, 3 product picks with real prices
and links, one clear button text), P.S. line. Then one line in Russian for the owner: whom to send it to and when. Markdown.`,
  community: `Write 5 forum/community discussion starters (English) for the site's forum that make producers feel at home and talk:
title, the first post (3–6 lines, ends with a question), and why it works (one line in Russian). Also one weekly ritual idea for the community (in Russian, 3 lines). Markdown.`,
  hooks: `Give 20 different first-line hooks (English) for short music-production videos that lead to the products below (e.g. a mistake, a before/after, a secret, a challenge).
Group them by type, and after each group one line in Russian: which product it fits. Markdown.`,
};

const sys = `${RULES}\n\nFACTS — products (popular first):\n${await products()}\n\nFACTS — offers:\n${read('docs/offers.md')}\n\nFACTS — what customers need:\n${read('docs/customer-needs.md')}\n\nFACTS — robots' summary:\n${read('docs/robots-summary.md')}`;

const t0 = Date.now();
/* streamed (17.11): on a CPU robot the 7B model needs >5 min, and a non-streamed request hit Node's 300 s header timeout */
const r = await fetch(HOST + '/api/chat', { method: 'POST', headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ model: MODEL, stream: true, options: { temperature: 0.8, num_ctx: 8192 }, messages: [{ role: 'system', content: sys }, { role: 'user', content: TASKS[TASK] || TASKS.hooks }] }) });
let text = '', buf = '';
const dec = new TextDecoder();
for await (const chunk of r.body) {
  buf += dec.decode(chunk, { stream: true }); let i;
  while ((i = buf.indexOf('\n')) >= 0) { const line = buf.slice(0, i).trim(); buf = buf.slice(i + 1); if (!line) continue; try { const j = JSON.parse(line); if (j.message && j.message.content) text += j.message.content; } catch { /* partial line */ } }
}
if (!text.trim()) text = '(модель не ответила)';
fs.writeFileSync(OUT, `\n## ${({ reels: 'Ролики (сценарии)', captions: 'Подписи для Instagram', youtube: 'YouTube', email: 'Письмо подписчикам', community: 'Темы для форума (семья)', hooks: 'Цепляющие первые фразы' })[TASK] || TASK}\n\n_Черновик локальной нейросети (${MODEL}, ${Math.round((Date.now() - t0) / 60000)} мин). Claude проверяет и доводит._\n\n${text.trim()}\n`);
console.log('ok', TASK, text.length);
