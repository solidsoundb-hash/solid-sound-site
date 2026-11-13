// SEO bot (Google Search Console): what people search to find us, growing / falling pages, queries without
// a good page (ideas for Wiki articles and landing pages), sitemap health. Needs secrets GOOGLE_SA_JSON + GSC_SITE.
'use strict';
const { metrics, BASE, googleToken, gpost, gget, day, daysAgo, pct, sign, esc, loadState, saveState } = require('./lib');

module.exports = async function seo() {
  const L = ['## 🔎 Google: как нас находят (Search Console)', ''];
  const site = process.env.GSC_SITE || 'sc-domain:solid-sound.net';
  let auth;
  try { auth = await googleToken(['https://www.googleapis.com/auth/webmasters.readonly']); } catch (e) { return L.concat(['⚠️ ' + e.message, '']).join('\n'); }
  if (!auth) return L.concat(['⏸ Не подключён: нужен доступ к Search Console (инструкция — `docs/bots.md`, шаг «Google»).', '']).join('\n');
  const api = 'https://www.googleapis.com/webmasters/v3/sites/' + encodeURIComponent(site);
  // data in Search Console lags ~3 days: compare the last 7 full days with the 7 before
  const end = daysAgo(3), start = daysAgo(9), pEnd = daysAgo(10), pStart = daysAgo(16);
  const q = (s, e, dims, limit) => gpost(api + '/searchAnalytics/query', auth.token, { startDate: day(s), endDate: day(e), dimensions: dims, rowLimit: limit || 250 }).then(j => j.rows || []);
  let tot, totP, qs, qsP, pages, pagesP;
  try {
    [tot, totP, qs, qsP, pages, pagesP] = await Promise.all([q(start, end, []), q(pStart, pEnd, []), q(start, end, ['query'], 500), q(pStart, pEnd, ['query'], 500), q(start, end, ['page'], 200), q(pStart, pEnd, ['page'], 200)]);
  } catch (e) { return L.concat([`⚠️ Search Console не отвечает: ${e.message}. Проверьте, что адрес бота ${auth.email} добавлен в Search Console как пользователь, а GSC_SITE = ${site}.`, '']).join('\n'); }
  const t = tot[0] || { clicks: 0, impressions: 0, position: 0 }, tp = totP[0] || { clicks: 0, impressions: 0, position: 0 };
  L.push(`За неделю ${day(start)} — ${day(end)}: **переходов из Google ${t.clicks}** (${sign(pct(t.clicks, tp.clicks))}% к прошлой неделе), **показов ${t.impressions}** (${sign(pct(t.impressions, tp.impressions))}%), средняя позиция ${t.position ? t.position.toFixed(1) : '—'} (была ${tp.position ? tp.position.toFixed(1) : '—'}).`, '');
  Object.assign(metrics, { seo_clicks: t.clicks, seo_impressions: t.impressions, seo_position: t.position ? +t.position.toFixed(1) : null, seo_queries: qs.length });
  const prevQ = new Map(qsP.map(r => [r.keys[0], r]));
  L.push('**Топ запросов** (по показам):', '', '| Запрос | Показы | Переходы | Позиция | Было |', '|---|---|---|---|---|');
  qs.slice().sort((a, b) => b.impressions - a.impressions).slice(0, 15).forEach(r => { const p = prevQ.get(r.keys[0]); L.push(`| ${esc(r.keys[0])} | ${r.impressions} | ${r.clicks} | ${r.position.toFixed(1)} | ${p ? p.position.toFixed(1) : 'новый'} |`); });
  // opportunities: seen often, but we are on page 2+ or nobody clicks -> a better page / article is worth it
  const opp = qs.filter(r => r.impressions >= 20 && (r.position > 10 || r.ctr < 0.01)).sort((a, b) => b.impressions - a.impressions).slice(0, 12);
  L.push('', '**💡 Возможности** — нас показывают, но мы далеко или по нам не кликают (сделать/улучшить страницу или статью Wiki):', '');
  L.push(opp.length ? opp.map(r => `- «${r.keys[0]}» — ${r.impressions} показов, позиция ${r.position.toFixed(1)}, переходов ${r.clicks}`).join('\n') : '- пока нет (мало данных)');
  const prevP = new Map(pagesP.map(r => [r.keys[0], r]));
  const moves = pages.map(r => ({ u: r.keys[0].replace(BASE, ''), c: r.clicks, d: r.clicks - ((prevP.get(r.keys[0]) || {}).clicks || 0), i: r.impressions })).filter(x => Math.abs(x.d) >= 3).sort((a, b) => a.d - b.d);
  if (moves.length) { L.push('', '**Страницы: рост и падение переходов**', ''); moves.slice(0, 6).concat(moves.slice(-6)).filter((x, i, a) => a.indexOf(x) === i).forEach(x => L.push(`- ${x.d > 0 ? '📈' : '📉'} ${x.u}: ${x.c} переходов (${sign(x.d)})`)); }
  const st = loadState('seo'); const known = new Set((st && st.queries) || []);
  const fresh = qs.filter(r => !known.has(r.keys[0]) && r.impressions >= 5).map(r => r.keys[0]).slice(0, 15);
  if (st && fresh.length) L.push('', '**Новые запросы этой недели:** ' + fresh.map(x => `«${x}»`).join(', '));
  saveState('seo', { queries: [...new Set([...known, ...qs.map(r => r.keys[0])])].slice(-3000) });
  try {
    const sm = await gget(api + '/sitemaps', auth.token);
    const bad = (sm.sitemap || []).filter(s => +s.errors > 0 || +s.warnings > 0 || s.isPending);
    L.push('', '**Карта сайта в Google:** ' + ((sm.sitemap || []).length ? (bad.length ? bad.map(s => `${s.path} — ошибок ${s.errors}, предупреждений ${s.warnings}${s.isPending ? ', ещё обрабатывается' : ''}`).join('; ') : 'ок, ' + sm.sitemap.length + ' шт., без ошибок') : '⚠️ не добавлена — добавить https://solid-sound.net/wp-sitemap.xml в Search Console'));
  } catch (e) {}
  return L.join('\n') + '\n';
};
