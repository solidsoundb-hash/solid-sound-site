// Clicker report (owner 20.11.2026: «роботы по команде проклацывают все кнопки, делают скрины, проверяют куда ведут линки —
// они могут быть перепутаны — на ПК, планшете и телефоне»).
// Input: <DIR>/**/index-*.json written by tools/photo/photo.js (one per device × part). Every distinct link is opened once
// (slowly — the hosting blocks fast robots) and compared with its text. Output: <DIR>/report.md (Russian).
const fs = require('fs'); const path = require('path');
const DIR = process.env.CLICK_DIR || 'click'; const BASE = 'https://solid-sound.net';
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const files = []; (function walk(d) { for (const f of fs.readdirSync(d)) { const p = path.join(d, f); if (fs.statSync(p).isDirectory()) walk(p); else if (/^index-.*\.json$/.test(f)) files.push(p); } })(DIR);
const recs = files.flatMap(f => { try { return JSON.parse(fs.readFileSync(f, 'utf8')).map(r => ({ ...r, dir: path.relative(DIR, path.dirname(f)) })); } catch (e) { return []; } });
const STOP = new Set('the a an to of and or for with on in at by from your our my you we us is are be this that it its get open see view more now here go click read learn show all new free top best page about how what why when where who join try start →'.split(' '));
const words = (s) => String(s || '').toLowerCase().normalize('NFKD').replace(/[^\p{L}\p{N}\s-]/gu, ' ').split(/[\s-]+/).filter(w => w.length > 2 && !STOP.has(w) && !/^\d+$/.test(w));
const stem = (w) => w.slice(0, 5);
const dec = (s) => String(s || '').replace(/&#8211;|&ndash;/g, '–').replace(/&amp;/g, '&').replace(/&#0?39;|&rsquo;/g, "'").replace(/&quot;/g, '"').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
(async () => {
  const links = new Map(); // href -> [{page, dev, text, where}]
  for (const r of recs) for (const l of (r.links || [])) { if (!l.vis) continue; if (!links.has(l.href)) links.set(l.href, []); links.get(l.href).push({ page: r.url.replace(BASE, ''), dev: r.dev, text: l.text, where: l.where }); }
  const dest = new Map(); let i = 0;
  for (const href of links.keys()) {
    if (/\/wp-admin|logout|add-to-cart=|\/cart\/?\?|wc-ajax/.test(href)) continue;
    const own = href.startsWith(BASE);
    try {
      const r = await fetch(href, { headers: { 'User-Agent': UA, Accept: 'text/html' }, redirect: 'follow', signal: AbortSignal.timeout(40000) });
      const t = own ? await r.text() : '';
      dest.set(href, { code: r.status, final: r.url, title: dec((t.match(/<title>([\s\S]*?)<\/title>/i) || [])[1]), h1: dec((t.match(/<h1[^>]*>([\s\S]*?)<\/h1>/i) || [])[1]).slice(0, 90) });
    } catch (e) { dest.set(href, { code: 0, err: String(e.message || e).slice(0, 60) }); }
    if (own || ++i % 5 === 0) await sleep(1200);
  }
  const broken = [], odd = [];
  for (const [href, uses] of links) {
    const d = dest.get(href); if (!d) continue;
    const u = uses[0], where = [...new Set(uses.map(x => x.page))].slice(0, 3).join(', ') + (uses.length > 3 ? ' …' : '');
    if (d.code >= 400 || d.code === 0) { broken.push(`| ${d.code || 'нет ответа'} | ${href.replace(BASE, '')} | «${u.text}» | ${where} |`); continue; }
    if (!href.startsWith(BASE)) continue;
    const target = words(d.title + ' ' + d.h1 + ' ' + decodeURIComponent(new URL(d.final || href).pathname)).map(stem);
    for (const x of uses) {
      const lw = words(x.text); if (!lw.length || lw.length > 7) continue;
      if (!lw.some(w => target.includes(stem(w)))) { odd.push(`| «${x.text}» (${x.where}, ${x.dev}) | ${x.page} | ${href.replace(BASE, '')} → «${d.h1 || d.title}» |`); break; }
    }
  }
  /* buttons: does what opened match the button's words? (same word test as links; synonyms below) */
  const SYN = { price: ['cost', 'pric', 'calcu', 'quote'], cart: ['buy', 'order', 'basket', 'check'], mix: ['mixin', 'mixed'], login: ['sign', 'accou', 'log'], menu: ['secti', 'categ'], search: ['find', 'searc'] };
  const near = (lw, target) => lw.some(w => { const s = stem(w); if (target.includes(s)) return true; for (const [k, v] of Object.entries(SYN)) if (s.startsWith(k.slice(0, 4)) || v.includes(s)) { if ([k.slice(0, 5), ...v].some(x => target.includes(x))) return true; } return false; });
  const wrongBtn = [];
  for (const r of recs) for (const c of (r.clicks || [])) {
    if (!c.opened) continue; const lw = words(c.text); if (!lw.length || lw.length > 6) continue;
    if (!near(lw, words(c.opened).map(stem))) wrongBtn.push(`| «${c.text}» | ${r.url.replace(BASE, '')} (${r.dev}) | «${c.opened}» | [снимок](${r.dir}/${slugDir(r)}/${c.shot}) |`);
  }
  const dead = [], errs = [];
  for (const r of recs) for (const c of (r.clicks || [])) {
    const at = `${r.url.replace(BASE, '')} (${r.dev})`, shot = `[снимок](${r.dir}/${slugDir(r)}/${c.shot})`;
    if (c.effect === 'НИЧЕГО НЕ ПРОИЗОШЛО') dead.push(`| «${c.text}» | ${at} | ${shot} |`);
    if (c.err) errs.push(`| «${c.text}» | ${at} | ${c.err.replace(/\|/g, '/')} | ${shot} |`);
  }
  function slugDir(r) { const k = r.dev === 'компьютер' ? 'pc' : r.dev === 'телефон' ? 'phone' : 'tablet'; return k + '/' + ((new URL(r.url).pathname + new URL(r.url).search).replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home'); }
  const devs = [...new Set(recs.map(r => r.dev))], pages = new Set(recs.map(r => r.url)).size, clicks = recs.reduce((a, r) => a + (r.clicks || []).length, 0);
  const md = [`# Кликер — ${new Date().toISOString().slice(0, 16).replace('T', ' ')} UTC`, '',
    `Устройства: ${devs.join(', ')} · страниц: ${pages} · нажатий: ${clicks} · разных ссылок проверено: ${dest.size}`, '',
    `## ❌ Битые ссылки (${broken.length})`, '', '| Код | Ссылка | Текст | Где |', '|---|---|---|---|', ...broken.slice(0, 200), '',
    `## ⚠️ Ссылка, возможно, перепутана: текст не совпадает со страницей (${odd.length})`, '', 'Проверить глазами: робот сравнивает слова ссылки с заголовком страницы, куда она ведёт.', '', '| Текст ссылки | На странице | Ведёт на → заголовок там |', '|---|---|---|', ...odd.slice(0, 300), '',
    `## ⚠️ Кнопка открыла что-то другое по смыслу (${wrongBtn.length})`, '', 'Робот сравнил слова кнопки с заголовком того, что открылось. Проверить глазами по снимку.', '', '| Кнопка | Где | Открылось | |', '|---|---|---|---|', ...wrongBtn.slice(0, 300), '',
    `## 🔇 Кнопка нажата — ничего не произошло (${dead.length})`, '', '| Кнопка | Где | |', '|---|---|---|', ...dead.slice(0, 300), '',
    `## 🐞 Ошибка в коде после нажатия (${errs.length})`, '', '| Кнопка | Где | Ошибка | |', '|---|---|---|---|', ...errs.slice(0, 200), '',
    '## Все снимки', '', ...recs.map(r => `- ${r.dev} · ${r.url.replace(BASE, '')}${r.err ? ' · ⚠ ' + r.err : ''}: ` + (r.shots || []).slice(0, 60).map(s => `[${s[0].slice(0, 30)}](${r.dir}/${slugDir(r)}/${s[1]})`).join(' · '))];
  fs.writeFileSync(path.join(DIR, 'report.md'), md.join('\n') + '\n'); console.log(md.slice(0, 40).join('\n'));
})();
