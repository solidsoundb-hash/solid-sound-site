# Автотестировщик — отчёт 2026-11-20 03:01 UTC (полная проверка)

Проверено: страниц 110, кнопок 756, кнопок звука 77, файлов звука 28, ссылок 557, роботов 20. Время: 45 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 37.
Найдено: 🔴 Критично 0 · 🟠 Важно 14 · 🟡 Мелочь 15 · ⚪ Проверить глазами 19 · новых 14 · исправлено с прошлого раза 18

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (14)

### 1. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/mastering-album-calculator/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/vendor-register/ и ещё 5 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую).
- **Скриншот:** [shots/11_003.jpg](../../latest/shots/11_003.jpg)

### 2. Нет заголовка страницы (title) или он слишком длинный/короткий 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/wiki/how-to-promote-yourself-as-a-dj/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** title 15–65 символов, свой у каждой страницы.
- **На деле:** Сейчас: «Ableton Templates, Serum Presets & Royalty-Free Music – All Products | Solid Sound» (82 симв.). Почему важно: Заголовок — первая строка в выдаче Google. Пустой/обрезанный = меньше кликов. · Сейчас: «How to Start Promoting Yourself as a DJ: Your First 90 Days – Solid Sound» (73 симв.). Почему важно: Заголовок 
- **Скриншот:** [shots/10_018.jpg](../../latest/shots/10_018.jpg)

### 3. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div#templates.w-hwrapper.align_left «Play All 8 items Newest Top Sellers Popu»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/10_020.jpg](../../latest/shots/10_020.jpg)

### 4. После паузы кнопка «Play Infinity» остаётся в виде ⏸ 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Infinity» 2) Нажать ещё раз
- **Ожидалось:** Значок возвращается в ▶
- **На деле:** Звука нет, а значок ⏸
- **Скриншот:** [shots/10_005.jpg](../../latest/shots/10_005.jpg)

### 5. После паузы кнопка «Play Kratos» остаётся в виде ⏸ 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Kratos» 2) Нажать ещё раз
- **Ожидалось:** Значок возвращается в ▶
- **На деле:** Звука нет, а значок ⏸
- **Скриншот:** [shots/10_006.jpg](../../latest/shots/10_006.jpg)

### 6. После запуска другого трека прежняя кнопка всё ещё показывает ⏸ 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Kratos» 2) Нажать ▶ «Play Space Echo»
- **Ожидалось:** «Play Kratos» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/10_007.jpg](../../latest/shots/10_007.jpg)

### 7. Слайдер на главной не помещается на экране (телефон) 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную на телефоне 2) Пролистать карточки слайдера точками
- **Ожидалось:** Весь блок «What are you working on?» виден над нижней панелью
- **На деле:** Уходит под нижнюю панель: карточка 2 на 8px, карточка 6 на 69px, карточка 7 на 23px
- **Скриншот:** [shots/10_009.jpg](../../latest/shots/10_009.jpg)

### 8. Кнопку ▶ «Play Infinity» невозможно нажать (её что-то закрывает) 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Infinity»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/10_010.jpg](../../latest/shots/10_010.jpg)

### 9. Кнопку ▶ «Play Kratos» невозможно нажать (её что-то закрывает) 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Kratos»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/10_011.jpg](../../latest/shots/10_011.jpg)

### 10. Кнопку ▶ «Play Space Echo» невозможно нажать (её что-то закрывает) 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Space Echo»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/10_012.jpg](../../latest/shots/10_012.jpg)

### 11. После паузы кнопка «Play Infinity» остаётся в виде ⏸ 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ «Play Infinity» 2) Нажать ещё раз
- **Ожидалось:** Значок возвращается в ▶
- **На деле:** Звука нет, а значок ⏸
- **Скриншот:** [shots/16_008.jpg](../../latest/shots/16_008.jpg)

### 12. После паузы кнопка «Play Kratos» остаётся в виде ⏸ 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ «Play Kratos» 2) Нажать ещё раз
- **Ожидалось:** Значок возвращается в ▶
- **На деле:** Звука нет, а значок ⏸
- **Скриншот:** [shots/16_009.jpg](../../latest/shots/16_009.jpg)

### 13. После запуска другого трека прежняя кнопка всё ещё показывает ⏸ 🆕
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ «Play Kratos» 2) Нажать ▶ «Play Space Echo»
- **Ожидалось:** «Play Kratos» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/16_010.jpg](../../latest/shots/16_010.jpg)

### 14. Слайдер на главной не помещается на экране (Samsung A31) 🆕
- **Где:** https://solid-sound.net/en/ (Samsung A31)
- **Шаги:** 1) Открыть главную на телефоне 2) Пролистать карточки слайдера точками
- **Ожидалось:** Весь блок «What are you working on?» виден над нижней панелью
- **На деле:** Уходит под нижнюю панель: карточка 1 на 21px, карточка 2 на 72px, карточка 5 на 15px, карточка 6 на 125px, карточка 7 на 87px
- **Скриншот:** [shots/18_006.jpg](../../latest/shots/18_006.jpg)

## 🟡 Мелочь (15)

### 15. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 45 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/10_003.jpg](../../latest/shots/10_003.jpg)

### 16. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ и ещё 21 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_008.jpg](../../latest/shots/10_008.jpg)

### 17. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/music/ и ещё 7 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_019.jpg](../../latest/shots/10_019.jpg)

### 18. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/en/client-dashboard/, https://solid-sound.net/forum/ и ещё 6 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/10_004.jpg](../../latest/shots/10_004.jpg)

### 19. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/ и ещё 3 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: em «Metadata & ISRC» 3.8:1; em «BPM & key map» 3.8:1; em «Ableton live set (stems)» 3.8:1; em «Mix / mixtape mastering» 3.8:1; em «Transition tools» 3.8:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/10_002.jpg](../../latest/shots/10_002.jpg)

### 20. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Sort products» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/10_022.jpg](../../latest/shots/10_022.jpg)

### 21. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/sell/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/prices/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (телефон) 2) Где: small «optional · SoundCloud, YouTube, Spotify,» 10.4px; small «optional» 10.4px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/11_006.jpg](../../latest/shots/11_006.jpg)

### 22. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/15_006.jpg](../../latest/shots/15_006.jpg)

### 23. Главная картинка первого экрана грузится «лениво» (loading=lazy) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (Samsung A31) 2) Где: xPitch_Title_PSD-200x200.jpg.pagespeed.ic.ahRYm-WLmX.webp
- **Ожидалось:** Для первого экрана — без lazy, с fetchpriority=high.
- **На деле:** Почему важно: Самая важная картинка появляется последней.
- **Скриншот:** [shots/18_013.jpg](../../latest/shots/18_013.jpg)

### 24. Превью «02-After.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «02-After.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.9 дБ

### 25. Превью «01-Before.mp3» начинается с тишины (4.4 с)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

### 26. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 27. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=338 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=338 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 28. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=340 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=340 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 29. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=61 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=61 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

## ⚪ Проверить глазами (19)

### 30. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 40 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/10_001.jpg](../../latest/shots/10_001.jpg)

### 31. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/, https://solid-sound.net/product/infinity/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_015.jpg](../../latest/shots/10_015.jpg)

### 32. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_013.jpg](../../latest/shots/10_013.jpg)

### 33. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_014.jpg](../../latest/shots/10_014.jpg)

### 34. Кнопка «Video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_016.jpg](../../latest/shots/10_016.jpg)

### 35. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_017.jpg](../../latest/shots/10_017.jpg)

### 36. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3582. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3561. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/10_021.jpg](../../latest/shots/10_021.jpg)

### 37. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/presets/, https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_024.jpg](../../latest/shots/10_024.jpg)

### 38. Кнопка «Video» ничего не делает 🆕
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/10_023.jpg](../../latest/shots/10_023.jpg)

### 39. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/12_015.jpg](../../latest/shots/12_015.jpg)

### 40. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_007.jpg](../../latest/shots/15_007.jpg)

### 41. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_008.jpg](../../latest/shots/15_008.jpg)

### 42. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_016.jpg](../../latest/shots/18_016.jpg)

### 43. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_017.jpg](../../latest/shots/18_017.jpg)

### 44. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_018.jpg](../../latest/shots/18_018.jpg)

### 45. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_019.jpg](../../latest/shots/18_019.jpg)

### 46. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_020.jpg](../../latest/shots/18_020.jpg)

### 47. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_021.jpg](../../latest/shots/18_021.jpg)

### 48. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.1 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/19_005.jpg](../../latest/shots/19_005.jpg)

## ✅ Исправлено с прошлой проверки (18)

- Битая ссылка: /de/vorlagen/ (код 503)
- Ошибка в коде страницы (JavaScript)
- Ошибка в коде страницы (JavaScript)
- Поле ввода без подписи
- Внешняя ссылка в новой вкладке без rel="noopener"
- Картинка загружается намного больше, чем показывается
- Картинки без размеров — страница «прыгает» при загрузке
- Кнопка «Get the free pack →» скруглена не так, как остальные
- Кнопка «SEND REQUEST — GET A REPLY IN 24 H» скруглена не так, как остальные
- Кнопка «Get free feedback» скруглена не так, как остальные
- Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- Кнопка «Отримати безкоштовно →» скруглена не так, как остальные
- Кнопка «Gratis-Paket holen →» скруглена не так, как остальные
- Кнопка «Obtenir le pack gratuit →» скруглена не так, как остальные
- Кнопка «Obter o pacote grátis →» скруглена не так, как остальные
- Кнопка «Obtener el pack gratis →» скруглена не так, как остальные
- Кнопка «Ottieni il pacchetto gratis →» скруглена не так, как остальные
- Круглая кнопка-значок «без текста» — нужна ли квадратная со скруглёнными углами?

