# Скорость сайта — 2026-11-21 06:55 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/all-products/ (телефон): оценка упала 44 → 0
- /en/all-products/ (телефон): страница «прыгает» при загрузке (CLS 0.189, норма до 0.1)
- /en/studio-calculator/ (телефон): оценка упала 85 → 75
- /en/studio-calculator/ (телефон): главный блок появляется позже 2.8 → 4.4 с

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **38** (+19) | 8.5 с | 3.1 с | 1107 мс | 0.027 | 1288 КБ | 70 | 2.2 с |
| /en/ | компьютер | **69** (-3) | 3.1 с | 1.7 с | 75 мс | 0.004 | 1292 КБ | 74 | 1.2 с |
| /en/all-products/ | телефон | **0** (-44) | 0.0 с | 3.0 с | 0 мс | 0.189 | 1419 КБ | 87 | 1.6 с |
| /en/all-products/ | компьютер | **0** (+0) | 0.0 с | 1.7 с | 0 мс | 0.067 | 1490 КБ | 95 | 1.8 с |
| /product/infinity/ | телефон | **59** (+12) | 9.5 с | 3.0 с | 140 мс | 0.069 | 4881 КБ | 186 | 1.3 с |
| /product/infinity/ | компьютер | **72** (-3) | 2.8 с | 1.7 с | 0 мс | 0.031 | 4882 КБ | 193 | 1.4 с |
| /en/studio-calculator/ | телефон | **75** (-10) | 4.4 с | 2.7 с | 50 мс | 0 | 1270 КБ | 64 | 1.2 с |
| /en/studio-calculator/ | компьютер | **83** (-3) | 1.7 с | 1.4 с | 0 мс | 0.077 | 1264 КБ | 64 | 1.2 с |
| /en/wiki/ | телефон | **78** (-4) | 4.0 с | 2.7 с | 32 мс | 0 | 753 КБ | 56 | 1.3 с |
| /en/wiki/ | компьютер | **83** (-4) | 1.7 с | 1.5 с | 0 мс | 0.003 | 753 КБ | 56 | 1.6 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −2168ms · Reduce unused CSS −1350ms · Reduce unused JavaScript −670ms · Serve images in next-gen formats −670ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.d4ad1d5026.js.pagespeed.ce.LL 50KB · /en/ 49KB
- скрипты грузят процессор: /en/ 1607ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1113ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 1027ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.81b37f386f.js.pagespeed.ce.P 539ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 471ms · Unattributable 453ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1203ms · Reduce unused CSS −230ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.d4ad1d5026.js.pagespeed.ce.LL 50KB · /en/ 49KB
- скрипты грузят процессор: /en/ 320ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 265ms · Unattributable 158ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 87ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 78ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 51ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1577ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 2247ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 993ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 664ms · Unattributable 523ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.81b37f386f.js.pagespeed.ce.P 295ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 201ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1777ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 962ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 153ms · Unattributable 99ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 98ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1306ms · Reduce unused CSS −1010ms · Reduce unused JavaScript −670ms · Defer offscreen images −340ms · Serve images in next-gen formats −340ms · Properly size images −340ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/efeb3ef832f1328925e303eabb17b901d549de70cb1063ab4f9bfc311 686KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 652KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 381KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 269KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-a4f0bebf1486634d6991b242fd89f3a8.js 184KB
- скрипты грузят процессор: /product/infinity/ 1089ms · https://js.stripe.com/v3/?ver=3.0 468ms · Unattributable 409ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 352ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 172ms · /wp-content/plugins/woocommerce-payments/dist/express-checkout.js?ver=11.2.1 163ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1418ms · Reduce unused CSS −230ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/efeb3ef832f1328925e303eabb17b901d549de70cb1063ab4f9bfc311 686KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 652KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 381KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 269KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-a4f0bebf1486634d6991b242fd89f3a8.js 184KB
- скрипты грузят процессор: /product/infinity/ 319ms · https://js.stripe.com/v3/?ver=3.0 111ms · Unattributable 97ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 57ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 50ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1248ms · Reduce unused JavaScript −670ms · Reduce unused CSS −340ms · Defer offscreen images −290ms · Serve images in next-gen formats −290ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 491ms · Unattributable 435ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 227ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 204ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 175ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.81b37f386f.js.pagespeed.ce.P 95ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1205ms · Reduce unused CSS −210ms · Serve images in next-gen formats −210ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 149ms · Unattributable 95ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 58ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1294ms · Reduce unused CSS −670ms · Reduce unused JavaScript −670ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 474ms · Unattributable 250ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 248ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 172ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 96ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.81b37f386f.js.pagespeed.ce.P 83ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1599ms · Reduce unused CSS −230ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 137ms · Unattributable 99ms
