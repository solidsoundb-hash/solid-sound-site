// Weekly business report: orders and revenue (WooCommerce, read-only key), visitors and where they come from
// (Google Analytics 4). Secrets: WC_KEY + WC_SECRET (WooCommerce read-only), GOOGLE_SA_JSON + GA4_PROPERTY_ID.
'use strict';
const { metrics, BASE, get, googleToken, gpost, day, daysAgo, pct, sign, esc } = require('./lib');

async function woo() {
  const L = ['### 💰 Продажи (WooCommerce)', ''];
  const k = process.env.WC_KEY, s = process.env.WC_SECRET;
  if (!k || !s) return L.concat(['⏸ Не подключено: нужен ключ магазина только для чтения (инструкция — `docs/bots.md`, шаг «Магазин»).', '']).join('\n');
  const auth = { Authorization: 'Basic ' + Buffer.from(k + ':' + s).toString('base64') };
  const orders = async (a, b) => {
    const out = [];
    for (let page = 1; page <= 10; page++) {
      const r = await get(`${BASE}/wp-json/wc/v3/orders?after=${a.toISOString()}&before=${b.toISOString()}&per_page=100&page=${page}&status=any`, { headers: auth });
      if (r.status !== 200) throw new Error(r.error || ('HTTP ' + r.status + ' ' + r.text.slice(0, 120)));
      const j = r.json(); out.push(...j); if (j.length < 100) break;
    }
    return out.filter(o => ['completed', 'processing', 'on-hold'].includes(o.status));
  };
  let cur, prev;
  try { [cur, prev] = await Promise.all([orders(daysAgo(7), new Date()), orders(daysAgo(14), daysAgo(7))]); } catch (e) { return L.concat(['⚠️ Магазин не отвечает: ' + e.message, '']).join('\n'); }
  const sum = (a) => a.reduce((n, o) => n + parseFloat(o.total || 0), 0);
  const cur$ = sum(cur), prev$ = sum(prev), cur0 = cur[0] ? cur[0].currency : '';
  L.push(`За 7 дней: **заказов ${cur.length}** (${sign(cur.length - prev.length)} к прошлой неделе), **выручка ${cur$.toFixed(2)} ${cur0}** (${sign(pct(cur$, prev$))}%), средний чек ${cur.length ? (cur$ / cur.length).toFixed(2) : '—'} ${cur0}.`, '');
  Object.assign(metrics, { orders: cur.length, revenue: +cur$.toFixed(2) });
  const items = new Map(); cur.forEach(o => (o.line_items || []).forEach(li => { const x = items.get(li.name) || { n: 0, sum: 0 }; x.n += li.quantity; x.sum += parseFloat(li.total || 0); items.set(li.name, x); }));
  if (items.size) { L.push('| Товар | Продано | Сумма |', '|---|---|---|'); [...items].sort((a, b) => b[1].sum - a[1].sum).slice(0, 10).forEach(([n, x]) => L.push(`| ${esc(n)} | ${x.n} | ${x.sum.toFixed(2)} |`)); L.push(''); }
  const abandoned = await get(`${BASE}/wp-json/wc/v3/orders?after=${daysAgo(7).toISOString()}&status=pending,failed,cancelled&per_page=100`, { headers: auth }).then(r => r.status === 200 ? r.json().length : 0).catch(() => 0);
  if (abandoned) L.push(`⚠️ Незавершённых/неудачных оплат за неделю: **${abandoned}** — стоит посмотреть, где люди бросают оплату.`, '');
  return L.join('\n') + '\n';
}

async function ga4() {
  const L = ['### 👥 Посетители (Google Analytics)', ''];
  const prop = process.env.GA4_PROPERTY_ID;
  let auth;
  try { auth = prop ? await googleToken(['https://www.googleapis.com/auth/analytics.readonly']) : null; } catch (e) { return L.concat(['⚠️ ' + e.message, '']).join('\n'); }
  if (!auth) return L.concat(['⏸ Не подключено: нужен доступ к Google Analytics (инструкция — `docs/bots.md`, шаг «Google»).', '']).join('\n');
  const run = (body) => gpost(`https://analyticsdata.googleapis.com/v1beta/properties/${prop}:runReport`, auth.token, body);
  try {
    const range = [{ startDate: '7daysAgo', endDate: 'yesterday', name: 'cur' }, { startDate: '14daysAgo', endDate: '8daysAgo', name: 'prev' }];
    const tot = await run({ dateRanges: range, metrics: [{ name: 'totalUsers' }, { name: 'sessions' }, { name: 'ecommercePurchases' }, { name: 'addToCarts' }] });
    const v = (rng, i) => { const row = (tot.rows || []).find(r => r.dimensionValues && r.dimensionValues.some(d => d.value === rng)) || (tot.rows || [])[rng === 'cur' ? 0 : 1]; return row ? +row.metricValues[i].value : 0; };
    Object.assign(metrics, { visitors: v('cur', 0), add_to_carts: v('cur', 3), purchases_ga: v('cur', 2) });
    L.push(`Посетителей **${v('cur', 0)}** (${sign(pct(v('cur', 0), v('prev', 0)))}%), визитов ${v('cur', 1)}, добавили в корзину ${v('cur', 3)}, покупок ${v('cur', 2)}.`, '');
    const src = await run({ dateRanges: [range[0]], dimensions: [{ name: 'sessionDefaultChannelGroup' }], metrics: [{ name: 'sessions' }, { name: 'ecommercePurchases' }], orderBys: [{ metric: { metricName: 'sessions' }, desc: true }], limit: 8 });
    L.push('Откуда приходят: ' + (src.rows || []).map(r => `${r.dimensionValues[0].value} — ${r.metricValues[0].value}${+r.metricValues[1].value ? ` (покупок ${r.metricValues[1].value})` : ''}`).join(', '), '');
    const pg = await run({ dateRanges: [range[0]], dimensions: [{ name: 'pagePath' }], metrics: [{ name: 'screenPageViews' }], orderBys: [{ metric: { metricName: 'screenPageViews' }, desc: true }], limit: 8 });
    L.push('Самые просматриваемые страницы: ' + (pg.rows || []).map(r => `${r.dimensionValues[0].value} (${r.metricValues[0].value})`).join(', '), '');
  } catch (e) { L.push(`⚠️ Google Analytics не отвечает: ${e.message}. Проверьте, что ${auth.email} добавлен в Analytics с ролью «Читатель», а GA4_PROPERTY_ID — это номер ресурса (только цифры).`, ''); }
  return L.join('\n') + '\n';
}

module.exports = async function analytics() {
  return ['## 📊 Неделя в цифрах', '', await woo(), await ga4()].join('\n');
};
