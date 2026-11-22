// Probe (22.11): the raw HTML of the Brevo tracker and the «Official Facebook Pixel» plugin code (owner: Brevo only after
// «Accept», one Meta plugin). Prints the matching <script>/<noscript> tags from the server HTML (before any JS runs).
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-out'; fs.mkdirSync(OUT, { recursive: true });
const RE = /sib|brevo|sendinblue|official-facebook|facebook_signal|fbq\(|facebook-for-woocommerce|fbevents/i;
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await b.newContext({ userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' });
  const p = await ctx.newPage(); const out = [];
  for (const u of ['/en/faq/', '/product/infinity/']) {
    const r = await p.goto('https://solid-sound.net' + u + '?nc=' + Date.now(), { waitUntil: 'domcontentloaded', timeout: 90000 }).catch(() => null);
    const h = r ? await r.text() : ''; out.push('==== ' + u + ' ' + (r ? r.status() : 0) + ' ' + h.length);
    for (const m of h.matchAll(/<(script|noscript)\b[^>]*>[\s\S]*?<\/\1>|<script\b[^>]*\/>/gi)) if (RE.test(m[0])) out.push(m[0].replace(/key=[a-z0-9]+/gi, 'key=…').slice(0, 900), '----');
  }
  fs.writeFileSync(OUT + '/inl.txt', out.join('\n') + '\n'); console.log(out.join('\n')); await b.close();
})();
