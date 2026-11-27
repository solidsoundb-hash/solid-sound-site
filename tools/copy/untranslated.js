// «Копирайтер» robot (25.11.2026, docs/team/bots.md): untranslated strings on the language versions of the site.
// Read-only. Opens each page in a real browser (texts printed by scripts count too), collects every text node + placeholder /
// aria-label / title / alt + <title> + meta description, and lists the strings that are still English.
// Env: UNTR_LANGS="de,fr,es" · UNTR_PAGES="/services-prices/,/sell/" (paths without the language) · UNTR_OUT=dir.
// `node tools/copy/untranslated.js --exam` checks the English detector on tools/bots/untranslated/exam.json (must be 100 %).
const fs = require('fs'); const path = require('path');
const ROOT = path.join(__dirname, '..', '..');
const OUT = process.env.UNTR_OUT || 'untr-out';
const S = 'https://solid-sound.net';
const PAGES = (process.env.UNTR_PAGES || '/refund_returns/,/services-prices/,/all-products/,/product/pitch/,/free/,/sell/,/faq/,/studio-calculator/?s=mixing,/cart/,/my-account/,/terms/,/license/,/creator-terms/').split(',').map(s => s.trim()).filter(Boolean);
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
const notText = t => /^(https?:\/\/|www\.)\S+$|^\S+@\S+\.\w+$/i.test(t.trim()); /* a link or an email address is not copy */
function isEnglish(text, lang) {
  const w = words(text); if (w.length < 3) return false;
  if (lang === 'ua' && /[а-яіїєґ]/i.test(text)) return false;
  const also = new Set((ALSO[lang] || '').split(' ')), me = new Set((ME[lang] || '').split(' '));
  let en = 0, mine = 0;
  for (const x of w) { if (EN.has(x) && !also.has(x)) en++; if (me.has(x)) mine++; }
  return en >= 2 && en > mine * 2 && en / w.length >= 0.15;
}
/* the same string word for word on the /en/ page: untranslated if it has an ordinary lowercase word (4+ letters) or a section
   number («5. Payouts», «Last updated: …»). Product names, brands and plugin names are Capitalised → skipped. */
function sameAsEn(text, lang, enSet) {
  if (!enSet.has(text) || lang === 'en') return false;
  if (/^\d+\.\s+\p{L}/u.test(text)) return true;
  return /(^|[\s(])\p{Ll}{4,}/u.test(text);
}
const untr = (t, lang, enSet) => !notText(t) && (isEnglish(t, lang) || sameAsEn(t, lang, enSet));

if (process.argv.includes('--exam')) {
  const ex = JSON.parse(fs.readFileSync(path.join(ROOT, 'tools/bots/untranslated/exam.json'), 'utf8'));
  let ok = 0; const bad = [];
  for (const e of ex) { const r = untr(e.text, e.lang, new Set(e.same ? [e.text] : [])); if (r === e.english) ok++; else bad.push(e); }
  console.log(`exam ${ok}/${ex.length}`); bad.forEach(b => console.log('  WRONG', b.lang, b.english, b.text));
  process.exit(ok === ex.length ? 0 : 1);
}

(async () => {
  const { chromium } = require('playwright');
  fs.mkdirSync(OUT, { recursive: true });
  let mem = { noise: [] }; try { mem = JSON.parse(fs.readFileSync(path.join(ROOT, 'reports/copy/memory.json'), 'utf8')); } catch (e) {}
  const noise = new Set((mem.noise || []).map(s => s.replace(/\s+/g, ' ').trim()));
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await b.newContext({ viewport: { width: 1366, height: 900 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage();
  const COLLECT = () => {
    const SK = { SCRIPT: 1, STYLE: 1, NOSCRIPT: 1, TEXTAREA: 1, CODE: 1 }; const out = [];
    const vis = el => { try { return el.checkVisibility ? el.checkVisibility({ checkVisibilityCSS: true }) : !!el.offsetParent; } catch (e) { return true; } };
    const w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT); let n;
    while ((n = w.nextNode())) { const el = n.parentElement; if (!el || SK[el.nodeName] || el.closest('script,style,noscript,textarea,code,svg,#wpadminbar')) continue;
      const t = n.nodeValue.replace(/\s+/g, ' ').trim(); if (t.length > 6) out.push([t, vis(el) ? '' : 'скрыт']); }
    document.querySelectorAll('[placeholder],[aria-label],[title],img[alt]').forEach(el => ['placeholder', 'aria-label', 'title', 'alt'].forEach(a => { const v = (el.getAttribute(a) || '').replace(/\s+/g, ' ').trim(); if (v.length > 6) out.push([v, a]); }));
    out.push([document.title, 'title']);
    const md = document.querySelector('meta[name=description]'); if (md) out.push([md.content.trim(), 'description']);
    return out;
  };
  async function load(url) {
    const r = await p.goto(S + url + (url.includes('?') ? '&' : '?') + 'nc=' + Date.now(), { waitUntil: 'domcontentloaded', timeout: 60000 });
    const code = r ? r.status() : 0, final = p.url().replace(S, '').replace(/[?&]nc=\d+/, '');
    await p.waitForLoadState('networkidle', { timeout: 15000 }).catch(() => {});
    await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 700) { scrollTo(0, y); await new Promise(r => setTimeout(r, 120)); } scrollTo(0, 0); });
    await p.waitForTimeout(1200);
    return { code, final, items: await p.evaluate(COLLECT) };
  }
  const found = {}; const pages = []; const EN_PAGE = {};
  const t0 = Date.now();
  for (const pg of PAGES) { try { EN_PAGE[pg] = new Set((await load('/en' + pg)).items.map(x => x[0])); } catch (e) { EN_PAGE[pg] = new Set(); } }
  for (const lang of LANGS) {
    found[lang] = {};
    for (const pg of PAGES) {
      const url = `/${lang}${pg}`; let res;
      try { res = await load(url); } catch (e) { pages.push({ lang, url, code: 'ошибка', final: String(e.message).slice(0, 80), n: 0 }); continue; }
      let n = 0;
      for (const [t, where] of res.items) {
        if (noise.has(t) || noise.has(lang + '|' + t) || !untr(t, lang, EN_PAGE[pg])) continue; /* «fr|10. Contact»: the same word in that language */
        const k = t.slice(0, 300); const f = found[lang][k] || (found[lang][k] = { where: new Set(), pages: new Set() });
        f.pages.add(pg); if (where) f.where.add(where); n++;
      }
      pages.push({ lang, url, code: res.code, final: res.final, n });
    }
  }
  await b.close();
  const md = ['# Непереведённые строки — ' + new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC', '',
    `Робот «Копирайтера» (только чтение): ${LANGS.length} яз. × ${PAGES.length} стр., ${Math.round((Date.now() - t0) / 60000)} мин. Строка считается английской, если (1) в ней ≥ 3 слов и английские служебные слова (the, your, you, for…) явно перевешивают слова языка страницы, или (2) она слово в слово как на /en/ и в ней есть обычное слово со строчной буквы или номер раздела. Шум убирается через \`reports/copy/memory.json\` → \`noise\`.`, '',
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
