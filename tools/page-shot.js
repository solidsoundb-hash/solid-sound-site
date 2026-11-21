// Before / after pictures of any pages of OUR site (owner 20.11: «всегда скринить до и после правок»).
// Pages = last line of .github/page-shot.txt after the date: «<date> <label> /path/ /path2/ …». Each page on a computer (1366×768)
// and a weak phone (Samsung A31 with browser bars, 412×700): first screen + full page, plus weight (KB), number of requests,
// YouTube requests, JS errors. Output: page-shot/<label>/ + report.md, pushed to branch shots-ab.
const { chromium } = require('playwright'); const fs = require('fs');
const line = fs.readFileSync('.github/page-shot.txt', 'utf8').trim().split('\n').pop().trim().split(/\s+/);
const clicks = line.filter(x => x.startsWith('click=')).map(x => x.slice(6).replace(/_/g, ' ')); // «click=Film_·_Ads_·_Video»: click that text, then extra shot
const paths = line.filter(x => x.startsWith('/')), label = (line.find(x => !x.startsWith('/') && !x.startsWith('click=') && !/^\d/.test(x)) || 'shot').replace(/[^\w-]/g, '');
const OUT = 'page-shot/' + label; fs.mkdirSync(OUT, { recursive: true });
const S = 'https://solid-sound.net';
const DEV = [['pc', { viewport: { width: 1366, height: 768 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' }], ['a31', { viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' }]];
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const md = ['# Страницы до/после — ' + label + ' — ' + new Date().toISOString(), '', '| Страница | Где | До действий: вес / запросов | Вес | Запросов | YouTube | Ошибки JS | Снимки |', '|---|---|---|---|---|---|---|---|'];
  for (const p0 of paths) for (const [dn, o] of DEV) {
    const ctx = await b.newContext({ ...o, locale: 'en-US', deviceScaleFactor: 1 });
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    const p = await ctx.newPage(); let n = 0, yt = 0, kb = 0; const errs = [];
    p.on('response', async r => { n++; if (/youtube|ytimg|googlevideo/.test(r.url())) yt++; const l = +(r.headers()['content-length'] || 0); kb += l; });
    p.on('pageerror', e => errs.push(e.message.slice(0, 120)));
    await p.goto(S + p0, { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {});
    await p.waitForTimeout(2500);
    await p.evaluate(() => document.querySelectorAll('body *').forEach(z => { const s = getComputedStyle(z); if (s.position === 'fixed' && /privacy|cookie/i.test((z.textContent || '').slice(0, 200)) && (z.textContent || '').length < 900) z.style.display = 'none'; })).catch(() => {});
    const n0 = n, kb0 = await p.evaluate(() => Math.round(performance.getEntriesByType('resource').reduce((a, e) => a + (e.transferSize || 0), 0) / 1024)).catch(() => 0); // before any action
    for (let y = 0; y < 12; y++) { await p.mouse.wheel(0, 900); await p.waitForTimeout(200); } await p.evaluate(() => scrollTo(0, 0)); await p.waitForTimeout(1200);
    const res = await p.evaluate(() => Math.round(performance.getEntriesByType('resource').reduce((a, e) => a + (e.transferSize || 0), 0) / 1024)).catch(() => 0);
    const slug = (p0.replace(/[^a-z0-9]+/gi, '_').replace(/^_|_$/g, '') || 'home') + '_' + dn;
    await p.screenshot({ path: `${OUT}/${slug}.jpg`, type: 'jpeg', quality: 60 }).catch(() => {});
    await p.screenshot({ path: `${OUT}/${slug}_full.jpg`, type: 'jpeg', quality: 50, fullPage: true }).catch(() => {});
    for (let ci = 0; ci < clicks.length; ci++) {
      const el = p.getByText(clicks[ci], { exact: true }).first();
      try { await el.scrollIntoViewIfNeeded({ timeout: 8000 }); await el.click({ timeout: 8000 }); await p.waitForTimeout(1500); await p.evaluate(() => scrollBy(0, -120));
        await p.screenshot({ path: `${OUT}/${slug}_click${ci + 1}.jpg`, type: 'jpeg', quality: 70 }); } catch (e) { errs.push('click «' + clicks[ci] + '»: ' + e.message.slice(0, 60)); }
    }
    md.push(`| ${p0} | ${dn === 'pc' ? 'ПК 1366' : 'A31 412×700'} | ${kb0} КБ / ${n0} | ${Math.max(res, Math.round(kb / 1024))} КБ | ${n} | ${yt} | ${errs.length ? errs.join(' / ') : '—'} | [экран](${slug}.jpg) · [целиком](${slug}_full.jpg) |`);
    await ctx.close();
  }
  fs.writeFileSync(`${OUT}/report.md`, md.join('\n') + '\n'); console.log(md.join('\n')); await b.close();
})();
