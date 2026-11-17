# Автотестировщик — отчёт 2026-11-17 21:30 UTC (полная проверка)

Проверено: страниц 106, кнопок 654, кнопок звука 71, файлов звука 0, ссылок 524, роботов 20. Время: 47 мин.
Переводы не проверялись (владелец 17.11: сначала сайт на английском) — пропущено замечаний: 23.
Найдено: 🔴 Критично 0 · 🟠 Важно 23 · 🟡 Мелочь 18 · ⚪ Проверить глазами 31 · новых 7 · исправлено с прошлого раза 28

Для Claude: каждый пункт — задача. Исправить → выложить → бот перепроверит сам. Пункты «Проверить глазами» — возможные ложные срабатывания, решать по скриншоту.

## 🟠 Важно (23)

### 1. Canonical указывает на другую страницу или на http
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/mastering-album-calculator/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/checkout/, https://solid-sound.net/en/vendor-register/ и ещё 5 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/ (компьютер)
- **Ожидалось:** canonical = адрес самой страницы, https.
- **На деле:** Указано: https://solid-sound.net/studio-calculator/ Почему важно: Google решит, что это копия, и покажет другую страницу (или никакую).
- **Скриншот:** [shots/11_004.jpg](../../latest/shots/11_004.jpg)

### 2. Важную кнопку закрывает что-то сверху (чат, плеер, баннер)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: a.button.product_type_simple «Add to cart: “Time Goes Fast Documentary» ← закрывает div#templates.w-hwrapper.align_left «Play All 8 items Newest Top Sellers Popu»
- **Ожидалось:** Сдвинуть плавающие элементы или добавить отступ снизу.
- **На деле:** Почему важно: Нажимается не кнопка, а то, что её закрывает.
- **Скриншот:** [shots/10_015.jpg](../../latest/shots/10_015.jpg)

### 3. Картинка не загружается 🆕
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Картинка видна
- **На деле:** /wp-content/uploads/Pitch_Title_PSD-400x400.jpg
- **Скриншот:** [shots/10_001.jpg](../../latest/shots/10_001.jpg)

### 4. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/10_006.jpg](../../latest/shots/10_006.jpg)

### 5. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/all-products/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/10_017.jpg](../../latest/shots/10_017.jpg)

### 6. «Video» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/all-products/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ 2) Нажать «Video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в span.ssh-tag
- **Скриншот:** [shots/10_019.jpg](../../latest/shots/10_019.jpg)

### 7. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/presets/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/10_021.jpg](../../latest/shots/10_021.jpg)

### 8. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/12_013.jpg](../../latest/shots/12_013.jpg)

### 9. «Video» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в span.ssh-tag
- **Скриншот:** [shots/12_017.jpg](../../latest/shots/12_017.jpg)

### 10. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/music/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/music/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/13_009.jpg](../../latest/shots/13_009.jpg)

### 11. После запуска другого трека прежняя кнопка всё ещё показывает ⏸
- **Где:** https://solid-sound.net/en/forum-experimental/ (компьютер)
- **Шаги:** 1) Нажать ▶ «Play Space Echo — client track» 2) Нажать ▶ «Play Feeling Good — client track»
- **Ожидалось:** «Play Space Echo — client track» снова показывает ▶
- **На деле:** Показывает ⏸, хотя этот трек не играет
- **Скриншот:** [shots/16_008.jpg](../../latest/shots/16_008.jpg)

### 12. Кнопка «Play» не превращается в ⏸ (паузу), когда трек играет
- **Где:** https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play»
- **Ожидалось:** Значок меняется на ⏸
- **На деле:** Трек играет, а на кнопке всё ещё ▶ (кнопка: button.ss-top-play в ss-top-c)
- **Скриншот:** [shots/17_007.jpg](../../latest/shots/17_007.jpg)

### 13. Кнопка ▶ «Play all tracks» включает другой трек
- **Где:** https://solid-sound.net/en/templates/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/templates/ 2) Нажать ▶ «Play all tracks»
- **Ожидалось:** Играет «pitch_128_kbps.mp3»
- **На деле:** Играет «kratos_dark_techno_128kbps.mp3»
- **Скриншот:** [shots/17_008.jpg](../../latest/shots/17_008.jpg)

### 14. «⚙️ Налаштування» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «⚙️ Налаштування»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в a
- **Скриншот:** [shots/17_015.jpg](../../latest/shots/17_015.jpg)

### 15. «📜 Правила» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «📜 Правила»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#banner.pulse
- **Скриншот:** [shots/17_016.jpg](../../latest/shots/17_016.jpg)

### 16. «Звинуватити! карта відкриється — хтось отримає Оману» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Звинуватити! карта відкриється — хтось отримає Оману»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div#dockGrid
- **Скриншот:** [shots/17_017.jpg](../../latest/shots/17_017.jpg)

### 17. «Зрозуміло» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Зрозуміло»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в img.cimg
- **Скриншот:** [shots/17_018.jpg](../../latest/shots/17_018.jpg)

### 18. «🌿 Нова гра» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «🌿 Нова гра»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в div.mtitle
- **Скриншот:** [shots/17_020.jpg](../../latest/shots/17_020.jpg)

### 19. «Повірити вміння заявленого духа спрацює» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Повірити вміння заявленого духа спрацює»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в i
- **Скриншот:** [shots/17_021.jpg](../../latest/shots/17_021.jpg)

### 20. «Cookie settings» нельзя нажать — сверху лежит другой элемент
- **Где:** https://solid-sound.net/en/forest-spirits/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/ 2) Нажать «Cookie settings»
- **Ожидалось:** Кнопка нажимается
- **На деле:** Клик попадает в p
- **Скриншот:** [shots/17_022.jpg](../../latest/shots/17_022.jpg)

### 21. Товар за $0 / €0 виден покупателям (не бесплатный пакет) 🆕
- **Где:** https://solid-sound.net/en/my-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ (компьютер)
- **Ожидалось:** Скрыть из каталога.
- **На деле:** Текст: «Bonus balance: $0.00». Почему важно: Обычно это технический товар — выглядит как ошибка магазина.
- **Скриншот:** [shots/19_003.jpg](../../latest/shots/19_003.jpg)

### 22. Ошибка в коде страницы (JavaScript) 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Посмотреть консоль браузера
- **Ожидалось:** Без ошибок
- **На деле:** color is not defined

### 23. Ошибка в коде страницы (JavaScript) 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Посмотреть консоль браузера
- **Ожидалось:** Без ошибок
- **На деле:** Cannot read properties of undefined (reading 'green')

## 🟡 Мелочь (18)

### 24. Скрипты в <head> без defer/async — тормозят показ страницы
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/prices/, https://solid-sound.net/en/terms/ и ещё 43 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: https://solid-sound.net/wp-content/plugins/official-facebook-pixel/js/facebook_signal.js,qver=5.2.2.pagespeed.ce.OkuaffgxX-.js
- **Ожидалось:** defer (правило проекта в CLAUDE.md).
- **На деле:** Почему важно: Браузер ждёт скачивания скрипта, страница белая.
- **Скриншот:** [shots/10_004.jpg](../../latest/shots/10_004.jpg)

### 25. Кнопки/ссылки слишком маленькие для пальца
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/mastering-album-calculator/ и ещё 21 (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (телефон) 2) Где: input 18×18; button «Page 1» 24×8; button «Page 2» 8×8; button «Page 3» 8×8; button «Page 4» 8×8
- **Ожидалось:** Увеличить зону нажатия (padding).
- **На деле:** Почему важно: Промахиваются и жмут соседнюю кнопку. Норма — 44×44 px (Apple), 48 px (Google).
- **Скриншот:** [shots/10_007.jpg](../../latest/shots/10_007.jpg)

### 26. Одинаковые id у разных элементов
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/music/ и ещё 8 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: templates
- **Ожидалось:** Уникальные id.
- **На деле:** Почему важно: Кнопки, якоря и подписи полей начинают работать не с тем элементом.
- **Скриншот:** [shots/10_014.jpg](../../latest/shots/10_014.jpg)

### 27. Ссылка-пустышка: href="#" или пусто, ничего не происходит
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/forum/, https://solid-sound.net/en/my-account/, https://solid-sound.net/en/forum-experimental/, https://solid-sound.net/en/client-dashboard/, https://solid-sound.net/forum/ и ещё 6 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/ (компьютер) 2) Где: a.ssp-cover «Open the product page»
- **Ожидалось:** Настоящий адрес или кнопка с действием.
- **На деле:** Почему важно: Человек нажимает — и ничего.
- **Скриншот:** [shots/10_005.jpg](../../latest/shots/10_005.jpg)

### 28. Поле ввода без подписи
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price»
- **Ожидалось:** <label> или aria-label.
- **На деле:** Почему важно: Когда человек начал печатать, подсказка-placeholder исчезает — непонятно, что вводить.
- **Скриншот:** [shots/10_013.jpg](../../latest/shots/10_013.jpg)

### 29. Поле ввода с шрифтом меньше 16 px — iPhone сам увеличивает страницу
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/ (телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (телефон) 2) Где: select#us_grid_order_1 «Newest Top Sellers Popular Highest Price» 14px
- **Ожидалось:** font-size: 16px у полей ввода на телефоне.
- **На деле:** Почему важно: При нажатии на поле страница «прыгает» и съезжает — раздражает на оформлении заказа.
- **Скриншот:** [shots/10_018.jpg](../../latest/shots/10_018.jpg)

### 30. Слишком мелкий текст на телефоне (меньше 12 px)
- **Где:** https://solid-sound.net/en/prices/, https://solid-sound.net/en/sell/, https://solid-sound.net/en/vendor-membership/, https://solid-sound.net/en/vendor-register/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/prices/ (телефон) 2) Где: div.absolute.top-0 «POPULAR» 10px
- **Ожидалось:** Минимум 12–13 px для подписей, 15–16 для текста.
- **На деле:** Почему важно: Нечитаемо без увеличения.
- **Скриншот:** [shots/10_022.jpg](../../latest/shots/10_022.jpg)

### 31. Картинка загружается намного больше, чем показывается
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/, https://solid-sound.net/en/wiki/record-clean-vocals-at-home/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: P1_3.jpg 1080px → 218px; P1_4.jpg 880px → 218px
- **Ожидалось:** srcset / миниатюра нужного размера.
- **На деле:** Почему важно: Лишние мегабайты на каждой загрузке.
- **Скриншот:** [shots/15_015.jpg](../../latest/shots/15_015.jpg)

### 32. Внешняя ссылка в новой вкладке без rel="noopener"
- **Где:** https://solid-sound.net/en/forum/, https://solid-sound.net/forum/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forum/ (компьютер) 2) Где: https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fsolid-sound.net%2Fcom; https://web.whatsapp.com/send?text=Solid+Sound+Forum%20URL:%20https%3A%2F%2Fsoli
- **Ожидалось:** rel="noopener noreferrer".
- **На деле:** Почему важно: Чужой сайт может подменить нашу вкладку (фишинг).
- **Скриншот:** [shots/14_007.jpg](../../latest/shots/14_007.jpg)

### 33. Нет главного заголовка H1 или их несколько
- **Где:** https://solid-sound.net/en/add-product/, https://solid-sound.net/en/links/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/add-product/ (компьютер)
- **Ожидалось:** Ровно один видимый H1.
- **На деле:** Видимых H1: 0. Почему важно: Google и экранные дикторы по H1 понимают, о чём страница.
- **Скриншот:** [shots/15_006.jpg](../../latest/shots/15_006.jpg)

### 34. Картинки без размеров — страница «прыгает» при загрузке
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: xP1_1.jpg.pagespeed.ic.scqHlX9dBa.webp; P1_2.jpg; P1_3.jpg; P1_4.jpg; P1_5.jpg
- **Ожидалось:** width/height или aspect-ratio.
- **На деле:** Почему важно: Пока грузится картинка, текст и кнопки съезжают — промахи, плохая оценка Google (CLS).
- **Скриншот:** [shots/15_016.jpg](../../latest/shots/15_016.jpg)

### 35. Кнопка «GET A FREE DEMO FEEDBACK» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «GET A FREE DEMO FEEDBACK»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/15_008.jpg](../../latest/shots/15_008.jpg)

### 36. Кнопка «WRITE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «WRITE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 60px)
- **Скриншот:** [shots/15_009.jpg](../../latest/shots/15_009.jpg)

### 37. Кнопка «SEE MORE CLIENT STORIES» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «SEE MORE CLIENT STORIES»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 52px)
- **Скриншот:** [shots/15_010.jpg](../../latest/shots/15_010.jpg)

### 38. Кнопка «MESSAGE US NOW!» круглая («таблетка»), а не в стиле сайта
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ 2) Найти кнопку «MESSAGE US NOW!»
- **Ожидалось:** Прямоугольная кнопка со скруглёнными углами 8–12px, как остальные кнопки сайта
- **На деле:** Углы скруглены полностью (радиус 50px при высоте 62px)
- **Скриншот:** [shots/15_013.jpg](../../latest/shots/15_013.jpg)

### 39. Текст плохо читается: слишком бледный на фоне
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер) 2) Где: a.btn «GET A FREE DEMO FEEDBACK» 1.2:1
- **Ожидалось:** Контраст не ниже 4.5:1 (мелкий текст).
- **На деле:** Почему важно: На телефоне на улице такой текст не видно.
- **Скриншот:** [shots/15_014.jpg](../../latest/shots/15_014.jpg)

### 40. Ссылка «#chat» ведёт в никуда (нет такого раздела на странице) 🆕
- **Где:** https://solid-sound.net/en/my-account/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/my-account/ 2) Нажать ссылку #chat
- **Ожидалось:** Прокрутка к разделу
- **На деле:** Раздела с таким id нет
- **Скриншот:** [shots/19_001.jpg](../../latest/shots/19_001.jpg)

### 41. Ссылка «#chat» ведёт в никуда (нет такого раздела на странице) 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/ 2) Нажать ссылку #chat
- **Ожидалось:** Прокрутка к разделу
- **На деле:** Раздела с таким id нет
- **Скриншот:** [shots/19_007.jpg](../../latest/shots/19_007.jpg)

## ⚪ Проверить глазами (31)

### 42. Круглая кнопка-значок «Questions? Ask here» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/all-products/, https://solid-sound.net/en/contacts/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/prices/, https://solid-sound.net/en/terms/ и ещё 38 (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/10_003.jpg](../../latest/shots/10_003.jpg)

### 43. Круглая кнопка-значок «+» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/presets/, https://solid-sound.net/en/shop/, https://solid-sound.net/en/music/, https://solid-sound.net/en/templates/, https://solid-sound.net/product/infinity/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_010.jpg](../../latest/shots/10_010.jpg)

### 44. Кнопка «Get the free pack →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/, https://solid-sound.net/en/forum-experimental/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/10_002.jpg](../../latest/shots/10_002.jpg)

### 45. Круглая кнопка-значок «Previous» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_008.jpg](../../latest/shots/10_008.jpg)

### 46. Круглая кнопка-значок «Next» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 46px)
- **Скриншот:** [shots/10_009.jpg](../../latest/shots/10_009.jpg)

### 47. Кнопка «Video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_011.jpg](../../latest/shots/10_011.jpg)

### 48. Кнопка «Watch video» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 0px
- **Скриншот:** [shots/10_012.jpg](../../latest/shots/10_012.jpg)

### 49. Слишком много элементов на странице (>3000)
- **Где:** https://solid-sound.net/en/all-products/, https://solid-sound.net/en/shop/ (компьютер, телефон, Samsung A31)
- **Шаги:** 1) Открыть https://solid-sound.net/en/all-products/ (компьютер)
- **Ожидалось:** Меньше вложенности, подгружать по частям.
- **На деле:** Элементов: 3563. Почему важно: Телефон тормозит при прокрутке и нажатиях.
- **Скриншот:** [shots/10_016.jpg](../../latest/shots/10_016.jpg)

### 50. Круглая кнопка-значок «Watch video» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/presets/, https://solid-sound.net/en/templates/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/presets/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 30px)
- **Скриншот:** [shots/10_020.jpg](../../latest/shots/10_020.jpg)

### 51. Кнопка «SEND REQUEST — GET A REPLY IN 24 H» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/services-calculator/, https://solid-sound.net/en/mastering-album-calculator/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/services-calculator/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/11_003.jpg](../../latest/shots/11_003.jpg)

### 52. Кнопка «Get free feedback» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/, https://solid-sound.net/en/free-demo-feedback/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/14_010.jpg](../../latest/shots/14_010.jpg)

### 53. Страница весит больше 3 МБ
- **Где:** https://solid-sound.net/en/free-demo-feedback/, https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Ожидалось:** Смотреть, что самое тяжёлое.
- **На деле:** Скачано 3.4 МБ. Почему важно: На 3G/4G в дороге — долго и дорого. · Скачано 3.5 МБ. Почему важно: На 3G/4G в дороге — долго и дорого.
- **Скриншот:** [shots/15_017.jpg](../../latest/shots/15_017.jpg)

### 54. Кнопка «Video» ничего не делает
- **Где:** https://solid-sound.net/en/shop/ (телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/shop/ 2) Нажать «Video»
- **Ожидалось:** Что-то открывается/меняется
- **На деле:** Ничего не происходит
- **Скриншот:** [shots/12_016.jpg](../../latest/shots/12_016.jpg)

### 55. Кнопка «UNLOCK FREE DOWNLOADS» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/free/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/14_009.jpg](../../latest/shots/14_009.jpg)

### 56. Круглая кнопка-значок «❮» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_011.jpg](../../latest/shots/15_011.jpg)

### 57. Круглая кнопка-значок «❯» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/en/free-demo-feedback/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/free-demo-feedback/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 50px)
- **Скриншот:** [shots/15_012.jpg](../../latest/shots/15_012.jpg)

### 58. Кнопка «Повірити вміння заявленого духа спрацює» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/17_011.jpg](../../latest/shots/17_011.jpg)

### 59. Кнопка «Звинуватити! карта відкриється — хтось о» скруглена не так, как остальные
- **Где:** https://solid-sound.net/en/forest-spirits/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/en/forest-spirits/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 18px
- **Скриншот:** [shots/17_012.jpg](../../latest/shots/17_012.jpg)

### 60. Кнопка «Отримати безкоштовно →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_015.jpg](../../latest/shots/18_015.jpg)

### 61. Круглая кнопка-значок «Є питання? Пишіть» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/ua/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/ua/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_016.jpg](../../latest/shots/18_016.jpg)

### 62. Кнопка «Gratis-Paket holen →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_017.jpg](../../latest/shots/18_017.jpg)

### 63. Круглая кнопка-значок «Fragen? Hier fragen» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/de/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/de/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_018.jpg](../../latest/shots/18_018.jpg)

### 64. Кнопка «Obtenir le pack gratuit →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_019.jpg](../../latest/shots/18_019.jpg)

### 65. Круглая кнопка-значок «Une question ? Demande ici» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/fr/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/fr/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_020.jpg](../../latest/shots/18_020.jpg)

### 66. Кнопка «Obter o pacote grátis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_021.jpg](../../latest/shots/18_021.jpg)

### 67. Круглая кнопка-значок «Dúvidas? Pergunte aqui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/pt/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/pt/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_022.jpg](../../latest/shots/18_022.jpg)

### 68. Кнопка «Obtener el pack gratis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_023.jpg](../../latest/shots/18_023.jpg)

### 69. Круглая кнопка-значок «¿Dudas? Pregunta aquí» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/es/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/es/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_024.jpg](../../latest/shots/18_024.jpg)

### 70. Кнопка «Ottieni il pacchetto gratis →» скруглена не так, как остальные
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Скругление углов 8–12px
- **На деле:** Радиус 5px
- **Скриншот:** [shots/18_025.jpg](../../latest/shots/18_025.jpg)

### 71. Круглая кнопка-значок «Domande? Chiedi qui» — нужна ли квадратная со скруглёнными углами?
- **Где:** https://solid-sound.net/it/ (компьютер, телефон)
- **Шаги:** 1) Открыть https://solid-sound.net/it/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/18_026.jpg](../../latest/shots/18_026.jpg)

### 72. Круглая кнопка-значок «без текста» — нужна ли квадратная со скруглёнными углами? 🆕
- **Где:** https://solid-sound.net/en/store-manager/ (компьютер)
- **Шаги:** 1) Открыть https://solid-sound.net/en/store-manager/
- **Ожидалось:** Квадратная кнопка со скруглёнными углами 8–12px
- **На деле:** Круг (радиус 50px, размер 56px)
- **Скриншот:** [shots/19_008.jpg](../../latest/shots/19_008.jpg)

## ✅ Исправлено с прошлой проверки (28)

- Нет заголовка страницы (title) или он слишком длинный/короткий
- Кнопка или ссылка без названия (только значок)
- После нажатия «Previous examples» — ошибка в коде
- Ошибка в коде страницы (JavaScript)
- «Cookie settings» нельзя нажать — сверху лежит другой элемент
- Меню на UA (компьютер) отличается от английского: пункты ведут не туда
- Меню на UA (телефон) отличается от английского: пункты ведут не туда
- Меню на DE (компьютер) отличается от английского: пункты ведут не туда
- Меню на DE (телефон) отличается от английского: пункты ведут не туда
- Меню на FR (компьютер) отличается от английского: пункты ведут не туда
- Меню на FR (телефон) отличается от английского: пункты ведут не туда
- Меню на PT (компьютер) отличается от английского: пункты ведут не туда
- Меню на PT (телефон) отличается от английского: пункты ведут не туда
- Меню на ES (компьютер) отличается от английского: пункты ведут не туда
- Меню на ES (телефон) отличается от английского: пункты ведут не туда
- Меню на IT (компьютер) отличается от английского: пункты ведут не туда
- Меню на IT (телефон) отличается от английского: пункты ведут не туда
- Поиск по слову «techno» ничего не находит
- Кнопка «Mixing» круглая («таблетка»), а не в стиле сайта
- Кнопка «Release Ready» круглая («таблетка»), а не в стиле сайта
- Кнопка «Mastering» круглая («таблетка»), а не в стиле сайта
- Кнопка «Remix» круглая («таблетка»), а не в стиле сайта
- Кнопка «Remake» круглая («таблетка»), а не в стиле сайта
- Кнопка «Ghost producing» круглая («таблетка»), а не в стиле сайта
- Кнопка «Co-producing» круглая («таблетка»), а не в стиле сайта
- Кнопка «Post production» круглая («таблетка»), а не в стиле сайта
- Кнопка «Video» ничего не делает
- Кнопка «Expand All Details» скруглена не так, как остальные

## Не удалось проверить (1)

- Звук файлов не проверен: ffmpeg не установлен

