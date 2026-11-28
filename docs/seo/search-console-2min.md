# Search Console — 2 минуты (для владельца, решение №8)

Зачем: без Search Console мы не видим, по каким запросам нас находят в Google, и не можем попросить Google быстрее обойти новые страницы.

## А. Сейчас, 2 минуты (делаете вы, ничего не передаёте)
1. Откройте **search.google.com/search-console** → слева выберите сайт **solid-sound.net**.
2. Слева **«Файлы Sitemap»** → в поле впишите `wp-sitemap.xml` → **«Отправить»**.
3. Вверху в строку поиска вставьте по очереди адреса и нажмите **«Запросить индексирование»** у каждого:
   - `https://solid-sound.net/en/`
   - `https://solid-sound.net/en/all-products/`
   - `https://solid-sound.net/en/services-prices/`
   - `https://solid-sound.net/de/services-prices/`
   - `https://solid-sound.net/product/pitch/`

Готово. Напишите «Задачам» одно слово: «отправил».

## Б. Потом, ~10 минут — чтобы роботы сами читали запросы каждую неделю
По шагам: `docs/analytics-keys-howto.md`, раздел 1 (сервисный аккаунт `stats-robot`, ключ → секрет GitHub `GOOGLE_SA_JSON`).
Затем в Search Console: **Настройки → Пользователи и разрешения → Добавить пользователя** → адрес `stats-robot@…iam.gserviceaccount.com` → **«Полный»**.
И секрет `GSC_SITE` = `sc-domain:solid-sound.net` (или `https://solid-sound.net/` — как сайт добавлен в Search Console).
