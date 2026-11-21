// ACCESSIBILITY (owner 21.11.2026): axe-core (the standard checker) on the main pages, computer + Samsung A31:
// text contrast, buttons/links without a name for screen readers, form fields without labels, page language, zoom
// blocked, keyboard traps. Russian names for the common rules; the rest keep axe's own short help.
'use strict';
const { chromium } = require('playwright');
const { BASE, esc } = require('../bots/lib');
const PAGES = ['/en/', '/en/all-products/', '/product/infinity/', '/en/services-prices/', '/en/studio-calculator/', '/en/cart/', '/en/wiki/', '/en/free/', '/ua/'];
const DEV = {
  'компьютер': { viewport: { width: 1280, height: 900 }, userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0' },
  'A31': { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, userAgent: 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0' },
};
const RU = {
  'color-contrast': 'текст плохо читается (мало контраста с фоном)', 'button-name': 'кнопка без названия (незрячий услышит просто «кнопка»)',
  'link-name': 'ссылка без названия', 'image-alt': 'картинка без описания (alt)', 'label': 'поле формы без подписи',
  'html-has-lang': 'не указан язык страницы', 'html-lang-valid': 'неверный код языка страницы', 'meta-viewport': 'на телефоне запрещено увеличение',
  'aria-hidden-focus': 'спрятанный элемент ловит клавиатуру', 'duplicate-id-aria': 'одинаковые id у подписей', 'select-name': 'выпадающий список без подписи',
  'frame-title': 'встроенное окно (iframe) без названия', 'nested-interactive': 'кнопка внутри кнопки/ссылки', 'list': 'список свёрстан неправильно',
  'listitem': 'пункт списка вне списка', 'heading-order': 'заголовки идут не по порядку', 'landmark-one-main': 'нет главной области страницы (main)',
  'region': 'часть текста вне разметки областей', 'aria-allowed-attr': 'неверный ARIA-атрибут', 'aria-required-children': 'неполная ARIA-структура',
  'scrollable-region-focusable': 'прокручиваемый блок недоступен с клавиатуры', 'target-size': 'слишком маленькая цель для пальца',
};
const IMPACT = { critical: '🔴', serious: '🟠', moderate: '🟡', minor: '⚪' };

module.exports = async function a11y() {
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const rules = {}; let checked = 0, skipped = [];
  for (const [dn, d] of Object.entries(DEV)) {
    const ctx = await browser.newContext({ ...d, locale: 'en-US' }); await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    for (const p of PAGES) {
      const pg = await ctx.newPage();
      try {
        await pg.goto(BASE + p, { waitUntil: 'networkidle', timeout: 60000 }).catch(() => {}); await pg.waitForTimeout(2000);
        if (/Захищена сторінка/.test(await pg.title())) { skipped.push(p + ' (' + dn + ')'); continue; }
        await pg.addScriptTag({ path: require.resolve('axe-core') });
        const r = await pg.evaluate(async () => { const x = await window.axe.run(document, { resultTypes: ['violations'] });
          return x.violations.map(v => ({ id: v.id, impact: v.impact, help: v.help, n: v.nodes.length, ex: v.nodes.slice(0, 2).map(n => n.target.join(' ')) })); });
        checked++;
        for (const v of r) { const k = v.id; const R = rules[k] = rules[k] || { id: k, impact: v.impact, help: v.help, n: 0, pages: new Set(), ex: new Set() };
          R.n += v.n; R.pages.add(p + ' ' + dn); v.ex.forEach(e => R.ex.size < 3 && R.ex.add(e)); }
      } catch (e) { skipped.push(p + ' (' + dn + '): ' + e.message.slice(0, 60)); } finally { await pg.close(); }
    }
    await ctx.close();
  }
  await browser.close();
  const L = Object.values(rules).sort((a, b) => ['critical', 'serious', 'moderate', 'minor'].indexOf(a.impact) - ['critical', 'serious', 'moderate', 'minor'].indexOf(b.impact) || b.n - a.n);
  const cnt = i => L.filter(r => r.impact === i).length;
  let md = `## ♿ Доступность (контраст, подписи, клавиатура)\n\nПроверено страниц: **${checked}** (${PAGES.length} адресов × компьютер и A31). Правил нарушено: 🔴 ${cnt('critical')} · 🟠 ${cnt('serious')} · 🟡 ${cnt('moderate')} · ⚪ ${cnt('minor')}\n\n`;
  if (skipped.length) md += 'Не проверено: ' + skipped.join(', ') + '\n\n';
  md += '| | Что не так | Элементов | Страниц | Пример (где искать) |\n|---|---|---|---|---|\n' +
    L.map(r => `| ${IMPACT[r.impact] || '⚪'} | ${esc(RU[r.id] || r.help)} | ${r.n} | ${r.pages.size} | \`${esc([...r.ex][0] || '').slice(0, 70).replace(/`/g, "'")}\` |`).join('\n') + '\n';
  return { md, metrics: { a11y_critical: cnt('critical'), a11y_serious: cnt('serious'), a11y_rules: L.length }, alerts: cnt('critical') ? [`Доступность: ${cnt('critical')} критичных нарушений`] : [] };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
