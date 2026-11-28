# Автотестировщик — отчёт 2026-11-28 00:31 UTC (полная проверка)

Проверено: страниц 88, кнопок 556, кнопок звука 54, файлов звука 35, ссылок 1036, роботов 23, плюс-проверок (Safari/Firefox, клавиатура, хаос, вчера↔сегодня, экраны) 106. Время: 54 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 45.
Найдено: 🔴 Критично 0 · 🟠 Важно 7 · 🟡 Мелочь 5 · ⚪ Проверить глазами 0 · новых 1 · исправлено с прошлого раза 5

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (7)

### 1. Кнопки/ссылки слишком маленькие для пальца (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/data-deletion/, https://solid-sound.net/en/sitemap/, https://solid-sound.net/en/privacy-policy/ и ещё 33 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; a.ss-cp «Only an idea? Custom production →» 304×15; a «Kratos (Techno)» 208×22; a «Time Goes Fast Documentary Piano (Cinema» 208×22; a «Infinity (Techno)» 208×22
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_003.jpg](../../latest/shots/10_003.jpg)

### 2. Одинаковые id у разных элементов (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/music/, https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/templates/ и ещё 9 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: top-q
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_002.jpg](../../latest/shots/10_002.jpg)

### 3. Скрипты в <head> без defer/async — тормозят показ страницы (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/, https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: https://solid-sound.net/wp-content/uploads/wpforo/assets/phrases.js,qver=3.2.2.56ca56b977786945ca64b6e0e5548eed.pagespeed.ce.VspWuXd4aU.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/11_003.jpg](../../latest/shots/11_003.jpg)

### 4. Ссылка-пустышка: href="#" или пусто, ничего не происходит (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a «Forums»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/11_004.jpg](../../latest/shots/11_004.jpg)

### 5. Слишком мелкий текст на телефоне (меньше 12 px) (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/prices/, https://solid-sound.net/en/sell/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ (телефон) 2) Где: div.absolute.top-0 «POPULAR» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/13_003.jpg](../../latest/shots/13_003.jpg)

### 6. Текст плохо читается: слишком бледный на фоне (висит 16 проверок подряд)
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: a.btn «GET A FREE DEMO FEEDBACK» 1.2:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/12_005.jpg](../../latest/shots/12_005.jpg)

### 7. ↩ вернулся после исправления: Страница весит больше 3 МБ 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.1 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/19_004.jpg](../../latest/shots/19_004.jpg)

## 🟡 Мелочь (5)

### 8. Превью «01-Before.mp3» начинается с тишины (4.4 с)
- **Где:** https://solid-sound.net/en/all-products/ (звук)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ у трека «01-Before.mp3»
- **Ожидалось:** Музыка с первой секунды — покупатель слушает 2–3 секунды
- **На деле:** Первые 4.4 с тишина

### 9. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «BPM»; input «on»
- **Скриншот:** [shots/22_001_keys_en_.jpg](../../latest/shots/22_001_keys_en_.jpg)

### 10. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/studio-calculator/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/studio-calculator/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «12»; input «on»
- **Скриншот:** [shots/22_005_keys_en_studio_calculator_.jpg](../../latest/shots/22_005_keys_en_studio_calculator_.jpg)

### 11. Tab попадает на невидимые элементы
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** Фокус только на видимых кнопках
- **На деле:** input «wcfmvm_custom_infos[what-do-yo»
- **Скриншот:** [shots/22_007_keys_en_sell_.jpg](../../latest/shots/22_007_keys_en_sell_.jpg)

### 12. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «first_name»; input «last_name»; input «user_email»; input «store_name»
- **Скриншот:** [shots/22_007_keys_en_sell_.jpg](../../latest/shots/22_007_keys_en_sell_.jpg)

## ✅ Исправлено с прошлой проверки (5)

- Нижний плеер показывает «играет», но звука нет
- Tab попадает на невидимые элементы
- Трек долго не начинает играть (больше 12 секунд)
- Трек долго не начинает играть (больше 12 секунд)
- Трек долго не начинает играть (больше 12 секунд)

