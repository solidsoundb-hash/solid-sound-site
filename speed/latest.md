# Скорость сайта — 2026-11-21 18:30 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (телефон): оценка упала 38 → 32
- /en/ (компьютер): оценка упала 69 → 64
- /en/ (компьютер): страница «прыгает» при загрузке (CLS 0.105, норма до 0.1)
- /en/all-products/ (телефон): страница «прыгает» при загрузке (CLS 0.189, норма до 0.1)
- /en/all-products/ (компьютер): главный блок появляется позже 0.0 → 2.0 с
- /product/infinity/ (телефон): оценка упала 59 → 53

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **32** (-6) | 8.7 с | 3.6 с | 1958 мс | 0.033 | 1257 КБ | 71 | 2.5 с |
| /en/ | компьютер | **64** (-5) | 2.6 с | 1.6 с | 167 мс | 0.105 | 1241 КБ | 75 | 4.9 с |
| /en/all-products/ | телефон | **0** (+0) | 0.0 с | 3.2 с | 0 мс | 0.189 | 1377 КБ | 90 | 7.8 с |
| /en/all-products/ | компьютер | **75** (+75) | 2.0 с | 1.6 с | 64 мс | 0.068 | 1550 КБ | 96 | 7.7 с |
| /product/infinity/ | телефон | **53** (-6) | 10.1 с | 2.9 с | 360 мс | 0 | 5430 КБ | 216 | 3.9 с |
| /product/infinity/ | компьютер | **72** (+0) | 2.7 с | 1.6 с | 0 мс | 0.018 | 5153 КБ | 225 | 2.0 с |
| /en/studio-calculator/ | телефон | **81** (+6) | 3.5 с | 2.9 с | 88 мс | 0 | 1272 КБ | 65 | 1.2 с |
| /en/studio-calculator/ | компьютер | **84** (+1) | 1.6 с | 1.4 с | 0 мс | 0.077 | 1272 КБ | 65 | 1.3 с |
| /en/wiki/ | телефон | **82** (+4) | 3.5 с | 2.6 с | 91 мс | 0 | 755 КБ | 57 | 1.4 с |
| /en/wiki/ | компьютер | **82** (-1) | 1.6 с | 1.4 с | 0 мс | 0.003 | 755 КБ | 57 | 4.1 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −2547ms · Reduce unused JavaScript −640ms · Reduce unused CSS −320ms · Serve images in next-gen formats −320ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 99KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 51KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB
- скрипты грузят процессор: /en/ 2626ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 2204ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 1095ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 999ms · Unattributable 899ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 633ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −4921ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 51KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB
- скрипты грузят процессор: /en/ 511ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 287ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 189ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 175ms · Unattributable 107ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 85ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −7756ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 48KB
- скрипты грузят процессор: /en/all-products/ 2776ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1279ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 849ms · Unattributable 589ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 504ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 265ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −7663ms · Defer offscreen images −230ms · Reduce unused CSS −230ms · Serve images in next-gen formats −230ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 48KB
- скрипты грузят процессор: /en/all-products/ 1023ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 283ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 208ms · Unattributable 120ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 103ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 53ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −3900ms · Reduce unused CSS −640ms · Reduce unused JavaScript −640ms · Defer offscreen images −320ms · Properly size images −250ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 824KB · https://newassets.hcaptcha.com/c/675d4744755bed918679906e0b70424198f1de70000fe7c2cad03fecf 685KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 454KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 269KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 251KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1512ms · https://js.stripe.com/v3/?ver=3.0 658ms · Unattributable 489ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 407ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 323ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 258ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1998ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/675d4744755bed918679906e0b70424198f1de70000fe7c2cad03fecf 685KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 652KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 381KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 269KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 246KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 518ms · https://js.stripe.com/v3/?ver=3.0 168ms · Unattributable 121ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 77ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 53ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 52ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1164ms · Reduce unused CSS −580ms · Defer offscreen images −270ms · Serve images in next-gen formats −270ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 700ms · Unattributable 308ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 270ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 236ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 216ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 115ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1291ms · Reduce unused CSS −210ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 192ms · Unattributable 87ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 67ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 65ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 64ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1382ms · Reduce unused CSS −590ms · Defer offscreen images −280ms · Serve images in next-gen formats −280ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · /en/wiki/ 38KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB
- скрипты грузят процессор: /en/wiki/ 598ms · Unattributable 437ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 251ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 225ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 132ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 126ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −4055ms · Reduce unused CSS −210ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · /en/wiki/ 38KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB
- скрипты грузят процессор: /en/wiki/ 172ms · Unattributable 115ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 66ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 59ms
