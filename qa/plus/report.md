# Автотестировщик — отчёт 2026-11-23 07:58 UTC (полная проверка)

Проверено: страниц 0, кнопок 0, кнопок звука 0, файлов звука 0, ссылок 0, роботов 2, плюс-проверок (Safari/Firefox, клавиатура, хаос, вчера↔сегодня, экраны) 106. Время: 0 мин.
Найдено: 🔴 Критично 0 · 🟠 Важно 1 · 🟡 Мелочь 4 · ⚪ Проверить глазами 0

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (1)

### 1. Ошибка скрипта в Safari iPhone: «ResizeObserver loop completed with undelivered notifications.»
- **Где:** https://solid-sound.net/en/ (Safari iPhone)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (Safari iPhone)
- **Ожидалось:** Без ошибок скриптов
- **На деле:** ResizeObserver loop completed with undelivered notifications.
- **Скриншот:** [shots/2_001_webkit_en_.jpg](shots/2_001_webkit_en_.jpg)

## 🟡 Мелочь (4)

### 2. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «BPM»; input «on»
- **Скриншот:** [shots/1_001_keys_en_.jpg](shots/1_001_keys_en_.jpg)

### 3. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/studio-calculator/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/studio-calculator/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «12»; input «on»
- **Скриншот:** [shots/1_005_keys_en_studio_calculator_.jpg](shots/1_005_keys_en_studio_calculator_.jpg)

### 4. Tab попадает на невидимые элементы
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** Фокус только на видимых кнопках
- **На деле:** input «Ableton templates»; input «Other DAW templates»; input «Synth presets»; input «Sample packs»; input «MIDI»
- **Скриншот:** [shots/1_007_keys_en_sell_.jpg](shots/1_007_keys_en_sell_.jpg)

### 5. Не видно, где фокус клавиатуры (нет рамки)
- **Где:** https://solid-sound.net/en/sell/ (Клавиатура)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажимать Tab
- **Ожидалось:** У кнопки в фокусе видна рамка
- **На деле:** input «»; input «first_name»; input «last_name»; input «user_email»; input «store_name»
- **Скриншот:** [shots/1_007_keys_en_sell_.jpg](shots/1_007_keys_en_sell_.jpg)

