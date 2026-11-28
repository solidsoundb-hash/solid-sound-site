# Скорость сайта — 2026-11-28 04:42 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (телефон): оценка упала 39 → 29
- /en/ (телефон): главный блок появляется позже 6.9 → 8.8 с
- /en/all-products/ (телефон): оценка упала 46 → 39
- /en/all-products/ (компьютер): оценка упала 85 → 78
- /product/infinity/ (телефон): оценка упала 62 → 56
- /en/studio-calculator/ (телефон): оценка упала 89 → 64
- /en/studio-calculator/ (телефон): главный блок появляется позже 3.0 → 3.9 с

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **29** (-10) | 8.8 с | 4.2 с | 4186 мс | 0.034 | 1110 КБ | 66 | 1.1 с |
| /en/ | компьютер | **79** (+0) | 2.4 с | 1.3 с | 68 мс | 0.002 | 1115 КБ | 70 | 1.0 с |
| /en/all-products/ | телефон | **39** (-7) | 7.2 с | 3.1 с | 1442 мс | 0.036 | 1289 КБ | 86 | 1.3 с |
| /en/all-products/ | компьютер | **78** (-7) | 1.7 с | 1.2 с | 199 мс | 0.017 | 1328 КБ | 92 | 1.3 с |
| /product/infinity/ | телефон | **56** (-6) | 10.0 с | 2.5 с | 428 мс | 0 | 4922 КБ | 212 | 1.1 с |
| /product/infinity/ | компьютер | **76** (-1) | 2.9 с | 1.2 с | 7 мс | 0.032 | 4924 КБ | 224 | 1.1 с |
| /en/studio-calculator/ | телефон | **64** (-25) | 3.9 с | 2.2 с | 731 мс | 0 | 1177 КБ | 65 | 1.2 с |
| /en/studio-calculator/ | компьютер | **91** (+0) | 1.2 с | 1.1 с | 0 мс | 0.078 | 1177 КБ | 65 | 1.0 с |
| /en/wiki/ | телефон | **88** (-1) | 3.1 с | 2.3 с | 105 мс | 0 | 654 КБ | 54 | 1.0 с |
| /en/wiki/ | компьютер | **90** (+2) | 1.6 с | 1.1 с | 0 мс | 0.006 | 654 КБ | 54 | 1.0 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1124ms · Reduce unused CSS −270ms · Reduce unused JavaScript −270ms · Serve images in next-gen formats −270ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 4810ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 1967ms · Unattributable 1863ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1443ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 1227ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 989ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1022ms · Serve images in next-gen formats −290ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 345ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 146ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 131ms · Unattributable 121ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 102ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 54ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1281ms · Defer offscreen images −830ms · Reduce unused CSS −830ms · Serve images in next-gen formats −830ms · Reduce unused JavaScript −280ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 56KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1875ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1530ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1494ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1447ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 568ms · Unattributable 435ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1288ms · Reduce unused CSS −310ms · Defer offscreen images −300ms · Serve images in next-gen formats −300ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 56KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 522ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 440ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 348ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 321ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 304ms · Unattributable 114ms

### /product/infinity/ — mobile
- можно выиграть: Reduce unused JavaScript −1300ms · Reduce initial server response time −1085ms · Reduce unused CSS −540ms · Defer offscreen images −430ms · Properly size images −270ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://newassets.hcaptcha.com/c/c1a1880b8cb97049a43a6cd946e6512289bf892ee384ba27be0f1c949 540KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/a7305732738ccf3b7862eab7fe6d8f7b136a48f4/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 219KB
- скрипты грузят процессор: /product/infinity/ 1733ms · https://js.stripe.com/v3/?ver=3.0 963ms · Unattributable 550ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 350ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 340ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 324ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1071ms · Reduce unused JavaScript −310ms · Reduce unused CSS −200ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://newassets.hcaptcha.com/c/c1a1880b8cb97049a43a6cd946e6512289bf892ee384ba27be0f1c949 540KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/a7305732738ccf3b7862eab7fe6d8f7b136a48f4/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 219KB
- скрипты грузят процессор: /product/infinity/ 570ms · https://js.stripe.com/v3/?ver=3.0 202ms · Unattributable 190ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 84ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 75ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 62ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1212ms · Reduce unused CSS −620ms · Defer offscreen images −270ms · Serve images in next-gen formats −270ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_488_0/static/js/chunk-WidgetIframe-5f078add2abea76693a3. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: Unattributable 1092ms · /en/studio-calculator/ 726ms · https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 375ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 281ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 208ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 193ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −968ms · Reduce unused CSS −180ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_488_0/static/js/chunk-WidgetIframe-5f078add2abea76693a3. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 182ms · https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 70ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 70ms · Unattributable 64ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1016ms · Defer offscreen images −270ms · Reduce unused CSS −270ms · Serve images in next-gen formats −270ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 629ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 283ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 218ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 203ms · Unattributable 200ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 132ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1038ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 170ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 90ms · Unattributable 82ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 74ms
