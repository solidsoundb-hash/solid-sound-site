# Темы статей со спросом — вклад SEO в шаг 4 конвейера (28.11.2026)

Для «Маркетолога»: соединить с болями «Аналитика» (шаг 1) и форматом (шаг 3) → `docs/marketing/article-plan.md`.

**Как оценён спрос.** Платных инструментов нет, подсказки Google из облака закрыты. ★ — по этому запросу нас уже показывал Google (Search Console, 11–25.11). Остальное — запрос с покупательским намерением из `docs/seo/audit-2026-11-25.md`. Точные частоты появятся, когда владелец даст доступ к Search Console.

| # | Тема (рабочий заголовок EN) | Главный ключ | Спрос | Ведёт на |
|---|---|---|---|---|
| 1 | How Much Do Mixing and Mastering Cost in 2026? | mixing and mastering prices / mastering cost per track | ★ «mixing mastering preise», «professional mastering rates», «mix and master prices», «mastering kosten» (10 место) | /services-prices/, калькулятор |
| 2 | Vinyl Mastering: Cost and What to Prepare | vinyl mastering cost | ★ «vinyl mastering kosten» — 2 место (DE) | мастеринг; **DE-версия первой** |
| 3 | How to Prepare Stems for Mixing (Checklist) | how to prepare stems for mixing | вопрос перед заказом сведения | услуга сведения |
| 4 | Mixing vs Mastering: What You Actually Need | mixing vs mastering | частый вопрос новичков | Mix + Master |
| 5 | How Loud Should a Techno Master Be (LUFS)? | techno mastering lufs | вопрос перед релизом | мастеринг |
| 6 | How to Make Dark Techno in Ableton (Real Project Breakdown) | how to make dark techno ableton | «dark techno ableton template» | Kratos, Intricate, Mutualism |
| 7 | Techno Kick and Bass: How Pros Build the Low End | techno kick and bass ableton | частая боль сведения | Pitch, Kratos + сведение |
| 8 | Techno Arrangement: Structure of a 7-Minute Track | techno arrangement structure | обучающий запрос | шаблоны (длина 6–7 мин) |
| 9 | How to Make Melodic Techno in Ableton | how to make melodic techno | большой жанр | Melodic Techno Presets |
| 10 | Are Ableton Templates Worth It? How to Learn From a Project File | ableton project file techno | сомнение перед покупкой | все шаблоны |
| 11 | Royalty-Free Piano Music for Documentaries | documentary piano music royalty free | ★ «documentary piano» | Time Goes Fast Documentary Piano |
| 12 | Serum Presets for Melodic Techno: How to Tweak Them | serum presets melodic techno | покупательский | Melodic Techno Presets |

Совет SEO: первыми — №1 и №2 (Google нас уже показывает, путь до заказа услуги короче всего) и №6 (ведёт на 3 товара).

## Чек-лист SEO для каждой статьи (шаг 6) — делает «SEO» после выкладки EN

1. **title** ≤ 60 знаков, ключ в начале, без «– Solid Sound» в конце, если длинно. **description** 120–155 знаков, ключ + польза + «Solid Sound».
2. **Один видимый H1** = заголовок статьи (проверка роботом в браузере).
3. **Разметка Article** — ставится сама (`modules/seo.php`) на `/wiki/…`; FAQPage — если в статье есть блок FAQ.
4. **Внутренние ссылки:** 2–3 на товары/услуги из колонки «Ведёт на» (текст ссылки — ключ, не «click here»), 1–2 на соседние статьи вики; со старых статей — ссылка на новую.
5. **Картинки:** alt по смыслу; аудио «до/после» — с подписью.
6. **Переводы DE/FR/ES** — только после проверки EN; у перевода свои title/description на языке страницы.
7. **Проверка:** строка в `.github/seo.txt` → робот `seo-weekly` (новые статьи вики он находит сам по карте сайта).
