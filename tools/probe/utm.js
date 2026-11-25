// Probe (25.11, «Тестировщик»): FREE forms → Brevo with UTM. Pictures of the feedback form (new consent box) on PC + A31,
// then ONE test sign-up on each form from a link with ?utm_source=test (addresses @example.com — no mail is delivered).
// Check afterwards in Brevo: contacts qa-free-<date>@example.com / qa-fb-<date>@example.com have UTM_SOURCE=test.
const { chromium } = require('playwright'); const fs = require('fs');
const OUT = process.env.PROBE_OUT || 'probe-utm'; fs.mkdirSync(OUT, { recursive: true });
const SITE = 'https://solid-sound.net';
const DEV = {
  a31: { viewport: { width: 412, height: 700 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36' },
  pc: { viewport: { width: 1366, height: 768 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' },
};
const log = []; const L = (...a) => { const s = a.join(' '); log.push(s); console.log(s); };
const tag = new Date().toISOString().slice(0, 16).replace(/[-:T]/g, '');
const Q = '?utm_source=test&utm_medium=qa&utm_campaign=qa_' + tag;
(async () => {
  const b = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  for (const [dev, opt] of Object.entries(DEV)) {
    const ctx = await b.newContext({ ...opt, locale: 'en-US' });
    await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    const p = await ctx.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message.slice(0, 140)));
    await p.goto(SITE + '/en/free-demo-feedback/' + Q, { waitUntil: 'domcontentloaded', timeout: 60000 });
    const fb = p.locator('#ss-fb'); await fb.waitFor({ timeout: 30000 });
    await p.waitForTimeout(1500);
    await fb.screenshot({ path: `${OUT}/${dev}_feedback_form.jpg`, type: 'jpeg', quality: 70 });
    const ck = p.locator('#ss-fb input[name=news]');
    L(`${dev}: feedback consent box ${await ck.count() ? 'есть' : 'НЕТ'}; text: ${(await p.locator('#ss-fb .ssfb-ck').textContent().catch(() => '')).trim()}`);
    if (await ck.count()) { const bx = await p.locator('#ss-fb .ssfb-ck').boundingBox(); L(`${dev}: box ${Math.round(bx.width)}×${Math.round(bx.height)}`); }
    if (dev === 'a31') {
      /* feedback test with the tick */
      const f = p.locator('#ss-fb form');
      await f.locator('[name=link]').fill('https://example.com/qa-test-track');
      await f.locator('[name=email]').fill(`qa-fb-${tag}@example.com`);
      await f.locator('[name=name]').fill('QA test - ignore');
      await ck.check();
      await p.waitForTimeout(3500);
      const [r] = await Promise.all([p.waitForResponse(x => x.url().includes('admin-ajax') && x.request().method() === 'POST' && /ss_fb_submit/.test(x.request().postData() || ''), { timeout: 30000 }).catch(() => null), f.locator('button[type=submit]').click()]);
      L(`a31: feedback sent → ${r ? r.status() + ' ' + (await r.text()).slice(0, 120) : 'нет ответа'}`);
      await p.waitForTimeout(1500); await fb.screenshot({ path: `${OUT}/a31_feedback_sent.jpg`, type: 'jpeg', quality: 70 });
      /* /free/ test */
      await p.goto(SITE + '/en/free/' + Q, { waitUntil: 'domcontentloaded', timeout: 60000 });
      const ff = p.locator('#ss-free-form'); await ff.waitFor({ timeout: 30000 });
      await ff.locator('[name=name]').fill('QA test');
      await ff.locator('[name=email]').fill(`qa-free-${tag}@example.com`);
      const role = ff.locator('input[name=role]').first(); if (await role.count()) await role.check({ force: true }).catch(() => {});
      await ff.locator('input[name=consent]').check({ force: true });
      const [r2] = await Promise.all([p.waitForResponse(x => x.url().includes('admin-ajax') && x.request().method() === 'POST' && /ss_free_join/.test(x.request().postData() || ''), { timeout: 30000 }).catch(() => null), ff.locator('button[type=submit], .ssf-btn').first().click()]);
      L(`a31: /free/ sent → ${r2 ? r2.status() + ' ' + (await r2.text()).slice(0, 120) : 'нет ответа'}`);
      await p.waitForTimeout(2500); await p.screenshot({ path: `${OUT}/a31_free_after.jpg`, type: 'jpeg', quality: 60 });
    }
    L(`${dev}: JS errors: ${errs.length ? errs.join(' | ') : 0}`);
    await ctx.close();
  }
  L(`emails: qa-fb-${tag}@example.com, qa-free-${tag}@example.com; campaign qa_${tag}`);
  fs.writeFileSync(`${OUT}/utm.txt`, log.join('\n') + '\n');
  await b.close();
})();
