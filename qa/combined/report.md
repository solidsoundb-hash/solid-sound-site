# Автотестировщик — общий отчёт (две проверки вместе)

- Старая проверка: 2026-11-13 06:45 UTC (quick), без базы знаний — 117 пунктов.
- Новая проверка: 2026-11-13 07:55 UTC (quick), с базой знаний общих багов — 164 пунктов.
- Вместе без повторов: **179** · 🔴 Критично 1 · 🟠 Важно 50 · 🟡 Мелочь 87 · ⚪ Проверить глазами 41 · из базы знаний: 49 📚
- Пометки: 📚 — общий баг из базы знаний (`docs/qa-knowledge.md`); ⏳ — был только в старой проверке (возможно, уже исправлен или страница не проверялась заново).

## 🔴 Критично (1)

### 1. Главная на других языках — другая (старая) страница, а не переведённая новая
- **Где:** https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Открыть https://solid-sound.net/ua/ 3) Сравнить
- **Ожидалось:** Та же страница, только переведённая
- **На деле:** UA: совпадает с английской на 10%; DE: совпадает с английской на 10%; FR: совпадает с английской на 10%; PT: совпадает с английской на 10%; ES: совпадает с английской на 10%; IT: совпадает с английской на 10%

## 🟠 Важно (50)

### 2. После нажатия «Products» — ошибка в коде
- **Где:** https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/faq/, https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать «Products»
- **Ожидалось:** Без ошибок
- **На деле:** mod_pagespeed_CpaDOhQqFK is not defined
- **Скриншот:** [../kb-scan/shots/057.jpg](../kb-scan/shots/057.jpg)

### 3. Кнопка «Play Time Goes Fast Documentary Piano preview» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Time Goes Fast Documentary Piano preview»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [../kb-scan/shots/018.jpg](../kb-scan/shots/018.jpg)

### 4. Кнопка «Play Pitch preview» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Pitch preview»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [../kb-scan/shots/019.jpg](../kb-scan/shots/019.jpg)

### 5. Кнопка «Play Mutualism preview» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Mutualism preview»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [../kb-scan/shots/020.jpg](../kb-scan/shots/020.jpg)

### 6. «For you» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «For you»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в a
- **Скриншот:** [../kb-scan/shots/027.jpg](../kb-scan/shots/027.jpg)

### 7. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [../kb-scan/shots/041.jpg](../kb-scan/shots/041.jpg)

### 8. Картинка не загружается
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/zWJqFa0f9UA/hqdefault.jpg
- **Скриншот:** [../kb-scan/shots/052.jpg](../kb-scan/shots/052.jpg)

### 9. Кнопка ▶ «Play video» не запускает звук ⏳
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ▶ «Play video» 3) Подождать 12 секунд
- **Ожидалось:** Играет трек
- **На деле:** Ничего не происходит
- **Скриншот:** [../latest/shots/035.jpg](../latest/shots/035.jpg)

### 10. Кнопку ▶ «Play» невозможно нажать (её что-то закрывает) ⏳
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ▶ «Play»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [../latest/shots/036.jpg](../latest/shots/036.jpg)

### 11. Кнопку ▶ «Add to queue» невозможно нажать (её что-то закрывает) ⏳
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ▶ «Add to queue»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [../latest/shots/037.jpg](../latest/shots/037.jpg)

### 12. Кнопку ▶ «Play video» невозможно нажать (её что-то закрывает) ⏳
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ▶ «Play video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [../latest/shots/038.jpg](../latest/shots/038.jpg)

### 13. В меню подсвечены сразу несколько пунктов: Головна и Послуги
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Посмотреть на верхнее меню
- **Ожидалось:** Подсвечен один пункт — раздел, где вы находитесь
- **На деле:** Подсвечены: Головна, Послуги
- **Скриншот:** [../latest/shots/053.jpg](../latest/shots/053.jpg)

### 14. Меню на UA ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на UA
- **На деле:** 19 пунктов: «Головна» → /en/, «Products» → /en/all-products/, «All products» → /en/all-products/, «Послуги» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Examples» → /en/, «Відгуки» → /en/, «How it works» → /en/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/ …

### 15. Меню на UA (компьютер) отличается от английского: пункты ведут не туда ⏳
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №5 «FAQ» ведёт на /, а в EN «FAQ» → /all-products
- **Скриншот:** [../latest/shots/054.jpg](../latest/shots/054.jpg)

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

### 18. Меню на DE (компьютер) отличается от английского: пункты ведут не туда ⏳
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

### 21. Меню на FR (компьютер) отличается от английского: пункты ведут не туда ⏳
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

### 24. Меню на PT (компьютер) отличается от английского: пункты ведут не туда ⏳
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

### 27. Меню на ES (компьютер) отличается от английского: пункты ведут не туда ⏳
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

### 30. Меню на IT (компьютер) отличается от английского: пункты ведут не туда ⏳
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

### 37. В списке языков нет UA (телефон) ⏳
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть UA
- **На деле:** Нет

### 38. В списке языков нет DE (телефон) ⏳
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть DE
- **На деле:** Нет

### 39. В списке языков нет FR (телефон) ⏳
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть FR
- **На деле:** Нет

### 40. В списке языков нет PT (телефон) ⏳
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть PT
- **На деле:** Нет

### 41. В списке языков нет ES (телефон) ⏳
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть ES
- **На деле:** Нет

### 42. В списке языков нет IT (телефон) ⏳
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Открыть список языков
- **Ожидалось:** Есть IT
- **На деле:** Нет

### 43. Поиск по слову «techno» ничего не находит
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть главную 2) Ввести «techno» в поиск 3) Нажать Enter
- **Ожидалось:** Список товаров
- **На деле:** Открылось https://solid-sound.net/?post_type=product&s=techno, товаров: 0

### 44. «Add to queue» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Add to queue»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в input#top-q
- **Скриншот:** [../kb-scan/shots/051.jpg](../kb-scan/shots/051.jpg)

### 45. На главной (телефон) нельзя выбрать язык — в меню нет списка языков
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Нажать кнопку меню
- **Ожидалось:** Список языков: EN, UA, DE, FR, PT, ES, IT
- **На деле:** Списка языков нет

### 46. Битая ссылка: /es/product/infinity/ (код 404)
- **Где:** https://solid-sound.net/product/infinity/
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ссылку на /es/product/infinity/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /product/infinity/, /es/

### 47. Битая ссылка: /it/product/infinity/ (код 404)
- **Где:** https://solid-sound.net/product/infinity/
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать ссылку на /it/product/infinity/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /product/infinity/, /it/

### 48. Битая ссылка: /es/product/pitch/ (код 404)
- **Где:** https://solid-sound.net/es/
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Нажать ссылку на /es/product/pitch/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /es/, /it/

### 49. Кнопка или ссылка без названия (только значок) 📚
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/faq/ и ещё 7 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover
- **Ожидалось:** aria-label="Play", "Add to cart" и т.п.
- **На деле:** Почему важно: Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.
- **Скриншот:** [../kb-scan/shots/014.jpg](../kb-scan/shots/014.jpg)

### 50. Важную кнопку закрывает что-то сверху (чат, плеер, баннер) 📚
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div.w-filter-item.ssf-fmt «Templates Presets Reconstructed Template»; a.button.product_type_simple «Add to cart: “Mutualism”» ← закрывает button.ssb-fab «Questions? Ask here»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [../kb-scan/shots/039.jpg](../kb-scan/shots/039.jpg)

### 51. Кнопка или ссылка без названия (только значок) 📚
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (компьютер) 2) Где: button; a; a; a; a.ssp-cover
- **Ожидалось:** aria-label="Play", "Add to cart" и т.п.
- **На деле:** Почему важно: Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.
- **Скриншот:** [../kb-scan/shots/054.jpg](../kb-scan/shots/054.jpg)

## 🟡 Мелочь (87)

### 52. Ссылка «#account» ведёт в никуда (нет такого раздела на странице)
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ссылку #account
- **Ожидалось:** Прокрутка к разделу
- **На деле:** Раздела с таким id нет
- **Скриншот:** [../kb-scan/shots/001.jpg](../kb-scan/shots/001.jpg)

### 53. Кнопка «Writing a track» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Writing a track»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../kb-scan/shots/002.jpg](../kb-scan/shots/002.jpg)

### 54. Кнопка «Finishing a track» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Finishing a track»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../kb-scan/shots/003.jpg](../kb-scan/shots/003.jpg)

### 55. Кнопка «Releasing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Releasing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../kb-scan/shots/004.jpg](../kb-scan/shots/004.jpg)

### 56. Кнопка «DJ set» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «DJ set»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../kb-scan/shots/005.jpg](../kb-scan/shots/005.jpg)

### 57. Кнопка «Video & ads» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Video & ads»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../kb-scan/shots/006.jpg](../kb-scan/shots/006.jpg)

### 58. Кнопка «Just starting» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Just starting»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../kb-scan/shots/007.jpg](../kb-scan/shots/007.jpg)

### 59. Кнопка «More» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «More»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../kb-scan/shots/008.jpg](../kb-scan/shots/008.jpg)

### 60. Кнопка «Get it with Google» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Найти кнопку «Get it with Google»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [../kb-scan/shots/009.jpg](../kb-scan/shots/009.jpg)

### 61. Кнопка «Browse products» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Найти кнопку «Browse products»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../kb-scan/shots/032.jpg](../kb-scan/shots/032.jpg)

### 62. Страница /all-products не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 63. Кнопка «BUY» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Найти кнопку «BUY»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 54px)
- **Скриншот:** [../kb-scan/shots/053.jpg](../kb-scan/shots/053.jpg)

### 64. Страница /free не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 65. Страница /services-prices не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 66. Страница /faq не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 67. Страница /sell не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 68. Кнопка «Дивитися товари» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Найти кнопку «Дивитися товари»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../latest/shots/051.jpg](../latest/shots/051.jpg)

### 69. Меню на UA (компьютер) частично не переведено
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Products, All products, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [../latest/shots/055.jpg](../latest/shots/055.jpg)

### 70. Меню на UA (телефон) частично не переведено
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [../latest/shots/056.jpg](../latest/shots/056.jpg)

### 71. Кнопка «Produkte ansehen» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ 2) Найти кнопку «Produkte ansehen»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [../latest/shots/059.jpg](../latest/shots/059.jpg)

### 72. Меню на DE (компьютер) частично не переведено
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 73. Меню на DE (телефон) частично не переведено
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 74. Кнопка «Voir les produits» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Найти кнопку «Voir les produits»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 75. Меню на FR (компьютер) частично не переведено
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Products, All products, Templates, Presets, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 76. Меню на FR (телефон) частично не переведено
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 77. Кнопка «Ver produtos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Найти кнопку «Ver produtos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 78. Меню на PT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 79. Меню на PT (телефон) частично не переведено
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 80. Кнопка «Ver productos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Найти кнопку «Ver productos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 81. Меню на ES (компьютер) частично не переведено
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 82. Меню на ES (телефон) частично не переведено
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

### 83. Кнопка «Vedi i prodotti» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Найти кнопку «Vedi i prodotti»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 84. Меню на IT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 85. Меню на IT (телефон) частично не переведено
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

### 86. Ссылка /product-genre/cinematic/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/cinematic/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-genre=cinematic

### 87. Ссылка /product-genre/melodic-techno/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/melodic-techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-genre=melodic-techno

### 88. Ссылка /external-plugin/serum/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/serum/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_external-plugin=serum

### 89. Ссылка /product-genre/techno/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-genre=techno

### 90. Ссылка /product-version/ableton-live-11/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-11/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-version=ableton-live-11

### 91. Ссылка /external-plugin/no-external-plugins/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/no-external-plugins/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_external-plugin=no-external-plugins

### 92. Ссылка /product-version/ableton-live-10/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-10/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/?filter_product-version=ableton-live-10

### 93. Ссылка /product-category/sound/ выкидывает на главную
- **Где:** https://solid-sound.net/product/infinity/
- **Шаги:** 1) Нажать ссылку на /product-category/sound/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /en/

### 94. Скрипты в <head> без defer/async — тормозят показ страницы 📚
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/ и ещё 8 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [../kb-scan/shots/016.jpg](../kb-scan/shots/016.jpg)

### 95. Слишком мелкий текст на телефоне (меньше 12 px) 📚
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/faq/ и ещё 1 (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [../kb-scan/shots/026.jpg](../kb-scan/shots/026.jpg)

### 96. Текст плохо читается: слишком бледный на фоне 📚
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [../kb-scan/shots/037.jpg](../kb-scan/shots/037.jpg)

### 97. Одинаковые id у разных элементов 📚
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [../kb-scan/shots/038.jpg](../kb-scan/shots/038.jpg)

### 98. Поле ввода без подписи 📚
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [../kb-scan/shots/036.jpg](../kb-scan/shots/036.jpg)

### 99. Текст плохо читается: слишком бледный на фоне 📚
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LENGTH:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [../kb-scan/shots/046.jpg](../kb-scan/shots/046.jpg)

### 100. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 📚
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [../kb-scan/shots/048.jpg](../kb-scan/shots/048.jpg)

### 101. Нет главного заголовка H1 или их несколько 📚
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [../kb-scan/shots/012.jpg](../kb-scan/shots/012.jpg)

### 102. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 📚
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.
- **Скриншот:** [../kb-scan/shots/013.jpg](../kb-scan/shots/013.jpg)

### 103. Поле ввода без подписи 📚
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: input.inp [Your best email]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [../kb-scan/shots/015.jpg](../kb-scan/shots/015.jpg)

### 104. Ссылка-пустышка: href="#" или пусто, ничего не происходит 📚
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [../kb-scan/shots/017.jpg](../kb-scan/shots/017.jpg)

### 105. Кнопки/ссылки слишком маленькие для пальца 📚
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 41×21; input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [../kb-scan/shots/025.jpg](../kb-scan/shots/025.jpg)

### 106. Нет описания страницы (meta description) 📚
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.
- **Скриншот:** [../kb-scan/shots/035.jpg](../kb-scan/shots/035.jpg)

### 107. Слишком мелкий текст на телефоне (меньше 12 px) 📚
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSIC» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [../kb-scan/shots/047.jpg](../kb-scan/shots/047.jpg)

### 108. Главная картинка первого экрана грузится «лениво» (loading=lazy) 📚
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: 0621-%D0%9E%D0%B1%D0%BB%D0%BE%D0%B6%D0%BA%D0%B0-e17555510432
- **Ожидалось:** Для первого экрана — без lazy, с fetchpriority=high.
- **На деле:** Почему важно: Самая важная картинка появляется последней.
- **Скриншот:** [../kb-scan/shots/049.jpg](../kb-scan/shots/049.jpg)

### 109. Поле ввода без подписи 📚
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (компьютер) 2) Где: input [Your email]; textarea [Artist name, label / release p]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [../kb-scan/shots/055.jpg](../kb-scan/shots/055.jpg)

### 110. Текст плохо читается: слишком бледный на фоне 📚
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (компьютер) 2) Где: span.onsale «SALE!» 2.4:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [../kb-scan/shots/056.jpg](../kb-scan/shots/056.jpg)

### 111. Кнопки/ссылки слишком маленькие для пальца 📚
- **Где:** https://solid-sound.net/product/infinity/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (телефон) 2) Где: a «Home» 36×16; a «Templates» 63×16; a «Refund policy» 84×16; a «Questions?» 68×16
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [../kb-scan/shots/059.jpg](../kb-scan/shots/059.jpg)

### 112. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 📚
- **Где:** https://solid-sound.net/en/wiki/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.
- **Скриншот:** [../kb-scan/shots/060.jpg](../kb-scan/shots/060.jpg)

### 113. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 📚
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.

### 114. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 📚
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.

### 115. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 📚
- **Где:** https://solid-sound.net/en/faq/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.

### 116. Нет картинки/заголовка для превью в соцсетях (og:image, og:title) 📚
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер)
- **Ожидалось:** og:title + og:image 1200×630.
- **На деле:** og:title: есть, og:image: нет. Почему важно: Ссылка в Telegram/Facebook/Instagram без картинки — по ней почти не кликают.

### 117. Поле ввода без подписи 📚
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер) 2) Где: input.wcfm-text.wcfm_email_verified_input [Verification Code]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 118. Одинаковые id у разных элементов 📚
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер) 2) Где: terms
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.

### 119. Поле e-mail/телефона без нужного типа — неудобная клавиатура 📚
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер) 2) Где: input#user_email.wcfm-text.wcfm_ele; input.wcfm-text.wcfm_email_verified_input [Verification Code]
- **Ожидалось:** type="email" / type="tel" + autocomplete.
- **На деле:** Почему важно: Нет @ на клавиатуре, нет автозаполнения — бросают форму.

### 120. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 📚
- **Где:** https://solid-sound.net/en/sell/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (телефон) 2) Где: input#user_email.wcfm-text.wcfm_ele 15px; input.wcfm-text.wcfm_email_verified_input [Verification Code] 15px; input#first_name.wcfm-text.wcfm_ele 15px; input#last_name.wcfm-text.wcfm_ele 15px; input#store_name.wcfm-text.wcfm_ele [e.g. Grenør] 15px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 121. Поле ввода без подписи 📚
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (компьютер) 2) Где: select#us_grid_order_1 «Нові Хіти продажу Популярні Найдорожчі ▲»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 122. Текст плохо читается: слишком бледный на фоне 📚
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «ЖАНР:» 4.3:1; span.w-text-value «ТРИВАЛІСТЬ:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 123. Слишком мелкий текст на телефоне (меньше 12 px) 📚
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «МУЗИКА» 8px; span.ssh-tag.ssh-tag-templates «ШАБЛОН» 8px; span.ssh-tag.ssh-tag-presets «ПРЕСЕТИ» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 124. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 📚
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (телефон) 2) Где: select#us_grid_order_1 «Нові Хіти продажу Популярні Найдорожчі ▲» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 125. Поле ввода без подписи 📚
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (компьютер) 2) Где: select#us_grid_order_1 «Neueste Bestseller Beliebt Höchster Prei»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 126. Текст плохо читается: слишком бледный на фоне 📚
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LÄNGE:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 127. Слишком мелкий текст на телефоне (меньше 12 px) 📚
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSIK» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 128. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 📚
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (телефон) 2) Где: select#us_grid_order_1 «Neueste Bestseller Beliebt Höchster Prei» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 129. Поле ввода без подписи 📚
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (компьютер) 2) Где: select#us_grid_order_1 «Nouveautés Meilleures ventes Populaires »
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 130. Текст плохо читается: слишком бледный на фоне 📚
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «GENRE :» 4.3:1; span.w-text-value «DURÉE :» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 131. Слишком мелкий текст на телефоне (меньше 12 px) 📚
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSIQUE» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 132. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 📚
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (телефон) 2) Где: select#us_grid_order_1 «Nouveautés Meilleures ventes Populaires » 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 133. Поле ввода без подписи 📚
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (компьютер) 2) Где: select#us_grid_order_1 «Mais recentes Mais vendidos Populares Pr»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 134. Текст плохо читается: слишком бледный на фоне 📚
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (телефон) 2) Где: em.ssh-tier.ssh-tier-trending «TRENDING» 2.5:1; span.w-text-value «GÉNERO:» 4.3:1; span.w-text-value «DURAÇÃO:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 135. Слишком мелкий текст на телефоне (меньше 12 px) 📚
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MÚSICA» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 136. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 📚
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (телефон) 2) Где: select#us_grid_order_1 «Mais recentes Mais vendidos Populares Pr» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 137. Слишком мелкий текст на телефоне (меньше 12 px) 📚
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MÚSICA» 8px; span.ssh-tag.ssh-tag-templates «PLANTILLA» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 138. Слишком мелкий текст на телефоне (меньше 12 px) 📚
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSICA» 8px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 8px; span.ssh-tag.ssh-tag-presets «PRESETS» 8px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

## ⚪ Проверить глазами (41)

### 139. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/ и ещё 2 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [../kb-scan/shots/011.jpg](../kb-scan/shots/011.jpg)

### 140. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/ и ещё 2 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [../kb-scan/shots/033.jpg](../kb-scan/shots/033.jpg)

### 141. Круглая кнопка-значок «Add to queue» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [../kb-scan/shots/030.jpg](../kb-scan/shots/030.jpg)

### 142. Круглая кнопка-значок «Video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [../kb-scan/shots/031.jpg](../kb-scan/shots/031.jpg)

### 143. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [../kb-scan/shots/034.jpg](../kb-scan/shots/034.jpg)

### 144. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [../kb-scan/shots/028.jpg](../kb-scan/shots/028.jpg)

### 145. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [../kb-scan/shots/029.jpg](../kb-scan/shots/029.jpg)

### 146. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [../kb-scan/shots/010.jpg](../kb-scan/shots/010.jpg)

### 147. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../kb-scan/shots/021.jpg](../kb-scan/shots/021.jpg)

### 148. Кнопка «For you» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «For you»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../kb-scan/shots/022.jpg](../kb-scan/shots/022.jpg)

### 149. Круглая кнопка-значок «DJ set» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 999px, размер 44px)
- **Скриншот:** [../kb-scan/shots/023.jpg](../kb-scan/shots/023.jpg)

### 150. Круглая кнопка-значок «More» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 999px, размер 44px)
- **Скриншот:** [../kb-scan/shots/024.jpg](../kb-scan/shots/024.jpg)

### 151. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../kb-scan/shots/042.jpg](../kb-scan/shots/042.jpg)

### 152. Кнопка «Previous» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Previous»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../kb-scan/shots/043.jpg](../kb-scan/shots/043.jpg)

### 153. Кнопка «Next» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Next»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../kb-scan/shots/044.jpg](../kb-scan/shots/044.jpg)

### 154. Кнопка «Add to queue» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Add to queue»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../kb-scan/shots/045.jpg](../kb-scan/shots/045.jpg)

### 155. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../kb-scan/shots/050.jpg](../kb-scan/shots/050.jpg)

### 156. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../kb-scan/shots/058.jpg](../kb-scan/shots/058.jpg)

### 157. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/wiki/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../latest/shots/041.jpg](../latest/shots/041.jpg)

### 158. Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [../latest/shots/042.jpg](../latest/shots/042.jpg)

### 159. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [../latest/shots/043.jpg](../latest/shots/043.jpg)

### 160. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../latest/shots/044.jpg](../latest/shots/044.jpg)

### 161. Кнопка «Services» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «Services»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../latest/shots/045.jpg](../latest/shots/045.jpg)

### 162. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../latest/shots/046.jpg](../latest/shots/046.jpg)

### 163. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../latest/shots/047.jpg](../latest/shots/047.jpg)

### 164. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [../latest/shots/048.jpg](../latest/shots/048.jpg)

### 165. Круглая кнопка-значок «Назад» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [../latest/shots/049.jpg](../latest/shots/049.jpg)

### 166. Круглая кнопка-значок «Далі» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [../latest/shots/050.jpg](../latest/shots/050.jpg)

### 167. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [../latest/shots/052.jpg](../latest/shots/052.jpg)

### 168. Круглая кнопка-значок «Zurück» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [../latest/shots/057.jpg](../latest/shots/057.jpg)

### 169. Круглая кнопка-значок «Weiter» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [../latest/shots/058.jpg](../latest/shots/058.jpg)

### 170. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [../latest/shots/060.jpg](../latest/shots/060.jpg)

### 171. Круглая кнопка-значок «Précédent» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 172. Круглая кнопка-значок «Suivant» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 173. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 174. Круглая кнопка-значок «Anterior» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 175. Круглая кнопка-значок «Próximo» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 176. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 177. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 178. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 179. Слишком много элементов на странице (>3000) 📚
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3267. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [../kb-scan/shots/040.jpg](../kb-scan/shots/040.jpg)

## Не удалось проверить (3)

- Бот остановился с ошибкой: page.$: Execution context was destroyed, most likely because of a navigation     at scCart (/home/runner/work/solid-sound-site/solid-sound-site/tools/qa/qa.js:502:54)     at /home/runner/work/solid-sound-site/solid-sound-site/async /home/runner/work/solid-sound-site/solid-sound-site/tools/qa/qa.js:664:5
- Вход как клиент — нет тестового логина в секретах
- Вход как админ — нет тестового логина в секретах

