# Автотестировщик — отчёт 2026-11-23 16:28 UTC (полная проверка)

Проверено: страниц 76, кнопок 570, кнопок звука 54, файлов звука 27, ссылок 0. Время: 117 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 43.
Найдено: 🔴 Критично 0 · 🟠 Важно 12 · 🟡 Мелочь 12 · ⚪ Проверить глазами 0 · новых 14 · исправлено с прошлого раза 9

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (12)

### 1. Скрипты в <head> без defer/async — тормозят показ страницы (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/refund_returns/, https://solid-sound.net/en/license/, https://solid-sound.net/en/contacts/ и ещё 32 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/solid-sound-custom/build/assets/js-l1023.d70a254c40.js; https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/003.jpg](../../latest/shots/003.jpg)

### 2. Кнопки/ссылки слишком маленькие для пальца (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/sell/ и ещё 14 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/004.jpg](../../latest/shots/004.jpg)

### 3. Одинаковые id у разных элементов (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/, https://solid-sound.net/en/free-demo-feedback/ и ещё 7 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: top-q
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/002.jpg](../../latest/shots/002.jpg)

### 4. Слишком мелкий текст на телефоне (меньше 12 px) (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/prices/, https://solid-sound.net/en/sell/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/ и ещё 3 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: em.ss-more «+3 more» 0px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/005.jpg](../../latest/shots/005.jpg)

### 5. Ссылка-пустышка: href="#" или пусто, ничего не происходит (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a «Forums»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/011.jpg](../../latest/shots/011.jpg)

### 6. Текст плохо читается: слишком бледный на фоне (висит 14 проверок подряд)
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: a.btn «GET A FREE DEMO FEEDBACK» 1.2:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/014.jpg](../../latest/shots/014.jpg)

### 7. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз) (висит 13 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/, https://solid-sound.net/product/infinity/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 8. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз) (висит 13 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 9. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз) (висит 13 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 10. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз) (висит 13 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

### 11. После нажатия «Product 4» — ошибка в коде 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Product 4»
- **Ожидалось:** Без ошибок
- **На деле:** console: Permissions policy violation: compute-pressure is not allowed in this document.
- **Скриншот:** [shots/009.jpg](../../latest/shots/009.jpg)

### 12. После запуска другого трека прежняя кнопка всё ещё показывает ⏸ 🆕
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/017.jpg](../../latest/shots/017.jpg)

## 🟡 Мелочь (12)

### 13. Меню на UA (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Products, All products, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/019.jpg](../../latest/shots/019.jpg)

### 14. Меню на UA (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/020.jpg](../../latest/shots/020.jpg)

### 15. Меню на DE (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/022.jpg](../../latest/shots/022.jpg)

### 16. Меню на DE (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/023.jpg](../../latest/shots/023.jpg)

### 17. Меню на FR (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Products, All products, Templates, Presets, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/025.jpg](../../latest/shots/025.jpg)

### 18. Меню на FR (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/026.jpg](../../latest/shots/026.jpg)

### 19. Меню на PT (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/028.jpg](../../latest/shots/028.jpg)

### 20. Меню на PT (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/029.jpg](../../latest/shots/029.jpg)

### 21. Меню на ES (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, Reviews, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell
- **Скриншот:** [shots/031.jpg](../../latest/shots/031.jpg)

### 22. Меню на ES (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved
- **Скриншот:** [shots/032.jpg](../../latest/shots/032.jpg)

### 23. Меню на IT (компьютер) частично не переведено 🆕
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Get your set heard, Examples, Reviews, How it works, Forum, Why Solid Sound, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell
- **Скриншот:** [shots/034.jpg](../../latest/shots/034.jpg)

### 24. Меню на IT (телефон) частично не переведено 🆕
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved
- **Скриншот:** [shots/035.jpg](../../latest/shots/035.jpg)

## 🔇 Не показано — робот помнит, что это не баг или отложено (15)

Память: `qa/learned.json` (как пополнять — `docs/bots.md`).

- Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами? — не баг: кнопка чата круглая по дизайну (как у всех чатов)
- Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами? — отложено на 14 дней (повторяется 16 проверок подряд)
- Кнопка «Watch video» скруглена не так, как остальные — отложено на 14 дней (повторяется 14 проверок подряд)
- Слишком много элементов на странице (>3000) — отложено на 14 дней (повторяется 14 проверок подряд)
- Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами? — отложено на 14 дней (повторяется 14 проверок подряд)
- Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами? — не баг: стрелки листания отзывов — круглые по дизайну
- Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами? — не баг: стрелки листания отзывов — круглые по дизайну
- Кнопка «Marco L.» скруглена не так, как остальные — не баг: это аватарки авторов отзывов, а не кнопки
- Кнопка «Hector M.» скруглена не так, как остальные — не баг: это аватарки авторов отзывов, а не кнопки
- Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами? — не баг: кнопка чата круглая по дизайну (как у всех чатов)
- Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами? — не баг: кнопка чата круглая по дизайну (как у всех чатов)
- Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами? — не баг: кнопка чата круглая по дизайну (как у всех чатов)
- Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами? — не баг: кнопка чата круглая по дизайну (как у всех чатов)
- Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами? — не баг: кнопка чата круглая по дизайну (как у всех чатов)
- Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами? — не баг: кнопка чата круглая по дизайну (как у всех чатов)

## ✅ Исправлено с прошлой проверки (9)

- Canonical указывает на другую страницу или на http
- Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- Кнопка «Play Space Echo — client track» не превращается в ⏸ (паузу), когда трек играет
- Кнопка «Play Feeling Good — client track» не превращается в ⏸ (паузу), когда трек играет
- Кнопка «Play Escape — client track» не превращается в ⏸ (паузу), когда трек играет
- Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- Превью «02-After.mp3» может хрипеть (перегруз)
- Превью «01-Before.mp3» начинается с тишины (4.4 с)
- Страница весит больше 3 МБ

## Не удалось проверить (2)

- Сценарий «Корзина» прервался: page.$: Execution context was destroyed, most likely because of a navigation
- Проверка остановлена по времени — часть ссылок/входов не проверена

