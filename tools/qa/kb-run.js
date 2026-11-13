// Run the common-bug knowledge base (tools/qa/kb.js) on its own, without the full QA bot.
//   node tools/qa/kb-run.js                       -> key pages, phone + computer, prints the findings
//   node tools/qa/kb-run.js /en/ /product/pitch/  -> only these pages
//   node tools/qa/kb-run.js --doc                 -> rewrite docs/qa-knowledge.md from the rules
//   KB_JSON=out.json node tools/qa/kb-run.js      -> also save the findings as JSON
'use strict';
const fs = require('fs');
const path = require('path');
const KB = require('./kb');

if (process.argv.includes('--doc')) {
  fs.writeFileSync(path.join(__dirname, '..', '..', 'docs', 'qa-knowledge.md'), KB.doc());
  console.log('docs/qa-knowledge.md updated,', KB.RULES.length, 'rules');
  process.exit(0);
}

const { chromium } = require('playwright');
const BASE = 'https://solid-sound.net';
const PAGES = process.argv.slice(2).filter(a => a.startsWith('/'));
const LIST = PAGES.length ? PAGES : ['/en/', '/en/all-products/', '/product/intricate/', '/en/services-prices/', '/studio-calculator/', '/en/faq/', '/en/free/', '/en/cart/'];
const DEV = {
  'компьютер': { viewport: { width: 1280, height: 900 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' },
  'телефон': { viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' },
};
const SEV = { critical: '🔴', major: '🟠', minor: '🟡', check: '⚪' };

(async () => {
  const browser = await chromium.launch(process.env.PW_CHANNEL ? { channel: process.env.PW_CHANNEL } : {});
  const all = new Map();
  for (const [devName, o] of Object.entries(DEV)) {
    const ctx = await browser.newContext(o);
    // no analytics / pixels / chats: they are not ours to fix and slow the run
    await ctx.route(/googletagmanager|google-analytics|facebook\.net|connect\.facebook|tidio|clarity\.ms|hotjar|doubleclick/, r => r.abort());
    const page = await ctx.newPage();
    for (const p of LIST) {
      try {
        await page.goto(BASE + p, { waitUntil: 'domcontentloaded', timeout: 60000 });
        await page.waitForLoadState('load', { timeout: 30000 }).catch(() => {});
        await page.waitForTimeout(2500);
        // like a real visitor: answer the cookie banner once (its covering of buttons on the very first screen is reported separately)
        await page.evaluate(() => { const b = document.querySelector('.ss-cc-no'); if (b) b.click(); }).catch(() => {});
        await page.waitForTimeout(300);
        for (const b of await KB.run(page, { url: BASE + p, devName })) {
          const g = all.get(b.sig) || { ...b, where: new Set(), devs: new Set() };
          g.where.add(p); g.devs.add(devName); all.set(b.sig, g);
        }
      } catch (e) { console.log('! не открылась', p, devName, e.message.split('\n')[0]); }
      await page.waitForTimeout(1500); // gentle with the hosting's bot protection
    }
    await ctx.close();
  }
  await browser.close();
  const order = ['critical', 'major', 'minor', 'check'];
  const list = [...all.values()].sort((a, b) => order.indexOf(a.sev) - order.indexOf(b.sev));
  for (const b of list) {
    console.log(`${SEV[b.sev]} [${b.area}] ${b.title}  (${[...b.devs].join(', ')}: ${[...b.where].join(' ')})`);
    if (b.actual) console.log('   ' + b.actual.split('Почему важно:')[0].trim());
    b.steps.slice(1).forEach(s => console.log('   ' + s));
  }
  console.log(`\nИтого: ${list.length} находок на ${LIST.length} страницах × 2 устройства.`);
  if (process.env.KB_JSON) fs.writeFileSync(process.env.KB_JSON, JSON.stringify(list.map(b => ({ ...b, where: [...b.where], devs: [...b.devs] })), null, 1));
})();
