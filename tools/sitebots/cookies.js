// COOKIES & GDPR (owner 21.11.2026): a new EU visitor, no choice made yet. Before consent no marketing data may leave the
// browser (Meta Pixel, Google Analytics/Ads, TikTok, Hotjar, Clarity…). Then: «Reject non-essential» → still nothing;
// «Accept all» → the pixel works (so ads are measured). Also: the banner is shown, «Cookie settings» reopens it, and which
// cookies are set before consent. Banner code: custom.php ss_consent_* (cookie ss_consent = yes/no).
'use strict';
const { chromium } = require('playwright');
const { BASE } = require('../bots/lib');
const PAGES = ['/en/', '/product/infinity/', '/en/all-products/'];
// requests that SEND data (loading a script file alone is not tracking; the hit is)
const TRACK = [
  ['Meta Pixel', /facebook\.com\/tr|facebook\.com\/privacy_sandbox|connect\.facebook\.net\/signals/],
  ['Google Analytics', /google-analytics\.com\/(g\/)?collect|analytics\.google\.com\/g\/collect|\/g\/collect\?/],
  ['Google Ads', /googleadservices\.com\/pagead|google\.com\/pagead|doubleclick\.net/],
  ['TikTok', /analytics\.tiktok\.com\/api/], ['Hotjar', /hotjar\.(com|io)/], ['Clarity', /clarity\.ms\/collect/], ['Brevo', /sibautomation\.com|in-automate\.brevo\.com|cdn\.brevo\.com\/js\/sdk-loader/], ['LinkedIn', /px\.ads\.linkedin/],
];
const SCRIPTS = [['Meta Pixel (скрипт)', /connect\.facebook\.net\/.*fbevents/], ['Google tag (скрипт)', /googletagmanager\.com\/gtag\/js/], ['TikTok (скрипт)', /analytics\.tiktok\.com\/i18n/]];
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';

async function visit(browser, choice) {
  const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 }, userAgent: UA, locale: 'fr-FR', timezoneId: 'Europe/Paris' });
  const p = await ctx.newPage(); const hits = []; let phase = 'до выбора';
  p.on('request', r => { const u = r.url(); for (const [n, re] of TRACK) if (re.test(u)) hits.push([phase, n === 'Google Analytics' && /[?&]gcs=G100/.test(u) ? 'GA без cookie (режим согласия Google — допустимо)' : n, u.slice(0, 90)]); for (const [n, re] of SCRIPTS) if (re.test(u)) hits.push([phase, n, '']); });
  const out = { banner: null, reopen: null, cookies: [] };
  for (const [i, path] of PAGES.entries()) {
    await p.goto(BASE + path, { waitUntil: 'networkidle', timeout: 60000 }).catch(() => {}); await p.waitForTimeout(3500);
    if (/Захищена сторінка/.test(await p.title())) { out.blocked = true; break; }
    if (i === 0) {
      out.banner = await p.locator('#ss-cc:not([hidden])').isVisible().catch(() => false);
      out.cookies = (await ctx.cookies()).filter(c => !/^(ss_|wp|woocommerce|pll_|PHPSESSID|wordpress)/i.test(c.name)).map(c => c.name + ' (' + c.domain + ')');
      if (choice && out.banner) { phase = choice === 'yes' ? 'после «Accept all»' : 'после «Reject»'; await p.click(choice === 'yes' ? '#ss-cc .ss-cc-yes' : '#ss-cc .ss-cc-no').catch(() => {}); await p.waitForTimeout(3000); }
    }
  }
  if (choice === 'no') { await p.click('.ss-cc-open').catch(() => {}); await p.waitForTimeout(800); out.reopen = await p.locator('#ss-cc:not([hidden])').isVisible().catch(() => false); }
  await ctx.close(); return { ...out, hits };
}

module.exports = async function cookies() {
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const none = await visit(browser, null), no = await visit(browser, 'no'), yes = await visit(browser, 'yes'); await browser.close();
  if (none.blocked) return { md: '## 🍪 Cookie и GDPR\n\n⏸ Хостинг показал капчу — не проверено.\n', metrics: {}, alerts: [] };
  const ok = n => /режим согласия/.test(n);
  const sent = (r, ph) => [...new Set(r.hits.filter(h => h[0] === ph && h[2] && !ok(h[1])).map(h => h[1]))];
  const pings = [...new Set([none, no].flatMap(r => r.hits.filter(h => ok(h[1])).map(h => h[1])))];
  const scr = (r, ph) => [...new Set(r.hits.filter(h => h[0] === ph && !h[2]).map(h => h[1]))];
  const before = [...new Set([...sent(none, 'до выбора'), ...sent(no, 'до выбора'), ...sent(yes, 'до выбора')])];
  const afterNo = sent(no, 'после «Reject»'), afterYes = sent(yes, 'после «Accept all»');
  const R = []; const alerts = [];
  R.push([none.banner ? '✅' : '🔴', 'Баннер согласия показывается новому посетителю', none.banner ? 'да' : 'НЕТ']);
  R.push([before.length ? '🔴' : '✅', 'До выбора данные не уходят', before.length ? 'уходят: ' + before.join(', ') : 'ничего не уходит' + (scr(none, 'до выбора').length ? ` (скрипты грузятся: ${scr(none, 'до выбора').join(', ')} — это допустимо, если они ждут согласия; лучше грузить после)` : '')]);
  R.push([afterNo.length ? '🔴' : '✅', '«Reject non-essential» — ничего не уходит', afterNo.length ? 'всё равно уходят: ' + afterNo.join(', ') : 'да']);
  R.push([afterYes.includes('Meta Pixel') ? '✅' : '🟠', '«Accept all» — пиксель Meta работает', afterYes.length ? 'уходят: ' + afterYes.join(', ') : 'после согласия ничего не ушло — реклама не меряется']);
  R.push([no.reopen ? '✅' : '🟡', '«Cookie settings» в подвале снова открывает выбор', no.reopen ? 'да' : 'нет']);
  if (pings.length) R.push(['🟡', 'Google Analytics до согласия', 'шлёт обезличенные сигналы без cookie (gcs=G100) — так работает режим согласия Google, в ЕС обычно допустимо; строже — грузить GA только после «Accept»']);
  if (none.cookies.length) R.push(['🟡', 'Сторонние cookie до выбора', none.cookies.join(', ')]);
  if (before.length || afterNo.length) alerts.push('GDPR: данные уходят без согласия (' + [...before, ...afterNo].join(', ') + ') — риск штрафа в ЕС');
  if (!none.banner) alerts.push('GDPR: нет баннера согласия');
  const md = '## 🍪 Cookie и GDPR: новый посетитель из ЕС\n\nСтраницы: ' + PAGES.join(', ') + '. Отслеживаются запросы, которые отправляют данные (Meta, Google, TikTok, Hotjar, Clarity, LinkedIn).\n\n| | Проверка | Итог |\n|---|---|---|\n' + R.map(r => `| ${r.join(' | ')} |`).join('\n') + '\n';
  return { md, metrics: { gdpr_leaks_before: before.length, gdpr_leaks_reject: afterNo.length, gdpr_banner: none.banner ? 1 : 0 }, alerts };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
