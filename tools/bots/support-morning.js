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
// RSS 2.0 → [{title, link, date}] (wpForo topic feed)
const parseRss = (xml) => (String(xml).match(/<item>[\s\S]*?<\/item>/g) || []).map(it => {
  const g = (t) => { const m = it.match(new RegExp('<' + t + '>(?:<!\\[CDATA\\[)?([\\s\\S]*?)(?:\\]\\]>)?</' + t + '>')); return m ? m[1].trim() : ''; };
  return { title: g('title').replace(/&amp;/g, '&').replace(/&#0?39;|&apos;/g, "'").replace(/&quot;/g, '"'), link: g('link').replace(/[?#].*$/, ''), date: g('pubDate') ? new Date(g('pubDate')).toISOString() : '' };
});
const isQuestion = (title) => /\?|^(how|why|what|where|which|can|does|is|help|wie|comment|cómo|как|чому|чем|що)\b/i.test(String(title || '').trim());

if (require.main !== module) { module.exports = { scrub, action, urgent, isQuestion, parseRss }; return; }

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const { chromium } = require('playwright');
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await browser.newContext({ locale: 'en-US', userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36' });
  const page = await ctx.newPage();
  const since = new Date(Date.now() - HOURS * 3600e3).toISOString().slice(0, 19);
  const R = { updated: new Date().toISOString(), hours: HOURS, orders: [], funnel: null, leads: null, forum: [], errors: [] };
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

    // 2b. requests from site forms (modules/site-leads.php, admin «Requests») — form, time, TEST flag only (no names / e-mails)
    const l = await ctx.request.get(`${S}/wp-json/ss/v1/leads?days=${Math.max(1, Math.ceil(HOURS / 24))}`, H);
    if (l.status() === 200) { const d = await l.json(); R.leads = (d.leads || []).map(x => ({ id: x.id, date: x.date, form: scrub(x.form), test: !!x.test, reply: !!x.has_email })); }
    else R.errors.push('заявки: ответ ' + l.status());
  } catch (e) { R.errors.push(e.message.split('\n')[0]); }

  // 3. forum: wpForo RSS of all topics (public, titles + dates only — no nicknames); new = published within HOURS
  try {
    let r = null;
    for (let i = 0; i < 3; i++) { r = await ctx.request.get(S + '/en/forum/?type=rss2&forum=g&topic=g', { timeout: 90000 }); if (r.status() < 400) break; await page.waitForTimeout(45000); }
    if (r.status() >= 400) throw new Error('лента форума ответила ' + r.status());
    for (const it of parseRss(await r.text()).slice(0, 15)) {
      R.forum.push({ title: scrub(it.title), url: it.link, date: it.date, question: isQuestion(it.title), fresh: !!it.date && Date.parse(it.date) > Date.now() - HOURS * 3600e3 });
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
  L.push('', `## Форум — темы (${R.forum.length}, новых ${R.forum.filter(f => f.fresh).length})`, R.forum.length ? R.forum.map(f => `- ${f.fresh ? '🆕 ' : ''}${f.question ? '❓ ' : ''}${f.title} (${(f.date || '').slice(0, 10)}) — ${f.url}`).join('\n') : '_тем не найдено_');
  const real = (R.leads || []).filter(x => !x.test);
  L.push('', `## Заявки с форм (${real.length}${(R.leads || []).length > real.length ? ', тестовых ' + ((R.leads || []).length - real.length) : ''})`,
    R.leads ? (R.leads.length ? R.leads.map(x => `- ${x.test ? '🧪 TEST ' : ''}${x.date.slice(0, 16).replace('T', ' ')} — ${x.form}${x.reply ? '' : ' (без e-mail)'} — ответить: wp-admin → Requests #${x.id}`).join('\n') : '_Новых заявок нет._') : '_нет данных_');
  if (R.errors.length) L.push('', '## Ошибки робота', ...R.errors.map(e => '- ' + e));
  const md = L.join('\n') + '\n';
  if (/[\w.+-]+@[\w-]+\.[a-z]{2,}/i.test(md.replace(/actions@users\.noreply\.github\.com/g, ''))) { console.log('STOP: в отчёте нашлась почта — не сохраняю'); process.exit(1); }
  fs.writeFileSync(OUT + '/morning.md', md);
  fs.writeFileSync(OUT + '/morning.json', JSON.stringify(R, null, 1));
  console.log(md);
  process.exit(R.errors.length && !R.orders.length && !R.funnel ? 1 : 0);
})();
