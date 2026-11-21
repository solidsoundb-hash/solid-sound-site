# Роботы-аналитики сайта — 2026-11-21 19:46 UTC

Запущены: 🧹 Мёртвый код (4 с), 🛒 Покупатель (74 с), 💶 Цены (48 с), 🎛 Товары (19 с), 🔎 SEO страниц (441 с), ✍️ Тексты (137 с), ♿ Доступность (264 с), ✉️ Письма (5 с), 🛡 Безопасность (61 с), 🗄 Сервер (60 с).

## ⚠️ Главное

- Покупка ломается (компьютер) на шаге «Корзина»
- Покупка ломается (Samsung A31) на шаге «Корзина»
- SEO: 7 серьёзных проблем на страницах
- Доступность: 2 критичных нарушений
- Сервер: 1 опасных файлов открыто из интернета

Для Claude: 🔴 — чинить сразу, 🟠 — в ближайшей правке, 🟡/⚪ — по возможности. Правило «скрины до и после» действует.

---

## 🧹 Код: мёртвый код и ошибки

Проверено файлов: **87** (2112 КБ).

| Проверка | Найдено |
|---|---|
| 🔴 Ошибки синтаксиса | 0 |
| 🔴 Глобальное имя перекрыто (как баг с плеером 21.11) | 0 |
| 🟠 Модули, которые не подключены | 0 |
| 🟠 Файлы сборки, которые никто не грузит | 0 |
| 🟡 PHP-функции без вызовов | 3 |
| 🟡 JS-функции без вызовов | 0 |
| 🟡 Наши CSS-классы, которых нет в разметке | 37 |
| 🟡 setInterval (правило: только MutationObserver/rAF) | 16 |
| 🟡 Скрипты без defer | 2 |
| ⚪ console.log в коде | 1 |
| ⚪ Повторы кода (6+ одинаковых строк) | 26 |

### 🟡 PHP-функции, которые нигде не вызываются
- `plugin/solid-sound-custom/src/custom.php:6839` — ss_is_vendor_user()
- `plugin/solid-sound-custom/src/modules/account.php:18` — ss_acc_is_vendor_only()
- `plugin/solid-sound-custom/src/modules/packages.php:51` — ss_pk_html()

### 🟡 CSS-классы без разметки (кандидаты на удаление — проверить, не ставит ли их чужой код)
- `.ss-ap-format`
- `.ss-ap-mp3`
- `.ssh-lead`
- `.ssh-lt`
- `.ssh-ls`
- `.ssh-tag-presets`
- `.ssh-tag-music`
- `.ssh-svc`
- `.ssh-sn`
- `.ssh-sp`
- `.ssh-svc-all`
- `.ssh-col-free`
- `.ssh-col-svc`
- `.ssh-from`
- `.ssh-tier-best`
- `.ssh-tier-trending`
- `.ss-soc`
- `.ss-cart-n`
- `.ss-ap-version`
- `.ss-ap-daw`
- `.ss-ds-off`
- `.ss-ap-genre`
- `.ss-ff-x1`
- `.ss-ff-x2`
- `.ss-ap-mood`
- `.ss-ap-ext`
- `.ss-ap-dl`
- `.ss-ap-bpm`
- `.ss-ap-key`
- `.ss-pp-buy`
- `.ss-wm-send`
- `.ss-ap-send`
- `.ssw-sub`
- `.ssg-tri-btn`
- `.ssw-rate`
- `.ss-hide`
- `.ss-lead-msg`

### 🟡 setInterval
- `plugin/solid-sound-custom/src/account/dashboard.js:134` — `setInterval(function(){ var ta=D.querySelector('#ssb textarea, .ssb-in textarea'); n++; if`
- `plugin/solid-sound-custom/src/custom.php:1992` — `setInterval(ss,1500);apply();`
- `plugin/solid-sound-custom/src/custom.php:2619` — `setInterval(fix,600);})();</script>`
- `plugin/solid-sound-custom/src/custom.php:4726` — `setInterval(upd,600);document.addEventListener('ssp:state',upd);`
- `plugin/solid-sound-custom/src/custom.php:4951` — `setInterval(function(){if(!document.body.contains(root)||over||document.hidden||Date.now()`
- `plugin/solid-sound-custom/src/custom.php:5422` — `setInterval(lift,800);addEventListener('resize',lift);`
- `plugin/solid-sound-custom/src/custom.php:6171` — `setInterval(function(){if(wrapSSP()||++tries>40)clearInterval(iv)},500)}}`
- `plugin/solid-sound-custom/src/home/app.template.html:669` — `setInterval(function () { self.setState({ tick: Date.now() }); }, 1000);`
- `plugin/solid-sound-custom/src/home/app.template.html:670` — `setInterval(function () {`
- `plugin/solid-sound-custom/src/home/patches.js:69` — `setInterval(function () { self.setState({ tick: Date.now() }); }, 60000);").join("this._cd`
- `plugin/solid-sound-custom/src/home/patches.js:69` — `setInterval(function () { self.setState({ tick: Date.now() }); }, 1000);")`
- `plugin/solid-sound-custom/src/home/patches.js:70` — `setInterval(function () { self.setState({ tick: Date.now() }); }, 1000);").join("this._cd `
- `plugin/solid-sound-custom/src/home/patches.js:70` — `setInterval(function () { var L = self.cd(), S = self.cd(true), n = document.querySelector`
- `plugin/solid-sound-custom/src/ss-global.js:341` — `setInterval(function(){ var t=p.querySelector('.p-t'); if(t) t.textContent=promoLeft(); },`
- `plugin/solid-sound-custom/src/ss-global.js:388` — `setInterval(playIcons,800);`
- `plugin/solid-sound-custom/src/ss-global.js:433` — `setInterval(paint,700); window.addEventListener('resize',function(){ paint(true); },{passi`

### 🟡 Скрипты без defer
- `plugin/solid-sound-custom/src/modules/prices.php:14`
- `plugin/solid-sound-custom/src/modules/prices.php:15`

### ⚪ Повторы кода
- `plugin/solid-sound-custom/src/audio/ss-audio.js:269` = `plugin/solid-sound-custom/src/custom.php:3266`
- `plugin/solid-sound-custom/src/modules/home-wow.php:239` = `plugin/solid-sound-custom/src/modules/home-wow.php:250`
- `plugin/solid-sound-custom/src/home/ai-search.js:2` = `plugin/solid-sound-custom/src/ss-global.js:391`
- `plugin/solid-sound-custom/src/home/ai-search.js:8` = `plugin/solid-sound-custom/src/ss-global.js:397`
- `plugin/solid-sound-custom/src/home/ai-search.js:18` = `plugin/solid-sound-custom/src/ss-global.js:406`
- `plugin/solid-sound-custom/src/home/ai-search.js:43` = `plugin/solid-sound-custom/src/ss-global.js:435`
- `plugin/solid-sound-custom/src/home/ai-search.js:49` = `plugin/solid-sound-custom/src/ss-global.js:441`
- `plugin/solid-sound-custom/src/home/ai-search.js:55` = `plugin/solid-sound-custom/src/ss-global.js:447`
- `plugin/solid-sound-custom/src/home/ai-search.js:61` = `plugin/solid-sound-custom/src/ss-global.js:453`
- `plugin/solid-sound-custom/src/home/ai-search.js:67` = `plugin/solid-sound-custom/src/ss-global.js:459`
- `plugin/solid-sound-custom/src/home/ai-search.js:73` = `plugin/solid-sound-custom/src/ss-global.js:465`
- `plugin/solid-sound-custom/src/home/ai-search.js:87` = `plugin/solid-sound-custom/src/ss-global.js:479`
- `plugin/solid-sound-custom/src/home/ai-search.js:96` = `plugin/solid-sound-custom/src/ss-global.js:488`
- `plugin/solid-sound-custom/src/home/ai-search.js:102` = `plugin/solid-sound-custom/src/ss-global.js:494`
- `plugin/solid-sound-custom/src/home/ai-search.js:108` = `plugin/solid-sound-custom/src/ss-global.js:500`
- … и ещё 11

### Самые большие файлы
| Файл | КБ | Строк |
|---|---|---|
| `plugin/solid-sound-custom/src/custom.php` | 643 | 6927 |
| `plugin/solid-sound-custom/src/home/app.template.html` | 130 | 1139 |
| `plugin/solid-sound-custom/src/modules/home-wow.php` | 103 | 807 |
| `plugin/solid-sound-custom/src/ss-global.js` | 70 | 627 |
| `plugin/solid-sound-custom/src/modules/services-prices-html.php` | 69 | 33 |
| `plugin/solid-sound-custom/src/modules/files-first.php` | 55 | 358 |
| `plugin/solid-sound-custom/src/theme/player.js` | 52 | 611 |
| `plugin/solid-sound-custom/src/home/home.js` | 51 | 563 |

---

## 🛒 Покупатель: путь до оформления заказа

Робот проходит покупку как клиент (без оплаты). ❌ — здесь путь ломается, снимок в `reports/sitebots/shots/`.

**компьютер** — ❌ сломано на шаге «Корзина»

| Шаг | Время | Итог |
|---|---|---|
| Главная | 5.7 с | ✅  |
| Каталог | 5.0 с | ✅ первый товар: /product/time-goes-fast-documentary-piano/ |
| Страница товара | 8.6 с | ✅ Time Goes Fast Documentary Piano – Cinematic Royalty-Free Mu |
| Добавить в корзину | 4.3 с | ✅  |
| Корзина | 5.2 с | ❌ page.goto: net::ERR_ABORTED at https://solid-sound.net/en/cart/ |
| Оформление (форма видна) | — | пропущено |

**Samsung A31** — ❌ сломано на шаге «Корзина»

| Шаг | Время | Итог |
|---|---|---|
| Главная | 3.2 с | ✅  |
| Каталог | 5.8 с | ✅ первый товар: /product/time-goes-fast-documentary-piano/ |
| Страница товара | 11.4 с | ✅ Time Goes Fast Documentary Piano – Cinematic Royalty-Free Mu |
| Добавить в корзину | 4.2 с | ✅  |
| Корзина | 3.8 с | ❌ page.goto: net::ERR_ABORTED at https://solid-sound.net/en/cart/ |
| Оформление (форма видна) | — | пропущено |


---

## 💶 Цены: одна цена везде

Товаров: **8**, со скидкой в магазине: **7**, с расхождениями: **0**.

| Товар | Обычная | Магазин | Google | На странице | Что не так |
|---|---|---|---|---|---|
| [Time Goes Fast Documentary Piano](https://solid-sound.net/product/time-goes-fast-documentary-piano/) | 15 | 15 | 15 | 15 | ✅ |
| [Melodic Techno Presets](https://solid-sound.net/product/melodic-techno-presets/) | 9 | 4.5 | 4.5 | 4.5 | ✅ |
| [Pitch](https://solid-sound.net/product/pitch/) | 24 | 12 | 12 | 12 | ✅ |
| [Mutualism](https://solid-sound.net/product/mutualism/) | 24 | 12 | 12 | 12 | ✅ |
| [Intricate](https://solid-sound.net/product/intricate/) | 24 | 12 | 12 | 12 | ✅ |
| [Infinity](https://solid-sound.net/product/infinity/) | 24 | 12 | 12 | 12 | ✅ |
| [Kratos](https://solid-sound.net/product/kratos/) | 24 | 12 | 12 | 12 | ✅ |
| [Protos](https://solid-sound.net/product/protos/) | 24 | 12 | 12 | 12 | ✅ |

---

## 🎛 Товары: всё ли на месте и открывается

Товаров: **8**. Сломано (файл не открывается / нельзя купить): **0**. Неполных: **8**. Тексты и картинки для Google — в недельном отчёте `reports/weekly`.

| Товар | BPM · тон | Превью | Что не так |
|---|---|---|---|
| [Time Goes Fast Documentary Piano](https://solid-sound.net/product/time-goes-fast-documentary-piano/) | — | ✅ 1 | 🟡 только одна картинка (галерея: скрин проекта в Ableton, список каналов — помогает продавать)<br>🟡 не указан BPM<br>🟡 не указана тональность<br>⚪ нет отзывов (звёзды в Google появятся с первым отзывом) |
| [Melodic Techno Presets](https://solid-sound.net/product/melodic-techno-presets/) | — | ✅ 1 | 🟡 только одна картинка (галерея: скрин проекта в Ableton, список каналов — помогает продавать)<br>⚪ нет отзывов (звёзды в Google появятся с первым отзывом) |
| [Pitch](https://solid-sound.net/product/pitch/) | 130 · Bm | ✅ 1 | 🟡 только одна картинка (галерея: скрин проекта в Ableton, список каналов — помогает продавать)<br>⚪ нет отзывов (звёзды в Google появятся с первым отзывом) |
| [Mutualism](https://solid-sound.net/product/mutualism/) | G | ✅ 1 | 🟡 только одна картинка (галерея: скрин проекта в Ableton, список каналов — помогает продавать)<br>🟡 не указан BPM<br>⚪ нет отзывов (звёзды в Google появятся с первым отзывом) |
| [Intricate](https://solid-sound.net/product/intricate/) | 130 · G | ✅ 1 | 🟡 только одна картинка (галерея: скрин проекта в Ableton, список каналов — помогает продавать)<br>⚪ нет отзывов (звёзды в Google появятся с первым отзывом) |
| [Infinity](https://solid-sound.net/product/infinity/) | 130 · Am | ✅ 1 | 🟡 только одна картинка (галерея: скрин проекта в Ableton, список каналов — помогает продавать)<br>⚪ нет отзывов (звёзды в Google появятся с первым отзывом) |
| [Kratos](https://solid-sound.net/product/kratos/) | 128 | ✅ 1 | 🟡 только одна картинка (галерея: скрин проекта в Ableton, список каналов — помогает продавать)<br>🟡 не указана тональность<br>⚪ нет отзывов (звёзды в Google появятся с первым отзывом) |
| [Protos](https://solid-sound.net/product/protos/) | 128 | ✅ 1 | 🟡 только одна картинка (галерея: скрин проекта в Ableton, список каналов — помогает продавать)<br>🟡 не указана тональность<br>⚪ нет отзывов (звёзды в Google появятся с первым отзывом) |

---

## 🔎 SEO: страницы глазами Google

Проверено страниц из карты сайта: **110**. 🔴 7 · 🟠 9 · 🟡 44 · ⚪ 10

robots.txt: ✅ (карта сайта указана) · карта сайта: ✅ 110 адресов

| | Проблема | Страниц | Примеры |
|---|---|---|---|
| 🔴 | закрыта от Google (noindex), а стоит в карте сайта | 7 | /en/cart/, /en/checkout/, /en/my-account/ |
| 🟠 | в карте сайта адрес с переадресацией → /en/my-account/ | 2 | /en/store-manager/, /en/client-dashboard/ |
| 🟠 | в карте сайта адрес с переадресацией → /en/sell/ | 2 | /en/vendor-membership/, /en/vendor-register/ |
| 🟠 | в карте сайта адрес с переадресацией → /en/all-products/ | 1 | /en/shop/ |
| 🟠 | в карте сайта адрес с переадресацией → /en/cart/ | 1 | /en/checkout/ |
| 🟠 | в карте сайта адрес с переадресацией → /studio-calculator/?s=mixing | 1 | /en/services-calculator/ |
| 🟠 | в карте сайта адрес с переадресацией → /studio-calculator/?s=mastering | 1 | /en/mastering-album-calculator/ |
| 🟠 | в карте сайта адрес с переадресацией → /en/ | 1 | /en/forum-experimental/ |
| 🟡 | нет ссылок на языковые версии (hreflang) | 31 | /en/all-products/, /en/privacy-policy/, /en/shop/ |
| 🟡 | нет заголовка H1 | 6 | /en/cart/, /en/checkout/, /en/my-account/ |
| 🟡 | заголовок короткий (18 зн.) | 3 | /en/cart/, /en/checkout/, /en/links/ |
| 🟡 | заголовок длинный (67 зн., Google обрежет после ~60) | 3 | /en/wiki/how-much-do-djs-get-paid/, /product/melodic-techno-presets/, /product/time-goes-fast-documentary-piano/ |
| 🟡 | описание длинное (193 зн.) | 1 | /en/services-prices/ |
| ⚪ | нет рейтинга (звёзды в Google появятся только с отзывами) | 8 | /product/protos/, /product/kratos/, /product/infinity/ |
| ⚪ | H1 несколько (2) | 2 | /en/all-products/, /en/shop/ |

### Один заголовок на нескольких страницах
- «Ableton Templates, Serum Presets & Music / Solid Sound» — /en/all-products/, /en/shop/
- «Cart – Solid Sound» — /en/cart/, /en/checkout/
- «My Account – Solid Sound» — /en/my-account/, /en/store-manager/, /en/client-dashboard/
- «Sell Your Music – Solid Sound» — /en/vendor-membership/, /en/vendor-register/, /en/sell/
- «Studio Price Calculator – Mixing, Mastering, Remix / Solid Sound» — /en/services-calculator/, /en/mastering-album-calculator/
- «Ableton Templates, Presets & Mixing/Mastering / Solid Sound» — /en/forum-experimental/, /en/, /ua/, /de/, /fr/, /pt/, /es/, /it/
- «Solid Wiki – Solid Sound» — /en/wiki/, /ua/wiki-ua/, /de/wiki-de/, /fr/wiki-fr/, /pt/wiki-pt/, /es/wiki-es/, /it/wiki-it/

### Одно описание на нескольких страницах
- «All Solid Sound products in one place: royalty-free Ableton Live templ…» — 2 стр.: /en/all-products/, /en/shop/
- «Your cart at Solid Sound: royalty-free Ableton templates, Serum preset…» — 2 стр.: /en/cart/, /en/checkout/
- «Your Solid Sound account: orders, downloads and studio projects in one…» — 3 стр.: /en/my-account/, /en/store-manager/, /en/client-dashboard/
- «Sell your Ableton templates, presets and sample packs on Solid Sound. …» — 3 стр.: /en/vendor-membership/, /en/vendor-register/, /en/sell/
- «Instant prices for mixing, mastering (stereo, stem, multitrack), remix…» — 2 стр.: /en/services-calculator/, /en/mastering-album-calculator/
- «Royalty-free Ableton Live templates, Serum presets and music for video…» — 7 стр.: /en/forum-experimental/, /en/, /ua/, /de/


---

## ✍️ Тексты: опечатки и ошибки (английский)

Проверено: 12 страниц и 8 описаний товаров. Возможных ошибок: **27**.

| Слово | Что не так | Как исправить | Где | Фрагмент |
|---|---|---|---|---|
| kbps | Spelling mistake | KBS / KBP | /en/services-prices/, товар «Infinity», товар «Protos» | …WAV 24-bit 44.1 kHz + MP3 320 kbps ✓ Optional extras Stems +10 €… |
|  and | Use a comma before ‘and’ if it connects two independent clauses (unless they are closely connected and short). | , and | /en/faq/, /en/terms/ | …or Operator) Please contact us and we’ll try to solve this issue… |
| Anouch Anouch | Word repetition | Anouch | /en/services-prices/ | …nd Real messages from clients Anouch Anouch Marco L. Marco L. Marco L. Ma… |
| package Package | Word repetition | package | /en/services-prices/ | …x Ultra from 450€ Label-ready package Package Versions Radio edit What is i… |
| books | Possible agreement error | book | /en/get-heard/, /en/wiki/ | …ed at clubs and festivals Who books, when they book, and the mess… |
| Sølid | Spelling mistake | Solid / Slid | /en/terms/, /en/legal-notice/ | …olid-sound.net is operated by Sølid Søund Studio, Cannes, France … |
| Søund | Spelling mistake | Sound | /en/terms/, /en/legal-notice/ | …ound.net is operated by Sølid Søund Studio, Cannes, France (detai… |
| Presets Preset | You’ve repeated a verb. Did you mean to only write one of them? | Presets / Preset | /en/all-products/ | …inematic · 1 3 Melodic Techno Presets Preset · Melodic Techno · 1 1 Melodi… |
| services Services | Word repetition | services | /en/services-prices/ | …s / Solid Sound Online studio services Services & Prices Professional sound r… |
| Multiband | Spelling mistake | — | /en/services-prices/ | …tion (corrective and tonal) ✓ Multiband compression for dynamics cont… |
| Kedavra | Spelling mistake | Kadabra | /en/services-prices/ | …ut killing the dynamics ▶ Ava Kedavra Mud and rumble removed, tight… |
| low end | Did you mean the adjective “high-end” (spelled with a hyphen)? | high-end | /en/services-prices/ | …Mud and rumble removed, tight low end Real messages from clients An… |
| de-essing | Spelling mistake | dressing / depressing | /en/services-prices/ | …oval ✓ Voice EQ, compression, de-essing ✓ Loudness for the platform (… |
| showreel | Spelling mistake | show reel | /en/services-prices/ | …e Ask a question ▶ Film & ads showreel Sound design, Foley, music an… |
| FREE Free | Word repetition | FREE | /en/why-solid-sound/ | …. Join the forum → Solid Wiki FREE Free guides by a working engineer:… |
| WooPayments | Spelling mistake | Woo Payments | /en/faq/ | … our payment partners Stripe (WooPayments) and PayPal. What can I do wi… |
| royalty free | Compound | royalty-free | /en/faq/ | …e inside». Moreover, they are royalty free so you can use samples, MIDI … |
| VSTi | Spelling mistake | VSTI / Asti | /en/faq/ | …ase install and (or) activate VSTi version of Massive/Sylenth1/S… |
| Init | Spelling mistake | Unit / IIT | /en/faq/ | …erum tracks have the default (Init) preset loaded. Please update… |
| from | Possible typo | form | /en/faq/ | …. Please contact us using the from below. I purchased the templa… |
|  but | Use a comma before ‘but’ if it connects two independent clauses (unless they are closely connected and short). | , but | /en/faq/ | …elow. I purchased the template but I don’t have required plugins… |
| me | Consider adding a comma here to clarify the structure of the sentence. | me, | /en/sell/ | …ucts — please upload them for me I have 5+ products — please u… |
| Solid Sound Solid Sound | Duplicated phrase | Solid Sound | /en/get-heard/ | …by Club & Festival Managers / Solid Sound Solid Sound · Artist support Get your set… |
| Mixcloud | Spelling mistake | Mix cloud | /en/get-heard/ | …. 1 Send one link SoundCloud, Mixcloud, YouTube or a private downloa… |
| licence | Possible spelling mistake. ‘licence’ is British English. | license | /en/terms/ | …License Agreement . You buy a licence to use the content, not owner… |
| fxp | Spelling mistake | FDP / FP | товар «Melodic Techno Presets» | …resets pack: Serum Presets (*.fxp) Required plugins: Serum v1.3… |
| wavetables | Spelling mistake | wavetable / wave tables | товар «Melodic Techno Presets» | …ds and start creating. Change wavetables, filters and effects to fit y… |


---

## ♿ Доступность (контраст, подписи, клавиатура)

Проверено страниц: **18** (9 адресов × компьютер и A31). Правил нарушено: 🔴 2 · 🟠 4 · 🟡 2 · ⚪ 1

| | Что не так | Элементов | Страниц | Пример (где искать) |
|---|---|---|---|---|
| 🔴 | неполная ARIA-структура | 4 | 4 | `.tasks` |
| 🔴 | поле формы без подписи | 2 | 2 | `#sc-range` |
| 🟠 | текст плохо читается (мало контраста с фоном) | 18 | 18 | `.ss-cc-open` |
| 🟠 | кнопка внутри кнопки/ссылки | 16 | 6 | `.ssw-match[role="button"]` |
| 🟠 | прокручиваемый блок недоступен с клавиатуры | 4 | 2 | `ol[data-dc-tpl="691"]` |
| 🟠 | Links must be distinguishable without relying on color | 2 | 2 | `.muted > a` |
| 🟡 | часть текста вне разметки областей | 90 | 18 | `.p-b` |
| 🟡 | заголовки идут не по порядку | 1 | 1 | `h3[data-dc-tpl="400"]` |
| ⚪ | Table header text should not be empty | 2 | 2 | `#pr-mixing > .pr-tw > table > tfoot > tr > .pr-f` |

---

## ✉️ Письма: дойдут ли до клиента

| | Настройка | Состояние |
|---|---|---|
| ✅ | Почтовый сервер (MX) | mx.ukraine.com.ua. |
| 🟡 | SPF (кому можно слать от нашего имени) | `v=spf1 include:_spf.ukraine.com.ua ~all` — нет Brevo (include:spf.brevo.com), письма Brevo могут уйти в спам |
| ✅ | DKIM (подпись писем) | найдено: brevo1, brevo2 |
| 🟡 | DMARC (политика против подделки) | `v=DMARC1; p=none; rua=mailto:rua@dmarc.brevo.com` — только наблюдение (p=none), можно ужесточить позже |
| ✅ | Домен подтверждён в Brevo | да |

⏸ Статистика доставки Brevo — нужен ключ: секрет `BREVO_API_KEY` в GitHub (Settings → Secrets → Actions). Ключ создаётся в Brevo: SMTP & API → API keys.

---

## 🛡 Безопасность сайта снаружи

То, что видит любой посетитель или взломщик (без входа). Устаревшие плагины — в недельном отчёте `reports/weekly`.

- 🟠 нет заголовка HSTS (только HTTPS)
- 🟠 список пользователей открыт через /wp-json/wp/v2/users (3 имён; сами имена в отчёт не пишу — репозиторий публичный) — облегчает подбор паролей
- 🟠 xmlrpc.php включён — через него подбирают пароли (если Jetpack не нужен — выключить)
- 🟡 нет заголовка X-Content-Type-Options
- 🟡 нет заголовка защита от встраивания в чужой сайт (X-Frame-Options)
- 🟡 на странице видна версия WordPress 7.1.5
- ⚪ нет заголовка Referrer-Policy
- ⚪ нет заголовка Content-Security-Policy
- ⚪ нет заголовка Permissions-Policy
- ⚪ readme.html показывает, что это WordPress (мелочь) (`/readme.html`)

---

## 🗄 Сервер: что лежит на хостинге и не нужно

Файлов: **461**, всего **10.2 МБ**. Робот только смотрит, ничего не удаляет.

| Папка | Размер |
|---|---|
| `solid-sound.net/www` | 10.2 МБ |
| `solid-sound.net/www/wp-content/uploads` | 0.0 МБ |
| `solid-sound.net/www/wp-content/plugins` | 0.0 МБ |
| `solid-sound.net/www/wp-content/themes` | 0.0 МБ |
| `solid-sound.net/www/wp-content/cache` | 0.0 МБ |
| `solid-sound.net/www/wp-content` | 0.0 МБ |
| `solid-sound.net/_removed` | 0.0 МБ |
| `solid-sound.net/landingpt` | 0.0 МБ |
| `solid-sound.net/post` | 0.0 МБ |
| `solid-sound.net/ssl` | 0.0 МБ |
| `solid-sound.net/studio` | 0.0 МБ |
| `solid-sound.net/templates` | 0.0 МБ |
| `solid-sound.net/test` | 0.0 МБ |
| `solid-sound.net/www_old` | 0.0 МБ |

Загрузки по годам: 

### 🔴 Опасные файлы, доступные из интернета (1)
Копии базы, архивы, логи, копии wp-config — их может скачать кто угодно. Удалить или перенести выше папки www.

- `/.htaccess.bak` — 0.0 МБ

### 🟠 Папки старых копий (1)
- `solid-sound.net/_removed` — 0.0 МБ

### Самые большие файлы
| Файл | Размер |
|---|---|
| `solid-sound.net/www/wp-includes/formatting.php` | 0.3 МБ |
| `solid-sound.net/www/wp-includes/post.php` | 0.3 МБ |
| `solid-sound.net/www/wp-includes/functions.php` | 0.3 МБ |
| `solid-sound.net/www/wp-includes/media.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/class-wp-theme-json.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/class-wp-xmlrpc-server.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/class-wp-customize-manager.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/certificates/ca-bundle.crt` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/deprecated.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/general-template.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/user.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/taxonomy.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/ID3/module.audio-video.quicktime.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/class-wp-query.php` | 0.2 МБ |
| `solid-sound.net/www/wp-includes/script-loader.php` | 0.2 МБ |

