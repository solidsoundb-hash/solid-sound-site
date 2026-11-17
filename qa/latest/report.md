# Автотестировщик — отчёт 2026-11-17 17:32 UTC (полная проверка)

Проверено: страниц 163, кнопок 681, кнопок звука 71, файлов звука 0, ссылок 0. Время: 117 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 109.
Найдено: 🔴 Критично 0 · 🟠 Важно 37 · 🟡 Мелочь 24 · ⚪ Проверить глазами 32 · новых 0 · исправлено с прошлого раза 122

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (37)

### 1. Нет заголовка страницы (title) или он слишком длинный/короткий
- **Где:** https://solid-sound.net/ua/wiki-ua/iak-zapysaty-chystyi-vokal-udoma-3-pravyla-mikrofona/, https://solid-sound.net/ua/wiki-ua/zakryti-navushnyky-dlia-zapysu-vokalu-ua/, https://solid-sound.net/de/wiki-de/so-machst-du-saubere-vocals-zu-hause-3-mikrofon-regeln-die-einen/, https://solid-sound.net/de/wiki-de/symmetrische-unsymmetrische-kabel-de/, https://solid-sound.net/de/wiki-de/geschlossene-kopfhoerer-gesangsaufnahmen-de/, https://solid-sound.net/fr/wiki-fr/comment-enregistrer-des-voix-propres-a-la-maison-3-regles-de-micro/ и ещё 8 (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/wiki-ua/iak-zapysaty-chystyi-vokal-udoma-3-pravyla-mikrofona/ (компьютер)
- **Ожидалось:** title 15–65 символов, свой у каждой страницы.
- **На деле:** Сейчас: «Техніка роботи з мікрофоном: 3 правила чистого вокалу вдома – Solid Sound» (73 симв.). Почему важно: Заголовок — первая строка в выдаче Google. Пустой/обрезанный = меньше кликов. · Сейчас: «Закриті навушники для запису вокалу: позбудься витоку звуку – Solid Sound» (73 симв.). Почему важно: Заголовок  · Сейчас: «Mikrofontechnik für Gesang: 3 Regeln für saubere Vocals zu Hause – Solid Sound» (78 симв.). Почему важно: Загол · Сейчас: «Symmetrische vs. unsymmetrische Kabel: XLR, TRS und TS erklärt – Solid Sound» (76 симв.). Почему важно: Заголов · Сейчас: «Geschlossene Kopfhörer für Gesangsaufnahmen: Bleed stoppen – Solid Sound» (72 симв.). Почему важно: Заголовок —
- **Скриншот:** [shots/091.jpg](shots/091.jpg)

### 2. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/shop/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/, https://solid-sound.net/en/forum-experimental/ и ещё 2 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/en/all-products/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую). · Указано: https://solid-sound.net/en/sell/ Почему важно: Google решит, что это копия, и покажет другую страницу (или ника · Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу · Указано: https://solid-sound.net/en/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую). · Указано: https://solid-sound.net/en/my-account/ Почему важно: Google решит, что это копия, и покажет другую страницу (ил
- **Скриншот:** [shots/022.jpg](shots/022.jpg)

### 3. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div#templates.w-hwrapper.align_left «Play All 8 items Newest Top Sellers Popu»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/014.jpg](shots/014.jpg)

### 4. Кнопка или ссылка без названия (только значок)
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: a.wpf-sb-button.wpf-sb-icon; a.wpf-sb-button.wpf-sb-icon; a.wpf-sb-button.wpf-sb-icon
- **Ожидалось:** aria-label="Play", "Add to cart" и т.п.
- **На деле:** Почему важно: Экранный диктор читает «кнопка» — непонятно, что она делает; Google тоже снижает оценку.
- **Скриншот:** [shots/042.jpg](shots/042.jpg)

### 5. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/006.jpg](shots/006.jpg)

### 6. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/016.jpg](shots/016.jpg)

### 7. «Video» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в span.ssh-tag
- **Скриншот:** [shots/021.jpg](shots/021.jpg)

### 8. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/023.jpg](shots/023.jpg)

### 9. «Video» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в span.ssh-tag
- **Скриншот:** [shots/025.jpg](shots/025.jpg)

### 10. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/029.jpg](shots/029.jpg)

### 11. Кнопка ▶ «Play all tracks» включает другой трек
- **Где:** https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play all tracks»
- **Ожидалось:** Играет «pitch_128_kbps.mp3»
- **На деле:** Играет «kratos_dark_techno_128kbps.mp3»
- **Скриншот:** [shots/030.jpg](shots/030.jpg)

### 12. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/presets/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/031.jpg](shots/031.jpg)

### 13. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/music/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/041.jpg](shots/041.jpg)

### 14. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/053.jpg](shots/053.jpg)

### 15. После нажатия «Previous examples» — ошибка в коде
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum-experimental/ 2) Нажать «Previous examples»
- **Ожидалось:** Без ошибок
- **На деле:** console: Permissions policy violation: compute-pressure is not allowed in this document.
- **Скриншот:** [shots/054.jpg](shots/054.jpg)

### 16. «⚙️ Налаштування» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «⚙️ Налаштування»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в a
- **Скриншот:** [shots/057.jpg](shots/057.jpg)

### 17. «📜 Правила» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «📜 Правила»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#banner.pulse
- **Скриншот:** [shots/058.jpg](shots/058.jpg)

### 18. «Зрозуміло» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Зрозуміло»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#revealOverlay.overlay
- **Скриншот:** [shots/059.jpg](shots/059.jpg)

### 19. «Cookie settings» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Cookie settings»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#revealCap.peekcap
- **Скриншот:** [shots/060.jpg](shots/060.jpg)

### 20. «🌿 Нова гра» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «🌿 Нова гра»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div.mtitle
- **Скриншот:** [shots/061.jpg](shots/061.jpg)

### 21. «Повірити вміння заявленого духа спрацює» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Повірити вміння заявленого духа спрацює»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в i
- **Скриншот:** [shots/062.jpg](shots/062.jpg)

### 22. «Звинуватити! карта відкриється — хтось отримає Оману» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Звинуватити! карта відкриється — хтось отримає Оману»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в p
- **Скриншот:** [shots/063.jpg](shots/063.jpg)

### 23. Ошибка в коде страницы (JavaScript)
- **Где:** https://solid-sound.net/en/prices/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ 2) Посмотреть консоль браузера
- **Ожидалось:** Без ошибок
- **На деле:** tailwind is not defined

### 24. «Cookie settings» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/free/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/ 2) Нажать «Cookie settings»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#ssp.ssp
- **Скриншот:** [shots/066.jpg](shots/066.jpg)

### 25. Меню на UA (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/ua/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/069.jpg](shots/069.jpg)

### 26. Меню на UA (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/ua/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/070.jpg](shots/070.jpg)

### 27. Меню на DE (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/de/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/073.jpg](shots/073.jpg)

### 28. Меню на DE (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/de/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/074.jpg](shots/074.jpg)

### 29. Меню на FR (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/fr/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/077.jpg](shots/077.jpg)

### 30. Меню на FR (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/fr/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/078.jpg](shots/078.jpg)

### 31. Меню на PT (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/pt/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/081.jpg](shots/081.jpg)

### 32. Меню на PT (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/pt/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/082.jpg](shots/082.jpg)

### 33. Меню на ES (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/es/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/085.jpg](shots/085.jpg)

### 34. Меню на ES (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/es/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/086.jpg](shots/086.jpg)

### 35. Меню на IT (компьютер) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/it/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Products»; пункт №3: нет пункта «Services»; пункт №4: нет пункта «Wiki»; пункт №5: нет пункта «Forum»; пункт №6: нет пункта «FAQ»; пункт №7: нет пункта «Free»; пункт №8: нет пункта «Sell»
- **Скриншот:** [shots/089.jpg](shots/089.jpg)

### 36. Меню на IT (телефон) отличается от английского: пункты ведут не туда
- **Где:** https://solid-sound.net/it/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/ и https://solid-sound.net/en/ 2) Сравнить пункты меню
- **Ожидалось:** Те же пункты меню, ведут на те же страницы (на своём языке)
- **На деле:** пункт №1: нет пункта «Home»; пункт №2: нет пункта «Shop»; пункт №3: нет пункта «Studio»; пункт №4: нет пункта «Free»; пункт №5: нет пункта «Sell»; пункт №6: нет пункта «Saved»
- **Скриншот:** [shots/090.jpg](shots/090.jpg)

### 37. Поиск по слову «techno» ничего не находит
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть главную 2) Ввести «techno» в поиск 3) Нажать Enter
- **Ожидалось:** Список товаров
- **На деле:** Открылось https://solid-sound.net/?post_type=product&s=techno, товаров: 0
- **Скриншот:** [shots/092.jpg](shots/092.jpg)

## 🟡 Мелочь (24)

### 38. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/ и ещё 108 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/004.jpg](shots/004.jpg)

### 39. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/ и ещё 33 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.ss-ai «AI search» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/019.jpg](shots/019.jpg)

### 40. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/ и ещё 30 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/013.jpg](shots/013.jpg)

### 41. Поле ввода без подписи
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ и ещё 29 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: input.inp [Your best email]
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/003.jpg](shots/003.jpg)

### 42. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/ и ещё 24 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/007.jpg](shots/007.jpg)

### 43. Картинки без размеров — страница «прыгает» при загрузке
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/, https://solid-sound.net/it/product/protos/, https://solid-sound.net/it/product/kratos/, https://solid-sound.net/it/product/infinity/, https://solid-sound.net/it/product/intricate/ и ещё 3 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: xP1_1.jpg.pagespeed.ic.scqHlX9dBa.webp; xP1_2.jpg.pagespeed.ic.GWL1JTogGk.webp; xP1_3.jpg.pagespeed.ic.GgXTSEaTvn.webp; xP1_4.jpg.pagespeed.ic.xgRK2x1cif.webp; xP1_5.jpg.pagespeed.ic.X6I2H2Afi-.webp
- **Ожидалось:** width/height или aspect-ratio.
- **На деле:** Почему важно: Пока грузится картинка, текст и кнопки съезжают — промахи, плохая оценка Google (CLS).
- **Скриншот:** [shots/052.jpg](shots/052.jpg)

### 44. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ и ещё 2 (телефон, компьютер, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: span.w-text-value «GENRE:» 4.3:1; span.w-text-value «LENGTH:» 4.3:1; span.w-text-value «DAW:» 4.3:1; span.w-text-value «BPM:» 4.3:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/018.jpg](shots/018.jpg)

### 45. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/client-dashboard/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/005.jpg](shots/005.jpg)

### 46. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/020.jpg](shots/020.jpg)

### 47. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/checkout/, https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/checkout/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/026.jpg](shots/026.jpg)

### 48. Картинка загружается намного больше, чем показывается
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/, https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: xP1_3.jpg.pagespeed.ic.GgXTSEaTvn.webp 1080px → 218px; xP1_4.jpg.pagespeed.ic.xgRK2x1cif.webp 880px → 218px
- **Ожидалось:** srcset / миниатюра нужного размера.
- **На деле:** Почему важно: Лишние мегабайты на каждой загрузке.
- **Скриншот:** [shots/051.jpg](shots/051.jpg)

### 49. Кнопка «Mixing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Mixing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/032.jpg](shots/032.jpg)

### 50. Кнопка «Release Ready» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Release Ready»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/033.jpg](shots/033.jpg)

### 51. Кнопка «Mastering» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Mastering»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/034.jpg](shots/034.jpg)

### 52. Кнопка «Remix» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Remix»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/035.jpg](shots/035.jpg)

### 53. Кнопка «Remake» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Remake»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/036.jpg](shots/036.jpg)

### 54. Кнопка «Ghost producing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Ghost producing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/037.jpg](shots/037.jpg)

### 55. Кнопка «Co-producing» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Co-producing»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/038.jpg](shots/038.jpg)

### 56. Кнопка «Post production» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ 2) Найти кнопку «Post production»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 999px при высоте 48px)
- **Скриншот:** [shots/039.jpg](shots/039.jpg)

### 57. Внешняя ссылка в новой вкладке без rel="noopener"
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fsolid-sound.net%2Fcom; https://web.whatsapp.com/send?text=Solid+Sound+Forum%20URL:%20https%3A%2F%2Fsoli
- **Ожидалось:** rel="noopener noreferrer".
- **На деле:** Почему важно: Чужой сайт может подменить нашу вкладку (фишинг).
- **Скриншот:** [shots/043.jpg](shots/043.jpg)

### 58. Кнопка «GET A FREE DEMO FEEDBACK» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «GET A FREE DEMO FEEDBACK»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/045.jpg](shots/045.jpg)

### 59. Кнопка «WRITE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «WRITE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/046.jpg](shots/046.jpg)

### 60. Кнопка «SEE MORE CLIENT STORIES» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «SEE MORE CLIENT STORIES»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 52px)
- **Скриншот:** [shots/047.jpg](shots/047.jpg)

### 61. Кнопка «MESSAGE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «MESSAGE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 62px)
- **Скриншот:** [shots/050.jpg](shots/050.jpg)

## ⚪ Проверить глазами (32)

### 62. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/privacy-policy/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/cart/, https://solid-sound.net/en/my-account/ и ещё 35 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/002.jpg](shots/002.jpg)

### 63. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/music/, https://solid-sound.net/product/infinity/ и ещё 30 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/010.jpg](shots/010.jpg)

### 64. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/, https://solid-sound.net/it/templates-it/, https://solid-sound.net/it/presets-it/, https://solid-sound.net/it/music-it/, https://solid-sound.net/it/contacts-it/, https://solid-sound.net/it/wiki-it/ и ещё 12 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/088.jpg](shots/088.jpg)

### 65. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/templates/, https://solid-sound.net/en/presets/, https://solid-sound.net/ua/shablony/, https://solid-sound.net/ua/presety/, https://solid-sound.net/ua/sample-page-uk/, https://solid-sound.net/ua/templates-uk/ и ещё 11 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/028.jpg](shots/028.jpg)

### 66. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/, https://solid-sound.net/ua/shablony/, https://solid-sound.net/ua/presety/, https://solid-sound.net/ua/muzyka/, https://solid-sound.net/ua/kontakty/, https://solid-sound.net/ua/sample-page-uk/ и ещё 9 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/068.jpg](shots/068.jpg)

### 67. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/, https://solid-sound.net/de/vorlagen/, https://solid-sound.net/de/voreinstellungen/, https://solid-sound.net/de/musik/, https://solid-sound.net/de/kontakt/, https://solid-sound.net/de/wiki-de/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/072.jpg](shots/072.jpg)

### 68. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/, https://solid-sound.net/fr/modeles/, https://solid-sound.net/fr/prereglages/, https://solid-sound.net/fr/musique/, https://solid-sound.net/fr/contact/, https://solid-sound.net/fr/wiki-fr/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/076.jpg](shots/076.jpg)

### 69. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/, https://solid-sound.net/pt/modelos/, https://solid-sound.net/pt/predefinicoes/, https://solid-sound.net/pt/musica/, https://solid-sound.net/pt/contactos/, https://solid-sound.net/pt/wiki-pt/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/080.jpg](shots/080.jpg)

### 70. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/, https://solid-sound.net/es/templates-es/, https://solid-sound.net/es/presets-es/, https://solid-sound.net/es/music-es/, https://solid-sound.net/es/contacts-es/, https://solid-sound.net/es/wiki-es/ и ещё 4 (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/084.jpg](shots/084.jpg)

### 71. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/ua/sample-page-uk/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3564. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3563. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3542. Почему важно: Телефон тормозит при прокрутке и нажатиях. · Элементов: 3078. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/015.jpg](shots/015.jpg)

### 72. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/forum-experimental/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/001.jpg](shots/001.jpg)

### 73. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/008.jpg](shots/008.jpg)

### 74. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/009.jpg](shots/009.jpg)

### 75. Кнопка «Video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/011.jpg](shots/011.jpg)

### 76. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/012.jpg](shots/012.jpg)

### 77. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/checkout/, https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/checkout/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.2 МБ. Почему важно: На 3G/4G в дороге — долго и дорого. · Скачано 3.7 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/027.jpg](shots/027.jpg)

### 78. Кнопка «SEND REQUEST — GET A REPLY IN 24 H» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/040.jpg](shots/040.jpg)

### 79. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/044.jpg](shots/044.jpg)

### 80. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/all-products/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/017.jpg](shots/017.jpg)

### 81. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/024.jpg](shots/024.jpg)

### 82. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/048.jpg](shots/048.jpg)

### 83. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/049.jpg](shots/049.jpg)

### 84. Кнопка «Повірити вміння заявленого духа спрацює» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/055.jpg](shots/055.jpg)

### 85. Кнопка «Звинуватити! карта відкриється — хтось о» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/056.jpg](shots/056.jpg)

### 86. Кнопка «Expand All Details» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/prices/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/064.jpg](shots/064.jpg)

### 87. Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/065.jpg](shots/065.jpg)

### 88. Кнопка «Отримати безкоштовно →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/067.jpg](shots/067.jpg)

### 89. Кнопка «Gratis-Paket holen →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/071.jpg](shots/071.jpg)

### 90. Кнопка «Obtenir le pack gratuit →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/075.jpg](shots/075.jpg)

### 91. Кнопка «Obter o pacote grátis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/079.jpg](shots/079.jpg)

### 92. Кнопка «Obtener el pack gratis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/083.jpg](shots/083.jpg)

### 93. Кнопка «Ottieni il pacchetto gratis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/087.jpg](shots/087.jpg)

## ✅ Исправлено с прошлой проверки (122)

- Страница /en/wiki/balanced-vs-unbalanced-cables/ не открывается (код 503)
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- Не загружается файл или ресурс на странице
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- «Questions? Ask here» нельзя нажать — сверху лежит другой элемент
- Товар за $0 / €0 виден покупателям (не бесплатный пакет)
- Ошибка в коде страницы (JavaScript)
- Ошибка в коде страницы (JavaScript)

## Не удалось проверить (2)

- Сценарий «Корзина» прервался: page.$: Execution context was destroyed, most likely because of a navigation
- Проверка остановлена по времени — часть ссылок/входов не проверена

