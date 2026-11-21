// HEALTH (owner 21.11.2026): file integrity (hack signs), database health, shop state, reviews, outside services.
// Server part: modules/ss-health.php computes daily on the site (read-only); this robot logs in as the test admin and
// reads /wp-admin/admin-ajax.php?action=ss_health&run=1. Guest part: chat button, «Continue with Google», payment
// methods on checkout, PayPal / Stripe scripts reachable. Needs TEST_ADMIN_USER / TEST_ADMIN_PASS (already used by robots).
'use strict';
const { chromium } = require('playwright');
const { BASE, esc } = require('../bots/lib');
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const GW = { woocommerce_payments: 'WooPayments (карты, Apple/Google Pay)', 'ppcp-gateway': 'PayPal', ppcp: 'PayPal', mono_gateway: 'Plata by mono', bacs: 'банковский перевод', cod: 'оплата при получении', stripe: 'Stripe', amazon_payments_advanced: 'Amazon Pay' };

module.exports = async function health() {
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 }, userAgent: UA, locale: 'en-US' });
  await ctx.addCookies([{ name: 'ss_consent', value: 'no', domain: 'solid-sound.net', path: '/' }]);
  const md = ['## 🩺 Здоровье: файлы, база, магазин, внешние сервисы', '']; const alerts = []; const metrics = {};
  /* guest: outside services */
  const p = await ctx.newPage(); const ext = [];
  try {
    await p.goto(BASE + '/en/', { waitUntil: 'networkidle', timeout: 60000 }).catch(() => {}); await p.waitForTimeout(4000);
    const chat = await p.locator('.ssb-fab, [class*="telegram" i], #tidio-chat, [aria-label*="chat" i]').count();
    ext.push([chat ? '✅' : '🟠', 'Кнопка чата на главной', chat ? 'есть' : 'не найдена']);
    await p.goto(BASE + '/en/my-account/', { waitUntil: 'networkidle', timeout: 60000 }).catch(() => {}); await p.waitForTimeout(2500);
    const g = (await p.getByText(/Continue with Google|Sign in with Google/i).count()) + (await p.locator('iframe[src*="accounts.google.com"], .nsl-button-google, [class*="google" i][class*="login" i], [id*="g_id" i]').count());
    ext.push([g ? '✅' : '🟡', 'Вход через Google (кабинет)', g ? 'кнопка есть' : 'кнопка не найдена']);
  } catch (e) { ext.push(['⚪', 'Внешние сервисы', 'не проверено: ' + e.message.slice(0, 60)]); }
  /* admin: server health */
  let H = null;
  const user = process.env.TEST_ADMIN_USER, pass = process.env.TEST_ADMIN_PASS;
  if (!user || !pass) md.push('⏸ Нет входа тестового админа (секреты TEST_ADMIN_USER / TEST_ADMIN_PASS) — проверка файлов и базы пропущена.', '');
  else {
    try {
      await p.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 60000 });
      await p.fill('#user_login', user); await p.fill('#user_pass', pass); await Promise.all([p.waitForNavigation({ timeout: 60000 }).catch(() => {}), p.click('#wp-submit')]);
      const r = await p.request.get(BASE + '/wp-admin/admin-ajax.php?action=ss_health&run=1', { timeout: 320000 });
      const j = await r.json().catch(() => null); H = j && j.success ? j.data : null;
      if (!H) md.push('🔴 Сайт не отдал данные здоровья (' + r.status() + ') — модуль ss-health ещё не выложен или вход не удался.', '');
    } catch (e) { md.push('🔴 Не удалось войти/прочитать: ' + e.message.slice(0, 120), ''); }
  }
  await browser.close();
  if (H) {
    const c = H.core || {}; const chg = (c.changed || []).length + (c.missing || []).length, unk = (c.unknown_php || []).length;
    md.push(`Проверено на сервере за ${H.sec} с, WordPress ${H.wp}.`, '', '### Подмена файлов (признаки взлома)', '');
    md.push(c.error ? '⚪ Ядро не проверено: ' + c.error : (chg || unk ? `🔴 Ядро WordPress: изменено/нет **${chg}**, лишних PHP-файлов **${unk}** из ${c.checked}` : `✅ Ядро WordPress: все ${c.checked} файлов совпадают с оригиналом`));
    [...(c.changed || []).map(f => 'изменён ' + f), ...(c.missing || []).map(f => 'нет файла ' + f), ...(c.unknown_php || []).map(f => 'лишний ' + f)].slice(0, 15).forEach(x => md.push('  - `' + x + '`'));
    const pl = Object.entries(H.plugins || {}); const bad = pl.filter(([, v]) => (v.changed || []).length || (v.missing || []).length); const skip = pl.filter(([, v]) => v.skip);
    md.push(bad.length ? `🔴 Плагины с изменёнными файлами: ${bad.map(([k, v]) => `**${k}** ${v.v} (${(v.changed || []).length} изменено: ${(v.changed || []).slice(0, 3).join(', ')})`).join('; ')}` : `✅ Плагины с wordpress.org (${pl.length - skip.length}): файлы совпадают с оригиналом`);
    if (skip.length) md.push(`⚪ Не проверены (платные/не с wordpress.org): ${skip.map(([k]) => k).join(', ')}`);
    const KNOWN = /^\/smile_fonts\/[^/]+\/charmap\.php$/; /* Ultimate Addons icon fonts — a known plugin file */
    const upBad = (H.uploads_php || []).filter(f => !KNOWN.test(f)), upKnown = (H.uploads_php || []).filter(f => KNOWN.test(f));
    md.push(upBad.length ? `🔴 PHP-файлы в папке загрузок (так прячут вирусы): ${upBad.slice(0, 10).map(f => '`' + f + '`').join(', ')}` : '✅ В загрузках нет чужих PHP-файлов' + (upKnown.length ? ` (${upKnown.length} известный файл плагина Ultimate Addons — шрифты иконок)` : ''), '');
    if (chg + unk || bad.length || upBad.length) alerts.push('Здоровье: изменённые/лишние файлы — возможный взлом, проверить срочно');
    const d = H.db || {};
    md.push('### База данных', '', `Размер **${d.total_mb} МБ**, таблиц ${d.table_count}. Самые большие: ${(d.tables || []).slice(0, 5).map(t => `${t.t.replace(/^[a-z0-9]+_/i, '')} ${t.mb} МБ`).join(', ')}.`, '');
    const rows = [
      [d.autoload_kb > 900 ? '🟠' : d.autoload_kb > 500 ? '🟡' : '✅', 'Автозагрузка (грузится на КАЖДОЙ странице)', `${d.autoload_kb} КБ (норма до ~500)` + (d.autoload_kb > 500 ? '; больше всего: ' + (d.autoload_top || []).slice(0, 4).map(o => `${o.name} ${o.kb} КБ`).join(', ') : '')],
      [d.revisions > 2000 ? '🟡' : '✅', 'Ревизии страниц', d.revisions], [d.auto_drafts > 200 ? '🟡' : '✅', 'Пустые черновики', d.auto_drafts], [d.trash > 200 ? '🟡' : '✅', 'В корзине', d.trash],
      [d.transients_expired > 500 ? '🟡' : '✅', 'Просроченные временные записи', `${d.transients_expired} из ${d.transients_all}`], [d.orphan_postmeta > 5000 ? '🟡' : '✅', 'Мусорные поля без записей', d.orphan_postmeta],
      [d.spam_comments > 500 ? '🟡' : '✅', 'Спам/удалённые комментарии', d.spam_comments],
      ...(d.as_done != null ? [[d.as_done > 20000 ? '🟡' : '✅', 'Старые фоновые задачи (Action Scheduler)', d.as_done], [d.as_failed > 50 ? '🟠' : '✅', 'Упавшие фоновые задачи', d.as_failed], [d.as_pending_late > 20 ? '🟠' : '✅', 'Фоновые задачи, застрявшие >1 ч', d.as_pending_late]] : []),
      [d.cron_late > 20 ? '🟠' : '✅', 'Просроченные задания WP-Cron', d.cron_late], ...(d.woo_sessions != null ? [[d.woo_sessions > 20000 ? '🟡' : '✅', 'Сессии корзин', d.woo_sessions]] : []),
    ];
    md.push('| | Что | Сколько |', '|---|---|---|', ...rows.map(r => `| ${r[0]} | ${r[1]} | ${esc(r[2])} |`), '');
    const s = H.shop || {}; const gws = (s.gateways || []).map(g => GW[g] || g);
    md.push('### Магазин и отзывы', '', `- Способы оплаты включены: ${gws.length ? gws.join(', ') : '🔴 НИ ОДНОГО'}`);
    if (s.orders_7d) md.push(`- Заказы за 7 дней: оплачено ${(s.orders_7d.completed || 0) + (s.orders_7d.processing || 0)}, неудачных ${s.orders_7d.failed || 0}, брошено на оплате ${s.orders_7d.pending || 0}, отменено ${s.orders_7d.cancelled || 0}. Последний оплаченный заказ: ${s.last_paid_order || 'нет'}`);
    md.push(`- Отзывы: всего ${s.reviews_total}, новых за неделю ${s.reviews_7d}, ждут проверки **${s.reviews_pending}**; комментарии ждут проверки: ${s.comments_pending}`, '');
    if (!gws.length) alerts.push('Магазин: не включён ни один способ оплаты');
    if ((s.orders_7d || {}).failed > 2) alerts.push(`Магазин: ${s.orders_7d.failed} неудачных оплат за неделю`);
    if (s.reviews_pending) alerts.push(`Отзывы: ${s.reviews_pending} ждут проверки`);
    Object.assign(metrics, { health_core_changed: chg + unk, health_plugins_changed: bad.length, health_uploads_php: upBad.length, db_mb: d.total_mb, db_autoload_kb: d.autoload_kb, shop_failed_7d: (s.orders_7d || {}).failed || 0, reviews_pending: s.reviews_pending || 0 });
  }
  md.push('### Внешние сервисы', '', '| | Что | Итог |', '|---|---|---|', ...ext.map(e => `| ${e.join(' | ')} |`), '');
  return { md: md.join('\n'), metrics, alerts };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
