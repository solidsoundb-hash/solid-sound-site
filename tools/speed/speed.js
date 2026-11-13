// Speed bot: measures key pages with Google PageSpeed Insights (real Lighthouse, run on Google's servers — the
// hosting's anti-bot page does not get in the way) on phone and computer, compares with the previous run and
// warns when the site got slower. Free; an optional PSI_KEY (Google Cloud API key, free) lifts Google's quota.
//   node tools/speed/speed.js            -> speed/latest.md, speed/latest.json, speed/history.csv
const fs = require('fs');
const path = require('path');

const OUT = process.env.SPEED_OUT || 'speed';
const PAGES = (process.env.SPEED_PAGES || '/en/,/en/all-products/,/product/infinity/,/en/wiki/').split(',');
const BASE = 'https://solid-sound.net';
const KEY = process.env.PSI_KEY || '';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const T0 = Date.now(); const BUDGET = (+(process.env.SPEED_BUDGET_MIN || 14)) * 60000; // stop measuring in time, save what we have

async function psi(url, strategy) {
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
  for (const p of PAGES) for (const s of ['mobile', 'desktop']) {
    const k = p + '|' + s; const t = Date.now(); results[k] = await psi(BASE + p, s);
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
  const L = [`# Скорость сайта — ${date.slice(0, 16).replace('T', ' ')} UTC`, '',
    'Google PageSpeed (Lighthouse). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.', ''];
  L.push(warn.length ? '## ⚠️ Стало хуже / проблемы\n\n' + warn.map(w => '- ' + w).join('\n') + '\n' : '## ✅ Скорость не ухудшилась\n');
  L.push('| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов |', '|---|---|---|---|---|---|---|---|---|');
  for (const [k, r] of Object.entries(results)) {
    const [p, s] = k.split('|'); const o = prev[k];
    const d = (o && !o.error && !r.error) ? ` (${r.score - o.score >= 0 ? '+' : ''}${r.score - o.score})` : '';
    L.push(r.error ? `| ${p} | ${s === 'mobile' ? 'телефон' : 'компьютер'} | не измерено: ${r.error.replace(/\|/g, '/')} |||||||` :
      `| ${p} | ${s === 'mobile' ? 'телефон' : 'компьютер'} | **${r.score}**${d} | ${sec(r.lcp)} | ${sec(r.fcp)} | ${r.tbt} мс | ${r.cls} | ${r.kb} КБ | ${r.requests} |`);
  }
  fs.writeFileSync(path.join(OUT, 'latest.md'), L.join('\n') + '\n');
  fs.writeFileSync(path.join(OUT, 'latest.json'), JSON.stringify({ date, results, warn }, null, 1));
  const csv = path.join(OUT, 'history.csv');
  if (!fs.existsSync(csv)) fs.writeFileSync(csv, 'date,page,device,score,lcp_ms,fcp_ms,tbt_ms,cls,kb,requests\n');
  fs.appendFileSync(csv, Object.entries(results).filter(([, r]) => !r.error).map(([k, r]) => { const [p, s] = k.split('|'); return [date, p, s, r.score, r.lcp, r.fcp, r.tbt, r.cls, r.kb, r.requests].join(','); }).join('\n') + '\n');
  console.log(L.slice(0, 8).join('\n'));
})();
