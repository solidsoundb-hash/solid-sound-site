// Юр. страницы до/после правок (Юрист, 25.11). Только чтение. Последняя строка .github/legal-look.txt: «<метка> /путь1/,/путь2/».
// Снимки: ПК 1366, телефон 412×780 и 412×700 → legal-out/<метка>/ ; текст страницы → .txt (для сравнения слов).
const { chromium } = require('playwright'); const fs = require('fs');
const [tag, list] = fs.readFileSync('.github/legal-look.txt', 'utf8').trim().split('\n').pop().split(' ');
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36';
(async () => {
  const out = `legal-out/${tag}`; fs.mkdirSync(out, { recursive: true });
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const path of list.split(',')) {
    const name = path.replace(/\W+/g, '-').replace(/^-|-$/g, '') || 'home';
    for (const [d, o] of [['pc', { viewport: { width: 1366, height: 900 } }], ['ph780', { viewport: { width: 412, height: 780 }, isMobile: true }], ['ph700', { viewport: { width: 412, height: 700 }, isMobile: true }]]) {
      const p = await (await b.newContext({ ...o, locale: 'en-US', userAgent: UA })).newPage();
      const r = await p.goto('https://solid-sound.net' + path + '?v=' + tag, { waitUntil: 'networkidle', timeout: 90000 }).catch(e => null);
      await p.waitForTimeout(2000);
      await p.screenshot({ path: `${out}/${name}-${d}.jpg`, type: 'jpeg', quality: 60, fullPage: d === 'pc' || d === 'ph780' }).catch(() => {});
      if (d === 'pc') fs.writeFileSync(`${out}/${name}.txt`, (r ? r.status() : 'ERR') + '\n' + await p.evaluate(() => (document.querySelector('main, #page-content, .l-main') || document.body).innerText).catch(() => ''));
      await p.context().close();
    }
  }
  await b.close();
})();
