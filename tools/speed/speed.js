// Speed bot: measures key pages with Google PageSpeed Insights (real Lighthouse, run on Google's servers — the
// hosting's anti-bot page does not get in the way) on phone and computer, compares with the previous run and
// warns when the site got slower. Free; an optional PSI_KEY (Google Cloud API key, free) lifts Google's quota.
//   node tools/speed/speed.js            -> speed/latest.md, speed/latest.json, speed/history.csv
const fs = require('fs');
const path = require('path');

const OUT = process.env.SPEED_OUT || 'speed';
const PAGES = (process.env.SPEED_PAGES || '/en/,/en/all-products/,/product/infinity/,/en/studio-calculator/,/en/wiki/').split(',');
const BASE = 'https://solid-sound.net';
const KEY = process.env.PSI_KEY || '';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const T0 = Date.now(); const BUDGET = (+(process.env.SPEED_BUDGET_MIN || 14)) * 60000; // stop measuring in time, save what we have

// No PSI_KEY (19.11.2026): Lighthouse runs right here on the GitHub robot (same Lighthouse as PageSpeed, own Chrome).
// (20.11) WHY it is slow — kept in latest.json and listed in latest.md: heaviest files, scripts that keep the processor busy,
// the main block (LCP element), and the biggest savings Lighthouse sees
function why(a) {
  const it = (k) => ((a[k] && a[k].details && a[k].details.items) || []);
  const short = (u) => String(u || '').replace(/^https?:\/\/(www\.)?solid-sound\.net/, '').slice(0, 90);
  const lcpEl = it('largest-contentful-paint-element')[0]; let lcp = '';
  try { const x = lcpEl && (lcpEl.items ? lcpEl.items[0] : lcpEl); lcp = (x && x.node && (x.node.snippet || x.node.selector)) || ''; } catch (e) {}
  return {
    heavy: it('total-byte-weight').slice(0, 8).map(x => short(x.url) + ' ' + Math.round((x.totalBytes || 0) / 1024) + 'KB'),
    cpu: it('bootup-time').slice(0, 6).map(x => short(x.url) + ' ' + Math.round(x.total || 0) + 'ms'),
    lcp: String(lcp).slice(0, 160),
    save: Object.values(a).filter(x => x.details && x.details.type === 'opportunity' && (x.numericValue || 0) > 150).sort((p, q) => q.numericValue - p.numericValue).slice(0, 6).map(x => x.title + ' −' + Math.round(x.numericValue) + 'ms'),
  };
}
async function local(url, strategy) {
  try {
    const lh = (await import('lighthouse')).default; const cl = await import('chrome-launcher');
    const chrome = await cl.launch({ chromePath: process.env.CHROME_PATH || '/usr/bin/google-chrome', chromeFlags: ['--headless=new', '--no-sandbox'] });
    try {
      const cfg = strategy === 'desktop' ? (await import('lighthouse/core/config/desktop-config.js')).default : undefined;
      const r = await lh(url, { port: chrome.port, onlyCategories: ['performance'], output: 'json', logLevel: 'error' }, cfg);
      const L = r.lhr; const a = L.audits; const n = (k) => (a[k] && a[k].numericValue) || 0;
      if (L.runtimeError) return { error: L.runtimeError.message.slice(0, 200) };
      return { score: Math.round((L.categories.performance.score || 0) * 100), lcp: Math.round(n('largest-contentful-paint')), fcp: Math.round(n('first-contentful-paint')),
        tbt: Math.round(n('total-blocking-time')), cls: +(n('cumulative-layout-shift')).toFixed(3), kb: Math.round(n('total-byte-weight') / 1024),
        requests: ((a['network-requests'] && a['network-requests'].details && a['network-requests'].details.items) || []).length, ttfb: Math.round(n('server-response-time')), field: 'lighthouse на роботе', why: why(a) };
    } finally { await chrome.kill(); }
  } catch (e) { return { error: 'lighthouse: ' + String(e.message || e).slice(0, 180) }; }
}
async function psi(url, strategy) {
  if (!KEY || process.env.SPEED_LOCAL === '1') return local(url, strategy);
  const q = new URLSearchParams({ url, strategy, category: 'performance' });
  if (KEY) q.set('key', KEY);
  let last = '';
  for (let i = 0; i < 2; i++) {
    if (Date.now() - T0 > BUDGET) return { error: 'не хватило времени (Google отвечал медленно)' };
    try {
      const r = await fetch('https://www.googleapis.com/pagespeedonline/v5/runPagespeed?' + q, { signal: AbortSignal.timeout(90000) });
      const j = await r.json();
      if (r.ok && j.lighthouseResult) {
        const a = j.lighthouseResult.audits; const n = (k) => (a[k] && a[k].numericValue) || 0;
        const items = (a['network-requests'] && a['network-requests'].details && a['network-requests'].details.items) || [];
        return {
          score: Math.round((j.lighthouseResult.categories.performance.score || 0) * 100),
          lcp: Math.round(n('largest-contentful-paint')), fcp: Math.round(n('first-contentful-paint')),
          tbt: Math.round(n('total-blocking-time')), cls: +(n('cumulative-layout-shift')).toFixed(3),
          kb: Math.round(n('total-byte-weight') / 1024), requests: items.length,
          field: (j.loadingExperience && j.loadingExperience.overall_category) || '',
        };
      }
      last = (j.error && j.error.message) || ('HTTP ' + r.status);
      if (r.status === 429) return { error: 'лимит Google без ключа — добавьте бесплатный PSI_KEY (docs/bots.md)' };
    } catch (e) { last = e.message; }
    await sleep(10000);
  }
  return { error: last.slice(0, 200) };
}

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  let prev = {}; try { prev = JSON.parse(fs.readFileSync(path.join(OUT, 'latest.json'), 'utf8')).results || {}; } catch (e) {}
  const results = {}; const warn = []; const date = new Date().toISOString();
  /* (25.11 Developer robot «after deploy»): SPEED_DEVICES=mobile, SPEED_RUNS=3 → median of 3 runs by score (one run swings ±15) */
  const DEVS = (process.env.SPEED_DEVICES || 'mobile,desktop').split(','); const RUNS = Math.max(1, +(process.env.SPEED_RUNS || 1));
  for (const p of PAGES) for (const s of DEVS) {
    const k = p + '|' + s; const t = Date.now();
    const runs = []; for (let i = 0; i < RUNS; i++) { const x = await psi(BASE + p, s); if (!x.error) runs.push(x); else if (!runs.length && i === RUNS - 1) runs.push(x); }
    runs.sort((a, b) => (a.score || 0) - (b.score || 0)); results[k] = runs[Math.floor((runs.length - 1) / 2)] || { error: 'нет результата' };
    console.log(k, results[k].error ? 'ERROR ' + results[k].error : 'score ' + results[k].score, Math.round((Date.now() - t) / 1000) + 's');
    const r = results[k], o = prev[k];
    if (r.error) continue;
    if (o && !o.error) {
      if (r.score <= o.score - 5) warn.push(`${p} (${s === 'mobile' ? 'телефон' : 'компьютер'}): оценка упала ${o.score} → ${r.score}`);
      if (r.lcp > o.lcp * 1.2 && r.lcp - o.lcp > 400) warn.push(`${p} (${s === 'mobile' ? 'телефон' : 'компьютер'}): главный блок появляется позже ${(o.lcp / 1000).toFixed(1)} → ${(r.lcp / 1000).toFixed(1)} с`);
      if (r.kb > o.kb * 1.2 && r.kb - o.kb > 150) warn.push(`${p} (${s === 'mobile' ? 'телефон' : 'компьютер'}): страница стала тяжелее ${o.kb} → ${r.kb} КБ`);
    }
    if (r.cls > 0.1) warn.push(`${p} (${s === 'mobile' ? 'телефон' : 'компьютер'}): страница «прыгает» при загрузке (CLS ${r.cls}, норма до 0.1)`);
  }
  const sec = (ms) => (ms / 1000).toFixed(1) + ' с';
  const L = [`# Скорость сайта — ${date.slice(0, 16).replace('T', ' ')} UTC`, '', ...(process.env.SPEED_NOTE ? ['После выкладки: ' + process.env.SPEED_NOTE + (RUNS > 1 ? ` (медиана ${RUNS} замеров)` : ''), ''] : []),
    (KEY ? 'Google PageSpeed (Lighthouse).' : 'Lighthouse на роботе GitHub (ключа PageSpeed нет).') + ' Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.', ''];
  const measured = Object.values(results).filter(r => !r.error).length;
  L.push(!measured ? '## ⏸ Ничего не измерено — см. причину в таблице\n' : warn.length ? '## ⚠️ Стало хуже / проблемы\n\n' + warn.map(w => '- ' + w).join('\n') + '\n' : '## ✅ Скорость не ухудшилась\n');
  L.push('| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |', '|---|---|---|---|---|---|---|---|---|---|');
  for (const [k, r] of Object.entries(results)) {
    const [p, s] = k.split('|'); const o = prev[k];
    const d = (o && !o.error && !r.error) ? ` (${r.score - o.score >= 0 ? '+' : ''}${r.score - o.score})` : '';
    L.push(r.error ? `| ${p} | ${s === 'mobile' ? 'телефон' : 'компьютер'} | не измерено: ${r.error.replace(/\|/g, '/')} |||||||` :
      `| ${p} | ${s === 'mobile' ? 'телефон' : 'компьютер'} | **${r.score}**${d} | ${sec(r.lcp)} | ${sec(r.fcp)} | ${r.tbt} мс | ${r.cls} | ${r.kb} КБ | ${r.requests} | ${r.ttfb != null ? sec(r.ttfb) : '—'} |`);
  }
  L.push('', '## Почему медленно (Lighthouse)');
  for (const [k, v] of Object.entries(results)) if (v && v.why) { L.push('', '### ' + k.replace('|', ' — ')); if (v.why.lcp) L.push('- главный блок: `' + v.why.lcp.replace(/`/g, "'") + '`'); if (v.why.save.length) L.push('- можно выиграть: ' + v.why.save.join(' · ')); L.push('- тяжёлые файлы: ' + v.why.heavy.join(' · ')); L.push('- скрипты грузят процессор: ' + v.why.cpu.join(' · ')); }
  fs.writeFileSync(path.join(OUT, 'latest.md'), L.join('\n') + '\n');
  fs.writeFileSync(path.join(OUT, 'latest.json'), JSON.stringify({ date, results, warn }, null, 1));
  const csv = path.join(OUT, 'history.csv');
  if (!fs.existsSync(csv)) fs.writeFileSync(csv, 'date,page,device,score,lcp_ms,fcp_ms,tbt_ms,cls,kb,requests\n');
  fs.appendFileSync(csv, Object.entries(results).filter(([, r]) => !r.error).map(([k, r]) => { const [p, s] = k.split('|'); return [date, p, s, r.score, r.lcp, r.fcp, r.tbt, r.cls, r.kb, r.requests].join(','); }).join('\n') + '\n');
  console.log(L.slice(0, 8).join('\n'));
})();
