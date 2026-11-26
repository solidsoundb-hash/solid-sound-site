// SEO weekly: how Google's snippet looks on the KEY selling pages in every language (role «SEO», 25.11.2026).
// The big crawl (all pages, broken links, sitemap, orphans) is tools/sitebots/seo-pages.js (Sunday) — this robot adds:
//  1. per key page × language: title / description / H1 / canonical / hreflang / structured data (Product brand, price);
//  2. «не переведено»: a /de/ /fr/ … page whose title or description is the same as the English one (found 25.11: /de/services-prices/);
//  3. what changed since last week (memory: reports/seo/memory.json).
// Read-only, no secrets. Run: a line in .github/seo.txt, or Monday by schedule (.github/workflows/seo-weekly.yml).
'use strict';
const fs = require('fs');
const BASE = 'https://solid-sound.net';
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundBots/1.0';
const OUT = 'reports/seo/snippets.md', MEM = 'reports/seo/memory.json';
const LANGS = ['ua', 'de', 'fr', 'pt', 'es', 'it'];
const VP = ['de', 'fr']; // languages of the server-translated pages (modules/i18n-pages.php ss_vp_langs)
const PRODUCTS = ['infinity', 'pitch', 'kratos'];
const sleep = ms => new Promise(r => setTimeout(r, ms));

const groups = [
  { name: 'Главная', en: '/en/', other: LANGS.map(l => '/' + l + '/') },
  { name: 'Каталог', en: '/en/all-products/', other: VP.map(l => '/' + l + '/all-products/') },
  { name: 'Услуги', en: '/en/services-prices/', other: VP.map(l => '/' + l + '/services-prices/') },
  { name: 'Калькулятор', en: '/studio-calculator/', other: VP.map(l => '/' + l + '/studio-calculator/') },
  ...PRODUCTS.map(p => ({ name: 'Товар ' + p, en: '/product/' + p + '/', other: LANGS.map(l => '/' + l + '/product/' + p + '/') })),
];

const dec = s => String(s || '').replace(/<[^>]+>/g, '').replace(/&amp;/g, '&').replace(/&#0?39;|&#8217;|&rsquo;/g, "'").replace(/&quot;/g, '"')
  .replace(/&#8211;|&ndash;/g, '–').replace(/&#8212;|&mdash;/g, '—').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
const all = (s, re) => [...s.matchAll(re)].map(m => m[1]);
const attr = (tag, a) => { const m = tag.match(new RegExp('\\b' + a + '\\s*=\\s*["\']([^"\']*)', 'i')); return m ? m[1] : ''; };

async function get(path) {
  for (let i = 0; i < 4; i++) {
    try {
      const r = await fetch(BASE + path, { headers: { 'User-Agent': UA }, signal: AbortSignal.timeout(45000) });
      const t = await r.text();
      if (r.status === 429 || r.status === 503 || /Перевірка безпеки|Захищена сторінка/.test(t.slice(0, 3000))) { await sleep(10000 * (i + 1)); continue; }
      return { st: r.status, url: r.url, t };
    } catch (e) { await sleep(5000); }
  }
  return { st: 0, url: BASE + path, t: '' };
}

function parse(t) {
  const head = t.split(/<\/head>/i)[0] || t;
  const metas = all(head, /(<meta\b[^>]*>)/gi);
  const desc = dec(attr(metas.find(m => /name=["']description["']/i.test(m)) || '', 'content'));
  const ld = [];
  let brand = '', price = '';
  for (const j of all(t, /<script[^>]+application\/ld\+json[^>]*>([\s\S]*?)<\/script>/gi)) {
    try {
      const d = JSON.parse(j); const items = Array.isArray(d) ? d : (d['@graph'] || [d]);
      for (const it of items) {
        const ty = [].concat(it['@type'] || []).join('/'); if (ty) ld.push(ty);
        if (/Product/.test(ty)) {
          brand = it.brand ? (it.brand.name || String(it.brand)) : '';
          const o = [].concat(it.offers || [])[0] || {}; price = o.price ? o.price + ' ' + (o.priceCurrency || '') : '';
        }
      }
    } catch (e) { ld.push('ОШИБКА JSON'); }
  }
  return {
    title: dec((head.match(/<title[^>]*>([\s\S]*?)<\/title>/i) || [])[1]),
    desc,
    h1: all(t, /<h1\b[^>]*>([\s\S]*?)<\/h1>/gi).map(dec).filter(Boolean),
    canon: attr(all(head, /(<link\b[^>]*rel=["']canonical["'][^>]*>)/gi)[0] || '', 'href').replace(BASE, ''),
    hreflang: [...new Set(all(head, /hreflang=["']([^"']+)/gi))],
    ld: [...new Set(ld)], brand, price,
  };
}

(async () => {
  const mem = fs.existsSync(MEM) ? JSON.parse(fs.readFileSync(MEM, 'utf8')) : { pages: {} };
  const now = {}, issues = [], changes = [], rows = [];
  for (const g of groups) {
    for (const path of [g.en, ...g.other]) {
      const r = await get(path); await sleep(3000);
      const p = r.st === 200 ? parse(r.t) : null;
      const finalPath = r.url.replace(BASE, '');
      now[path] = p ? { title: p.title, desc: p.desc, h1: p.h1.join(' | '), brand: p.brand } : { st: r.st };
      const lang = path === g.en ? 'en' : path.split('/')[1];
      if (!p) { rows.push(`| ${g.name} | ${lang} | ❌ ${r.st || 'нет ответа'} | | | |`); issues.push(`🔴 ${path} — ответ ${r.st || 'нет'}`); continue; }
      const en = now[g.en] || {};
      const flags = [];
      if (finalPath.split('?')[0] !== path) flags.push('переадресация → ' + finalPath);
      if (lang !== 'en' && en.title && p.title === en.title) flags.push('title как в EN');
      if (lang !== 'en' && en.desc && p.desc && p.desc === en.desc) flags.push('description как в EN');
      const home = now['/' + lang + '/'] || {}; // 25.11: /de/all-products/ got the home title
      if (path !== '/' + lang + '/' && home.title && p.title === home.title) flags.push('title как у главной');
      if (p.title.length > 60) flags.push('title ' + p.title.length + ' зн.');
      if (!p.desc) flags.push('нет description'); else if (p.desc.length > 160) flags.push('description ' + p.desc.length + ' зн.');
      if (p.h1.length !== 1) flags.push('H1: ' + p.h1.length);
      if (p.canon && p.canon !== finalPath.split('?')[0]) flags.push('canonical → ' + p.canon);
      if (/^\/(product|[a-z]{2}\/product)\//.test(path)) { if (!p.price) flags.push('нет цены в Product'); if (!p.brand) flags.push('нет brand'); }
      flags.forEach(f => issues.push(`${/как в EN|нет цены|ответ/.test(f) ? '🟠' : '🟡'} ${path} — ${f}`));
      rows.push(`| ${g.name} | ${lang} | ${p.title.replace(/\|/g, '¦')} | ${p.h1.join(' / ').replace(/\|/g, '¦').slice(0, 60)} | ${p.hreflang.length} | ${flags.join('; ') || '✅'} |`);
      const old = mem.pages[path];
      if (old && !old.st) for (const k of ['title', 'desc', 'h1', 'brand']) if ((old[k] || '') !== (now[path][k] || '')) changes.push(`- ${path} **${k}**: «${old[k] || '—'}» → «${now[path][k] || '—'}»`);
    }
  }
  // 26.11: count VISIBLE H1 in a real browser (25.11: the only H1 on /en/all-products/ sat in a hidden block → 0 visible)
  let pw = null; try { pw = require('playwright'); } catch (e) {}
  if (pw) {
    const br = await pw.chromium.launch(process.env.PW_CHANNEL ? { channel: process.env.PW_CHANNEL } : {});
    const ctx = await br.newContext({ userAgent: UA, viewport: { width: 1366, height: 768 } });
    for (const path of ['/en/', '/en/all-products/', '/en/services-prices/', ...PRODUCTS.map(p => '/product/' + p + '/')]) {
      const pg = await ctx.newPage();
      try {
        await pg.goto(BASE + path, { waitUntil: 'domcontentloaded', timeout: 60000 });
        const n = await pg.evaluate(() => [...document.querySelectorAll('h1')].filter(h => { const r = h.getBoundingClientRect(), c = getComputedStyle(h); return r.width > 0 && r.height > 0 && c.visibility !== 'hidden' && c.display !== 'none' && +c.opacity !== 0; }).length);
        if (n !== 1) issues.push(`🟠 ${path} — видимых H1: ${n}`);
        rows.push(`| видимые H1 | en | ${path} | | | ${n === 1 ? '✅ 1' : '❌ ' + n} |`);
      } catch (e) { rows.push(`| видимые H1 | en | ${path} | | | не открылась |`); }
      await pg.close(); await sleep(3000);
    }
    await br.close();
  } else rows.push('| видимые H1 | — | нет playwright | | | |');
  // exam (docs/team/bots.md): known answers, checked by hand
  const exam = fs.existsSync('tools/seo/exam.json') ? JSON.parse(fs.readFileSync('tools/seo/exam.json', 'utf8')).items : [];
  const bad = exam.filter(e => issues.some(i => i.includes(e.path + ' —') && i.includes(e.flag)) !== e.expect);
  const examLine = exam.length ? `Экзамен: ${exam.length - bad.length}/${exam.length}` + (bad.length ? ' — ошибки: ' + bad.map(e => e.path + ' «' + e.flag + '» ' + (e.expect ? 'не найдено' : 'ложная тревога')).join('; ') : ' ✅') : '';
  const date = new Date().toISOString().slice(0, 16).replace('T', ' ');
  const md = [
    `# SEO: сниппеты ключевых страниц — ${date} UTC`,
    '',
    examLine,
    '',
    `Страниц: ${rows.length}. Большой обход всего сайта (битые ссылки, карта сайта, сироты, hreflang) — \`reports/sitebots/seo.md\` (по воскресеньям).`,
    '',
    `## Что поправить (${issues.length})`,
    ...(issues.length ? issues.map(i => '- ' + i) : ['- ✅ всё в порядке']),
    '',
    `## Что изменилось с прошлого прогона (${mem.date || 'первый прогон'})`,
    ...(changes.length ? changes : ['- ничего']),
    '',
    '## Все страницы',
    '| Страница | Язык | title | H1 | hreflang | Замечания |',
    '|---|---|---|---|---|---|',
    ...rows,
    '',
  ].join('\n');
  fs.mkdirSync('reports/seo', { recursive: true });
  fs.writeFileSync(OUT, md);
  fs.writeFileSync(MEM, JSON.stringify({ date, pages: now, runs: (mem.runs || 0) + 1, exam: examLine }, null, 1));
  console.log(md.split('\n').slice(0, 30).join('\n'));
})();
