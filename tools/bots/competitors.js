// Competitor watch: once a week reads the public product lists of competitor shops (tools/bots/competitors.json):
// new products, price changes, sales — and compares their price range with ours.
'use strict';
const fs = require('fs');
const path = require('path');
const { get, esc, loadState, saveState } = require('./lib');

module.exports = async function competitors() {
  const L = ['## 🕵️ Конкуренты: что изменилось за неделю', ''];
  const cfg = JSON.parse(fs.readFileSync(path.join(__dirname, 'competitors.json'), 'utf8'));
  const st = loadState('competitors') || {}; const next = {};
  for (const c of cfg.shopify || []) {
    const r = await get(c.url, { tries: 2 });
    if (r.status !== 200) { L.push(`- **${c.name}**: не удалось прочитать (${r.error || 'HTTP ' + r.status})`); continue; }
    let prods; try { prods = r.json().products || []; } catch (e) { L.push(`- **${c.name}**: неожиданный ответ`); continue; }
    const map = {}; prods.forEach(p => { const v = (p.variants || [])[0] || {}; map[p.handle] = { t: p.title, p: +v.price || 0, cmp: +v.compare_at_price || 0 }; });
    next[c.name] = map;
    const old = st[c.name] || {};
    const prices = Object.values(map).map(x => x.p).filter(Boolean).sort((a, b) => a - b);
    const onSale = Object.values(map).filter(x => x.cmp > x.p).length;
    const added = Object.keys(map).filter(h => !old[h]); const removed = Object.keys(old).filter(h => !map[h]);
    const changed = Object.keys(map).filter(h => old[h] && old[h].p !== map[h].p);
    const host = new URL(c.url).origin;
    L.push(`- **${c.name}**: товаров ${prods.length}, цены ${prices[0] || '—'}–${prices[prices.length - 1] || '—'} (медиана ${prices[Math.floor(prices.length / 2)] || '—'}), со скидкой ${onSale}`);
    if (Object.keys(old).length) {
      if (added.length) L.push(`  - 🆕 новые: ${added.slice(0, 8).map(h => `[${esc(map[h].t)}](${host}/products/${h}) (${map[h].p})`).join(', ')}${added.length > 8 ? ` и ещё ${added.length - 8}` : ''}`);
      if (changed.length) L.push(`  - 💲 изменили цену: ${changed.slice(0, 8).map(h => `${esc(map[h].t)} ${old[h].p} → ${map[h].p}`).join(', ')}`);
      if (removed.length) L.push(`  - убрали: ${removed.length}`);
      if (!added.length && !changed.length && !removed.length) L.push('  - без изменений');
    } else L.push('  - первый замер — изменения покажу со следующей недели');
  }
  saveState('competitors', next);
  L.push('', '_Для решения: если конкуренты массово снижают цены или запускают акцию — подумать о своей акции или бандле._');
  return L.join('\n') + '\n';
};
