# Боты Solid Sound — что делают и как подключить

## Главный робот (раз в неделю, понедельник 14:17 Париж)
Робот GitHub «Main robot» (`.github/workflows/main-robot.yml`) по очереди запускает всех ботов, потом
**надзиратель** (`tools/bots/supervisor.js`) проверяет каждого и пишет `reports/bots-health/latest.md`:
- состояние каждого бота (отработал ли, когда, сколько проверил, ошибки, ждёт ли доступа);
- динамика по неделям (`reports/bots-health/history.csv`): баги новые/исправленные/висящие, скорость, Google,
  посетители, заказы, товары с замечаниями, плагины;
- **план развития** ботов и сайта.

В понедельник 22:05 (Париж) — сразу после сброса недельного лимита Claude — **сессия Claude (Sonnet)** пишет сводку `docs/robots-summary.md` (для владельца и контент-фабрики),
а по чётным неделям ещё и развивает ботов (новые проверки, исключения для ложных тревог, починка сбоев).
В субботу — контент-фабрика владельца (Claude, на ПК HOME-PC). Порядок: роботы (пн днём) → «глаза» и сводка Claude (пн 22:05) → контент-фабрика (сб). Вручную запустить всё: строка в `.github/main-robot.txt`.

| Бот | Файл | Отчёт | Что нужно |
|---|---|---|---|
| Тестировщик (баги) | `tools/qa/qa.js` | `qa/latest/report.md` | — (логины тестовых аккаунтов уже в секретах) |
| Скорость | `tools/speed/speed.js` | `speed/latest.md` | желательно `PSI_KEY` |
| Снимки сайта | `tools/site-check.js` | `checks/latest/report.md` | — |
| Google (Search Console) | `tools/bots/seo.js` | `reports/weekly/latest.md` | `GOOGLE_SA_JSON`, `GSC_SITE` |
| Продажи и посетители | `tools/bots/analytics.js` | там же | `WC_KEY`, `WC_SECRET`, `GA4_PROPERTY_ID` |
| Товары для Google | `tools/bots/products.js` | там же | — |
| Конкуренты | `tools/bots/competitors.js` (+ `competitors.json`) | там же | — |
| Акции и поводы | `tools/bots/promos.js` | там же | — |
| Безопасность плагинов | `tools/bots/security.js` (+ `plugins.json`) | там же | желательно `WPSCAN_TOKEN` |

Все ключи кладутся **только** в секреты GitHub (никогда в чат):
https://github.com/solidsoundb-hash/solid-sound-site/settings/secrets/actions → **New repository secret**.

## Шаг «Google» (Search Console + Analytics) — один «служебный аккаунт» на оба
1. https://console.cloud.google.com → проект **Solid Sound**.
2. «APIs & Services» → «Library» → включить **Google Search Console API** и **Google Analytics Data API**.
3. «IAM & Admin» → «Service Accounts» → **Create service account** (имя `solid-sound-bots`) → Done.
4. Открыть его → вкладка **Keys** → **Add key → Create new key → JSON** — скачается файл.
5. Секрет GitHub `GOOGLE_SA_JSON` = всё содержимое этого файла (открыть блокнотом, скопировать целиком).
6. Скопировать e-mail аккаунта (вида `solid-sound-bots@….iam.gserviceaccount.com`):
   - Search Console (https://search.google.com/search-console) → Настройки → Пользователи → **Добавить** → этот
     e-mail, права «Ограниченные»;
   - Google Analytics → Администратор → Доступ к ресурсу → **+** → этот e-mail, роль «Читатель».
7. Секрет `GSC_SITE`: `sc-domain:solid-sound.net` (если ресурс «Домен») или `https://solid-sound.net/` (если «Префикс URL»).
8. Секрет `GA4_PROPERTY_ID`: Analytics → Администратор → Сведения о ресурсе → «Идентификатор ресурса» (только цифры).

## Шаг «Магазин» (заказы и выручка, только чтение)
1. Админка WordPress → WooCommerce → Настройки → Дополнительно → **REST API** → **Добавить ключ**.
2. Описание `bots`, пользователь — вы, права **Чтение** (Read) → Создать.
3. Секреты GitHub: `WC_KEY` = Consumer key (ck_…), `WC_SECRET` = Consumer secret (cs_…).

## Шаг «Скорость» (по желанию)
Google Cloud, проект Solid Sound → «APIs & Services» → включить **PageSpeed Insights API** → «Credentials» →
**Create credentials → API key** → секрет `PSI_KEY`. Без ключа работает, но Google иногда ограничивает.

## Шаг «Безопасность» (по желанию)
Бесплатная регистрация https://wpscan.com/register → в профиле «API Token» → секрет `WPSCAN_TOKEN`.

Ничего из этого не платное. После добавления секретов — написать Claude «запусти главного робота».
