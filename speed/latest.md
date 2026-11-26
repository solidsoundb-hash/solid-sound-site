# Скорость сайта — 2026-11-26 04:46 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/all-products/ (телефон): оценка упала 59 → 40
- /en/all-products/ (телефон): главный блок появляется позже 4.5 → 7.8 с
- /en/studio-calculator/ (телефон): оценка упала 87 → 81
- /en/studio-calculator/ (телефон): главный блок появляется позже 2.8 → 3.6 с
- /en/wiki/ (телефон): оценка упала 87 → 82

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **37** (+0) | 8.1 с | 3.0 с | 1337 мс | 0.004 | 1153 КБ | 68 | 1.9 с |
| /en/ | компьютер | **72** (-3) | 2.7 с | 1.6 с | 106 мс | 0.004 | 1158 КБ | 72 | 1.4 с |
| /en/all-products/ | телефон | **40** (-19) | 7.8 с | 3.2 с | 1066 мс | 0.036 | 1288 КБ | 86 | 1.8 с |
| /en/all-products/ | компьютер | **76** (-2) | 2.1 с | 1.6 с | 113 мс | 0.013 | 1373 КБ | 92 | 1.7 с |
| /product/infinity/ | телефон | **61** (-3) | 11.0 с | 2.9 с | 172 мс | 0 | 5082 КБ | 213 | 1.7 с |
| /product/infinity/ | компьютер | **71** (-1) | 3.0 с | 1.6 с | 0 мс | 0.031 | 5082 КБ | 222 | 1.7 с |
| /en/studio-calculator/ | телефон | **81** (-6) | 3.6 с | 2.6 с | 17 мс | 0 | 1177 КБ | 65 | 1.3 с |
| /en/studio-calculator/ | компьютер | **84** (-1) | 1.6 с | 1.4 с | 0 мс | 0.078 | 1177 КБ | 65 | 1.4 с |
| /en/wiki/ | телефон | **82** (-5) | 3.3 с | 2.6 с | 60 мс | 0 | 654 КБ | 54 | 2.6 с |
| /en/wiki/ | компьютер | **82** (-2) | 1.8 с | 1.6 с | 0 мс | 0.003 | 653 КБ | 54 | 1.4 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1902ms · Reduce unused JavaScript −320ms · Serve images in next-gen formats −190ms · Properly size images −190ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 2534ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 1617ms · Unattributable 865ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 739ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 663ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 577ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1386ms · Serve images in next-gen formats −220ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 252ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 249ms · Unattributable 163ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 94ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 72ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 60ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1817ms · Defer offscreen images −1300ms · Reduce unused CSS −970ms · Reduce unused JavaScript −860ms · Serve images in next-gen formats −860ms · Properly size images −210ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1348ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1336ms · /en/all-products/ 1316ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1019ms · Unattributable 588ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 447ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1685ms · Reduce unused CSS −190ms · Defer offscreen images −180ms · Serve images in next-gen formats −180ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 446ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 348ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 270ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 238ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 236ms · Unattributable 128ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1659ms · Reduce unused JavaScript −1300ms · Reduce unused CSS −640ms · Properly size images −250ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/9f325a5d0d57d27994efe61f7e57f1a3f53a3290d29b73257917abce0 702KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1578ms · https://js.stripe.com/v3/?ver=3.0 737ms · Unattributable 540ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 337ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 268ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 228ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1724ms · Reduce unused JavaScript −330ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/9f325a5d0d57d27994efe61f7e57f1a3f53a3290d29b73257917abce0 702KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 510ms · Unattributable 196ms · https://js.stripe.com/v3/?ver=3.0 191ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 81ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 63ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 56ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1322ms · Reduce unused CSS −640ms · Defer offscreen images −320ms · Serve images in next-gen formats −320ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_486_0/static/js/widget.d94c078352d7cc9b702d.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_486_0/static/js/chunk-WidgetIframe-d94c078352d7cc9b702d. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 598ms · Unattributable 502ms · https://code.tidio.co/widget-v4/1_486_0/static/js/widget.d94c078352d7cc9b702d.js 226ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 186ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 180ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 106ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1357ms · Reduce unused CSS −210ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_486_0/static/js/widget.d94c078352d7cc9b702d.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_486_0/static/js/chunk-WidgetIframe-d94c078352d7cc9b702d. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 146ms · Unattributable 66ms · https://code.tidio.co/widget-v4/1_486_0/static/js/widget.d94c078352d7cc9b702d.js 62ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 55ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −2574ms · Reduce unused CSS −320ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 496ms · Unattributable 338ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 200ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 181ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 134ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 102ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1435ms · Reduce unused CSS −190ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 117ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 53ms · Unattributable 51ms
