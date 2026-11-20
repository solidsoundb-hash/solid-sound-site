// Language robot (owner 20.11.2026: «проверь языки»). Read-only, plain page requests (slow: the hosting blocks fast robots).
// Every page of every language from the sitemap: opens? right <html lang>? hreflang links to the other languages? the language
// switcher points to the SAME page in the other language (not the home page)? how much English is left in the title, headings,
// menu and text? menu items left in English. Output: lang-out/report.md (Russian) + lang-out/data.json.
const fs = require('fs'); const OUT = 'lang-out'; fs.mkdirSync(OUT, { recursive: true });
const BASE = 'https://solid-sound.net';
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const LANGS = { ua: 'uk', de: 'de', fr: 'fr', pt: 'pt', es: 'es', it: 'it' };
// short, very common words of each language (enough to tell a sentence's language)
const SW = {
  en: 'the and you your for with this that are from our all get what how can will more have was not into about free track mix',
  uk: 'та і й що як для це ви ваш ваша на з до від не але або ще все ми наш треку трек вашого',
  de: 'und der die das ist mit für sie ihr ihre nicht auf ein eine wir den dem zu von auch',
  fr: 'et le la les des une est pour avec vous votre nous dans sur pas qui que du au en',
  pt: 'e o a os as de do da para com você seu sua não uma um em no na que mais',
  es: 'y el la los las de del para con tu su no una un en es que más por al',
  it: 'e il la i le di del della per con tu tuo tua non una un in che è più al',
};
const set = (s) => new Set(s.split(' '));
const SWS = Object.fromEntries(Object.entries(SW).map(([k, v]) => [k, set(v)]));
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const txt = (h) => h.replace(/<script[\s\S]*?<\/script>|<style[\s\S]*?<\/style>|<!--[\s\S]*?-->/g, ' ').replace(/<[^>]+>/g, ' ').replace(/&nbsp;|&#160;/g, ' ').replace(/&[a-z#0-9]+;/gi, ' ').replace(/\s+/g, ' ').trim();
function share(text, lang) { const w = text.toLowerCase().match(/\p{L}+/gu) || []; let en = 0, me = 0; for (const x of w) { if (SWS.en.has(x)) en++; if (SWS[lang] && SWS[lang].has(x)) me++; } return { en, me, n: w.length, enPct: en + me ? Math.round(100 * en / (en + me)) : 0 }; }
async function get(u) {
  for (let i = 0; i < 4; i++) {
    try { const r = await fetch(u, { headers: { 'User-Agent': UA, Accept: 'text/html' }, redirect: 'follow', signal: AbortSignal.timeout(45000) }); const t = await r.text();
      if (r.status === 429 || /Сторінка захищена|Захищена сторінка/.test(t)) { await sleep(30000 * (i + 1)); continue; }
      return { code: r.status, url: r.url, html: t }; } catch (e) { await sleep(5000); }
  }
  return { code: 0, url: u, html: '' };
}
(async () => {
  const idx = await get(BASE + '/wp-sitemap.xml'); const pages = new Set();
  for (const sm of (idx.html.match(/<loc>[^<]+/g) || []).map(s => s.slice(5))) { const x = await get(sm); (x.html.match(/<loc>[^<]+/g) || []).map(s => s.slice(5)).forEach(u => pages.add(u)); await sleep(1500); }
  const all = [...pages].filter(u => /^\/(ua|de|fr|pt|es|it)\//.test(new URL(u).pathname)).sort();
  // the English menu labels, to find menu items left untranslated
  const en = await get(BASE + '/en/'); const enMenu = new Set((en.html.match(/<nav[\s\S]*?<\/nav>/g) || []).flatMap(n => (n.match(/<a\b[^>]*>([\s\S]*?)<\/a>/g) || []).map(a => txt(a).toLowerCase())).filter(x => x.length > 2 && x.length < 30));
  const rows = [], probs = { open: [], lang: [], hreflang: [], switcher: [], english: [], menu: [] };
  for (const u of all) {
    const code = new URL(u).pathname.split('/')[1], want = LANGS[code]; const r = await get(u); await sleep(1800);
    const p = u.replace(BASE, '');
    if (r.code !== 200) { probs.open.push(`| ${p} | ${r.code || 'нет ответа'} |`); continue; }
    const h = r.html, hl = (h.match(/<html[^>]*\blang="([^"]+)"/i) || [])[1] || '';
    if (!hl.toLowerCase().startsWith(want)) probs.lang.push(`| ${p} | lang="${hl}" (нужно ${want}) |`);
    const alts = (h.match(/<link[^>]+hreflang="[^"]+"[^>]*>/gi) || []);
    if (alts.length < 2) probs.hreflang.push(`| ${p} | ссылок на другие языки (hreflang): ${alts.length} |`);
    // language switcher: links whose text is a language name / code and which lead to another /xx/ address
    const sw = (h.match(/<li[^>]*class="[^"]*lang-item[^"]*"[\s\S]*?<\/li>/gi) || []).map(li => (li.match(/href="([^"]+)"/) || [])[1]).filter(Boolean);
    const toHome = sw.filter(x => { try { const q = new URL(x, BASE).pathname; return /^\/(en|ua|de|fr|pt|es|it)\/?$/.test(q); } catch (e) { return false; } });
    if (sw.length && toHome.length && !/^\/(ua|de|fr|pt|es|it)\/?$/.test(new URL(u).pathname)) probs.switcher.push(`| ${p} | ${toHome.length} из ${sw.length} языков ведут на главную, а не на эту страницу |`);
    const title = txt((h.match(/<title>([\s\S]*?)<\/title>/i) || [])[1] || ''), h1 = txt((h.match(/<h1[^>]*>([\s\S]*?)<\/h1>/i) || [])[1] || '');
    const main = txt((h.match(/<main[\s\S]*?<\/main>/i) || h.match(/<div[^>]+class="[^"]*l-main[\s\S]*/i) || [h])[0]);
    const s = share(main, want), st = share(title + ' ' + h1, want);
    if (s.n > 40 && s.enPct >= 50) probs.english.push(`| ${p} | текст: ~${s.enPct}% английских слов | «${(h1 || title).slice(0, 60)}» |`);
    else if (st.en >= 2 && st.enPct >= 60) probs.english.push(`| ${p} | заголовок по-английски | «${(h1 || title).slice(0, 60)}» |`);
    const menu = (h.match(/<nav[\s\S]*?<\/nav>/g) || []).flatMap(n => (n.match(/<a\b[^>]*>([\s\S]*?)<\/a>/g) || []).map(a => txt(a))).filter(x => enMenu.has(x.toLowerCase()) && !/^(wiki|forum|faq|shop|home|solid sound|en|ua|de|fr|pt|es|it|ableton|serum)$/i.test(x));
    if (menu.length) probs.menu.push(`| ${p} | ${[...new Set(menu)].slice(0, 8).join(', ')} |`);
    rows.push({ p, hl, alts: alts.length, sw: sw.length, enPct: s.enPct, title: title.slice(0, 80) });
  }
  const sec = (t, head, arr) => [`## ${t} (${arr.length})`, '', head, head.replace(/[^|]+/g, '---'), ...arr.slice(0, 200), ''];
  const md = [`# Языки — ${new Date().toISOString().slice(0, 16).replace('T', ' ')} UTC`, '', `Проверено страниц на других языках: ${rows.length + probs.open.length} (${Object.keys(LANGS).join(', ')}).`, '',
    ...sec('❌ Не открывается', '| Страница | Код |', probs.open),
    ...sec('⚠️ Язык страницы указан неверно (html lang)', '| Страница | Сейчас |', probs.lang),
    ...sec('⚠️ Нет ссылок на эту же страницу на других языках (hreflang, важно для Google)', '| Страница | Что |', probs.hreflang),
    ...sec('⚠️ Переключатель языка ведёт на главную, а не на эту же страницу', '| Страница | Что |', probs.switcher),
    ...sec('🌐 Осталось по-английски', '| Страница | Что | Заголовок |', probs.english),
    ...sec('🌐 Пункты меню не переведены', '| Страница | Пункты |', [...new Set(probs.menu)]),
  ];
  fs.writeFileSync(OUT + '/report.md', md.join('\n') + '\n'); fs.writeFileSync(OUT + '/data.json', JSON.stringify({ rows, probs }, null, 1)); console.log(md.join('\n'));
})();
