# Формат статьи, версия 0 (Маркетолог, 28.11.2026) — гипотеза, проверяем на 3 статьях

Шаг 3 конвейера (`docs/team/content-pipeline.md`). **Честно о данных:** отчётов `reports/analyst/pains.md` и `reports/analyst/content.md` (шаги 1–2) ещё нет. Поэтому v0 опирается на то, что есть сейчас, и будет пересчитана, когда придут цифры content-watch (просмотры, комментарии) и наши (шаг 9).

## На чём основано
| Факт | Источник |
|---|---|
| Из 160 показов в Google 3 клика; лучше всего нас показывают по **запросам о цене** («vinyl mastering kosten» — 2 место, «mastering kosten» — 10, «professional mastering rates» — 30, «mix and master prices» — 38) | `docs/seo/audit-2026-11-25.md`, Search Console |
| Наша вики «balanced vs unbalanced cables» стоит на 27–48 месте, но **не продаёт** — тема без связи с товаром | там же |
| На первой странице Google по «techno rumble kick ableton» — пошаговые разборы с **номерами шагов в заголовке** («in 10 easy steps», «6 steps») и **аудио/картинками на каждом шаге** (MusicRadar, Attack Magazine «Beat Dissected», Production Music Live, Gearnews «Reading time: 6 min») | поиск Google, 28.11 |
| По «how loud should techno master be» — гайды с **конкретными числами** (−14 LUFS для стриминга, −6…−9 short-term для клуба), длина 6–11 мин чтения | поиск Google, 28.11 (Gearnews, Mastering The Mix, EDMProd, Sweetwater) |
| По «mixing and mastering cost 2026» — статьи **«Real numbers» с таблицей цен по уровням** (AI / онлайн / профи) и годом в заголовке | поиск Google, 28.11 (RoEx, bchillmix, Twine) |
| Люди приходят в основном с телефона? — 35 из 154 сессий; главная аудитория — США | `reports/analyst/stats-daily.md` |

## Формат v0 — «Пошаговый разбор с числами + звук до/после»
- **Заголовок:** результат + число + год/инструмент. Примеры: «How to Make a Techno Rumble Kick in Ableton (6 Steps, Stock Devices)», «How Much Does Mixing and Mastering Cost in 2026? Real Prices».
- **Длина:** 900–1500 слов (6–8 мин). Короче — проигрываем первой странице, длиннее — не дочитывают с телефона.
- **Первый экран (до прокрутки на телефоне 412×780):** 1 фраза «что получишь» + **короткий ответ** (2–3 строки, сразу цифра/рецепт) + аудио-пример «результат». Ответ сразу — так Google и ИИ-поиск берут нас в сниппет.
- **Тело:** 5–8 шагов, у каждого: заголовок H2 с действием → 2–4 предложения → настройки цифрами (Hz, ms, dB, LUFS) → скрин устройства или аудио «до/после» (5–10 с).
- **Таблица** хотя бы одна (цены, настройки, «стриминг vs клуб») — её копируют и на неё ссылаются.
- **Ошибки:** блок «3 common mistakes» (короткий список) — из него же потом карусель.
- **CTA (2 места):** после 3-го шага — мягко («Want the full project? It's in the free starter pack»), в конце — товар/услуга по теме с ценой из сайта (не писать цену вручную, если страница может показать другую валюту — ссылка на товар). Пока оплата выключена — главный CTA на `/en/free/` или `/en/free-demo-feedback/`, второй — товар.
- **Метки:** ссылки из статьи с `?utm_source=wiki&utm_medium=article&utm_campaign=<slug>&utm_content=cta1|cta2`.
- **FAQ в конце:** 3–4 вопроса из «People also ask» (для разметки FAQ — SEO).
- **Чего не делаем:** вступлений «In this article we will…», историй, воды, чужих текстов; имён клиентов.

## Шаблон (копировать в начало черновика)
```
# <Result> in <Tool> (<N> Steps / <Year>)
<1 line: what you get>
**Short answer:** <2–3 lines with the number/recipe>
[audio: result, 10 s]

## Step 1 — <action>
<2–4 sentences> · Settings: <numbers> · [screenshot or audio before/after]
... (5–8 steps; after step 3: soft CTA → free pack)

## 3 common mistakes
- ...

| table |

## Get the project / Get it done for you
<CTA: product or service + link with UTM>

## FAQ
**Q?** A.
```

## Как проверим (шаг 9)
3 статьи → 14 дней → по каждой: показы/клики (Search Console), время на странице и прокрутка (Solid Stats), переходы по CTA (UTM), почты. Порог «формат работает»: ≥ 1 % CTR из поиска и ≥ 3 % переходов на CTA. Не дотянули — меняем ОДНУ вещь (заголовок или первый экран) и смотрим ещё 14 дней.
