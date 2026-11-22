// CATALOG (owner 21.11.2026): every product is complete and its files really load. Adds to tools/bots/products.js (text,
// picture, category): the sound preview file opens and is audio, the cover opens, BPM / key / length are filled,
// gallery pictures, reviews. Store API only (public), plus a HEAD request per file.
'use strict';
const { BASE, get, sleep, esc } = require('../bots/lib');
async function head(u) {
  try { let r = await fetch(u, { method: 'HEAD', redirect: 'follow', signal: AbortSignal.timeout(30000), headers: { 'User-Agent': 'Mozilla/5.0 SolidSoundBots/1.0' } });
    if (r.status === 405 || r.status === 403) r = await fetch(u, { headers: { Range: 'bytes=0-1023', 'User-Agent': 'Mozilla/5.0 SolidSoundBots/1.0' }, signal: AbortSignal.timeout(30000) });
    return { st: r.status, type: r.headers.get('content-type') || '', len: +(r.headers.get('content-length') || 0) }; } catch (e) { return { st: 0, err: e.message }; }
}
module.exports = async function catalog() {
  const r = await get(BASE + '/wp-json/wc/store/v1/products?per_page=100');
  if (!r.status) return { md: '## 🎛 Товары: всё ли на месте\n\n⏸ Магазин не ответил.\n', metrics: {}, alerts: [] };
  const list = r.json(); const rows = []; let broken = 0, incomplete = 0;
  for (const p of list) {
    const P = []; const html = (p.short_description || '') + (p.description || '');
    const isPreset = /preset/i.test(p.name + ' ' + (p.categories || []).map(c => c.name).join(' '));
    const audio = [...new Set([...html.matchAll(/https?:[^"'\s<>]+\.(?:mp3|wav|ogg|m4a)/gi)].map(m => m[0].replace(/\\\//g, '/')))];
    if (!audio.length) P.push('⚠️ нет превью-звука в описании (на странице может быть свой плеер — проверить)');
    for (const a of audio.slice(0, 2)) { const h = await head(a); await sleep(500);
      if (h.st !== 200 && h.st !== 206) { P.push(`🔴 превью не открывается (${h.st || h.err}): ${a.split('/').pop()}`); broken++; }
      else if (!/audio|octet/.test(h.type)) { P.push(`🔴 превью — не звук (${h.type})`); broken++; }
      else if (h.len && h.len < 200 * 1024) P.push(`🟡 превью очень маленькое (${Math.round(h.len / 1024)} КБ) — обрезано?`); }
    const img = (p.images || [])[0];
    if (img) { const h = await head(img.src); await sleep(500); if (h.st !== 200) { P.push(`🔴 обложка не открывается (${h.st || h.err})`); broken++; } }
    if ((p.images || []).length < 2) P.push('🟡 только одна картинка (галерея: скрин проекта в Ableton, список каналов — помогает продавать)');
    const at = Object.fromEntries((p.attributes || []).map(a => [a.name.toLowerCase(), (a.terms || []).map(t => t.name).join(', ')]));
    if (!isPreset) { if (!at.bpm) P.push('🟡 не указан BPM'); if (!at['root key'] && !at.key) P.push('🟡 не указана тональность'); if (!at.length) P.push('⚪ не указана длительность'); }
    if (!+p.review_count) P.push('⚪ нет отзывов (звёзды в Google появятся с первым отзывом)');
    if (!p.is_purchasable) { P.push('🔴 нельзя купить'); broken++; }
    if (P.some(x => /🟡|⚠️/.test(x))) incomplete++;
    rows.push([p.name, p.permalink, [at.bpm, at['root key']].filter(Boolean).join(' · ') || '—', audio.length, P]);
  }
  let md = `## 🎛 Товары: всё ли на месте и открывается\n\nТоваров: **${list.length}**. Сломано (файл не открывается / нельзя купить): **${broken}**. Неполных: **${incomplete}**. Тексты и картинки для Google — в недельном отчёте \`reports/weekly\`.\n\n`;
  md += '| Товар | BPM · тон | Превью | Что не так |\n|---|---|---|---|\n' + rows.map(x => `| [${esc(x[0])}](${x[1]}) | ${esc(x[2])} | ${x[3] ? '✅ ' + x[3] : '—'} | ${x[4].length ? esc(x[4].join('<br>')) : '✅'} |`).join('\n') + '\n';
  return { md, metrics: { catalog_products: list.length, catalog_broken: broken, catalog_incomplete: incomplete }, alerts: broken ? [`Товары: ${broken} сломанных файлов/товаров`] : [] };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
