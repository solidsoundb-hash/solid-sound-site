## 🍪 Cookie и GDPR: новый посетитель из ЕС

Страницы: /en/, /product/infinity/, /en/all-products/. Отслеживаются запросы, которые отправляют данные (Meta, Google, TikTok, Hotjar, Clarity, LinkedIn).

| | Проверка | Итог |
|---|---|---|
| ✅ | Баннер согласия показывается новому посетителю | да |
| ✅ | До выбора данные не уходят | ничего не уходит (скрипты грузятся: Google tag (скрипт) — это допустимо, если они ждут согласия; лучше грузить после) |
| ✅ | «Reject non-essential» — ничего не уходит | да |
| ✅ | «Accept all» — пиксель Meta работает | уходят: Brevo, Meta Pixel, Google Analytics |
| ✅ | «Cookie settings» в подвале снова открывает выбор | да |
| 🟡 | Google Analytics до согласия | шлёт обезличенные сигналы без cookie (gcs=G100) — так работает режим согласия Google, в ЕС обычно допустимо; строже — грузить GA только после «Accept» |
| 🟡 | Сторонние cookie до выбора | _fbp (.solid-sound.net), sbjs_migrations (.solid-sound.net), sbjs_current_add (.solid-sound.net), sbjs_first_add (.solid-sound.net), sbjs_current (.solid-sound.net), sbjs_first (.solid-sound.net), sbjs_udata (.solid-sound.net), sbjs_session (.solid-sound.net) |
