# Автотестировщик — отчёт 2026-11-23 07:32 UTC (полная проверка)

Проверено: страниц 0, кнопок 0, кнопок звука 0, файлов звука 0, ссылок 0, роботов 1, плюс-проверок (Safari/Firefox, клавиатура, хаос, вчера↔сегодня, экраны) 95. Время: 0 мин.
Найдено: 🔴 Критично 0 · 🟠 Важно 1 · 🟡 Мелочь 14 · ⚪ Проверить глазами 0

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (1)

### 1. Firefox ПК: кнопка меню ничего не делает
- **Где:** https://solid-sound.net/en/ (Firefox ПК)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ в Firefox ПК 2) Нажать первую кнопку меню
- **Ожидалось:** Меню открывается или переходит на страницу
- **На деле:** Ничего не изменилось
- **Скриншот:** [shots/1_002_firefox_menu.jpg](shots/1_002_firefox_menu.jpg)

## 🟡 Мелочь (14)

### 2. Текст обрезан (Firefox ПК)
- **Где:** https://solid-sound.net/en/ (Firefox ПК, телефон 360, телефон боком, планшет, ноутбук 1024, монитор 2560)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (Firefox ПК)
- **Ожидалось:** Текст виден целиком или с «…»
- **На деле:** span.screen-reader-text «— ABLETON TEMPLATES, PRESETS A»
- **Скриншот:** [shots/1_001_firefox_en_.jpg](shots/1_001_firefox_en_.jpg)

### 3. Текст обрезан (Firefox ПК)
- **Где:** https://solid-sound.net/product/infinity/ (Firefox ПК, телефон 360, телефон боком, планшет, ноутбук 1024, монитор 2560)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (Firefox ПК)
- **Ожидалось:** Текст виден целиком или с «…»
- **На деле:** span.screen-reader-text «Original price was: €24.00.»; span.screen-reader-text «Current price is: €12.00.»; span.screen-reader-text «Original price was: €24.00.»; span.screen-reader-text «Current price is: €12.00.»; span.screen-reader-text «Original price was: €24.00.»; span.screen-reader-text «Current price is: €12.00.»
- **Скриншот:** [shots/1_004_firefox_product_infinity_.jpg](shots/1_004_firefox_product_infinity_.jpg)

### 4. Текст обрезан (Firefox ПК)
- **Где:** https://solid-sound.net/en/sell/ (Firefox ПК, телефон 360, телефон боком, планшет, ноутбук 1024, монитор 2560)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (Firefox ПК)
- **Ожидалось:** Текст виден целиком или с «…»
- **На деле:** label.screen-reader-text «First Name»; label.screen-reader-text «Last Name»; label.screen-reader-text «What do you make?»; label.screen-reader-text «Link to your work»; label.screen-reader-text «Second link (optional)»; label.screen-reader-text «Anything we should know?»
- **Скриншот:** [shots/1_008_firefox_en_sell_.jpg](shots/1_008_firefox_en_sell_.jpg)

### 5. Текст обрезан (Firefox ПК)
- **Где:** https://solid-sound.net/en/cart/ (Firefox ПК)
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ (Firefox ПК)
- **Ожидалось:** Текст виден целиком или с «…»
- **На деле:** h1.ss-h1x «Cart»
- **Скриншот:** [shots/1_011_firefox_en_cart_.jpg](shots/1_011_firefox_en_cart_.jpg)

### 6. Текст обрезан (Firefox ПК)
- **Где:** https://solid-sound.net/en/my-account/ (Firefox ПК)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ (Firefox ПК)
- **Ожидалось:** Текст виден целиком или с «…»
- **На деле:** h1.ss-h1x «My Account»; span.screen-reader-text «Required»; span.screen-reader-text «Required»
- **Скриншот:** [shots/1_012_firefox_en_my_account_.jpg](shots/1_012_firefox_en_my_account_.jpg)

### 7. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** a «BLACK MONTH −50% on all Ableto»; a «Solid Sound home»; a «Home»; button «Products»; button «Services»
- **Скриншот:** [shots/1_013_keys_en_.jpg](shots/1_013_keys_en_.jpg)

### 8. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/all-products/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** a «BLACK MONTH −50% on all Ableto»; a «Solid Sound home»; a «Home»; button «Products»; button «Services»
- **Скриншот:** [shots/1_014_keys_en_all_products_.jpg](shots/1_014_keys_en_all_products_.jpg)

### 9. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/product/infinity/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** a «BLACK MONTH −50% on all Ableto»; a «Solid Sound home»; a «Home»; button «Products»; button «Services»
- **Скриншот:** [shots/1_015_keys_product_infinity_.jpg](shots/1_015_keys_product_infinity_.jpg)

### 10. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/services-prices/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** a «BLACK MONTH −50% on all Ableto»; a «Solid Sound home»; a «Home»; button «Products»; button «Services»
- **Скриншот:** [shots/1_016_keys_en_services_prices_.jpg](shots/1_016_keys_en_services_prices_.jpg)

### 11. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/studio-calculator/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/studio-calculator/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** a «BLACK MONTH −50% on all Ableto»; a «Solid Sound home»; a «Home»; button «Products»; button «Services»
- **Скриншот:** [shots/1_017_keys_en_studio_calculator_.jpg](shots/1_017_keys_en_studio_calculator_.jpg)

### 12. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/faq/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** a «BLACK MONTH −50% on all Ableto»; a «Solid Sound home»; a «Home»; button «Products»; button «Services»
- **Скриншот:** [shots/1_018_keys_en_faq_.jpg](shots/1_018_keys_en_faq_.jpg)

### 13. Tab попадает на невидимые элементы
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** Фокус только на видимых кнопках
- **На деле:** input «Ableton templates»; input «Other DAW templates»; input «Synth presets»; input «Sample packs»; input «MIDI»
- **Скриншот:** [shots/1_019_keys_en_sell_.jpg](shots/1_019_keys_en_sell_.jpg)

### 14. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** a «BLACK MONTH −50% on all Ableto»; a «Solid Sound home»; a «Home»; button «Products»; button «Services»
- **Скриншот:** [shots/1_019_keys_en_sell_.jpg](shots/1_019_keys_en_sell_.jpg)

### 15. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/wiki/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** a «BLACK MONTH −50% on all Ableto»; a «Solid Sound home»; a «Home»; button «Products»; button «Services»
- **Скриншот:** [shots/1_020_keys_en_wiki_.jpg](shots/1_020_keys_en_wiki_.jpg)

## Не удалось проверить (1)

- Safari iPhone: браузер не запустился (browserType.launch: 
╔══════════════════════════════════════)

