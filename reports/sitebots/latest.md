# Роботы-аналитики сайта — 2026-11-21 23:25 UTC

Запущены: 🛒 Покупатель (51 с), 🍪 Cookie/GDPR (140 с), 🔎 SEO страниц (1688 с), 🗄 Сервер (1327 с).

## ⚠️ Главное

- GDPR: данные уходят без согласия (Google Analytics, Meta Pixel, Meta Pixel, Google Analytics) — риск штрафа в ЕС
- SEO: 2 серьёзных проблем (см. раздел SEO)
- Сервер: 81 опасных файлов открыто из интернета

Для Claude: 🔴 — чинить сразу, 🟠 — в ближайшей правке, 🟡/⚪ — по возможности. Правило «скрины до и после» действует.

---

## 🧹 Код: мёртвый код и ошибки _(прошлый запуск)_

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

**компьютер** — ✅ весь путь пройден

| Шаг | Время | Итог |
|---|---|---|
| Главная | 2.8 с | ✅  |
| Каталог | 2.2 с | ✅ первый товар: /product/time-goes-fast-documentary-piano/ |
| Страница товара | 2.2 с | ✅ Time Goes Fast Documentary Piano – Cinematic Royalty-Free Mu |
| Добавить в корзину | 4.1 с | ✅  |
| Корзина | 4.6 с | ✅ товаров: 1, итого €15.00 |
| Оформление (форма видна) | 1.7 с | ✅ есть блок оплаты, быстрые кнопки оплаты есть. Саму оплату робот не делает — проходит ли она, видно только по реальному заказу |

**Samsung A31** — ✅ весь путь пройден

| Шаг | Время | Итог |
|---|---|---|
| Главная | 3.4 с | ✅  |
| Каталог | 2.4 с | ✅ первый товар: /product/time-goes-fast-documentary-piano/ |
| Страница товара | 2.0 с | ✅ Time Goes Fast Documentary Piano – Cinematic Royalty-Free Mu |
| Добавить в корзину | 4.2 с | ✅  |
| Корзина | 4.2 с | ✅ товаров: 1, итого €15.00 |
| Оформление (форма видна) | 1.7 с | ✅ есть блок оплаты, быстрые кнопки оплаты есть. Саму оплату робот не делает — проходит ли она, видно только по реальному заказу |


---

## 💶 Цены: одна цена везде _(прошлый запуск)_

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

## 🎛 Товары: всё ли на месте и открывается _(прошлый запуск)_

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

## 🔎 SEO: полная проверка глазами Google

Страниц обойдено: **597** (из карты сайта 97 + найдены по ссылкам). Картинок проверено: 120. 🔴 2 · 🟠 66 · 🟡 102 · ⚪ 366

robots.txt: ✅ (карта сайта указана) · карта сайта: ✅ 97 адресов · языки в hreflang: de, en, es, fr, it, pt, uk, x-default

### Проблемы на страницах

| | Проблема | Страниц | Где |
|---|---|---|---|
| 🔴 | битая ссылка на /fr/product-tag/inspirational/ (404) | 1 | /fr/product/time-goes-fast-documentary-piano/ |
| 🔴 | страница не открывается (404) | 1 | /fr/product-tag/inspirational/ |
| 🟠 | нет в карте сайта (Google найдёт её позже или не найдёт) | 51 | /en/why-solid-sound/, /en/get-heard/, /ua/product/time-goes-fast-documentary-piano/, /ua/product/infinity/, /ua/product/intricate/, /ua/product/pitch/, /ua/product/melodic-techno-presets/, /ua/product/mutualism/ … |
| 🟠 | canonical указывает на другую страницу | 13 | /en/community/say-hi/, /en/community/main-forum/, /en/community/main-forum/ideas-suggestions/, /en/community/rate-my-track/, /en/community/ableton-help/, /en/community/sound-design-presets/, /en/community/collabs-vocals/, /en/community/releasing-promo/ … |
| 🟠 | нет описания (description) | 1 | /pt/contactos/ |
| 🟡 | нет ссылок на языковые версии (hreflang) | 32 | /en/all-products/, /en/privacy-policy/, /en/refund_returns/, /en/license/, /en/faq/, /en/forum/, /en/free-demo-feedback/, /en/prices/ … |
| 🟡 | мало текста (151 слов) — «тонкая» страница | 31 | /en/contacts/, /en/presets/, /en/music/, /en/prices/, /en/free/, /en/legal-notice/, /ua/presety/, /ua/muzyka/ … |
| 🟡 | заголовок длинный (67 зн., Google обрежет после ~60) | 15 | /en/wiki/how-much-do-djs-get-paid/, /product/melodic-techno-presets/, /product/time-goes-fast-documentary-piano/, /ua/product/time-goes-fast-documentary-piano/, /ua/product/melodic-techno-presets/, /de/product/time-goes-fast-documentary-piano/, /de/product/melodic-techno-presets/, /fr/product/time-goes-fast-documentary-piano/ … |
| 🟡 | сирота | 14 | /en/prices/, /en/sitemap/, /ua/kontakty/, /ua/wiki-ua/, /de/kontakt/, /de/wiki-de/, /fr/contact/, /fr/wiki-fr/ … |
| 🟡 | статья вики без разметки Article | 3 | /en/wiki/how-much-do-djs-get-paid/, /en/wiki/how-to-promote-yourself-as-a-dj/, /en/wiki/how-to-get-booked-as-a-dj/ |
| 🟡 | описание короткое (12 зн.) | 3 | /en/community/recent/, /en/community/tags/, /en/community/ |
| 🟡 | FAQ без разметки FAQPage (вопросы могут показываться прямо в Google) | 1 | /en/faq/ |
| 🟡 | описание длинное (193 зн.) | 1 | /en/services-prices/ |
| 🟡 | нет картинки для соцсетей (og:image) | 1 | /pt/contactos/ |
| ⚪ | картинок без размеров (width/height) | 157 | /en/all-products/, /en/privacy-policy/, /en/refund_returns/, /en/license/, /en/contacts/, /en/faq/, /en/templates/, /en/presets/ … |
| ⚪ | нет хлебных крошек (BreadcrumbList) | 95 | /en/all-products/, /en/privacy-policy/, /en/refund_returns/, /en/license/, /en/contacts/, /en/faq/, /en/templates/, /en/presets/ … |
| ⚪ | товар без рейтинга (звёзды появятся с отзывами) | 56 | /product/protos/, /product/kratos/, /product/infinity/, /product/intricate/, /product/mutualism/, /product/pitch/, /product/melodic-techno-presets/, /product/time-goes-fast-documentary-piano/ … |
| ⚪ | товар без бренда (brand) | 56 | /product/protos/, /product/kratos/, /product/infinity/, /product/intricate/, /product/mutualism/, /product/pitch/, /product/melodic-techno-presets/, /product/time-goes-fast-documentary-piano/ … |
| ⚪ | несколько H1 | 1 | /en/all-products/ |
| ⚪ | нет hreflang x-default | 1 | /pt/contactos/ |

### 🟠 Нет в карте сайта (51)
Открыты для Google, но не перечислены в карте сайта:
- /en/why-solid-sound/
- /en/get-heard/
- /ua/product/time-goes-fast-documentary-piano/
- /ua/product/infinity/
- /ua/product/intricate/
- /ua/product/pitch/
- /ua/product/melodic-techno-presets/
- /ua/product/mutualism/
- /ua/product/kratos/
- /ua/product/protos/
- /de/product/time-goes-fast-documentary-piano/
- /de/product/infinity/
- /de/product/intricate/
- /de/product/pitch/
- /de/product/melodic-techno-presets/
- /de/product/mutualism/
- /de/product/kratos/
- /de/product/protos/
- /fr/product/time-goes-fast-documentary-piano/
- /fr/product/infinity/
- /fr/product/intricate/
- /fr/product/pitch/
- /fr/product/melodic-techno-presets/
- /fr/product/mutualism/
- /fr/product/kratos/
- /fr/product/protos/
- /pt/product/time-goes-fast-documentary-piano/
- /pt/product/infinity/
- /pt/product/intricate/
- /pt/product/pitch/
- /pt/product/melodic-techno-presets/
- /pt/product/mutualism/
- /pt/product/kratos/
- /pt/product/protos/
- /es/product/time-goes-fast-documentary-piano/
- /es/product/infinity/
- /es/product/intricate/
- /es/product/pitch/
- /es/product/melodic-techno-presets/
- /es/product/mutualism/

### Порядок в адресах
- 🟠 http://www → https: http://www.solid-sound.net/en/faq/ → 301 https://www.solid-sound.net/en/faq/ (нужно 301 сразу на https://solid-sound.net/en/faq/)
- 🟡 ссылки с параметром ?add-to-cart= на 78 страницах (например /en/all-products/) — Google видит дубли; закрыть такие адреса от индекса или убрать из ссылок

### Картинки
- Тяжелее 250 КБ: **0**
- JPEG/PNG больше 60 КБ (в WebP были бы в 2–3 раза легче): **33**

### Один заголовок на нескольких страницах
- «Music Production Forum / Solid Sound» — /en/forum/, /en/community/say-hi/, /en/community/main-forum/, /en/community/main-forum/ideas-suggestions/, /en/community/rate-my-track/, /en/community/ableton-help/, /en/community/sound-design-presets/, /en/community/collabs-vocals/, /en/community/releasing-promo/, /en/community/lounge/, /en/community/recent/, /en/community/tags/, /en/community/, /en/community/main-category/
- «Solid Wiki – Solid Sound» — /en/wiki/, /ua/wiki-ua/, /de/wiki-de/, /fr/wiki-fr/, /pt/wiki-pt/, /es/wiki-es/, /it/wiki-it/
- «Ableton Templates, Presets & Mixing/Mastering / Solid Sound» — /en/, /ua/, /de/, /fr/, /pt/, /es/, /it/
- «Protos – Techno Ableton Live Template / Solid Sound» — /product/protos/, /de/product/protos/, /es/product/protos/, /it/product/protos/
- «Kratos – Techno Ableton Live Template / Solid Sound» — /product/kratos/, /de/product/kratos/, /es/product/kratos/, /it/product/kratos/
- «Infinity – Techno Ableton Live Template / Solid Sound» — /product/infinity/, /de/product/infinity/, /es/product/infinity/, /it/product/infinity/
- «Intricate – Techno Ableton Live Template / Solid Sound» — /product/intricate/, /de/product/intricate/, /es/product/intricate/, /it/product/intricate/
- «Mutualism – Techno Ableton Live Template / Solid Sound» — /product/mutualism/, /de/product/mutualism/, /es/product/mutualism/, /it/product/mutualism/
- «Pitch – Techno Ableton Live Template / Solid Sound» — /product/pitch/, /de/product/pitch/, /es/product/pitch/, /it/product/pitch/
- «Melodic Techno Presets – Melodic Techno Serum Presets / Solid Sound» — /product/melodic-techno-presets/, /es/product/melodic-techno-presets/, /it/product/melodic-techno-presets/
- «Time Goes Fast Documentary Piano – Cinematic Royalty-Free Music / Solid Sound» — /product/time-goes-fast-documentary-piano/, /es/product/time-goes-fast-documentary-piano/, /it/product/time-goes-fast-documentary-piano/
- «Infinity – Techno Template Ableton Live / Solid Sound» — /fr/product/infinity/, /pt/product/infinity/
- «Intricate – Techno Template Ableton Live / Solid Sound» — /fr/product/intricate/, /pt/product/intricate/
- «Pitch – Techno Template Ableton Live / Solid Sound» — /fr/product/pitch/, /pt/product/pitch/
- «Melodic Techno Presets – Melodic Techno Presets Serum / Solid Sound» — /fr/product/melodic-techno-presets/, /pt/product/melodic-techno-presets/

### Одно описание на нескольких страницах
- «Royalty-free Ableton Live templates, Serum presets and music for video…» — 6 стр.: /en/, /ua/, /de/, /fr/
- «Protos is a full-length techno template for Ableton Live 10, produced …» — 3 стр.: /product/protos/, /es/product/protos/, /it/product/protos/
- «Kratos is a full-length dark techno template for Ableton Live 10, prod…» — 3 стр.: /product/kratos/, /es/product/kratos/, /it/product/kratos/
- «Infinity is a full-length Ableton Live 11 template produced by Grenør.…» — 3 стр.: /product/infinity/, /es/product/infinity/, /it/product/infinity/
- «Intricate is a full-length dark techno template for Ableton Live 11, p…» — 3 стр.: /product/intricate/, /es/product/intricate/, /it/product/intricate/
- «Mutualism is a fresh take on dark techno, inspired by Adam Beyer and V…» — 3 стр.: /product/mutualism/, /es/product/mutualism/, /it/product/mutualism/
- «Pitch is a techno template inspired by the legendary Dino Maggiorana. …» — 3 стр.: /product/pitch/, /es/product/pitch/, /it/product/pitch/
- «Tech specs Presets pack: Serum Presets (*.fxp) Compatible: PC and Mac …» — 3 стр.: /product/melodic-techno-presets/, /es/product/melodic-techno-presets/, /it/product/melodic-techno-presets/
- «"Time Goes Fast Documentary Piano" is a reflective and emotional piano…» — 3 стр.: /product/time-goes-fast-documentary-piano/, /es/product/time-goes-fast-documentary-piano/, /it/product/time-goes-fast-documentary-piano/

_Что видит сам Google (индекс, запросы, позиции, скорость у посетителей) — робот Search Console, ждёт ключ `GOOGLE_SA_JSON` (`docs/keys-todo.md`)._

---

## ✍️ Тексты: опечатки и ошибки (английский) _(прошлый запуск)_

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

## ♿ Доступность (контраст, подписи, клавиатура) _(прошлый запуск)_

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

## ✉️ Письма: дойдут ли до клиента _(прошлый запуск)_

| | Настройка | Состояние |
|---|---|---|
| ✅ | Почтовый сервер (MX) | mx.ukraine.com.ua. |
| 🟡 | SPF (кому можно слать от нашего имени) | `v=spf1 include:_spf.ukraine.com.ua ~all` — нет Brevo (include:spf.brevo.com), письма Brevo могут уйти в спам |
| ✅ | DKIM (подпись писем) | найдено: brevo1, brevo2 |
| 🟡 | DMARC (политика против подделки) | `v=DMARC1; p=none; rua=mailto:rua@dmarc.brevo.com` — только наблюдение (p=none), можно ужесточить позже |
| ✅ | Домен подтверждён в Brevo | да |

⏸ Статистика доставки Brevo — нужен ключ: секрет `BREVO_API_KEY` в GitHub (Settings → Secrets → Actions). Ключ создаётся в Brevo: SMTP & API → API keys.

---

## 🛡 Безопасность сайта снаружи _(прошлый запуск)_

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

## 🍪 Cookie и GDPR: новый посетитель из ЕС

Страницы: /en/, /product/infinity/, /en/all-products/. Отслеживаются запросы, которые отправляют данные (Meta, Google, TikTok, Hotjar, Clarity, LinkedIn).

| | Проверка | Итог |
|---|---|---|
| ✅ | Баннер согласия показывается новому посетителю | да |
| 🔴 | До выбора данные не уходят | уходят: Google Analytics, Meta Pixel |
| 🔴 | «Reject non-essential» — ничего не уходит | всё равно уходят: Meta Pixel, Google Analytics |
| ✅ | «Accept all» — пиксель Meta работает | уходят: Meta Pixel, Google Analytics |
| ✅ | «Cookie settings» в подвале снова открывает выбор | да |
| 🟡 | Сторонние cookie до выбора | _fbp (.solid-sound.net), sbjs_migrations (.solid-sound.net), sbjs_current_add (.solid-sound.net), sbjs_first_add (.solid-sound.net), sbjs_current (.solid-sound.net), sbjs_first (.solid-sound.net), sbjs_udata (.solid-sound.net), sbjs_session (.solid-sound.net), __cf_bm (.brevo.com), sib_cuid (.solid-sound.net) |

---

## 🗄 Сервер: что лежит на хостинге и не нужно

Файлов: **13231**, всего **1.19 ГБ** (обход остановлен на лимите — показано не всё). Робот только смотрит, ничего не удаляет.

| Папка | Размер |
|---|---|
| `solid-sound.net/www` | 1.19 ГБ |
| `solid-sound.net/www/wp-content` | 1.10 ГБ |
| `solid-sound.net/www/wp-content/uploads` | 946.5 МБ |
| `solid-sound.net/www/wp-content/plugins` | 87.0 МБ |
| `solid-sound.net/www/wp-content/themes` | 0.0 МБ |
| `solid-sound.net/www/wp-content/cache` | 0.0 МБ |
| `solid-sound.net/_removed` | 0.0 МБ |
| `solid-sound.net/landingpt` | 0.0 МБ |
| `solid-sound.net/post` | 0.0 МБ |
| `solid-sound.net/ssl` | 0.0 МБ |
| `solid-sound.net/studio` | 0.0 МБ |
| `solid-sound.net/templates` | 0.0 МБ |
| `solid-sound.net/test` | 0.0 МБ |
| `solid-sound.net/www_old` | 0.0 МБ |

Загрузки по годам: 

### 🔴 Опасные файлы, доступные из интернета (81)
Копии базы, архивы, логи, копии wp-config — их может скачать кто угодно. Удалить или перенести выше папки www.

- `/wp-content/uploads/wc-logs/woopayments-2026-11-01-1d5efedf99f4f1f8d6bda1026afcd804.log` — 0.3 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-12-c85c8c9fae478dabcd34183a9205e941.log` — 0.2 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-17-86f1294a30a0daa67305c444ee8b4cc2.log` — 0.2 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-04-be96e5a96fdb8adbbb82642e427563c0.log` — 0.2 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-20-5ad5c69e7856c9594be2ca57d5681bee.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-21-4ce36ccfd6a550b18c99a5f65157b978.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-19-6d3cf20df245d5b2aa6550ab4fee02dc.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-10-23-942005ddb0da1c49e4b9b8be84e55737.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-16-b7e3cb1d68185e9f159fc2c0c1677e28.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-15-36b6b1a06c37335a318bafd8daef2c4b.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-10-25-839aea4fddea39ce5338ac869e938448.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-03-f30349640a138418fd63bf869a555b02.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-18-7de32de93f7b49717bfc7dd6badd4616.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-10-1faf327aed993730a94b6af0a7f188be.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-13-1f933ebcc55076ddbbe2666bf4b5d1ed.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-11-e4b007e99321f0a21436df133016aa98.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-02-0862d77def66b73eb1913df99ce1b369.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-08-374a307d0bc28a3c6ea01a2e6f4e8d29.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-09-88cc3b9b7c4e8f7db315fff03a495948.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-06-b22bcbe091a371c14ed834487b8ac9b3.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-10-26-5b5ef8f9f36d13fdb1b7c59075340246.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-10-24-1371aae29947f5c7aba28667219b98e8.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-10-29-dfcedbdb0c3931991d679faf87bf2efe.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-10-28-25f5e9a5d8cf1a4a0f82b3c24f6edfe0.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-10-31-d91c47d5f31a2ebac9739be0f05719aa.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-10-30-0ccd3cfd3645d68fa2d78f073135c13a.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-10-27-bfb2561016e9c41ddd87edc5d6e3cd09.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-07-201abeee73b85ae6709009a28004cc79.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-05-4a50bd4b74007af680075b375f761beb.log` — 0.1 МБ
- `/wp-content/uploads/wc-logs/woopayments-2026-11-14-08cfa39b37d042c30129b01ebbe163a5.log` — 0.1 МБ

### 🟡 Копии .htaccess (сервер обычно не отдаёт .ht*, но лучше убрать)
- `/www/.htaccess.bak`

### 🟠 Папки старых копий (1)
- `solid-sound.net/_removed` — 0.0 МБ

### Самые большие файлы
| Файл | Размер |
|---|---|
| `solid-sound.net/www/wp-content/uploads/woocommerce_uploads/Pitch_Masters_Wav_Mp3-pt2jpo.zip` | 91.7 МБ |
| `solid-sound.net/www/wp-content/uploads/Kratos_Masters_Wav_Mp3.zip` | 81.5 МБ |
| `solid-sound.net/www/wp-content/uploads/Example-of-Post-Production-Before-After-Etiquette.mp4` | 80.2 МБ |
| `solid-sound.net/www/wp-content/uploads/Mutualism_Masters_Wav_Mp3.zip` | 74.0 МБ |
| `solid-sound.net/www/wp-content/uploads/Intricate_Masters_Wav_Mp3_2.zip` | 71.1 МБ |
| `solid-sound.net/www/wp-content/uploads/Infinity_Masters_Wav_Mp3.zip` | 65.0 МБ |
| `solid-sound.net/www/wp-content/uploads/Protos_Masters_Wav_Mp3.zip` | 45.9 МБ |
| `solid-sound.net/www/wp-content/uploads/woocommerce_uploads/Time-Goes-Fast-Documentary-Piano-frhsoh.zip` | 33.8 МБ |
| `solid-sound.net/www/wp-content/uploads/Infinity_Ableton-Live_11_Template_Grenor.zip` | 32.0 МБ |
| `solid-sound.net/www/wp-content/uploads/Mutualism_Ableton_Live_10_Template_Grenor.zip` | 31.8 МБ |
| `solid-sound.net/www/wp-content/uploads/Kratos_Ableton_Live_10_Template_Grenor.zip` | 26.3 МБ |
| `solid-sound.net/www/wp-content/uploads/Intricate_Ableton_Live_11_Template.zip` | 24.6 МБ |
| `solid-sound.net/www/wp-content/uploads/Protos_Ableton_Live_10_Template_Grenor.zip` | 19.8 МБ |
| `solid-sound.net/www/wp-content/uploads/01-After.mp3` | 16.3 МБ |
| `solid-sound.net/www/wp-content/uploads/woocommerce_uploads/Pitch_Ableton_Live_11.1_Template-fzrptj.zip` | 16.0 МБ |

### 🟡 Загрузки, которых нет в медиатеке (68 файлов, 10.4 МБ)
Кандидаты на удаление: файл лежит в uploads, но в медиатеке WordPress такого нет. Перед удалением — проверить поиском по сайту.

- `acf-master-1.zip` — 6.1 МБ
- `0621-Обложка.jpg` — 0.3 МБ
- `0621-Обложка-e1750602842251.jpg` — 0.3 МБ
- `vocal-recording-checklist-de-1-pdf.jpg` — 0.2 МБ
- `vocal-recording-checklist-fr-1-pdf.jpg` — 0.2 МБ
- `vocal-recording-checklist-it-1-pdf.jpg` — 0.2 МБ
- `vocal-recording-checklist-pt-1-pdf.jpg` — 0.2 МБ
- `vocal-recording-checklist-ua-1-pdf.jpg` — 0.2 МБ
- `vocal-recording-checklist-es-1-pdf.jpg` — 0.2 МБ
- `0621-Обложка-e1750602842251-1200x896.jpg` — 0.2 МБ
- `vocal-recording-checklist-en-1-pdf.jpg` — 0.2 МБ
- `0621-Обложка-1200x675.jpg` — 0.1 МБ
- `vocal-recording-checklist-de-1-pdf-848x1200.jpg` — 0.1 МБ
- `vocal-recording-checklist-fr-1-pdf-848x1200.jpg` — 0.1 МБ
- `vocal-recording-checklist-ua-1-pdf-848x1200.jpg` — 0.1 МБ
- `vocal-recording-checklist-it-1-pdf-848x1200.jpg` — 0.1 МБ
- `vocal-recording-checklist-pt-1-pdf-848x1200.jpg` — 0.1 МБ
- `vocal-recording-checklist-es-1-pdf-848x1200.jpg` — 0.1 МБ
- `0621-Обложка-e1755550985382.jpg` — 0.1 МБ
- `vocal-recording-checklist-en-1-pdf-848x1200.jpg` — 0.1 МБ
- `0621-Обложка-e1750602842251-600x448.jpg` — 0.1 МБ
- `Монтажная-область-0_3-400x164.png` — 0.1 МБ
- `vocal-recording-checklist-fr-1-pdf-424x600.jpg` — 0.0 МБ
- `vocal-recording-checklist-de-1-pdf-424x600.jpg` — 0.0 МБ
- `vocal-recording-checklist-it-1-pdf-424x600.jpg` — 0.0 МБ
- `Монтажная-область-0_3.png` — 0.0 МБ
- `0621-Обложка-600x338.jpg` — 0.0 МБ
- `vocal-recording-checklist-pt-1-pdf-424x600.jpg` — 0.0 МБ
- `vocal-recording-checklist-ua-1-pdf-424x600.jpg` — 0.0 МБ
- `vocal-recording-checklist-es-1-pdf-424x600.jpg` — 0.0 МБ
- `Монтажная-область-0_3-400x167.png` — 0.0 МБ
- `vocal-recording-checklist-en-1-pdf-424x600.jpg` — 0.0 МБ
- `0621-Обложка-e1750602842251-400x400.jpg` — 0.0 МБ
- `0621-Обложка-400x400.jpg` — 0.0 МБ
- `0621-Обложка-e1750602842251-400x299.jpg` — 0.0 МБ
- `kc-lights-ableton-remake-300x300.jpg` — 0.0 МБ
- `0621-Обложка-400x225.jpg` — 0.0 МБ
- `Монтажная-область-0_3-200x82.png` — 0.0 МБ
- `Монтажная-область-0_3-150x150.png` — 0.0 МБ
- `Монтажная-область-0_3-180x74.png` — 0.0 МБ
- … и ещё 28

