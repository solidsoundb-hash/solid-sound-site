# Автотестировщик — отчёт 2026-11-21 19:26 UTC (полная проверка)

Проверено: страниц 110, кнопок 686, кнопок звука 69, файлов звука 35, ссылок 1232, роботов 20. Время: 56 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 48.
Найдено: 🔴 Критично 2 · 🟠 Важно 14 · 🟡 Мелочь 33 · ⚪ Проверить глазами 19 · новых 35 · исправлено с прошлого раза 36

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🔴 Критично (2)

### 1. Страница /en/sell/ не открывается (код 503) 🆕
- **Где:** https://solid-sound.net/en/sell/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/
- **Ожидалось:** Страница открывается
- **На деле:** Сервер ответил ошибкой 503
- **Скриншот:** [shots/11_006.jpg](shots/11_006.jpg)

### 2. Страница /en/shop/ не открывается (код 503) 🆕
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/
- **Ожидалось:** Страница открывается
- **На деле:** Сервер ответил ошибкой 503
- **Скриншот:** [shots/12_001.jpg](shots/12_001.jpg)

## 🟠 Важно (14)

### 3. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/mastering-album-calculator/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/vendor-register/ и ещё 5 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую).
- **Скриншот:** [shots/11_003.jpg](shots/11_003.jpg)

### 4. После нажатия «Services» — ошибка в коде 🆕
- **Где:** https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/data-deletion/, https://solid-sound.net/en/sitemap/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-membership/ 2) Нажать «Services»
- **Ожидалось:** Без ошибок
- **На деле:** Invalid or unexpected token
- **Скриншот:** [shots/13_005.jpg](shots/13_005.jpg)

### 5. После нажатия «Services» — ошибка в коде 🆕
- **Где:** https://solid-sound.net/en/legal-notice/, https://solid-sound.net/en/wiki/how-to-promote-yourself-as-a-dj/, https://solid-sound.net/en/free/, https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/legal-notice/ 2) Нажать «Services»
- **Ожидалось:** Без ошибок
- **На деле:** Failed to execute 'appendChild' on 'Node': Invalid or unexpected token
- **Скриншот:** [shots/10_023.jpg](shots/10_023.jpg)

### 6. Кнопку ▶ «Play Infinity» невозможно нажать (её что-то закрывает) 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Infinity»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/10_005.jpg](shots/10_005.jpg)

### 7. Кнопку ▶ «Play Kratos» невозможно нажать (её что-то закрывает) 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Kratos»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/10_006.jpg](shots/10_006.jpg)

### 8. Слайдер на главной не помещается на экране (телефон) 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную на телефоне 2) Пролистать карточки слайдера точками
- **Ожидалось:** Весь блок «What are you working on?» виден над нижней панелью
- **На деле:** Уходит под нижнюю панель: карточка 1 на 772px, карточка 2 на 772px, карточка 3 на 772px, карточка 4 на 772px, карточка 5 на 772px, карточка 6 на 772px, карточка 7 на 772px
- **Скриншот:** [shots/10_009.jpg](shots/10_009.jpg)

### 9. Кнопка «Play Space Echo — client track» не превращается в ⏸ (паузу), когда трек играет 🆕
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать ▶ «Play Space Echo — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.sab-p в sab-c)
- **Скриншот:** [shots/10_020.jpg](shots/10_020.jpg)

### 10. Кнопка «Play Feeling Good — client track» не превращается в ⏸ (паузу), когда трек играет 🆕
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.sab-p в sab-c)
- **Скриншот:** [shots/10_021.jpg](shots/10_021.jpg)

### 11. Кнопка «Play Escape — client track» не превращается в ⏸ (паузу), когда трек играет 🆕
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать ▶ «Play Escape — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.sab-p в sab-c)
- **Скриншот:** [shots/10_022.jpg](shots/10_022.jpg)

### 12. Не загружается файл или ресурс на странице 🆕
- **Где:** https://solid-sound.net/en/sell/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 503 /en/sell/

### 13. Не загружается файл или ресурс на странице 🆕
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 503 /en/shop/

### 14. Битая ссылка: /en/shop/?currency=JPY (код 503) 🆕
- **Где:** https://solid-sound.net/en/shop/
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать ссылку на /en/shop/?currency=JPY
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 503. Ссылка есть на страницах: /en/shop/

### 15. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (телефон) 2) Где: button «Get free feedback» ← закрывает a.gfree «Free»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/15_010.jpg](shots/15_010.jpg)

### 16. Слайдер на главной не помещается на экране (Samsung A31) 🆕
- **Где:** https://solid-sound.net/en/ (Samsung A31)
- **Шаги:** 1) Открыть главную на телефоне 2) Пролистать карточки слайдера точками
- **Ожидалось:** Весь блок «What are you working on?» виден над нижней панелью
- **На деле:** Уходит под нижнюю панель: карточка 1 на 705px, карточка 2 на 705px, карточка 3 на 705px, карточка 4 на 705px, карточка 5 на 705px, карточка 6 на 705px, карточка 7 на 705px
- **Скриншот:** [shots/18_007.jpg](shots/18_007.jpg)

## 🟡 Мелочь (33)

### 17. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 45 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/10_004.jpg](shots/10_004.jpg)

### 18. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/, https://solid-sound.net/en/shop/ и ещё 20 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_007.jpg](shots/10_007.jpg)

### 19. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/vendor-membership/ и ещё 15 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: top-q
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_003.jpg](shots/10_003.jpg)

### 20. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/en/prices/, https://solid-sound.net/ua/ и ещё 5 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: em.ss-more «+3 more» 0px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/10_008.jpg](shots/10_008.jpg)

### 21. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/ и ещё 3 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: em «BPM & key map» 3.8:1; em «Ableton live set (stems)» 3.8:1; em «Mix / mixtape mastering» 3.8:1; em «Transition tools» 3.8:1; em «Echo / room sound» 3.8:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/10_002.jpg](shots/10_002.jpg)

### 22. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Sort products» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/10_018.jpg](shots/10_018.jpg)

### 23. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/client-dashboard/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a «Forums»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/14_008.jpg](shots/14_008.jpg)

### 24. Нет описания страницы (meta description) 🆕
- **Где:** https://solid-sound.net/en/sell/, https://solid-sound.net/en/shop/ (телефон, компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (телефон)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.
- **Скриншот:** [shots/11_007.jpg](shots/11_007.jpg)

### 25. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 🆕
- **Где:** https://solid-sound.net/en/sell/, https://solid-sound.net/en/shop/ (телефон, компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (телефон)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: нет, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.
- **Скриншот:** [shots/11_008.jpg](shots/11_008.jpg)

### 26. Главная картинка первого экрана грузится «лениво» (loading=lazy) 🆕
- **Где:** https://solid-sound.net/en/shop/, https://solid-sound.net/en/all-products/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ (телефон) 2) Где: xPitch_Title_PSD-200x200.jpg.pagespeed.ic.ahRYm-WLmX.webp
- **Ожидалось:** Для первого экрана — без lazy, с fetchpriority=high.
- **На деле:** Почему важно: Самая важная картинка появляется последней.
- **Скриншот:** [shots/12_012.jpg](shots/12_012.jpg)

### 27. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/15_006.jpg](shots/15_006.jpg)

### 28. Ссылка /en/forum-experimental/?currency=EUR выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=EUR
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 29. Ссылка /en/forum-experimental/?currency=AUD выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=AUD
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 30. Ссылка /en/forum-experimental/?currency=CAD выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=CAD
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 31. Ссылка /en/forum-experimental/?currency=DKK выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=DKK
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 32. Ссылка /en/forum-experimental/?currency=HKD выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=HKD
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 33. Ссылка /en/forum-experimental/?currency=INR выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=INR
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 34. Ссылка /en/forum-experimental/?currency=JPY выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=JPY
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 35. Ссылка /en/forum-experimental/?currency=NZD выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=NZD
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 36. Ссылка /en/forum-experimental/?currency=NOK выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=NOK
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 37. Ссылка /en/forum-experimental/?currency=PLN выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=PLN
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 38. Ссылка /en/forum-experimental/?currency=GBP выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=GBP
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 39. Ссылка /en/forum-experimental/?currency=ZAR выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=ZAR
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 40. Ссылка /en/forum-experimental/?currency=SEK выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=SEK
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 41. Ссылка /en/forum-experimental/?currency=CHF выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=CHF
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 42. Ссылка /en/forum-experimental/?currency=UAH выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=UAH
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 43. Ссылка /en/forum-experimental/?currency=USD выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/
- **Шаги:** 1) Нажать ссылку на /en/forum-experimental/?currency=USD
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 44. Превью «02-After.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «02-After.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.9 дБ

### 45. Превью «01-Before.mp3» начинается с тишины (4.4 с)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

### 46. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 47. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=338 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=338 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 48. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=340 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=340 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 49. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=61 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=61 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

## ⚪ Проверить глазами (19)

### 50. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 40 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/10_001.jpg](shots/10_001.jpg)

### 51. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/, https://solid-sound.net/product/infinity/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_012.jpg](shots/10_012.jpg)

### 52. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3652. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3134. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3090. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/10_015.jpg](shots/10_015.jpg)

### 53. Кнопка «Video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_013.jpg](shots/10_013.jpg)

### 54. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_014.jpg](shots/10_014.jpg)

### 55. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/presets/, https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_019.jpg](shots/10_019.jpg)

### 56. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_010.jpg](shots/10_010.jpg)

### 57. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_011.jpg](shots/10_011.jpg)

### 58. Кнопка «Previous» ничего не делает 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Previous»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/10_016.jpg](shots/10_016.jpg)

### 59. Кнопка «Video» ничего не делает 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/10_017.jpg](shots/10_017.jpg)

### 60. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_007.jpg](shots/15_007.jpg)

### 61. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_008.jpg](shots/15_008.jpg)

### 62. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_016.jpg](shots/18_016.jpg)

### 63. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_017.jpg](shots/18_017.jpg)

### 64. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_018.jpg](shots/18_018.jpg)

### 65. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_019.jpg](shots/18_019.jpg)

### 66. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_020.jpg](shots/18_020.jpg)

### 67. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_021.jpg](shots/18_021.jpg)

### 68. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.1 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/19_005.jpg](shots/19_005.jpg)

## ✅ Исправлено с прошлой проверки (36)

- Кнопка ▶ «Play Infinity» не запускает звук
- Кнопку ▶ «?» невозможно нажать (её что-то закрывает)
- Кнопка ▶ «Play Infinity» не запускает звук
- Кнопку ▶ «?» невозможно нажать (её что-то закрывает)
- Страница /all-products не переведена — переключатель языка выкидывает на главную
- Страница /services-prices не переведена — переключатель языка выкидывает на главную
- Страница /legal-notice не переведена — переключатель языка выкидывает на главную
- Страница /wiki/how-to-promote-yourself-as-a-dj не переведена — переключатель языка выкидывает на главную
- Страница /privacy-policy не переведена — переключатель языка выкидывает на главную
- Страница /faq не переведена — переключатель языка выкидывает на главную
- Страница /services-calculator не переведена — переключатель языка выкидывает на главную
- Страница /sell не переведена — переключатель языка выкидывает на главную
- Страница /wiki/how-to-get-booked-as-a-dj не переведена — переключатель языка выкидывает на главную
- Страница /shop не переведена — переключатель языка выкидывает на главную
- Страница /mastering-album-calculator не переведена — переключатель языка выкидывает на главную
- Страница /creator-terms не переведена — переключатель языка выкидывает на главную
- Страница /vendor-membership не переведена — переключатель языка выкидывает на главную
- Страница /free не переведена — переключатель языка выкидывает на главную
- Страница /vendor-register не переведена — переключатель языка выкидывает на главную
- Страница /forum не переведена — переключатель языка выкидывает на главную
- Страница /data-deletion не переведена — переключатель языка выкидывает на главную
- Страница /sitemap не переведена — переключатель языка выкидывает на главную
- Страница /add-product не переведена — переключатель языка выкидывает на главную
- Страница /refund_returns не переведена — переключатель языка выкидывает на главную
- Страница /license не переведена — переключатель языка выкидывает на главную
- Страница /prices не переведена — переключатель языка выкидывает на главную
- Страница /terms не переведена — переключатель языка выкидывает на главную
- Страница /wiki/how-much-do-djs-get-paid не переведена — переключатель языка выкидывает на главную
- Страница на UA в основном не переведена (много английского текста)
- Страница на DE в основном не переведена (много английского текста)
- Страница на FR в основном не переведена (много английского текста)
- Страница на PT в основном не переведена (много английского текста)
- Страница на ES в основном не переведена (много английского текста)
- Страница на IT в основном не переведена (много английского текста)
- Страница /store-manager не переведена — переключатель языка выкидывает на главную
- Кнопка «Video» ничего не делает

