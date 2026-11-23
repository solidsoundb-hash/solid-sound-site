# Скорость сайта — 2026-11-23 16:29 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (телефон): главный блок появляется позже 7.0 → 8.4 с
- /en/ (компьютер): оценка упала 78 → 68
- /en/ (компьютер): главный блок появляется позже 2.4 → 2.9 с
- /en/all-products/ (телефон): оценка упала 49 → 39
- /en/all-products/ (телефон): главный блок появляется позже 5.0 → 8.0 с
- /en/all-products/ (компьютер): оценка упала 80 → 71
- /product/infinity/ (телефон): оценка упала 63 → 55
- /en/studio-calculator/ (телефон): оценка упала 90 → 74
- /en/studio-calculator/ (телефон): главный блок появляется позже 2.6 → 4.0 с
- /en/studio-calculator/ (компьютер): оценка упала 90 → 85
- /en/wiki/ (компьютер): главный блок появляется позже 1.3 → 1.7 с

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **35** (-3) | 8.4 с | 2.8 с | 1895 мс | 0.031 | 1163 КБ | 72 | 1.5 с |
| /en/ | компьютер | **68** (-10) | 2.9 с | 1.6 с | 160 мс | 0.004 | 1168 КБ | 77 | 1.5 с |
| /en/all-products/ | телефон | **39** (-10) | 8.0 с | 3.2 с | 1225 мс | 0.036 | 1300 КБ | 92 | 1.8 с |
| /en/all-products/ | компьютер | **71** (-9) | 2.2 с | 1.5 с | 191 мс | 0.017 | 1383 КБ | 97 | 1.8 с |
| /product/infinity/ | телефон | **55** (-8) | 9.4 с | 2.9 с | 372 мс | 0 | 5382 КБ | 220 | 1.6 с |
| /product/infinity/ | компьютер | **73** (-3) | 2.7 с | 1.5 с | 0 мс | 0.018 | 5305 КБ | 226 | 1.7 с |
| /en/studio-calculator/ | телефон | **74** (-16) | 4.0 с | 2.7 с | 149 мс | 0 | 1180 КБ | 67 | 1.5 с |
| /en/studio-calculator/ | компьютер | **85** (-5) | 1.5 с | 1.3 с | 0 мс | 0.078 | 1181 КБ | 67 | 1.4 с |
| /en/wiki/ | телефон | **83** (-2) | 3.4 с | 2.5 с | 110 мс | 0 | 663 КБ | 59 | 1.4 с |
| /en/wiki/ | компьютер | **85** (+0) | 1.7 с | 1.3 с | 0 мс | 0.002 | 663 КБ | 59 | 1.5 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1507ms · Reduce unused CSS −310ms · Reduce unused JavaScript −310ms · Properly size images −160ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 2026ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1552ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 1079ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 756ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 705ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 632ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1464ms · Serve images in next-gen formats −400ms · Reduce unused CSS −200ms · Reduce unused JavaScript −200ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 360ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 334ms · Unattributable 154ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 129ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 99ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 92ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1750ms · Reduce unused CSS −1560ms · Defer offscreen images −1510ms · Serve images in next-gen formats −750ms · Properly size images −600ms · Reduce unused JavaScript −580ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1731ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1617ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1550ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1170ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 641ms · Unattributable 554ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1802ms · Defer offscreen images −220ms · Serve images in next-gen formats −200ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 491ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 451ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 329ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 324ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 307ms · Unattributable 157ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1585ms · Reduce unused CSS −790ms · Properly size images −310ms · Defer offscreen images −160ms · Reduce unused JavaScript −160ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 827KB · https://newassets.hcaptcha.com/c/a4d8bab5a6d640ffc420104fd1bd82e3f1ace50377f1a8efe86ad622b 706KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 454KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 292KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 251KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1878ms · Unattributable 725ms · https://js.stripe.com/v3/?ver=3.0 705ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 391ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 360ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 341ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1720ms · Reduce unused CSS −410ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 766KB · https://newassets.hcaptcha.com/c/a4d8bab5a6d640ffc420104fd1bd82e3f1ace50377f1a8efe86ad622b 705KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 454KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 292KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 251KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 219KB
- скрипты грузят процессор: /product/infinity/ 470ms · https://js.stripe.com/v3/?ver=3.0 178ms · Unattributable 159ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 84ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 77ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 76ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1517ms · Reduce unused CSS −630ms · Defer offscreen images −310ms · Serve images in next-gen formats −310ms · Reduce unused JavaScript −270ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 674ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 310ms · Unattributable 292ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 253ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 212ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 209ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1404ms · Reduce unused CSS −200ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 188ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 74ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 70ms · Unattributable 70ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1428ms · Reduce unused CSS −580ms · Defer offscreen images −270ms · Serve images in next-gen formats −270ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 657ms · Unattributable 365ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 308ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 256ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 153ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 136ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1471ms · Reduce unused CSS −200ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 189ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 89ms · Unattributable 59ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 55ms
