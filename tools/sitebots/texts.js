// TEXTS (owner 21.11.2026): typos and grammar on the English pages and in product descriptions (LanguageTool — free public
// checker, only the public site text is sent), Cyrillic left on English pages, and products sharing the same description.
'use strict';
const { BASE, get, sleep, esc } = require('../bots/lib');
const PAGES_ALL = ['/en/', '/en/all-products/', '/en/services-prices/', '/en/why-solid-sound/', '/en/faq/', '/en/free/', '/en/sell/', '/en/get-heard/', '/en/studio-calculator/', '/en/terms/', '/en/legal-notice/', '/en/wiki/'];
const PAGES = process.env.SITEBOTS_SMOKE === '1' ? PAGES_ALL.slice(0, 1) : PAGES_ALL;
// our words the dictionary does not know
const OK = /^(ableton|serum|bpm|daw|grenor|grenør|solid|kratos|protos|infinity|intricate|mutualism|vst|wav|midi|stems?|presets?|mixdown|edm|techno|vocals?|beatport|spotify|youtube|tiktok|instagram|soundcloud|sidechain|eq|lufs|fx|synths?|plugins?|royalty|gmbh|stripe|paypal|wiki|ua|faq|dj|djs|vat|ai|mp3|zip|vocoder|arp|arps|pluck|plucks|reese|hihats?|kickdrum|subbass|melodic|dubstep|trance|hardstyle|livestream|patreon|wishlist|checkout|vendor|vendors|remix|remixes|remake|co-producing|mastering|ozone|fabfilter|xfer)$/i;

const visible = html => String(html || '').replace(/<(script|style|noscript|svg|template)[\s\S]*?<\/\1>/gi, ' ').replace(/<(header|footer|nav)\b[\s\S]*?<\/\1>/gi, ' ')
  .replace(/<br\s*\/?>|<\/(p|div|li|h\d|td|section)>/gi, '\n').replace(/<[^>]+>/g, ' ').replace(/&nbsp;/g, ' ').replace(/&amp;/g, '&').replace(/&#8217;|&#039;|&#39;/g, "'").replace(/&#8220;|&#8221;|&quot;/g, '"').replace(/&#8211;|&#8212;/g, '—').replace(/&[a-z#0-9]+;/gi, ' ')
  .split('\n').map(s => s.replace(/\s+/g, ' ').trim()).filter(s => s.length > 25 && /[a-z]{3}/i.test(s)).join('\n');

async function lt(text) {
  const out = []; for (let i = 0; i < text.length; i += 15000) {
    const part = text.slice(i, i + 15000);
    const r = await fetch('https://api.languagetool.org/v2/check', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ text: part, language: 'en-US', disabledCategories: 'TYPOGRAPHY,CASING,STYLE,REDUNDANCY', disabledRules: 'WHITESPACE_RULE,EN_QUOTES,DASH_RULE,ENGLISH_WORD_REPEAT_BEGINNING_RULE' }) }).catch(e => null);
    if (!r || !r.ok) { out.push({ fail: r ? 'HTTP ' + r.status : 'нет связи' }); await sleep(20000); continue; }
    const j = await r.json(); for (const m of j.matches || []) { const w = part.substr(m.offset, m.length);
      if (OK.test(w) || /^[A-Z0-9]{2,}$/.test(w) || /\d/.test(w)) continue;
      out.push({ w, msg: m.shortMessage || m.message, fix: (m.replacements || []).slice(0, 2).map(x => x.value).join(' / '), ctx: m.context.text.slice(Math.max(0, m.context.offset - 30), m.context.offset + m.context.length + 30) }); }
    await sleep(4000); // public API: 20 requests / minute
  }
  return out;
}

module.exports = async function texts() {
  const found = []; const cyr = []; let failed = 0;
  for (const p of PAGES) {
    const r = await get(BASE + p); await sleep(1500); const t = visible(r.text);
    const c = t.split('\n').filter(l => /[а-яіїєґ]{3}/i.test(l)); if (c.length) cyr.push([p, c.slice(0, 2)]);
    for (const m of await lt(t)) { if (m.fail) { failed++; continue; } found.push({ ...m, where: p }); }
  }
  const prods = (await get(BASE + '/wp-json/wc/store/v1/products?per_page=100')).json();
  const byDesc = {};
  for (const p of prods) {
    const d = visible('<p>' + (p.description || '') + '</p><p>' + (p.short_description || '') + '</p>');
    if (d.length > 40) (byDesc[d.slice(0, 200)] = byDesc[d.slice(0, 200)] || []).push(p.name);
    for (const m of await lt(d)) { if (m.fail) { failed++; continue; } found.push({ ...m, where: 'товар «' + p.name + '»' }); }
  }
  const same = Object.values(byDesc).filter(l => l.length > 1);
  // the same word flagged on many pages is one finding
  const g = {}; for (const f of found) { const k = f.w.toLowerCase() + '|' + f.msg; (g[k] = g[k] || { ...f, where: [] }).where.push(f.where); }
  const L = Object.values(g).sort((a, b) => b.where.length - a.where.length);
  let md = `## ✍️ Тексты: опечатки и ошибки (английский)\n\nПроверено: ${PAGES.length} страниц и ${prods.length} описаний товаров. Возможных ошибок: **${L.length}**${failed ? ` (проверщик не ответил ${failed} раз — часть текста не проверена)` : ''}.\n\n`;
  if (L.length) md += '| Слово | Что не так | Как исправить | Где | Фрагмент |\n|---|---|---|---|---|\n' + L.slice(0, 40).map(f => `| ${esc(f.w)} | ${esc(f.msg)} | ${esc(f.fix) || '—'} | ${esc([...new Set(f.where)].slice(0, 3).join(', '))} | …${esc(f.ctx)}… |`).join('\n') + '\n\n';
  if (cyr.length) md += '### 🟠 Русский/украинский текст на английских страницах\n' + cyr.map(([p, l]) => `- ${p}: «${esc(l[0].slice(0, 90))}»`).join('\n') + '\n\n';
  if (same.length) md += '### 🟡 Одинаковые описания у разных товаров (Google считает копией)\n' + same.map(l => '- ' + l.join(', ')).join('\n') + '\n\n';
  return { md, metrics: { texts_typos: L.length, texts_cyrillic_pages: cyr.length, texts_same_desc: same.length }, alerts: cyr.length ? [`Тексты: кириллица на ${cyr.length} англ. страницах`] : [] };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
