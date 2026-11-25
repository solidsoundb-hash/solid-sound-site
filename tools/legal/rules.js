// Правила юр. проверки сайта (роль «Юрист», 25.11.2026). Чистые функции: страницы → находки. Их же гоняет экзамен.
// pages: { terms, refund, license, creator, notice, privacy, cancel } → { status, text, html }.  Ответ: [{ id, level, msg }]
// level: ok | warn (🟠 ждёт решения / мелочь) | bad (🔴 нарушение, чинить).
'use strict';
const has = (p, re) => !!(p && p.status === 200 && re.test(p.text || ''));
const rules = [
  ['pages', p => { const miss = ['terms', 'refund', 'license', 'notice', 'privacy'].filter(k => !p[k] || p[k].status !== 200);
    return miss.length ? ['bad', 'Не открываются: ' + miss.join(', ')] : ['ok', 'Terms, Refund, License, Legal Notice, Privacy открываются']; }],
  ['eur', p => { const usd = ['terms', 'creator', 'refund'].filter(k => has(p[k], /US dollars?|\bUSD\b|\$\s?\d/));
    return usd.length ? ['bad', 'Доллары вместо евро: ' + usd.join(', ')] : has(p.terms, /euros? \(EUR\)|\bEUR\b/) ? ['ok', 'Цены в евро (EUR)'] : ['warn', 'В Terms не сказано, что цены в евро']; }],
  ['seller', p => has(p.terms, /SIRE[NT]|RCS|EDRPOU|ЄДРПОУ|RNOKPP|registration (number|no)|VAT (number|no)|TVA/i) || has(p.notice, /SIRE[NT]|RCS|EDRPOU|RNOKPP|registration (number|no)|VAT (number|no)/i)
    ? ['ok', 'Продавец указан с регистрационным номером'] : ['warn', 'Нет регистрационного номера продавца (ждёт выбора ФОП / micro-entreprise)']],
  ['withdrawal', p => has(p.terms, /16\(m\)|L221-28/) && has(p.terms, /checkbox/i) ? ['ok', 'Отказ от 14 дней для цифровых товаров описан (галочка)'] : ['bad', 'В Terms нет условия об отказе от 14 дней для цифровых товаров']],
  ['refund30', p => has(p.refund, /30 days/) && has(p.refund, /within 14 days\./) && has(p.refund, /cancel-order|Cancel an order/) ? ['ok', 'Refund: 14 дней услуги, 30 дней брак'] : ['bad', 'Refund без сроков 14/30 дней']],
  ['law', p => has(p.license, /United Kingdom|\bUK\b|England/) ? ['bad', 'License ссылается на право Великобритании, Terms — на французское'] : ['ok', 'License и Terms: одно право']],
  ['payouts', p => has(p.creator, /PayPal|once a month, when|\$\s?\d/) ? ['bad', 'Creator Terms обещают выплаты/PayPal до выбора схемы'] : ['ok', 'Creator Terms без обещаний выплат']],
  ['mediator', p => has(p.terms, /details are available on request/i) || !has(p.terms, /mediat/i) ? ['warn', 'Медиатор не назван (нужно решение владельца и цена)'] : ['ok', 'Медиатор назван']],
  ['cancel', p => p.cancel && p.cancel.status === 200 && /Withdraw from contract here/i.test(p.cancel.text || '') ? (/Cancelar|Annuler|stornieren/.test((p.cancel.text || '').slice(0, 300)) ? ['bad', '«Cancel an order» показывается не на английском (язык страницы в Polylang)'] : ['ok', 'Есть страница «Cancel an order» с кнопкой отказа']) : ['warn', 'Нет страницы «Cancel an order» (кнопка отказа ЕС с 19.06.2026)']],
  ['checkbox', p => p.checkbox ? ['ok', 'Галочки отказа в коде оформления есть'] : ['bad', 'Галочки отказа пропали из кода оформления']],
  ['footer', p => { const h = (p.terms && p.terms.html) || ''; const miss = [['terms', /\/terms\//], ['privacy', /privacy/], ['refund', /refund/], ['license', /\/license\//], ['legal notice', /legal-notice/]].filter(([, re]) => !re.test(h)).map(x => x[0]);
    return miss.length ? ['bad', 'В подвале нет ссылок: ' + miss.join(', ')] : ['ok', 'В подвале все юр. ссылки']; }],
];
module.exports = pages => rules.map(([id, f]) => { const [level, msg] = f(pages); return { id, level, msg }; });
