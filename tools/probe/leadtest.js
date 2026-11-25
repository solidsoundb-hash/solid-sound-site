// Probe (25.11, «Тестировщик»): one TEST request through the studio calculator form (admin-post ss_studio_request),
// to check that it lands in the admin list «Requests» (modules/site-leads.php). The e-mail still goes to the shop.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-leadtest'; fs.mkdirSync(OUT, { recursive: true });
const S = 'https://solid-sound.net'; const log = []; const L = (...a) => { const s = a.join(' '); log.push(s); console.log(s); };
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await b.newContext({ viewport: { width: 1366, height: 768 }, locale: 'en-US', userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' });
  const p = await ctx.newPage();
  try {
    const r0 = await p.goto(S + '/studio-calculator/', { waitUntil: 'domcontentloaded', timeout: 90000 }); L('calculator page:', r0 && r0.status());
    const r = await ctx.request.post(S + '/wp-admin/admin-post.php', { form: { action: 'ss_studio_request', name: 'TEST QA robot', email: 'qa-leads@example.com',
      service: 'TEST', links: '-', quote: 'TEST', message: 'TEST request from the QA robot - please ignore (checks the admin list Requests).', website: '' },
      headers: { Referer: S + '/studio-calculator/' }, maxRedirects: 0, timeout: 90000 });
    L('submit:', r.status(), r.headers()['location'] || '', (await r.text()).slice(0, 200).replace(/\s+/g, ' '));
  } catch (e) { L('error:', e.message.split('\n')[0]); }
  fs.writeFileSync(OUT + '/log.txt', log.join('\n') + '\n'); await b.close();
})();
