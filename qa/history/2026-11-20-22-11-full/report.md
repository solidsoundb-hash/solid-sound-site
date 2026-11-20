# Автотестировщик — отчёт 2026-11-20 22:11 UTC (полная проверка)

Проверено: страниц 110, кнопок 711, кнопок звука 65, файлов звука 33, ссылок 567, роботов 20. Время: 44 мин.
Найдено: 🔴 Критично 2 · 🟠 Важно 5 · 🟡 Мелочь 46 · ⚪ Проверить глазами 18 · новых 37 · исправлено с прошлого раза 11

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🔴 Критично (2)

### 1. Страница /en/shop/ не открывается (код 503) 🆕
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/
- **Ожидалось:** Страница открывается
- **На деле:** Сервер ответил ошибкой 503
- **Скриншот:** [shots/12_001.jpg](../../latest/shots/12_001.jpg)

### 2. Страница /en/store-manager/ не открывается (код 503) 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/
- **Ожидалось:** Страница открывается
- **На деле:** Сервер ответил ошибкой 503
- **Скриншот:** [shots/12_015.jpg](../../latest/shots/12_015.jpg)

## 🟠 Важно (5)

### 3. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/mastering-album-calculator/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/vendor-register/ и ещё 5 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую).
- **Скриншот:** [shots/11_003.jpg](../../latest/shots/11_003.jpg)

### 4. После нажатия «Video» — ошибка в коде
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Без ошибок
- **На деле:** console: Permissions policy violation: compute-pressure is not allowed in this document.
- **Скриншот:** [shots/10_015.jpg](../../latest/shots/10_015.jpg)

### 5. Не загружается файл или ресурс на странице 🆕
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 503 /en/shop/

### 6. Не загружается файл или ресурс на странице 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 503 /en/store-manager/

### 7. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (телефон) 2) Где: button «Get free feedback» ← закрывает a.gfree «Free»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/15_010.jpg](../../latest/shots/15_010.jpg)

## 🟡 Мелочь (46)

### 8. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 45 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/10_003.jpg](../../latest/shots/10_003.jpg)

### 9. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ и ещё 21 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_005.jpg](../../latest/shots/10_005.jpg)

### 10. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/music/ и ещё 7 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_012.jpg](../../latest/shots/10_012.jpg)

### 11. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/en/client-dashboard/, https://solid-sound.net/forum/ и ещё 6 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/10_004.jpg](../../latest/shots/10_004.jpg)

### 12. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/en/prices/ и ещё 6 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: em.ss-more «+3 more» 0px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/10_006.jpg](../../latest/shots/10_006.jpg)

### 13. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/ и ещё 3 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: em «BPM & key map» 3.8:1; em «Ableton live set (stems)» 3.8:1; em «Mix / mixtape mastering» 3.8:1; em «Transition tools» 3.8:1; em «Echo / room sound» 3.8:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/10_002.jpg](../../latest/shots/10_002.jpg)

### 14. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Sort products» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/10_014.jpg](../../latest/shots/10_014.jpg)

### 15. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/15_006.jpg](../../latest/shots/15_006.jpg)

### 16. Страница /all-products не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 17. Страница /services-prices не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 18. Страница /legal-notice не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/legal-notice/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/legal-notice/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 19. Страница /wiki/how-to-promote-yourself-as-a-dj не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/wiki/how-to-promote-yourself-as-a-dj/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/how-to-promote-yourself-as-a-dj/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 20. Страница /privacy-policy не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/privacy-policy/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/privacy-policy/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 21. Страница /faq не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 22. Страница /services-calculator не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/services-calculator/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, UA, DE, FR, PT, ES, IT — открывается главная

### 23. Страница /sell не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 24. Страница /wiki/how-to-get-booked-as-a-dj не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/wiki/how-to-get-booked-as-a-dj/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/how-to-get-booked-as-a-dj/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 25. Нет описания страницы (meta description) 🆕
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ (компьютер)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.
- **Скриншот:** [shots/12_002.jpg](../../latest/shots/12_002.jpg)

### 26. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 🆕
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: нет, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.
- **Скриншот:** [shots/12_003.jpg](../../latest/shots/12_003.jpg)

### 27. Страница /mastering-album-calculator не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/mastering-album-calculator/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/mastering-album-calculator/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, UA, DE, FR, PT, ES, IT — открывается главная

### 28. Страница /creator-terms не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/creator-terms/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/creator-terms/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 29. Страница /vendor-membership не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/vendor-membership/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-membership/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 30. Страница /free не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 31. Страница /vendor-register не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/vendor-register/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-register/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 32. Страница /forum не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 33. Страница /data-deletion не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/data-deletion/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/data-deletion/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 34. Страница /sitemap не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/sitemap/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sitemap/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 35. Страница /add-product не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/add-product/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 36. Страница /refund_returns не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/refund_returns/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/refund_returns/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 37. Страница /license не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/license/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/license/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 38. Страница /prices не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 39. Страница /terms не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/terms/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/terms/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 40. Страница /wiki/how-much-do-djs-get-paid не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/wiki/how-much-do-djs-get-paid/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/how-much-do-djs-get-paid/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 41. Страница на UA в основном не переведена (много английского текста) 🆕
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Текст на языке UA
- **На деле:** Доля английских слов 10%, кириллицы 42%
- **Скриншот:** [shots/18_014.jpg](../../latest/shots/18_014.jpg)

### 42. Страница на DE в основном не переведена (много английского текста) 🆕
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Текст на языке DE
- **На деле:** Доля английских слов 11%
- **Скриншот:** [shots/18_016.jpg](../../latest/shots/18_016.jpg)

### 43. Страница на FR в основном не переведена (много английского текста) 🆕
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Текст на языке FR
- **На деле:** Доля английских слов 9%
- **Скриншот:** [shots/18_018.jpg](../../latest/shots/18_018.jpg)

### 44. Страница на PT в основном не переведена (много английского текста) 🆕
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Текст на языке PT
- **На деле:** Доля английских слов 9%
- **Скриншот:** [shots/18_020.jpg](../../latest/shots/18_020.jpg)

### 45. Страница на ES в основном не переведена (много английского текста) 🆕
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Текст на языке ES
- **На деле:** Доля английских слов 9%
- **Скриншот:** [shots/18_022.jpg](../../latest/shots/18_022.jpg)

### 46. Страница на IT в основном не переведена (много английского текста) 🆕
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 9%
- **Скриншот:** [shots/18_024.jpg](../../latest/shots/18_024.jpg)

### 47. Страница /store-manager не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 48. Превью «02-After.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «02-After.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.9 дБ

### 49. Превью «01-Before.mp3» начинается с тишины (4.4 с)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

### 50. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 51. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=338 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=338 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 52. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=340 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=340 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 53. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=61 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=61 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

## ⚪ Проверить глазами (18)

### 54. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 40 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/10_001.jpg](../../latest/shots/10_001.jpg)

### 55. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/, https://solid-sound.net/product/infinity/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_009.jpg](../../latest/shots/10_009.jpg)

### 56. Кнопка «Video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_010.jpg](../../latest/shots/10_010.jpg)

### 57. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_011.jpg](../../latest/shots/10_011.jpg)

### 58. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3562. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3564. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/10_013.jpg](../../latest/shots/10_013.jpg)

### 59. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/presets/, https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_016.jpg](../../latest/shots/10_016.jpg)

### 60. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_007.jpg](../../latest/shots/10_007.jpg)

### 61. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_008.jpg](../../latest/shots/10_008.jpg)

### 62. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/12_014.jpg](../../latest/shots/12_014.jpg)

### 63. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_007.jpg](../../latest/shots/15_007.jpg)

### 64. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_008.jpg](../../latest/shots/15_008.jpg)

### 65. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_015.jpg](../../latest/shots/18_015.jpg)

### 66. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_017.jpg](../../latest/shots/18_017.jpg)

### 67. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_019.jpg](../../latest/shots/18_019.jpg)

### 68. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_021.jpg](../../latest/shots/18_021.jpg)

### 69. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_023.jpg](../../latest/shots/18_023.jpg)

### 70. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_025.jpg](../../latest/shots/18_025.jpg)

### 71. Страница весит больше 3 МБ 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.1 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/19_005.jpg](../../latest/shots/19_005.jpg)

## ✅ Исправлено с прошлой проверки (11)

- После паузы кнопка «Play Infinity» остаётся в виде ⏸
- После паузы кнопка «Play Kratos» остаётся в виде ⏸
- После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- Слайдер на главной не помещается на экране (телефон)
- Кнопка или ссылка без названия (только значок)
- Битая ссылка: /ua/wiki-ua/balansni-nebalansni-kabeli-ua/ (код 503)
- После паузы кнопка «Play Infinity» остаётся в виде ⏸
- После паузы кнопка «Play Kratos» остаётся в виде ⏸
- После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- Не нажимается кнопка выбора языка (телефон)
- Кнопка «Video» ничего не делает

