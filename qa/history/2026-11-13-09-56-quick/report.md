# Автотестировщик — отчёт 2026-11-13 09:56 UTC (быстрая проверка)

Проверено: страниц 38, кнопок 118, кнопок звука 17, ссылок 150. Время: 25 мин.
Найдено: 🔴 Критично 1 · 🟠 Важно 13 · 🟡 Мелочь 78 · ⚪ Проверить глазами 37 · новых 58 · исправлено с прошлого раза 46

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🔴 Критично (1)

### 1. Главная на других языках — другая (старая) страница, а не переведённая новая
- **Где:** https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Открыть https://solid-sound.net/ua/ 3) Сравнить
- **Ожидалось:** Та же страница, только переведённая
- **На деле:** UA: совпадает с английской на 11%; DE: совпадает с английской на 11%; FR: совпадает с английской на 11%; PT: совпадает с английской на 11%; ES: совпадает с английской на 11%; IT: совпадает с английской на 11%

## 🟠 Важно (13)

### 2. Важную кнопку закрывает что-то сверху (чат, плеер, баннер) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div.w-filter-item.ssf-fmt «Templates Presets Reconstructed Template»; a.button.product_type_simple «Add to cart: “Mutualism”» ← закрывает button.ssb-fab «Questions? Ask here»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/019.jpg](../../latest/shots/019.jpg)

### 3. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/021.jpg](../../latest/shots/021.jpg)

### 4. Картинка не загружается
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/zWJqFa0f9UA/hqdefault.jpg
- **Скриншот:** [shots/031.jpg](../../latest/shots/031.jpg)

### 5. Меню на UA ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на UA
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Послуги» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 6. Меню на DE ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на DE
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Leistungen» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 7. Меню на FR ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на FR
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 8. Меню на PT ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на PT
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Serviços» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 9. Меню на ES ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на ES
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 10. Меню на IT ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на IT
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 11. Поиск по слову «techno» ничего не находит
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть главную 2) Ввести «techno» в поиск 3) Нажать Enter
- **Ожидалось:** Список товаров
- **На деле:** Открылось https://solid-sound.net/?post_type=product&s=techno, товаров: 0

### 12. Товар за $0 / €0 виден покупателям (не бесплатный пакет) 🆕
- **Где:** https://solid-sound.net/en/my-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ (компьютер)
- **Ожидалось:** Скрыть из каталога.
- **На деле:** Текст: «Bonus balance: $0.00». Почему важно: Обычно это технический товар — выглядит как ошибка магазина.

### 13. Canonical указывает на другую страницу или на http 🆕
- **Где:** https://solid-sound.net/en/my-account/orders/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/orders/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/en/my-account/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую).

### 14. Canonical указывает на другую страницу или на http 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/en/my-account/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую).

## 🟡 Мелочь (78)

### 15. Скрипты в <head> без defer/async — тормозят показ страницы 🆕
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/ и ещё 8 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/005.jpg](../../latest/shots/005.jpg)

### 16. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/, https://solid-sound.net/en/faq/ и ещё 4 (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/008.jpg](../../latest/shots/008.jpg)

### 17. Одинаковые id у разных элементов 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/018.jpg](../../latest/shots/018.jpg)

### 18. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/017.jpg](../../latest/shots/017.jpg)

### 19. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LENGTH:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/026.jpg](../../latest/shots/026.jpg)

### 20. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/028.jpg](../../latest/shots/028.jpg)

### 21. Скрипты в <head> без defer/async — тормозят показ страницы 🆕
- **Где:** https://solid-sound.net/en/my-account/, https://solid-sound.net/en/my-account/orders/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.

### 22. Ссылка «#account» ведёт в никуда (нет такого раздела на странице)
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ссылку #account
- **Ожидалось:** Прокрутка к разделу
- **На деле:** Раздела с таким id нет
- **Скриншот:** [shots/001.jpg](../../latest/shots/001.jpg)

### 23. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: input.inp [Your best email]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/004.jpg](../../latest/shots/004.jpg)

### 24. Ссылка-пустышка: href="#" или пусто, ничего не происходит 🆕
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/006.jpg](../../latest/shots/006.jpg)

### 25. Кнопки/ссылки слишком маленькие для пальца 🆕
- **Где:** https://solid-sound.net/en/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 41×21; input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/007.jpg](../../latest/shots/007.jpg)

### 26. Кнопка «Browse products» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Найти кнопку «Browse products»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/013.jpg](../../latest/shots/013.jpg)

### 27. Нет описания страницы (meta description) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.
- **Скриншот:** [shots/016.jpg](../../latest/shots/016.jpg)

### 28. Страница /all-products не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 29. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSIC» 10px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 10px; span.ssh-tag.ssh-tag-presets «PRESETS» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/027.jpg](../../latest/shots/027.jpg)

### 30. Главная картинка первого экрана грузится «лениво» (loading=lazy) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: 0621-%D0%9E%D0%B1%D0%BB%D0%BE%D0%B6%D0%BA%D0%B0-e17555510432
- **Ожидалось:** Для первого экрана — без lazy, с fetchpriority=high.
- **На деле:** Почему важно: Самая важная картинка появляется последней.
- **Скриншот:** [shots/029.jpg](../../latest/shots/029.jpg)

### 31. Кнопка «BUY» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/product/infinity/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Найти кнопку «BUY»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 54px)
- **Скриншот:** [shots/032.jpg](../../latest/shots/032.jpg)

### 32. Кнопки/ссылки слишком маленькие для пальца 🆕
- **Где:** https://solid-sound.net/product/infinity/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ (телефон) 2) Где: a «Home» 38×15; a «Templates» 66×15; a «Refund policy» 88×15; a «Questions?» 72×15; a «License details» 96×15
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/034.jpg](../../latest/shots/034.jpg)

### 33. Страница /free не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 34. Страница /services-prices не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 35. Страница /faq не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 36. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер) 2) Где: input.wcfm-text.wcfm_email_verified_input [Verification Code]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/041.jpg](../../latest/shots/041.jpg)

### 37. Одинаковые id у разных элементов 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер) 2) Где: terms
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/042.jpg](../../latest/shots/042.jpg)

### 38. Поле e-mail/телефона без нужного типа — неудобная клавиатура 🆕
- **Где:** https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ (компьютер) 2) Где: input#user_email.wcfm-text.wcfm_ele; input.wcfm-text.wcfm_email_verified_input [Verification Code]
- **Ожидалось:** type="email" / type="tel" + autocomplete.
- **На деле:** Почему важно: Нет @ на клавиатуре, нет автозаполнения — бросают форму.
- **Скриншот:** [shots/043.jpg](../../latest/shots/043.jpg)

### 39. Страница /sell не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 40. Кнопка «Дивитися товари» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Найти кнопку «Дивитися товари»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/047.jpg](../../latest/shots/047.jpg)

### 41. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (компьютер) 2) Где: select#us_grid_order_1 «Нові Хіти продажу Популярні Найдорожчі ▲»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/049.jpg](../../latest/shots/049.jpg)

### 42. Меню на UA (компьютер) частично не переведено
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Products, All products, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/050.jpg](../../latest/shots/050.jpg)

### 43. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (телефон) 2) Где: span.w-text-value «ЖАНР:» 4.3:1; span.w-text-value «ТРИВАЛІСТЬ:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/051.jpg](../../latest/shots/051.jpg)

### 44. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «МУЗИКА» 10px; span.ssh-tag.ssh-tag-templates «ШАБЛОН» 10px; span.ssh-tag.ssh-tag-presets «ПРЕСЕТИ» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/052.jpg](../../latest/shots/052.jpg)

### 45. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ (телефон) 2) Где: select#us_grid_order_1 «Нові Хіти продажу Популярні Найдорожчі ▲» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/053.jpg](../../latest/shots/053.jpg)

### 46. Меню на UA (телефон) частично не переведено
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Shop, Studio, Sell, Saved
- **Скриншот:** [shots/054.jpg](../../latest/shots/054.jpg)

### 47. Кнопка «Produkte ansehen» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ 2) Найти кнопку «Produkte ansehen»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/057.jpg](../../latest/shots/057.jpg)

### 48. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (компьютер) 2) Где: select#us_grid_order_1 «Neueste Bestseller Beliebt Höchster Prei»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/059.jpg](../../latest/shots/059.jpg)

### 49. Меню на DE (компьютер) частично не переведено
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell
- **Скриншот:** [shots/060.jpg](../../latest/shots/060.jpg)

### 50. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (телефон) 2) Где: span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LÄNGE:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 51. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSIK» 10px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 10px; span.ssh-tag.ssh-tag-presets «PRESETS» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 52. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ (телефон) 2) Где: select#us_grid_order_1 «Neueste Bestseller Beliebt Höchster Prei» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 53. Меню на DE (телефон) частично не переведено
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 54. Кнопка «Voir les produits» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Найти кнопку «Voir les produits»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 55. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (компьютер) 2) Где: select#us_grid_order_1 «Nouveautés Meilleures ventes Populaires »
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 56. Меню на FR (компьютер) частично не переведено
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Products, All products, Templates, Presets, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 57. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (телефон) 2) Где: span.w-text-value «GENRE :» 4.3:1; span.w-text-value «DURÉE :» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 58. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSIQUE» 10px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 10px; span.ssh-tag.ssh-tag-presets «PRESETS» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 59. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ (телефон) 2) Где: select#us_grid_order_1 «Nouveautés Meilleures ventes Populaires » 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 60. Меню на FR (телефон) частично не переведено
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 61. Кнопка «Ver produtos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Найти кнопку «Ver produtos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 62. Поле ввода без подписи 🆕
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (компьютер) 2) Где: select#us_grid_order_1 «Mais recentes Mais vendidos Populares Pr»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.

### 63. Меню на PT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 64. Текст плохо читается: слишком бледный на фоне 🆕
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (телефон) 2) Где: span.w-text-value «GÉNERO:» 4.3:1; span.w-text-value «DURAÇÃO:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.

### 65. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MÚSICA» 10px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 10px; span.ssh-tag.ssh-tag-presets «PRESETS» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 66. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу 🆕
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ (телефон) 2) Где: select#us_grid_order_1 «Mais recentes Mais vendidos Populares Pr» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.

### 67. Меню на PT (телефон) частично не переведено
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 68. Кнопка «Ver productos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Найти кнопку «Ver productos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 69. Меню на ES (компьютер) частично не переведено
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 70. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MÚSICA» 10px; span.ssh-tag.ssh-tag-templates «PLANTILLA» 10px; span.ssh-tag.ssh-tag-presets «PRESETS» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 71. Меню на ES (телефон) частично не переведено
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

### 72. Кнопка «Vedi i prodotti» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Найти кнопку «Vedi i prodotti»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 73. Меню на IT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 74. Слишком мелкий текст на телефоне (меньше 12 px) 🆕
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ (телефон) 2) Где: span.ss-ai «AI search» 10px; span.ssh-tag.ssh-tag-music «MUSICA» 10px; span.ssh-tag.ssh-tag-templates «TEMPLATE» 10px; span.ssh-tag.ssh-tag-presets «PRESETS» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.

### 75. Меню на IT (телефон) частично не переведено
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

### 76. Ссылка «#chat» ведёт в никуда (нет такого раздела на странице) 🆕
- **Где:** https://solid-sound.net/en/my-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать ссылку #chat
- **Ожидалось:** Прокрутка к разделу
- **На деле:** Раздела с таким id нет

### 77. Нет описания страницы (meta description) 🆕
- **Где:** https://solid-sound.net/en/my-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ (компьютер)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.

### 78. Одинаковые id у разных элементов 🆕
- **Где:** https://solid-sound.net/en/my-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ (компьютер) 2) Где: ssd; projects; downloads; purchases; favourites
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.

### 79. Нет описания страницы (meta description) 🆕
- **Где:** https://solid-sound.net/en/my-account/orders/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/orders/ (компьютер)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.

### 80. Нет главного заголовка H1 или их несколько 🆕
- **Где:** https://solid-sound.net/en/my-account/orders/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/orders/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.

### 81. Нет описания страницы (meta description) 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.

### 82. Нет главного заголовка H1 или их несколько 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.

### 83. Одинаковые id у разных элементов 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер) 2) Где: account_first_name; account_last_name; account_display_name; account_display_name_description; account_email
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.

### 84. Скрипты в <head> без defer/async — тормозят показ страницы 🆕
- **Где:** https://solid-sound.net/en/my-account/edit-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/edit-account/ (компьютер) 2) Где: https://solid-sound.net/wp-includes/js/zxcvbn-async.min.js; https://solid-sound.net/wp-includes/js/dist/hooks.min.js; https://solid-sound.net/wp-includes/js/dist/i18n.min.js; https://solid-sound.net/wp-admin/js/password-strength-meter.min.js; https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.

### 85. Страница /store-manager не переведена — переключатель языка выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 86. Ссылка /product-genre/cinematic/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/cinematic/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Cinematic

### 87. Ссылка /product-genre/melodic-techno/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/melodic-techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Melodic%20Techno

### 88. Ссылка /external-plugin/serum/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/serum/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Serum

### 89. Ссылка /product-genre/techno/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Techno

### 90. Ссылка /product-version/ableton-live-11/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-11/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Ableton%20Live%2011

### 91. Ссылка /external-plugin/no-external-plugins/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/no-external-plugins/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=No%20External%20Plugins

### 92. Ссылка /product-version/ableton-live-10/ выкидывает на главную 🆕
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-10/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Ableton%20Live%2010

## ⚪ Проверить глазами (37)

### 93. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/en/wiki/, https://solid-sound.net/en/free/, https://solid-sound.net/en/services-prices/ и ещё 6 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/003.jpg](../../latest/shots/003.jpg)

### 94. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/product/infinity/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/ и ещё 2 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/014.jpg](../../latest/shots/014.jpg)

### 95. Круглая кнопка-значок «Add to queue» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/011.jpg](../../latest/shots/011.jpg)

### 96. Круглая кнопка-значок «Video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/012.jpg](../../latest/shots/012.jpg)

### 97. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/ и ещё 1 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/015.jpg](../../latest/shots/015.jpg)

### 98. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/009.jpg](../../latest/shots/009.jpg)

### 99. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/010.jpg](../../latest/shots/010.jpg)

### 100. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/002.jpg](../../latest/shots/002.jpg)

### 101. Слишком много элементов на странице (>3000) 🆕
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3271. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/020.jpg](../../latest/shots/020.jpg)

### 102. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/022.jpg](../../latest/shots/022.jpg)

### 103. Кнопка «Previous» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Previous»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/023.jpg](../../latest/shots/023.jpg)

### 104. Кнопка «Next» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Next»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/024.jpg](../../latest/shots/024.jpg)

### 105. Кнопка «Add to queue» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Add to queue»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/025.jpg](../../latest/shots/025.jpg)

### 106. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/030.jpg](../../latest/shots/030.jpg)

### 107. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/product/infinity/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/033.jpg](../../latest/shots/033.jpg)

### 108. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/wiki/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/035.jpg](../../latest/shots/035.jpg)

### 109. Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/036.jpg](../../latest/shots/036.jpg)

### 110. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/037.jpg](../../latest/shots/037.jpg)

### 111. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/038.jpg](../../latest/shots/038.jpg)

### 112. Кнопка «Services» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «Services»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/039.jpg](../../latest/shots/039.jpg)

### 113. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/040.jpg](../../latest/shots/040.jpg)

### 114. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/044.jpg](../../latest/shots/044.jpg)

### 115. Круглая кнопка-значок «Назад» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/045.jpg](../../latest/shots/045.jpg)

### 116. Круглая кнопка-значок «Далі» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/046.jpg](../../latest/shots/046.jpg)

### 117. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/048.jpg](../../latest/shots/048.jpg)

### 118. Круглая кнопка-значок «Zurück» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/055.jpg](../../latest/shots/055.jpg)

### 119. Круглая кнопка-значок «Weiter» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/056.jpg](../../latest/shots/056.jpg)

### 120. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/058.jpg](../../latest/shots/058.jpg)

### 121. Круглая кнопка-значок «Précédent» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 122. Круглая кнопка-значок «Suivant» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 123. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 124. Круглая кнопка-значок «Anterior» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 125. Круглая кнопка-значок «Próximo» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 126. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 127. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 128. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 129. Круглая кнопка-значок «без текста» — нужна ли квадратная со скруглёнными углами? 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

## ✅ Исправлено с прошлой проверки (46)

- После нажатия «Products» — ошибка в коде
- Кнопка «Play Time Goes Fast Documentary Piano preview» не превращается в ⏸ (паузу), когда трек играет
- Кнопка «Play Pitch preview» не превращается в ⏸ (паузу), когда трек играет
- Кнопка «Play Mutualism preview» не превращается в ⏸ (паузу), когда трек играет
- «For you» нельзя нажать — сверху лежит другой элемент
- Кнопка ▶ «Play video» не запускает звук
- Кнопку ▶ «Play» невозможно нажать (её что-то закрывает)
- Кнопку ▶ «Add to queue» невозможно нажать (её что-то закрывает)
- Кнопку ▶ «Play video» невозможно нажать (её что-то закрывает)
- В меню подсвечены сразу несколько пунктов: Головна и Послуги
- Меню на UA (компьютер) отличается от английского: пункты ведут не туда
- В меню подсвечены сразу несколько пунктов: Start и Leistungen
- Меню на DE (компьютер) отличается от английского: пункты ведут не туда
- В меню подсвечены сразу несколько пунктов: Accueil и Services
- Меню на FR (компьютер) отличается от английского: пункты ведут не туда
- В меню подсвечены сразу несколько пунктов: Início и Serviços
- Меню на PT (компьютер) отличается от английского: пункты ведут не туда
- В меню подсвечены сразу несколько пунктов: Home и Services
- Меню на ES (компьютер) отличается от английского: пункты ведут не туда
- В меню подсвечены сразу несколько пунктов: Home и Services
- Меню на IT (компьютер) отличается от английского: пункты ведут не туда
- Главная UA помечена как язык «en-US»
- Главная DE помечена как язык «en-US»
- Главная FR помечена как язык «en-US»
- Главная PT помечена как язык «en-US»
- Главная ES помечена как язык «en-US»
- Главная IT помечена как язык «en-US»
- В списке языков нет UA (телефон)
- В списке языков нет DE (телефон)
- В списке языков нет FR (телефон)
- В списке языков нет PT (телефон)
- В списке языков нет ES (телефон)
- В списке языков нет IT (телефон)
- Кнопка «Writing a track» круглая («таблетка»), а не в стиле сайта
- Кнопка «Finishing a track» круглая («таблетка»), а не в стиле сайта
- Кнопка «Releasing» круглая («таблетка»), а не в стиле сайта
- Кнопка «DJ set» круглая («таблетка»), а не в стиле сайта
- Кнопка «Video & ads» круглая («таблетка»), а не в стиле сайта
- Кнопка «Just starting» круглая («таблетка»), а не в стиле сайта
- Кнопка «More» круглая («таблетка»), а не в стиле сайта

