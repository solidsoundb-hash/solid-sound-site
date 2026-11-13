# Автотестировщик — отчёт 2026-11-13 07:55 UTC (быстрая проверка)

Проверено: страниц 28, кнопок 118, кнопок звука 17, ссылок 150. Время: 24 мин.
Найдено: 🔴 Критично 1 · 🟠 Важно 35 · 🟡 Мелочь 87 · ⚪ Проверить глазами 41 · новых 63 · исправлено с прошлого раза 16

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🔴 Критично (1)

### 1. Главная на других языках — другая (старая) страница, а не переведённая новая
- **Где:** https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Открыть https://solid-sound.net/ua/ 3) Сравнить
- **Ожидалось:** Та же страница, только переведённая
- **На деле:** UA: совпадает с английской на 10%; DE: совпадает с английской на 10%; FR: совпадает с английской на 10%; PT: совпадает с английской на 10%; ES: совпадает с английской на 10%; IT: совпадает с английской на 10%

## 🟠 Важно (35)

### 2. Кнопка или ссылка без названия (только значок) 🆕
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/faq/ и ещё 7 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover
- **Ожидалось:** aria-label="Play", "Add to cart" и т.п.
- **На деле:** Почему важно: Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.
- **Скриншот:** [shots/014.jpg](shots/014.jpg)

### 3. После нажатия «Products» — ошибка в коде
- **Где:** https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/faq/, https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать «Products»
- **Ожидалось:** Без ошибок
- **На деле:** mod_pagespeed_CpaDOhQqFK is not defined
- **Скриншот:** [shots/057.jpg](shots/057.jpg)

### 4. Кнопка «Play Time Goes Fast Documentary Piano preview» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Time Goes Fast Documentary Piano preview»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/018.jpg](shots/018.jpg)

### 5. Кнопка «Play Pitch preview» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Pitch preview»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/019.jpg](shots/019.jpg)

### 6. Кнопка «Play Mutualism preview» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Mutualism preview»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/020.jpg](shots/020.jpg)

### 7. «For you» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «For you»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в a
- **Скриншот:** [shots/027.jpg](shots/027.jpg)

### 8. Важную кнопку закрывает что-то сверху (чат, плеер, баннер) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div.w-filter-item.ssf-fmt «Templates Presets Reconstructed Template»; a.button.product_type_simple «Add to cart: “Mutualism”» ← закрывает button.ssb-fab «Questions? Ask here»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/039.jpg](shots/039.jpg)

### 9. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/041.jpg](shots/041.jpg)

### 10. «Add to queue» нельзя нажать — сверху лежит другой элемент 🆕
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Add to queue»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в input#top-q
- **Скриншот:** [shots/051.jpg](shots/051.jpg)

### 11. Картинка не загружается
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/zWJqFa0f9UA/hqdefault.jpg
- **Скриншот:** [shots/052.jpg](shots/052.jpg)

### 12. Кнопка или ссылка без названия (только значок) 🆕
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (компьютер) 2) Где: button; a; a; a; a.ssp-cover
- **Ожидалось:** aria-label="Play", "Add to cart" и т.п.
- **На деле:** Почему важно: Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.
- **Скриншот:** [shots/054.jpg](shots/054.jpg)

### 13. Товар за $0 / €0 виден покупателям (не бесплатный пакет) 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер)
- **Ожидалось:** Скрыть из каталога.
- **На деле:** Текст: «$0 listing fees». Почему важно: Обычно это технический товар — выглядит как ошибка магазина.

### 14. В меню подсвечены сразу несколько пунктов: Головна и Послуги
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Головна, Послуги

### 15. Меню на UA ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на UA
- **На деле:** 19 пунктов: «Головна» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Послуги» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Відгуки» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 16. В меню подсвечены сразу несколько пунктов: Start и Leistungen
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Start, Leistungen

### 17. Меню на DE ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на DE
- **На деле:** 19 пунктов: «Start» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Leistungen» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Bewertungen» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 18. В меню подсвечены сразу несколько пунктов: Accueil и Services
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Accueil, Services

### 19. Меню на FR ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на FR
- **На деле:** 19 пунктов: «Accueil» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Avis» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 20. В меню подсвечены сразу несколько пунктов: Início и Serviços
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Início, Serviços

### 21. Меню на PT ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на PT
- **На деле:** 19 пунктов: «Início» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Serviços» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Avaliações» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 22. В меню подсвечены сразу несколько пунктов: Home и Services
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Home, Services

### 23. Меню на ES ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на ES
- **На деле:** 19 пунктов: «Home» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Reviews» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 24. В меню подсвечены сразу несколько пунктов: Home и Services
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Home, Services

### 25. Меню на IT ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на IT
- **На деле:** 19 пунктов: «Home» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Reviews» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 26. Главная UA помечена как язык «en-US»
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «UA Українська»
- **Ожидалось:** uk
- **На деле:** en-US

### 27. Главная DE помечена как язык «en-US»
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «DE Deutsch»
- **Ожидалось:** de
- **На деле:** en-US

### 28. Главная FR помечена как язык «en-US»
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «FR Français»
- **Ожидалось:** fr
- **На деле:** en-US

### 29. Главная PT помечена как язык «en-US»
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «PT Português»
- **Ожидалось:** pt
- **На деле:** en-US

### 30. Главная ES помечена как язык «en-US»
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «ES Español»
- **Ожидалось:** es
- **На деле:** en-US

### 31. Главная IT помечена как язык «en-US»
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «IT Italiano»
- **Ожидалось:** it
- **На деле:** en-US

### 32. На главной (телефон) нельзя выбрать язык — в меню нет списка языков 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Нажать кнопку меню
- **Ожидалось:** Список языков: EN, UA, DE, FR, PT, ES, IT
- **На деле:** Списка языков нет

### 33. Поиск по слову «techno» ничего не находит
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть главную 2) Ввести «techno» в поиск 3) Нажать Enter
- **Ожидалось:** Список товаров
- **На деле:** Открылось https://solid-sound.net/?post_type=product&s=techno, товаров: 0

### 34. Битая ссылка: /es/product/infinity/ (код 404) 🆕
- **Где:** https://solid-sound.net/product/infinity/
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ссылку на /es/product/infinity/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /product/infinity/, /es/

### 35. Битая ссылка: /it/product/infinity/ (код 404) 🆕
- **Где:** https://solid-sound.net/product/infinity/
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ссылку на /it/product/infinity/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /product/infinity/, /it/

### 36. Битая ссылка: /es/product/pitch/ (код 404) 🆕
- **Где:** https://solid-sound.net/es/
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Нажать ссылку на /es/product/pitch/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /es/, /it/

## 🟡 Мелочь (87)

### 37. Скрипты в <head> без defer/async — тормозят показ страницы 🆕
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/ и ещё 8 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/016.jpg](shots/016.jpg)

### 38. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/faq/ и ещё 1 (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/026.jpg](shots/026.jpg)

### 39. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/037.jpg](shots/037.jpg)

### 40. Одинаковые id у разных элементов 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/038.jpg](shots/038.jpg)

### 41. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/036.jpg](shots/036.jpg)

### 42. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LENGTH:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/046.jpg](shots/046.jpg)

### 43. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/048.jpg](shots/048.jpg)

### 44. Ссылка «#account» ведёт в никуда (нет такого раздела на странице)
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ссылку #account
- **Ожидалось:** Прокрутка к разделу
- **На деле:** Раздела с таким id нет
- **Скриншот:** [shots/001.jpg](shots/001.jpg)

### 45. Кнопка «Writing a track» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Writing a track»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/002.jpg](shots/002.jpg)

### 46. Кнопка «Finishing a track» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Finishing a track»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/003.jpg](shots/003.jpg)

### 47. Кнопка «Releasing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Releasing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/004.jpg](shots/004.jpg)

### 48. Кнопка «DJ set» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «DJ set»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/005.jpg](shots/005.jpg)

### 49. Кнопка «Video & ads» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Video & ads»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/006.jpg](shots/006.jpg)

### 50. Кнопка «Just starting» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Just starting»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/007.jpg](shots/007.jpg)

### 51. Кнопка «More» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «More»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/008.jpg](shots/008.jpg)

### 52. Кнопка «Get it with Google» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Get it with Google»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/009.jpg](shots/009.jpg)

### 53. Нет главного заголовка H1 или их несколько 🆕
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/012.jpg](shots/012.jpg)

### 54. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 🆕
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.
- **Скриншот:** [shots/013.jpg](shots/013.jpg)

### 55. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: input.inp [Your best email]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/015.jpg](shots/015.jpg)

### 56. Ссылка-пустышка: href="#" или пусто, ничего не происходит 🆕
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/017.jpg](shots/017.jpg)

### 57. Кнопки/ссылки слишком маленькие для пальца 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 41×21; input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/025.jpg](shots/025.jpg)

### 58. Кнопка «Browse products» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Найти кнопку «Browse products»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/032.jpg](shots/032.jpg)

### 59. Нет описания страницы (meta description) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.
- **Скриншот:** [shots/035.jpg](shots/035.jpg)

### 60. Страница /all-products не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 61. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSIC» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/047.jpg](shots/047.jpg)

### 62. Главная картинка первого экрана грузится «лениво» (loading=lazy) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: 0621-%D0%9E%D0%B1%D0%BB%D0%BE%D0%B6%D0%BA%D0%B0-e17555510432
- **Ожидалось:** Для первого экрана — без lazy, с fetchpriority=high.
- **На деле:** Почему важно: Самая важная картинка появляется последней.
- **Скриншот:** [shots/049.jpg](shots/049.jpg)

### 63. Кнопка «BUY» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Найти кнопку «BUY»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 54px)
- **Скриншот:** [shots/053.jpg](shots/053.jpg)

### 64. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (компьютер) 2) Где: input [Your email]; textarea [Artist name, label / release p]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/055.jpg](shots/055.jpg)

### 65. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (компьютер) 2) Где: span.onsale «SALE!» 2.4:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/056.jpg](shots/056.jpg)

### 66. Кнопки/ссылки слишком маленькие для пальца 🆕
- **Где:** https://solid-sound.net/product/infinity/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (телефон) 2) Где: a «Home» 36×16; a «Templates» 63×16; a «Refund policy» 84×16; a «Questions?» 68×16
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/059.jpg](shots/059.jpg)

### 67. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 🆕
- **Где:** https://solid-sound.net/en/wiki/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.
- **Скриншот:** [shots/060.jpg](shots/060.jpg)

### 68. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 🆕
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.

### 69. Страница /free не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 70. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 🆕
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.

### 71. Страница /services-prices не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 72. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 🆕
- **Где:** https://solid-sound.net/en/faq/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.

### 73. Страница /faq не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 74. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.

### 75. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер) 2) Где: input.wcfm-text.wcfm_email_verified_input [Verification Code]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 76. Одинаковые id у разных элементов 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер) 2) Где: terms
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.

### 77. Поле e-mail/телефона без нужного типа — неудобная клавиатура 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер) 2) Где: input#user_email.wcfm-text.wcfm_ele; input.wcfm-text.wcfm_email_verified_input [Verification Code]
- **Ожидалось:** type="email" / type="tel" + autocomplete.
- **На деле:** Почему важно: Нет @ на клавиатуре, нет автозаполнения — бросают форму.

### 78. Страница /sell не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 79. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/en/sell/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (телефон) 2) Где: input#user_email.wcfm-text.wcfm_ele 15px; input.wcfm-text.wcfm_email_verified_input [Verification Code] 15px; input#first_name.wcfm-text.wcfm_ele 15px; input#last_name.wcfm-text.wcfm_ele 15px; input#store_name.wcfm-text.wcfm_ele [e.g. Grenør] 15px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 80. Кнопка «Дивитися товари» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Найти кнопку «Дивитися товари»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 81. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (компьютер) 2) Где: select#us_grid_order_1 «Нові Хіти продажу Популярні Найдорожчі ▲»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 82. Меню на UA (компьютер) частично не переведено
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Products, All products, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 83. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «ЖАНР:» 4.3:1; span.w-text-value «ТРИВАЛІСТЬ:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 84. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «МУЗИКА» 8px; span.ssh-tag.ssh-tag-templates «ШАБЛОН» 8px; span.ssh-tag.ssh-tag-presets «ПРЕСЕТИ» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 85. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (телефон) 2) Где: select#us_grid_order_1 «Нові Хіти продажу Популярні Найдорожчі ▲» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 86. Меню на UA (телефон) частично не переведено
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 87. Кнопка «Produkte ansehen» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ 2) Найти кнопку «Produkte ansehen»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 88. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (компьютер) 2) Где: select#us_grid_order_1 «Neueste Bestseller Beliebt Höchster Prei»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 89. Меню на DE (компьютер) частично не переведено
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 90. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LÄNGE:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 91. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSIK» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 92. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (телефон) 2) Где: select#us_grid_order_1 «Neueste Bestseller Beliebt Höchster Prei» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 93. Меню на DE (телефон) частично не переведено
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 94. Кнопка «Voir les produits» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Найти кнопку «Voir les produits»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 95. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (компьютер) 2) Где: select#us_grid_order_1 «Nouveautés Meilleures ventes Populaires »
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 96. Меню на FR (компьютер) частично не переведено
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Products, All products, Templates, Presets, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 97. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «GENRE :» 4.3:1; span.w-text-value «DURÉE :» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 98. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSIQUE» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 99. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (телефон) 2) Где: select#us_grid_order_1 «Nouveautés Meilleures ventes Populaires » 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 100. Меню на FR (телефон) частично не переведено
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 101. Кнопка «Ver produtos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Найти кнопку «Ver produtos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 102. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (компьютер) 2) Где: select#us_grid_order_1 «Mais recentes Mais vendidos Populares Pr»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 103. Меню на PT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 104. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «GÉNERO:» 4.3:1; span.w-text-value «DURAÇÃO:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 105. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MÚSICA» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 106. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (телефон) 2) Где: select#us_grid_order_1 «Mais recentes Mais vendidos Populares Pr» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 107. Меню на PT (телефон) частично не переведено
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 108. Кнопка «Ver productos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Найти кнопку «Ver productos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 109. Меню на ES (компьютер) частично не переведено
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 110. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MÚSICA» 8px; span.ssh-tag.ssh-tag-templates «PLANTILLA» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 111. Меню на ES (телефон) частично не переведено
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

### 112. Кнопка «Vedi i prodotti» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Найти кнопку «Vedi i prodotti»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 113. Меню на IT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 114. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSICA» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 115. Меню на IT (телефон) частично не переведено
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

### 116. Ссылка /product-genre/cinematic/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/cinematic/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-genre=cinematic

### 117. Ссылка /product-genre/melodic-techno/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/melodic-techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-genre=melodic-techno

### 118. Ссылка /external-plugin/serum/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/serum/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_external-plugin=serum

### 119. Ссылка /product-genre/techno/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-genre=techno

### 120. Ссылка /product-version/ableton-live-11/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-11/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-version=ableton-live-11

### 121. Ссылка /external-plugin/no-external-plugins/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/no-external-plugins/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_external-plugin=no-external-plugins

### 122. Ссылка /product-version/ableton-live-10/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-10/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-version=ableton-live-10

### 123. Ссылка /product-category/sound/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/product/infinity/
- **Шаги:** 1) Нажать ссылку на /product-category/sound/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

## ⚪ Проверить глазами (41)

### 124. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/ и ещё 2 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/011.jpg](shots/011.jpg)

### 125. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/ и ещё 2 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/033.jpg](shots/033.jpg)

### 126. Круглая кнопка-значок «Add to queue» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/030.jpg](shots/030.jpg)

### 127. Круглая кнопка-значок «Video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/031.jpg](shots/031.jpg)

### 128. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/034.jpg](shots/034.jpg)

### 129. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/028.jpg](shots/028.jpg)

### 130. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/029.jpg](shots/029.jpg)

### 131. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/010.jpg](shots/010.jpg)

### 132. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/021.jpg](shots/021.jpg)

### 133. Кнопка «For you» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «For you»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/022.jpg](shots/022.jpg)

### 134. Круглая кнопка-значок «DJ set» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 999px, размер 44px)
- **Скриншот:** [shots/023.jpg](shots/023.jpg)

### 135. Круглая кнопка-значок «More» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 999px, размер 44px)
- **Скриншот:** [shots/024.jpg](shots/024.jpg)

### 136. Слишком много элементов на странице (>3000) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3267. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/040.jpg](shots/040.jpg)

### 137. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/042.jpg](shots/042.jpg)

### 138. Кнопка «Previous» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Previous»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/043.jpg](shots/043.jpg)

### 139. Кнопка «Next» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Next»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/044.jpg](shots/044.jpg)

### 140. Кнопка «Add to queue» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Add to queue»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/045.jpg](shots/045.jpg)

### 141. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/050.jpg](shots/050.jpg)

### 142. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/058.jpg](shots/058.jpg)

### 143. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/wiki/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит

### 144. Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px

### 145. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px

### 146. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит

### 147. Кнопка «Services» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «Services»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит

### 148. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит

### 149. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит

### 150. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит

### 151. Круглая кнопка-значок «Назад» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 152. Круглая кнопка-значок «Далі» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 153. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 154. Круглая кнопка-значок «Zurück» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 155. Круглая кнопка-значок «Weiter» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 156. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 157. Круглая кнопка-значок «Précédent» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 158. Круглая кнопка-значок «Suivant» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 159. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 160. Круглая кнопка-значок «Anterior» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 161. Круглая кнопка-значок «Próximo» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 162. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 163. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 164. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

## ✅ Исправлено с прошлой проверки (16)

- Кнопка ▶ «Play video» не запускает звук
- Кнопку ▶ «Play» невозможно нажать (её что-то закрывает)
- Кнопку ▶ «Add to queue» невозможно нажать (её что-то закрывает)
- Кнопку ▶ «Play video» невозможно нажать (её что-то закрывает)
- Меню на UA (компьютер) отличается от английского: пункты ведут не туда
- Меню на DE (компьютер) отличается от английского: пункты ведут не туда
- Меню на FR (компьютер) отличается от английского: пункты ведут не туда
- Меню на PT (компьютер) отличается от английского: пункты ведут не туда
- Меню на ES (компьютер) отличается от английского: пункты ведут не туда
- Меню на IT (компьютер) отличается от английского: пункты ведут не туда
- В списке языков нет UA (телефон)
- В списке языков нет DE (телефон)
- В списке языков нет FR (телефон)
- В списке языков нет PT (телефон)
- В списке языков нет ES (телефон)
- В списке языков нет IT (телефон)

## Не удалось проверить (2)

- Вход как клиент — нет тестового логина в секретах
- Вход как админ — нет тестового логина в секретах

