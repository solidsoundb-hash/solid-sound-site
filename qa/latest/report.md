# Автотестировщик — отчёт 2026-11-24 00:35 UTC (полная проверка)

Проверено: страниц 86, кнопок 442, кнопок звука 54, файлов звука 35, ссылок 973, роботов 23, плюс-проверок (Safari/Firefox, клавиатура, хаос, вчера↔сегодня, экраны) 106. Время: 60 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 44.
Найдено: 🔴 Критично 0 · 🟠 Важно 14 · 🟡 Мелочь 4 · ⚪ Проверить глазами 0 · новых 7 · исправлено с прошлого раза 13

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (14)

### 1. Скрипты в <head> без defer/async — тормозят показ страницы (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/data-deletion/, https://solid-sound.net/en/sitemap/, https://solid-sound.net/en/privacy-policy/ и ещё 35 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/solid-sound-custom/build/assets/js-l1023.d70a254c40.js; https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/10_003.jpg](shots/10_003.jpg)

### 2. Кнопки/ссылки слишком маленькие для пальца (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/sitemap/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/wiki/record-clean-vocals-at-home/, https://solid-sound.net/en/contacts/ и ещё 15 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_004.jpg](shots/10_004.jpg)

### 3. Одинаковые id у разных элементов (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/templates/ и ещё 9 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: top-q
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_002.jpg](shots/10_002.jpg)

### 4. Слишком мелкий текст на телефоне (меньше 12 px) (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/prices/, https://solid-sound.net/en/sell/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/ и ещё 3 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: em.ss-more «+3 more» 0px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/10_005.jpg](shots/10_005.jpg)

### 5. Ссылка-пустышка: href="#" или пусто, ничего не происходит (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a «Forums»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/11_003.jpg](shots/11_003.jpg)

### 6. Текст плохо читается: слишком бледный на фоне (висит 15 проверок подряд)
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: a.btn «GET A FREE DEMO FEEDBACK» 1.2:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/12_005.jpg](shots/12_005.jpg)

### 7. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз) (висит 14 проверок подряд)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 8. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз) (висит 14 проверок подряд)
- **Где:** https://solid-sound.net/?p=338 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=338 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 9. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз) (висит 14 проверок подряд)
- **Где:** https://solid-sound.net/?p=340 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=340 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 10. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз) (висит 14 проверок подряд)
- **Где:** https://solid-sound.net/?p=61 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=61 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

### 11. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/14_007.jpg](shots/14_007.jpg)

### 12. Картинка не загружается 🆕
- **Где:** https://solid-sound.net/en/wiki/audio-interface-for-vocals/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/audio-interface-for-vocals/
- **Ожидалось:** Картинка видна
- **На деле:** /wp-content/uploads/audio-interface-patch-cables-usb-connections.webp
- **Скриншот:** [shots/16_007.jpg](shots/16_007.jpg)

### 13. ↩ вернулся после исправления: ↩ вернулся после исправления: Превью «02-After.mp3» может хрипеть (перегруз) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «02-After.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.9 дБ

### 14. ↩ вернулся после исправления: ↩ вернулся после исправления: Превью «01-Before.mp3» начинается с тишины (4.4 с) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

## 🟡 Мелочь (4)

### 15. Не видно, где фокус клавиатуры (нет рамки) 🆕
- **Где:** https://solid-sound.net/en/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «BPM»; input «on»
- **Скриншот:** [shots/22_001_keys_en_.jpg](shots/22_001_keys_en_.jpg)

### 16. Не видно, где фокус клавиатуры (нет рамки) 🆕
- **Где:** https://solid-sound.net/en/studio-calculator/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/studio-calculator/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «12»; input «on»
- **Скриншот:** [shots/22_005_keys_en_studio_calculator_.jpg](shots/22_005_keys_en_studio_calculator_.jpg)

### 17. Tab попадает на невидимые элементы 🆕
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** Фокус только на видимых кнопках
- **На деле:** input «wcfmvm_custom_infos[what-do-yo»
- **Скриншот:** [shots/22_007_keys_en_sell_.jpg](shots/22_007_keys_en_sell_.jpg)

### 18. Не видно, где фокус клавиатуры (нет рамки) 🆕
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «first_name»; input «last_name»; input «user_email»; input «store_name»
- **Скриншот:** [shots/22_007_keys_en_sell_.jpg](shots/22_007_keys_en_sell_.jpg)

## ✅ Исправлено с прошлой проверки (13)

- После нажатия «Product 4» — ошибка в коде
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

