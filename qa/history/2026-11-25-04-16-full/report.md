# Автотестировщик — отчёт 2026-11-25 04:16 UTC (полная проверка)

Проверено: страниц 86, кнопок 460, кнопок звука 54, файлов звука 35, ссылок 991, роботов 22, плюс-проверок (Safari/Firefox, клавиатура, хаос, вчера↔сегодня, экраны) 84. Время: 282 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 44.
Найдено: 🔴 Критично 0 · 🟠 Важно 12 · 🟡 Мелочь 2 · ⚪ Проверить глазами 0 · новых 1 · исправлено с прошлого раза 5

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (12)

### 1. Скрипты в <head> без defer/async — тормозят показ страницы (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/data-deletion/, https://solid-sound.net/en/sitemap/, https://solid-sound.net/en/privacy-policy/ и ещё 35 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/solid-sound-custom/build/assets/js-l1023.357a36aaa1.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/10_003.jpg](../../latest/shots/10_003.jpg)

### 2. Кнопки/ссылки слишком маленькие для пальца (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/sitemap/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/wiki/record-clean-vocals-at-home/, https://solid-sound.net/en/contacts/ и ещё 15 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_004.jpg](../../latest/shots/10_004.jpg)

### 3. Одинаковые id у разных элементов (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/templates/ и ещё 9 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: top-q
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_002.jpg](../../latest/shots/10_002.jpg)

### 4. Слишком мелкий текст на телефоне (меньше 12 px) (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/prices/, https://solid-sound.net/en/sell/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/ и ещё 3 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: em.ss-more «+3 more» 0px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/10_005.jpg](../../latest/shots/10_005.jpg)

### 5. Ссылка-пустышка: href="#" или пусто, ничего не происходит (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a «Forums»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/11_003.jpg](../../latest/shots/11_003.jpg)

### 6. Текст плохо читается: слишком бледный на фоне (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: a.btn «GET A FREE DEMO FEEDBACK» 1.2:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/12_005.jpg](../../latest/shots/12_005.jpg)

### 7. Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз) (висит 15 проверок подряд)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «Pitch_128_kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.3 дБ

### 8. Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз) (висит 15 проверок подряд)
- **Где:** https://solid-sound.net/?p=338 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=338 2) Нажать ▶ у трека «Infinity_Tech_House_128Kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.1 дБ

### 9. Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз) (висит 15 проверок подряд)
- **Где:** https://solid-sound.net/?p=340 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=340 2) Нажать ▶ у трека «Intricate_Dark-Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.4 дБ

### 10. Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз) (висит 15 проверок подряд)
- **Где:** https://solid-sound.net/?p=61 (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/?p=61 2) Нажать ▶ у трека «Kratos_Dark_Techno_128kbps.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -7.9 дБ

### 11. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/14_007.jpg](../../latest/shots/14_007.jpg)

### 12. На главной (телефон) нельзя выбрать язык — в меню нет списка языков 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть главную 2) Нажать кнопку меню
- **Ожидалось:** Список языков: EN, UA, DE, FR, PT, ES, IT
- **На деле:** Списка языков нет
- **Скриншот:** [shots/18_016.jpg](../../latest/shots/18_016.jpg)

## 🟡 Мелочь (2)

### 13. Превью «02-After.mp3» может хрипеть (перегруз)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «02-After.mp3»
- **Ожидалось:** Пики ниже 0 дБ
- **На деле:** Пик 0 дБ при средней громкости -8.9 дБ

### 14. Превью «01-Before.mp3» начинается с тишины (4.4 с)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

## ✅ Исправлено с прошлой проверки (5)

- Картинка не загружается
- Не видно, где фокус клавиатуры (нет рамки)
- Не видно, где фокус клавиатуры (нет рамки)
- Tab попадает на невидимые элементы
- Не видно, где фокус клавиатуры (нет рамки)

## Не удалось проверить (3)

- Сценарий «Корзина» прервался: page.$: Execution context was destroyed, most likely because of a navigation
- Проверка остановлена по времени — часть ссылок/входов не проверена
- Робот y1 не прислал отчёт

