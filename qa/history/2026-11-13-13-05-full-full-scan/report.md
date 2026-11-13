# Автотестировщик — отчёт 2026-11-13 13:05 UTC (полная проверка)

Проверено: страниц 159, кнопок 660, кнопок звука 64, ссылок 261. Время: 91 мин.
Найдено: 🔴 Критично 1 · 🟠 Важно 97 · 🟡 Мелочь 153 · ⚪ Проверить глазами 82

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🔴 Критично (1)

### 1. Главная на других языках — другая (старая) страница, а не переведённая новая
- **Где:** https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Открыть https://solid-sound.net/ua/ 3) Сравнить
- **Ожидалось:** Та же страница, только переведённая
- **На деле:** UA: совпадает с английской на 11%; DE: совпадает с английской на 11%; FR: совпадает с английской на 11%; PT: совпадает с английской на 11%; ES: совпадает с английской на 11%; IT: совпадает с английской на 11%

## 🟠 Важно (97)

### 2. Меню на IT ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/it/, https://solid-sound.net/it/templates-it/, https://solid-sound.net/it/presets-it/, https://solid-sound.net/it/music-it/, https://solid-sound.net/it/contacts-it/, https://solid-sound.net/it/wiki-it/ и ещё 12 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на IT
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 3. Меню на UA ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/ua/, https://solid-sound.net/ua/shablony/, https://solid-sound.net/ua/presety/, https://solid-sound.net/ua/muzyka/, https://solid-sound.net/ua/kontakty/, https://solid-sound.net/ua/sample-page-uk/ и ещё 9 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на UA
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Послуги» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 4. Нет заголовка страницы (title) или он слишком длинный/короткий
- **Где:** https://solid-sound.net/ua/wiki-ua/iak-zapysaty-chystyi-vokal-udoma-3-pravyla-mikrofona/, https://solid-sound.net/ua/wiki-ua/zakryti-navushnyky-dlia-zapysu-vokalu-ua/, https://solid-sound.net/de/wiki-de/so-machst-du-saubere-vocals-zu-hause-3-mikrofon-regeln-die-einen/, https://solid-sound.net/de/wiki-de/symmetrische-unsymmetrische-kabel-de/, https://solid-sound.net/de/wiki-de/geschlossene-kopfhoerer-gesangsaufnahmen-de/, https://solid-sound.net/fr/wiki-fr/comment-enregistrer-des-voix-propres-a-la-maison-3-regles-de-micro/ и ещё 8 (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/wiki-ua/iak-zapysaty-chystyi-vokal-udoma-3-pravyla-mikrofona/ (компьютер)
- **Ожидалось:** title 15–65 символов, свой у каждой страницы.
- **На деле:** Сейчас: «Техніка роботи з мікрофоном: 3 правила чистого вокалу вдома – Solid Sound» (73 симв.). Почему важно: Заголовок — первая строка в выдаче Google. Пустой/обрезанный = меньше кликов. · Сейчас: «Закриті навушники для запису вокалу: позбудься витоку звуку – Solid Sound» (73 симв.). Почему важно: Заголовок  · Сейчас: «Mikrofontechnik für Gesang: 3 Regeln für saubere Vocals zu Hause – Solid Sound» (78 симв.). Почему важно: Загол · Сейчас: «Symmetrische vs. unsymmetrische Kabel: XLR, TRS und TS erklärt – Solid Sound» (76 симв.). Почему важно: Заголов · Сейчас: «Geschlossene Kopfhörer für Gesangsaufnahmen: Bleed stoppen – Solid Sound» (72 симв.). Почему важно: Заголовок —

### 5. Меню на DE ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/de/, https://solid-sound.net/de/vorlagen/, https://solid-sound.net/de/voreinstellungen/, https://solid-sound.net/de/musik/, https://solid-sound.net/de/kontakt/, https://solid-sound.net/de/wiki-de/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на DE
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Leistungen» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 6. Меню на FR ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/fr/, https://solid-sound.net/fr/modeles/, https://solid-sound.net/fr/prereglages/, https://solid-sound.net/fr/musique/, https://solid-sound.net/fr/contact/, https://solid-sound.net/fr/wiki-fr/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на FR
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 7. Меню на PT ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/pt/, https://solid-sound.net/pt/modelos/, https://solid-sound.net/pt/predefinicoes/, https://solid-sound.net/pt/musica/, https://solid-sound.net/pt/contactos/, https://solid-sound.net/pt/wiki-pt/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на PT
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Serviços» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 8. Меню на ES ведёт на страницы на другом языке
- **Где:** https://solid-sound.net/es/, https://solid-sound.net/es/templates-es/, https://solid-sound.net/es/presets-es/, https://solid-sound.net/es/music-es/, https://solid-sound.net/es/contacts-es/, https://solid-sound.net/es/wiki-es/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Нажать любой из пунктов ниже
- **Ожидалось:** Каждый пункт открывает страницу на ES
- **На деле:** 13 пунктов: «Products» → /en/all-products/, «All products» → /en/all-products/, «Services» → /en/services-prices/, «Services and Prices» → /en/services-prices/, «Wiki» → /en/wiki/, «Common Questions» → /en/faq/, «Terms of Sale» → /en/terms/, «Legal Notice» → /en/legal-notice/, «Free» → /en/free/, «Sell» → /en/vendor-register/, «Shop» → /en/all-products/, «Studio» → /en/services-prices/ …

### 9. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/ua/shablony/, https://solid-sound.net/ua/sample-page-uk/, https://solid-sound.net/ua/templates-uk/, https://solid-sound.net/de/vorlagen/, https://solid-sound.net/fr/modeles/ и ещё 1 (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div.w-filter-item.ssf-fmt «Templates Presets Reconstructed Template»; a.button.product_type_simple «Add to cart: “Mutualism”» ← закрывает button.ssb-fab «Questions? Ask here»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/022.jpg](../../full-scan/shots/022.jpg)

### 10. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/shop/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/, https://solid-sound.net/en/client-dashboard/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/en/all-products/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую). · Указано: https://solid-sound.net/en/cart/ Почему важно: Google решит, что это копия, и покажет другую страницу (или ника · Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу · Указано: https://solid-sound.net/en/my-account/ Почему важно: Google решит, что это копия, и покажет другую страницу (ил
- **Скриншот:** [shots/037.jpg](../../full-scan/shots/037.jpg)

### 11. Кнопка «Play Space Echo — client track» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Space Echo — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/007.jpg](../../full-scan/shots/007.jpg)

### 12. Кнопка «Play Feeling Good — client track» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/008.jpg](../../full-scan/shots/008.jpg)

### 13. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/024.jpg](../../full-scan/shots/024.jpg)

### 14. Кнопка «Play all tracks» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play all tracks»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/025.jpg](../../full-scan/shots/025.jpg)

### 15. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/034.jpg](../../full-scan/shots/034.jpg)

### 16. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/privacy-policy/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/privacy-policy/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/036.jpg](../../full-scan/shots/036.jpg)

### 17. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/038.jpg](../../full-scan/shots/038.jpg)

### 18. Кнопка «Play all tracks» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать ▶ «Play all tracks»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/039.jpg](../../full-scan/shots/039.jpg)

### 19. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/045.jpg](../../full-scan/shots/045.jpg)

### 20. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/cart/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/048.jpg](../../full-scan/shots/048.jpg)

### 21. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/checkout/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/checkout/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/050.jpg](../../full-scan/shots/050.jpg)

### 22. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/my-account/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/052.jpg](../../full-scan/shots/052.jpg)

### 23. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/refund_returns/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/refund_returns/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/054.jpg](../../full-scan/shots/054.jpg)

### 24. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/license/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/license/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/056.jpg](../../full-scan/shots/056.jpg)

### 25. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/contacts/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/contacts/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/058.jpg](../../full-scan/shots/058.jpg)

### 26. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/faq/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/060.jpg](../../full-scan/shots/060.jpg)

### 27. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/store-manager/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/062.jpg](../../full-scan/shots/062.jpg)

### 28. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/vendor-membership/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-membership/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/063.jpg](../../full-scan/shots/063.jpg)

### 29. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/vendor-register/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-register/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/066.jpg](../../full-scan/shots/066.jpg)

### 30. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/add-product/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/068.jpg](../../full-scan/shots/068.jpg)

### 31. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/links/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/links/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/070.jpg](../../full-scan/shots/070.jpg)

### 32. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/071.jpg](../../full-scan/shots/071.jpg)

### 33. Кнопка «Play all tracks» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play all tracks»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/072.jpg](../../full-scan/shots/072.jpg)

### 34. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/templates/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/074.jpg](../../full-scan/shots/074.jpg)

### 35. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/presets/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/075.jpg](../../full-scan/shots/075.jpg)

### 36. Кнопка «Play all tracks» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/presets/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать ▶ «Play all tracks»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/076.jpg](../../full-scan/shots/076.jpg)

### 37. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/presets/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/078.jpg](../../full-scan/shots/078.jpg)

### 38. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/services-calculator/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/089.jpg](../../full-scan/shots/089.jpg)

### 39. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/mastering-album-calculator/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/mastering-album-calculator/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/091.jpg](../../full-scan/shots/091.jpg)

### 40. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/music/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/092.jpg](../../full-scan/shots/092.jpg)

### 41. Кнопка «Play all tracks» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/music/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать ▶ «Play all tracks»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶
- **Скриншот:** [shots/093.jpg](../../full-scan/shots/093.jpg)

### 42. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/music/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/095.jpg](../../full-scan/shots/095.jpg)

### 43. Кнопка или ссылка без названия (только значок)
- **Где:** https://solid-sound.net/en/forum/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a.wpf-sb-button.wpf-sb-icon; a.wpf-sb-button.wpf-sb-icon; a.wpf-sb-button.wpf-sb-icon
- **Ожидалось:** aria-label="Play", "Add to cart" и т.п.
- **На деле:** Почему важно: Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.
- **Скриншот:** [shots/096.jpg](../../full-scan/shots/096.jpg)

### 44. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forum/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/099.jpg](../../full-scan/shots/099.jpg)

### 45. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/110.jpg](../../full-scan/shots/110.jpg)

### 46. Не загружается файл или ресурс на странице
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum-experimental/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 404 /en/forum-experimental/style.css

### 47. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forum-experimental/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum-experimental/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/114.jpg](../../full-scan/shots/114.jpg)

### 48. «⚙️ Налаштування» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «⚙️ Налаштування»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в a
- **Скриншот:** [shots/118.jpg](../../full-scan/shots/118.jpg)

### 49. «📜 Правила» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «📜 Правила»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#banner.pulse
- **Скриншот:** [shots/119.jpg](../../full-scan/shots/119.jpg)

### 50. «Звинуватити! карта відкриється — хтось отримає Оману» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Звинуватити! карта відкриється — хтось отримає Оману»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#dockGrid
- **Скриншот:** [shots/120.jpg](../../full-scan/shots/120.jpg)

### 51. «Зрозуміло» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Зрозуміло»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в img.cimg
- **Скриншот:** [shots/121.jpg](../../full-scan/shots/121.jpg)

### 52. «Cookie settings» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Cookie settings»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в canvas.ssw-cv
- **Скриншот:** [shots/122.jpg](../../full-scan/shots/122.jpg)

### 53. «🌿 Нова гра» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «🌿 Нова гра»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div.mtitle
- **Скриншот:** [shots/123.jpg](../../full-scan/shots/123.jpg)

### 54. «Повірити вміння заявленого духа спрацює» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Повірити вміння заявленого духа спрацює»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в i
- **Скриншот:** [shots/124.jpg](../../full-scan/shots/124.jpg)

### 55. Ошибка в коде страницы (JavaScript)
- **Где:** https://solid-sound.net/en/prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ 2) Посмотреть консоль браузера
- **Ожидалось:** Без ошибок
- **На деле:** tailwind is not defined

### 56. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/prices/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/127.jpg](../../full-scan/shots/127.jpg)

### 57. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/services-prices/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/130.jpg](../../full-scan/shots/130.jpg)

### 58. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/sell/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/132.jpg](../../full-scan/shots/132.jpg)

### 59. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/creator-terms/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/creator-terms/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/134.jpg](../../full-scan/shots/134.jpg)

### 60. «Cookie settings» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/free/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать «Cookie settings»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ssp.ssp
- **Скриншот:** [shots/137.jpg](../../full-scan/shots/137.jpg)

### 61. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/data-deletion/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/data-deletion/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/139.jpg](../../full-scan/shots/139.jpg)

### 62. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/141.jpg](../../full-scan/shots/141.jpg)

### 63. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/143.jpg](../../full-scan/shots/143.jpg)

### 64. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/terms/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/terms/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/145.jpg](../../full-scan/shots/145.jpg)

### 65. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/legal-notice/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/legal-notice/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/147.jpg](../../full-scan/shots/147.jpg)

### 66. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/149.jpg](../../full-scan/shots/149.jpg)

### 67. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/audio-interface-for-vocals/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/audio-interface-for-vocals/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/151.jpg](../../full-scan/shots/151.jpg)

### 68. Не загружается файл или ресурс на странице
- **Где:** https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 404 /wp-content/plugins/solid-sound-custom/build/assets/ss-goto-js.a15f61046a.js

### 69. Не загружается файл или ресурс на странице
- **Где:** https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/
- **Ожидалось:** Все файлы загружаются
- **На деле:** 404 /wp-content/plugins/solid-sound-custom/build/assets/ss-foot-js.4f48c742d2.js

### 70. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/153.jpg](../../full-scan/shots/153.jpg)

### 71. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/sitemap/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sitemap/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/155.jpg](../../full-scan/shots/155.jpg)

### 72. «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/client-dashboard/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/client-dashboard/ 2) Нажать «Questions? Ask here»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в button.ss-cc-yes
- **Скриншот:** [shots/157.jpg](../../full-scan/shots/157.jpg)

### 73. Картинка не загружается
- **Где:** https://solid-sound.net/it/product/protos/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/protos/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/YhHqgjL7GZE/hqdefault.jpg

### 74. Картинка не загружается
- **Где:** https://solid-sound.net/it/product/kratos/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/kratos/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/nSpn5K1RCPI/hqdefault.jpg

### 75. Картинка не загружается
- **Где:** https://solid-sound.net/it/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/infinity/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/zWJqFa0f9UA/hqdefault.jpg

### 76. Картинка не загружается
- **Где:** https://solid-sound.net/it/product/intricate/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/intricate/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/JLFhtPQ_H90/hqdefault.jpg

### 77. Картинка не загружается
- **Где:** https://solid-sound.net/it/product/mutualism/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/mutualism/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/DUAjMbw1QUo/hqdefault.jpg

### 78. Картинка не загружается
- **Где:** https://solid-sound.net/it/product/pitch/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/pitch/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/XYoGLiePfEc/hqdefault.jpg

### 79. Картинка не загружается
- **Где:** https://solid-sound.net/it/product/melodic-techno-presets/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/melodic-techno-presets/
- **Ожидалось:** Картинка видна
- **На деле:** https://i.ytimg.com/vi/BIpHU9kKfCc/hqdefault.jpg

### 80. Поиск по слову «techno» ничего не находит
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть главную 2) Ввести «techno» в поиск 3) Нажать Enter
- **Ожидалось:** Список товаров
- **На деле:** Открылось https://solid-sound.net/?post_type=product&s=techno, товаров: 0

### 81. Битая ссылка: /ua/en/cart/ (код 404)
- **Где:** https://solid-sound.net/en/cart/
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать ссылку на /ua/en/cart/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/cart/, /en/checkout/, /ua/shablony/, /ua/presety/, /ua/muzyka/ и ещё 11

### 82. Битая ссылка: /de/en/cart/ (код 404)
- **Где:** https://solid-sound.net/en/cart/
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать ссылку на /de/en/cart/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/cart/, /en/checkout/, /de/vorlagen/, /de/voreinstellungen/, /de/musik/ и ещё 6

### 83. Битая ссылка: /fr/en/cart/ (код 404)
- **Где:** https://solid-sound.net/en/cart/
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать ссылку на /fr/en/cart/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/cart/, /en/checkout/, /fr/modeles/, /fr/prereglages/, /fr/musique/ и ещё 6

### 84. Битая ссылка: /pt/en/cart/ (код 404)
- **Где:** https://solid-sound.net/en/cart/
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать ссылку на /pt/en/cart/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/cart/, /en/checkout/, /pt/modelos/, /pt/predefinicoes/, /pt/musica/ и ещё 6

### 85. Битая ссылка: /es/en/cart/ (код 404)
- **Где:** https://solid-sound.net/en/cart/
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать ссылку на /es/en/cart/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/cart/, /en/checkout/, /es/templates-es/, /es/presets-es/, /es/music-es/ и ещё 6

### 86. Битая ссылка: /it/en/cart/ (код 404)
- **Где:** https://solid-sound.net/en/cart/
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать ссылку на /it/en/cart/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/cart/, /en/checkout/, /it/templates-it/, /it/presets-it/, /it/music-it/ и ещё 14

### 87. Битая ссылка: /ua/en/my-account/ (код 404)
- **Где:** https://solid-sound.net/en/my-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать ссылку на /ua/en/my-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/, /en/store-manager/, /en/client-dashboard/

### 88. Битая ссылка: /de/en/my-account/ (код 404)
- **Где:** https://solid-sound.net/en/my-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать ссылку на /de/en/my-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/, /en/store-manager/, /en/client-dashboard/

### 89. Битая ссылка: /fr/en/my-account/ (код 404)
- **Где:** https://solid-sound.net/en/my-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать ссылку на /fr/en/my-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/, /en/store-manager/, /en/client-dashboard/

### 90. Битая ссылка: /pt/en/my-account/ (код 404)
- **Где:** https://solid-sound.net/en/my-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать ссылку на /pt/en/my-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/, /en/store-manager/, /en/client-dashboard/

### 91. Битая ссылка: /es/en/my-account/ (код 404)
- **Где:** https://solid-sound.net/en/my-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать ссылку на /es/en/my-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/, /en/store-manager/, /en/client-dashboard/

### 92. Битая ссылка: /it/en/my-account/ (код 404)
- **Где:** https://solid-sound.net/en/my-account/
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать ссылку на /it/en/my-account/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /en/my-account/, /en/store-manager/, /en/client-dashboard/

### 93. Битая ссылка: /ua/en/my-account/lost-password/ (код 404)
- **Где:** https://solid-sound.net/ua/shablony/
- **Шаги:** 1) Открыть https://solid-sound.net/ua/shablony/ 2) Нажать ссылку на /ua/en/my-account/lost-password/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /ua/shablony/, /ua/presety/, /ua/muzyka/, /ua/kontakty/, /ua/sample-page-uk/ и ещё 9

### 94. Битая ссылка: /de/en/my-account/lost-password/ (код 404)
- **Где:** https://solid-sound.net/de/vorlagen/
- **Шаги:** 1) Открыть https://solid-sound.net/de/vorlagen/ 2) Нажать ссылку на /de/en/my-account/lost-password/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /de/vorlagen/, /de/voreinstellungen/, /de/musik/, /de/kontakt/, /de/wiki-de/ и ещё 4

### 95. Битая ссылка: /fr/en/my-account/lost-password/ (код 404)
- **Где:** https://solid-sound.net/fr/modeles/
- **Шаги:** 1) Открыть https://solid-sound.net/fr/modeles/ 2) Нажать ссылку на /fr/en/my-account/lost-password/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /fr/modeles/, /fr/prereglages/, /fr/musique/, /fr/contact/, /fr/wiki-fr/ и ещё 4

### 96. Битая ссылка: /pt/en/my-account/lost-password/ (код 404)
- **Где:** https://solid-sound.net/pt/modelos/
- **Шаги:** 1) Открыть https://solid-sound.net/pt/modelos/ 2) Нажать ссылку на /pt/en/my-account/lost-password/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /pt/modelos/, /pt/predefinicoes/, /pt/musica/, /pt/contactos/, /pt/wiki-pt/ и ещё 4

### 97. Битая ссылка: /es/en/my-account/lost-password/ (код 404)
- **Где:** https://solid-sound.net/es/templates-es/
- **Шаги:** 1) Открыть https://solid-sound.net/es/templates-es/ 2) Нажать ссылку на /es/en/my-account/lost-password/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /es/templates-es/, /es/presets-es/, /es/music-es/, /es/contacts-es/, /es/wiki-es/ и ещё 4

### 98. Битая ссылка: /it/en/my-account/lost-password/ (код 404)
- **Где:** https://solid-sound.net/it/templates-it/
- **Шаги:** 1) Открыть https://solid-sound.net/it/templates-it/ 2) Нажать ссылку на /it/en/my-account/lost-password/
- **Ожидалось:** Страница открывается
- **На деле:** Ошибка 404. Ссылка есть на страницах: /it/templates-it/, /it/presets-it/, /it/music-it/, /it/contacts-it/, /it/wiki-it/ и ещё 12

## 🟡 Мелочь (153)

### 99. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/ и ещё 106 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/005.jpg](../../full-scan/shots/005.jpg)

### 100. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/ и ещё 39 (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/012.jpg](../../full-scan/shots/012.jpg)

### 101. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/links/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/ и ещё 33 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/021.jpg](../../full-scan/shots/021.jpg)

### 102. Поле ввода без подписи
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/ и ещё 32 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: input.inp [Your best email]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/004.jpg](../../full-scan/shots/004.jpg)

### 103. Нет описания страницы (meta description)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/add-product/ и ещё 19 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Описание 70–160 символов.
- **На деле:** Описания нет. Почему важно: Google сам вырезает кусок текста — часто неудачный.
- **Скриншот:** [shots/020.jpg](../../full-scan/shots/020.jpg)

### 104. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/services-calculator/ и ещё 9 (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: span.ss-ai «AI search» 41×21; input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/011.jpg](../../full-scan/shots/011.jpg)

### 105. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/, https://solid-sound.net/en/forum/ и ещё 9 (телефон, компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LENGTH:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/031.jpg](../../full-scan/shots/031.jpg)

### 106. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/, https://solid-sound.net/ua/ и ещё 5 (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/032.jpg](../../full-scan/shots/032.jpg)

### 107. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/, https://solid-sound.net/en/forum-experimental/ и ещё 3 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/046.jpg](../../full-scan/shots/046.jpg)

### 108. Кнопка «BUY» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/it/product/protos/, https://solid-sound.net/it/product/kratos/, https://solid-sound.net/it/product/infinity/, https://solid-sound.net/it/product/intricate/, https://solid-sound.net/it/product/mutualism/, https://solid-sound.net/it/product/pitch/ и ещё 2 (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/protos/ 2) Найти кнопку «BUY»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 54px)

### 109. Картинка загружается намного больше, чем показывается
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/, https://solid-sound.net/en/wiki/record-clean-vocals-at-home/, https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/, https://solid-sound.net/en/wiki/audio-interface-for-vocals/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: P1_3.jpg 1080px → 218px; P1_4.jpg 880px → 218px
- **Ожидалось:** srcset / миниатюра нужного размера.
- **На деле:** Почему важно: Лишние мегабайты на каждой загрузке.
- **Скриншот:** [shots/107.jpg](../../full-scan/shots/107.jpg)

### 110. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/client-dashboard/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/006.jpg](../../full-scan/shots/006.jpg)

### 111. Кнопка «Browse products» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Найти кнопку «Browse products»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/017.jpg](../../full-scan/shots/017.jpg)

### 112. Главная картинка первого экрана грузится «лениво» (loading=lazy)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: 0621-%D0%9E%D0%B1%D0%BB%D0%BE%D0%B6%D0%BA%D0%B0-e17555510432
- **Ожидалось:** Для первого экрана — без lazy, с fetchpriority=high.
- **На деле:** Почему важно: Самая важная картинка появляется последней.
- **Скриншот:** [shots/033.jpg](../../full-scan/shots/033.jpg)

### 113. Поле e-mail/телефона без нужного типа — неудобная клавиатура
- **Где:** https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/sell/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-register/ (компьютер) 2) Где: input#user_email.wcfm-text.wcfm_ele; input.wcfm-text.wcfm_email_verified_input [Verification Code]
- **Ожидалось:** type="email" / type="tel" + autocomplete.
- **На деле:** Почему важно: Нет @ на клавиатуре, нет автозаполнения — бросают форму.
- **Скриншот:** [shots/064.jpg](../../full-scan/shots/064.jpg)

### 114. Кнопка «Mixing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Mixing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/079.jpg](../../full-scan/shots/079.jpg)

### 115. Кнопка «Release Ready» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Release Ready»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/080.jpg](../../full-scan/shots/080.jpg)

### 116. Кнопка «Mastering» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Mastering»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/081.jpg](../../full-scan/shots/081.jpg)

### 117. Кнопка «Remix» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Remix»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/082.jpg](../../full-scan/shots/082.jpg)

### 118. Кнопка «Remake» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Remake»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/083.jpg](../../full-scan/shots/083.jpg)

### 119. Кнопка «Ghost producing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Ghost producing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/084.jpg](../../full-scan/shots/084.jpg)

### 120. Кнопка «Co-producing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Co-producing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/085.jpg](../../full-scan/shots/085.jpg)

### 121. Кнопка «Post production» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Post production»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/086.jpg](../../full-scan/shots/086.jpg)

### 122. Картинки без размеров — страница «прыгает» при загрузке
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: P1_1.jpg; P1_2.jpg; P1_3.jpg; P1_4.jpg; P1_5.jpg
- **Ожидалось:** width/height или aspect-ratio.
- **На деле:** Почему важно: Пока грузится картинка, текст и кнопки съезжают — промахи, плохая оценка Google (CLS).
- **Скриншот:** [shots/108.jpg](../../full-scan/shots/108.jpg)

### 123. Ссылка «#account» ведёт в никуда (нет такого раздела на странице)
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать ссылку #account
- **Ожидалось:** Прокрутка к разделу
- **На деле:** Раздела с таким id нет
- **Скриншот:** [shots/001.jpg](../../full-scan/shots/001.jpg)

### 124. Страница /all-products не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 125. Страница /privacy-policy не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/privacy-policy/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/privacy-policy/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 126. Страница /shop не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 127. Страница /refund_returns не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/refund_returns/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/refund_returns/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 128. Страница /license не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/license/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/license/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 129. Страница /faq не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 130. Страница /vendor-membership не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/vendor-membership/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-membership/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 131. Страница /vendor-register не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/vendor-register/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-register/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 132. Страница /add-product не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/add-product/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 133. Страница /services-calculator не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/services-calculator/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, UA, DE, FR, PT, ES, IT — открывается главная

### 134. Страница /mastering-album-calculator не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/mastering-album-calculator/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/mastering-album-calculator/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, UA, DE, FR, PT, ES, IT — открывается главная

### 135. Внешняя ссылка в новой вкладке без rel="noopener"
- **Где:** https://solid-sound.net/en/forum/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fsolid-sound.net%2Fcom; https://web.whatsapp.com/send?text=Solid+Sound+Forum%20URL:%20https%3A%2F%2Fsoli
- **Ожидалось:** rel="noopener noreferrer".
- **На деле:** Почему важно: Чужой сайт может подменить нашу вкладку (фишинг).
- **Скриншот:** [shots/097.jpg](../../full-scan/shots/097.jpg)

### 136. Страница /forum не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/forum/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 137. Кнопка «GET A FREE DEMO FEEDBACK» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «GET A FREE DEMO FEEDBACK»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/101.jpg](../../full-scan/shots/101.jpg)

### 138. Кнопка «WRITE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «WRITE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/102.jpg](../../full-scan/shots/102.jpg)

### 139. Кнопка «SEE MORE CLIENT STORIES» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «SEE MORE CLIENT STORIES»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 52px)
- **Скриншот:** [shots/103.jpg](../../full-scan/shots/103.jpg)

### 140. Кнопка «MESSAGE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «MESSAGE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 62px)
- **Скриншот:** [shots/106.jpg](../../full-scan/shots/106.jpg)

### 141. Страница /forum-experimental не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum-experimental/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 142. Страница /forest-spirits не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 143. Страница /prices не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 144. Страница /services-prices не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 145. Страница /sell не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 146. Страница /creator-terms не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/creator-terms/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/creator-terms/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 147. Страница /free не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 148. Страница /data-deletion не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/data-deletion/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/data-deletion/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 149. Страница /terms не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/terms/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/terms/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 150. Страница /legal-notice не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/legal-notice/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/legal-notice/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 151. Страница /sitemap не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/en/sitemap/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sitemap/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: UA, DE, FR, PT, ES, IT — открывается главная

### 152. Кнопка «Дивитися товари» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ 2) Найти кнопку «Дивитися товари»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)
- **Скриншот:** [shots/160.jpg](../../full-scan/shots/160.jpg)

### 153. Меню на UA (компьютер) частично не переведено
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Products, All products, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 154. Меню на UA (телефон) частично не переведено
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Все пункты меню на UA
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 155. Кнопка «Produkte ansehen» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ 2) Найти кнопку «Produkte ansehen»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 156. Меню на DE (компьютер) частично не переведено
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 157. Меню на DE (телефон) частично не переведено
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Все пункты меню на DE
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 158. Кнопка «Voir les produits» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ 2) Найти кнопку «Voir les produits»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 159. Меню на FR (компьютер) частично не переведено
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Products, All products, Templates, Presets, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 160. Меню на FR (телефон) частично не переведено
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Все пункты меню на FR
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 161. Кнопка «Ver produtos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ 2) Найти кнопку «Ver produtos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 162. Меню на PT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Products, All products, Templates, Presets, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Legal Notice, Sell

### 163. Меню на PT (телефон) частично не переведено
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Все пункты меню на PT
- **На деле:** На английском: Shop, Studio, Sell, Saved

### 164. Кнопка «Ver productos» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ 2) Найти кнопку «Ver productos»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 165. Меню на ES (компьютер) частично не переведено
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 166. Меню на ES (телефон) частично не переведено
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Все пункты меню на ES
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

### 167. Кнопка «Vedi i prodotti» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ 2) Найти кнопку «Vedi i prodotti»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 44px)

### 168. Меню на IT (компьютер) частично не переведено
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Products, All products, Templates, Presets, Music, Services, Services and Prices, Mixing and Mastering Calculator, Mastering Album Calculator, Examples, Reviews, How it works, Common Questions, Contact Us, Terms of Sale, Refund Policy, License Agreement, Privacy Policy, Legal Notice, Sell

### 169. Меню на IT (телефон) частично не переведено
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Все пункты меню на IT
- **На деле:** На английском: Home, Shop, Studio, Sell, Saved, Cart

### 170. Страница на UA в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/ua/kontakty/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/kontakty/
- **Ожидалось:** Текст на языке UA
- **На деле:** Доля английских слов 22%, кириллицы 40%

### 171. Страница /sample-page-uk не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/ua/sample-page-uk/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/sample-page-uk/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, DE, FR, PT, ES, IT — открывается главная

### 172. Страница /templates-uk не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/ua/templates-uk/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/templates-uk/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, DE, FR, PT, ES, IT — открывается главная

### 173. Страница /presets-uk не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/ua/presets-uk/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/presets-uk/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, DE, FR, PT, ES, IT — открывается главная

### 174. Страница /music-uk не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/ua/music-uk/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/music-uk/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, DE, FR, PT, ES, IT — открывается главная

### 175. Страница на UA в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/ua/contacts-uk/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/contacts-uk/
- **Ожидалось:** Текст на языке UA
- **На деле:** Доля английских слов 22%, кириллицы 40%

### 176. Страница /contacts-uk не переведена — переключатель языка выкидывает на главную
- **Где:** https://solid-sound.net/ua/contacts-uk/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/contacts-uk/ 2) Нажать на язык 3) Выбрать другой язык
- **Ожидалось:** Эта же страница на выбранном языке
- **На деле:** Нет перевода на: EN, DE, FR, PT, ES, IT — открывается главная

### 177. Страница на DE в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/de/kontakt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/kontakt/
- **Ожидалось:** Текст на языке DE
- **На деле:** Доля английских слов 14%

### 178. Страница на FR в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/fr/contact/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/contact/
- **Ожидалось:** Текст на языке FR
- **На деле:** Доля английских слов 13%

### 179. Страница на PT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/pt/contactos/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/contactos/
- **Ожидалось:** Текст на языке PT
- **На деле:** Доля английских слов 22%

### 180. Страница на ES в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/es/contacts-es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/contacts-es/
- **Ожидалось:** Текст на языке ES
- **На деле:** Доля английских слов 14%

### 181. Страница на IT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/it/contacts-it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/contacts-it/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 16%

### 182. Страница на IT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/it/product/protos/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/protos/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 22%

### 183. Страница на IT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/it/product/kratos/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/kratos/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 23%

### 184. Страница на IT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/it/product/infinity/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/infinity/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 24%

### 185. Страница на IT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/it/product/intricate/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/intricate/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 23%

### 186. Страница на IT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/it/product/mutualism/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/mutualism/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 23%

### 187. Страница на IT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/it/product/pitch/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/pitch/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 24%

### 188. Страница на IT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/it/product/melodic-techno-presets/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/melodic-techno-presets/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 21%

### 189. Страница на IT в основном не переведена (много английского текста)
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Ожидалось:** Текст на языке IT
- **На деле:** Доля английских слов 21%

### 190. Ссылка /product-genre/cinematic/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/cinematic/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Cinematic

### 191. Ссылка /product-genre/melodic-techno/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/melodic-techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Melodic%20Techno

### 192. Ссылка /external-plugin/serum/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/serum/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Serum

### 193. Ссылка /product-genre/techno/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-genre/techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Techno

### 194. Ссылка /product-version/ableton-live-11/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-11/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Ableton%20Live%2011

### 195. Ссылка /external-plugin/no-external-plugins/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /external-plugin/no-external-plugins/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=No%20External%20Plugins

### 196. Ссылка /product-version/ableton-live-10/ выкидывает на главную
- **Где:** https://solid-sound.net/en/all-products/
- **Шаги:** 1) Нажать ссылку на /product-version/ableton-live-10/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=Ableton%20Live%2010

### 197. Ссылка /it/product-tag/pitch/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/pitch/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/pitch/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=%23Pitch

### 198. Ссылка /it/product-tag/techno/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/melodic-techno-presets/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=%23Techno

### 199. Ссылка /it/product-tag/presets/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/melodic-techno-presets/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/presets/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=%23Presets

### 200. Ссылка /it/product-tag/melodic-techno/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/melodic-techno-presets/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/melodic-techno/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=%23Melodic%20Techno

### 201. Ссылка /it/product-tag/optimism/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/optimism/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=optimism

### 202. Ссылка /it/product-tag/cozy/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/cozy/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=cozy

### 203. Ссылка /it/product-tag/delicate/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/delicate/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=delicate

### 204. Ссылка /it/product-tag/dreamy/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/dreamy/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=dreamy

### 205. Ссылка /it/product-tag/serene/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/serene/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=serene

### 206. Ссылка /it/product-tag/ambient/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/ambient/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=ambient

### 207. Ссылка /it/product-tag/storytelling/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/storytelling/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=storytelling

### 208. Ссылка /it/product-tag/melody/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/melody/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=melody

### 209. Ссылка /it/product-tag/harmony/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/harmony/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=harmony

### 210. Ссылка /it/product-tag/inspiring/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/inspiring/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=inspiring

### 211. Ссылка /it/product-tag/soothing/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/soothing/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=soothing

### 212. Ссылка /it/product-tag/tranquil/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/tranquil/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=tranquil

### 213. Ссылка /it/product-tag/success/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/success/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=success

### 214. Ссылка /it/product-tag/thoughtful/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/thoughtful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=thoughtful

### 215. Ссылка /it/product-tag/mindful/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/mindful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=mindful

### 216. Ссылка /it/product-tag/smooth/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/smooth/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=smooth

### 217. Ссылка /it/product-tag/refreshing/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/refreshing/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=refreshing

### 218. Ссылка /it/product-tag/meditation/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/meditation/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=meditation

### 219. Ссылка /it/product-tag/calmness/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/calmness/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=calmness

### 220. Ссылка /it/product-tag/tender/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/tender/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=tender

### 221. Ссылка /it/product-tag/classical/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/classical/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=classical

### 222. Ссылка /it/product-tag/creative/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/creative/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=creative

### 223. Ссылка /it/product-tag/comfort/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/comfort/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=comfort

### 224. Ссылка /it/product-tag/atmospheric/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/atmospheric/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=atmospheric

### 225. Ссылка /it/product-tag/balance/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/balance/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=balance

### 226. Ссылка /it/product-tag/energy/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/energy/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=energy

### 227. Ссылка /it/product-tag/flow/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/flow/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=flow

### 228. Ссылка /it/product-tag/spirit/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/spirit/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=spirit

### 229. Ссылка /it/product-tag/elegant/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/elegant/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=elegant

### 230. Ссылка /it/product-tag/piano/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/piano/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=piano

### 231. Ссылка /it/product-tag/inspirational/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/inspirational/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=inspirational

### 232. Ссылка /it/product-tag/uplifting/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/uplifting/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=uplifting

### 233. Ссылка /it/product-tag/morning/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/morning/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=morning

### 234. Ссылка /it/product-tag/motivation/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/motivation/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=motivation

### 235. Ссылка /it/product-tag/calm/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/calm/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=calm

### 236. Ссылка /it/product-tag/relaxing/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/relaxing/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=relaxing

### 237. Ссылка /it/product-tag/positive/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/positive/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=positive

### 238. Ссылка /it/product-tag/warm/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/warm/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=warm

### 239. Ссылка /it/product-tag/emotional/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/emotional/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=emotional

### 240. Ссылка /it/product-tag/gentle/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/gentle/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=gentle

### 241. Ссылка /it/product-tag/background/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/background/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=background

### 242. Ссылка /it/product-tag/cinematic/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/cinematic/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=cinematic

### 243. Ссылка /it/product-tag/peaceful/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/peaceful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=peaceful

### 244. Ссылка /it/product-tag/hopeful/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/hopeful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=hopeful

### 245. Ссылка /it/product-tag/soft/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/soft/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=soft

### 246. Ссылка /it/product-tag/bright/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/bright/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=bright

### 247. Ссылка /it/product-tag/fresh/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/fresh/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=fresh

### 248. Ссылка /it/product-tag/happiness/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/happiness/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=happiness

### 249. Ссылка /it/product-tag/joyful/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/joyful/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=joyful

### 250. Ссылка /it/product-tag/light/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/light/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=light

### 251. Ссылка /it/product-tag/reflective/ выкидывает на главную
- **Где:** https://solid-sound.net/it/product/time-goes-fast-documentary-piano/
- **Шаги:** 1) Нажать ссылку на /it/product-tag/reflective/
- **Ожидалось:** Нужная страница
- **На деле:** Перенаправляет на /?s=reflective

## ⚪ Проверить глазами (82)

### 252. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/, https://solid-sound.net/ua/ и ещё 35 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/018.jpg](../../full-scan/shots/018.jpg)

### 253. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/ и ещё 34 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/003.jpg](../../full-scan/shots/003.jpg)

### 254. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/ua/, https://solid-sound.net/de/ и ещё 19 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/019.jpg](../../full-scan/shots/019.jpg)

### 255. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/, https://solid-sound.net/it/templates-it/, https://solid-sound.net/it/presets-it/, https://solid-sound.net/it/music-it/, https://solid-sound.net/it/contacts-it/, https://solid-sound.net/it/wiki-it/ и ещё 12 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 256. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/, https://solid-sound.net/ua/shablony/, https://solid-sound.net/ua/presety/, https://solid-sound.net/ua/muzyka/, https://solid-sound.net/ua/kontakty/, https://solid-sound.net/ua/sample-page-uk/ и ещё 9 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 257. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/, https://solid-sound.net/de/vorlagen/, https://solid-sound.net/de/voreinstellungen/, https://solid-sound.net/de/musik/, https://solid-sound.net/de/kontakt/, https://solid-sound.net/de/wiki-de/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 258. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/, https://solid-sound.net/fr/modeles/, https://solid-sound.net/fr/prereglages/, https://solid-sound.net/fr/musique/, https://solid-sound.net/fr/contact/, https://solid-sound.net/fr/wiki-fr/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 259. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/, https://solid-sound.net/pt/modelos/, https://solid-sound.net/pt/predefinicoes/, https://solid-sound.net/pt/musica/, https://solid-sound.net/pt/contactos/, https://solid-sound.net/pt/wiki-pt/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 260. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/, https://solid-sound.net/es/templates-es/, https://solid-sound.net/es/presets-es/, https://solid-sound.net/es/music-es/, https://solid-sound.net/es/contacts-es/, https://solid-sound.net/es/wiki-es/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)

### 261. Круглая кнопка-значок «Add to queue» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/ и ещё 2 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/015.jpg](../../full-scan/shots/015.jpg)

### 262. Круглая кнопка-значок «Video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/ua/, https://solid-sound.net/de/, https://solid-sound.net/fr/, https://solid-sound.net/pt/ и ещё 2 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 32px)
- **Скриншот:** [shots/016.jpg](../../full-scan/shots/016.jpg)

### 263. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/013.jpg](../../full-scan/shots/013.jpg)

### 264. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/es/, https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/014.jpg](../../full-scan/shots/014.jpg)

### 265. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3269. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3271. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/023.jpg](../../full-scan/shots/023.jpg)

### 266. Кнопка «SEND REQUEST — GET A REPLY IN 24 H» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/087.jpg](../../full-scan/shots/087.jpg)

### 267. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/100.jpg](../../full-scan/shots/100.jpg)

### 268. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.5 МБ. Почему важно: На 3G/4G в дороге — долго и дорого. · Скачано 3.6 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/109.jpg](../../full-scan/shots/109.jpg)

### 269. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/002.jpg](../../full-scan/shots/002.jpg)

### 270. Кнопка «All» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «All»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/009.jpg](../../full-scan/shots/009.jpg)

### 271. Кнопка «Page 1» ничего не делает
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ 2) Нажать «Page 1»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/010.jpg](../../full-scan/shots/010.jpg)

### 272. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/026.jpg](../../full-scan/shots/026.jpg)

### 273. Кнопка «Previous» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Previous»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/027.jpg](../../full-scan/shots/027.jpg)

### 274. Кнопка «Next» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Next»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/028.jpg](../../full-scan/shots/028.jpg)

### 275. Кнопка «Add to queue» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Add to queue»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/029.jpg](../../full-scan/shots/029.jpg)

### 276. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/030.jpg](../../full-scan/shots/030.jpg)

### 277. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/privacy-policy/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/privacy-policy/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/035.jpg](../../full-scan/shots/035.jpg)

### 278. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/040.jpg](../../full-scan/shots/040.jpg)

### 279. Кнопка «Previous» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Previous»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/041.jpg](../../full-scan/shots/041.jpg)

### 280. Кнопка «Next» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Next»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/042.jpg](../../full-scan/shots/042.jpg)

### 281. Кнопка «Add to queue» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Add to queue»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/043.jpg](../../full-scan/shots/043.jpg)

### 282. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/044.jpg](../../full-scan/shots/044.jpg)

### 283. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/cart/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/cart/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/047.jpg](../../full-scan/shots/047.jpg)

### 284. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/checkout/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/checkout/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/049.jpg](../../full-scan/shots/049.jpg)

### 285. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/my-account/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/051.jpg](../../full-scan/shots/051.jpg)

### 286. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/refund_returns/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/refund_returns/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/053.jpg](../../full-scan/shots/053.jpg)

### 287. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/license/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/license/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/055.jpg](../../full-scan/shots/055.jpg)

### 288. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/contacts/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/contacts/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/057.jpg](../../full-scan/shots/057.jpg)

### 289. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/faq/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/faq/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/059.jpg](../../full-scan/shots/059.jpg)

### 290. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/061.jpg](../../full-scan/shots/061.jpg)

### 291. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/vendor-register/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/vendor-register/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/065.jpg](../../full-scan/shots/065.jpg)

### 292. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/add-product/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/067.jpg](../../full-scan/shots/067.jpg)

### 293. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/links/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/links/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/069.jpg](../../full-scan/shots/069.jpg)

### 294. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/073.jpg](../../full-scan/shots/073.jpg)

### 295. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/presets/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/077.jpg](../../full-scan/shots/077.jpg)

### 296. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/services-calculator/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/088.jpg](../../full-scan/shots/088.jpg)

### 297. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/mastering-album-calculator/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/mastering-album-calculator/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/090.jpg](../../full-scan/shots/090.jpg)

### 298. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/music/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/094.jpg](../../full-scan/shots/094.jpg)

### 299. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/forum/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/098.jpg](../../full-scan/shots/098.jpg)

### 300. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/104.jpg](../../full-scan/shots/104.jpg)

### 301. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/105.jpg](../../full-scan/shots/105.jpg)

### 302. Кнопка «Відповісти» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum-experimental/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/111.jpg](../../full-scan/shots/111.jpg)

### 303. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum-experimental/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/112.jpg](../../full-scan/shots/112.jpg)

### 304. Кнопка «Відповісти» ничего не делает
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum-experimental/ 2) Нажать «Відповісти»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/113.jpg](../../full-scan/shots/113.jpg)

### 305. Кнопка «Повірити вміння заявленого духа спрацює» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/115.jpg](../../full-scan/shots/115.jpg)

### 306. Кнопка «Звинуватити! карта відкриється — хтось о» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/116.jpg](../../full-scan/shots/116.jpg)

### 307. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/117.jpg](../../full-scan/shots/117.jpg)

### 308. Кнопка «Expand All Details» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/125.jpg](../../full-scan/shots/125.jpg)

### 309. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/126.jpg](../../full-scan/shots/126.jpg)

### 310. Кнопка «Services» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «Services»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/128.jpg](../../full-scan/shots/128.jpg)

### 311. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/services-prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-prices/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/129.jpg](../../full-scan/shots/129.jpg)

### 312. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/sell/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sell/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/131.jpg](../../full-scan/shots/131.jpg)

### 313. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/creator-terms/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/creator-terms/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/133.jpg](../../full-scan/shots/133.jpg)

### 314. Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/135.jpg](../../full-scan/shots/135.jpg)

### 315. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/free/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/136.jpg](../../full-scan/shots/136.jpg)

### 316. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/data-deletion/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/data-deletion/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/138.jpg](../../full-scan/shots/138.jpg)

### 317. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/wiki/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/140.jpg](../../full-scan/shots/140.jpg)

### 318. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/142.jpg](../../full-scan/shots/142.jpg)

### 319. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/terms/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/terms/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/144.jpg](../../full-scan/shots/144.jpg)

### 320. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/legal-notice/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/legal-notice/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/146.jpg](../../full-scan/shots/146.jpg)

### 321. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/balanced-vs-unbalanced-cables/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/148.jpg](../../full-scan/shots/148.jpg)

### 322. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/wiki/audio-interface-for-vocals/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/audio-interface-for-vocals/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/150.jpg](../../full-scan/shots/150.jpg)

### 323. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/wiki/closed-back-headphones-for-recording/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/152.jpg](../../full-scan/shots/152.jpg)

### 324. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/sitemap/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/sitemap/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/154.jpg](../../full-scan/shots/154.jpg)

### 325. Кнопка «FAQ» ничего не делает
- **Где:** https://solid-sound.net/en/client-dashboard/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/client-dashboard/ 2) Нажать «FAQ»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/156.jpg](../../full-scan/shots/156.jpg)

### 326. Круглая кнопка-значок «Назад» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/158.jpg](../../full-scan/shots/158.jpg)

### 327. Круглая кнопка-значок «Далі» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/159.jpg](../../full-scan/shots/159.jpg)

### 328. Круглая кнопка-значок «Zurück» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 329. Круглая кнопка-значок «Weiter» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 330. Круглая кнопка-значок «Précédent» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 331. Круглая кнопка-значок «Suivant» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 332. Круглая кнопка-значок «Anterior» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

### 333. Круглая кнопка-значок «Próximo» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)

## Не удалось проверить (2)

- Вход как клиент — нет тестового логина в секретах
- Вход как админ — нет тестового логина в секретах

