# Автотестировщик — отчёт 2026-11-13 06:45 UTC (быстрая проверка)

Проверено: страниц 28, кнопок 118, кнопок звука 20, ссылок 0. Время: 16 мин.
Найдено: 🔴 Критично 1 · 🟠 Важно 42 · 🟡 Мелочь 34 · ⚪ Проверить глазами 40

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🔴 Критично (1)

### 1. Главная на других языках — другая (старая) страница, а не переведённая новая
- **Где:** https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Открыть https://solid-sound.net/ua/ 3) Сравнить
- **Ожидалось:** Та же страница, только переведённая
- **На деле:** UA: совпадает с английской на 10%; DE: совпадает с английской на 10%; FR: совпадает с английской на 10%; PT: совпадает с английской на 10%; ES: совпадает с английской на 10%; IT: совпадает с английской на 10%

## 🟠 Важно (42)

### 2. После нажатия «Products» — ошибка в коде
- **Где:** https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/faq/, https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать «Products»
- **Ожидалось:** Без ошибок
- **На деле:** mod_pagespeed_CpaDOhQqFK is not defined
- **Скриншот:** [shots/039.jpg](shots/039.jpg)

### 3. Кнопка «Play Time Goes Fast Documentary Piano preview» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Time Goes Fast Documentary Piano preview»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/012.jpg](shots/012.jpg)

### 4. Кнопка «Play Pitch preview» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Pitch preview»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/013.jpg](shots/013.jpg)

### 5. Кнопка «Play Mutualism preview» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Mutualism preview»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/014.jpg](shots/014.jpg)

### 6. «For you» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «For you»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в a
- **Скриншот:** [shots/019.jpg](shots/019.jpg)

### 7. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/027.jpg](shots/027.jpg)

### 8. Картинка не загружается
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/zWJqFa0f9UA/hqdefault.jpg
- **Скриншот:** [shots/033.jpg](shots/033.jpg)

### 9. Кнопка ▶ «Play video» не запускает звук
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ▶ «Play video» 3) Подождать 12 секунд
- **Ожидалось:** Играет трек
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/035.jpg](shots/035.jpg)

### 10. Кнопку ▶ «Play» невозможно нажать (её что-то закрывает)
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ▶ «Play»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/036.jpg](shots/036.jpg)

### 11. Кнопку ▶ «Add to queue» невозможно нажать (её что-то закрывает)
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ▶ «Add to queue»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/037.jpg](shots/037.jpg)

### 12. Кнопку ▶ «Play video» невозможно нажать (её что-то закрывает)
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ▶ «Play video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/038.jpg](shots/038.jpg)

### 13. В меню подсвечены сразу несколько пунктов: Головна и Послуги
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Головна, Послуги
- **Скриншот:** [shots/053.jpg](shots/053.jpg)

### 14. Меню на UA ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на UA
- **На деле:** 19 пунктов: «Головна» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Послуги» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Відгуки» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 15. Меню на UA (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №5 «FAQ» ведёт на /, а в EN «FAQ» → /all-products
- **Скриншот:** [shots/054.jpg](shots/054.jpg)

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

### 18. Меню на DE (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №5 «FAQ» ведёт на /, а в EN «FAQ» → /all-products

### 19. В меню подсвечены сразу несколько пунктов: Accueil и Services
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Accueil, Services

### 20. Меню на FR ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на FR
- **На деле:** 19 пунктов: «Accueil» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Avis» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 21. Меню на FR (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №5 «FAQ» ведёт на /, а в EN «FAQ» → /all-products

### 22. В меню подсвечены сразу несколько пунктов: Início и Serviços
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Início, Serviços

### 23. Меню на PT ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на PT
- **На деле:** 19 пунктов: «Início» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Serviços» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Avaliações» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 24. Меню на PT (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №5 «FAQ» ведёт на /, а в EN «FAQ» → /all-products

### 25. В меню подсвечены сразу несколько пунктов: Home и Services
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Home, Services

### 26. Меню на ES ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на ES
- **На деле:** 19 пунктов: «Home» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Reviews» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 27. Меню на ES (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №5 «FAQ» ведёт на /, а в EN «FAQ» → /all-products

### 28. В меню подсвечены сразу несколько пунктов: Home и Services
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Home, Services

### 29. Меню на IT ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на IT
- **На деле:** 19 пунктов: «Home» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Reviews» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 30. Меню на IT (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №5 «FAQ» ведёт на /, а в EN «FAQ» → /all-products

### 31. Главная UA помечена как язык «en-US»
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «UA Українська»
- **Ожидалось:** uk
- **На деле:** en-US

### 32. Главная DE помечена как язык «en-US»
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «DE Deutsch»
- **Ожидалось:** de
- **На деле:** en-US

### 33. Главная FR помечена как язык «en-US»
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «FR Français»
- **Ожидалось:** fr
- **На деле:** en-US

### 34. Главная PT помечена как язык «en-US»
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «PT Português»
- **Ожидалось:** pt
- **На деле:** en-US

### 35. Главная ES помечена как язык «en-US»
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «ES Español»
- **Ожидалось:** es
- **На деле:** en-US

### 36. Главная IT помечена как язык «en-US»
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть главную на английском 2) Нажать «EN» вверху справа 3) Выбрать «IT Italiano»
- **Ожидалось:** it
- **На деле:** en-US

### 37. В списке языков нет UA (телефон)
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть UA
- **На деле:** Нет

### 38. В списке языков нет DE (телефон)
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть DE
- **На деле:** Нет

### 39. В списке языков нет FR (телефон)
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть FR
- **На деле:** Нет

### 40. В списке языков нет PT (телефон)
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть PT
- **На деле:** Нет

### 41. В списке языков нет ES (телефон)
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть ES
- **На деле:** Нет

### 42. В списке языков нет IT (телефон)
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть IT
- **На деле:** Нет

### 43. Поиск по слову «techno» ничего не находит
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть главную 2) Ввести «techno» в поиск 3) Нажать Enter
- **Ожидалось:** Список товаров
- **На деле:** Открылось https://solid-sound.net/?post_type=product&s=techno, товаров: 0

## 🟡 Мелочь (34)

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

### 53. Кнопка «Browse products» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Найти кнопку «Browse products»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/024.jpg](shots/024.jpg)

### 54. Страница /all-products не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 55. Кнопка «BUY» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Найти кнопку «BUY»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 54px)
- **Скриншот:** [shots/034.jpg](shots/034.jpg)

### 56. Страница /free не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 57. Страница /services-prices не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 58. Страница /faq не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 59. Страница /sell не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 60. Кнопка «Дивитися товари» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Найти кнопку «Дивитися товари»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/051.jpg](shots/051.jpg)

### 61. Меню на UA (компьютер) частично не переведено
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Products, All products, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/055.jpg](shots/055.jpg)

### 62. Меню на UA (телефон) частично не переведено
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/056.jpg](shots/056.jpg)

### 63. Кнопка «Produkte ansehen» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ 2) Найти кнопку «Produkte ansehen»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/059.jpg](shots/059.jpg)

### 64. Меню на DE (компьютер) частично не переведено
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 65. Меню на DE (телефон) частично не переведено
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 66. Кнопка «Voir les produits» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Найти кнопку «Voir les produits»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 67. Меню на FR (компьютер) частично не переведено
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Products, All products, Templates, Presets, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 68. Меню на FR (телефон) частично не переведено
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 69. Кнопка «Ver produtos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Найти кнопку «Ver produtos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 70. Меню на PT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 71. Меню на PT (телефон) частично не переведено
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 72. Кнопка «Ver productos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Найти кнопку «Ver productos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 73. Меню на ES (компьютер) частично не переведено
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 74. Меню на ES (телефон) частично не переведено
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

### 75. Кнопка «Vedi i prodotti» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Найти кнопку «Vedi i prodotti»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 76. Меню на IT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 77. Меню на IT (телефон) частично не переведено
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

## ⚪ Проверить глазами (40)

### 78. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/ и ещё 2 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/011.jpg](shots/011.jpg)

### 79. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/ и ещё 2 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/025.jpg](shots/025.jpg)

### 80. Круглая кнопка-значок «Add to queue» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/022.jpg](shots/022.jpg)

### 81. Круглая кнопка-значок «Video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/023.jpg](shots/023.jpg)

### 82. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/026.jpg](shots/026.jpg)

### 83. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/020.jpg](shots/020.jpg)

### 84. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/021.jpg](shots/021.jpg)

### 85. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/010.jpg](shots/010.jpg)

### 86. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/015.jpg](shots/015.jpg)

### 87. Кнопка «For you» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «For you»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/016.jpg](shots/016.jpg)

### 88. Круглая кнопка-значок «DJ set» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 999px, размер 44px)
- **Скриншот:** [shots/017.jpg](shots/017.jpg)

### 89. Круглая кнопка-значок «More» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 999px, размер 44px)
- **Скриншот:** [shots/018.jpg](shots/018.jpg)

### 90. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/028.jpg](shots/028.jpg)

### 91. Кнопка «Previous» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Previous»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/029.jpg](shots/029.jpg)

### 92. Кнопка «Next» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Next»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/030.jpg](shots/030.jpg)

### 93. Кнопка «Add to queue» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Add to queue»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/031.jpg](shots/031.jpg)

### 94. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/032.jpg](shots/032.jpg)

### 95. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/040.jpg](shots/040.jpg)

### 96. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/wiki/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/041.jpg](shots/041.jpg)

### 97. Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/042.jpg](shots/042.jpg)

### 98. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/043.jpg](shots/043.jpg)

### 99. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/044.jpg](shots/044.jpg)

### 100. Кнопка «Services» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «Services»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/045.jpg](shots/045.jpg)

### 101. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/046.jpg](shots/046.jpg)

### 102. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/047.jpg](shots/047.jpg)

### 103. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/048.jpg](shots/048.jpg)

### 104. Круглая кнопка-значок «Назад» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/049.jpg](shots/049.jpg)

### 105. Круглая кнопка-значок «Далі» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/050.jpg](shots/050.jpg)

### 106. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/052.jpg](shots/052.jpg)

### 107. Круглая кнопка-значок «Zurück» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/057.jpg](shots/057.jpg)

### 108. Круглая кнопка-значок «Weiter» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/058.jpg](shots/058.jpg)

### 109. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/060.jpg](shots/060.jpg)

### 110. Круглая кнопка-значок «Précédent» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 111. Круглая кнопка-значок «Suivant» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 112. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 113. Круглая кнопка-значок «Anterior» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 114. Круглая кнопка-значок «Próximo» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 115. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 116. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 117. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

## Не удалось проверить (1)

- Бот остановился с ошибкой: page.$: Execution context was destroyed, most likely because of a navigation     at scCart (/home/runner/work/solid-sound-site/solid-sound-site/tools/qa/qa.js:502:54)     at /home/runner/work/solid-sound-site/solid-sound-site/async /home/runner/work/solid-sound-site/solid-sound-site/tools/qa/qa.js:664:5

