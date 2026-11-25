// Solid Sound QA — knowledge base of COMMON bugs (owner 13.11.2026: "база знаний, по которой бот ищет общие баги,
// о которых я могу даже не догадываться"). Unique bugs the owner reports himself; these are the typical ones every
// shop / WordPress / phone site gets: SEO, Google product data, accessibility, phone usability, speed, old promos,
// price mismatches, link safety, forms.
//
// Separate from qa.js on purpose (two Claude sessions, see docs/SYNC.md). Use from qa.js:
//     const KB = require('./kb');
//     for (const b of await KB.run(page, { url, dev, devName: DEVICES[dev].name })) await bug(b, page);
// Standalone check:  node tools/qa/kb-run.js /en/ /product/intricate/
// The list for people: docs/qa-knowledge.md  (node tools/qa/kb-run.js --doc  rewrites it from RULES below).
//
// To add a rule: one entry in RULES (id, sev, area, title, why, fix) + its test in check(). Known false alarms:
// add to IGNORE with a reason.
'use strict';

const RULES = [
  // ---------- Google / SEO
  { id: 'seo-title', sev: 'major', area: 'Google', title: 'Нет заголовка страницы (title) или он слишком длинный/короткий',
    why: 'Заголовок — первая строка в выдаче Google. Пустой/обрезанный = меньше кликов.', fix: 'title 15–65 символов, свой у каждой страницы.' },
  { id: 'seo-desc', sev: 'minor', area: 'Google', title: 'Нет описания страницы (meta description)',
    why: 'Google сам вырезает кусок текста — часто неудачный.', fix: 'Описание 70–160 символов.' },
  { id: 'seo-h1', sev: 'minor', area: 'Google', title: 'Нет главного заголовка H1 или их несколько',
    why: 'Google и экранные дикторы по H1 понимают, о чём страница.', fix: 'Ровно один видимый H1.' },
  { id: 'seo-noindex', sev: 'critical', area: 'Google', title: 'Важная страница закрыта от Google (noindex)',
    why: 'Страница товара/главная не попадёт в поиск — нет бесплатных покупателей.', fix: 'Убрать noindex (Yoast / наш код).' },
  { id: 'seo-canonical', sev: 'major', area: 'Google', title: 'Canonical указывает на другую страницу или на http',
    why: 'Google решит, что это копия, и покажет другую страницу (или никакую).', fix: 'canonical = адрес самой страницы, https.' },
  { id: 'seo-og', sev: 'minor', area: 'Соцсети', title: 'Нет картинки/заголовка для превью в соцсетях (og:image, og:title)',
    why: 'Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.', fix: 'og:title + og:image 1200×630.' },
  { id: 'seo-product-ld', sev: 'major', area: 'Google', title: 'У товара нет разметки Product для Google (цена, наличие)',
    why: 'Без неё Google не покажет цену и звёзды в выдаче и не возьмёт товар в Google Shopping.', fix: 'JSON-LD Product с offers.price.' },
  { id: 'seo-ld-price', sev: 'major', area: 'Google', title: 'Цена в разметке для Google не совпадает с ценой на странице',
    why: 'Google Merchant блокирует товары с разной ценой; покупатель видит в Google одно, на сайте другое.', fix: 'Цена в JSON-LD = цена на странице (учесть скидки).' },
  { id: 'seo-ld-broken', sev: 'major', area: 'Google', title: 'Разметка для Google (JSON-LD) с ошибкой — Google её не прочитает',
    why: 'Сломанный JSON целиком игнорируется.', fix: 'Проверить в search.google.com/test/rich-results.' },

  // ---------- shop
  { id: 'shop-price-mix', sev: 'major', area: 'Магазин', title: 'Один и тот же товар показан с разной ценой на одной странице',
    why: 'Покупатель не понимает, сколько платить, — уходит или пишет в поддержку.', fix: 'Все цены из одного места (WooCommerce), скидки — во всех блоках.' },
  { id: 'shop-promo-expired', sev: 'major', area: 'Магазин', title: 'Акция/таймер уже закончились, а на сайте ещё висят',
    why: 'Обещание скидки, которой нет, — жалобы, возвраты, в ЕС это нарушение закона о защите потребителей.', fix: 'Убирать баннеры по дате автоматически.' },
  { id: 'shop-zero-price', sev: 'major', area: 'Магазин', title: 'Товар за $0 / €0 виден покупателям (не бесплатный пакет)',
    why: 'Обычно это технический товар — выглядит как ошибка магазина.', fix: 'Скрыть из каталога.' },
  { id: 'shop-old-year', sev: 'minor', area: 'Магазин', title: 'Старый год в подвале (©) или в тексте акции',
    why: 'Сайт выглядит заброшенным — меньше доверия.', fix: 'Год подставлять автоматически.' },

  { id: 'style-checklist', sev: 'minor', area: 'Стиль сайта', title: 'Чек-лист без значков у пунктов (правило владельца)',
    why: 'Правило владельца (13.11.2026): у каждого пункта чек-листа — свой значок по смыслу (линейка, угол, ползунки, дом…), не одинаковые галочки.', fix: 'ul.check — ss-global сам ставит значки; в статьях вики — modules/wiki.php.' },
  // ---------- accessibility / clarity
  { id: 'a11y-img-alt', sev: 'minor', area: 'Доступность', title: 'Картинки без подписи (alt)',
    why: 'Google Картинки не понимают картинку; незрячий покупатель слышит «изображение».', fix: 'alt с названием товара/смыслом.' },
  { id: 'a11y-btn-name', sev: 'major', area: 'Доступность', title: 'Кнопка или ссылка без названия (только значок)',
    why: 'Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.', fix: 'aria-label="Play", "Add to cart" и т.п.' },
  { id: 'a11y-input-label', sev: 'minor', area: 'Доступность', title: 'Поле ввода без подписи',
    why: 'Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.', fix: '<label> или aria-label.' },
  { id: 'a11y-contrast', sev: 'minor', area: 'Доступность', title: 'Текст плохо читается: слишком бледный на фоне',
    why: 'На телефоне на улице такой текст не видно.', fix: 'Контраст не ниже 4.5:1 (мелкий текст).' },
  { id: 'a11y-zoom-off', sev: 'major', area: 'Телефон', title: 'На телефоне запрещено увеличивать страницу пальцами',
    why: 'Людям со слабым зрением не прочитать; Google считает это ошибкой.', fix: 'Убрать user-scalable=no / maximum-scale=1.' },
  { id: 'a11y-dup-id', sev: 'minor', area: 'Код', title: 'Одинаковые id у разных элементов',
    why: 'Кнопки, якоря и подписи полей начинают работать не с тем элементом.', fix: 'Уникальные id.' },
  { id: 'a11y-iframe-title', sev: 'minor', area: 'Доступность', title: 'Встроенное видео/окно без названия (title)',
    why: 'Диктор читает «фрейм» — непонятно, что внутри.', fix: 'title="YouTube: …".' },

  // ---------- phone
  { id: 'mob-tap', sev: 'minor', area: 'Телефон', title: 'Кнопки/ссылки слишком маленькие для пальца',
    why: 'Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).', fix: 'Увеличить зону нажатия (padding).' },
  { id: 'mob-font', sev: 'minor', area: 'Телефон', title: 'Слишком мелкий текст на телефоне (меньше 12 px)',
    why: 'Нечитаемо без увеличения.', fix: 'Минимум 12–13 px для подписей, 15–16 для текста.' },
  { id: 'mob-input-zoom', sev: 'minor', area: 'Телефон', title: 'Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу',
    why: 'При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.', fix: 'font-size: 16px у полей ввода на телефоне.' },
  { id: 'mob-covered', sev: 'major', area: 'Телефон', title: 'Важную кнопку закрывает что-то сверху (чат, плеер, баннер)',
    why: 'Нажимается не кнопка, а то, что её закрывает.', fix: 'Сдвинуть плавающие элементы или добавить отступ снизу.' },
  { id: 'mob-fixed-big', sev: 'minor', area: 'Телефон', title: 'Закреплённые панели занимают слишком много экрана',
    why: 'Остаётся мало места для товара; на маленьких телефонах (A31, iPhone SE) особенно.', fix: 'Вместе не больше ~35% высоты экрана.' },
  { id: 'mob-input-type', sev: 'minor', area: 'Телефон', title: 'Поле e-mail/телефона без нужного типа — неудобная клавиатура',
    why: 'Нет @ на клавиатуре, нет автозаполнения — бросают форму.', fix: 'type="email" / type="tel" + autocomplete.' },

  // ---------- speed
  { id: 'perf-img-heavy', sev: 'minor', area: 'Скорость', title: 'Очень тяжёлая картинка (больше 400 КБ)',
    why: 'На мобильном интернете страница грузится секунды; Google ставит ниже.', fix: 'WebP/AVIF, сжать, нужный размер.' },
  { id: 'perf-img-oversize', sev: 'minor', area: 'Скорость', title: 'Картинка загружается намного больше, чем показывается',
    why: 'Лишние мегабайты на каждой загрузке.', fix: 'srcset / миниатюра нужного размера.' },
  { id: 'perf-img-dims', sev: 'minor', area: 'Скорость', title: 'Картинки без размеров — страница «прыгает» при загрузке',
    why: 'Пока грузится картинка, текст и кнопки съезжают — промахи, плохая оценка Google (CLS).', fix: 'width/height или aspect-ratio.' },
  { id: 'perf-lcp-lazy', sev: 'minor', area: 'Скорость', title: 'Главная картинка первого экрана грузится «лениво» (loading=lazy)',
    why: 'Самая важная картинка появляется последней.', fix: 'Для первого экрана — без lazy, с fetchpriority=high.' },
  { id: 'perf-blocking-js', sev: 'minor', area: 'Скорость', title: 'Скрипты в <head> без defer/async — тормозят показ страницы',
    why: 'Браузер ждёт скачивания скрипта, страница белая.', fix: 'defer (правило проекта в CLAUDE.md).' },
  { id: 'perf-dom', sev: 'check', area: 'Скорость', title: 'Слишком много элементов на странице (>3000)',
    why: 'Телефон тормозит при прокрутке и нажатиях.', fix: 'Меньше вложенности, подгружать по частям.' },
  { id: 'perf-weight', sev: 'check', area: 'Скорость', title: 'Страница весит больше 3 МБ',
    why: 'На 3G/4G в дороге — долго и дорого.', fix: 'Смотреть, что самое тяжёлое.' },

  // ---------- links / security
  { id: 'sec-mixed', sev: 'major', area: 'Безопасность', title: 'Файлы грузятся по http:// на https-странице',
    why: 'Браузер блокирует их или пишет «Не защищено».', fix: 'Все адреса на https.' },
  { id: 'sec-blank', sev: 'minor', area: 'Безопасность', title: 'Внешняя ссылка в новой вкладке без rel="noopener"',
    why: 'Чужой сайт может подменить нашу вкладку (фишинг).', fix: 'rel="noopener noreferrer".' },
  { id: 'link-empty', sev: 'minor', area: 'Ссылки', title: 'Ссылка-пустышка: href="#" или пусто, ничего не происходит',
    why: 'Человек нажимает — и ничего.', fix: 'Настоящий адрес или кнопка с действием.' },
  { id: 'link-http-own', sev: 'minor', area: 'Ссылки', title: 'Ссылка на свой сайт по http:// или старому адресу',
    why: 'Лишний редирект, иногда предупреждение браузера.', fix: 'https://solid-sound.net/…' },

  // ---------- media
  { id: 'media-autoplay', sev: 'major', area: 'Звук', title: 'Звук/видео со звуком включается сам при открытии страницы',
    why: 'Раздражает, браузеры блокируют, люди закрывают сайт.', fix: 'Только по нажатию или без звука.' },
];
const RULE = Object.fromEntries(RULES.map(r => [r.id, r]));

// known false alarms: rule id -> list of { re: what (selector/url/text), why }
const IGNORE = {
  'a11y-iframe-title': [{ re: /recaptcha|google\.com\/recaptcha/, why: 'служебное окно Google' }],
  'mob-tap': [{ re: /ssp-|ssw-|wp-admin|#wpadminbar/, why: 'плеер проверяется отдельно, админ-панель не для клиентов' }],
  'sec-blank': [],
  'link-empty': [{ re: /w-filter-item|w-filter-opener|w-tabs|w-toplink/, why: 'фильтры и вкладки темы работают через скрипт, а не по ссылке' }],
  'perf-blocking-js': [{ re: /gtag|googletagmanager|jquery(\.min)?\.js/, why: 'аналитика и jQuery (нужен темой) — известно' }],
};
const ignored = (id, s) => (IGNORE[id] || []).some(x => x.re.test(String(s)));

// pages that must be in Google (noindex there = critical)
const MUST_INDEX = /^\/((en|ua|de|fr|pt|es|it)\/?)?$|^\/product\/|^\/(en\/)?(all-products|services-prices|templates|presets|music|studio-calculator)\/?$/;
// promos with an end date (keep in sync with modules/sale.php and ss-global.js PROMO_END)
const PROMOS = [{ re: /black month|−50%|-50%/i, end: '2026-11-30T23:59:59+01:00', name: 'Black Month −50%' }];

/* ---------------------------------------------------------------- facts collected in the browser */
function collect() {
  const vw = innerWidth, vh = innerHeight;
  const vis = (e) => { const r = e.getBoundingClientRect(); const s = getComputedStyle(e); return r.width > 0 && r.height > 0 && s.visibility !== 'hidden' && s.display !== 'none' && +s.opacity !== 0; };
  const name = (e) => (e.getAttribute('aria-label') || e.getAttribute('title') || (e.innerText || '').trim() || (e.textContent || '').trim() || (e.querySelector('img[alt]') || {}).alt || (e.querySelector('svg title') || {}).textContent || (e.getAttribute('aria-labelledby') && (document.getElementById(e.getAttribute('aria-labelledby')) || {}).textContent) || '').replace(/\s+/g, ' ').trim();
  const desc = (e) => { const t = e.tagName.toLowerCase(); const c = typeof e.className === 'string' ? e.className.trim().split(/\s+/).slice(0, 2).join('.') : ''; const n = name(e).slice(0, 40); return t + (e.id ? '#' + e.id : '') + (c ? '.' + c : '') + (n ? ' «' + n + '»' : ''); };
  const meta = (n) => { const m = document.querySelector(`meta[name="${n}"], meta[property="${n}"]`); return m ? (m.getAttribute('content') || '') : null; };
  const f = { vw, vh, path: location.pathname, title: document.title || '', desc: meta('description'), robots: (meta('robots') || '') + ' ' + (meta('googlebot') || ''),
    canonical: (document.querySelector('link[rel=canonical]') || {}).href || '', ogImage: meta('og:image'), ogTitle: meta('og:title'), viewport: meta('viewport') || '',
    h1: [...document.querySelectorAll('h1')].filter(vis).map(h => h.innerText.trim().slice(0, 60)), isProduct: document.body.classList.contains('single-product') };
  // JSON-LD
  f.ld = []; f.ldBroken = 0;
  document.querySelectorAll('script[type="application/ld+json"]').forEach(s => { try { const j = JSON.parse(s.textContent); (Array.isArray(j) ? j : (j['@graph'] || [j])).forEach(x => f.ld.push(x)); } catch (e) { f.ldBroken++; } });
  const prod = f.ld.find(x => [].concat(x['@type'] || []).includes('Product'));
  f.ldPrices = []; if (prod) [].concat(prod.offers || []).forEach(o => { if (o.price != null) f.ldPrices.push(+o.price); if (o.lowPrice != null) f.ldPrices.push(+o.lowPrice); [].concat(o.priceSpecification || []).forEach(p => p.price != null && f.ldPrices.push(+p.price)); });
  f.hasProductLd = !!prod;
  const pe = document.querySelector('.summary .price, .product .summary .price, .woocommerce-Price-amount');
  f.pagePrice = null;
  if (f.isProduct) { const box = document.querySelector('.summary .price') || document.querySelector('.price'); if (box) { const ins = box.querySelector('ins .amount') || box.querySelector('.amount'); if (ins) f.pagePrice = parseFloat(ins.textContent.replace(/[^\d.,]/g, '').replace(',', '.')); } }
  // prices per product name on the page (cards)
  f.cardPrices = {};
  document.querySelectorAll('article, .product, .w-grid-item, li.product').forEach(c => {
    if (!vis(c)) return; const h = c.querySelector('h2,h3,h4,.woocommerce-loop-product__title,.post_title,a[href*="/product/"]'); if (!h) return;
    const n = h.innerText.trim().split('\n')[0].slice(0, 40); if (!n) return;
    const t = (c.innerText || '').replace(/Original price was:[^.]*\./g, '');
    const m = t.match(/[$€]\s?\d+(?:[.,]\d{2})?/g); if (!m) return;
    const last = m[m.length - 1]; (f.cardPrices[n] = f.cardPrices[n] || new Set()).add(last.replace(/\s/g, '').replace(/[.,]00$/, ''));
  });
  for (const k in f.cardPrices) f.cardPrices[k] = [...f.cardPrices[k]];
  const text = (document.body.innerText || '');
  f.zero = '';
  { const w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT); let k = 0;
    while (w.nextNode() && k++ < 20000) { const t = w.currentNode.textContent; if (!/(^|\s)[$€]\s?0(?:[.,]00)?(\s|$)/.test(t)) continue; const el = w.currentNode.parentElement; if (!el || !vis(el)) continue;
      const card = el.closest('article, .offer, .card, li, .product, .w-grid-item, tr') || el.parentElement; const ct = (card && card.innerText || '').slice(0, 400);
      if (/free|безкошт|бесплат|gratis|kostenlos|gratuit|starter|fee|commission|комісі|комисс|gebühr|frais|tarifa|commissione/i.test(ct) || card.querySelector('s, del')) continue; f.zero = ct.replace(/\s+/g, ' ').slice(0, 90); break; } }
  f.textHead = text.slice(0, 20000);
  f.years = (text.match(/©\s?(20\d\d)/g) || []).map(s => +s.replace(/\D/g, ''));
  // images
  f.imgs = [...document.images].filter(i => vis(i) && i.naturalWidth > 0).map(i => { const r = i.getBoundingClientRect(); return { src: i.currentSrc || i.src, alt: i.getAttribute('alt'), role: i.getAttribute('role'), aria: i.getAttribute('aria-hidden'), w: r.width, h: r.height, nw: i.naturalWidth, nh: i.naturalHeight, top: r.top + scrollY, lazy: i.loading === 'lazy', dims: !!(i.getAttribute('width') && i.getAttribute('height')) || getComputedStyle(i).aspectRatio !== 'auto' || (i.style.height && i.style.width) }; });
  // buttons and links without a name
  f.noName = [...document.querySelectorAll('button, a[href], [role=button]')].filter(e => vis(e) && !name(e) && !e.closest('[aria-hidden=true]')).slice(0, 8).map(desc);
  // inputs without a label
  f.inputs = [...document.querySelectorAll('input:not([type=hidden]):not([type=submit]):not([type=button]):not([type=checkbox]):not([type=radio]):not([type=range]), textarea, select')].filter(vis).filter(i => i.tabIndex !== -1 && !/(^|[-_])(hp|honeypot)([-_]|$)/i.test(i.className + ' ' + i.name)).map(i => {
    const lab = i.getAttribute('aria-label') || i.getAttribute('aria-labelledby') || (i.id && document.querySelector(`label[for="${CSS.escape(i.id)}"]`)) || i.closest('label') || i.getAttribute('title');
    const nm = (i.name || i.id || i.placeholder || '').toLowerCase();
    return { d: desc(i) + (i.placeholder ? ' [' + i.placeholder.slice(0, 30) + ']' : ''), lab: !!lab, fs: parseFloat(getComputedStyle(i).fontSize), type: i.type, nm };
  });
  // duplicate ids
  const ids = {}; document.querySelectorAll('[id]').forEach(e => { if (e.id) ids[e.id] = (ids[e.id] || 0) + 1; });
  f.dupIds = Object.keys(ids).filter(k => ids[k] > 1 && !/^(svg|path|clip|mask|grad|lg|rg)/i.test(k)).slice(0, 6);
  f.iframes = [...document.querySelectorAll('iframe')].filter(vis).filter(i => !i.getAttribute('title')).map(i => i.src.slice(0, 80));
  // tap targets (phone)
  f.small = [];
  if (vw < 600) document.querySelectorAll('a[href], button, [role=button], input[type=checkbox], input[type=radio], select').forEach(e => {
    if (!vis(e) || e.closest('p, li p, .woocommerce-product-details__short-description, article p')) return; // links inside text are fine
    const r = e.getBoundingClientRect(); if (r.width >= 24 && r.height >= 24) return;
    { const a = getComputedStyle(e, '::after'); if (a.content !== 'none' && a.position === 'absolute') { const w = r.width - (parseFloat(a.left) || 0) - (parseFloat(a.right) || 0), h = r.height - (parseFloat(a.top) || 0) - (parseFloat(a.bottom) || 0); if (w >= 24 && h >= 24) return; } } // invisible ::after hit area (slider dots) counts (25.11)
    { const lb = e.matches('input') && e.closest('label'); if (lb) { const q = lb.getBoundingClientRect(); if (q.width >= 24 && q.height >= 24) return; } } // the whole label is the tap target (25.11) if (r.width >= 120) return; /* long text links: wide enough to hit */ if (r.width < 4 || r.height < 4) return; if (e.closest('footer, .ssf, .l-footer')) return;
    if (r.top > vh * 6) return; f.small.push(desc(e) + ` ${Math.round(r.width)}×${Math.round(r.height)}`);
  });
  // tiny text + contrast (sample)
  f.tiny = []; f.lowContrast = [];
  const lum = (c) => { const m = c.match(/[\d.]+/g); if (!m) return null; const [r, g, b, a] = m.map(Number); if (a === 0) return null; const L = [r, g, b].map(v => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); }); return 0.2126 * L[0] + 0.7152 * L[1] + 0.0722 * L[2]; };
  const bgOf = (e) => { while (e && e.nodeType === 1) { const s = getComputedStyle(e); if (s.backgroundImage !== 'none') return null; const c = s.backgroundColor; const m = c.match(/[\d.]+/g); if (m && (m.length < 4 || +m[3] > 0.9)) return c; e = e.parentElement; } return 'rgb(255,255,255)'; };
  let n = 0;
  const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, { acceptNode: t => t.textContent.trim().length > 2 ? 1 : 2 });
  while (walker.nextNode() && n < 1500) {
    n++; const e = walker.currentNode.parentElement; if (!e || !vis(e) || e.closest('#wpadminbar, script, style, noscript, [aria-hidden=true]')) continue;
    const s = getComputedStyle(e); const fs = parseFloat(s.fontSize);
    // fs 0 = text swapped for a ::after label (e.g. «+3 more» → «+6 more» 13px) — not tiny text (25.11)
    if (vw < 600 && fs > 0 && fs < 10.5 && f.tiny.length < 6 && !f.tiny.some(x => x.startsWith(desc(e)))) f.tiny.push(desc(e) + ` ${fs}px`);
    const bg = bgOf(e); if (!bg) continue; const a = lum(s.color), b = lum(bg); if (a == null || b == null) continue;
    const ratio = (Math.max(a, b) + 0.05) / (Math.min(a, b) + 0.05); const big = fs >= 18.5 || (fs >= 14 && +s.fontWeight >= 700);
    if (ratio < (big ? 3 : 4.5) - 0.05 && f.lowContrast.length < 6) { const d = desc(e) + ` ${ratio.toFixed(1)}:1`; if (!f.lowContrast.includes(d)) f.lowContrast.push(d); }
  }
  // covered buttons: what is on top of the centre of each main button
  f.covered = [];
  document.querySelectorAll('a.button, button.button, .single_add_to_cart_button, .add_to_cart_button, .checkout-button, a[class*=cta], button[type=submit]').forEach(e => {
    if (!vis(e) || f.covered.length > 4) return; const r = e.getBoundingClientRect(); if (r.top < 0 || r.bottom > vh) return;
    const x = r.left + r.width / 2, y = r.top + r.height / 2; const top = document.elementFromPoint(x, y);
    // (20.11) a button that only scrolls UNDER a sticky / fixed header or toolbar at the top is not covered — scrolling brings it out
    const stuck = (n) => { for (let k = 0; n && k < 8; k++, n = n.parentElement) { const p = getComputedStyle(n).position; if (p === 'sticky' || p === 'fixed') return n; } return null; };
    const st = top && stuck(top); if (st && st.getBoundingClientRect().top < vh * 0.25 && y < st.getBoundingClientRect().bottom + 2) return;
    // (23.11) the same for a bar fixed at the BOTTOM (audio player, sticky buy) when the page leaves room for it below — scrolling brings the button out
    if (st && st.getBoundingClientRect().top > vh * 0.6 && parseFloat(getComputedStyle(document.body).paddingBottom) >= st.getBoundingClientRect().height - 4) return;
    if (top && top !== e && !e.contains(top) && !top.contains(e)) f.covered.push(desc(e) + ' ← закрывает ' + desc(top));
  });
  // fixed panels height
  let fixedH = 0; const seen = [];
  document.querySelectorAll('body *').forEach(e => { const s = getComputedStyle(e); if ((s.position === 'fixed' || s.position === 'sticky') && vis(e)) { const r = e.getBoundingClientRect(); if (r.width > vw * 0.6 && r.height < vh * 0.8 && !seen.some(p => p.contains(e))) { seen.push(e); if (r.top < vh && r.bottom > 0) fixedH += Math.min(r.height, vh); } } });
  f.fixedShare = fixedH / vh; f.fixedList = seen.slice(0, 5).map(desc);
  // links
  const abs = [...document.querySelectorAll('a[href]')].filter(vis);
  f.blank = abs.filter(a => a.target === '_blank' && a.host !== location.host && !/noopener|noreferrer/.test(a.rel)).slice(0, 5).map(a => a.href.slice(0, 80));
  f.empty = abs.filter(a => { const h = a.getAttribute('href').trim(); return (h === '#' || h === '' || /^javascript:\s*(void|;|$)/.test(h)) && !a.getAttribute('role') && !a.onclick && !a.getAttribute('data-toggle') && !a.closest('nav, [role=tablist], .w-tabs'); }).slice(0, 5).map(desc);
  f.httpOwn = abs.filter(a => /^http:\/\/(www\.)?solid-sound\.net/.test(a.href)).slice(0, 5).map(a => a.href);
  f.mixed = [...document.querySelectorAll('img[src^="http:"], script[src^="http:"], link[rel=stylesheet][href^="http:"], iframe[src^="http:"], audio[src^="http:"], source[src^="http:"], video[src^="http:"]')].slice(0, 5).map(e => e.src || e.href);
  f.blocking = [...document.head.querySelectorAll('script[src]')].filter(s => !s.defer && !s.async && s.type !== 'module').map(s => s.src.replace(/\?.*/, '')).slice(0, 8);
  f.badChecklists = [...document.querySelectorAll('h2,h3,h4')].filter(h => /check ?-?list|чек-?лист/i.test(h.textContent)).map(h => { let n = h.nextElementSibling; while (n && n.tagName === 'P' && !n.textContent.trim()) n = n.nextElementSibling; return n && /^(UL|OL)$/.test(n.tagName) && n.querySelector('li') && !n.querySelector('li > .wi, li > .ssg-ci') ? h.textContent.trim().slice(0, 50) : null; }).filter(Boolean);
  f.dom = document.getElementsByTagName('*').length;
  const res = performance.getEntriesByType('resource');
  f.weight = res.reduce((a, r) => a + (r.transferSize || 0), 0) + ((performance.getEntriesByType('navigation')[0] || {}).transferSize || 0);
  f.heavyImg = res.filter(r => r.initiatorType === 'img' || /\.(jpe?g|png|webp|gif|avif)(\?|$)/i.test(r.name)).filter(r => (r.transferSize || r.encodedBodySize || 0) > 400 * 1024).slice(0, 5).map(r => r.name.replace(/\?.*/, '') + ' ' + Math.round((r.transferSize || r.encodedBodySize) / 1024) + ' КБ');
  f.autoplay = [...document.querySelectorAll('audio, video')].filter(m => !m.paused && !m.muted && m.currentTime > 0).map(m => (m.currentSrc || m.src).slice(0, 80));
  return f;
}

/* ---------------------------------------------------------------- turn facts into bugs */
function check(f, ctx) {
  const out = [];
  const here = { page: ctx.url, device: ctx.devName || '' };
  const add = (id, actual, items, key) => {
    const r = RULE[id]; items = (items || []).filter(x => !ignored(id, x));
    out.push({ ...here, sev: r.sev, type: 'kb-' + id, sig: 'kb|' + id + '|' + (key || f.path),
      title: r.title,
      steps: [`Открыть https://solid-sound.net${f.path}` + (ctx.devName ? ` (${ctx.devName})` : '')].concat(items && items.length ? ['Где: ' + items.slice(0, 5).join('; ')] : []),
      expected: r.fix, actual: (actual || '') + (r.why ? (actual ? ' ' : '') + 'Почему важно: ' + r.why : ''), area: r.area, kb: id });
  };
  // same elements on many pages = one bug with a list of pages (the QA bot groups by sig)
  const list = (id, items, actual) => { items = (items || []).filter(x => !ignored(id, x)); if (items.length) add(id, actual || '', items, 'el|' + items.slice(0, 3).map(x => x.replace(/ \d+(\.\d+)?(px|:1)| \d+×\d+/g, '')).join(',').slice(0, 200)); };
  const p = f.path;
  const admin = /^\/(wp-admin|wp-login|.*store-manager)/.test(p);
  if (admin) return out;
  // Google
  if (!f.title || f.title.length < 15 || f.title.length > 70) add('seo-title', `Сейчас: «${f.title}» (${f.title.length} симв.).`, [], 'all|' + p);
  if (f.desc === null || f.desc.length < 50) add('seo-desc', f.desc === null ? 'Описания нет.' : `Слишком короткое: «${f.desc}».`, [], p);
  if (f.h1.length !== 1) add('seo-h1', `Видимых H1: ${f.h1.length}${f.h1.length ? ' (' + f.h1.join(' | ') + ')' : ''}.`, [], p);
  if (/noindex/i.test(f.robots) && MUST_INDEX.test(p)) add('seo-noindex', `robots: «${f.robots.trim()}».`, [], p);
  if (f.canonical) { try { const c = new URL(f.canonical); if (c.protocol !== 'https:' || (c.pathname.replace(/\/$/, '') !== p.replace(/\/$/, '') && c.pathname.replace(/\/$/, '') !== '/en' + p.replace(/\/$/, '') /* 23.11: /forum/ → /en/forum/ is the main copy, right */ && !/[?&](s|page|orderby)=/.test(String(ctx.url || '')))) add('seo-canonical', `Указано: ${f.canonical}`, [], p); } catch (e) {} }
  if (!f.ogImage || !f.ogTitle) add('seo-og', `og:title: ${f.ogTitle ? 'есть' : 'нет'}, og:image: ${f.ogImage ? 'есть' : 'нет'}.`, [], p);
  if (f.ldBroken) add('seo-ld-broken', `Сломанных блоков: ${f.ldBroken}.`, [], p);
  if (f.isProduct && !f.hasProductLd) add('seo-product-ld', '', [], p);
  if (f.isProduct && f.hasProductLd && f.pagePrice != null && f.ldPrices.length && !f.ldPrices.some(x => Math.abs(x - f.pagePrice) < 0.01)) add('seo-ld-price', `На странице ${f.pagePrice}, в разметке ${f.ldPrices.join(' / ')}.`, [], p);
  // shop
  for (const [n, ps] of Object.entries(f.cardPrices)) if (ps.length > 1) add('shop-price-mix', `«${n}»: ${ps.join(' и ')}.`, [], 'price|' + n);
  const now = Date.now();
  for (const pr of PROMOS) if (now > new Date(pr.end).getTime() && pr.re.test(f.textHead)) add('shop-promo-expired', `${pr.name} закончилась ${pr.end.slice(0, 10)}, но текст на странице есть.`, [], 'promo|' + pr.name);
  if (/(ends in|осталось|залишилось)\s*0d\s*0h|00\s*:\s*00\s*:\s*00/i.test(f.textHead)) add('shop-promo-expired', 'Таймер показывает ноль.', [], 'timer0');
  if (f.zero && !/free|безкошт|бесплат|starter|\bfree\b|gratis|kostenlos|gratuit|bonus|balance|wallet|credit|баланс|бонус|discount|saved|total|subtotal/i.test(f.zero)) /* 17.11: «Bonus balance: $0.00» is the customer's bonus, not a product */ add('shop-zero-price', `Текст: «${f.zero}».`, [], 'zero|' + p);
  const yr = new Date().getFullYear(); if (f.years.length && Math.max(...f.years) < yr) add('shop-old-year', `В подвале © ${Math.max(...f.years)}.`, [], 'year');
  // accessibility
  list('a11y-img-alt', f.imgs.filter(i => (i.alt === null) && i.role !== 'presentation' && i.aria !== 'true' && i.w > 40).map(i => i.src.split('/').pop().slice(0, 60)), '', 'alt|' + p);
  list('a11y-btn-name', f.noName, '', 'name|' + p);
  list('a11y-input-label', f.inputs.filter(i => !i.lab).map(i => i.d), '', 'label|' + p);
  list('a11y-contrast', f.lowContrast, '', 'contrast|' + p);
  if (/user-scalable\s*=\s*(no|0)|maximum-scale\s*=\s*1(\.0)?\b/i.test(f.viewport)) add('a11y-zoom-off', `viewport: «${f.viewport}».`, [], 'zoom');
  list('a11y-dup-id', f.dupIds, '', 'dupid|' + f.dupIds.join(','));
  list('a11y-iframe-title', f.iframes, '', 'iframe|' + p);
  // phone
  if (f.vw < 600) {
    list('mob-tap', f.small, '', 'tap|' + p);
    list('mob-font', f.tiny, '', 'font|' + p);
    list('mob-input-zoom', f.inputs.filter(i => i.fs < 16).map(i => i.d + ` ${i.fs}px`), '', 'izoom|' + p);
    if (f.fixedShare > 0.35) add('mob-fixed-big', `Закреплено ${Math.round(f.fixedShare * 100)}% высоты экрана.`, f.fixedList, 'fixed|' + p);
  }
  list('mob-covered', f.covered, '', 'covered|' + p);
  list('mob-input-type', f.inputs.filter(i => (/mail/.test(i.nm) && i.type !== 'email') || (/phone|tel/.test(i.nm) && i.type !== 'tel')).map(i => i.d), '', 'itype|' + p);
  list('style-checklist', f.badChecklists, '');
  // speed
  list('perf-img-heavy', f.heavyImg, '', 'heavy|' + p);
  list('perf-img-oversize', f.imgs.filter(i => i.w > 20 && i.nw > i.w * 3 && i.nw > 600).slice(0, 5).map(i => `${i.src.split('/').pop().slice(0, 50)} ${i.nw}px → ${Math.round(i.w)}px`), '', 'oversize|' + p);
  list('perf-img-dims', f.imgs.filter(i => !i.dims && i.w > 60).slice(0, 5).map(i => i.src.split('/').pop().slice(0, 60)), '', 'dims|' + p);
  list('perf-lcp-lazy', f.imgs.filter(i => i.lazy && i.top < f.vh && i.w * i.h > f.vw * f.vh * 0.08).map(i => i.src.split('/').pop().slice(0, 60)), '', 'lcp|' + p);
  list('perf-blocking-js', f.blocking, '', 'block|' + p);
  if (f.dom > 3000) add('perf-dom', `Элементов: ${f.dom}.`, [], 'dom|' + p);
  if (f.weight > 3 * 1024 * 1024) add('perf-weight', `Скачано ${(f.weight / 1048576).toFixed(1)} МБ.`, [], 'weight|' + p);
  // links / security
  list('sec-mixed', f.mixed, '', 'mixed|' + p);
  list('sec-blank', f.blank, '', 'blank|' + p);
  list('link-empty', f.empty, '', 'empty|' + p);
  list('link-http-own', f.httpOwn, '', 'httpown|' + p);
  list('media-autoplay', f.autoplay, '', 'autoplay|' + p);
  return out;
}

async function run(page, ctx) {
  let f;
  try { f = await page.evaluate(collect); } catch (e) { return []; }
  return check(f, ctx || { url: page.url() });
}

function doc() {
  const by = {};
  RULES.forEach(r => (by[r.area] = by[r.area] || []).push(r));
  const S = { critical: '🔴 Критично', major: '🟠 Важно', minor: '🟡 Мелочь', check: '⚪ Проверить глазами' };
  let s = '# База знаний QA-бота: общие баги\n\n' +
    'Это список типичных проблем любого интернет-магазина на WordPress, о которых владелец может не догадываться.\n' +
    'Бот проверяет каждую страницу по этому списку (код: `tools/qa/kb.js`). Уникальные баги владелец сообщает сам.\n' +
    'Список обновляется командой `node tools/qa/kb-run.js --doc`. Новые правила — в `RULES` в `tools/qa/kb.js`.\n\n' +
    `Всего правил: ${RULES.length}.\n`;
  for (const [a, rs] of Object.entries(by)) {
    s += `\n## ${a}\n\n| Что ищет | Важность | Почему это важно | Как исправить |\n|---|---|---|---|\n`;
    rs.forEach(r => { s += `| ${r.title} | ${S[r.sev]} | ${r.why} | ${r.fix} |\n`; });
  }
  s += '\n## Известные исключения (не считать багом)\n\n';
  for (const [id, xs] of Object.entries(IGNORE)) xs.forEach(x => { s += `- ${RULE[id].title}: \`${x.re.source}\` — ${x.why}\n`; });
  return s;
}

module.exports = { RULES, IGNORE, run, check, collect, doc };
