# Скорость сайта — 2026-11-27 04:44 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ✅ Скорость не ухудшилась

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **39** (+2) | 6.9 с | 2.5 с | 1652 мс | 0.004 | 1153 КБ | 67 | 1.6 с |
| /en/ | компьютер | **79** (+7) | 2.4 с | 1.3 с | 42 мс | 0 | 1158 КБ | 72 | 1.3 с |
| /en/all-products/ | телефон | **46** (+6) | 8.1 с | 2.9 с | 773 мс | 0.002 | 1288 КБ | 86 | 1.7 с |
| /en/all-products/ | компьютер | **85** (+9) | 1.6 с | 1.2 с | 108 мс | 0.013 | 1328 КБ | 92 | 1.6 с |
| /product/infinity/ | телефон | **62** (+1) | 10.3 с | 2.7 с | 208 мс | 0 | 5272 КБ | 210 | 1.5 с |
| /product/infinity/ | компьютер | **77** (+6) | 2.7 с | 1.2 с | 0 мс | 0.019 | 5301 КБ | 220 | 1.4 с |
| /en/studio-calculator/ | телефон | **89** (+8) | 3.0 с | 2.1 с | 39 мс | 0 | 1176 КБ | 65 | 1.2 с |
| /en/studio-calculator/ | компьютер | **91** (+7) | 1.2 с | 1.0 с | 0 мс | 0.078 | 1176 КБ | 65 | 1.3 с |
| /en/wiki/ | телефон | **89** (+7) | 3.0 с | 2.2 с | 66 мс | 0 | 653 КБ | 54 | 1.4 с |
| /en/wiki/ | компьютер | **88** (+6) | 1.5 с | 1.2 с | 0 мс | 0.003 | 653 КБ | 54 | 2.0 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1625ms · Reduce unused CSS −790ms · Reduce unused JavaScript −260ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 2488ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 1851ms · Unattributable 813ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 765ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 644ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 589ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1264ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 299ms · Unattributable 125ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 101ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 96ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 70ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 58ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1694ms · Defer offscreen images −790ms · Reduce unused JavaScript −790ms · Reduce unused CSS −530ms · Serve images in next-gen formats −530ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1457ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1131ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1075ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1034ms · Unattributable 586ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 386ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1649ms · Defer offscreen images −280ms · Serve images in next-gen formats −280ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 392ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 333ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 278ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 243ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 224ms · Unattributable 157ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1467ms · Reduce unused JavaScript −1290ms · Reduce unused CSS −520ms · Defer offscreen images −260ms · Serve images in next-gen formats −260ms · Properly size images −260ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 768KB · https://newassets.hcaptcha.com/c/8a1635c2539b96c1581ce239ca2b8df6aaada2e56153e1cd353b4291c 676KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 457KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/a7305732738ccf3b7862eab7fe6d8f7b136a48f4/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 252KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 219KB
- скрипты грузят процессор: /product/infinity/ 1402ms · https://js.stripe.com/v3/?ver=3.0 791ms · Unattributable 549ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 257ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 247ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 222ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1388ms · Reduce unused JavaScript −240ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 768KB · https://newassets.hcaptcha.com/c/8a1635c2539b96c1581ce239ca2b8df6aaada2e56153e1cd353b4291c 677KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 457KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/a7305732738ccf3b7862eab7fe6d8f7b136a48f4/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 294KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 252KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 219KB
- скрипты грузят процессор: /product/infinity/ 467ms · https://js.stripe.com/v3/?ver=3.0 164ms · Unattributable 149ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 78ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 75ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 71ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1234ms · Defer offscreen images −260ms · Reduce unused CSS −260ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_488_0/static/js/chunk-WidgetIframe-5f078add2abea76693a3. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 594ms · Unattributable 362ms · https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 256ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 216ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 208ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 122ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1294ms · Reduce unused CSS −160ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_488_0/static/js/chunk-WidgetIframe-5f078add2abea76693a3. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 132ms · Unattributable 116ms · https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 63ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 57ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1414ms · Defer offscreen images −260ms · Reduce unused CSS −260ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 494ms · Unattributable 266ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 216ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 215ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 105ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 104ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1967ms · Reduce unused CSS −310ms · Serve images in next-gen formats −270ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 118ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 59ms · Unattributable 53ms
