// Probe (22.11, «Копирайтер»): all visible English texts of the pages on the way to a purchase — for the translation dictionaries.
// For each page: the text nodes after scripts ran (+ placeholders, aria-label, title, alt, button values) and which of them
// are already in the server HTML (srv:1 → can be translated on the server; srv:0 → printed by scripts).
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
const S = 'https://solid-sound.net';
const PAGES = ['/en/all-products/', '/en/services-prices/', '/studio-calculator/?s=mixing', '/studio-calculator/?s=mastering', '/en/free/', '/en/sell/', '/en/faq/', '/en/why-solid-sound/', '/en/get-heard/',
  '/product/pitch/', '/en/cart/', '/en/checkout/', '/en/my-account/', '/en/terms/', '/refund_returns/', '/license/', '/privacy-policy/', '/en/legal-notice/', '/en/about/', '/contacts/'];
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await b.newContext({ viewport: { width: 1366, height: 768 }, locale: 'en-US', userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage(); const all = {}; let n = 0;
  for (const u of PAGES) {
    if (u === '/en/cart/' && !n++) { await p.goto(S + '/product/pitch/', { waitUntil: 'domcontentloaded', timeout: 90000 }).catch(() => {}); await p.click('button[name=add-to-cart], .single_add_to_cart_button', { timeout: 8000 }).catch(() => {}); await p.waitForTimeout(3000); }
    let html = '';
    const resp = await p.goto(S + u, { waitUntil: 'networkidle', timeout: 120000 }).catch(() => null);
    try { html = resp ? await resp.text() : ''; } catch (e) {}
    await p.waitForTimeout(2500);
    if (u === '/en/my-account/') { for (const t of ['Create account', 'Become a vendor']) { await p.click('text=' + t, { timeout: 3000 }).catch(() => {}); await p.waitForTimeout(600); } }
    const r = await p.evaluate(() => {
      const out = new Map(); const SK = { SCRIPT: 1, STYLE: 1, NOSCRIPT: 1, TEXTAREA: 1, CODE: 1, svg: 1 };
      const add = (t, kind) => { t = (t || '').replace(/\s+/g, ' ').trim(); if (t.length < 2 || !/[A-Za-z]{2}/.test(t)) return; if (!out.has(t)) out.set(t, kind); };
      const w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT); let x;
      while ((x = w.nextNode())) { const pe = x.parentElement; if (!pe || SK[pe.nodeName] || pe.closest('#wpadminbar,#tidio-chat,script,style')) continue; add(x.nodeValue, 't'); }
      document.querySelectorAll('[placeholder],[aria-label],[title],img[alt],input[type=submit][value],button[value]').forEach(e => { ['placeholder', 'aria-label', 'title', 'alt'].forEach(a => e.hasAttribute(a) && add(e.getAttribute(a), a)); if (e.matches('input[type=submit]')) add(e.value, 'value'); });
      return { title: document.title, status: document.documentElement.lang, items: [...out.entries()] };
    });
    const flat = html.replace(/<(script|style)[\s\S]*?<\/\1>/gi, ' ').replace(/&#8217;|&rsquo;/g, '’').replace(/&amp;/g, '&').replace(/&nbsp;|&#160;/g, ' ').replace(/\s+/g, ' ');
    all[u] = { title: r.title, n: r.items.length, items: r.items.map(([t, k]) => ({ t, k, srv: flat.includes(t) ? 1 : 0 })) };
    console.log(u, r.title, r.items.length);
  }
  fs.writeFileSync(OUT + '/texts.json', JSON.stringify(all, null, 1));
  await b.close();
})();
