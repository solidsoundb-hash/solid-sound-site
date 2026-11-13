// MAIN ROBOT — supervisor of all bots. Runs last every week:
//  1) health: did every bot run this week, how long ago, errors, waiting for access;
//  2) analytics of the bots week by week (reports/bots-health/history.csv): findings, fixed, coverage, speed, growth numbers;
//  3) development plan: rule-based proposals (stuck bugs, possible false alarms, broken / silent bots, missing access,
//     trends) — the weekly Claude session reads it and improves the bots.
// Output: reports/bots-health/latest.md (+ dated copy in history/).
'use strict';
const fs = require('fs');
const path = require('path');

const OUT = 'reports/bots-health';
const now = Date.now();
const read = (f) => { try { return fs.readFileSync(f, 'utf8'); } catch (e) { return ''; } };
const json = (f) => { try { return JSON.parse(read(f)); } catch (e) { return null; } };
const ageDays = (iso) => (iso ? Math.floor((now - new Date(iso).getTime()) / 864e5) : null);
const fmtAge = (d) => (d == null ? 'никогда' : d === 0 ? 'сегодня' : d + ' дн. назад');

function qaHistory() {
  // every QA run is kept in qa/history/<date-time-mode>/bugs.json
  const dir = 'qa/history'; if (!fs.existsSync(dir)) return [];
  return fs.readdirSync(dir).sort().map(d => ({ dir: d, j: json(path.join(dir, d, 'bugs.json')) })).filter(x => x.j && x.j.bugs);
}

(async () => {
  fs.mkdirSync(path.join(OUT, 'history'), { recursive: true });
  const date = new Date().toISOString().slice(0, 10);
  const plan = []; const health = []; const row = { date };

  // ---------- QA bot
  const qa = json('qa/latest/bugs.json');
  const qaAge = qa ? ageDays(qa.date) : null;
  if (qa) {
    const c = (s) => qa.bugs.filter(b => b.sev === s).length;
    Object.assign(row, { qa_critical: c('critical'), qa_major: c('major'), qa_minor: c('minor'), qa_check: c('check'), qa_pages: qa.stats.pages, qa_clicks: qa.stats.clicks, qa_sounds: qa.stats.sounds, qa_links: qa.stats.links, qa_notchecked: (qa.notChecked || []).length });
    health.push(`| Тестировщик (баги) | ${fmtAge(qaAge)} | ${qa.mode} | страниц ${qa.stats.pages}, кнопок ${qa.stats.clicks}, звук ${qa.stats.sounds}, ссылок ${qa.stats.links} | 🔴${c('critical')} 🟠${c('major')} 🟡${c('minor')} ⚪${c('check')} |`);
    const crash = (qa.notChecked || []).filter(x => /остановился|Внутренняя ошибка|прервался/.test(x));
    if (crash.length) plan.push(`🛠 Тестировщик: сбои в работе (${crash.length}) — починить: ${crash.slice(0, 2).join(' | ').slice(0, 300)}`);
    const time = (qa.notChecked || []).filter(x => /не хватило времени/.test(x)).length;
    if (time) plan.push(`⏱ Тестировщик не успевает проверить всё (${time} пунктов «не хватило времени») — ускорить или увеличить время полной проверки.`);
    if ((qa.notChecked || []).some(x => /нет тестового логина/.test(x))) plan.push('🔑 Тестировщик не проверяет вход клиентом/админом — нет тестовых логинов в секретах GitHub.');
  } else health.push('| Тестировщик (баги) | никогда | — | — | — |');
  if (qaAge == null || qaAge > 8) plan.push('🚨 Тестировщик не запускался больше недели — проверить робота QA bot.');

  // stuck bugs and possible false alarms: same item in 3+ QA runs
  const hist = qaHistory();
  if (hist.length >= 3) {
    const last = hist.slice(-4); const seen = new Map();
    last.forEach(h => h.j.bugs.forEach(b => { const x = seen.get(b.sig) || { b, n: 0 }; x.n++; seen.set(b.sig, x); }));
    const stuck = [...seen.values()].filter(x => x.n >= 3 && x.b.sev !== 'check').sort((a, b) => ['critical', 'major', 'minor'].indexOf(a.b.sev) - ['critical', 'major', 'minor'].indexOf(b.b.sev));
    const noisy = [...seen.values()].filter(x => x.n >= 3 && x.b.sev === 'check');
    row.qa_stuck = stuck.length;
    if (stuck.length) plan.push(`📌 Висят ${stuck.length} бага(ов) 3+ проверки подряд — поднять в приоритет: ` + stuck.slice(0, 6).map(x => `«${x.b.title}»`).join(', '));
    if (noisy.length) plan.push(`🔇 ${noisy.length} пунктов «проверить глазами» повторяются неделями — Claude смотрит скриншоты: если это не баг, добавить в исключения бота (меньше шума): ` + noisy.slice(0, 5).map(x => `«${x.b.title}»`).join(', '));
    const prev = hist[hist.length - 2].j, cur = hist[hist.length - 1].j;
    const ps = new Set(prev.bugs.map(b => b.sig)), cs = new Set(cur.bugs.map(b => b.sig));
    row.qa_new = [...cs].filter(s => !ps.has(s)).length; row.qa_fixed = [...ps].filter(s => !cs.has(s)).length;
  }

  // ---------- speed bot
  const sp = json('speed/latest.json'); const spAge = sp ? ageDays(sp.date) : null;
  if (sp) {
    const ok = Object.entries(sp.results).filter(([, r]) => !r.error);
    const mob = ok.filter(([k]) => k.endsWith('|mobile')).map(([, r]) => r.score);
    row.speed_mobile_avg = mob.length ? Math.round(mob.reduce((a, b) => a + b, 0) / mob.length) : '';
    health.push(`| Скорость | ${fmtAge(spAge)} | Google PageSpeed | ${ok.length} замеров | телефон в среднем ${row.speed_mobile_avg || '—'} / 100${(sp.warn || []).length ? `, ⚠️ ${sp.warn.length}` : ''} |`);
    if (ok.length < Object.keys(sp.results).length) plan.push('🔑 Скорость: часть замеров не прошла (лимит Google) — добавить бесплатный ключ PSI_KEY (docs/bots.md).');
    if (row.speed_mobile_avg && row.speed_mobile_avg < 70) plan.push(`🐢 Скорость на телефоне ${row.speed_mobile_avg}/100 — ниже цели 90: следующий шаг ТЗ по скорости (картинки WebP, шрифт у себя, кэш).`);
  } else health.push('| Скорость | никогда | — | — | — |');
  if (spAge == null || spAge > 8) plan.push('🚨 Бот скорости не запускался больше недели.');

  // ---------- site screenshots
  const sc = read('checks/latest/report.md'); const scDate = (sc.match(/# Site check (\S+)/) || [])[1];
  health.push(`| Снимки сайта | ${fmtAge(ageDays(scDate))} | гость/клиент/админ | ${(sc.match(/→ HTTP/g) || []).length} страниц | ${(sc.match(/⚠|❌/g) || []).length} предупреждений |`);

  // ---------- growth bots (weekly report)
  const wk = json('reports/weekly/metrics.json'); const wkMd = read('reports/weekly/latest.md');
  if (wk) {
    Object.assign(row, wk.metrics);
    const wait = (wkMd.match(/⏸[^\n]*/g) || []); const errs = (wkMd.match(/⚠️ [^\n]*(не отвечает|ошибк|не загрузил|не удалось)[^\n]*/g) || []);
    health.push(`| Рост (SEO, продажи, товары, конкуренты, акции, безопасность) | ${fmtAge(ageDays(wk.date))} | ${wk.ran.length} ботов | ⏸ ждут доступа: ${wait.length} | ошибок: ${errs.length} |`);
    wait.forEach(w => plan.push('🔑 ' + w.replace(/^⏸\s*/, '')));
    errs.slice(0, 5).forEach(e => plan.push('🛠 ' + e));
  } else { health.push('| Рост (SEO, продажи, …) | никогда | — | — | — |'); plan.push('🚨 Боты роста не запускались.'); }

  // ---------- trends from history
  const csv = path.join(OUT, 'history.csv');
  const cols = ['date', 'qa_critical', 'qa_major', 'qa_minor', 'qa_check', 'qa_new', 'qa_fixed', 'qa_stuck', 'qa_pages', 'qa_clicks', 'qa_sounds', 'qa_links', 'qa_notchecked', 'speed_mobile_avg', 'seo_clicks', 'seo_impressions', 'seo_position', 'visitors', 'add_to_carts', 'orders', 'revenue', 'products', 'products_with_issues', 'plugins_outdated', 'plugins_vulnerable'];
  const prevRows = read(csv).trim().split('\n').slice(1).filter(Boolean).map(l => { const v = l.split(','); return Object.fromEntries(cols.map((c, i) => [c, v[i]])); });
  const before = prevRows[prevRows.length - 1];
  const trend = (k, good) => { if (!before || before[k] === '' || before[k] == null || row[k] == null || row[k] === '') return ''; const d = +row[k] - +before[k]; if (!d) return '='; return (d > 0 ? '+' : '') + d + ((good === 'up' ? d > 0 : d < 0) ? ' ✅' : ' ⚠️'); };
  if (!fs.existsSync(csv)) fs.writeFileSync(csv, cols.join(',') + '\n');
  fs.appendFileSync(csv, cols.map(c => (row[c] == null ? '' : row[c])).join(',') + '\n');
  if (before && +row.qa_critical > +before.qa_critical) plan.push('🔴 Критичных багов стало больше, чем неделю назад — разобрать первыми.');
  if (before && row.seo_clicks != null && +row.seo_clicks < +before.seo_clicks * 0.8) plan.push('📉 Переходов из Google на 20%+ меньше, чем неделю назад — проверить, что изменилось (страницы, выпадение из индекса).');

  // ---------- report
  const L = [`# Главный робот — отчёт ${date}`, '',
    'Раз в неделю: запускает всех ботов → проверяет, что каждый отработал → ведёт статистику по неделям → пишет план развития. План читает еженедельная сессия Claude и улучшает ботов.', '',
    '## Состояние ботов', '', '| Бот | Последний запуск | Режим | Охват | Результат |', '|---|---|---|---|---|', ...health, '',
    '## Динамика за неделю', '',
    '| Показатель | Сейчас | Изменение |', '|---|---|---|',
    ...[['Критичные баги', 'qa_critical', 'down'], ['Важные баги', 'qa_major', 'down'], ['Новых багов', 'qa_new', 'down'], ['Исправлено багов', 'qa_fixed', 'up'], ['Висят 3+ проверки подряд', 'qa_stuck', 'down'], ['Скорость (телефон, из 100)', 'speed_mobile_avg', 'up'], ['Переходы из Google', 'seo_clicks', 'up'], ['Показы в Google', 'seo_impressions', 'up'], ['Посетители', 'visitors', 'up'], ['Заказы', 'orders', 'up'], ['Выручка', 'revenue', 'up'], ['Товары с замечаниями', 'products_with_issues', 'down'], ['Устаревшие плагины', 'plugins_outdated', 'down']]
      .filter(([, k]) => row[k] != null && row[k] !== '').map(([n, k, g]) => `| ${n} | ${row[k]} | ${trend(k, g) || 'первый замер'} |`), '',
    '## План развития ботов и сайта', '', ...(plan.length ? [...new Set(plan)].map((p, i) => `${i + 1}. ${p}`) : ['Всё работает, замечаний нет. ✅']), '',
    'Отчёты ботов: баги — `qa/latest/report.md`, скорость — `speed/latest.md`, рост — `reports/weekly/latest.md`, снимки — `checks/latest/report.md`. История — `reports/bots-health/history.csv`.'];
  const md = L.join('\n') + '\n';
  fs.writeFileSync(path.join(OUT, 'latest.md'), md);
  fs.writeFileSync(path.join(OUT, 'history', date + '.md'), md);
  console.log(md);
})();
