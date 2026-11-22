# Скорость сайта — 2026-11-22 04:41 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (компьютер): оценка упала 67 → 62
- /en/ (компьютер): страница «прыгает» при загрузке (CLS 0.105, норма до 0.1)
- /en/all-products/ (телефон): оценка упала 49 → 38
- /en/all-products/ (телефон): главный блок появляется позже 5.1 → 8.1 с
- /product/infinity/ (телефон): оценка упала 59 → 51

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **31** (+4) | 9.1 с | 3.8 с | 2388 мс | 0.034 | 1163 КБ | 72 | 1.3 с |
| /en/ | компьютер | **62** (-5) | 2.9 с | 2.0 с | 124 мс | 0.105 | 1168 КБ | 77 | 1.1 с |
| /en/all-products/ | телефон | **38** (-11) | 8.1 с | 3.2 с | 1388 мс | 0.036 | 1298 КБ | 91 | 1.4 с |
| /en/all-products/ | компьютер | **73** (-2) | 2.2 с | 1.6 с | 174 мс | 0.013 | 1383 КБ | 97 | 1.4 с |
| /product/infinity/ | телефон | **51** (-8) | 9.1 с | 2.9 с | 467 мс | 0.071 | 5052 КБ | 216 | 1.2 с |
| /product/infinity/ | компьютер | **74** (-1) | 2.7 с | 1.6 с | 0 мс | 0.033 | 5054 КБ | 223 | 1.2 с |
| /en/studio-calculator/ | телефон | **81** (-3) | 3.5 с | 2.6 с | 127 мс | 0 | 1175 КБ | 67 | 1.1 с |
| /en/studio-calculator/ | компьютер | **85** (+0) | 1.6 с | 1.4 с | 0 мс | 0.078 | 1175 КБ | 67 | 1.1 с |
| /en/wiki/ | телефон | **83** (+0) | 3.5 с | 2.5 с | 86 мс | 0 | 664 КБ | 59 | 1.1 с |
| /en/wiki/ | компьютер | **84** (-1) | 1.9 с | 1.4 с | 0 мс | 0.003 | 664 КБ | 59 | 1.2 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1255ms · Reduce unused CSS −950ms · Reduce unused JavaScript −640ms · Serve images in next-gen formats −490ms · Properly size images −170ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 3290ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 1852ms · Unattributable 1041ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 843ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 820ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 788ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1081ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 413ms · /en/ 364ms · Unattributable 158ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 133ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 89ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 77ms

### /en/all-products/ — mobile
- можно выиграть: Reduce unused CSS −1590ms · Reduce initial server response time −1368ms · Defer offscreen images −950ms · Reduce unused JavaScript −630ms · Serve images in next-gen formats −530ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1620ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1571ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1424ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1162ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 582ms · Unattributable 427ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1371ms · Reduce unused CSS −390ms · Defer offscreen images −380ms · Serve images in next-gen formats −380ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 504ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 441ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 325ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 318ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 290ms · Unattributable 128ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1188ms · Reduce unused JavaScript −790ms · Serve images in next-gen formats −790ms · Properly size images −790ms · Defer offscreen images −640ms · Reduce unused CSS −640ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/7f00ef24564aecf01ec1aaba536fbf20405daade02051c34eac935982 677KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 652KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 381KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 269KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 246KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1865ms · https://js.stripe.com/v3/?ver=3.0 882ms · Unattributable 613ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 354ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 326ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 292ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1188ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/7f00ef24564aecf01ec1aaba536fbf20405daade02051c34eac935982 677KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 652KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 381KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 269KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 246KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 532ms · Unattributable 175ms · https://js.stripe.com/v3/?ver=3.0 162ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 81ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 75ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 70ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1082ms · Reduce unused CSS −580ms · Defer offscreen images −260ms · Serve images in next-gen formats −260ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 703ms · Unattributable 334ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 295ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 268ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 256ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 122ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1094ms · Reduce unused CSS −210ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 185ms · Unattributable 98ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 72ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 70ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 62ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1131ms · Reduce unused CSS −640ms · Defer offscreen images −320ms · Serve images in next-gen formats −320ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 575ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 245ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 226ms · Unattributable 211ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 161ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 116ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1165ms · Reduce unused CSS −250ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 164ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 72ms · Unattributable 67ms
