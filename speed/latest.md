# Скорость сайта — 2026-11-21 04:37 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (телефон): оценка упала 25 → 19
- /en/ (телефон): страница «прыгает» при загрузке (CLS 0.245, норма до 0.1)
- /en/all-products/ (телефон): страница «прыгает» при загрузке (CLS 0.189, норма до 0.1)
- /product/infinity/ (телефон): оценка упала 52 → 47
- /en/wiki/ (телефон): главный блок появляется позже 2.7 → 3.4 с

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **19** (-6) | 7.3 с | 3.6 с | 3721 мс | 0.245 | 1235 КБ | 66 | 1.4 с |
| /en/ | компьютер | **72** (+2) | 2.9 с | 1.5 с | 96 мс | 0.013 | 1239 КБ | 71 | 1.1 с |
| /en/all-products/ | телефон | **44** (+8) | 3.8 с | 2.8 с | 1249 мс | 0.189 | 1418 КБ | 87 | 1.4 с |
| /en/all-products/ | компьютер | **0** (+0) | 0.0 с | 1.5 с | 0 мс | 0.053 | 1491 КБ | 92 | 1.5 с |
| /product/infinity/ | телефон | **47** (-5) | 9.4 с | 2.9 с | 588 мс | 0.072 | 4936 КБ | 187 | 1.2 с |
| /product/infinity/ | компьютер | **75** (+1) | 2.6 с | 1.5 с | 0 мс | 0.032 | 4915 КБ | 197 | 1.3 с |
| /en/studio-calculator/ | телефон | **85** (+2) | 2.8 с | 2.5 с | 196 мс | 0 | 1270 КБ | 64 | 1.1 с |
| /en/studio-calculator/ | компьютер | **86** (-1) | 1.5 с | 1.3 с | 0 мс | 0.078 | 1270 КБ | 64 | 1.1 с |
| /en/wiki/ | телефон | **82** (-3) | 3.4 с | 2.5 с | 145 мс | 0 | 753 КБ | 56 | 1.3 с |
| /en/wiki/ | компьютер | **87** (+2) | 1.5 с | 1.3 с | 0 мс | 0.002 | 753 КБ | 56 | 1.3 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1398ms · Reduce unused CSS −1130ms · Serve images in next-gen formats −780ms · Properly size images −470ms · Reduce unused JavaScript −310ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.d4ad1d5026.js.pagespeed.ce.LL 50KB · /en/ 49KB
- скрипты грузят процессор: /en/ 4083ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 2603ms · Unattributable 2057ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1249ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 1244ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 439ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1150ms · Serve images in next-gen formats −420ms · Reduce unused CSS −380ms · Reduce unused JavaScript −200ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.d4ad1d5026.js.pagespeed.ce.LL 50KB · /en/ 49KB
- скрипты грузят процессор: /en/ 447ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 191ms · Unattributable 161ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 117ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 104ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 93ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1404ms · Defer offscreen images −890ms · Reduce unused CSS −620ms · Serve images in next-gen formats −310ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 3340ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1425ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1050ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.81b37f386f.js.pagespeed.ce.P 668ms · Unattributable 570ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 224ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1479ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 1126ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.81b37f386f.js.pagespeed.ce.P 333ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 293ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 247ms · Unattributable 166ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 70ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1169ms · Reduce unused CSS −1090ms · Properly size images −780ms · Defer offscreen images −630ms · Reduce unused JavaScript −630ms · Serve images in next-gen formats −460ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/9c2cc8631eed87c634b3dd66c0ad8595f862861b603be9af024142d59 709KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 652KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 381KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 292KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-a4f0bebf1486634d6991b242fd89f3a8.js 184KB
- скрипты грузят процессор: /product/infinity/ 2178ms · Unattributable 769ms · https://js.stripe.com/v3/?ver=3.0 745ms · /wp-content/plugins/woocommerce-payments/dist/express-checkout.js?ver=11.2.1 526ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.81b37f386f.js.pagespeed.ce.P 400ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 282ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1266ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/9c2cc8631eed87c634b3dd66c0ad8595f862861b603be9af024142d59 708KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 652KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 381KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 269KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-a4f0bebf1486634d6991b242fd89f3a8.js 184KB
- скрипты грузят процессор: /product/infinity/ 569ms · Unattributable 176ms · https://js.stripe.com/v3/?ver=3.0 151ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 77ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 74ms · /wp-content/plugins/woocommerce-payments/dist/express-checkout.js?ver=11.2.1 55ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1087ms · Reduce unused CSS −310ms · Defer offscreen images −270ms · Serve images in next-gen formats −270ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 780ms · Unattributable 647ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 401ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 290ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 258ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.81b37f386f.js.pagespeed.ce.P 163ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1085ms · Reduce unused CSS −190ms · Serve images in next-gen formats −190ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 232ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 78ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 75ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 72ms · Unattributable 69ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1262ms · Reduce unused CSS −580ms · Defer offscreen images −270ms · Serve images in next-gen formats −270ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 763ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 317ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 265ms · Unattributable 240ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.81b37f386f.js.pagespeed.ce.P 133ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 128ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1308ms · Reduce unused CSS −190ms · Serve images in next-gen formats −190ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 209ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 69ms · Unattributable 64ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 54ms
