# Автотестировщик — отчёт 2026-11-20 14:47 UTC (полная проверка)

Проверено: страниц 110, кнопок 676, кнопок звука 75, файлов звука 32, ссылок 567, роботов 20. Время: 50 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 37.
Найдено: 🔴 Критично 0 · 🟠 Важно 13 · 🟡 Мелочь 14 · ⚪ Проверить глазами 18 · новых 3 · исправлено с прошлого раза 6

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (13)

### 1. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/mastering-album-calculator/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/vendor-register/ и ещё 5 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую).
- **Скриншот:** [shots/11_003.jpg](../../latest/shots/11_003.jpg)

### 2. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div#templates.w-hwrapper.align_left «Play All 8 items Newest Top Sellers Popu»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/10_016.jpg](../../latest/shots/10_016.jpg)

### 3. После нажатия «Video» — ошибка в коде
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Без ошибок
- **На деле:** console: Permissions policy violation: compute-pressure is not allowed in this document.
- **Скриншот:** [shots/10_020.jpg](../../latest/shots/10_020.jpg)

### 4. После паузы кнопка «Play Infinity» остаётся в виде ⏸
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ «Play Infinity» 2) Нажать ещё раз
- **Ожидалось:** Значок возвращается в ▶
- **На деле:** Звука нет, а значок ⏸
- **Скриншот:** [shots/10_005.jpg](../../latest/shots/10_005.jpg)

### 5. После паузы кнопка «Play Kratos» остаётся в виде ⏸
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ «Play Kratos» 2) Нажать ещё раз
- **Ожидалось:** Значок возвращается в ▶
- **На деле:** Звука нет, а значок ⏸
- **Скриншот:** [shots/10_006.jpg](../../latest/shots/10_006.jpg)

### 6. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ «Play Kratos» 2) Нажать ▶ «Play»
- **Ожидалось:** «Play Kratos» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/10_007.jpg](../../latest/shots/10_007.jpg)

### 7. Слайдер на главной не помещается на экране (телефон)
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную на телефоне 2) Пролистать карточки слайдера точками
- **Ожидалось:** Весь блок «What are you working on?» виден над нижней панелью
- **На деле:** Уходит под нижнюю панель: карточка 1 на 52px, карточка 2 на 52px, карточка 3 на 52px, карточка 4 на 52px, карточка 5 на 52px, карточка 6 на 52px, карточка 7 на 52px
- **Скриншот:** [shots/10_009.jpg](../../latest/shots/10_009.jpg)

### 8. Кнопка или ссылка без названия (только значок) 🆕
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ (компьютер) 2) Где: a.card; a.card; a.card; a.card.hot; a.card.hot
- **Ожидалось:** aria-label="Play", "Add to cart" и т.п.
- **На деле:** Почему важно: Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.
- **Скриншот:** [shots/10_023.jpg](../../latest/shots/10_023.jpg)

### 9. Битая ссылка: /ua/wiki-ua/balansni-nebalansni-kabeli-ua/ (код 503) 🆕
- **Где:** https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ 2) Нажать ссылку на /ua/wiki-ua/balansni-nebalansni-kabeli-ua/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 503. Ссылка есть на страницах: /en/wiki/balanced-vs-unbalanced-cables/

### 10. После паузы кнопка «Play Infinity» остаётся в виде ⏸
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ «Play Infinity» 2) Нажать ещё раз
- **Ожидалось:** Значок возвращается в ▶
- **На деле:** Звука нет, а значок ⏸
- **Скриншот:** [shots/16_008.jpg](../../latest/shots/16_008.jpg)

### 11. После паузы кнопка «Play Kratos» остаётся в виде ⏸
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ «Play Kratos» 2) Нажать ещё раз
- **Ожидалось:** Значок возвращается в ▶
- **На деле:** Звука нет, а значок ⏸
- **Скриншот:** [shots/16_009.jpg](../../latest/shots/16_009.jpg)

### 12. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ «Play Kratos» 2) Нажать ▶ «Play»
- **Ожидалось:** «Play Kratos» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/16_010.jpg](../../latest/shots/16_010.jpg)

### 13. Не нажимается кнопка выбора языка (телефон) 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Нажать «Menu»
- **Ожидалось:** Открывается список языков
- **На деле:** locator.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/18_020.jpg](../../latest/shots/18_020.jpg)

## 🟡 Мелочь (14)

### 14. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 45 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/10_003.jpg](../../latest/shots/10_003.jpg)

### 15. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ и ещё 21 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_008.jpg](../../latest/shots/10_008.jpg)

### 16. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/music/ и ещё 7 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_015.jpg](../../latest/shots/10_015.jpg)

### 17. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/en/prices/ и ещё 7 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ (телефон) 2) Где: span.rib «BEST VALUE» 10px; span.rib «FREE» 10px; span.rib «MOST POPULAR» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/10_024.jpg](../../latest/shots/10_024.jpg)

### 18. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/en/client-dashboard/, https://solid-sound.net/forum/ и ещё 6 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/10_004.jpg](../../latest/shots/10_004.jpg)

### 19. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/ и ещё 3 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: em «BPM & key map» 3.8:1; em «Ableton live set (stems)» 3.8:1; em «Mix / mixtape mastering» 3.8:1; em «Transition tools» 3.8:1; em «Echo / room sound» 3.8:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/10_002.jpg](../../latest/shots/10_002.jpg)

### 20. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Sort products» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/10_018.jpg](../../latest/shots/10_018.jpg)

### 21. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 2 (Your music. Finished. | SERVICES & PRICES). Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/10_022.jpg](../../latest/shots/10_022.jpg)

### 22. Превью «02-After.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «02-After.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.9 дБ

### 23. Превью «01-Before.mp3» начинается с тишины (4.4 с)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

### 24. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 25. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=338 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=338 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 26. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=340 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=340 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 27. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=61 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=61 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

## ⚪ Проверить глазами (18)

### 28. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 40 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/10_001.jpg](../../latest/shots/10_001.jpg)

### 29. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/, https://solid-sound.net/product/infinity/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_012.jpg](../../latest/shots/10_012.jpg)

### 30. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_010.jpg](../../latest/shots/10_010.jpg)

### 31. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_011.jpg](../../latest/shots/10_011.jpg)

### 32. Кнопка «Video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_013.jpg](../../latest/shots/10_013.jpg)

### 33. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_014.jpg](../../latest/shots/10_014.jpg)

### 34. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3583. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3564. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/10_017.jpg](../../latest/shots/10_017.jpg)

### 35. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/presets/, https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_021.jpg](../../latest/shots/10_021.jpg)

### 36. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/10_019.jpg](../../latest/shots/10_019.jpg)

### 37. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/12_015.jpg](../../latest/shots/12_015.jpg)

### 38. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_007.jpg](../../latest/shots/15_007.jpg)

### 39. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_008.jpg](../../latest/shots/15_008.jpg)

### 40. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_014.jpg](../../latest/shots/18_014.jpg)

### 41. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_015.jpg](../../latest/shots/18_015.jpg)

### 42. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_016.jpg](../../latest/shots/18_016.jpg)

### 43. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_017.jpg](../../latest/shots/18_017.jpg)

### 44. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_018.jpg](../../latest/shots/18_018.jpg)

### 45. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_019.jpg](../../latest/shots/18_019.jpg)

## ✅ Исправлено с прошлой проверки (6)

- Нет заголовка страницы (title) или он слишком длинный/короткий
- Кнопку ▶ «Play Infinity» невозможно нажать (её что-то закрывает)
- Кнопку ▶ «Play Kratos» невозможно нажать (её что-то закрывает)
- Кнопку ▶ «Play Space Echo» невозможно нажать (её что-то закрывает)
- Слайдер на главной не помещается на экране (Samsung A31)
- Страница весит больше 3 МБ

