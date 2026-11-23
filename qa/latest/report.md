# Автотестировщик — отчёт 2026-11-23 00:08 UTC (полная проверка)

Проверено: страниц 86, кнопок 515, кнопок звука 54, файлов звука 35, ссылок 973, роботов 20. Время: 42 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 44.
Найдено: 🔴 Критично 0 · 🟠 Важно 5 · 🟡 Мелочь 13 · ⚪ Проверить глазами 16 · новых 2 · исправлено с прошлого раза 15

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (5)

### 1. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/forum/, https://solid-sound.net/en/my-account/orders/, https://solid-sound.net/en/my-account/edit-account/ (Samsung A31, компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/forum/ (Samsung A31)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/en/forum/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую).
- **Скриншот:** [shots/18_011.jpg](shots/18_011.jpg)

### 2. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/free-demo-feedback/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: a.ssn-cart.add_to_cart_button «Add Time Goes Fast Documentary Piano to » ← закрывает div#ssp.ssp.is-open «Audio player»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/10_011.jpg](shots/10_011.jpg)

### 3. Кнопка «Play Space Echo — client track» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать ▶ «Play Space Echo — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.sab-p в sab-c)
- **Скриншот:** [shots/14_007.jpg](shots/14_007.jpg)

### 4. Кнопка «Play Feeling Good — client track» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.sab-p в sab-c)
- **Скриншот:** [shots/14_008.jpg](shots/14_008.jpg)

### 5. Кнопка «Play Escape — client track» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать ▶ «Play Escape — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.sab-p в sab-c)
- **Скриншот:** [shots/14_009.jpg](shots/14_009.jpg)

## 🟡 Мелочь (13)

### 6. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/data-deletion/, https://solid-sound.net/en/sitemap/, https://solid-sound.net/en/privacy-policy/ и ещё 35 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/solid-sound-custom/build/assets/js-l1023.d70a254c40.js; https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/10_004.jpg](shots/10_004.jpg)

### 7. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/sitemap/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/wiki/record-clean-vocals-at-home/, https://solid-sound.net/en/contacts/ и ещё 15 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_005.jpg](shots/10_005.jpg)

### 8. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/templates/ и ещё 9 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: top-q
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_003.jpg](shots/10_003.jpg)

### 9. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/prices/, https://solid-sound.net/en/sell/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/ и ещё 3 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: em.ss-more «+3 more» 0px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/10_006.jpg](shots/10_006.jpg)

### 10. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/ и ещё 2 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: em «BPM & key map» 3.8:1; em «Ableton live set (stems)» 3.8:1; em «Mix / mixtape mastering» 3.8:1; em «Transition tools» 3.8:1; em «Echo / room sound» 3.8:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/10_002.jpg](shots/10_002.jpg)

### 11. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Sort products» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/10_010.jpg](shots/10_010.jpg)

### 12. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a «Forums»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/11_003.jpg](shots/11_003.jpg)

### 13. Превью «02-After.mp3» может хрипеть (перегруз) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «02-After.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.9 дБ

### 14. Превью «01-Before.mp3» начинается с тишины (4.4 с) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

### 15. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 16. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=338 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=338 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 17. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=340 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=340 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 18. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/?p=61 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=61 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

## ⚪ Проверить глазами (16)

### 19. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/data-deletion/, https://solid-sound.net/en/sitemap/, https://solid-sound.net/en/privacy-policy/ и ещё 30 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/10_001.jpg](shots/10_001.jpg)

### 20. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/product/infinity/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_007.jpg](shots/10_007.jpg)

### 21. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/services-prices/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3736. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/10_009.jpg](shots/10_009.jpg)

### 22. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/16_002.jpg](shots/16_002.jpg)

### 23. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_008.jpg](shots/10_008.jpg)

### 24. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/12_003.jpg](shots/12_003.jpg)

### 25. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/12_004.jpg](shots/12_004.jpg)

### 26. Кнопка «Marco L.» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/14_004.jpg](shots/14_004.jpg)

### 27. Кнопка «Hector M.» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/14_005.jpg](shots/14_005.jpg)

### 28. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_013.jpg](shots/18_013.jpg)

### 29. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_014.jpg](shots/18_014.jpg)

### 30. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_015.jpg](shots/18_015.jpg)

### 31. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_016.jpg](shots/18_016.jpg)

### 32. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_017.jpg](shots/18_017.jpg)

### 33. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_018.jpg](shots/18_018.jpg)

### 34. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.1 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/19_005.jpg](shots/19_005.jpg)

## ✅ Исправлено с прошлой проверки (15)

- Кнопку ▶ «Play Infinity» невозможно нажать (её что-то закрывает)
- Кнопку ▶ «Play Kratos» невозможно нажать (её что-то закрывает)
- После нажатия «Film · Ads · Video» — ошибка в коде
- Меню на UA (компьютер) частично не переведено
- Меню на UA (телефон) частично не переведено
- Меню на DE (компьютер) частично не переведено
- Меню на DE (телефон) частично не переведено
- Меню на FR (компьютер) частично не переведено
- Меню на FR (телефон) частично не переведено
- Меню на PT (компьютер) частично не переведено
- Меню на PT (телефон) частично не переведено
- Меню на ES (компьютер) частично не переведено
- Меню на ES (телефон) частично не переведено
- Меню на IT (компьютер) частично не переведено
- Меню на IT (телефон) частично не переведено

