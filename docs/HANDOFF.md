# Передача проекта другому Claude Code — Solid Sound (solid-sound.net)

Уроки и грабли — `docs/lessons.md` (читать перед работой).
Состояние на 11.11.2026, вечер. Прочитай этот файл целиком, потом `CLAUDE.md`, `docs/owner-preferences.md`
(пожелания владельца — читать перед КАЖДОЙ задачей и дописывать новые), `docs/TZ-claude-code-speed.md`.

## 1. Кто и как
- Владелец — Grenor (Sølid Søund Studio). Не программист: говорить по-русски, просто, без жаргона,
  ссылки давать полными (`https://solid-sound.net/...`). Тексты для клиентов сайта — по-английски.
- Роль: эксперт по сайтам, SEO и продажам. Делать «как в больших компаниях»: git, проверки, откат.
- Владелец видит только чат и картинки. Скриншоты показывать через инструмент отправки файлов (SendUserFile),
  а не «прочитав» их себе.

## 2. Сайт и хостинг
- WordPress + WooCommerce + тема Impreza (WPBakery) + Polylang (7 языков) + WCFM (маркетплейс) + Tidio + AI Engine.
- Хостинг Украина, панель https://adm.tools (аккаунт `cinearts`), сайт лежит в `solid-sound.net/www/`.
  Сервер nginx, mod_pagespeed, brotli, HTTP/2. Redis/OPcache — НЕ трогать (решение владельца).
- Защита хостинга от ботов часто отвечает **429** или «Захищена сторінка». Для curl ставь браузерный User-Agent
  и паузы 15–20 с; в Playwright — троттлинг запросов (см. `tools/compare.js`, `tools/site-check.js`).
- Кэш страниц: WP Super Cache установлен, **сейчас выключен владельцем** на время разработки. Его настройки
  (исключения cart/checkout/my-account/store-manager/client-dashboard/vendor-register/wp-json, не кэшировать
  вошедших) сохранены в `live/wp-content/wp-cache-config.php`. Включить в конце работ.

## 3. Доступы (ничего из секретов в чат и в код не писать!)
| Что | Где | Как пользоваться |
|---|---|---|
| Репозиторий | https://github.com/solidsoundb-hash/solid-sound-site (private) | обычный git, ветка `main` |
| WordPress | MCP-коннектор `www_solid-sound_net` (AI Engine), права владельца | инструменты `mcp__www_solid-sound_net__*`: читать/править посты, опции и т. д. |
| FTP | секреты GitHub `FTP_HOST`, `FTP_USER`, `FTP_PASS` (пользователь `cinearts_claude`) | только через роботов GitHub Actions — из облачной сессии FTP/SSH закрыты |
| Тест-аккаунты | секреты `TEST_USER`/`TEST_PASS` (Test), `TEST_ADMIN_USER`/`TEST_ADMIN_PASS` (Claude_Code, админ) | использует робот `site-check` |
| Сеть облачной сессии | среда Claude Code `solid-sound-site`: разрешены solid-sound.net, www.solid-sound.net, cdn.jsdelivr.net, fonts.googleapis.com, fonts.gstatic.com | если новая сессия не видит сайт — выбрать эту среду или добавить хосты |
| Google Sign-In | Client ID `152337347722-trdd5avbmpcqplnrd3un7p5g78vm18ko.apps.googleusercontent.com`, проект Google Cloud «Solid Sound» у владельца | уже в `modules/social-login.php` |
| Разрешения Claude Code | `.claude/settings.json` (создан владельцем): git add/commit/push/pull/fetch и чтение/правка WP без вопросов | работает с новой сессии |

Безопасность: в панели FTP ограничение по IP **выключено** (нужно роботам). В конце работ напомнить владельцу
включить обратно / удалить FTP-пользователя `cinearts_claude`.

## 4. Как устроен код
- `plugin/solid-sound-custom/` — плагин на сайте (вся наша логика).
  - `solid-sound-custom.php` — загрузчик: переключатель `SS_BUILD_DEFAULT` (сейчас **true** = все видят
    оптимизированную сборку); `?ss_v2=0` показывает замороженный оригинал, `?ss_v2=1` — сборку; `ss_asset()`
    печатает кэшируемые файлы вместо инлайн-кода.
  - `includes/custom.php` — замороженный оригинал (не править).
  - `src/` — РАБОЧИЕ исходники:
    - `custom.php` — основной код (большой, старый) + подключение модулей в конце;
    - `modules/account.php` — «My account» = клиентский дашборд (рендер на сервере), страница 34 без «ADD PRODUCT»;
    - `modules/home.php` — главная как кэшируемые файлы; `modules/all-products.php` — hero на «Все товары» с сервера;
    - `modules/ranking.php` — рейтинг качества (WooCommerce → Ranking), «Best products» на главной;
    - `modules/social-login.php` — вход через Google (персонал — только пароль);
    - `ss-global.js` — общее меню всех страниц (как на главной), языки текстом, меню аккаунта по ролям;
    - `account/` — стили и JS кабинета; `home/` — приложение главной: `app.template.html` + `runtime.js` +
      `patches.js` (все правки текста главной применяются ПРИ СБОРКЕ), `home.js` (поведение), `parts/assistant.*`
      («помощник покупателя» на первом экране), `vendor/` (React 18.3.1 у себя).
  - `build/` — результат сборки, коммитится. Собирать: `node tools/build.mjs` (нужен `npm i --no-save esbuild@0.24.2`).
- `tools/` — `build.mjs`, `extract-assets.php`, `ftp_sync.py` (FTP на чистом Python), `compare.js`, `site-check.js`.
- `live/` — снимок файлов с сервера (робот ftp-pull), `live/plugins/solid-sound-custom/exports/` — код из базы.
- `docs/` — ТЗ, пожелания владельца, анализ конкурентов, боли клиентов, линейка предложений, рейтинг, правила студии.
- `mockups/` — макеты и скриншоты.

## 5. Роботы (GitHub Actions) — как выкладывать
- **Выкладка:** допиши строку в `.github/deploy.txt` и запушь в `main` → `deploy.yml`: проверка, что `build/`
  актуален + `php -l`, копия плагина с сервера (артефакт), заливка (build → includes → backup → загрузчик),
  очистка кэша WP Super Cache, проверка 4 страниц, при PHP-фатале **автооткат**. Отчёт — `deploy-log/last.txt`
  (ждать коммит с `commit <hash>`).
- **Забрать файлы с сервера:** строка в `.github/ftp-pull.txt` → `live/`.
- **Проверка сайта со скриншотами (гость, Test, админ, телефон + ПК):** строка в `.github/check.txt`
  (и автоматически после каждой выкладки) → `checks/latest/report.md` + jpg.
- API GitHub Actions из облачной сессии закрыт — ход роботов не видно, только их отчёты в репозитории.
- Перед выкладкой коротко скажи владельцу, что выкладываешь. Система безопасности может требовать его
  «выкладывай» в чате — тогда попроси.

## 6. Важные грабли
- Не добавлять код вставками в страницы / Theme Options — только в плагин (src → build → deploy).
- Главная — React-приложение из шаблона; менять её только через `src/home/patches.js` / `home.js` / `parts/`.
- После правок `src/` всегда `node tools/build.mjs`, иначе робот откажется выкладывать.
- Счётчики рейтинга анонимные; не хранить персональные данные.
- Менять `CLAUDE.md` и `.claude/settings.json` — только с явного «да» владельца.

## 7. Что сделано (11.11.2026)
Плагин в git + сборка + выкладка с откатом; меню как на главной на всех страницах, языки текстом;
вход: «Sign in» с меню (Log in / Create account / Become a vendor), после входа «My account» с меню;
один аккаунт — две роли (клиент + продавец, Producer space / Start selling); «My account» = дашборд;
вход через Google; рейтинг качества + «Best products»; главная: без старой страницы снизу, без повторной
загрузки, React у себя, 0 ошибок, скрытое боковое меню, навигация с прокруткой и подсветкой разделов,
«помощник покупателя» вместо слайдера; «Все товары» — hero с сервера; убраны таймеры-«проверялки» и WebGL-рябь
(рябь сохранена как заготовка, `docs/components.md`).

## 8. Что дальше (план, согласовать с владельцем)
1. Фильтры «помощника» → переносить галочки на «Все товары»; горизонтальный скролл на «Все товары» (ПК).
2. Создать товары/цены из `docs/offers.md` после утверждения (Producer Kit, пакеты студии, пост-продакшн).
3. Free pack через Google (ждём: строка согласия или галочка).
4. Студия: ревизии с комментариями по секундам (таймкод автоматически, MP3 только последней версии,
   удаление через 14 дней по кнопке владельца), панель «Studio projects», роль «Sound engineer»
   (см. `docs/owner-preferences.md`, `docs/studio-rules-uk.md`).
5. Вход по ссылке на почту, регистрация в одно поле.
6. Скорость: первый экран главной сразу (prerender), шрифт Exo 2 у себя, картинки WebP, калькулятор без «прыжка»,
   затем включить WP Super Cache, Lighthouse «до/после» (база — `reports/baseline-2026-11-11.md`).
7. SEO/AI-поиск: FAQ, schema.org, переводы; письма Brevo после Free pack; Cloudflare в конце.
8. Напомнить владельцу: включить ограничение FTP по IP в конце; хостинг оплачен до 11.12.2026.
