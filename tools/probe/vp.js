// Probe (24.11, «Копирайтер»; 25.11 + /fr/ and the language list on /en/all-products/): do the translated pages work? For each /de/ and /fr/ URL (cache-busting query): HTTP code, final URL
// (redirect to /en/?), <html lang>, our translator script (ss-vp-js), a German marker text, hreflang links; + header labels after JS.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
const S = 'https://solid-sound.net';
const PAGES = [['/en/all-products/', 'Play All'], ['/de/services-prices/', 'Leistungen'], ['/de/all-products/', 'Alle abspielen'], ['/de/product/pitch/', 'Technische Daten'], ['/de/free/', 'Gratis'], ['/de/sell/', 'Verkaufe'], ['/de/faq/', 'Zahlungsarten'], ['/de/studio-calculator/?s=mixing', 'Studio-Preisrechner'], ['/de/cart/', 'Warenkorb'], ['/de/my-account/', 'Willkommen zurück'], ['/fr/services-prices/', 'tarifs'], ['/fr/all-products/', 'Tout écouter'], ['/fr/product/pitch/', 'Caractéristiques'], ['/fr/free/', 'Gratuit'], ['/fr/sell/', 'Vendre'], ['/fr/faq/', 'moyens de paiement'], ['/fr/studio-calculator/?s=mixing', 'Calculateur de prix studio'], ['/fr/cart/', 'Panier'], ['/fr/my-account/', 'Bon retour'], ['/fr/terms/', 'Droit de rétractation'], ['/es/services-prices/', 'Precios'], ['/es/all-products/', 'Reproducir todo'], ['/es/product/pitch/', 'Especificaciones'], ['/es/free/', 'Gratis'], ['/es/sell/', 'Vende'], ['/en/sell/', 'Sell'], ['/es/faq/', 'formas de pago'], ['/es/studio-calculator/?s=mixing', 'Calculadora de precios'], ['/es/cart/', 'Carrito'], ['/es/my-account/', 'Hola de nuevo'], ['/es/terms/', 'desistimiento']];
/* VP_ONLY (regex): a short run of a few pages (tools/probe/vpes.js) — the full run is near the 15-minute limit */
if (process.env.VP_ONLY) { const re = new RegExp(process.env.VP_ONLY); for (let i = PAGES.length - 1; i >= 0; i--) if (!re.test(PAGES[i][0])) PAGES.splice(i, 1); }
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined }); const md = ['# Переводы /de/ — ' + new Date().toISOString(), '', '| Страница | Код | Куда пришли | lang | скрипт перевода | немецкий текст | hreflang | шапка | ошибки JS |', '|---|---|---|---|---|---|---|---|---|'];
  for (const [dev, opt] of [['pc', { viewport: { width: 1366, height: 768 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' }], ['a31', { viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' }]]) {
    const ctx = await b.newContext(Object.assign({ locale: 'de-DE' }, opt));
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    const p = await ctx.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message.slice(0, 100)));
    for (const [u, mark] of PAGES) {
      errs.length = 0;
      const url = S + u + (u.includes('?') ? '&' : '?') + 'nc=' + Date.now();
      const r = await p.goto(url, { waitUntil: 'networkidle', timeout: 120000 }).catch(() => null);
      await p.waitForTimeout(2000);
      const d = await p.evaluate((mark) => ({ lang: document.documentElement.lang, vp: !!document.getElementById('ss-vp-js'), de: document.body.innerText.includes(mark),
        hl: [...document.querySelectorAll('link[rel=alternate][hreflang]')].map(l => l.hreflang).join(','),
        langs: (window.ssLangs || []).map(l => l.k + ':' + l.t + ':' + l.n).join(' '), head: [...document.querySelectorAll('#ssg .g-l, #ssg-bar a')].map(x => x.textContent.trim()).filter(Boolean).slice(0, 12).join(' / ') }), mark).catch(() => ({}));
      md.push(`| ${u} (${dev}) | ${r ? r.status() : '—'} | ${p.url().replace(S, '').replace(/[?&]nc=\d+/, '')} | ${d.lang} | ${d.vp ? '✅' : '❌'} | ${d.de ? '✅' : '❌'} ${mark} | ${d.hl || '—'} | ${d.head || '—'} | ${errs.join(' / ') || '—'} |`); if (d.langs) md.push(`|   языки: ${d.langs} |`);
      if (dev === 'a31' || /services-prices|terms|sell/.test(u)) await p.screenshot({ path: `${OUT}/${dev}_${u.replace(/[^a-z0-9]+/gi, '_')}.jpg`, type: 'jpeg', quality: 60 });
    }
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/report.md', md.join('\n') + '\n'); console.log(md.join('\n')); await b.close();
})();
