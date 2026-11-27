// Read-only look at a public page (owner 20.11: «глянь вот это» — competitors' service pages). No clicks, no forms.
// URL = last line of .github/page-look.txt. Output: page-out/ (full-page screenshots + headings/prices text), workflow artifact.
const { chromium } = require('playwright'); const fs = require('fs');
const url = fs.readFileSync('.github/page-look.txt', 'utf8').trim().split('\n').pop().split(' ').pop();
(async () => {
  fs.mkdirSync('page-out', { recursive: true });
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const [n, o] of [['desktop', { viewport: { width: 1440, height: 900 } }], ['phone', { viewport: { width: 390, height: 844 }, isMobile: true }]]) {
    const p = await (await b.newContext({ ...o, locale: 'en-US', userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' })).newPage();
    const errs = []; p.on('pageerror', e => errs.push(e.message));
    await p.goto(url, { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {});
    await p.waitForTimeout(3000);
    for (let y = 0; y < 20; y++) { await p.mouse.wheel(0, 1200); await p.waitForTimeout(250); } await p.evaluate(() => scrollTo(0, 0));
    await p.screenshot({ path: `page-out/${n}.jpg`, type: 'jpeg', quality: 60, fullPage: true }).catch(() => {});
    if (url.includes('solid-sound.net') && await p.$('.pp-c')) { /* our client-chats carousel: images, arrows, big view (21.11) */
      const st = () => p.evaluate(() => { const c = document.querySelector('#pr-mixing .pp-ch'), h = c.previousElementSibling; return { n: h.querySelector('.pp-n').textContent, pv: h.querySelector('.pp-pv').disabled, imgs: [...c.querySelectorAll('img')].map(i => i.naturalWidth).join(',') }; });
      const r = [JSON.stringify(await st())];
      r.push('errors: ' + JSON.stringify(errs)); r.push('script: ' + await p.evaluate(() => { const s = [...document.scripts].find(x => x.textContent.includes('pp-lb')); return s ? s.type + ' | ' + s.textContent.slice(s.textContent.indexOf('function init'), s.textContent.indexOf('function init') + 400) : 'none'; }));
      await p.$eval('#pr-mixing .pp-sh', e => { e.scrollIntoView(); scrollBy(0, -120); }); await p.waitForTimeout(1500);
      await p.screenshot({ path: `page-out/${n}-chats.jpg`, type: 'jpeg', quality: 70 });
      await p.click('#pr-mixing .pp-nx').catch(e => r.push('nx ' + e.message)); await p.waitForTimeout(1200); r.push(JSON.stringify(await st()));
      await p.click('#pr-mixing .pp-c >> nth=1').catch(e => r.push('card ' + e.message)); await p.waitForTimeout(1200);
      r.push('lightbox: ' + !!(await p.$('.pp-lb'))); await p.screenshot({ path: `page-out/${n}-lightbox.jpg`, type: 'jpeg', quality: 70 });
      await p.keyboard.press('Escape'); fs.writeFileSync(`page-out/${n}-check.txt`, r.join('\n'));
    }
    if (url.includes('solid-sound.net')) { /* header: mini cart, favorites, currencies (22.11) */
      const r = []; r.push('currencies: ' + await p.evaluate(() => ((window.SSCUR || {}).l || []).map(x => x[0]).join(',')));
      r.push('header items: ' + await p.evaluate(() => [...document.querySelectorAll('#ssg .g-r-it .g-l')].map(x => x.textContent).join('|')));
      if (n === 'desktop') {
        const cart = await p.evaluateHandle(() => [...document.querySelectorAll('#ssg .g-r-it')].find(x => /cart/i.test((x.querySelector('.g-l') || {}).textContent || '')));
        if (cart.asElement()) { await p.evaluate(() => scrollTo(0, 0)); await cart.asElement().hover(); await p.waitForTimeout(2500); r.push('mini cart: ' + await p.evaluate(() => { const m = document.getElementById('ss-mc'); return m ? (m.classList.contains('on') + ' | ' + m.innerText.replace(/\s+/g, ' ').slice(0, 120)) : 'none'; })); await p.screenshot({ path: `page-out/${n}-minicart.jpg`, type: 'jpeg', quality: 70 }); await p.mouse.move(5, 600); await p.waitForTimeout(600); }
      }
      const fav = await p.evaluateHandle((d) => d ? [...document.querySelectorAll('#ssg .g-r-it')].find(x => /favou?rites/i.test((x.querySelector('.g-l') || {}).textContent || '')) : [...document.querySelectorAll('#ssg-bar a')].find(x => /saved/i.test(x.textContent)), n === 'desktop');
      await p.evaluate(() => { window.__fvlog = []; new MutationObserver(ms => ms.forEach(m => { if (m.target.id === 'ss-fv') window.__fvlog.push(Math.round(performance.now()) + ':' + m.target.className + ' ' + (new Error().stack || '').split('\n').slice(1, 4).join(' / ')); })).observe(document.body, { attributes: true, subtree: true, attributeFilter: ['class'] }); ['click', 'touchend', 'mouseover', 'scroll'].forEach(t => document.addEventListener(t, e => window.__fvlog.push(Math.round(performance.now()) + ' ev ' + t + ' ' + (e.target.tagName || 'doc')), true)); });
      if (fav.asElement()) { await fav.asElement().click(); await p.waitForTimeout(2500); r.push('fvlog: ' + JSON.stringify(await p.evaluate(() => (window.__fvlog || []).slice(0, 30)))); r.push('favorites: url=' + p.url() + ' | ' + await p.evaluate(() => { const m = document.getElementById('ss-fv'); return m ? (m.classList.contains('on') + ' | ' + m.innerText.replace(/\s+/g, ' ').slice(0, 120)) : 'none'; })); await p.screenshot({ path: `page-out/${n}-favorites.jpg`, type: 'jpeg', quality: 70 }); }
      fs.writeFileSync(`page-out/${n}-header.txt`, r.join('\n'));
    }
    if (url.includes('solid-sound.net')) { /* what is at given points (debug of stray lines) */
      const pts = n === 'desktop' ? [[720, 437], [300, 470], [1300, 390]] : [[200, 394], [60, 405], [340, 384]];
      fs.writeFileSync(`page-out/${n}-points.txt`, JSON.stringify(await p.evaluate(P => P.map(([x, y]) => { scrollTo(0, 0); return [x, y, document.elementsFromPoint(x, y).slice(0, 6).map(e => e.tagName.toLowerCase() + (e.id ? '#' + e.id : '') + (typeof e.className === 'string' && e.className ? '.' + e.className.trim().split(/\s+/).slice(0, 3).join('.') : '') + ' ' + getComputedStyle(e).position)]; }), pts), null, 1));
    }
    if (n === 'phone' && url.includes('solid-sound.net')) { /* (24.11) mobile menu: language/currency collapsed */
      const bu = await p.$('#ssg .g-burger'); if (bu) { await bu.click(); await p.waitForTimeout(800); await p.evaluate(() => { const s = document.querySelector('#ssg-sheet .s-p'); if (s) s.scrollTop = 99999; }); await p.screenshot({ path: 'page-out/phone-menu.jpg', type: 'jpeg', quality: 70 });
        const d = await p.$$('#ssg-sheet details.s-dd summary'); if (d.length) { await d[d.length - 1].click(); await p.waitForTimeout(400); await p.evaluate(() => { const s = document.querySelector('#ssg-sheet .s-p'); if (s) s.scrollTop = 99999; }); await p.screenshot({ path: 'page-out/phone-menu-open.jpg', type: 'jpeg', quality: 70 }); } }
    }
    if (n === 'desktop') {
      const t = await p.evaluate(() => [...document.querySelectorAll('h1,h2,h3,h4,[class*=price],a.button,button,.btn')].filter(e => e.offsetParent && e.innerText.trim()).map(e => e.tagName + ': ' + e.innerText.trim().replace(/\s+/g, ' ').slice(0, 160)).join('\n'));
      const hd = await p.evaluate(() => 'TITLE: ' + document.title + '\nDESC: ' + ((document.querySelector('meta[name=description]') || {}).content || '') + '\nLD: ' + [...document.querySelectorAll('script[type="application/ld+json"]')].map(x => x.textContent.slice(0, 600)).join('\n    '));
      fs.writeFileSync('page-out/text.txt', url + '\n' + hd + '\n\n' + t + '\n\n---- full text ----\n' + (await p.evaluate(() => document.body.innerText)).slice(0, 60000));
    }
  }
  await b.close();
})();
