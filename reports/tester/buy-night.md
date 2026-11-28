# 🛒 Ночная покупка — 2026-11-28 03:30 UTC

✅ Путь покупки работает.

Робот проходит покупку как клиент (без оплаты). ❌ — здесь путь ломается, снимок в `reports/tester/shots/`.

**компьютер** — ✅ весь путь пройден

| Шаг | Время | Итог |
|---|---|---|
| Главная | 4.4 с | ✅  |
| Каталог | 2.8 с | ✅ первый товар: /product/time-goes-fast-documentary-piano/ |
| Страница товара | 2.0 с | ✅ Time Goes Fast Documentary Piano – Cinematic Royalty-Free Mu |
| Добавить в корзину | 2.4 с | ✅ товар добавлен за 2.3 с |
| Корзина | 5.0 с | ✅ товаров: 1, итого €15.00 |
| Оформление (форма видна) | 2.0 с | ✅ есть блок оплаты, быстрые кнопки оплаты есть. Саму оплату робот не делает — проходит ли она, видно только по реальному заказу |

**Samsung A31** — ✅ весь путь пройден

| Шаг | Время | Итог |
|---|---|---|
| Главная | 3.0 с | ✅  |
| Каталог | 3.0 с | ✅ первый товар: /product/time-goes-fast-documentary-piano/ |
| Страница товара | 2.1 с | ✅ Time Goes Fast Documentary Piano – Cinematic Royalty-Free Mu |
| Добавить в корзину | 2.4 с | ✅ товар добавлен за 2.4 с |
| Корзина | 5.1 с | ✅ товаров: 1, итого €15.00 |
| Оформление (форма видна) | 1.9 с | ✅ есть блок оплаты, быстрые кнопки оплаты есть. Саму оплату робот не делает — проходит ли она, видно только по реальному заказу |


## ↩️ Отмена заказа (2 шага)

- ✅ Шаг 1: кнопка есть; чужой номер заказа → ошибка, шаг 2 не открылся, письма нет
- ✅ Шаг 2 (ПК 1366): «Confirm withdrawal here» видна, радиус 10px — [снимок](cancel-step2-pc.jpg)
- ✅ Шаг 2 (A31 412×780): «Confirm withdrawal here» видна, радиус 10px — [снимок](cancel-step2-a31.jpg)

## 🎧 Демо ↔ товар (8 товаров, ✅ всё совпадает)

Слово из названия товара должно быть в имени аудио и в названии видео; карточки в каталоге и на главной играют тот же файл, что страница товара.

| Товар | Аудио на странице | Видео YouTube | Итог |
|---|---|---|---|
| ✅ Time Goes Fast Documentary Piano | Time-Goes-Fast-Documentary-Piano-Watermark.mp3 | — | совпадает |
| ✅ Melodic Techno Presets | Solid-Sound-Presets-Melodic-Techno-Demo.mp3 | «Solid Sound Presets - Melodic Techno Video» | совпадает |
| ✅ Pitch | Pitch_128_kbps.mp3 | «Pitch» | совпадает |
| ✅ Mutualism | Mutualism_Dark_Techno_128kbps.mp3 | «Mutualism   Grenor» | совпадает |
| ✅ Intricate | Intricate_Dark-Techno_128kbps.mp3 | «Intricate» | совпадает |
| ✅ Infinity | Infinity_Tech_House_128Kbps.mp3 | «Infinity» | совпадает |
| ✅ Kratos | Kratos_Dark_Techno_128kbps.mp3 | «Ableton Live Template - Techno - Kratos (www.wemakedancemusic.com)» | совпадает |
| ✅ Protos | Protos_128kbps.mp3 | «Ableton Live Template - Techno - Protos (www.wemakedancemusic.com)» | совпадает |

---
Робот `tools/sitebots/buy-night.js`, каждую ночь 02:37 UTC; вручную — строка в `.github/buy-night.txt`.
