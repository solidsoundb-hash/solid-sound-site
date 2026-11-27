// Probe (25.11, Копирайтер): terms §3 «Prices and payment» — which currency the text names (EN /terms/ + DE /de/terms/), PC 1366 + A31 412×780 screenshots of that section.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
const UA_M = 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0';
const UA_D = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const DEV = { pc: { viewport: { width: 1366, height: 768 }, userAgent: UA_D }, a31: { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, userAgent: UA_M } };
const PAGES = ['/terms/', '/de/terms/'];
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined }); const rows = ['| Страница | Устройство | §3 текст |', '|---|---|---|'];
  for (const [dn, dv] of Object.entries(DEV)) {
    const ctx = await b.newContext({ ...dv, locale: 'en-US' });
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    for (const path of PAGES) {
      const p = await ctx.newPage();
      await p.goto('https://solid-sound.net' + path + '?r=' + Date.now(), { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {}); await p.waitForTimeout(2500);
      const txt = await p.evaluate(() => { const els = [...document.querySelectorAll('p,li')].filter(e => /Prices are set in|Die Preise werden in/.test(e.textContent)).sort((a, b) => a.textContent.length - b.textContent.length); const e = els[0]; if (!e) return ''; e.scrollIntoView({ block: 'center' }); return e.textContent.trim().slice(0, 220); });
      await p.waitForTimeout(600);
      rows.push(`| ${path} | ${dn} | ${txt || 'не найден'} |`);
      await p.screenshot({ path: `${OUT}/${dn}_${path.replace(/\W+/g, '_')}.jpg`, type: 'jpeg', quality: 70 });
      await p.close();
    }
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/report.md', rows.join('\n') + '\n'); console.log(rows.join('\n')); await b.close();
})();
