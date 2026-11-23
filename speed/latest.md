# Скорость сайта — 2026-11-23 04:52 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/wiki/ (телефон): оценка упала 90 → 85
- /en/wiki/ (телефон): главный блок появляется позже 2.6 → 3.3 с

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **38** (-1) | 7.0 с | 2.6 с | 1650 мс | 0.029 | 1163 КБ | 72 | 1.6 с |
| /en/ | компьютер | **78** (+1) | 2.4 с | 1.3 с | 78 мс | 0.004 | 1168 КБ | 76 | 1.1 с |
| /en/all-products/ | телефон | **49** (+2) | 5.0 с | 3.2 с | 887 мс | 0.051 | 1322 КБ | 91 | 1.4 с |
| /en/all-products/ | компьютер | **80** (+1) | 1.9 с | 1.3 с | 113 мс | 0.013 | 1383 КБ | 97 | 1.4 с |
| /product/infinity/ | телефон | **63** (+6) | 8.9 с | 2.6 с | 169 мс | 0 | 5326 КБ | 218 | 1.2 с |
| /product/infinity/ | компьютер | **76** (+0) | 2.6 с | 1.3 с | 0 мс | 0.019 | 5351 КБ | 226 | 1.3 с |
| /en/studio-calculator/ | телефон | **90** (+5) | 2.6 с | 2.4 с | 34 мс | 0 | 1181 КБ | 68 | 1.1 с |
| /en/studio-calculator/ | компьютер | **90** (+2) | 1.3 с | 1.1 с | 0 мс | 0.077 | 1181 КБ | 67 | 1.1 с |
| /en/wiki/ | телефон | **85** (-5) | 3.3 с | 2.4 с | 29 мс | 0 | 658 КБ | 59 | 1.8 с |
| /en/wiki/ | компьютер | **85** (-4) | 1.3 с | 1.1 с | 0 мс | 0.003 | 664 КБ | 59 | 1.1 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1563ms · Reduce unused CSS −580ms · Serve images in next-gen formats −290ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1919ms · /en/ 1313ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 787ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 613ms · Unattributable 514ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 502ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1068ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 357ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 199ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 139ms · Unattributable 133ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 99ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 92ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1366ms · Reduce unused CSS −1150ms · Defer offscreen images −860ms · Serve images in next-gen formats −580ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1612ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1180ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1177ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 886ms · Unattributable 542ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 366ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1374ms · Reduce unused CSS −350ms · Defer offscreen images −320ms · Serve images in next-gen formats −320ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 418ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 346ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 258ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 247ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 238ms · Unattributable 146ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1161ms · Reduce unused CSS −580ms · Defer offscreen images −290ms · Reduce unused JavaScript −290ms · Serve images in next-gen formats −290ms · Properly size images −290ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 767KB · https://newassets.hcaptcha.com/c/fc5158a7c63a1037603acdbf7f9b6c2c863a031e601d7d6aecf5884a4 687KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 455KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 292KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 273KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1370ms · https://js.stripe.com/v3/?ver=3.0 650ms · Unattributable 479ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 287ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 262ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 229ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1282ms · Reduce unused CSS −350ms · Reduce unused JavaScript −180ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 767KB · https://newassets.hcaptcha.com/c/fc5158a7c63a1037603acdbf7f9b6c2c863a031e601d7d6aecf5884a4 686KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 454KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 316KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 273KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 451ms · https://js.stripe.com/v3/?ver=3.0 161ms · Unattributable 135ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 55ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 52ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1095ms · Reduce unused CSS −290ms · Defer offscreen images −250ms · Serve images in next-gen formats −250ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 567ms · Unattributable 318ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 234ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 188ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 165ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 128ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1059ms · Reduce unused CSS −170ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 140ms · Unattributable 88ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 61ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 60ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1753ms · Reduce unused CSS −580ms · Defer offscreen images −290ms · Serve images in next-gen formats −290ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/l/font?kit=KFOBCnqEu92Fr1ME7kSn66aGLdTylUAMQXC89YmC2DPNWubEbQuYo 30KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 524ms · Unattributable 229ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 190ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 182ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 163ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.cb6b02c220.js.pagespeed.ce.f 99ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1103ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 135ms · Unattributable 82ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 50ms
