# Скорость сайта — 2026-11-24 04:45 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ✅ Скорость не ухудшилась

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **33** (-2) | 7.9 с | 3.1 с | 2418 мс | 0.034 | 1163 КБ | 72 | 2.0 с |
| /en/ | компьютер | **71** (+3) | 2.9 с | 1.4 с | 88 мс | 0.002 | 1168 КБ | 77 | 1.5 с |
| /en/all-products/ | телефон | **42** (+3) | 5.1 с | 3.3 с | 1469 мс | 0.038 | 1305 КБ | 93 | 1.9 с |
| /en/all-products/ | компьютер | **76** (+5) | 2.0 с | 1.4 с | 154 мс | 0.012 | 1338 КБ | 97 | 1.9 с |
| /product/infinity/ | телефон | **52** (-3) | 9.1 с | 2.7 с | 472 мс | 0.003 | 5069 КБ | 217 | 1.6 с |
| /product/infinity/ | компьютер | **75** (+2) | 2.6 с | 1.4 с | 0 мс | 0.022 | 5071 КБ | 224 | 1.6 с |
| /en/studio-calculator/ | телефон | **87** (+13) | 2.7 с | 2.4 с | 136 мс | 0.002 | 1181 КБ | 67 | 1.3 с |
| /en/studio-calculator/ | компьютер | **87** (+2) | 1.4 с | 1.2 с | 0 мс | 0.078 | 1181 КБ | 67 | 1.3 с |
| /en/wiki/ | телефон | **89** (+6) | 2.7 с | 2.4 с | 106 мс | 0 | 664 КБ | 59 | 1.4 с |
| /en/wiki/ | компьютер | **88** (+3) | 1.4 с | 1.2 с | 0 мс | 0.003 | 664 КБ | 59 | 1.5 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −2007ms · Reduce unused CSS −1480ms · Reduce unused JavaScript −890ms · Serve images in next-gen formats −890ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 3686ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 1988ms · Unattributable 962ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 928ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 881ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 767ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1451ms · Reduce unused CSS −380ms · Reduce unused JavaScript −190ms · Serve images in next-gen formats −190ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 380ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 221ms · Unattributable 166ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 154ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 80ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 63ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1906ms · Reduce unused CSS −1190ms · Defer offscreen images −730ms · Serve images in next-gen formats −730ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 2264ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1635ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1632ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1346ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 620ms · Unattributable 510ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1878ms · Reduce unused CSS −370ms · Defer offscreen images −360ms · Serve images in next-gen formats −360ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 576ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 381ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 338ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 299ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 279ms · Unattributable 148ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1554ms · Reduce unused CSS −1190ms · Serve images in next-gen formats −590ms · Properly size images −590ms · Defer offscreen images −300ms · Reduce unused JavaScript −300ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/68e4c0213f161e508156e83a0ccf7ac480ef54234fe4dcc88e97656e6 692KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-4827fbff349e5811 654KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-21ea411923d602dbcc2d2 383KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 269KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-64b254bf 246KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 2008ms · https://js.stripe.com/v3/?ver=3.0 925ms · Unattributable 715ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 503ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 302ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 268ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1611ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/68e4c0213f161e508156e83a0ccf7ac480ef54234fe4dcc88e97656e6 692KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-4827fbff349e5811 654KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-21ea411923d602dbcc2d2 383KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 269KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-64b254bf 246KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 608ms · Unattributable 192ms · https://js.stripe.com/v3/?ver=3.0 174ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 83ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 75ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 54ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1302ms · Reduce unused CSS −300ms · Defer offscreen images −250ms · Serve images in next-gen formats −250ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 720ms · Unattributable 536ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 333ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 280ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 272ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 162ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1279ms · Reduce unused CSS −190ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 205ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 77ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 76ms · Unattributable 69ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1437ms · Reduce unused CSS −300ms · Defer offscreen images −260ms · Serve images in next-gen formats −260ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 700ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 246ms · Unattributable 240ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 201ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 159ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 122ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1475ms · Reduce unused CSS −180ms · Serve images in next-gen formats −160ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 166ms · Unattributable 64ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 55ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 52ms
