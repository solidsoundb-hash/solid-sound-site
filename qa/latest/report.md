# Автотестировщик — отчёт 2026-11-17 13:27 UTC (полная проверка)

Проверено: страниц 173, кнопок 704, кнопок звука 71, файлов звука 0, ссылок 336. Время: 95 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 110.
Найдено: 🔴 Критично 1 · 🟠 Важно 91 · 🟡 Мелочь 90 · ⚪ Проверить глазами 33 · новых 90 · исправлено с прошлого раза 109

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🔴 Критично (1)

### 1. Страница /en/wiki/balanced-vs-unbalanced-cables/ не открывается (код 503) 🆕
- **Где:** https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/
- **Ожидалось:** Страница открывается
- **На деле:** Сервер ответил ошибкой 503
- **Скриншот:** [shots/097.jpg](shots/097.jpg)

## 🟠 Важно (91)

### 2. Нет заголовка страницы (title) или он слишком длинный/короткий
- **Где:** https://solid-sound.net/ua/wiki-ua/iak-zapysaty-chystyi-vokal-udoma-3-pravyla-mikrofona/, https://solid-sound.net/ua/wiki-ua/zakryti-navushnyky-dlia-zapysu-vokalu-ua/, https://solid-sound.net/de/wiki-de/so-machst-du-saubere-vocals-zu-hause-3-mikrofon-regeln-die-einen/, https://solid-sound.net/de/wiki-de/symmetrische-unsymmetrische-kabel-de/, https://solid-sound.net/de/wiki-de/geschlossene-kopfhoerer-gesangsaufnahmen-de/, https://solid-sound.net/fr/wiki-fr/comment-enregistrer-des-voix-propres-a-la-maison-3-regles-de-micro/ и ещё 8 (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/wiki-ua/iak-zapysaty-chystyi-vokal-udoma-3-pravyla-mikrofona/ (компьютер)
- **Ожидалось:** title 15–65 символов, свой у каждой страницы.
- **На деле:** Сейчас: «Техніка роботи з мікрофоном: 3 правила чистого вокалу вдома – Solid Sound» (73 симв.). Почему важно: Заголовок — первая строка в выдаче Google. Пустой/обрезанный = меньше кликов. · Сейчас: «Закриті навушники для запису вокалу: позбудься витоку звуку – Solid Sound» (73 симв.). Почему важно: Заголовок  · Сейчас: «Mikrofontechnik für Gesang: 3 Regeln für saubere Vocals zu Hause – Solid Sound» (78 симв.). Почему важно: Загол · Сейчас: «Symmetrische vs. unsymmetrische Kabel: XLR, TRS und TS erklärt – Solid Sound» (76 симв.). Почему важно: Заголов · Сейчас: «Geschlossene Kopfhörer für Gesangsaufnahmen: Bleed stoppen – Solid Sound» (72 симв.). Почему важно: Заголовок —
- **Скриншот:** [shots/129.jpg](shots/129.jpg)

### 3. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/shop/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/, https://solid-sound.net/en/forum-experimental/ и ещё 4 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/en/all-products/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую). · Указано: https://solid-sound.net/en/sell/ Почему важно: Google решит, что это копия, и покажет другую страницу (или ника · Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу · Указано: https://solid-sound.net/en/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую). · Указано: https://solid-sound.net/en/my-account/ Почему важно: Google решит, что это копия, и покажет другую страницу (ил
- **Скриншот:** [shots/025.jpg](shots/025.jpg)

### 4. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div#templates.w-hwrapper.align_left «Play All 8 items Newest Top Sellers Popu»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/014.jpg](shots/014.jpg)

### 5. После нажатия «Video» — ошибка в коде 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Без ошибок
- **На деле:** console: Permissions policy violation: compute-pressure is not allowed in this document.
- **Скриншот:** [shots/017.jpg](shots/017.jpg)

### 6. Кнопка или ссылка без названия (только значок)
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a.wpf-sb-button.wpf-sb-icon; a.wpf-sb-button.wpf-sb-icon; a.wpf-sb-button.wpf-sb-icon
- **Ожидалось:** aria-label="Play", "Add to cart" и т.п.
- **На деле:** Почему важно: Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.
- **Скриншот:** [shots/062.jpg](shots/062.jpg)

### 7. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/006.jpg](shots/006.jpg)

### 8. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/016.jpg](shots/016.jpg)

### 9. «Video» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в span.ssh-tag
- **Скриншот:** [shots/022.jpg](shots/022.jpg)

### 10. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/023.jpg](shots/023.jpg)

### 11. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/privacy-policy/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/privacy-policy/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/024.jpg](shots/024.jpg)

### 12. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/026.jpg](shots/026.jpg)

### 13. «Video» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в span.ssh-tag
- **Скриншот:** [shots/028.jpg](shots/028.jpg)

### 14. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/029.jpg](shots/029.jpg)

### 15. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/cart/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/030.jpg](shots/030.jpg)

### 16. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/my-account/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/033.jpg](shots/033.jpg)

### 17. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/refund_returns/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/refund_returns/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/034.jpg](shots/034.jpg)

### 18. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/license/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/license/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/035.jpg](shots/035.jpg)

### 19. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/contacts/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/contacts/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/036.jpg](shots/036.jpg)

### 20. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/faq/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/037.jpg](shots/037.jpg)

### 21. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/store-manager/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/038.jpg](shots/038.jpg)

### 22. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/vendor-membership/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-membership/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/039.jpg](shots/039.jpg)

### 23. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/vendor-register/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-register/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/040.jpg](shots/040.jpg)

### 24. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/add-product/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/041.jpg](shots/041.jpg)

### 25. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/links/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/links/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/042.jpg](shots/042.jpg)

### 26. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/044.jpg](shots/044.jpg)

### 27. Кнопка ▶ «Play all tracks» включает другой трек
- **Где:** https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play all tracks»
- **Ожидалось:** Играет «pitch_128_kbps.mp3»
- **На деле:** Играет «kratos_dark_techno_128kbps.mp3»
- **Скриншот:** [shots/045.jpg](shots/045.jpg)

### 28. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/templates/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/046.jpg](shots/046.jpg)

### 29. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/presets/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/047.jpg](shots/047.jpg)

### 30. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/presets/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/048.jpg](shots/048.jpg)

### 31. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/services-calculator/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/058.jpg](shots/058.jpg)

### 32. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/mastering-album-calculator/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/mastering-album-calculator/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/059.jpg](shots/059.jpg)

### 33. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/music/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/060.jpg](shots/060.jpg)

### 34. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/music/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/061.jpg](shots/061.jpg)

### 35. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forum/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/064.jpg](shots/064.jpg)

### 36. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/074.jpg](shots/074.jpg)

### 37. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/075.jpg](shots/075.jpg)

### 38. «⚙️ Налаштування» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «⚙️ Налаштування»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в a
- **Скриншот:** [shots/078.jpg](shots/078.jpg)

### 39. «📜 Правила» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «📜 Правила»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#banner.pulse
- **Скриншот:** [shots/079.jpg](shots/079.jpg)

### 40. «Звинуватити! карта відкриється — хтось отримає Оману» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Звинуватити! карта відкриється — хтось отримає Оману»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#dockGrid
- **Скриншот:** [shots/080.jpg](shots/080.jpg)

### 41. «Зрозуміло» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Зрозуміло»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в img.cimg
- **Скриншот:** [shots/081.jpg](shots/081.jpg)

### 42. «🌿 Нова гра» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «🌿 Нова гра»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div.mtitle
- **Скриншот:** [shots/082.jpg](shots/082.jpg)

### 43. «Повірити вміння заявленого духа спрацює» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Повірити вміння заявленого духа спрацює»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в i
- **Скриншот:** [shots/083.jpg](shots/083.jpg)

### 44. «Cookie settings» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Cookie settings»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в p
- **Скриншот:** [shots/084.jpg](shots/084.jpg)

### 45. Ошибка в коде страницы (JavaScript)
- **Где:** https://solid-sound.net/en/prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ 2) Посмотреть консоль браузера
- **Ожидалось:** Без ошибок
- **На деле:** tailwind is not defined

### 46. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/prices/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/086.jpg](shots/086.jpg)

### 47. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/services-prices/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/087.jpg](shots/087.jpg)

### 48. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/sell/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/088.jpg](shots/088.jpg)

### 49. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/creator-terms/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/creator-terms/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/089.jpg](shots/089.jpg)

### 50. «Cookie settings» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/free/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать «Cookie settings»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ssp.ssp
- **Скриншот:** [shots/091.jpg](shots/091.jpg)

### 51. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/data-deletion/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/data-deletion/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/092.jpg](shots/092.jpg)

### 52. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/093.jpg](shots/093.jpg)

### 53. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/094.jpg](shots/094.jpg)

### 54. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/terms/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/terms/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/095.jpg](shots/095.jpg)

### 55. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/legal-notice/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/legal-notice/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/096.jpg](shots/096.jpg)

### 56. Не загружается файл или ресурс на странице 🆕
- **Где:** https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 503 /en/wiki/balanced-vs-unbalanced-cables/

### 57. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/100.jpg](shots/100.jpg)

### 58. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/audio-interface-for-vocals/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/audio-interface-for-vocals/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/101.jpg](shots/101.jpg)

### 59. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/102.jpg](shots/102.jpg)

### 60. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/sitemap/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sitemap/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/103.jpg](shots/103.jpg)

### 61. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/client-dashboard/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/client-dashboard/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/104.jpg](shots/104.jpg)

### 62. Меню на UA (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/107.jpg](shots/107.jpg)

### 63. Меню на UA (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/108.jpg](shots/108.jpg)

### 64. Меню на DE (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/111.jpg](shots/111.jpg)

### 65. Меню на DE (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/112.jpg](shots/112.jpg)

### 66. Меню на FR (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/115.jpg](shots/115.jpg)

### 67. Меню на FR (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/116.jpg](shots/116.jpg)

### 68. Меню на PT (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/119.jpg](shots/119.jpg)

### 69. Меню на PT (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/120.jpg](shots/120.jpg)

### 70. Меню на ES (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/123.jpg](shots/123.jpg)

### 71. Меню на ES (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/124.jpg](shots/124.jpg)

### 72. Меню на IT (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/127.jpg](shots/127.jpg)

### 73. Меню на IT (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/128.jpg](shots/128.jpg)

### 74. Поиск по слову «techno» ничего не находит
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть главную 2) Ввести «techno» в поиск 3) Нажать Enter
- **Ожидалось:** Список товаров
- **На деле:** Открылось https://solid-sound.net/?post_type=product&s=techno, товаров: 0
- **Скриншот:** [shots/130.jpg](shots/130.jpg)

### 75. Товар за $0 / €0 виден покупателям (не бесплатный пакет) 🆕
- **Где:** https://solid-sound.net/en/my-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ (компьютер)
- **Ожидалось:** Скрыть из каталога.
- **На деле:** Текст: «Bonus balance: $0.00». Почему важно: Обычно это технический товар — выглядит как ошибка магазина.
- **Скриншот:** [shots/132.jpg](shots/132.jpg)

### 76. Ошибка в коде страницы (JavaScript) 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Посмотреть консоль браузера
- **Ожидалось:** Без ошибок
- **На деле:** color is not defined

### 77. Ошибка в коде страницы (JavaScript) 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Посмотреть консоль браузера
- **Ожидалось:** Без ошибок
- **На деле:** Cannot read properties of undefined (reading 'green')

### 78. Битая ссылка: /ua/en/my-account/orders/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/orders/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/orders/ 2) Нажать ссылку на /ua/en/my-account/orders/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/orders/

### 79. Битая ссылка: /de/en/my-account/orders/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/orders/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/orders/ 2) Нажать ссылку на /de/en/my-account/orders/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/orders/

### 80. Битая ссылка: /fr/en/my-account/orders/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/orders/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/orders/ 2) Нажать ссылку на /fr/en/my-account/orders/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/orders/

### 81. Битая ссылка: /pt/en/my-account/orders/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/orders/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/orders/ 2) Нажать ссылку на /pt/en/my-account/orders/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/orders/

### 82. Битая ссылка: /es/en/my-account/orders/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/orders/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/orders/ 2) Нажать ссылку на /es/en/my-account/orders/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/orders/

### 83. Битая ссылка: /it/en/my-account/orders/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/orders/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/orders/ 2) Нажать ссылку на /it/en/my-account/orders/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/orders/

### 84. Битая ссылка: /ua/en/my-account/edit-account/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ 2) Нажать ссылку на /ua/en/my-account/edit-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/edit-account/

### 85. Битая ссылка: /de/en/my-account/edit-account/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ 2) Нажать ссылку на /de/en/my-account/edit-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/edit-account/

### 86. Битая ссылка: /fr/en/my-account/edit-account/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ 2) Нажать ссылку на /fr/en/my-account/edit-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/edit-account/

### 87. Битая ссылка: /pt/en/my-account/edit-account/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ 2) Нажать ссылку на /pt/en/my-account/edit-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/edit-account/

### 88. Битая ссылка: /es/en/my-account/edit-account/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ 2) Нажать ссылку на /es/en/my-account/edit-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/edit-account/

### 89. Битая ссылка: /it/en/my-account/edit-account/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ 2) Нажать ссылку на /it/en/my-account/edit-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/edit-account/

### 90. Битая ссылка: /en/store-manager/orders/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/store-manager/
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать ссылку на /en/store-manager/orders/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/store-manager/

### 91. Битая ссылка: /en/store-manager/edit-address/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/store-manager/
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать ссылку на /en/store-manager/edit-address/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/store-manager/

### 92. Битая ссылка: /en/store-manager/edit-account/ (код 404) 🆕
- **Где:** https://solid-sound.net/en/store-manager/
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать ссылку на /en/store-manager/edit-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/store-manager/

## 🟡 Мелочь (90)

### 93. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/ и ещё 110 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/004.jpg](shots/004.jpg)

### 94. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/ и ещё 34 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.ss-ai «AI search» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/020.jpg](shots/020.jpg)

### 95. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/ и ещё 31 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/013.jpg](shots/013.jpg)

### 96. Поле ввода без подписи
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ и ещё 29 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: input.inp [Your best email]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/003.jpg](shots/003.jpg)

### 97. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/ и ещё 23 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/007.jpg](shots/007.jpg)

### 98. Картинки без размеров — страница «прыгает» при загрузке
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/, https://solid-sound.net/it/product/protos/, https://solid-sound.net/it/product/kratos/, https://solid-sound.net/it/product/infinity/, https://solid-sound.net/it/product/intricate/ и ещё 3 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: xP1_1.jpg.pagespeed.ic.scqHlX9dBa.webp; xP1_2.jpg.pagespeed.ic.GWL1JTogGk.webp; xP1_3.jpg.pagespeed.ic.GgXTSEaTvn.webp; xP1_4.jpg.pagespeed.ic.xgRK2x1cif.webp; xP1_5.jpg.pagespeed.ic.X6I2H2Afi-.webp
- **Ожидалось:** width/height или aspect-ratio.
- **На деле:** Почему важно: Пока грузится картинка, текст и кнопки съезжают — промахи, плохая оценка Google (CLS).
- **Скриншот:** [shots/073.jpg](shots/073.jpg)

### 99. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ и ещё 2 (телефон, компьютер, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LENGTH:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/019.jpg](shots/019.jpg)

### 100. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/client-dashboard/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/005.jpg](shots/005.jpg)

### 101. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/021.jpg](shots/021.jpg)

### 102. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/checkout/, https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/checkout/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/031.jpg](shots/031.jpg)

### 103. Картинка загружается намного больше, чем показывается
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/, https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: xP1_3.jpg.pagespeed.ic.GgXTSEaTvn.webp 1080px → 218px; xP1_4.jpg.pagespeed.ic.xgRK2x1cif.webp 880px → 218px
- **Ожидалось:** srcset / миниатюра нужного размера.
- **На деле:** Почему важно: Лишние мегабайты на каждой загрузке.
- **Скриншот:** [shots/072.jpg](shots/072.jpg)

### 104. Кнопка «Mixing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Mixing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/049.jpg](shots/049.jpg)

### 105. Кнопка «Release Ready» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Release Ready»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/050.jpg](shots/050.jpg)

### 106. Кнопка «Mastering» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Mastering»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/051.jpg](shots/051.jpg)

### 107. Кнопка «Remix» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Remix»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/052.jpg](shots/052.jpg)

### 108. Кнопка «Remake» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Remake»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/053.jpg](shots/053.jpg)

### 109. Кнопка «Ghost producing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Ghost producing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/054.jpg](shots/054.jpg)

### 110. Кнопка «Co-producing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Co-producing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/055.jpg](shots/055.jpg)

### 111. Кнопка «Post production» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Post production»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/056.jpg](shots/056.jpg)

### 112. Внешняя ссылка в новой вкладке без rel="noopener"
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fsolid-sound.net%2Fcom; https://web.whatsapp.com/send?text=Solid+Sound+Forum%20URL:%20https%3A%2F%2Fsoli
- **Ожидалось:** rel="noopener noreferrer".
- **На деле:** Почему важно: Чужой сайт может подменить нашу вкладку (фишинг).
- **Скриншот:** [shots/063.jpg](shots/063.jpg)

### 113. Кнопка «GET A FREE DEMO FEEDBACK» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «GET A FREE DEMO FEEDBACK»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/066.jpg](shots/066.jpg)

### 114. Кнопка «WRITE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «WRITE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/067.jpg](shots/067.jpg)

### 115. Кнопка «SEE MORE CLIENT STORIES» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «SEE MORE CLIENT STORIES»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 52px)
- **Скриншот:** [shots/068.jpg](shots/068.jpg)

### 116. Кнопка «MESSAGE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «MESSAGE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 62px)
- **Скриншот:** [shots/071.jpg](shots/071.jpg)

### 117. Нет описания страницы (meta description) 🆕
- **Где:** https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ (телефон)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.
- **Скриншот:** [shots/098.jpg](shots/098.jpg)

### 118. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 🆕
- **Где:** https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ (телефон)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: нет, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.
- **Скриншот:** [shots/099.jpg](shots/099.jpg)

### 119. Ссылка «#chat» ведёт в никуда (нет такого раздела на странице) 🆕
- **Где:** https://solid-sound.net/en/my-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать ссылку #chat
- **Ожидалось:** Прокрутка к разделу
- **На деле:** Раздела с таким id нет
- **Скриншот:** [shots/131.jpg](shots/131.jpg)

### 120. Ссылка «#chat» ведёт в никуда (нет такого раздела на странице) 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать ссылку #chat
- **Ожидалось:** Прокрутка к разделу
- **На деле:** Раздела с таким id нет
- **Скриншот:** [shots/133.jpg](shots/133.jpg)

### 121. Ссылка /product-genre/cinematic/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/cinematic/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Cinematic

### 122. Ссылка /product-genre/melodic-techno/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/melodic-techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Melodic%20Techno

### 123. Ссылка /external-plugin/serum/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/serum/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Serum

### 124. Ссылка /product-genre/techno/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Techno

### 125. Ссылка /product-version/ableton-live-11/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-11/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Ableton%20Live%2011

### 126. Ссылка /external-plugin/no-external-plugins/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/no-external-plugins/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=No%20External%20Plugins

### 127. Ссылка /product-version/ableton-live-10/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-10/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Ableton%20Live%2010

### 128. Ссылка /it/product-tag/pitch/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/pitch/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/pitch/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=%23Pitch

### 129. Ссылка /it/product-tag/techno/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/melodic-techno-presets/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=%23Techno

### 130. Ссылка /it/product-tag/presets/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/melodic-techno-presets/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/presets/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=%23Presets

### 131. Ссылка /it/product-tag/melodic-techno/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/melodic-techno-presets/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/melodic-techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=%23Melodic%20Techno

### 132. Ссылка /it/product-tag/optimism/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/optimism/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=optimism

### 133. Ссылка /it/product-tag/cozy/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/cozy/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=cozy

### 134. Ссылка /it/product-tag/delicate/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/delicate/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=delicate

### 135. Ссылка /it/product-tag/dreamy/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/dreamy/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=dreamy

### 136. Ссылка /it/product-tag/serene/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/serene/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=serene

### 137. Ссылка /it/product-tag/ambient/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/ambient/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=ambient

### 138. Ссылка /it/product-tag/storytelling/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/storytelling/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=storytelling

### 139. Ссылка /it/product-tag/melody/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/melody/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=melody

### 140. Ссылка /it/product-tag/harmony/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/harmony/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=harmony

### 141. Ссылка /it/product-tag/inspiring/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/inspiring/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=inspiring

### 142. Ссылка /it/product-tag/soothing/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/soothing/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=soothing

### 143. Ссылка /it/product-tag/tranquil/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/tranquil/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=tranquil

### 144. Ссылка /it/product-tag/success/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/success/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=success

### 145. Ссылка /it/product-tag/thoughtful/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/thoughtful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=thoughtful

### 146. Ссылка /it/product-tag/mindful/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/mindful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=mindful

### 147. Ссылка /it/product-tag/smooth/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/smooth/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=smooth

### 148. Ссылка /it/product-tag/refreshing/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/refreshing/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=refreshing

### 149. Ссылка /it/product-tag/meditation/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/meditation/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=meditation

### 150. Ссылка /it/product-tag/calmness/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/calmness/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=calmness

### 151. Ссылка /it/product-tag/tender/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/tender/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=tender

### 152. Ссылка /it/product-tag/classical/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/classical/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=classical

### 153. Ссылка /it/product-tag/creative/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/creative/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=creative

### 154. Ссылка /it/product-tag/comfort/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/comfort/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=comfort

### 155. Ссылка /it/product-tag/atmospheric/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/atmospheric/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=atmospheric

### 156. Ссылка /it/product-tag/balance/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/balance/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=balance

### 157. Ссылка /it/product-tag/energy/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/energy/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=energy

### 158. Ссылка /it/product-tag/flow/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/flow/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=flow

### 159. Ссылка /it/product-tag/spirit/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/spirit/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=spirit

### 160. Ссылка /it/product-tag/elegant/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/elegant/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=elegant

### 161. Ссылка /it/product-tag/piano/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/piano/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=piano

### 162. Ссылка /it/product-tag/inspirational/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/inspirational/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=inspirational

### 163. Ссылка /it/product-tag/uplifting/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/uplifting/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=uplifting

### 164. Ссылка /it/product-tag/morning/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/morning/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=morning

### 165. Ссылка /it/product-tag/motivation/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/motivation/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=motivation

### 166. Ссылка /it/product-tag/calm/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/calm/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=calm

### 167. Ссылка /it/product-tag/relaxing/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/relaxing/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=relaxing

### 168. Ссылка /it/product-tag/positive/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/positive/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=positive

### 169. Ссылка /it/product-tag/warm/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/warm/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=warm

### 170. Ссылка /it/product-tag/emotional/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/emotional/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=emotional

### 171. Ссылка /it/product-tag/gentle/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/gentle/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=gentle

### 172. Ссылка /it/product-tag/background/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/background/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=background

### 173. Ссылка /it/product-tag/cinematic/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/cinematic/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=cinematic

### 174. Ссылка /it/product-tag/peaceful/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/peaceful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=peaceful

### 175. Ссылка /it/product-tag/hopeful/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/hopeful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=hopeful

### 176. Ссылка /it/product-tag/soft/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/soft/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=soft

### 177. Ссылка /it/product-tag/bright/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/bright/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=bright

### 178. Ссылка /it/product-tag/fresh/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/fresh/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=fresh

### 179. Ссылка /it/product-tag/happiness/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/happiness/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=happiness

### 180. Ссылка /it/product-tag/joyful/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/joyful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=joyful

### 181. Ссылка /it/product-tag/light/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/light/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=light

### 182. Ссылка /it/product-tag/reflective/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/reflective/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=reflective

## ⚪ Проверить глазами (33)

### 183. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/my-account/ и ещё 37 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/002.jpg](shots/002.jpg)

### 184. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/, https://solid-sound.net/product/infinity/ и ещё 30 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/010.jpg](shots/010.jpg)

### 185. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/, https://solid-sound.net/it/templates-it/, https://solid-sound.net/it/presets-it/, https://solid-sound.net/it/music-it/, https://solid-sound.net/it/contacts-it/, https://solid-sound.net/it/wiki-it/ и ещё 12 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/126.jpg](shots/126.jpg)

### 186. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/ua/shablony/, https://solid-sound.net/ua/presety/, https://solid-sound.net/ua/sample-page-uk/, https://solid-sound.net/ua/templates-uk/ и ещё 11 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/043.jpg](shots/043.jpg)

### 187. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/, https://solid-sound.net/ua/shablony/, https://solid-sound.net/ua/presety/, https://solid-sound.net/ua/muzyka/, https://solid-sound.net/ua/kontakty/, https://solid-sound.net/ua/sample-page-uk/ и ещё 9 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/106.jpg](shots/106.jpg)

### 188. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/, https://solid-sound.net/de/vorlagen/, https://solid-sound.net/de/voreinstellungen/, https://solid-sound.net/de/musik/, https://solid-sound.net/de/kontakt/, https://solid-sound.net/de/wiki-de/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/110.jpg](shots/110.jpg)

### 189. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/, https://solid-sound.net/fr/modeles/, https://solid-sound.net/fr/prereglages/, https://solid-sound.net/fr/musique/, https://solid-sound.net/fr/contact/, https://solid-sound.net/fr/wiki-fr/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/114.jpg](shots/114.jpg)

### 190. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/, https://solid-sound.net/pt/modelos/, https://solid-sound.net/pt/predefinicoes/, https://solid-sound.net/pt/musica/, https://solid-sound.net/pt/contactos/, https://solid-sound.net/pt/wiki-pt/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/118.jpg](shots/118.jpg)

### 191. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/, https://solid-sound.net/es/templates-es/, https://solid-sound.net/es/presets-es/, https://solid-sound.net/es/music-es/, https://solid-sound.net/es/contacts-es/, https://solid-sound.net/es/wiki-es/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/122.jpg](shots/122.jpg)

### 192. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/ua/sample-page-uk/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3564. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3563. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3542. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3078. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/015.jpg](shots/015.jpg)

### 193. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/checkout/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/checkout/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.2 МБ. Почему важно: На 3G/4G в дороге — долго и дорого. · Скачано 3.1 МБ. Почему важно: На 3G/4G в дороге — долго и дорого. · Скачано 3.5 МБ. Почему важно: На 3G/4G в дороге — долго и дорого. · Скачано 3.7 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/032.jpg](shots/032.jpg)

### 194. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/forum-experimental/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/001.jpg](shots/001.jpg)

### 195. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/008.jpg](shots/008.jpg)

### 196. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/009.jpg](shots/009.jpg)

### 197. Кнопка «Video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/011.jpg](shots/011.jpg)

### 198. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/012.jpg](shots/012.jpg)

### 199. Кнопка «SEND REQUEST — GET A REPLY IN 24 H» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/057.jpg](shots/057.jpg)

### 200. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/065.jpg](shots/065.jpg)

### 201. Кнопка «Video» ничего не делает 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/018.jpg](shots/018.jpg)

### 202. Кнопка «Video» ничего не делает 🆕
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/027.jpg](shots/027.jpg)

### 203. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/069.jpg](shots/069.jpg)

### 204. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/070.jpg](shots/070.jpg)

### 205. Кнопка «Повірити вміння заявленого духа спрацює» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/076.jpg](shots/076.jpg)

### 206. Кнопка «Звинуватити! карта відкриється — хтось о» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/077.jpg](shots/077.jpg)

### 207. Кнопка «Expand All Details» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/085.jpg](shots/085.jpg)

### 208. Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/090.jpg](shots/090.jpg)

### 209. Кнопка «Отримати безкоштовно →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/105.jpg](shots/105.jpg)

### 210. Кнопка «Gratis-Paket holen →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/109.jpg](shots/109.jpg)

### 211. Кнопка «Obtenir le pack gratuit →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/113.jpg](shots/113.jpg)

### 212. Кнопка «Obter o pacote grátis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/117.jpg](shots/117.jpg)

### 213. Кнопка «Obtener el pack gratis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/121.jpg](shots/121.jpg)

### 214. Кнопка «Ottieni il pacchetto gratis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/125.jpg](shots/125.jpg)

### 215. Круглая кнопка-значок «без текста» — нужна ли квадратная со скруглёнными углами? 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/134.jpg](shots/134.jpg)

## ✅ Исправлено с прошлой проверки (109)

- Меню на IT ведёт на страницы на другом языке
- Меню на UA ведёт на страницы на другом языке
- Меню на DE ведёт на страницы на другом языке
- Меню на FR ведёт на страницы на другом языке
- Меню на PT ведёт на страницы на другом языке
- Меню на ES ведёт на страницы на другом языке
- «Browse products» нельзя нажать — сверху лежит другой элемент
- «All» нельзя нажать — сверху лежит другой элемент
- «Templates» нельзя нажать — сверху лежит другой элемент
- «Music» нельзя нажать — сверху лежит другой элемент
- «Cookie settings» нельзя нажать — сверху лежит другой элемент
- «Menu» нельзя нажать — сверху лежит другой элемент
- «Browse products» нельзя нажать — сверху лежит другой элемент
- «All» нельзя нажать — сверху лежит другой элемент
- «Templates» нельзя нажать — сверху лежит другой элемент
- «Music» нельзя нажать — сверху лежит другой элемент
- «Cookie settings» нельзя нажать — сверху лежит другой элемент
- «Menu» нельзя нажать — сверху лежит другой элемент
- Страница /all-products не переведена — переключатель языка выкидывает на главную
- Страница /privacy-policy не переведена — переключатель языка выкидывает на главную
- Страница /shop не переведена — переключатель языка выкидывает на главную
- Страница /refund_returns не переведена — переключатель языка выкидывает на главную
- Страница /license не переведена — переключатель языка выкидывает на главную
- Страница /faq не переведена — переключатель языка выкидывает на главную
- Страница /vendor-membership не переведена — переключатель языка выкидывает на главную
- Страница /vendor-register не переведена — переключатель языка выкидывает на главную
- Страница /add-product не переведена — переключатель языка выкидывает на главную
- Страница /services-calculator не переведена — переключатель языка выкидывает на главную
- Страница /mastering-album-calculator не переведена — переключатель языка выкидывает на главную
- Страница /forum не переведена — переключатель языка выкидывает на главную
- Страница /forest-spirits не переведена — переключатель языка выкидывает на главную
- Страница /prices не переведена — переключатель языка выкидывает на главную
- Страница /services-prices не переведена — переключатель языка выкидывает на главную
- Страница /sell не переведена — переключатель языка выкидывает на главную
- Страница /creator-terms не переведена — переключатель языка выкидывает на главную
- Страница /free не переведена — переключатель языка выкидывает на главную
- Страница /data-deletion не переведена — переключатель языка выкидывает на главную
- Страница /terms не переведена — переключатель языка выкидывает на главную
- Страница /legal-notice не переведена — переключатель языка выкидывает на главную
- Страница /sitemap не переведена — переключатель языка выкидывает на главную

## Не удалось проверить (1)

- Звук файлов не проверен: ffmpeg не установлен

