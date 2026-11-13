# Автотестировщик — отчёт 2026-11-13 15:17 UTC (короткая проверка после выкладки) — ⏳ проверка ещё идёт, отчёт неполный

Проверено: страниц 8, кнопок 0, кнопок звука 7, ссылок 0. Время: 160 мин.
Найдено: 🔴 Критично 1 · 🟠 Важно 8 · 🟡 Мелочь 17 · ⚪ Проверить глазами 12

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🔴 Критично (1)

### 1. Главная на других языках — другая (старая) страница, а не переведённая новая
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Открыть https://solid-sound.net/ua/ 3) Сравнить
- **Ожидалось:** Та же страница, только переведённая
- **На деле:** UA: совпадает с английской на 11%
- **Скриншот:** [shots/031.jpg](shots/031.jpg)

## 🟠 Важно (8)

### 2. Не загружается файл или ресурс на странице
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 404 /wp-content/plugins/solid-sound-custom/build/assets/js-l4539.5790d159cd.js

### 3. Не загружается файл или ресурс на странице
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 404 /wp-content/plugins/solid-sound-custom/build/assets/ssb-js.e35f0dad8c.js

### 4. Не загружается файл или ресурс на странице
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 404 /wp-content/plugins/solid-sound-custom/build/assets/ss-goto-js.a15f61046a.js

### 5. Не загружается файл или ресурс на странице
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 404 /wp-content/plugins/solid-sound-custom/build/assets/ss-foot-js.4f48c742d2.js

### 6. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div.w-filter-item.ssf-fmt «Templates Presets Reconstructed Template»; a.button.product_type_simple «Add to cart: “Mutualism”» ← закрывает button.ssb-fab «Questions? Ask here»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/017.jpg](shots/017.jpg)

### 7. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/019.jpg](shots/019.jpg)

### 8. Меню на UA ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на UA
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Послуги» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 9. Поиск по слову «techno» ничего не находит
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть главную 2) Ввести «techno» в поиск 3) Нажать Enter
- **Ожидалось:** Список товаров
- **На деле:** Открылось https://solid-sound.net/?post_type=product&s=techno, товаров: 0
- **Скриншот:** [shots/032.jpg](shots/032.jpg)

## 🟡 Мелочь (17)

### 10. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/004.jpg](shots/004.jpg)

### 11. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/007.jpg](shots/007.jpg)

### 12. Поле ввода без подписи
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: input.inp [Your best email]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/003.jpg](shots/003.jpg)

### 13. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/product/infinity/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 41×21; input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/006.jpg](shots/006.jpg)

### 14. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/016.jpg](shots/016.jpg)

### 15. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LENGTH:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/020.jpg](shots/020.jpg)

### 16. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/021.jpg](shots/021.jpg)

### 17. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/005.jpg](shots/005.jpg)

### 18. Кнопка «Browse products» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Найти кнопку «Browse products»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/012.jpg](shots/012.jpg)

### 19. Нет описания страницы (meta description)
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.
- **Скриншот:** [shots/015.jpg](shots/015.jpg)

### 20. Страница /all-products не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 21. Главная картинка первого экрана грузится «лениво» (loading=lazy)
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: 0621-%D0%9E%D0%B1%D0%BB%D0%BE%D0%B6%D0%BA%D0%B0-e17555510432
- **Ожидалось:** Для первого экрана — без lazy, с fetchpriority=high.
- **На деле:** Почему важно: Самая важная картинка появляется последней.
- **Скриншот:** [shots/022.jpg](shots/022.jpg)

### 22. Кнопка «BUY» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Найти кнопку «BUY»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 54px)
- **Скриншот:** [shots/023.jpg](shots/023.jpg)

### 23. Картинки без размеров — страница «прыгает» при загрузке
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (компьютер) 2) Где: hqdefault.jpg
- **Ожидалось:** width/height или aspect-ratio.
- **На деле:** Почему важно: Пока грузится картинка, текст и кнопки съезжают — промахи, плохая оценка Google (CLS).
- **Скриншот:** [shots/024.jpg](shots/024.jpg)

### 24. Кнопка «Дивитися товари» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Найти кнопку «Дивитися товари»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/027.jpg](shots/027.jpg)

### 25. Меню на UA (компьютер) частично не переведено
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Products, All products, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/029.jpg](shots/029.jpg)

### 26. Меню на UA (телефон) частично не переведено
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/030.jpg](shots/030.jpg)

## ⚪ Проверить глазами (12)

### 27. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/002.jpg](shots/002.jpg)

### 28. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/013.jpg](shots/013.jpg)

### 29. Круглая кнопка-значок «Add to queue» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/010.jpg](shots/010.jpg)

### 30. Круглая кнопка-значок «Video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/011.jpg](shots/011.jpg)

### 31. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/014.jpg](shots/014.jpg)

### 32. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/001.jpg](shots/001.jpg)

### 33. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/008.jpg](shots/008.jpg)

### 34. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/009.jpg](shots/009.jpg)

### 35. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3272. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3268. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/018.jpg](shots/018.jpg)

### 36. Круглая кнопка-значок «Назад» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/025.jpg](shots/025.jpg)

### 37. Круглая кнопка-значок «Далі» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/026.jpg](shots/026.jpg)

### 38. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/028.jpg](shots/028.jpg)

## Не удалось проверить (1)

- Сценарий «Корзина» прервался: page.$: Execution context was destroyed, most likely because of a navigation

