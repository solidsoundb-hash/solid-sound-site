# Автотестировщик — отчёт 2026-11-16 18:31 UTC (полная проверка) — ⏳ проверка ещё идёт, отчёт неполный

Проверено: страниц 45, кнопок 333, кнопок звука 61, ссылок 0. Время: 160 мин.
Найдено: 🔴 Критично 0 · 🟠 Важно 35 · 🟡 Мелочь 38 · ⚪ Проверить глазами 43

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (35)

### 1. Нижний плеер показывает «играет», но звука нет
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ (компьютер, телефон)
- **Шаги:** 1) Нажать ▶ в нижнем плеере
- **Ожидалось:** Играет трек
- **На деле:** Тишина
- **Скриншот:** [shots/023.jpg](shots/023.jpg)

### 2. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/shop/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/en/all-products/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую). · Указано: https://solid-sound.net/en/sell/ Почему важно: Google решит, что это копия, и покажет другую страницу (или ника · Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу
- **Скриншот:** [shots/040.jpg](shots/040.jpg)

### 3. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div#templates.w-hwrapper.align_left «Play All 8 items Newest Top Sellers Popu»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/020.jpg](shots/020.jpg)

### 4. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/006.jpg](shots/006.jpg)

### 5. «Video» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ss-ytm.open
- **Скриншот:** [shots/030.jpg](shots/030.jpg)

### 6. «Browse products» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Browse products»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ss-ytm.open
- **Скриншот:** [shots/031.jpg](shots/031.jpg)

### 7. «All» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «All»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ss-ytm.open
- **Скриншот:** [shots/032.jpg](shots/032.jpg)

### 8. «Templates» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Templates»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ss-ytm.open
- **Скриншот:** [shots/033.jpg](shots/033.jpg)

### 9. «Music» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Music»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ss-ytm.open
- **Скриншот:** [shots/034.jpg](shots/034.jpg)

### 10. «Cookie settings» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Cookie settings»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ss-ytm.open
- **Скриншот:** [shots/035.jpg](shots/035.jpg)

### 11. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ss-ytm.open
- **Скриншот:** [shots/036.jpg](shots/036.jpg)

### 12. «Menu» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Menu»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ss-ytm.open
- **Скриншот:** [shots/037.jpg](shots/037.jpg)

### 13. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/privacy-policy/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/privacy-policy/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/039.jpg](shots/039.jpg)

### 14. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/041.jpg](shots/041.jpg)

### 15. «Video» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в span.ssh-tag
- **Скриншот:** [shots/047.jpg](shots/047.jpg)

### 16. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/048.jpg](shots/048.jpg)

### 17. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/cart/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/050.jpg](shots/050.jpg)

### 18. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/my-account/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/055.jpg](shots/055.jpg)

### 19. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/refund_returns/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/refund_returns/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/057.jpg](shots/057.jpg)

### 20. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/license/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/license/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/058.jpg](shots/058.jpg)

### 21. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/contacts/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/contacts/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/059.jpg](shots/059.jpg)

### 22. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/faq/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/061.jpg](shots/061.jpg)

### 23. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/store-manager/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/063.jpg](shots/063.jpg)

### 24. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/vendor-membership/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-membership/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/064.jpg](shots/064.jpg)

### 25. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/vendor-register/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-register/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/065.jpg](shots/065.jpg)

### 26. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/add-product/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/067.jpg](shots/067.jpg)

### 27. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/links/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/links/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/069.jpg](shots/069.jpg)

### 28. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/templates/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/073.jpg](shots/073.jpg)

### 29. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/templates/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/074.jpg](shots/074.jpg)

### 30. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/presets/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/077.jpg](shots/077.jpg)

### 31. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/services-calculator/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/088.jpg](shots/088.jpg)

### 32. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/mastering-album-calculator/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/mastering-album-calculator/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/089.jpg](shots/089.jpg)

### 33. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/music/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/091.jpg](shots/091.jpg)

### 34. Кнопка или ссылка без названия (только значок)
- **Где:** https://solid-sound.net/en/forum/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a.wpf-sb-button.wpf-sb-icon; a.wpf-sb-button.wpf-sb-icon; a.wpf-sb-button.wpf-sb-icon
- **Ожидалось:** aria-label="Play", "Add to cart" и т.п.
- **На деле:** Почему важно: Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.
- **Скриншот:** [shots/092.jpg](shots/092.jpg)

### 35. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forum/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/095.jpg](shots/095.jpg)

## 🟡 Мелочь (38)

### 36. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/ и ещё 16 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/004.jpg](shots/004.jpg)

### 37. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/ и ещё 15 (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: b «GENRE» 10px; b «WHAT DO YOU NEED?» 10px; b «INCLUDED» 10px; b «PROJECT» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/011.jpg](shots/011.jpg)

### 38. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/ и ещё 7 (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/010.jpg](shots/010.jpg)

### 39. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/ и ещё 5 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/019.jpg](shots/019.jpg)

### 40. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ и ещё 2 (телефон, компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LENGTH:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/028.jpg](shots/028.jpg)

### 41. Поле ввода без подписи
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: input.inp [Your best email]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/003.jpg](shots/003.jpg)

### 42. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/029.jpg](shots/029.jpg)

### 43. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/forum/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/005.jpg](shots/005.jpg)

### 44. Главная картинка первого экрана грузится «лениво» (loading=lazy)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: 0621-%D0%9E%D0%B1%D0%BB%D0%BE%D0%B6%D0%BA%D0%B0-e17555510432; Pitch_Title_PSD-400x400.jpg; Mutualism_Logo_JPG-400x400.jpg; Intricate_Logo_JPG-400x400.jpg; Protos_Logo_JPG-400x400.jpg
- **Ожидалось:** Для первого экрана — без lazy, с fetchpriority=high.
- **На деле:** Почему важно: Самая важная картинка появляется последней.
- **Скриншот:** [shots/012.jpg](shots/012.jpg)

### 45. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/checkout/, https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/checkout/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/051.jpg](shots/051.jpg)

### 46. Кнопка «Browse products» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Найти кнопку «Browse products»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/017.jpg](shots/017.jpg)

### 47. Кнопка «Mixing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Mixing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/078.jpg](shots/078.jpg)

### 48. Кнопка «Release Ready» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Release Ready»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/079.jpg](shots/079.jpg)

### 49. Кнопка «Mastering» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Mastering»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/080.jpg](shots/080.jpg)

### 50. Кнопка «Remix» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Remix»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/081.jpg](shots/081.jpg)

### 51. Кнопка «Remake» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Remake»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/082.jpg](shots/082.jpg)

### 52. Кнопка «Ghost producing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Ghost producing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/083.jpg](shots/083.jpg)

### 53. Кнопка «Co-producing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Co-producing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/084.jpg](shots/084.jpg)

### 54. Кнопка «Post production» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Post production»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/085.jpg](shots/085.jpg)

### 55. Страница /all-products не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 56. Страница /privacy-policy не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/privacy-policy/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/privacy-policy/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 57. Страница /shop не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 58. Страница /refund_returns не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/refund_returns/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/refund_returns/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 59. Страница /license не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/license/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/license/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 60. Страница /faq не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 61. Страница /vendor-membership не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/vendor-membership/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-membership/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 62. Страница /vendor-register не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/vendor-register/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-register/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 63. Страница /add-product не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/add-product/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 64. Страница /services-calculator не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/services-calculator/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, UA, DE, FR, PT, ES, IT — открывается главная

### 65. Страница /mastering-album-calculator не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/mastering-album-calculator/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/mastering-album-calculator/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, UA, DE, FR, PT, ES, IT — открывается главная

### 66. Внешняя ссылка в новой вкладке без rel="noopener"
- **Где:** https://solid-sound.net/en/forum/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fsolid-sound.net%2Fcom; https://web.whatsapp.com/send?text=Solid+Sound+Forum%20URL:%20https%3A%2F%2Fsoli
- **Ожидалось:** rel="noopener noreferrer".
- **На деле:** Почему важно: Чужой сайт может подменить нашу вкладку (фишинг).
- **Скриншот:** [shots/093.jpg](shots/093.jpg)

### 67. Страница /forum не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/forum/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 68. Кнопка «GET A FREE DEMO FEEDBACK» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «GET A FREE DEMO FEEDBACK»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/097.jpg](shots/097.jpg)

### 69. Кнопка «WRITE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «WRITE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/098.jpg](shots/098.jpg)

### 70. Кнопка «SEE MORE CLIENT STORIES» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «SEE MORE CLIENT STORIES»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 52px)
- **Скриншот:** [shots/099.jpg](shots/099.jpg)

### 71. Кнопка «MESSAGE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «MESSAGE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 62px)
- **Скриншот:** [shots/102.jpg](shots/102.jpg)

### 72. Картинка загружается намного больше, чем показывается
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: P1_3.jpg 1080px → 218px; P1_4.jpg 880px → 218px
- **Ожидалось:** srcset / миниатюра нужного размера.
- **На деле:** Почему важно: Лишние мегабайты на каждой загрузке.
- **Скриншот:** [shots/103.jpg](shots/103.jpg)

### 73. Картинки без размеров — страница «прыгает» при загрузке
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: P1_1.jpg; P1_2.jpg; P1_3.jpg; P1_4.jpg; P1_5.jpg
- **Ожидалось:** width/height или aspect-ratio.
- **На деле:** Почему важно: Пока грузится картинка, текст и кнопки съезжают — промахи, плохая оценка Google (CLS).
- **Скриншот:** [shots/104.jpg](shots/104.jpg)

## ⚪ Проверить глазами (43)

### 74. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/my-account/ и ещё 16 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/002.jpg](shots/002.jpg)

### 75. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/015.jpg](shots/015.jpg)

### 76. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/013.jpg](shots/013.jpg)

### 77. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/014.jpg](shots/014.jpg)

### 78. Кнопка «Video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/016.jpg](shots/016.jpg)

### 79. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/018.jpg](shots/018.jpg)

### 80. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3554. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3553. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/021.jpg](shots/021.jpg)

### 81. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/checkout/, https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/checkout/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.2 МБ. Почему важно: На 3G/4G в дороге — долго и дорого. · Скачано 3.5 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/052.jpg](shots/052.jpg)

### 82. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/070.jpg](shots/070.jpg)

### 83. Кнопка «SEND REQUEST — GET A REPLY IN 24 H» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/086.jpg](shots/086.jpg)

### 84. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/001.jpg](shots/001.jpg)

### 85. Кнопка «For you» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «For you»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/007.jpg](shots/007.jpg)

### 86. Кнопка «All» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «All»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/008.jpg](shots/008.jpg)

### 87. Кнопка «Page 1» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «Page 1»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/009.jpg](shots/009.jpg)

### 88. Трек долго не начинает играть (больше 12 секунд)
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Звук через 1–3 секунды
- **На деле:** Плеер запущен, но звук не идёт 12 секунд (медленная загрузка файла)
- **Скриншот:** [shots/022.jpg](shots/022.jpg)

### 89. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/024.jpg](shots/024.jpg)

### 90. Кнопка «Previous» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Previous»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/025.jpg](shots/025.jpg)

### 91. Кнопка «Next» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Next»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/026.jpg](shots/026.jpg)

### 92. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/027.jpg](shots/027.jpg)

### 93. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/privacy-policy/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/privacy-policy/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/038.jpg](shots/038.jpg)

### 94. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/042.jpg](shots/042.jpg)

### 95. Кнопка «Previous» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Previous»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/043.jpg](shots/043.jpg)

### 96. Кнопка «Next» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Next»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/044.jpg](shots/044.jpg)

### 97. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/045.jpg](shots/045.jpg)

### 98. Трек долго не начинает играть (больше 12 секунд)
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать ▶ «Play»
- **Ожидалось:** Звук через 1–3 секунды
- **На деле:** Плеер запущен, но звук не идёт 12 секунд (медленная загрузка файла)
- **Скриншот:** [shots/046.jpg](shots/046.jpg)

### 99. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/cart/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/049.jpg](shots/049.jpg)

### 100. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/checkout/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/checkout/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/053.jpg](shots/053.jpg)

### 101. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/my-account/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/054.jpg](shots/054.jpg)

### 102. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/refund_returns/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/refund_returns/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/056.jpg](shots/056.jpg)

### 103. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/060.jpg](shots/060.jpg)

### 104. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/062.jpg](shots/062.jpg)

### 105. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/add-product/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/066.jpg](shots/066.jpg)

### 106. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/links/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/links/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/068.jpg](shots/068.jpg)

### 107. Трек долго не начинает играть (больше 12 секунд)
- **Где:** https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play»
- **Ожидалось:** Звук через 1–3 секунды
- **На деле:** Плеер запущен, но звук не идёт 12 секунд (медленная загрузка файла)
- **Скриншот:** [shots/071.jpg](shots/071.jpg)

### 108. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/072.jpg](shots/072.jpg)

### 109. Трек долго не начинает играть (больше 12 секунд)
- **Где:** https://solid-sound.net/en/presets/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать ▶ «Play»
- **Ожидалось:** Звук через 1–3 секунды
- **На деле:** Плеер запущен, но звук не идёт 12 секунд (медленная загрузка файла)
- **Скриншот:** [shots/075.jpg](shots/075.jpg)

### 110. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/presets/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/076.jpg](shots/076.jpg)

### 111. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/services-calculator/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/087.jpg](shots/087.jpg)

### 112. Трек долго не начинает играть (больше 12 секунд)
- **Где:** https://solid-sound.net/en/music/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать ▶ «Play»
- **Ожидалось:** Звук через 1–3 секунды
- **На деле:** Плеер запущен, но звук не идёт 12 секунд (медленная загрузка файла)
- **Скриншот:** [shots/090.jpg](shots/090.jpg)

### 113. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/forum/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/094.jpg](shots/094.jpg)

### 114. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/096.jpg](shots/096.jpg)

### 115. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/100.jpg](shots/100.jpg)

### 116. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/101.jpg](shots/101.jpg)

## Не удалось проверить (114)

- /en/free-demo-feedback/ (телефон) — не хватило времени
- /en/forum-experimental/ (компьютер) — не хватило времени
- /en/forum-experimental/ (телефон) — не хватило времени
- /en/forest-spirits/ (компьютер) — не хватило времени
- /en/forest-spirits/ (телефон) — не хватило времени
- /en/prices/ (компьютер) — не хватило времени
- /en/prices/ (телефон) — не хватило времени
- /en/services-prices/ (компьютер) — не хватило времени
- /en/services-prices/ (телефон) — не хватило времени
- /en/sell/ (компьютер) — не хватило времени
- /en/sell/ (телефон) — не хватило времени
- /en/creator-terms/ (компьютер) — не хватило времени
- /en/creator-terms/ (телефон) — не хватило времени
- /en/free/ (компьютер) — не хватило времени
- /en/free/ (телефон) — не хватило времени
- /en/data-deletion/ (компьютер) — не хватило времени
- /en/data-deletion/ (телефон) — не хватило времени
- /en/wiki/ (компьютер) — не хватило времени
- /en/wiki/ (телефон) — не хватило времени
- /en/wiki/record-clean-vocals-at-home/ (компьютер) — не хватило времени
- /en/wiki/record-clean-vocals-at-home/ (телефон) — не хватило времени
- /en/terms/ (компьютер) — не хватило времени
- /en/terms/ (телефон) — не хватило времени
- /en/legal-notice/ (компьютер) — не хватило времени
- /en/legal-notice/ (телефон) — не хватило времени
- /en/wiki/balanced-vs-unbalanced-cables/ (компьютер) — не хватило времени
- /en/wiki/balanced-vs-unbalanced-cables/ (телефон) — не хватило времени
- /en/wiki/audio-interface-for-vocals/ (компьютер) — не хватило времени
- /en/wiki/audio-interface-for-vocals/ (телефон) — не хватило времени
- /en/wiki/closed-back-headphones-for-recording/ (компьютер) — не хватило времени
- /en/wiki/closed-back-headphones-for-recording/ (телефон) — не хватило времени
- /en/sitemap/ (компьютер) — не хватило времени
- /en/sitemap/ (телефон) — не хватило времени
- /en/client-dashboard/ (компьютер) — не хватило времени
- /en/client-dashboard/ (телефон) — не хватило времени
- /ua/ (компьютер) — не хватило времени
- /ua/ (телефон) — не хватило времени
- /de/ (компьютер) — не хватило времени
- /de/ (телефон) — не хватило времени
- /fr/ (компьютер) — не хватило времени
- /fr/ (телефон) — не хватило времени
- /pt/ (компьютер) — не хватило времени
- /pt/ (телефон) — не хватило времени
- /es/ (компьютер) — не хватило времени
- /es/ (телефон) — не хватило времени
- /it/ (компьютер) — не хватило времени
- /it/ (телефон) — не хватило времени
- /ua/shablony/ (компьютер) — не хватило времени
- /ua/presety/ (компьютер) — не хватило времени
- /ua/muzyka/ (компьютер) — не хватило времени
- /ua/kontakty/ (компьютер) — не хватило времени
- /ua/sample-page-uk/ (компьютер) — не хватило времени
- /ua/templates-uk/ (компьютер) — не хватило времени
- /ua/presets-uk/ (компьютер) — не хватило времени
- /ua/music-uk/ (компьютер) — не хватило времени
- /ua/contacts-uk/ (компьютер) — не хватило времени
- /ua/wiki-ua/ (компьютер) — не хватило времени
- /ua/wiki-ua/iak-zapysaty-chystyi-vokal-udoma-3-pravyla-mikrofona/ (компьютер) — не хватило времени
- /ua/wiki-ua/audiointerfeis-dlia-zapysu-vokalu-ua/ (компьютер) — не хватило времени
- /ua/wiki-ua/zakryti-navushnyky-dlia-zapysu-vokalu-ua/ (компьютер) — не хватило времени

