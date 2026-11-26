# Автотестировщик — отчёт 2026-11-26 00:40 UTC (полная проверка)

Проверено: страниц 88, кнопок 430, кнопок звука 54, файлов звука 35, ссылок 1036, роботов 23, плюс-проверок (Safari/Firefox, клавиатура, хаос, вчера↔сегодня, экраны) 106. Время: 65 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 45.
Найдено: 🔴 Критично 0 · 🟠 Важно 14 · 🟡 Мелочь 2 · ⚪ Проверить глазами 1 · новых 10 · исправлено с прошлого раза 7

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (14)

### 1. Кнопки/ссылки слишком маленькие для пальца (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/data-deletion/, https://solid-sound.net/en/sitemap/, https://solid-sound.net/en/privacy-policy/ и ещё 33 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; a.ss-cp «Only an idea? Custom production →» 304×15; a «Time Goes Fast Documentary Piano (Cinema» 208×22; a «Pitch (Techno)» 208×22; a «Infinity (Techno)» 208×22
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_005.jpg](shots/10_005.jpg)

### 2. Одинаковые id у разных элементов (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/templates/ и ещё 9 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: top-q
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_002.jpg](shots/10_002.jpg)

### 3. Скрипты в <head> без defer/async — тормозят показ страницы (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/, https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: https://solid-sound.net/wp-content/uploads/wpforo/assets/phrases.js,qver=3.2.2.56ca56b977786945ca64b6e0e5548eed.pagespeed.ce.VspWuXd4aU.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/11_003.jpg](shots/11_003.jpg)

### 4. Ссылка-пустышка: href="#" или пусто, ничего не происходит (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a «Forums»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/11_004.jpg](shots/11_004.jpg)

### 5. Слишком мелкий текст на телефоне (меньше 12 px) (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/prices/, https://solid-sound.net/en/sell/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ (телефон) 2) Где: div.absolute.top-0 «POPULAR» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/13_003.jpg](shots/13_003.jpg)

### 6. Текст плохо читается: слишком бледный на фоне (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: a.btn «GET A FREE DEMO FEEDBACK» 1.2:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/12_005.jpg](shots/12_005.jpg)

### 7. Кнопку ▶ «Play Infinity» невозможно нажать (её что-то закрывает) 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Infinity»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/10_003.jpg](shots/10_003.jpg)

### 8. Кнопку ▶ «Play Kratos» невозможно нажать (её что-то закрывает) 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Kratos»
- **Ожидалось:** Кнопка нажимается
- **На деле:** elementHandle.click: Timeout 5000ms exceeded.
- **Скриншот:** [shots/10_004.jpg](shots/10_004.jpg)

### 9. ↩ вернулся после исправления: ↩ вернулся после исправления: ↩ вернулся после исправления: ↩ вернулся после исправления: ↩ вернулся после исправления: ↩ вернулся после исправления: ↩ вернулся после исправления: ↩ вернулся после исправления: ↩ вернулся после исправления: ↩ вернулся после исправления: ↩ вернулся после исправления: После нажатия «Product 3» — ошибка в коде 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Product 3»
- **Ожидалось:** Без ошибок
- **На деле:** console: Permissions policy violation: compute-pressure is not allowed in this document.
- **Скриншот:** [shots/10_010.jpg](shots/10_010.jpg)

### 10. Товар за $0 / €0 виден покупателям (не бесплатный пакет) 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер)
- **Ожидалось:** Скрыть из каталога.
- **На деле:** Текст: «€0 to list your products Non-exclusive · you set the price Revenue terms in writing before». Почему важно: Обычно это технический товар — выглядит как ошибка магазина.
- **Скриншот:** [shots/15_003.jpg](shots/15_003.jpg)

### 11. ↩ вернулся после исправления: Не видно, где фокус клавиатуры (нет рамки) 🆕
- **Где:** https://solid-sound.net/en/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «BPM»; input «on»
- **Скриншот:** [shots/22_001_keys_en_.jpg](shots/22_001_keys_en_.jpg)

### 12. ↩ вернулся после исправления: Не видно, где фокус клавиатуры (нет рамки) 🆕
- **Где:** https://solid-sound.net/en/studio-calculator/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/studio-calculator/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «12»; input «on»
- **Скриншот:** [shots/22_005_keys_en_studio_calculator_.jpg](shots/22_005_keys_en_studio_calculator_.jpg)

### 13. ↩ вернулся после исправления: Tab попадает на невидимые элементы 🆕
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** Фокус только на видимых кнопках
- **На деле:** input «wcfmvm_custom_infos[what-do-yo»
- **Скриншот:** [shots/22_007_keys_en_sell_.jpg](shots/22_007_keys_en_sell_.jpg)

### 14. ↩ вернулся после исправления: Не видно, где фокус клавиатуры (нет рамки) 🆕
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «first_name»; input «last_name»; input «user_email»; input «store_name»
- **Скриншот:** [shots/22_007_keys_en_sell_.jpg](shots/22_007_keys_en_sell_.jpg)

## 🟡 Мелочь (2)

### 15. Превью «01-Before.mp3» начинается с тишины (4.4 с)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

### 16. Нет главного заголовка H1 или их несколько 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/10_008.jpg](shots/10_008.jpg)

## ⚪ Проверить глазами (1)

### 17. Кнопка «WITHDRAW FROM CONTRACT HERE» скруглена не так, как остальные 🆕
- **Где:** https://solid-sound.net/en/cancel-order/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/cancel-order/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/16_006.jpg](shots/16_006.jpg)

## ✅ Исправлено с прошлой проверки (7)

- Превью «Pitch_128_kbps.mp3» может хрипеть (перегруз) (висит 15 проверок подряд)
- Превью «Infinity_Tech_House_128Kbps.mp3» может хрипеть (перегруз) (висит 15 проверок подряд)
- Превью «Intricate_Dark-Techno_128kbps.mp3» может хрипеть (перегруз) (висит 15 проверок подряд)
- Превью «Kratos_Dark_Techno_128kbps.mp3» может хрипеть (перегруз) (висит 15 проверок подряд)
- После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- На главной (телефон) нельзя выбрать язык — в меню нет списка языков
- Превью «02-After.mp3» может хрипеть (перегруз)

## Не удалось проверить (3)

- Ссылка /en/?currency=CAD — сервер не ответил (защита от частых запросов)
- Ссылка /en/music/?currency=INR — сервер не ответил (защита от частых запросов)
- Ссылка /services-calculator/ — сервер не ответил (защита от частых запросов)

