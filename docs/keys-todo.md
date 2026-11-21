# Ключи и секреты: что нужно (на завтра, 22.11.2026)

**Главное правило:** ключи и пароли НЕ пишем в файлы и в чат — репозиторий публичный. Только в секреты GitHub:
GitHub → репозиторий `solid-sound-site` → **Settings → Secrets and variables → Actions → New repository secret**.
Название секрета — точно как в таблице (большими буквами). После добавления скажите Claude «ключ добавил» — он запустит робота и проверит.

Как узнать, что уже есть: та же страница Settings → Secrets → Actions показывает названия (значения не видны).

## 1. Не хватает — роботы ждут (⏸)

| Секрет | Для чего | Где взять | Цена | Важность |
|---|---|---|---|---|
| `GOOGLE_SA_JSON` | Аналитика (визиты, откуда приходят, где уходят) и Search Console (что ищут в Google) — роботы `tools/bots/analytics.js`, `seo.js` | Google Cloud → сервисный аккаунт → ключ JSON. Пошагово: `docs/analytics-keys-howto.md` (там секрет назван `GA4_KEY` — **называть `GOOGLE_SA_JSON`**, так его читает код) | бесплатно | 🔴 высокая |
| `GA4_PROPERTY_ID` | номер ресурса Google Analytics | `559451411` (уже известен) | — | 🔴 вместе с ключом выше |
| `GSC_SITE` | какой сайт в Search Console | `sc-domain:solid-sound.net` или `https://solid-sound.net/` — как добавлен в Search Console; сервисный аккаунт выше добавить туда пользователем (чтение) | — | 🔴 вместе с ключом выше |
| `WC_KEY` + `WC_SECRET` | заказы и выручка за неделю в отчёте | WordPress → WooCommerce → Настройки → Дополнительно → REST API → «Добавить ключ», права **Только чтение** | бесплатно | 🟠 |
| `BREVO_API_KEY` | доставка писем: сколько дошло, сколько в спам/отскоки (робот `emails`) | Brevo → правый верх → SMTP & API → **API keys** → Generate | бесплатно | 🟠 |
| `WPSCAN_TOKEN` | известные дыры в плагинах (робот безопасности) | wpscan.com → регистрация → API token (25 проверок в день бесплатно) | бесплатно | 🟠 |
| `PSI_KEY` | скорость через Google PageSpeed (точнее, чем наш Lighthouse на GitHub) | Google Cloud (тот же проект) → «API и сервисы» → включить PageSpeed Insights API → «Учётные данные» → ключ API | бесплатно | 🟡 |
| `META_TOKEN` | Facebook/Instagram/реклама — робот ещё не сделан, будет после ключа | пошагово: `docs/analytics-keys-howto.md`, раздел 2 | бесплатно | 🟡 |

## 2. Уже используются (проверить, что есть и работают)

| Секрет | Кто пользуется |
|---|---|
| `FTP_HOST`, `FTP_USER`, `FTP_PASS` | выкладка на сайт (deploy), забор файлов, робот «Сервер» (только чтение) |
| `TEST_ADMIN_USER` / `TEST_ADMIN_PASS` | 10 роботов: рой, site check, кабинет автора, Add Product… |
| `TEST_VENDOR_USER` / `TEST_VENDOR_PASS` | проверки кабинета продавца |
| `TEST_USER` / `TEST_PASS` | рой и site check как обычный покупатель |
| `WMDM_USER` / `WMDM_PASS` | робот «WMDM look» (`wmdm-look.yml`) |

## 3. Навести порядок (лишнее)
- Для одного и того же входа в `qa-authors.yml` и `film.yml` перебираются 8 вариантов названий: `SOLID_SOUND_USERNAME`, `SOLID_SOUND_USER`, `SOLID_SOUND_LOGIN`, `SOLIDSOUND_LOGIN` и `SOLID_SOUND_PASSWORD`, `SOLID_SOUND_PASSWORD_`, `SOLID_SOUND_PASS`, `SOLIDSOUND_PASSWORD`. Завтра посмотреть, какие реально заведены, оставить одну пару (лучше `TEST_ADMIN_*`), остальные удалить, роботов поправить.
- В инструкции `docs/analytics-keys-howto.md` секрет назван `GA4_KEY`, а код читает `GOOGLE_SA_JSON` — исправить инструкцию (или завести под правильным именем).

## 4. Не секреты GitHub, но ждут владельца (из прошлых отчётов)
- **OPcache** — включить в adm.tools (ускорит сервер, сейчас выключен).
- **Ключ кэша WP Super Cache** — сменить (старый засветился в публичном репозитории; или сказать Claude «меняй»), потом убрать строку из `.gitleaksignore`.
- **Тестовые пароли** — если `TEST_*` пароли где-то светились в чатах/скринах, сменить на сайте и в секретах.

## Порядок на завтра (предложение)
1. Settings → Secrets → Actions: посмотреть список, что уже есть (раздел 2–3).
2. Google: один сервисный аккаунт → `GOOGLE_SA_JSON` + `GA4_PROPERTY_ID` + `GSC_SITE` (+ заодно `PSI_KEY`) — ~15 мин.
3. WooCommerce ключ «только чтение» → `WC_KEY`, `WC_SECRET` — 3 мин.
4. Brevo → `BREVO_API_KEY` — 2 мин.
5. WPScan → `WPSCAN_TOKEN` — 3 мин.
6. Сказать «ключи добавил» → Claude запускает роботов и показывает первые отчёты.
