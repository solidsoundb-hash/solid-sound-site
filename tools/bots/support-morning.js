// SUPPORT-MORNING — robot of «Продажи и поддержка» (owner 25.11.2026: «у каждой роли свой рой роботов»).
// Every morning: new orders and their state (paid / not paid → needs a follow-up), cart & checkout views without an order
// (Solid Stats, /wp-json/ss/v1/stats), new forum topics without a reply → reports/support/morning.md + .json.
// PUBLIC REPO: no names, e-mails, phones, addresses — only order number, status, items, total, country (scrub() below).
// Logs in like a person with the admin test account from GitHub Secrets (same way as tools/bots/stats-daily.js).
'use strict';
const fs = require('fs');
const S = 'https://solid-sound.net';
const OUT = 'reports/support';
const HOURS = +(process.env.HOURS || 24);

// ---- pure helpers (checked by exam.js) ----
const scrub = (t) => String(t || '')
  .replace(/[\w.+-]+@[\w-]+(\.[\w-]+)+/g, '[email]')
  .replace(/\+?\d[\d\s().-]{7,}\d/g, '[phone]')
  .replace(/https?:\/\/\S+/g, '[link]')
  .replace(/\s+/g, ' ').trim().slice(0, 120);
// what support must do with an order
const action = (status) => ({
  pending: 'не оплачен — напомнить (шаблон 19, письмо корзины)', failed: 'оплата не прошла — написать (шаблон 19)',
  'on-hold': 'ждёт подтверждения оплаты — проверить', processing: 'оплачен — проверить, что скачал', completed: 'оплачен — через 7 дней просьба об отзыве',
  cancelled: 'отменён — узнать причину', refunded: 'возврат — проверить причину', 'checkout-draft': 'бросил оформление — напомнить',
}[status] || 'проверить вручную');
const urgent = (status) => ['pending', 'failed', 'on-hold', 'checkout-draft'].includes(status);
// topic address = /forum/<section>/<topic>/ (not a section, profile, tag or service page)
const isTopicUrl = (u) => /\/forum\/(?!participant|profile|members|recent|tag|search|register|login|account)[^/?#]+\/[^/?#]+\/?$/.test(String(u || '').replace(/^https?:\/\/[^/]+(\/[a-z]{2}(?=\/))?/, ''));
const isQuestion = (title) => /\?|^(how|why|what|where|which|can|does|is|help|wie|comment|cómo|как|чому|чем|що)\b/i.test(String(title || '').trim());

if (require.main !== module) { module.exports = { scrub, action, urgent, isQuestion, isTopicUrl }; return; }

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const { chromium } = require('playwright');
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await browser.newContext({ locale: 'en-US', userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' });
  const page = await ctx.newPage();
  const since = new Date(Date.now() - HOURS * 3600e3).toISOString().slice(0, 19);
  const R = { updated: new Date().toISOString(), hours: HOURS, orders: [], funnel: null, forum: [], errors: [] };
  try {
    let resp = null;
    for (let i = 0; i < 4; i++) { // hosting answers 429 when many robots run at once
      resp = await page.goto(S + '/en/my-account/?ss_tab=login', { waitUntil: 'domcontentloaded', timeout: 120000 });
      if (resp && resp.status() < 400 && await page.locator('#username:visible').count()) break;
      await page.waitForTimeout(60000);
    }
    if (!(await page.locator('#username:visible').count())) throw new Error(`нет формы входа (HTTP ${resp && resp.status()})`);
    const form = page.locator('form.login').filter({ has: page.locator('#username:visible') }).first();
    await form.locator('#username').fill(process.env.TEST_ADMIN_USER || '');
    await form.locator('#password').fill(process.env.TEST_ADMIN_PASS || '');
    await Promise.all([page.waitForNavigation({ timeout: 120000 }).catch(() => {}), form.locator('button[name=login]').click()]);
    const nonce = (await (await ctx.request.get(S + '/wp-admin/admin-ajax.php?action=rest-nonce')).text()).trim();
    if (!/^[a-f0-9]{6,}$/.test(nonce)) throw new Error('вход не удался (нет ключа REST)');
    const H = { headers: { 'X-WP-Nonce': nonce } };

    // 1. orders (all statuses, incl. checkout drafts) — only non-personal fields are kept
    const o = await ctx.request.get(`${S}/wp-json/wc/v3/orders?after=${since}&per_page=50&status=any&orderby=date&order=desc`, H);
    if (o.status() === 200) {
      for (const x of await o.json()) R.orders.push({ id: x.id, status: x.status, total: x.total, currency: x.currency, country: (x.billing || {}).country || '',
        items: (x.line_items || []).map(i => scrub(i.name)).join(', '), method: x.payment_method_title || '', date: x.date_created });
    } else R.errors.push('заказы: ответ ' + o.status());

    // 2. carts without an order — page views from Solid Stats (true guest carts are not stored by WooCommerce)
    const s = await ctx.request.get(S + '/wp-json/ss/v1/stats?days=2', H);
    if (s.status() === 200) {
      const d = await s.json(); const day = Object.keys(d.days || {}).sort().pop(); const x = (d.days || {})[day] || {};
      const sum = (re) => Object.entries(x.p || {}).reduce((n, [p, v]) => n + (re.test(p) ? v : 0), 0);
      R.funnel = { day, product: sum(/\/product\//), cart: sum(/\/cart\/?$/), checkout: sum(/\/checkout\/?$/), thanks: sum(/order-received/), orders: ((d.orders || {})[day] || {}).n || 0 };
    } else R.errors.push('статистика: ответ ' + s.status());
  } catch (e) { R.errors.push(e.message.split('\n')[0]); }

  // 3. forum: topics on the main forum page (public, titles only — no nicknames); "new" = not in the previous report
  let prev = new Set();
  try { prev = new Set((JSON.parse(fs.readFileSync(OUT + '/morning.json', 'utf8')).forum || []).map(f => f.url)); } catch (e) {}
  try {
    let st = 0;
    for (let i = 0; i < 3; i++) { const r = await page.goto(S + '/en/forum/', { waitUntil: 'domcontentloaded', timeout: 90000 }); st = r ? r.status() : 0; if (st < 400) break; await page.waitForTimeout(45000); }
    if (st >= 400) throw new Error('страница форума ответила ' + st);
    const t = await page.$$eval('a[href*="/forum/"]', as => as.map(a => ({ t: a.textContent.trim(), h: a.getAttribute('href') || '' })));
    const seen = new Set();
    for (const a of t) {
      const u = a.h.replace(/[?#].*$/, '');
      if (!isTopicUrl(u) || a.t.length < 6 || seen.has(u)) continue; seen.add(u);
      R.forum.push({ title: scrub(a.t), url: u, question: isQuestion(a.t), fresh: prev.size > 0 && !prev.has(u) });
      if (R.forum.length >= 15) break;
    }
  } catch (e) { R.errors.push('форум: ' + e.message.split('\n')[0]); }
  await browser.close();

  // ---- report (Russian, for the role) ----
  const L = [`# Утренняя сводка поддержки — ${R.updated.slice(0, 16).replace('T', ' ')} UTC (за ${HOURS} ч)`, ''];
  const todo = R.orders.filter(x => urgent(x.status));
  L.push(`**Сделать сегодня:** ${todo.length ? todo.length + ' заказ(ов) без оплаты — написать клиенту' : 'неоплаченных заказов нет'}` +
    `${R.forum.some(f => f.fresh) ? '; новые темы на форуме — ответить' : ''}.`, '');
  L.push('## Заказы', R.orders.length ? '| № | Статус | Что делать | Товары | Сумма | Страна | Оплата |' : '_Новых заказов нет._');
  if (R.orders.length) { L.push('|---|---|---|---|---|---|---|'); for (const x of R.orders) L.push(`| #${x.id} | ${x.status} | ${action(x.status)} | ${x.items} | ${x.total} ${x.currency} | ${x.country} | ${x.method} |`); }
  L.push('', '## Корзина без заказа (Solid Stats, последний день)');
  L.push(R.funnel ? `${R.funnel.day}: товар ${R.funnel.product} → корзина ${R.funnel.cart} → оформление ${R.funnel.checkout} → «спасибо» ${R.funnel.thanks}; заказов ${R.funnel.orders}.` +
    (R.funnel.checkout > R.funnel.orders ? ' ⚠ дошли до оформления, но не купили — вероятно, мешает оплата.' : '') : '_нет данных_');
  L.push('', `## Форум — темы (${R.forum.length}, новых ${R.forum.filter(f => f.fresh).length})`, R.forum.length ? R.forum.map(f => `- ${f.fresh ? '🆕 ' : ''}${f.question ? '❓ ' : ''}${f.title} — ${f.url}`).join('\n') : '_тем не найдено_');
  L.push('', '## Формы', '_Заявки студии и формы контактов приходят только на почту (на сайте не хранятся) — робот их не видит._');
  if (R.errors.length) L.push('', '## Ошибки робота', ...R.errors.map(e => '- ' + e));
  const md = L.join('\n') + '\n';
  if (/[\w.+-]+@[\w-]+\.[a-z]{2,}/i.test(md.replace(/actions@users\.noreply\.github\.com/g, ''))) { console.log('STOP: в отчёте нашлась почта — не сохраняю'); process.exit(1); }
  fs.writeFileSync(OUT + '/morning.md', md);
  fs.writeFileSync(OUT + '/morning.json', JSON.stringify(R, null, 1));
  console.log(md);
  process.exit(R.errors.length && !R.orders.length && !R.funnel ? 1 : 0);
})();
