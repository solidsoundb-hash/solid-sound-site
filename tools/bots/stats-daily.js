// Daily table of OUR site: visits → product pages → cart → checkout → orders (Analyst robot, owner 25.11.2026:
// «у каждой роли свой рой роботов»). Source: Solid Stats (option ss_stats) + WooCommerce orders, read through the
// admin-only endpoint /wp-json/ss/v1/stats (modules/site-stats.php). The robot logs in like a person (admin test
// account from GitHub Secrets), reads the numbers, writes reports/analyst/stats-daily.md + stats-daily.json.
// Robots are not people: sessions with time zone "Other: UTC" are our GitHub robots → shown apart.
'use strict';
const fs = require('fs');
const { chromium } = require('playwright');
const S = 'https://solid-sound.net';
const OUT = 'reports/analyst';
const DAYS = +(process.env.DAYS || 30);

const sumWhere = (pages, test) => Object.entries(pages || {}).reduce((n, [p, v]) => n + (test(p) ? v : 0), 0);
const isProduct = (p) => /\/product\//.test(p);
const isCart = (p) => /\/cart\/?$/.test(p);
const isCheckout = (p) => /\/checkout\/?$/.test(p);
const isThanks = (p) => /order-received/.test(p);
const top = (o, n) => Object.entries(o || {}).sort((a, b) => b[1] - a[1]).slice(0, n).map(([k, v]) => `${k} ${v}`).join(', ');

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await browser.newContext(); const page = await ctx.newPage();
  let data = null, err = '';
  try {
    let resp = null;
    for (let i = 0; i < 4; i++) { // hosting may answer 429 when many robots run at once — wait and retry
      resp = await page.goto(S + '/en/my-account/?ss_tab=login', { waitUntil: 'domcontentloaded', timeout: 120000 });
      if (resp && resp.status() < 400 && await page.locator('#username:visible').count()) break;
      await page.waitForTimeout(60000);
    }
    if (!(await page.locator('#username:visible').count())) throw new Error(`нет формы входа (HTTP ${resp && resp.status()}, «${(await page.title()).slice(0, 60)}»)`);
    const form = page.locator('form.login').filter({ has: page.locator('#username:visible') }).first();
    await form.locator('#username').fill(process.env.TEST_ADMIN_USER || '');
    await form.locator('#password').fill(process.env.TEST_ADMIN_PASS || '');
    await Promise.all([page.waitForNavigation({ timeout: 120000 }).catch(() => {}), form.locator('button[name=login]').click()]);
    const nonce = (await (await ctx.request.get(S + '/wp-admin/admin-ajax.php?action=rest-nonce')).text()).trim();
    if (!/^[a-f0-9]{6,}$/.test(nonce)) throw new Error('вход не удался (нет ключа REST)');
    const r = await ctx.request.get(S + '/wp-json/ss/v1/stats?days=' + DAYS, { headers: { 'X-WP-Nonce': nonce } });
    if (r.status() !== 200) throw new Error('точка статистики ответила ' + r.status() + (r.status() === 404 ? ' (модуль ещё не выложен)' : ''));
    data = await r.json();
  } catch (e) { err = e.message.split('\n')[0]; }
  await browser.close();

  const L = [`# Наш сайт по дням — ${new Date().toISOString().slice(0, 16).replace('T', ' ')} UTC`, ''];
  if (!data) {
    L.push('❌ Не прочитал статистику: ' + err);
    fs.writeFileSync(OUT + '/stats-daily.md', L.join('\n') + '\n'); console.log(L.join('\n')); process.exit(1);
  }
  const days = Object.keys(data.days).sort().reverse();
  const rows = days.map(d => {
    const x = data.days[d]; const o = data.orders[d] || { n: 0, paid: 0, sum: 0 };
    const bots = (x.c || {})['Other: UTC'] || 0;
    return { d, s: x.s, people: x.s - bots, bots, v: x.v, prod: sumWhere(x.p, isProduct), cart: sumWhere(x.p, isCart), co: sumWhere(x.p, isCheckout), thx: sumWhere(x.p, isThanks), orders: o.n, paid: o.paid, sum: Math.round(o.sum * 100) / 100, phone: (x.m || {}).phone || 0, src: top(x.r, 3), ctry: top(x.c, 3) };
  });
  const T = (k) => rows.slice(0, 7).reduce((n, r) => n + r[k], 0); const P = (k) => rows.slice(7, 14).reduce((n, r) => n + r[k], 0);
  const ch = (k) => { const a = T(k), b = P(k); return b ? `${a} (${a >= b ? '+' : ''}${Math.round((a - b) / b * 100)} % к прошлой неделе)` : `${a}`; };
  L.push('## 7 дней', `- люди (сессии без роботов): **${ch('people')}**; страниц: ${ch('v')}`,
    `- воронка: товар ${T('prod')} → корзина ${T('cart')} → оформление ${T('co')} → «спасибо» ${T('thx')}; заказов ${T('orders')}, оплачено ${T('paid')} на ${Math.round(rows.slice(0, 7).reduce((n, r) => n + r.sum, 0) * 100) / 100} ${data.currency}`,
    `- с телефона: ${T('phone')} из ${T('s')} сессий`, '');
  L.push('## По дням (UTC)', '| День | Люди | Роботы | Страниц | Товар | Корзина | Оформл. | Заказы (оплач.) | Сумма | Источники | Страны |', '|---|---|---|---|---|---|---|---|---|---|---|');
  for (const r of rows) L.push(`| ${r.d} | ${r.people} | ${r.bots} | ${r.v} | ${r.prod} | ${r.cart} | ${r.co} | ${r.orders} (${r.paid}) | ${r.sum} | ${r.src} | ${r.ctry} |`);
  L.push('', '_«Роботы» = сессии с часовым поясом UTC (наши проверки на GitHub). Страница «Товар» — просмотры /product/…; корзина/оформление — просмотры этих страниц. Заказы — из WooCommerce._');
  fs.writeFileSync(OUT + '/stats-daily.md', L.join('\n') + '\n');
  fs.writeFileSync(OUT + '/stats-daily.json', JSON.stringify({ updated: new Date().toISOString(), currency: data.currency, rows }, null, 1));
  console.log(L.slice(0, 8).join('\n'));
})();
