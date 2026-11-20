# Передача: облачная сессия «главная + авторы + звук» (A), 20.11.2026

Сделано и выложено (выкладки 186–250), всё в `plugin/solid-sound-custom/src/`:

- **Главная:** вкладка «Free» первая; блок «Why producers work with us» (4 преимущества + плеер правок) как был; под ним «Our advantages» — 14 плиток со страницы Why (продюсеры → авторы → Learn & connect), `modules/home-wow.php` (`why()`, `WHY` из `ss_why_items()` в `get-heard.php`). Слайдер: вкладка Video компактнее (516 px).
- **Форум:** блок «Stuck? Start here» (`modules/forum-help.php`) — работает.
- **Add Product в 6 шагов** (`modules/files-first.php`, выкл `?ss_ff4=0`): 1 файлы → 2 тип/DAW/версия → 3 детали звука → 4 обложка (кнопка «Upload your cover» + ИИ-обложка) → 5 название и описание → 6 цена и отправка; сверху полоса шагов с галочками; шаги 2–6 открываются после файлов или «Fill it in by hand». Номера шагов — `::before` (span прятал чужой стиль).
- **Автозаполнение без подтверждения:** BPM/длина из демо (`custom.php` #ss-az), тональность (`modules/key-detect.php`), данные из ZIP Ableton (`creator-autofill.php`, + длина из аранжировки). Проект Ableton главнее демо (`window.__ssAlsBpm`, `__ssAlsLen`).
- **ИИ-описание без выдумок** (`modules/ai-creator.php`): ИИ пишет только текст (llama-3.3-70b, запасная 8b), «What's inside» собирается из данных (`ss_aic_inside`), выдуманные числа/тональности ловит `ss_aic_invented` → переписать → вырезать.
- **Свой анализатор звука** `src/audio/ss-audio.js` (ассет `ss-audio`): `keyModel` (обученная модель, 4 полосы HPCP, 69,7 % MIREX на 603 незнакомых треках GiantSteps; было 53,5 %), `tempo` (наложение долей + целый BPM, приоритет 140±1 окт — 65 % точно vs ~20 %), `loudness` (LUFS), `fingerprint` (копии), `hpcp`, `vote`. Форма: тональность одной строкой без «not sure» (владелец: «без неуверенности»).
- **Авторам:** «Where listeners stop» на обзоре (`listen-depth.php`, плеер даёт `window.ssPlayer`); «Record your project» на Products (`project-rec.php`, видео 9:16 с экрана DAW); защита от копий (`copy-guard.php`, Tools → Fingerprints, робот собрал 8/8); письма покупателям — сделаны, **выключены** (владелец «убери пока»).
- Intricate (товар 340) — тональность исправлена на **G**.

## В работе прямо сейчас
**Автоподбор на GitHub** (владелец: «если делают роботы — делай, пока не получится»): `.github/workflows/autotune.yml`, запуск правкой `.github/autotune.txt`, скрипт `tools/keydet/autotune.js`. Итог → `checks/keydet/autotune.md`, `best-key-model.json`, `best-tempo.json`.

**Когда закончится — сделать:**
1. `git pull`, прочитать `checks/keydet/autotune.md` (строки **Best key** / **Best tempo**).
2. Если Best key выше 69,7 %: заменить объект `var KM = {...}` в `src/audio/ss-audio.js` на `{"blocks":…,"W":…,"b":…}` из `best-key-model.json` (поле `log` всегда 1, `test` не нужен); комментарий над KM обновить цифрами.
3. Если Best tempo выше 65 %: поставить его `center`/`sd`/`kick` значениями по умолчанию в `tempo()` (строка `var center = opt.center || 140, sd = opt.sd || 1`, `opt.kick || 2`).
4. `node tools/build.mjs`, проверка `node tools/keydet/browser.js <mp3>`, коммит, строка в `.github/deploy.txt`, push, написать владельцу цифры (по-русски).
5. Если ниже цели (75 % тональность) — следующий круг: новые блоки в `POOL`/`COMBOS` в autotune.js (например HPCP по 3 частям трека, 24 бина баса, скрытый слой), правка `.github/autotune.txt`.

Локально (облачное окружение) есть доступ к www.cp.jku.at (базы GiantSteps); скретчпад сессии мог очиститься.

## Не начато / решения владельца
- Cloudflare (R2, Turnstile, аналитика) — владелец токен не дал, не делать.
- Акции и наборы — владелец: «не надо».
- Переводы — отложены.
