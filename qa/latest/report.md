# Автотестировщик — отчёт 2026-11-22 07:58 UTC (полная проверка)

Проверено: страниц 86, кнопок 570, кнопок звука 54, файлов звука 27, ссылок 600. Время: 77 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 44.
Найдено: 🔴 Критично 0 · 🟠 Важно 8 · 🟡 Мелочь 23 · ⚪ Проверить глазами 16 · новых 14 · исправлено с прошлого раза 2

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (8)

### 1. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/free-demo-feedback/ (телефон, компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: a.ssn-cart.add_to_cart_button «Add Time Goes Fast Documentary Piano to » ← закрывает div#ssp.ssp.is-open «Audio player»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/014.jpg](shots/014.jpg)

### 2. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/forum/, https://solid-sound.net/en/my-account/orders/, https://solid-sound.net/en/my-account/edit-account/ (Samsung A31, компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/forum/ (Samsung A31)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/en/forum/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую). · Указано: https://solid-sound.net/en/my-account/ Почему важно: Google решит, что это копия, и покажет другую страницу (ил
- **Скриншот:** [shots/024.jpg](shots/024.jpg)

### 3. Кнопку ▶ «Play Infinity» невозможно нажать (её что-то закрывает) 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Infinity»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/005.jpg](shots/005.jpg)

### 4. Кнопку ▶ «Play Kratos» невозможно нажать (её что-то закрывает) 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Kratos»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/006.jpg](shots/006.jpg)

### 5. После нажатия «Film · Ads · Video» — ошибка в коде
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «Film · Ads · Video»
- **Ожидалось:** Без ошибок
- **На деле:** console: Permissions policy violation: compute-pressure is not allowed in this document.
- **Скриншот:** [shots/007.jpg](shots/007.jpg)

### 6. Кнопка «Play Space Echo — client track» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать ▶ «Play Space Echo — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.sab-p в sab-c)
- **Скриншот:** [shots/021.jpg](shots/021.jpg)

### 7. Кнопка «Play Feeling Good — client track» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.sab-p в sab-c)
- **Скриншот:** [shots/022.jpg](shots/022.jpg)

### 8. Кнопка «Play Escape — client track» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать ▶ «Play Escape — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.sab-p в sab-c)
- **Скриншот:** [shots/023.jpg](shots/023.jpg)

## 🟡 Мелочь (23)

### 9. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/refund_returns/, https://solid-sound.net/en/license/, https://solid-sound.net/en/contacts/ и ещё 35 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/solid-sound-custom/build/assets/js-l1023.d70a254c40.js; https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/004.jpg](shots/004.jpg)

### 10. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/sell/ и ещё 15 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/008.jpg](shots/008.jpg)

### 11. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/, https://solid-sound.net/en/free-demo-feedback/ и ещё 9 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: top-q
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/003.jpg](shots/003.jpg)

### 12. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/prices/, https://solid-sound.net/en/sell/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/ и ещё 3 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: em.ss-more «+3 more» 0px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/009.jpg](shots/009.jpg)

### 13. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/ и ещё 2 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: em «BPM & key map» 3.8:1; em «Ableton live set (stems)» 3.8:1; em «Mix / mixtape mastering» 3.8:1; em «Transition tools» 3.8:1; em «Echo / room sound» 3.8:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/002.jpg](shots/002.jpg)

### 14. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Sort products» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/013.jpg](shots/013.jpg)

### 15. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/, https://solid-sound.net/product/infinity/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 16. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 17. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 18. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

### 19. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a «Forums»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/016.jpg](shots/016.jpg)

### 20. Меню на UA (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Products, All products, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/026.jpg](shots/026.jpg)

### 21. Меню на UA (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/027.jpg](shots/027.jpg)

### 22. Меню на DE (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/029.jpg](shots/029.jpg)

### 23. Меню на DE (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/030.jpg](shots/030.jpg)

### 24. Меню на FR (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Products, All products, Templates, Presets, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/032.jpg](shots/032.jpg)

### 25. Меню на FR (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/033.jpg](shots/033.jpg)

### 26. Меню на PT (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/035.jpg](shots/035.jpg)

### 27. Меню на PT (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/036.jpg](shots/036.jpg)

### 28. Меню на ES (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, Reviews, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell
- **Скриншот:** [shots/038.jpg](shots/038.jpg)

### 29. Меню на ES (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved
- **Скриншот:** [shots/039.jpg](shots/039.jpg)

### 30. Меню на IT (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, Reviews, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell
- **Скриншот:** [shots/041.jpg](shots/041.jpg)

### 31. Меню на IT (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved
- **Скриншот:** [shots/042.jpg](shots/042.jpg)

## ⚪ Проверить глазами (16)

### 32. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/refund_returns/, https://solid-sound.net/en/license/, https://solid-sound.net/en/contacts/ и ещё 30 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/001.jpg](shots/001.jpg)

### 33. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/, https://solid-sound.net/product/infinity/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/010.jpg](shots/010.jpg)

### 34. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/services-prices/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3736. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3231. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3187. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3715. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/012.jpg](shots/012.jpg)

### 35. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/015.jpg](shots/015.jpg)

### 36. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/011.jpg](shots/011.jpg)

### 37. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/017.jpg](shots/017.jpg)

### 38. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/018.jpg](shots/018.jpg)

### 39. Кнопка «Marco L.» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/019.jpg](shots/019.jpg)

### 40. Кнопка «Hector M.» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/020.jpg](shots/020.jpg)

### 41. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/025.jpg](shots/025.jpg)

### 42. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/028.jpg](shots/028.jpg)

### 43. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/031.jpg](shots/031.jpg)

### 44. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/034.jpg](shots/034.jpg)

### 45. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/037.jpg](shots/037.jpg)

### 46. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/040.jpg](shots/040.jpg)

### 47. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.1 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/043.jpg](shots/043.jpg)

## ✅ Исправлено с прошлой проверки (2)

- Превью «02-After.mp3» может хрипеть (перегруз)
- Превью «01-Before.mp3» начинается с тишины (4.4 с)

