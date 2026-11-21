// SITE BOTS runner (owner 21.11.2026: «делай всех»). One report for the owner and for Claude:
//   reports/sitebots/latest.md  (+ one file per bot, metrics.json, history.csv, dated copy in history/)
//   node tools/sitebots/run.js                    all bots
//   SITEBOTS=code,prices node tools/sitebots/run.js   only these
// Bots: code (dead code), buyer (purchase path), prices, seo (pages for Google), a11y (accessibility), texts (typos),
// emails (mail settings + Brevo), websec (security from outside), catalog (products complete), files (server, needs FTP).
// The watchman (every 15 min) has its own workflow: .github/workflows/watch.yml.
'use strict';
const fs = require('fs'); const path = require('path'); const { execFileSync } = require('child_process');
const OUT = process.env.SITEBOTS_OUT || 'reports/sitebots';
const BOTS = {
  code: ['🧹 Мёртвый код', () => require('./code')], buyer: ['🛒 Покупатель', () => require('./buyer')], prices: ['💶 Цены', () => require('./prices')],
  catalog: ['🎛 Товары', () => require('./catalog')], seo: ['🔎 SEO страниц', () => require('./seo-pages')], texts: ['✍️ Тексты', () => require('./texts')],
  a11y: ['♿ Доступность', () => require('./a11y')], emails: ['✉️ Письма', () => require('./emails')], websec: ['🛡 Безопасность', () => require('./websec')], cookies: ['🍪 Cookie/GDPR', () => require('./cookies')], health: ['🩺 Здоровье', () => require('./health')], forms: ['📝 Формы', () => require('./forms')],
  files: ['🗄 Сервер', () => async () => {
    if (!process.env.FTP_HOST) return { md: '## 🗄 Сервер\n\n⏸ Нет доступа FTP (секреты FTP_HOST/USER/PASS).\n', metrics: {}, alerts: [] };
    let err = ''; let md = '';
    try { md = execFileSync('python3', [path.join(__dirname, 'server_files.py')], { encoding: 'utf8', maxBuffer: 64 << 20, timeout: 32 * 60000, stdio: ['ignore', 'pipe', 'pipe'] }); }
    catch (e) { md = String(e.stdout || ''); err = String(e.stderr || e.message).split('\n').filter(Boolean).slice(-3).join(' '); }
    let metrics = {}; try { metrics = JSON.parse(fs.readFileSync(path.join(OUT, '.files-metrics.json'), 'utf8')); } catch (e) {}
    return { md: md || '## 🗄 Сервер\n\n🔴 Робот упал: ' + err + '\n', metrics, alerts: metrics.srv_risky ? [`Сервер: ${metrics.srv_risky} опасных файлов открыто из интернета`] : [] };
  }],
};
(async () => {
  fs.mkdirSync(path.join(OUT, 'history'), { recursive: true });
  const want = (process.env.SITEBOTS || 'all').split(/[\s,]+/).filter(Boolean); const names = want.includes('all') ? Object.keys(BOTS) : want.filter(n => BOTS[n]);
  const res = [];
  for (const n of names) {
    const t0 = Date.now(); let r;
    try { r = await BOTS[n][1]()(); } catch (e) { r = { md: `## ${BOTS[n][0]}\n\n🔴 Робот упал: \`${String(e.message).slice(0, 200)}\`\n`, metrics: {}, alerts: [], crashed: true }; }
    r.sec = Math.round((Date.now() - t0) / 1000); res.push([n, r]); fs.writeFileSync(path.join(OUT, n + '.md'), r.md);
    console.log(`${n}: ${r.sec} с, ${(r.alerts || []).length} тревог`);
  }
  const at = new Date().toISOString().replace('T', ' ').slice(0, 16);
  const alerts = res.flatMap(([, r]) => r.alerts || []);
  // keep the reports of bots that did not run this time (one file = latest of each bot)
  const parts = Object.keys(BOTS).map(n => { const r = res.find(x => x[0] === n); if (r) return r[1].md; try { return fs.readFileSync(path.join(OUT, n + '.md'), 'utf8').replace(/^(## .*)$/m, '$1 _(прошлый запуск)_'); } catch (e) { return ''; } }).filter(Boolean);
  let md = `# Роботы-аналитики сайта — ${at} UTC\n\nЗапущены: ${res.map(([n, r]) => `${BOTS[n][0]} (${r.sec} с${r.crashed ? ', упал' : ''})`).join(', ')}.\n\n`;
  md += alerts.length ? '## ⚠️ Главное\n\n' + alerts.map(a => '- ' + a).join('\n') + '\n\n' : '## ✅ Серьёзных проблем нет\n\n';
  md += 'Для Claude: 🔴 — чинить сразу, 🟠 — в ближайшей правке, 🟡/⚪ — по возможности. Правило «скрины до и после» действует.\n\n---\n\n' + parts.join('\n---\n\n');
  fs.writeFileSync(path.join(OUT, 'latest.md'), md); fs.writeFileSync(path.join(OUT, 'history', at.slice(0, 10) + '.md'), md);
  let prev = {}; try { prev = JSON.parse(fs.readFileSync(path.join(OUT, 'metrics.json'), 'utf8')); } catch (e) {}
  const m = Object.assign(prev, ...res.map(([, r]) => r.metrics || {}), { at }); fs.writeFileSync(path.join(OUT, 'metrics.json'), JSON.stringify(m, null, 1));
  const hf = path.join(OUT, 'history.csv'); const keys = Object.keys(m).filter(k => k !== 'at').sort();
  if (!fs.existsSync(hf)) fs.writeFileSync(hf, 'at,' + keys.join(',') + '\n');
  const head = fs.readFileSync(hf, 'utf8').split('\n')[0].split(',').slice(1); fs.appendFileSync(hf, at + ',' + head.map(k => m[k] ?? '').join(',') + '\n');
})();
