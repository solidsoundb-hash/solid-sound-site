# Автотестировщик — отчёт 2026-11-19 12:18 UTC (полная проверка)

Проверено: страниц 104, кнопок 537, кнопок звука 71, файлов звука 21, ссылок 519, роботов 20. Время: 170 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 22.
Найдено: 🔴 Критично 0 · 🟠 Важно 5 · 🟡 Мелочь 18 · ⚪ Проверить глазами 29 · новых 1 · исправлено с прошлого раза 3

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (5)

### 1. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/mastering-album-calculator/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/vendor-register/ и ещё 5 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую).
- **Скриншот:** [shots/11_004.jpg](../../latest/shots/11_004.jpg)

### 2. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div#templates.w-hwrapper.align_left «Play All 8 items Newest Top Sellers Popu»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/10_013.jpg](../../latest/shots/10_013.jpg)

### 3. Битая ссылка: /de/vorlagen/ (код 503) 🆕
- **Где:** https://solid-sound.net/en/templates/
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ссылку на /de/vorlagen/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 503. Ссылка есть на страницах: /en/templates/

### 4. Ошибка в коде страницы (JavaScript)
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Посмотреть консоль браузера
- **Ожидалось:** Без ошибок
- **На деле:** color is not defined

### 5. Ошибка в коде страницы (JavaScript)
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Посмотреть консоль браузера
- **Ожидалось:** Без ошибок
- **На деле:** Cannot read properties of undefined (reading 'green')

## 🟡 Мелочь (18)

### 6. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 42 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/10_003.jpg](../../latest/shots/10_003.jpg)

### 7. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ и ещё 21 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_005.jpg](../../latest/shots/10_005.jpg)

### 8. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/music/ и ещё 7 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_012.jpg](../../latest/shots/10_012.jpg)

### 9. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/en/client-dashboard/, https://solid-sound.net/forum/ и ещё 6 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/10_004.jpg](../../latest/shots/10_004.jpg)

### 10. Поле ввода без подписи
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/10_011.jpg](../../latest/shots/10_011.jpg)

### 11. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/10_015.jpg](../../latest/shots/10_015.jpg)

### 12. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/sell/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/prices/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (телефон) 2) Где: small «optional · SoundCloud, YouTube, Spotify,» 10.4px; small «optional» 10.4px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/11_007.jpg](../../latest/shots/11_007.jpg)

### 13. Внешняя ссылка в новой вкладке без rel="noopener"
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fsolid-sound.net%2Fcom; https://web.whatsapp.com/send?text=Solid+Sound+Forum%20URL:%20https%3A%2F%2Fsoli
- **Ожидалось:** rel="noopener noreferrer".
- **На деле:** Почему важно: Чужой сайт может подменить нашу вкладку (фишинг).
- **Скриншот:** [shots/14_007.jpg](../../latest/shots/14_007.jpg)

### 14. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/15_006.jpg](../../latest/shots/15_006.jpg)

### 15. Картинка загружается намного больше, чем показывается
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: P1_3.jpg 1080px → 218px; P1_4.jpg 880px → 218px
- **Ожидалось:** srcset / миниатюра нужного размера.
- **На деле:** Почему важно: Лишние мегабайты на каждой загрузке.
- **Скриншот:** [shots/15_011.jpg](../../latest/shots/15_011.jpg)

### 16. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: a.btn «GET A FREE DEMO FEEDBACK» 1.2:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/15_010.jpg](../../latest/shots/15_010.jpg)

### 17. Картинки без размеров — страница «прыгает» при загрузке
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: P1_1.jpg; P1_2.jpg; P1_3.jpg; P1_4.jpg; P1_5.jpg
- **Ожидалось:** width/height или aspect-ratio.
- **На деле:** Почему важно: Пока грузится картинка, текст и кнопки съезжают — промахи, плохая оценка Google (CLS).
- **Скриншот:** [shots/15_012.jpg](../../latest/shots/15_012.jpg)

### 18. Превью «02-After.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «02-After.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.9 дБ

### 19. Превью «01-Before.mp3» начинается с тишины (4.4 с)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

### 20. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 21. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=338 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=338 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 22. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=340 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=340 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 23. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=61 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=61 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

## ⚪ Проверить глазами (29)

### 24. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/legal-notice/ и ещё 37 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/10_002.jpg](../../latest/shots/10_002.jpg)

### 25. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/, https://solid-sound.net/product/infinity/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_008.jpg](../../latest/shots/10_008.jpg)

### 26. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/forum-experimental/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/10_001.jpg](../../latest/shots/10_001.jpg)

### 27. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_006.jpg](../../latest/shots/10_006.jpg)

### 28. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_007.jpg](../../latest/shots/10_007.jpg)

### 29. Кнопка «Video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_009.jpg](../../latest/shots/10_009.jpg)

### 30. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_010.jpg](../../latest/shots/10_010.jpg)

### 31. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3557. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3538. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/10_014.jpg](../../latest/shots/10_014.jpg)

### 32. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/presets/, https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_016.jpg](../../latest/shots/10_016.jpg)

### 33. Кнопка «SEND REQUEST — GET A REPLY IN 24 H» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/11_003.jpg](../../latest/shots/11_003.jpg)

### 34. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/, https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/13_011.jpg](../../latest/shots/13_011.jpg)

### 35. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/12_015.jpg](../../latest/shots/12_015.jpg)

### 36. Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/13_010.jpg](../../latest/shots/13_010.jpg)

### 37. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_008.jpg](../../latest/shots/15_008.jpg)

### 38. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_009.jpg](../../latest/shots/15_009.jpg)

### 39. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.5 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/15_013.jpg](../../latest/shots/15_013.jpg)

### 40. Кнопка «Отримати безкоштовно →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_015.jpg](../../latest/shots/18_015.jpg)

### 41. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_016.jpg](../../latest/shots/18_016.jpg)

### 42. Кнопка «Gratis-Paket holen →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_017.jpg](../../latest/shots/18_017.jpg)

### 43. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_018.jpg](../../latest/shots/18_018.jpg)

### 44. Кнопка «Obtenir le pack gratuit →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_019.jpg](../../latest/shots/18_019.jpg)

### 45. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_020.jpg](../../latest/shots/18_020.jpg)

### 46. Кнопка «Obter o pacote grátis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_021.jpg](../../latest/shots/18_021.jpg)

### 47. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_022.jpg](../../latest/shots/18_022.jpg)

### 48. Кнопка «Obtener el pack gratis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_023.jpg](../../latest/shots/18_023.jpg)

### 49. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_024.jpg](../../latest/shots/18_024.jpg)

### 50. Кнопка «Ottieni il pacchetto gratis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_025.jpg](../../latest/shots/18_025.jpg)

### 51. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_026.jpg](../../latest/shots/18_026.jpg)

### 52. Круглая кнопка-значок «без текста» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/19_005.jpg](../../latest/shots/19_005.jpg)

## ✅ Исправлено с прошлой проверки (3)

- После нажатия «Video» — ошибка в коде
- Главная картинка первого экрана грузится «лениво» (loading=lazy)
- Кнопка «Video» ничего не делает

## Не удалось проверить (2)

- Сценарий «Корзина» прервался: page.$: Execution context was destroyed, most likely because of a navigation
- Проверка остановлена по времени — часть ссылок/входов не проверена

