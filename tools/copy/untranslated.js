// «Копирайтер» robot (25.11.2026, docs/team/bots.md): untranslated strings on the language versions of the site.
// Read-only. Opens each page in a real browser (texts printed by scripts count too), collects every text node + placeholder /
// aria-label / title / alt + <title> + meta description, and lists the strings that are still English.
// Env: UNTR_LANGS="de,fr,es" · UNTR_PAGES="/services-prices/,/sell/" (paths without the language) · UNTR_OUT=dir.
// `node tools/copy/untranslated.js --exam` checks the English detector on tools/bots/untranslated/exam.json (must be 100 %).
const fs = require('fs'); const path = require('path');
const ROOT = path.join(__dirname, '..', '..');
const OUT = process.env.UNTR_OUT || 'untr-out';
const S = 'https://solid-sound.net';
const PAGES = (process.env.UNTR_PAGES || '/services-prices/,/all-products/,/product/pitch/,/free/,/sell/,/faq/,/studio-calculator/?s=mixing,/cart/,/my-account/,/terms/,/license/,/creator-terms/').split(',').map(s => s.trim()).filter(Boolean);
const LANGS = (process.env.UNTR_LANGS || 'de,fr,es').split(',').map(s => s.trim()).filter(Boolean);

/* English function words; a word that is ALSO common in the page language does not count as English there */
const EN = new Set('the and you your yours for with this that these those are from our all get what how can will more have has not into about to of on is it its we by be or as at if us my after before when only every any once than then they their there which while who why would should could been was were do does did just also here please per'.split(' '));
const ALSO = {
  de: 'in an was will so die am also',
  fr: 'on as an est plus',
  es: 'no a con',
  it: 'a con in',
  pt: 'a no do com',
  ua: '',
};
const ME = {
  de: 'und der die das ist mit für sie ihr ihre dein deine deinen nicht auf ein eine einen wir den dem zu von auch du dich dir bei oder wie sich noch nach jetzt',
  fr: 'et le la les des une est pour avec vous votre vos nous dans sur pas qui que du au en ce tu ton ta tes par plus',
  es: 'y el la los las de del para con tu tus su sus no una un en es que más por al lo te se',
  it: 'e il la i le di del della per con tu tuo tua non una un in che è più al lo ti si',
  pt: 'e o a os as de do da para com você seu sua não uma um em no na que mais',
  ua: 'та і й що як для це ви ваш ваша на з до від не але або ще все ми наш',
};
const words = s => (s.toLowerCase().match(/\p{L}+/gu) || []);
function isEnglish(text, lang) {
  const w = words(text); if (w.length < 3) return false;
  if (lang === 'ua' && /[а-яіїєґ]/i.test(text)) return false;
  const also = new Set((ALSO[lang] || '').split(' ')), me = new Set((ME[lang] || '').split(' '));
  let en = 0, mine = 0;
  for (const x of w) { if (EN.has(x) && !also.has(x)) en++; if (me.has(x)) mine++; }
  return en >= 2 && en > mine * 2 && en / w.length >= 0.15;
}

if (process.argv.includes('--exam')) {
  const ex = JSON.parse(fs.readFileSync(path.join(ROOT, 'tools/bots/untranslated/exam.json'), 'utf8'));
  let ok = 0; const bad = [];
  for (const e of ex) { const r = isEnglish(e.text, e.lang); if (r === e.english) ok++; else bad.push(e); }
  console.log(`exam ${ok}/${ex.length}`); bad.forEach(b => console.log('  WRONG', b.lang, b.english, b.text));
  process.exit(ok === ex.length ? 0 : 1);
}

(async () => {
  const { chromium } = require('playwright');
  fs.mkdirSync(OUT, { recursive: true });
  let mem = { noise: [] }; try { mem = JSON.parse(fs.readFileSync(path.join(ROOT, 'reports/copy/memory.json'), 'utf8')); } catch (e) {}
  const noise = new Set((mem.noise || []).map(s => s.replace(/\s+/g, ' ').trim()));
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await b.newContext({ viewport: { width: 1366, height: 900 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage();
  const found = {}; const pages = [];
  const t0 = Date.now();
  for (const lang of LANGS) {
    found[lang] = {};
    for (const pg of PAGES) {
      const url = `/${lang}${pg}`; let code = 0, final = '';
      try {
        const r = await p.goto(S + url + (url.includes('?') ? '&' : '?') + 'nc=' + Date.now(), { waitUntil: 'domcontentloaded', timeout: 60000 });
        code = r ? r.status() : 0; final = p.url().replace(S, '').replace(/[?&]nc=\d+/, '');
        await p.waitForLoadState('networkidle', { timeout: 15000 }).catch(() => {});
        await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 700) { scrollTo(0, y); await new Promise(r => setTimeout(r, 120)); } scrollTo(0, 0); });
        await p.waitForTimeout(1200);
      } catch (e) { pages.push({ lang, url, code: 'ошибка', final: String(e.message).slice(0, 80), n: 0 }); continue; }
      const items = await p.evaluate(() => {
        const SK = { SCRIPT: 1, STYLE: 1, NOSCRIPT: 1, TEXTAREA: 1, CODE: 1, svg: 1 }; const out = [];
        const vis = el => { try { return el.checkVisibility ? el.checkVisibility({ checkVisibilityCSS: true }) : !!el.offsetParent; } catch (e) { return true; } };
        const w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT); let n;
        while ((n = w.nextNode())) { const el = n.parentElement; if (!el || SK[el.nodeName] || el.closest('script,style,noscript,textarea,code,#wpadminbar')) continue;
          const t = n.nodeValue.replace(/\s+/g, ' ').trim(); if (t.length > 6) out.push([t, vis(el) ? '' : 'скрыт']); }
        document.querySelectorAll('[placeholder],[aria-label],[title],img[alt]').forEach(el => ['placeholder', 'aria-label', 'title', 'alt'].forEach(a => { const v = (el.getAttribute(a) || '').trim(); if (v.length > 6) out.push([v, a]); }));
        out.push([document.title, 'title']);
        const md = document.querySelector('meta[name=description]'); if (md) out.push([md.content, 'description']);
        return out;
      });
      let n = 0;
      for (const [t, where] of items) {
        if (noise.has(t) || !isEnglish(t, lang)) continue;
        const k = t.slice(0, 300); const f = found[lang][k] || (found[lang][k] = { where: new Set(), pages: new Set() });
        f.pages.add(pg); if (where) f.where.add(where); n++;
      }
      pages.push({ lang, url, code, final, n });
    }
  }
  await b.close();
  const md = ['# Непереведённые строки — ' + new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC', '',
    `Робот «Копирайтера» (только чтение): ${LANGS.length} яз. × ${PAGES.length} стр., ${Math.round((Date.now() - t0) / 60000)} мин. Строка считается английской, если в ней ≥ 3 слов и английские служебные слова (the, your, you, for…) явно перевешивают слова языка страницы. Шум убирается через \`reports/copy/memory.json\` → \`noise\`.`, '',
    '| Язык | Страница | Код | Куда пришли | Англ. строк |', '|---|---|---|---|---|',
    ...pages.map(x => `| ${x.lang} | ${x.url} | ${x.code} | ${x.final && x.final !== x.url ? x.final : '—'} | ${x.n} |`), ''];
  const data = {};
  for (const lang of LANGS) {
    const rows = Object.entries(found[lang]).sort((a, b) => b[1].pages.size - a[1].pages.size);
    data[lang] = rows.map(([t, f]) => ({ text: t, pages: [...f.pages], where: [...f.where] }));
    md.push(`## ${lang.toUpperCase()} — ${rows.length} уникальных строк`, '');
    if (!rows.length) { md.push('Всё переведено ✅', ''); continue; }
    md.push('| Строка | Где | Страницы |', '|---|---|---|');
    rows.forEach(([t, f]) => md.push(`| ${t.replace(/\|/g, '\\|')} | ${[...f.where].join(', ') || 'текст'} | ${[...f.pages].join(' ')} |`));
    md.push('');
  }
  fs.writeFileSync(path.join(OUT, 'report.md'), md.join('\n'));
  fs.writeFileSync(path.join(OUT, 'data.json'), JSON.stringify(data, null, 1));
  console.log(md.slice(0, 40).join('\n'));
})();
