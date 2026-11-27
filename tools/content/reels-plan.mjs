// Reels plan (owner 17.11.2026: «хочу полноценные видосы с голосом — рилсы»).
// Picks today's products from the store (real names, prices, covers, audio previews) and writes a short
// voice-over script for each with the local model (Ollama). Prices and the call to action are NOT written by the model.
//   node tools/content/reels-plan.mjs <count> <out.json>
import fs from 'node:fs';

const [COUNT = '3', OUT = 'reels.json'] = process.argv.slice(2);
const MODEL = process.env.OLLAMA_MODEL || 'qwen2.5:7b';
const HOST = process.env.OLLAMA_HOST_URL || 'http://127.0.0.1:11434';
const UA = { 'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/130 Safari/537.36 SolidSoundQA/1.0' };
const sleep = (ms) => new Promise(z => setTimeout(z, ms));

async function getJSON(u) {
  for (let i = 0; i < 6; i++) { try { const r = await fetch(u, { headers: UA }); if (r.ok) return await r.json(); } catch { /* retry */ } await sleep(8000 * (i + 1)); }
  return null;
}
async function ask(prompt) {
  const r = await fetch(HOST + '/api/chat', { method: 'POST', headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ model: MODEL, stream: true, format: 'json', options: { temperature: 0.9 }, messages: [{ role: 'user', content: prompt }] }) });
  let text = '', buf = ''; const dec = new TextDecoder();
  for await (const c of r.body) { buf += dec.decode(c, { stream: true }); let i; while ((i = buf.indexOf('\n')) >= 0) { const l = buf.slice(0, i).trim(); buf = buf.slice(i + 1); if (!l) continue; try { const j = JSON.parse(l); if (j.message?.content) text += j.message.content; } catch { /* partial */ } } }
  return JSON.parse(text);
}

const list = await getJSON('https://solid-sound.net/wp-json/wc/store/v1/products?per_page=60&orderby=popularity') || [];
const prods = [];
for (const p of list) {
  const img = p.images?.[0]?.src; if (!img) continue;
  const m = 10 ** (p.prices?.currency_minor_unit ?? 2);
  const price = +p.prices.price / m, reg = +p.prices.regular_price / m; if (!price) continue;
  const media = await getJSON(`https://solid-sound.net/wp-json/wp/v2/media?parent=${p.id}&media_type=audio&_fields=source_url`);
  const audio = (media || []).map(x => x.source_url).find(u => /\.mp3$/i.test(u)); if (!audio) continue;
  prods.push({ id: p.id, name: p.name, price, reg, img, audio, url: p.permalink,
    tags: [...(p.tags || []), ...(p.categories || [])].map(t => t.name).filter(Boolean).slice(0, 8),
    desc: String(p.short_description || p.description || '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').slice(0, 500) });
  await sleep(1500);
}
if (!prods.length) { console.error('no products'); process.exit(1); }
// a different set every day
const day = Math.floor(Date.now() / 864e5);
const pick = []; for (let k = 0; k < +COUNT && k < prods.length; k++) pick.push(prods[(day * +COUNT + k) % prods.length]);

const money = (v) => '$' + (v % 1 ? v.toFixed(2) : v);
const reels = [];
for (const p of pick) {
  const sale = p.reg > p.price;
  const facts = `Product: ${p.name}. Tags: ${p.tags.join(', ')}. Description: ${p.desc}`;
  let s = null;
  for (let t = 0; t < 2 && !s; t++) {
    try {
      s = await ask(`You write a 20-second voice-over for an Instagram Reel that sells a music production product.
${facts}
Rules: English, spoken style, warm and confident, no hype words like "insane"; use ONLY the facts above, never invent numbers, prices, dates or reviews; do not mention any person by name.
Return JSON: {"hook":"first line, max 9 words, makes a producer stop scrolling","lines":["3 short sentences, max 13 words each: what it is, what you learn or get, why it is easy"],"title_ru":"короткое название ролика по-русски","caption":"Instagram caption, English, 2 short lines, no price","hashtags":"8 hashtags"}`);
      if (!s || !s.hook || !Array.isArray(s.lines) || s.lines.length < 2) s = null;
    } catch { s = null; }
  }
  if (!s) s = { hook: `Want a ${p.tags[0] || 'techno'} track that sounds finished?`, lines: [`${p.name} is a full project you can open and learn from.`, 'Every sound, every effect, every automation is right there.', 'Make it your own and release it.'], title_ru: p.name, caption: `${p.name} — a full project you can open, learn from and make your own.`, hashtags: '#ableton #abletonlive #techno #musicproducer #producer #edm #beatmaking #solidsound' };
  // nothing with numbers or money from the model — prices only from the store
  s.lines = s.lines.map(String).filter(l => !/[$€£]|\d+\s*%/.test(l)).slice(0, 3);
  if (/[$€£]/.test(s.hook)) s.hook = `Want a ${p.tags[0] || 'techno'} track that sounds finished?`;
  const cta = sale ? `${p.name} is ${money(p.price)} instead of ${money(p.reg)}. Link in bio, solid-sound dot net.` : `Get ${p.name} for ${money(p.price)}. Link in bio, solid-sound dot net.`;
  const str = (v) => Array.isArray(v) ? v.join(' ') : String(v || ''); /* the model sometimes returns lists */
  s.caption = str(s.caption); s.hashtags = str(s.hashtags); s.title_ru = str(s.title_ru); s.hook = str(s.hook);
  reels.push({ ...p, priceText: money(p.price), regText: sale ? money(p.reg) : '', hook: s.hook, lines: s.lines, cta, title_ru: s.title_ru || p.name, caption: s.caption || '', hashtags: s.hashtags || '' });
  console.log('ok', p.name);
}
fs.writeFileSync(OUT, JSON.stringify(reels, null, 1));
