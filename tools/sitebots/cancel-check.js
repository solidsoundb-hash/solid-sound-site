// CANCEL AN ORDER check for the night buyer (26.11.2026, «Задачи» → Тестировщик). Two-step withdrawal (Directive 2023/2673):
//  1) guest: /en/cancel-order/ has «Withdraw from contract here»; a wrong order number gives the error (nothing is sent — the
//     form only mails the e-mail of a real order with that number);
//  2) admin (secrets TEST_ADMIN_USER / TEST_ADMIN_PASS): ?ss_cxo_preview=1 shows step 2 «Confirm withdrawal here» with made-up
//     data (no real order, the form sends nothing) — snapshot on a computer and on the Samsung A31.
// The robot never confirms a real withdrawal: it has no order (the night buyer never pays) and must not mail anyone.
// Returns { md, alerts }; a broken cancel form is an alert in the report, not the «purchase broken» alarm.
'use strict';
const fs = require('fs'); const path = require('path');
const S = process.env.SITE || 'https://solid-sound.net';
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const A31 = { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' };

async function goto(page, url) {
  let r = null;
  for (let i = 0; i < 3; i++) { // the hosting answers 429/503 when many robots run at once
    r = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 120000 }).catch(() => null);
    if (r && r.status() < 400) return r;
    await page.waitForTimeout(45000);
  }
  return r;
}

module.exports = async function cancelCheck(OUT) {
  const { chromium } = require('playwright');
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const L = []; const alerts = [];
  try {
    // 1. guest: step 1 + a wrong order → error, no step 2
    const g = await browser.newContext({ locale: 'en-US', userAgent: UA, viewport: { width: 1366, height: 768 } });
    const p = await g.newPage();
    const r = await goto(p, S + '/en/cancel-order/');
    const b1 = p.locator('.ss-cxo button[type=submit]', { hasText: /withdraw from contract here/i });
    if (!r || r.status() >= 400) L.push(`⚠️ /en/cancel-order/ не открылась (HTTP ${r && r.status()}) — хостинг, не ошибка формы`);
    else if (!(await b1.count())) { L.push('❌ Шаг 1: нет кнопки «Withdraw from contract here»'); alerts.push('Отмена заказа: нет кнопки шага 1'); }
    else {
      await p.fill('.ss-cxo input[name=order]', '1'); await p.fill('.ss-cxo input[name=email]', 'qa-cancel@example.com');
      await Promise.all([p.waitForNavigation({ timeout: 120000 }).catch(() => {}), b1.click()]);
      const err = await p.locator('.ss-cxo .msg.err').count(); const conf = await p.locator('.ss-cxo button', { hasText: /confirm withdrawal here/i }).count();
      if (err && !conf) L.push('✅ Шаг 1: кнопка есть; чужой номер заказа → ошибка, шаг 2 не открылся, письма нет');
      else { L.push(`❌ Шаг 1: неверный заказ — ошибка ${err ? 'есть' : 'НЕТ'}, шаг 2 ${conf ? 'ОТКРЫЛСЯ' : 'нет'}`); alerts.push('Отмена заказа: неверный заказ обработан не так'); }
    }
    await g.close();

    // 2. admin preview of step 2 (made-up data) on PC and A31
    if (!process.env.TEST_ADMIN_USER) { L.push('— Шаг 2: нет секретов TEST_ADMIN_* — не снимал'); }
    else {
      const a = await browser.newContext({ locale: 'en-US', userAgent: UA });
      const ap = await a.newPage();
      await goto(ap, S + '/en/my-account/?ss_tab=login');
      const form = ap.locator('form.login').filter({ has: ap.locator('#username:visible') }).first();
      if (!(await form.count())) L.push('⚠️ Шаг 2: нет формы входа (хостинг/капча) — не снимал');
      else {
        await form.locator('#username').fill(process.env.TEST_ADMIN_USER || '');
        await form.locator('#password').fill(process.env.TEST_ADMIN_PASS || '');
        await Promise.all([ap.waitForNavigation({ timeout: 120000 }).catch(() => {}), form.locator('button[name=login]').click()]);
        const cookies = await a.cookies();
        for (const [name, opt] of [['ПК 1366', { viewport: { width: 1366, height: 768 }, userAgent: UA }], ['A31 412×780', A31]]) {
          const c = await browser.newContext({ locale: 'en-US', ...opt }); await c.addCookies(cookies);
          const q = await c.newPage(); await goto(q, S + '/en/cancel-order/?ss_cxo_preview=1');
          const btn = q.locator('.ss-cxo button', { hasText: /confirm withdrawal here/i });
          const file = 'cancel-step2-' + (name.startsWith('ПК') ? 'pc' : 'a31') + '.jpg';
          if (await btn.count()) {
            await btn.first().scrollIntoViewIfNeeded().catch(() => {});
            const bb = await btn.first().boundingBox(); const vw = opt.viewport.width;
            const rad = await btn.first().evaluate(e => getComputedStyle(e).borderRadius);
            await q.screenshot({ path: path.join(OUT, file), type: 'jpeg', quality: 60 });
            const fits = bb && bb.x >= 0 && bb.x + bb.width <= vw + 1;
            L.push(`${fits ? '✅' : '❌'} Шаг 2 (${name}): «Confirm withdrawal here» видна${fits ? '' : ' — НЕ влезает по ширине'}, радиус ${rad} — [снимок](${file})`);
            if (!fits) alerts.push(`Отмена заказа: кнопка шага 2 не влезает (${name})`);
          } else { L.push(`❌ Шаг 2 (${name}): нет «Confirm withdrawal here» (вход админа не прошёл?)`); alerts.push(`Отмена заказа: шаг 2 не показался (${name})`); }
          await c.close();
        }
      }
      await a.close();
    }
  } catch (e) { L.push('⚠️ проверка отмены упала: ' + e.message.split('\n')[0]); }
  await browser.close();
  return { md: '## ↩️ Отмена заказа (2 шага)\n\n' + L.map(x => '- ' + x).join('\n') + '\n', alerts };
};
