# Скорость сайта — 2026-11-20 21:28 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (телефон): оценка упала 38 → 25
- /en/ (телефон): главный блок появляется позже 8.6 → 11.5 с
- /en/ (телефон): страница «прыгает» при загрузке (CLS 0.104, норма до 0.1)
- /en/all-products/ (телефон): страница «прыгает» при загрузке (CLS 0.189, норма до 0.1)

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **25** (-13) | 11.5 с | 4.3 с | 2981 мс | 0.104 | 1405 КБ | 73 | 3.4 с |
| /en/ | компьютер | **70** (+2) | 2.8 с | 1.4 с | 103 мс | 0.013 | 1239 КБ | 71 | 4.4 с |
| /en/all-products/ | телефон | **36** (+2) | 4.9 с | 2.7 с | 1228 мс | 0.189 | 1419 КБ | 88 | 7.0 с |
| /en/all-products/ | компьютер | **0** (+0) | 0.0 с | 1.4 с | 0 мс | 0.067 | 1528 КБ | 91 | 1.6 с |
| /product/infinity/ | телефон | **52** (+3) | 9.2 с | 2.7 с | 384 мс | 0.013 | 4901 КБ | 188 | 5.2 с |
| /product/infinity/ | компьютер | **74** (+6) | 2.6 с | 1.4 с | 0 мс | 0.031 | 4903 КБ | 194 | 2.8 с |
| /en/studio-calculator/ | телефон | **83** (+8) | 2.7 с | 2.4 с | 189 мс | 0 | 1270 КБ | 64 | 3.1 с |
| /en/studio-calculator/ | компьютер | **87** (+6) | 1.4 с | 1.2 с | 0 мс | 0.079 | 1270 КБ | 64 | 1.2 с |
| /en/wiki/ | телефон | **85** (+8) | 2.7 с | 2.4 с | 174 мс | 0 | 753 КБ | 56 | 1.6 с |
| /en/wiki/ | компьютер | **85** (+0) | 1.4 с | 1.2 с | 0 мс | 0.006 | 753 КБ | 56 | 4.0 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −3411ms · Reduce unused JavaScript −2070ms · Reduce unused CSS −1900ms · Serve images in next-gen formats −1180ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/signals/config/344368324189550?v=2.9.441&r=stable&domain=soli 108KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://connect.facebook.net/signals/plugins/openbridge3.js?v=2.9.441 61KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB
- скрипты грузят процессор: /wp-content/plugins/solid-sound-custom/build/assets/home-js.2b2164eb44.js.pagespeed.ce.HhP 3884ms · /en/ 2890ms · Unattributable 1309ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 1101ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 640ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 571ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −4427ms · Serve images in next-gen formats −350ms · Reduce unused JavaScript −190ms · Efficiently encode images −160ms · Properly size images −160ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.d4ad1d5026.js.pagespeed.ce.LL 50KB · /en/ 49KB
- скрипты грузят процессор: /en/ 479ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.2b2164eb44.js.pagespeed.ce.HhP 148ms · Unattributable 137ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 128ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 126ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 99ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −7047ms · Defer offscreen images −590ms · Serve images in next-gen formats −590ms · Reduce unused CSS −300ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 3234ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1574ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 912ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 752ms · Unattributable 600ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 237ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1597ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 1241ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 356ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 268ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 137ms · Unattributable 117ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 62ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −5212ms · Reduce unused CSS −590ms · Defer offscreen images −300ms · Properly size images −250ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/75c4d2af74eef811a43af072baed74e35687a40e0cb00a4878e0d059a 707KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-6382cc86e6f66b37 652KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-a5cf7b139a141745cf0f3 379KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 268KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-d9cb5de9c93433a59b3db4436a780303.js 184KB
- скрипты грузят процессор: /product/infinity/ 2055ms · https://js.stripe.com/v3/?ver=3.0 663ms · Unattributable 531ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 388ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 373ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 332ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −2825ms · Reduce unused CSS −190ms · Reduce unused JavaScript −190ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/75c4d2af74eef811a43af072baed74e35687a40e0cb00a4878e0d059a 707KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-6382cc86e6f66b37 652KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-a5cf7b139a141745cf0f3 379KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 268KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-d9cb5de9c93433a59b3db4436a780303.js 184KB
- скрипты грузят процессор: /product/infinity/ 538ms · https://js.stripe.com/v3/?ver=3.0 167ms · Unattributable 129ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 85ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 80ms · /wp-content/plugins/woocommerce-payments/dist/express-checkout.js?ver=11.2.1 59ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −3065ms · Reduce unused CSS −300ms · Defer offscreen images −260ms · Serve images in next-gen formats −260ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 755ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 364ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 316ms · Unattributable 274ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 250ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 174ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1165ms · Reduce unused CSS −180ms · Serve images in next-gen formats −180ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 212ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 81ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 68ms · Unattributable 67ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 60ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1630ms · Reduce unused CSS −300ms · Defer offscreen images −260ms · Serve images in next-gen formats −260ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 694ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 357ms · Unattributable 267ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 253ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 205ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 149ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −3952ms · Reduce unused CSS −180ms · Serve images in next-gen formats −180ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 196ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 68ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 66ms · Unattributable 61ms
