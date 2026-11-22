// FORMS (owner 21.11.2026): do the site's forms work? Contact, free track feedback, «Get your set heard», studio request
// (calculator), exclusive license request (product page).
// Normal run (weekly): the form is there, fields and button visible, an empty send is stopped by the form (no blank
// letters), the address it sends to answers (no 404/500), anti-spam field present. Nothing is sent.
// Send run (only by command: a line «<date> forms-send» in .github/sitebots.txt): fills every form with an obvious
// «TEST robot — ignore» message (e-mail on the reserved example.com domain) and checks the site confirms it
// (?sent=1 / thank-you text). The owner gets these test letters — that IS the proof they arrive.
'use strict';
const { chromium } = require('playwright');
const { BASE } = require('../bots/lib');
const SEND = process.env.FORMS_SEND === '1';
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const FORMS = [
  ['Контакты', '/en/contacts/', 'form.w-form, form[class*="cform"], .w-form form, form'],
  ['Бесплатный разбор трека', '/en/free-demo-feedback/', 'form'],
  ['Get your set heard', '/en/get-heard/', '#ssgh-form form, form[action*="admin-post"], form'],
  ['Заявка на услугу (калькулятор)', '/studio-calculator/?s=mixing', 'form[action*="admin-post"], form'],
  ['Эксклюзивная лицензия (товар)', '/product/infinity/', 'form[action*="admin-post"] input[name="action"][value="ss_excl_request"]'],
];
const TEST = { name: 'TEST robot', email: 'robot-test@example.com', text: 'TEST robot — automatic form check, please ignore. Solid Sound site bots.', url: 'https://soundcloud.com/test' };

module.exports = async function forms() {
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 }, userAgent: UA, locale: 'en-US' });
  await ctx.addCookies([{ name: 'ss_consent', value: 'no', domain: 'solid-sound.net', path: '/' }]);
  const rows = []; let broken = 0;
  for (const [name, path, sel] of FORMS) {
    const p = await ctx.newPage(); const R = []; let ok = true;
    try {
      await p.goto(BASE + path, { waitUntil: 'networkidle', timeout: 60000 }).catch(() => {}); await p.waitForTimeout(2500);
      if (/Захищена сторінка/.test(await p.title())) { rows.push([name, path, '⏸', 'капча хостинга — не проверено']); await p.close(); continue; }
      /* the page's own form: not the header search (#ssg) / any search form — the one with the most fields */
      const pick = async () => { const all = p.locator(sel).locator('xpath=self::form|ancestor-or-self::form').filter({ hasNot: p.locator('input[type=search], input[name=s]') });
        let best = null, bn = -1; for (const f of await all.all()) { if (await f.evaluate(e => !!e.closest('#ssg, #ssg-sheet, header, [role=search]'))) continue; const n = await f.locator('input:not([type=hidden]), textarea, select').count(); if (n > bn) { bn = n; best = f; } } return best || p.locator(sel).first(); };
      let form = await pick();
      if (!await form.count()) { rows.push([name, path, '🔴', 'формы на странице нет']); broken++; await p.close(); continue; }
      const fields = await form.locator('input:not([type=hidden]):not([name=website]), textarea, select').count();
      const vis = await form.locator('input:visible, textarea:visible, select:visible').count();
      const btn = form.locator('button[type=submit], input[type=submit], button:not([type])').first(); const hasBtn = await btn.count();
      const action = await form.getAttribute('action').catch(() => null);
      const hp = await form.locator('input[name="website"], input[name*="honeypot" i], .w-form-row.for_agreement, input[name="us_form_cform_hp"]').count();
      R.push(`полей ${fields} (видно ${vis}), кнопка ${hasBtn ? 'есть' : 'НЕТ'}${hp ? ', защита от спама есть' : ''}`);
      if (!vis || !hasBtn) { ok = false; R.push(vis ? '' : 'поля не видны (форма спрятана в окне? открыть кнопкой)'); }
      if (action && /admin-post|admin-ajax/.test(action)) { const r = await p.request.get(new URL(action, BASE + path).href, { maxRedirects: 0 }).catch(() => null); if (r && r.status() >= 500) { ok = false; R.push('адрес отправки отвечает ' + r.status()); } }
      // empty send must be stopped (browser validation or the form's own errors)
      if (vis && hasBtn) {
        const before = p.url(); await btn.click({ timeout: 5000 }).catch(() => {}); await p.waitForTimeout(2500);
        const invalid = await form.locator(':invalid, .w-form-row.check_wrong, .error, [aria-invalid="true"]').count().catch(() => 0);
        const moved = p.url() !== before && /sent|excl=sent|success/i.test(p.url());
        if (moved) { ok = false; R.push('пустая форма УШЛА — придут пустые письма'); } else R.push(invalid ? 'пустую не отправляет ✅' : 'пустую не отправляет (без подсказки, что заполнить)');
      }
      if (SEND && vis && hasBtn) {
        await p.goto(BASE + path, { waitUntil: 'networkidle', timeout: 60000 }).catch(() => {}); await p.waitForTimeout(2000);
        form = await pick();
        for (const el of await form.locator('input:visible, textarea:visible, select:visible').all()) {
          const t = (await el.getAttribute('type') || '').toLowerCase(), n = ((await el.getAttribute('name')) || '') + ' ' + ((await el.getAttribute('placeholder')) || '');
          if (await el.evaluate(e => e.tagName) === 'SELECT') { await el.selectOption({ index: 1 }).catch(() => {}); continue; }
          if (t === 'checkbox' || t === 'radio') { await el.check().catch(() => {}); continue; }
          if (t === 'file' || t === 'submit' || t === 'button') continue;
          const v = t === 'email' || /mail/i.test(n) ? TEST.email : /url|link|soundcloud/i.test(n) || t === 'url' ? TEST.url : /phone|tel/i.test(n) || t === 'tel' ? '+33000000000' : t === 'number' ? '1' : /name/i.test(n) ? TEST.name : TEST.text;
          await el.fill(v).catch(() => {});
        }
        const b = form.locator('button[type=submit], input[type=submit], button:not([type])').first();
        await Promise.all([p.waitForLoadState('networkidle').catch(() => {}), b.click({ timeout: 5000 }).catch(() => {})]); await p.waitForTimeout(4000);
        const done = /[?&](sent=1|excl=sent|sent=ok)/.test(p.url()) || await p.locator('text=/thank|we got|received|sent|message has been/i').count();
        R.push(done ? '📨 тестовое письмо отправлено, сайт подтвердил' : '🔴 после отправки нет подтверждения'); if (!done) ok = false;
      }
    } catch (e) { ok = false; R.push('ошибка: ' + e.message.slice(0, 80)); }
    if (!ok) broken++; rows.push([name, path, ok ? '✅' : '🔴', R.filter(Boolean).join('; ')]); await p.close();
  }
  await browser.close();
  const md = `## 📝 Формы: работают ли\n\n${SEND ? '**Режим отправки:** формы отправлены с пометкой «TEST robot» — на почту владельца пришли тестовые письма (это и есть проверка, что доходят).' : 'Обычный режим: формы проверены без отправки. Отправить тестовые письма — строка `<дата> forms-send` в `.github/sitebots.txt`.'}\n\n| | Форма | Страница | Итог |\n|---|---|---|---|\n` + rows.map(r => `| ${r[2]} | ${r[0]} | ${r[1]} | ${r[3]} |`).join('\n') + '\n';
  return { md, metrics: { forms_broken: broken }, alerts: broken ? [`Формы: ${broken} не работают`] : [] };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
