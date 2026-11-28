// DESIGN-WATCH (Designer, 28.11.2026, owner via «Задачи»): competitors' design ↔ ours, measured from the DOM (not by eye).
// Pages: home, catalog, product · devices: desktop 1366×768 + phone A31 412×780.
// Measures: buy button (size, colour, contrast, radius, text, first screen), sliders (slides, arrows, dots, autoplay),
// header (height, menu items, sticky), price (size, next to button, old price), covers (size, ratio, cards on 1st screen),
// demo player, reviews/stars, badges, fonts, entry popup, page weight and speed.
// Polite: public pages only, robots.txt (UA SolidSoundQA), pause per host, NO clicks, NO forms, NO login. Cookie/e-mail popups are
// hidden with CSS only for the screenshot, never clicked.
// Output: reports/designer/design-watch.md + .json, memory.json; pictures → shots-design/ (workflow pushes it to branch shots-design).
// MODE=probe → ours + 1 competitor (sites.json "probe"); MODE=run → all. Exam: node tools/bots/design/exam.js
'use strict';
const fs = require('fs');

const UA_PC = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';
const UA_PH = 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36 SolidSoundQA/1.0';
const DEVICES = [
  { id: 'pc', label: 'ПК 1366', o: { viewport: { width: 1366, height: 768 }, userAgent: UA_PC } },
  { id: 'phone', label: 'A31 412×780', o: { viewport: { width: 412, height: 780 }, isMobile: true, hasTouch: true, deviceScaleFactor: 1, userAgent: UA_PH } }
];
const PAGES = ['home', 'catalog', 'product'];
const PAGE_RU = { home: 'Главная', catalog: 'Каталог', product: 'Товар' };

// ---------- pure helpers (tested by the exam) ----------
const BUY_RE = /\b(add to (cart|bag|basket)|buy( now| it| this)?|purchase|get (it|it now|now|instant access)|order now|in den warenkorb|jetzt kaufen|kaufen|ajouter au panier|añadir al carrito|comprar)\b/i;
const BUY_NOT_RE = /\b(gift card|buy me|how to buy|login|log in|sign|subscribe|newsletter|learn more|wishlist|buy credits|membership)\b/i;
const isBuyText = (t) => { t = String(t || '').replace(/\s+/g, ' ').trim(); return t.length > 0 && t.length <= 48 && BUY_RE.test(t) && !BUY_NOT_RE.test(t); };
const PRICE_RE = /^(from\s*|ab\s*|à partir de\s*)?(([$€£]|us\$|eur|usd)\s?\d{1,4}([.,]\d{2})?|\d{1,4}([.,]\d{2})?\s?(€|\$|£|eur|usd))(\s*(usd|eur))?$/i;
const isPriceText = (t) => PRICE_RE.test(String(t || '').replace(/\s+/g, ' ').trim());
function parseColor(c) {
  const m = String(c || '').match(/rgba?\(\s*([\d.]+)[,\s]+([\d.]+)[,\s]+([\d.]+)(?:[,\s/]+([\d.]+%?))?/i);
  if (!m) return null; let a = m[4] == null ? 1 : (m[4].endsWith('%') ? parseFloat(m[4]) / 100 : +m[4]);
  return { r: +m[1], g: +m[2], b: +m[3], a };
}
const lum = ({ r, g, b }) => { const f = (v) => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); }; return 0.2126 * f(r) + 0.7152 * f(g) + 0.0722 * f(b); };
function contrast(fg, bg) { const a = typeof fg === 'string' ? parseColor(fg) : fg, b = typeof bg === 'string' ? parseColor(bg) : bg; if (!a || !b) return null;
  const L1 = lum(a), L2 = lum(b); return Math.round(((Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05)) * 10) / 10; }
const hex = (c) => { const p = typeof c === 'string' ? parseColor(c) : c; return p ? '#' + [p.r, p.g, p.b].map(v => Math.round(v).toString(16).padStart(2, '0')).join('') : ''; };
function median(a) { const v = a.filter(x => typeof x === 'number' && isFinite(x)).sort((x, y) => x - y); if (!v.length) return null; const m = v.length >> 1; return v.length % 2 ? v[m] : (v[m - 1] + v[m]) / 2; }
function quart(a, q) { const v = a.filter(x => typeof x === 'number' && isFinite(x)).sort((x, y) => x - y); if (!v.length) return null; const i = (v.length - 1) * q, lo = Math.floor(i); return v[lo] + (v[Math.ceil(i)] - v[lo]) * (i - lo); }
// Ours vs competitors → short mark. Numbers: outside the middle half (p25–p75) of competitors; yes/no: against ≥60 % majority.
function verdict(type, comp, ours, better) {
  const vals = comp.filter(v => v != null); if (ours == null || vals.length < 2) return '';
  if (type === 'bool') { const yes = vals.filter(Boolean).length / vals.length; if (yes >= 0.6 && !ours) return '⚠️ у большинства есть'; if (yes <= 0.4 && ours) return 'ℹ️ у большинства нет'; return '✅'; }
  const lo = quart(vals, 0.25), hi = quart(vals, 0.75); if (ours >= lo && ours <= hi) return '✅';
  const up = ours > hi; if (!better) return up ? '⬆ больше' : '⬇ меньше';
  return (better === 'up') === up ? (up ? '⭐ лучше (больше)' : '⭐ лучше (меньше)') : (up ? '⚠️ больше' : '⚠️ меньше');
}
function robotsAllowed(txt, path) { // rules for SolidSoundQA, else for *
  const mine = [], star = []; let m = false, s = false, prevUA = false;
  for (const line of String(txt || '').split(/\r?\n/)) { const l = line.replace(/#.*/, '').trim(); if (!l) continue; const i = l.indexOf(':'); if (i < 0) continue;
    const k = l.slice(0, i).trim().toLowerCase(), v = l.slice(i + 1).trim();
    if (k === 'user-agent') { if (!prevUA) { m = false; s = false; } prevUA = true; if (/solidsoundqa/i.test(v)) m = true; if (v === '*') s = true; continue; }
    prevUA = false; if (k !== 'allow' && k !== 'disallow') continue; const r = { allow: k === 'allow', p: v }; if (m) mine.push(r); if (s) star.push(r); }
  const rules = mine.length ? mine : star; let best = null;
  for (const r of rules) { if (!r.p) continue; const re = new RegExp('^' + r.p.replace(/[.+?^${}()|[\]\\]/g, '\\$&').replace(/\*/g, '.*').replace(/\\\$$/, '$'));
    if (re.test(path) && (!best || r.p.length > best.p.length || (r.p.length === best.p.length && r.allow))) best = r; }
  return !best || best.allow;
}

// ---------- what is measured (rows of the report) ----------
// type num|bool|text; better: up/down = which way is better for sales (only where it is clear); pages: where it makes sense.
const METRICS = [
  ['speed', 'kb', 'Вес страницы, КБ', 'num', 'down', PAGES], ['speed', 'req', 'Запросов', 'num', 'down', PAGES],
  ['speed', 'lcp', 'LCP (главная картинка/текст), мс', 'num', 'down', PAGES], ['speed', 'fcp', 'Первый показ, мс', 'num', 'down', PAGES],
  ['popup', 'popup', 'Всплывающее окно на входе (почта/скидка)', 'bool', null, ['home']],
  ['header', 'hdrH', 'Шапка: высота, px', 'num', null, PAGES], ['header', 'navN', 'Шапка: пунктов меню (видно)', 'num', null, ['home']],
  ['header', 'sticky', 'Шапка липкая при прокрутке', 'bool', null, ['home']], ['header', 'search', 'Поиск в шапке', 'bool', null, ['home']],
  ['header', 'cartIcon', 'Корзина в шапке', 'bool', null, ['home']], ['header', 'bar', 'Полоса-объявление над шапкой', 'bool', null, ['home']],
  ['hero', 'h1px', 'Главный заголовок: размер, px', 'num', null, ['home', 'product']],
  ['slider', 'sliders', 'Слайдеров/каруселей на странице', 'num', null, ['home', 'product']],
  ['slider', 'heroSlider', 'Слайдер на первом экране', 'bool', null, ['home']], ['slider', 'slides', 'Слайдов в первом слайдере', 'num', null, ['home']],
  ['slider', 'arrows', 'Стрелки у слайдера', 'bool', null, ['home']], ['slider', 'dots', 'Точки у слайдера', 'bool', null, ['home']],
  ['slider', 'autoplay', 'Слайдер крутится сам', 'bool', null, ['home']], ['slider', 'heroH', 'Высота первого слайдера, px', 'num', null, ['home']],
  ['buy', 'buyN', 'Кнопок «купить/в корзину» на странице', 'num', null, ['home', 'catalog']],
  ['buy', 'buyFold', 'Кнопка «купить» видна на первом экране', 'bool', 'up', ['product']],
  ['buy', 'buyW', 'Кнопка «купить»: ширина, px', 'num', null, ['product']], ['buy', 'buyH', 'Кнопка «купить»: высота, px', 'num', 'up', ['product']],
  ['buy', 'buyFont', 'Кнопка «купить»: шрифт, px', 'num', null, ['product']], ['buy', 'buyRadius', 'Кнопка «купить»: скругление, px', 'num', null, ['product']],
  ['buy', 'buyContrast', 'Кнопка «купить»: контраст текста', 'num', 'up', ['product']], ['buy', 'buyBgPop', 'Кнопка «купить»: контраст с фоном страницы', 'num', 'up', ['product']],
  ['buy', 'buyColor', 'Кнопка «купить»: цвет', 'text', null, ['product']], ['buy', 'buyText', 'Кнопка «купить»: текст', 'text', null, ['product']],
  ['buy', 'buyStick', 'Кнопка «купить» прилипает при прокрутке', 'bool', null, ['product']],
  ['price', 'pricePx', 'Цена: размер шрифта, px', 'num', 'up', ['product']], ['price', 'priceFold', 'Цена видна на первом экране', 'bool', 'up', ['product']],
  ['price', 'priceGap', 'Цена ↔ кнопка: расстояние, px', 'num', 'down', ['product']], ['price', 'oldPrice', 'Старая цена (зачёркнутая) видна', 'bool', null, ['catalog', 'product']],
  ['cards', 'cardsFold', 'Карточек товара на первом экране', 'num', null, ['home', 'catalog']], ['cards', 'perRow', 'Карточек в ряду', 'num', null, ['catalog']],
  ['cards', 'coverW', 'Обложка в каталоге: ширина, px', 'num', null, ['catalog']], ['cards', 'coverRatio', 'Обложка: ширина/высота', 'num', null, ['catalog']],
  ['cards', 'mainCoverW', 'Обложка товара: ширина, px', 'num', null, ['product']],
  ['player', 'player', 'Плеер демо на странице', 'bool', 'up', ['catalog', 'product']], ['player', 'playN', 'Кнопок «play» на странице', 'num', null, ['catalog', 'product']],
  ['player', 'video', 'Видео на странице', 'bool', null, ['product']],
  ['reviews', 'stars', 'Звёзды/рейтинг видно', 'bool', null, ['catalog', 'product']], ['reviews', 'reviewsN', 'Отзывов указано', 'num', null, ['product']],
  ['badges', 'badges', 'Бейджей (Sale, New, −50 %…)', 'num', null, ['home', 'catalog']],
  ['fonts', 'font', 'Шрифт текста', 'text', null, ['home']], ['fonts', 'fontH', 'Шрифт заголовков', 'text', null, ['home']],
  ['fonts', 'bodyPx', 'Текст: размер, px', 'num', null, ['product']], ['fonts', 'families', 'Разных шрифтов на странице', 'num', null, ['home']],
  ['look', 'pageBg', 'Фон страницы', 'text', null, ['home']], ['look', 'dark', 'Тёмная тема', 'bool', null, ['home']]
];
const GROUP_RU = { speed: 'Скорость и вес', popup: 'Окна', header: 'Шапка', hero: 'Заголовок', slider: 'Слайдеры и карусели', buy: 'Кнопка «купить»', price: 'Цена', cards: 'Карточки и обложки', player: 'Плеер демо', reviews: 'Отзывы', badges: 'Бейджи', fonts: 'Шрифты', look: 'Общий вид' };

// ---------- in-page measuring (runs inside the browser) ----------
function pageMeasure(arg) {
  const BUY = new RegExp(arg.buy, 'i'), NOTB = new RegExp(arg.notBuy, 'i'), PRICE = new RegExp(arg.price, 'i'); const vh = innerHeight, vw = innerWidth;
  const st = (e) => getComputedStyle(e);
  const vis = (e) => { const r = e.getBoundingClientRect(), s = st(e); return r.width > 3 && r.height > 3 && s.visibility !== 'hidden' && s.display !== 'none' && +s.opacity > 0.05; };
  const R = (e) => { const r = e.getBoundingClientRect(); return { x: r.left, y: r.top + scrollY, w: r.width, h: r.height, b: r.bottom + scrollY }; };
  const T = (e) => (e.innerText || e.value || e.getAttribute('aria-label') || '').replace(/\s+/g, ' ').trim();
  // background: own colour, else first colour of a gradient, else the parent's (buttons are often gradients)
  const bgOf = (e) => { for (let n = e; n && n.nodeType === 1; n = n.parentElement) { const s = st(n), c = s.backgroundColor; if (c && !/^rgba\(.*,\s*0\)$|transparent/.test(c)) return c;
    const g = (s.backgroundImage || '').match(/rgba?\([^)]+\)/); if (g && !/,\s*0\)$/.test(g[0])) return g[0]; } return 'rgb(255, 255, 255)'; };
  // header/menu/footer/drawers: semantic tags + fixed layers (not by class words — body classes like «menu-…» would hide everything)
  const fixedUp = (e) => { for (let n = e.parentElement; n && n !== document.body; n = n.parentElement) if (st(n).position === 'fixed') return true; return false; };
  const inChrome = (e) => !!e.closest('header,nav,footer,[role=banner],[role=navigation],[role=contentinfo],[aria-modal=true],[data-dw-hdr],#ssg,#ssg-bar,#ssg-sheet') || fixedUp(e);
  const out = {};
  // fonts and look
  const b = document.body; out.pageBg = bgOf(b); const pb = (out.pageBg.match(/\d+/g) || [255, 255, 255]).slice(0, 3).map(Number); out.dark = (0.2126 * pb[0] + 0.7152 * pb[1] + 0.0722 * pb[2]) < 90;
  const fam = (e) => st(e).fontFamily.split(',')[0].replace(/["']/g, '').trim();
  const firstVis = (sel) => [...document.querySelectorAll(sel)].find(vis);
  const p = firstVis('main p, p'), h1 = firstVis('h1'), h2 = firstVis('h2');
  const ps = [...document.querySelectorAll('main p, p, li')].filter(e => vis(e) && T(e).length > 40).slice(0, 40).map(e => parseFloat(st(e).fontSize)).sort((a, z) => a - z);
  // main heading = biggest h1/h2 on the first screen (a hidden or tiny SEO h1 must not count)
  const big = [...document.querySelectorAll('h1, h2, [class*=title], [class*=heading]')].filter(e => vis(e) && T(e).length > 2 && T(e).length < 120 && R(e).y < vh && !e.closest('header,nav,footer,[role=banner],#ssg')).map(e => ({ e, px: parseFloat(st(e).fontSize) })).sort((a, z) => z.px - a.px)[0];
  out.font = fam(p || b); out.fontH = fam((big && big.e) || h1 || h2 || b); out.bodyPx = ps.length ? Math.round(ps[ps.length >> 1]) : Math.round(parseFloat(st(b).fontSize)); out.h1px = big ? Math.round(big.px) : null;
  const fs = new Set(); [...document.querySelectorAll('h1,h2,h3,p,a,button,span,li')].slice(0, 600).forEach(e => { if (e.childElementCount === 0 && T(e) && vis(e)) fs.add(fam(e)); }); out.families = fs.size;
  // header
  let hd = [...document.querySelectorAll('header, [role=banner], #header, .header, .site-header, #shopify-section-header, #ssg')].filter(vis).map(e => ({ e, r: R(e) })).filter(o => o.r.y < 200 && o.r.w > vw * 0.6).sort((a, z) => a.r.y - z.r.y)[0];
  if (hd && hd.r.h > 320) { const c = [...hd.e.querySelectorAll('*')].filter(vis).map(e => ({ e, r: R(e) })).find(o => o.r.w > vw * 0.6 && o.r.h > 30 && o.r.h < 200); if (c) hd = c; }
  if (hd) { out.hdrH = Math.round(hd.r.b - Math.max(0, hd.r.y)); const links = [...hd.e.querySelectorAll('a, button')].filter(e => vis(e) && T(e).length > 1 && T(e).length < 30 && R(e).y < hd.r.b);
    out.navN = links.length; out.search = !!hd.e.querySelector('input[type=search], [class*=search], [aria-label*=earch i], a[href*=search]'); out.cartIcon = !!hd.e.querySelector('a[href*=cart], [class*=cart], [aria-label*=art i], [id*=cart]');
    out._hdrSel = true; hd.e.setAttribute('data-dw-hdr', '1'); }
  const bar = [...document.querySelectorAll('[class*=announcement], [class*=promo-bar], [class*=top-bar], [class*=topbar], [id*=announcement]')].filter(vis).find(e => R(e).y < 80 && R(e).h < 80); out.bar = !!bar;
  // buy buttons
  const cands = [...document.querySelectorAll('button, a, input[type=submit], input[type=button], [role=button]')].filter(e => vis(e) && !inChrome(e)).map(e => ({ e, t: T(e) })).filter(o => o.t.length <= 48 && BUY.test(o.t) && !NOTB.test(o.t));
  out.buyN = cands.length;
  if (arg.kind === 'product' && cands.length) {
    const top = cands.map(o => ({ ...o, r: R(o.e) })).filter(o => o.r.w >= 40 && o.r.h >= 20).sort((a, z) => (a.r.y < vh * 1.6 ? 0 : 1) - (z.r.y < vh * 1.6 ? 0 : 1) || z.r.w * z.r.h - a.r.w * a.r.h)[0] || { ...cands[0], r: R(cands[0].e) };
    const s = st(top.e), bg = bgOf(top.e), fg = s.color;
    out.buyW = Math.round(top.r.w); out.buyH = Math.round(top.r.h); out.buyFont = Math.round(parseFloat(s.fontSize)); out.buyRadius = Math.min(99, Math.round(parseFloat(s.borderTopLeftRadius) || 0));
    out._buyFg = fg; out._buyBg = bg; out._pageBg = out.pageBg; out.buyText = top.t.slice(0, 40); out.buyFold = top.r.y + top.r.h / 2 < vh; out._buyY = top.r.y + top.r.h / 2; out._buyX = top.r.x + top.r.w / 2;
    top.e.setAttribute('data-dw-buy', '1');
  }
  // prices
  const prices = [...document.querySelectorAll('span, div, p, strong, b, s, del, ins, bdi, h2, h3, h4, [class*=price]')].filter(e => { const t = T(e); return t.length < 26 && PRICE.test(t) && vis(e) && !inChrome(e); })
    .filter(e => ![...e.children].some(c => PRICE.test(T(c)) && T(c) === T(e)));
  // old price: crossed out (own style or up to 3 parents), inside s/del, or a class word compare/was/old/regular (whole word — «bold», «placeholder» must not count)
  const struck = (e) => { if (e.closest('s, del')) return true; for (let n = e, k = 0; n && k < 4; n = n.parentElement, k++) { if (/line-through/.test(st(n).textDecorationLine || '')) return true;
    if (/(^|[\s_-])(compare(-at)?|was|old|regular)([\s_-]|$)/i.test(typeof n.className === 'string' ? n.className : '')) return true; } return false; };
  out.oldPrice = prices.some(e => struck(e) && R(e).y < vh * 3);
  if (arg.kind === 'product') { const main = prices.filter(e => !struck(e) && R(e).y < vh * 1.6).map(e => ({ e, r: R(e), px: parseFloat(st(e).fontSize) })).sort((a, z) => z.px - a.px)[0];
    if (main) { out.pricePx = Math.round(main.px); out.priceFold = main.r.y + main.r.h / 2 < vh; if (out._buyY != null) { const dx = Math.max(0, Math.abs(main.r.x + main.r.w / 2 - out._buyX) - main.r.w / 2), dy = Math.max(0, Math.abs(main.r.y + main.r.h / 2 - out._buyY) - main.r.h / 2); out.priceGap = Math.round(Math.hypot(dx, dy)); } }
    else { out.priceFold = false; } }
  // sliders / carousels
  const SL = '.swiper, .swiper-container, .slick-slider, .splide, .flickity-enabled, .glide, .owl-carousel, .keen-slider, .embla, .flex-viewport, [aria-roledescription=carousel], [class*=slider], [class*=carousel], [class*=slideshow], [data-slider], [data-carousel]';
  let sl = [...document.querySelectorAll(SL)].filter(e => vis(e) && R(e).w > 200 && R(e).h > 80 && !/slider-?(range|track|thumb|handle)|range-slider|price-slider|volume/i.test(String(e.className)) && !e.closest('[aria-modal=true]'));
  [...document.querySelectorAll('body *')].slice(0, 4000).forEach(e => { const s = st(e); if (s.scrollSnapType && s.scrollSnapType !== 'none' && /auto|scroll/.test(s.overflowX) && e.scrollWidth > e.clientWidth + 40 && vis(e) && R(e).w > 200) sl.push(e); });
  sl = sl.filter((e, i, a) => a.indexOf(e) === i && !a.some(o => o !== e && o.contains(e)));
  out.sliders = sl.length;
  if (sl.length) { const f = sl.map(e => ({ e, r: R(e) })).sort((a, z) => a.r.y - z.r.y)[0]; const e = f.e;
    let slides = e.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate), .slick-slide:not(.slick-cloned), .splide__slide:not(.splide__slide--clone), .carousel-cell, .glide__slide:not(.glide__slide--clone), .owl-item:not(.cloned), .keen-slider__slide, .embla__slide, [class*=slide]:not([class*=slider]):not([class*=slides])').length;
    if (!slides) { let n = e; while (n && n.children.length === 1) n = n.children[0]; slides = n ? n.children.length : 0; }
    out.slides = slides; out.heroSlider = f.r.y < vh * 0.9 && f.r.h > vh * 0.25; out.heroH = Math.round(f.r.h);
    const q = (s) => [...e.querySelectorAll(s)].concat(e.parentElement ? [...e.parentElement.querySelectorAll(s)] : []).some(vis);
    out.arrows = q('[class*=prev], [class*=next], [class*=arrow], [aria-label*=revious i], [aria-label*=ext i]');
    out.dots = q('.swiper-pagination-bullet, .slick-dots li, .splide__pagination__page, .flickity-page-dots .dot, .glide__bullet, .owl-dot, [class*=dots] > *, [class*=bullet], [class*=pagination] button, [role=tab]');
    e.setAttribute('data-dw-slider', '1'); }
  // cards and covers
  // product card = a same-site link with a picture ≥80 px whose card box also shows a price (works for any shop URL scheme)
  const imgs = []; const seen = new Set(); const boxOf = (a) => { for (let n = a, k = 0; n && n !== document.body && k < 6; n = n.parentElement, k++) if ([...n.querySelectorAll('span,div,p,strong,bdi,ins')].some(x => x.childElementCount === 0 && PRICE.test(T(x)))) return n; return null; };
  [...document.querySelectorAll('a[href]')].filter(a => { try { return new URL(a.href).host === location.host && !inChrome(a) && !/cart|account|review|login/i.test(a.getAttribute('href')); } catch (e) { return false; } }).forEach(a => {
    const i = [...a.querySelectorAll('img')].find(x => vis(x) && R(x).w >= 80 && R(x).h >= 60); if (!i || seen.has(i) || !boxOf(a)) return; seen.add(i); imgs.push(R(i)); });
  out.cardsFold = imgs.filter(r => r.y < vh && r.y + r.h * 0.5 > 0 && r.x >= -5 && r.x + r.w <= vw + 5).length;
  if (imgs.length) { const ws = imgs.map(r => r.w).sort((a, z) => a - z); out.coverW = Math.round(ws[ws.length >> 1]); const rs = imgs.map(r => r.w / r.h).sort((a, z) => a - z); out.coverRatio = Math.round(rs[rs.length >> 1] * 100) / 100;
    const rows = {}; imgs.filter(r => r.x >= -5 && r.x + r.w <= vw + 5).forEach(r => { const k = Math.round(r.y / 20); rows[k] = (rows[k] || 0) + 1; }); out.perRow = Math.max(0, ...Object.values(rows)) || null; }
  if (arg.kind === 'product') { const big = [...document.querySelectorAll('main img, img')].filter(vis).map(R).filter(r => r.y < vh * 1.5 && r.w > 120).sort((a, z) => z.w * z.h - a.w * a.h)[0]; if (big) out.mainCoverW = Math.round(big.w); }
  // player
  const plays = [...document.querySelectorAll('button, a, [role=button], [class*=play], [aria-label*=lay i]')].filter(e => vis(e) && !inChrome(e) && (/\bplay\b|play-?(btn|button|icon)|preview|listen/i.test(String(e.className) + ' ' + (e.getAttribute('aria-label') || '') + ' ' + (e.getAttribute('title') || '')) || /^(▶|►|play|listen|preview)$/i.test(T(e))));
  out.playN = plays.length; out.player = plays.length > 0 || !!document.querySelector('audio, [class*=waveform], [class*=wave-surfer], wave, [class*=audio-player], iframe[src*=soundcloud], iframe[src*=spotify]');
  out.video = !![...document.querySelectorAll('video, iframe[src*=youtube], iframe[src*=vimeo], [class*=video]')].find(vis);
  // reviews / stars / badges
  out.stars = !![...document.querySelectorAll('[class*=star], [class*=rating], [aria-label*=rated i], [aria-label*=stars i], .jdgm-prev-badge, .spr-badge, .yotpo')].find(e => vis(e) && !inChrome(e)) || /★{3,}/.test(document.body.innerText.slice(0, 20000));
  if (arg.kind === 'product') { const m = document.body.innerText.match(/(\d[\d,.]*)\s+(customer\s+)?(reviews?|ratings?|bewertungen|avis)\b/i); out.reviewsN = m ? parseInt(m[1].replace(/[,.]/g, ''), 10) : 0; }
  out.badges = [...document.querySelectorAll('[class*=badge], [class*=ribbon], [class*=label], [class*=tag], [class*=sale], [class*=flag], span, div')].filter(e => { const t = T(e); return t.length > 1 && t.length < 18 && e.childElementCount <= 1 && /^(sale|on sale|new|hot|free|best ?seller|top|deal|-\s?\d{1,2}\s?%|\d{1,2}\s?% off|save \d+%?)$/i.test(t) && vis(e) && !inChrome(e); }).length;
  return out;
}
// entry popup: a fixed/dialog layer covering ≥25 % of the screen, not the header
function popupCheck() { const vw = innerWidth, vh = innerHeight;
  return [...document.querySelectorAll('body *')].slice(0, 5000).some(e => { const s = getComputedStyle(e); if (s.position !== 'fixed' && !e.matches('dialog[open], [aria-modal=true]')) return false; if (s.display === 'none' || s.visibility === 'hidden' || +s.opacity < 0.1) return false;
    const r = e.getBoundingClientRect(); if (e.closest('[data-dw-hdr], header, #ssg, #ssg-bar')) return false; const a = Math.max(0, Math.min(r.right, vw) - Math.max(r.left, 0)) * Math.max(0, Math.min(r.bottom, vh) - Math.max(r.top, 0));
    return a > vw * vh * 0.25 && r.height > 120 && !/cookie|consent|gdpr/i.test(e.id + ' ' + e.className + ' ' + (e.innerText || '').slice(0, 200)); }); }
const HIDE_CSS = '#onetrust-banner-sdk,#onetrust-consent-sdk,#CybotCookiebotDialog,.cc-window,.cookie-notice,#cookie-notice,[id*=cookie-banner],[class*=cookie-banner],[id*=cookieconsent],[class*=cookie-consent],[id*=klaviyo],[class*=klaviyo-form],.needsclick[role=dialog],#shopify-pc__banner,[class*=newsletter-popup],[class*=popup-overlay],.pum-overlay,#attentive_overlay{display:none!important}';
// hide (never click) cookie bars and big entry popups so they do not cover the screenshot and the measures
function hideLayers() { const vw = innerWidth, vh = innerHeight;
  [...document.querySelectorAll('body *')].slice(0, 5000).forEach(e => { const s = getComputedStyle(e); if (s.position !== 'fixed' || s.display === 'none') return; if (e.closest('header, #ssg, #ssg-bar, [data-dw-hdr]')) return;
    const r = e.getBoundingClientRect(); const big = r.width * r.height > vw * vh * 0.25 && r.height > 120; const t = (e.innerText || '').slice(0, 400);
    if (/cookie|consent|privacy|gdpr/i.test(t + ' ' + e.id + ' ' + e.className) && r.height < vh * 0.7 || big && /off|discount|subscribe|newsletter|sign up|join|e-?mail/i.test(t)) e.style.setProperty('display', 'none', 'important'); }); }
const sliderSig = () => { const e = document.querySelector('[data-dw-slider]'); if (!e) return ''; const parts = [];
  e.querySelectorAll('*').forEach((n, i) => { if (i > 300) return; const s = getComputedStyle(n); if (s.transform && s.transform !== 'none') parts.push(s.transform); if (n.scrollLeft) parts.push('L' + n.scrollLeft); if (/active|current|selected|is-selected/.test(String(n.className))) parts.push(i); });
  return parts.join('|').slice(0, 2000); };

// ---------- browser run ----------
async function run() {
  const { chromium } = require('playwright');
  const CFG = JSON.parse(fs.readFileSync(__dirname + '/design/sites.json', 'utf8'));
  const MODE = process.env.MODE || 'run'; const OUT = process.env.OUT || 'shots-design'; const REP = 'reports/designer';
  fs.mkdirSync(OUT, { recursive: true }); fs.mkdirSync(REP, { recursive: true });
  let sites = [{ ...CFG.ours, ours: true }].concat(CFG.competitors.filter(c => MODE !== 'probe' || c.name === CFG.probe));
  if (process.env.ONLY) sites = sites.filter(s => s.ours || process.env.ONLY.split(',').includes(s.name));
  const robots = {}, last = {}; const log = []; const t0 = Date.now();
  const sleep = (ms) => new Promise(r => setTimeout(r, ms));
  async function allowed(url) { const u = new URL(url); if (!(u.origin in robots)) { try { const r = await fetch(u.origin + '/robots.txt', { headers: { 'User-Agent': UA_PC }, signal: AbortSignal.timeout(20000) }); robots[u.origin] = r.status === 200 ? await r.text() : ''; } catch (e) { robots[u.origin] = ''; } }
    return robotsAllowed(robots[u.origin], u.pathname + u.search); }
  const slug = (s) => s.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  const data = { date: new Date().toISOString().slice(0, 16).replace('T', ' ') + ' UTC', mode: MODE, sites: {} };
  for (const site of sites) {
    const S = data.sites[site.name] = { ours: !!site.ours, pages: {}, urls: {} };
    for (const dev of DEVICES) {
      const ctx = await browser.newContext({ ...dev.o, locale: 'en-US' }); const page = await ctx.newPage();
      for (const kind of PAGES) {
        let url = kind === 'product' ? (site.product || S.urls.product) : site[kind];
        const key = kind + '/' + dev.id;
        if (!url) { S.pages[key] = { error: 'нет адреса товара (не нашёл ссылку в каталоге)' }; continue; }
        if (!(await allowed(url))) { S.pages[key] = { error: 'robots.txt запрещает' }; log.push(`${site.name} ${key}: robots.txt запрещает`); continue; }
        const host = new URL(url).host; const w = (last[host] || 0) + (site.ours ? 2 : 1) * (CFG.pauseMs || 4000) - Date.now(); if (w > 0) await sleep(w); last[host] = Date.now();
        let kb = 0, req = 0; const cdp = await ctx.newCDPSession(page); await cdp.send('Network.enable'); cdp.on('Network.loadingFinished', e => { kb += e.encodedDataLength; req++; });
        const errs = []; const onErr = (e) => errs.push(String(e.message).slice(0, 100)); page.on('pageerror', onErr);
        await page.addInitScript(() => { window.__dw = { lcp: 0 }; try { new PerformanceObserver(l => { for (const e of l.getEntries()) window.__dw.lcp = e.startTime; }).observe({ type: 'largest-contentful-paint', buffered: true }); } catch (e) {} });
        let status = 0; for (let tryN = 0; tryN < 2; tryN++) { try { const r = await page.goto(url, { waitUntil: 'load', timeout: 60000 }); status = r ? r.status() : 0; } catch (e) { log.push(`${site.name} ${key}: ${e.message.slice(0, 80)}`); }
          if (status !== 429 || tryN) break; await sleep(45000); } // hosting rate limit: one polite retry
        await page.waitForTimeout(4500);
        const body = await page.evaluate(() => document.body ? document.body.innerText.slice(0, 3000) : '').catch(() => '');
        if (status >= 400 || /cf-chl|captcha|verify you are human|access denied|Захищена сторінка/i.test(body)) { S.pages[key] = { error: status === 404 ? 'страница не найдена (404) — поправить адрес в sites.json' : `сайт ограничил доступ (HTTP ${status}), не обходим` }; await cdp.detach().catch(() => {}); page.off('pageerror', onErr); continue; }
        const popup = kind === 'home' ? await page.evaluate(popupCheck).catch(() => null) : null;
        await page.addStyleTag({ content: HIDE_CSS }).catch(() => {});
        await page.evaluate(hideLayers).catch(() => {});
        await page.evaluate(() => scrollTo(0, 0)); await page.waitForTimeout(400);
        const m = await page.evaluate(pageMeasure, { buy: BUY_RE.source, notBuy: BUY_NOT_RE.source, price: PRICE_RE.source, kind }).catch(e => ({ error: 'замер не удался: ' + e.message.slice(0, 80) }));
        const shot = `${slug(site.name)}-${kind}-${dev.id}.jpg`; await page.screenshot({ path: `${OUT}/${shot}`, type: 'jpeg', quality: 62 }).catch(() => {});
        if (kind === 'home' && m.sliders) { const a = await page.evaluate(sliderSig); await page.waitForTimeout(6500); const z = await page.evaluate(sliderSig); m.autoplay = !!a && a !== z; }
        // sticky header / sticky buy: scroll down 1.5 screens, see what is still on top/bottom
        await page.evaluate(() => scrollTo(0, Math.min(document.body.scrollHeight, innerHeight * 1.5))); await page.waitForTimeout(900);
        const stick = await page.evaluate(() => { const h = document.querySelector('[data-dw-hdr]'); const top = document.elementsFromPoint(innerWidth / 2, 8).concat(document.elementsFromPoint(30, 8));
          const hdr = !!h && top.some(e => h.contains(e) || e.contains(h)) || top.some(e => /fixed|sticky/.test(getComputedStyle(e).position) && e.getBoundingClientRect().height < 200 && e.getBoundingClientRect().width > innerWidth * 0.6);
          const re = /add to (cart|bag|basket)|buy|purchase|in den warenkorb/i; const buy = [...document.querySelectorAll('button, a, input[type=submit]')].some(e => { const r = e.getBoundingClientRect(); return r.top >= 0 && r.bottom <= innerHeight && r.height > 20 && re.test(e.innerText || e.value || '') && /fixed|sticky/.test((function f(n) { for (; n && n.nodeType === 1; n = n.parentElement) { const p = getComputedStyle(n).position; if (p === 'fixed' || p === 'sticky') return p; } return ''; })(e)); });
          return { hdr, buy }; }).catch(() => ({}));
        if (kind === 'home') m.sticky = !!stick.hdr; if (kind === 'product') m.buyStick = !!stick.buy;
        if (kind === 'catalog' && !site.product && !S.urls.product) S.urls.product = await page.evaluate((h) => { const a = [...document.querySelectorAll('a[href*="/products/"], a[href*="/product/"]')].map(a => a.href).find(x => { try { const u = new URL(x); return u.host === h && !/cart|review|#/.test(x) && /\/products?\/[^/?#]+/.test(u.pathname) && !/\/product\/?$/.test(u.pathname); } catch (e) { return false; } }); return a || ''; }, host).catch(() => '');
        const perf = await page.evaluate(() => { const p = performance.getEntriesByName('first-contentful-paint')[0]; return { fcp: p ? Math.round(p.startTime) : null, lcp: Math.round((window.__dw || {}).lcp || 0) || null }; }).catch(() => ({}));
        Object.assign(m, { kb: Math.round(kb / 1024), req, fcp: perf.fcp, lcp: perf.lcp, popup, jsErr: errs.length, shot, url: page.url() });
        if (m._buyFg) { m.buyContrast = contrast(m._buyFg, m._buyBg); m.buyBgPop = contrast(m._buyBg, m._pageBg); m.buyColor = hex(m._buyBg); }
        if (m.pageBg) m.pageBg = hex(m.pageBg);
        for (const k of Object.keys(m)) if (k[0] === '_') delete m[k];
        S.pages[key] = m; await cdp.detach().catch(() => {}); page.off('pageerror', onErr);
        console.log(`${site.name} ${key}: ${m.error || 'ok'} (${m.kb} KB, buy ${m.buyN}, sliders ${m.sliders})`);
      }
      await ctx.close();
    }
  }
  await browser.close();
  data.minutes = Math.round((Date.now() - t0) / 6000) / 10; data.log = log;
  fs.writeFileSync(`${REP}/design-watch.json`, JSON.stringify(data, null, 1));
  const memory = remember(loadMemory(`${REP}/memory.json`), data); const md = report(data, memory); fs.writeFileSync(`${REP}/design-watch.md`, md); fs.writeFileSync(`${OUT}/report.md`, md);
  fs.writeFileSync(`${REP}/memory.json`, JSON.stringify(memory, null, 1));
  console.log(md.split('\n').slice(0, 12).join('\n'));
}

// ---------- statistics + report ----------
function stats(data) { // rows: {group,label,page,dev,type,comp:[...],ours,median,pct,verdict,n}
  const names = Object.keys(data.sites), comps = names.filter(n => !data.sites[n].ours), ours = names.find(n => data.sites[n].ours); const rows = [];
  for (const [group, key, label, type, better, pages] of METRICS) for (const pg of pages) for (const d of DEVICES) {
    const k = pg + '/' + d.id; const val = (n) => { const p = (data.sites[n].pages || {})[k]; return p && !p.error && p[key] !== undefined ? p[key] : null; };
    const comp = comps.map(val), o = ours ? val(ours) : null; const got = comp.filter(v => v != null); const row = { group, key, label, page: pg, dev: d.id, type, ours: o, n: got.length };
    if (type === 'num') { row.median = median(got); row.p25 = quart(got, 0.25); row.p75 = quart(got, 0.75); }
    else if (type === 'bool') row.pct = got.length ? Math.round(got.filter(Boolean).length / got.length * 100) : null;
    else { const c = {}; got.forEach(v => { c[v] = (c[v] || 0) + 1; }); row.top = Object.entries(c).sort((a, z) => z[1] - a[1]).slice(0, 3).map(([v, n]) => `${v}${n > 1 ? ' ×' + n : ''}`).join(', '); }
    row.verdict = type === 'text' ? '' : verdict(type, comp, o, better); row.comp = comp; rows.push(row); }
  return rows;
}
const fmt = (v, type) => v == null ? '—' : type === 'bool' ? (v ? 'да' : 'нет') : typeof v === 'number' ? (Math.abs(v) >= 100 ? Math.round(v) : Math.round(v * 10) / 10) : String(v);
function report(data, memory) {
  const rows = stats(data); const names = Object.keys(data.sites), comps = names.filter(n => !data.sites[n].ours);
  const L = [`# Дизайн конкурентов ↔ наш (design-watch)`, ``, `${data.date} · режим ${data.mode} · ${comps.length} конкурентов · ${data.minutes} мин · замеры из DOM, ПК 1366×768 и A31 412×780.`,
    `Медиана и «% сайтов» — по конкурентам, у которых страница открылась. Отметка: ✅ мы в середине (между 25 и 75 % конкурентов), ⭐ лучше, ⚠️ отстаём, ⬆/⬇ — просто отличаемся. Снимки — ветка \`shots-design\`.`, ``];
  const flags = rows.filter(r => /⚠️/.test(r.verdict)); const stars = rows.filter(r => /⭐/.test(r.verdict));
  const noise = new Set((memory.noise || []).map(x => x.id));
  L.push(`## Главное`, ``); if (!flags.length) L.push(`- Отставаний не найдено.`);
  for (const r of flags.filter(r => !noise.has(r.key + '@' + r.page + '/' + r.dev)).slice(0, 12)) L.push(`- ⚠️ ${PAGE_RU[r.page]}, ${r.dev === 'pc' ? 'ПК' : 'A31'}: ${r.label} — у нас **${fmt(r.ours, r.type)}**, у конкурентов ${r.type === 'bool' ? r.pct + ' % «да»' : 'медиана ' + fmt(r.median, r.type)} (n=${r.n})`);
  for (const r of stars.slice(0, 6)) L.push(`- ⭐ ${PAGE_RU[r.page]}, ${r.dev === 'pc' ? 'ПК' : 'A31'}: ${r.label} — у нас ${fmt(r.ours, r.type)}, медиана ${fmt(r.median, r.type)}`);
  const ch = (memory.lastChanges || []); if (ch.length) { L.push(``, `Изменилось со вчера: ${ch.slice(0, 8).join('; ')}`); }
  let g0 = ''; for (const pg of PAGES) { L.push(``, `## ${PAGE_RU[pg]}`, ``, `| Что | Устр. | У конкурентов | У нас | |`, `|---|---|---|---|---|`);
    for (const r of rows.filter(r => r.page === pg)) { const c = r.type === 'num' ? `медиана ${fmt(r.median, 'num')} (${fmt(r.p25, 'num')}–${fmt(r.p75, 'num')})` : r.type === 'bool' ? `${r.pct == null ? '—' : r.pct + ' %'} сайтов` : (r.top || '—');
      L.push(`| ${r.group !== g0 ? '**' + GROUP_RU[r.group] + '**: ' : ''}${r.label} | ${r.dev === 'pc' ? 'ПК' : 'A31'} | ${c} | ${fmt(r.ours, r.type)} | ${r.verdict} |`); g0 = r.group; } g0 = ''; }
  L.push(``, `## Каждый сайт (товар, ПК)`, ``, `| Сайт | Вес КБ | LCP мс | Кнопка | Цвет | Цена px | Слайдеров (гл.) | Плеер | Звёзды |`, `|---|---|---|---|---|---|---|---|---|`);
  for (const n of names) { const p = data.sites[n].pages['product/pc'] || {}, h = data.sites[n].pages['home/pc'] || {};
    L.push(`| ${data.sites[n].ours ? '**' + n + '**' : n} | ${p.error ? p.error : fmt(p.kb)} | ${fmt(p.lcp)} | ${p.buyText ? `«${p.buyText}» ${p.buyW}×${p.buyH}` : '—'} | ${p.buyColor || '—'} | ${fmt(p.pricePx)} | ${h.error ? '—' : fmt(h.sliders)} | ${fmt(p.player, 'bool')} | ${fmt(p.stars, 'bool')} |`); }
  const errs = []; for (const n of names) for (const [k, p] of Object.entries(data.sites[n].pages)) if (p.error) errs.push(`${n} ${k}: ${p.error}`);
  if (errs.length || data.log.length) L.push(``, `## Не открылось / пропущено`, ``, ...[...new Set(errs.concat(data.log))].map(e => `- ${e}`));
  L.push(``, `_Робот только смотрит: без кликов, форм и входа, robots.txt соблюдён, пауза ${4} с между страницами сайта. Совет «что менять» пишет Дизайнер (\`reports/designer/advice.md\`)._`);
  return L.join('\n') + '\n';
}
function loadMemory(f) { try { return JSON.parse(fs.readFileSync(f, 'utf8')); } catch (e) { return { _note: 'design-watch memory: runs, last values, noise (metric@page/dev that the Designer marked as useless — hidden from «Главное»), useful (marked helpful).', runs: [], noise: [], useful: [], last: {} }; } }
function remember(mem, data) { // keep last values per site/page/metric → «Изменилось со вчера»
  const changes = []; const now = {};
  for (const [n, s] of Object.entries(data.sites)) for (const [k, p] of Object.entries(s.pages)) { if (p.error) continue; const id = n + ' ' + k;
    now[id] = {}; for (const key of ['buyColor', 'buyText', 'buyH', 'pricePx', 'sliders', 'slides', 'hdrH', 'popup', 'oldPrice', 'badges']) if (p[key] !== undefined) now[id][key] = p[key];
    const was = (mem.last || {})[id]; if (was) for (const [key, v] of Object.entries(now[id])) if (was[key] !== undefined && was[key] !== v && !(typeof v === 'number' && Math.abs(v - was[key]) <= Math.max(2, Math.abs(was[key]) * 0.1))) changes.push(`${n} ${PAGE_RU[k.split('/')[0]]} ${k.split('/')[1]}: ${key} ${fmt(was[key])} → ${fmt(v)}`); }
  mem.runs = (mem.runs || []).concat([{ date: data.date, mode: data.mode, sites: Object.keys(data.sites).length, ok: Object.values(data.sites).reduce((a, s) => a + Object.values(s.pages).filter(p => !p.error).length, 0), minutes: data.minutes, changes: changes.length }]).slice(-60);
  mem.last = Object.assign(mem.last || {}, now); mem.lastChanges = changes.slice(0, 30); return mem;
}

module.exports = { isBuyText, isPriceText, parseColor, contrast, hex, median, quart, verdict, robotsAllowed, stats, report, remember, METRICS };
if (require.main === module) run().catch(e => { console.error(e); process.exit(1); });
