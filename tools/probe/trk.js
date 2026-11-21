// Probe (22.11, robot «Cookie/GDPR» found data going to Meta and Google before consent and after «Reject»):
// which requests exactly, which script sent them, and the consent state each one carries. Read-only, new EU visitor.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
const RE = /facebook\.com\/tr|facebook\.net|google-analytics\.com|analytics\.google\.com|googletagmanager\.com|\/g\/collect|doubleclick|googleadservices|google\.com\/pagead|tiktok|clarity|hotjar/;
const log = []; const L = (...a) => log.push(a.map(x => typeof x === 'string' ? x : JSON.stringify(x)).join(' '));
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const choice of ['none', 'reject']) {
    const ctx = await b.newContext({ viewport: { width: 1280, height: 900 }, locale: 'fr-FR', timezoneId: 'Europe/Paris', userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' });
    const p = await ctx.newPage(); let phase = 'before';
    p.on('request', r => { const u = r.url(); if (!RE.test(u)) return; const x = new URL(u); const q = x.searchParams;
      const ini = r.frame() ? '' : ''; L(choice, phase, r.resourceType(), x.host + x.pathname.slice(0, 40), 'ev=' + (q.get('ev') || q.get('en') || ''), 'gcs=' + (q.get('gcs') || ''), 'gcd=' + (q.get('gcd') || ''), 'id=' + (q.get('id') || q.get('tid') || '').slice(0, 20)); });
    await p.goto('https://solid-sound.net/en/?nc=' + Date.now(), { waitUntil: 'networkidle', timeout: 90000 }).catch(() => {}); await p.waitForTimeout(5000);
    if (choice === 'reject') { phase = 'after-reject'; await p.click('#ss-cc .ss-cc-no').catch(e => L('no button', e.message.slice(0, 80))); await p.waitForTimeout(1000);
      await p.goto('https://solid-sound.net/product/infinity/?nc=' + Date.now(), { waitUntil: 'networkidle', timeout: 90000 }).catch(() => {}); await p.waitForTimeout(5000); }
    L(choice, 'scripts', await p.evaluate(() => [...document.scripts].map(s => s.src || ((s.textContent.match(/fbq\('init'|gtag\('config'|gtag\('consent'|GTM-|G-[A-Z0-9]{6,}|AW-\d+|pixelyoursite|facebook-for-woocommerce|googlesitekit|google-listings|wcgai|gtag\('js'/g) || []).join(',') ? 'INLINE:' + (s.textContent.match(/fbq\('init'|gtag\('config'|gtag\('consent'|GTM-|G-[A-Z0-9]{6,}|AW-\d+|pixelyoursite|facebook-for-woocommerce|googlesitekit|google-listings|wcgai|gtag\('js'/g) || []).join(',') + ' id=' + (s.id || '') : '')).filter(Boolean)));
    await ctx.close();
  }
  fs.writeFileSync(OUT + '/trk.txt', log.join('\n') + '\n'); console.log(log.join('\n')); await b.close();
})();
