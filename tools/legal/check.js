// Еженедельная юр. проверка сайта (роль «Юрист», 25.11.2026). Только чтение. Отчёт: reports/legal/check.md, память: reports/legal/memory.json.
// Ступень 1: LEGAL_ONLY=terms — одна страница. Запуск: строка в .github/legal.txt или по расписанию (.github/workflows/legal-weekly.yml).
'use strict';
const fs = require('fs'); const rules = require('./rules');
const BASE = 'https://solid-sound.net', UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundBots/1.0';
const PAGES = { terms: '/en/terms/', refund: '/en/refund_returns/', license: '/en/license/', creator: '/en/creator-terms/', notice: '/en/legal-notice/', privacy: '/en/privacy-policy/', cancel: '/en/cancel-order/' };
const text = h => h.replace(/<(script|style|noscript)[\s\S]*?<\/\1>/gi, ' ').replace(/<[^>]+>/g, ' ').replace(/&nbsp;|&#160;/g, ' ').replace(/&#8217;|&rsquo;/g, "'").replace(/&#8220;|&#8221;/g, '"').replace(/&amp;/g, '&').replace(/\s+/g, ' ');
const sleep = ms => new Promise(r => setTimeout(r, ms));
async function get(path) { for (let i = 0; i < 3; i++) { try { const r = await fetch(BASE + path, { headers: { 'user-agent': UA }, redirect: 'follow' }); const html = await r.text();
  if (r.status === 429 || r.status === 503) { await sleep(8000 * (i + 1)); continue; } return { status: r.status, html, text: text(html) }; } catch (e) { await sleep(5000); } } return { status: 0, html: '', text: '' }; }
(async () => {
  const only = (process.env.LEGAL_ONLY || '').split(',').filter(Boolean); const t0 = Date.now(); const pages = {};
  for (const [k, p] of Object.entries(PAGES)) { if (only.length && !only.includes(k)) continue; pages[k] = await get(p); await sleep(1500); }
  pages.checkbox = ['src', 'build'].every(d => { try { return fs.readFileSync(`plugin/solid-sound-custom/${d}/custom.php`, 'utf8').includes('lose my 14-day right of withdrawal'); } catch (e) { return false; } });
  let res = rules(pages); if (only.length) res = res.filter(r => ['eur', 'seller', 'withdrawal', 'mediator', 'checkbox'].includes(r.id) || only.some(o => r.id.includes(o)));
  fs.mkdirSync('reports/legal', { recursive: true });
  const MEMF = 'reports/legal/memory.json'; let mem = {}; try { mem = JSON.parse(fs.readFileSync(MEMF, 'utf8')); } catch (e) {}
  const prev = mem.last || {}; const icon = { ok: '✅', warn: '🟠', bad: '🔴' };
  const lines = res.map(r => `| ${icon[r.level]} | ${r.msg}${prev[r.id] && prev[r.id] !== r.level ? ` _(было ${icon[prev[r.id]]})_` : ''} | ${r.id} |`);
  const day = new Date().toISOString().slice(0, 10); const n = l => res.filter(r => r.level === l).length;
  fs.writeFileSync('reports/legal/check.md', `# Юр. проверка сайта — ${day}\n\n🔴 ${n('bad')} · 🟠 ${n('warn')} · ✅ ${n('ok')} · ${Math.round((Date.now() - t0) / 1000)} с${only.length ? ' · только: ' + only.join(',') : ''}\n\n| | Что | Правило |\n|---|---|---|\n${lines.join('\n')}\n\nСтраницы: ${Object.entries(PAGES).map(([k, p]) => `${k} ${pages[k] ? pages[k].status : '—'}`).join(' · ')}\n`);
  mem.last = Object.fromEntries(res.map(r => [r.id, r.level])); mem.runs = (mem.runs || []).concat({ day, bad: n('bad'), warn: n('warn'), ok: n('ok'), only: only.join(',') }).slice(-20);
  mem.noise = mem.noise || []; fs.writeFileSync(MEMF, JSON.stringify(mem, null, 1));
  console.log(fs.readFileSync('reports/legal/check.md', 'utf8'));
})();
