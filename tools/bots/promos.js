// Promo calendar: upcoming occasions for sales/posts (next 5 weeks) + expired promo banners still on the site.
'use strict';
const { BASE, get } = require('./lib');

// month is 1-12; "nth weekday" items are computed per year
const FIXED = [
  ['01-01', 'Новый год — подборки «старт года», скидка на бандлы'], ['02-14', 'День святого Валентина — love/romantic паки, «подарок другу-продюсеру»'],
  ['03-08', '8 марта (UA) — акция для женщин-продюсеров / подарочные сертификаты'], ['03-25', 'Неделя Miami Music Week / WMC (конец марта) — techno/house паки'],
  ['04-22', 'День Земли — «green» релизы, благотворительная акция'], ['06-21', 'День музыки (Fête de la Musique) — бесплатный пак + скидка'],
  ['07-15', 'Середина лета — летняя распродажа'], ['08-24', 'День Независимости Украины — акция UA'], ['10-15', 'ADE Amsterdam Dance Event (середина октября) — techno/house'],
  ['10-31', 'Halloween — dark techno паки, «страшные» скидки'], ['12-01', 'Старт «Black Month» / Cyber Monday'], ['12-15', 'Подарки к Рождеству — сертификаты, бандлы'],
  ['12-26', 'Boxing Day / итоги года — распродажа остатков'],
];
function nthWeekday(year, month, weekday, n) { const d = new Date(Date.UTC(year, month - 1, 1)); let c = 0; while (true) { if (d.getUTCDay() === weekday && ++c === n) return d; d.setUTCDate(d.getUTCDate() + 1); } }
function lastWeekday(year, month, weekday) { const d = new Date(Date.UTC(year, month, 0)); while (d.getUTCDay() !== weekday) d.setUTCDate(d.getUTCDate() - 1); return d; }

module.exports = async function promos() {
  const L = ['## 📅 Акции и поводы на ближайшие 5 недель', ''];
  const now = new Date(); const until = new Date(now.getTime() + 35 * 864e5);
  const ev = [];
  for (const y of [now.getUTCFullYear(), now.getUTCFullYear() + 1]) {
    FIXED.forEach(([md, t]) => ev.push([new Date(`${y}-${md}T00:00:00Z`), t]));
    const bf = lastWeekday(y, 11, 5); ev.push([bf, 'Black Friday — главная распродажа года (готовить за 2 недели: баннер, письма, соцсети)']);
    ev.push([new Date(bf.getTime() + 3 * 864e5), 'Cyber Monday']);
    ev.push([nthWeekday(y, 5, 0, 2), 'День матери (2-е воскресенье мая) — подарочные сертификаты']);
  }
  const soon = ev.filter(([d]) => d >= new Date(now.toDateString()) && d <= until).sort((a, b) => a[0] - b[0]);
  L.push(soon.length ? soon.map(([d, t]) => `- **${d.toISOString().slice(5, 10).split('-').reverse().join('.')}** — ${t} (через ${Math.ceil((d - now) / 864e5)} дн.)`).join('\n') : '- ближайших поводов нет');
  // promo banners on the site: a countdown/"ends" date that is already in the past, or ending within 3 days
  const r = await get(BASE + '/en/all-products/');
  if (r.status === 200) {
    const m = r.text.match(/PROMO_END\s*=\s*(\d{10,13})|data-promo-end="([^"]+)"|ends?\s+(?:on\s+)?(\d{1,2}[./]\d{1,2}[./]\d{2,4})/i);
    const promoText = (r.text.match(/BLACK MONTH|Black Friday|SALE[^<]{0,40}/i) || [])[0];
    if (promoText) L.push('', `Сейчас на сайте акция: «${promoText.trim()}».`);
    if (m && m[1]) { const end = new Date(+m[1] > 1e12 ? +m[1] : +m[1] * 1000); const left = Math.ceil((end - now) / 864e5); L.push(left < 0 ? `⚠️ Акция закончилась ${-left} дн. назад, а баннер ещё висит — убрать или продлить.` : left <= 3 ? `⏰ Акция заканчивается через ${left} дн. — письмо «последний шанс» подписчикам.` : `Акция идёт ещё ${left} дн.`); }
  }
  return L.join('\n') + '\n';
};
