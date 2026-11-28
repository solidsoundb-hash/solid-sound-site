# Скорость сайта — 2026-11-28 02:41 UTC

После выкладки: 2026-11-28 02:32:27 Тестировщик (просьба Аналитика через «Задачи»): счётчик прослушиваний — только люди + по дням; «в корзину после демо»; журнал «искали и не н (медиана 3 замеров)

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /product/infinity/ (телефон): оценка упала 60 → 55
- /en/all-products/ (телефон): оценка упала 54 → 40
- /en/all-products/ (телефон): главный блок появляется позже 4.8 → 6.4 с

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /product/infinity/ | телефон | **55** (-5) | 10.4 с | 2.6 с | 434 мс | 0 | 5070 КБ | 212 | 1.1 с |
| /en/all-products/ | телефон | **40** (-14) | 6.4 с | 3.2 с | 1223 мс | 0.036 | 1289 КБ | 86 | 2.0 с |

## Почему медленно (Lighthouse)

### /product/infinity/ — mobile
- можно выиграть: Reduce unused JavaScript −1480ms · Reduce initial server response time −1101ms · Reduce unused CSS −1020ms · Properly size images −730ms · Serve images in next-gen formats −440ms · Defer offscreen images −290ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/3dc66794c8db612c526fd3fc82b396585bf990a83b4f2b047cbb2e891 685KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/a7305732738ccf3b7862eab7fe6d8f7b136a48f4/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 219KB
- скрипты грузят процессор: /product/infinity/ 1994ms · https://js.stripe.com/v3/?ver=3.0 810ms · Unattributable 717ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 360ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 359ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 357ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1969ms · Defer offscreen images −640ms · Reduce unused CSS −580ms · Serve images in next-gen formats −350ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 56KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1790ms · /en/all-products/ 1524ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1522ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1137ms · Unattributable 512ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 491ms
