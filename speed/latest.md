# Скорость сайта — 2026-11-28 09:46 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ✅ Скорость не ухудшилась

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **36** (+7) | 6.9 с | 3.5 с | 1314 мс | 0.004 | 1110 КБ | 66 | 1.2 с |
| /en/ | компьютер | **78** (-1) | 2.4 с | 1.3 с | 10 мс | 0.002 | 1114 КБ | 70 | 1.2 с |
| /en/all-products/ | телефон | **54** (+15) | 4.8 с | 2.9 с | 665 мс | 0.002 | 1289 КБ | 86 | 2.3 с |
| /en/all-products/ | компьютер | **85** (+7) | 1.7 с | 1.3 с | 71 мс | 0.011 | 1373 КБ | 92 | 1.3 с |
| /product/infinity/ | телефон | **64** (+8) | 10.0 с | 2.6 с | 119 мс | 0 | 5078 КБ | 210 | 1.2 с |
| /product/infinity/ | компьютер | **76** (+0) | 2.7 с | 1.4 с | 0 мс | 0.019 | 5083 КБ | 217 | 1.2 с |
| /en/studio-calculator/ | телефон | **90** (+26) | 2.7 с | 2.3 с | 15 мс | 0 | 1177 КБ | 65 | 1.1 с |
| /en/studio-calculator/ | компьютер | **88** (-3) | 1.4 с | 1.2 с | 0 мс | 0.078 | 1177 КБ | 65 | 1.1 с |
| /en/wiki/ | телефон | **90** (+2) | 2.7 с | 2.4 с | 11 мс | 0 | 654 КБ | 54 | 1.1 с |
| /en/wiki/ | компьютер | **90** (+0) | 1.4 с | 1.2 с | 0 мс | 0.002 | 654 КБ | 54 | 1.2 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1178ms · Serve images in next-gen formats −580ms · Reduce unused CSS −290ms · Properly size images −290ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 2555ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d748abc7f5.js.pagespeed.ce.S 1586ms · Unattributable 1288ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 652ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 641ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 481ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1155ms · Serve images in next-gen formats −170ms · Properly size images −170ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 274ms · Unattributable 103ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 78ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 69ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 56ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −2304ms · Reduce unused CSS −1160ms · Defer offscreen images −870ms · Serve images in next-gen formats −580ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 56KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1412ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1014ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 908ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 748ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d748abc7f5.js.pagespeed.ce.S 383ms · Unattributable 350ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1281ms · Defer offscreen images −170ms · Reduce unused CSS −170ms · Serve images in next-gen formats −170ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 56KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 394ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 279ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 248ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d748abc7f5.js.pagespeed.ce.S 193ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 169ms · Unattributable 96ms

### /product/infinity/ — mobile
- можно выиграть: Reduce unused JavaScript −1560ms · Reduce initial server response time −1208ms · Reduce unused CSS −590ms · Properly size images −550ms · Defer offscreen images −520ms · Serve images in next-gen formats −520ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/743d71ece77c92c7ab1d64ce9daee1b11d245938ba44c85ef7cbb674f 700KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/a7305732738ccf3b7862eab7fe6d8f7b136a48f4/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 219KB
- скрипты грузят процессор: /product/infinity/ 1339ms · https://js.stripe.com/v3/?ver=3.0 535ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 381ms · Unattributable 362ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d748abc7f5.js.pagespeed.ce.S 233ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 215ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1165ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/743d71ece77c92c7ab1d64ce9daee1b11d245938ba44c85ef7cbb674f 700KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/a7305732738ccf3b7862eab7fe6d8f7b136a48f4/static/ 322KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 219KB
- скрипты грузят процессор: /product/infinity/ 420ms · Unattributable 167ms · https://js.stripe.com/v3/?ver=3.0 128ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 66ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 53ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1096ms · Defer offscreen images −290ms · Reduce unused CSS −290ms · Serve images in next-gen formats −290ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_488_0/static/js/chunk-WidgetIframe-5f078add2abea76693a3. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 518ms · Unattributable 364ms · https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 200ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 182ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 151ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d748abc7f5.js.pagespeed.ce.S 110ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1126ms · Reduce unused CSS −180ms · Serve images in next-gen formats −180ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_488_0/static/js/chunk-WidgetIframe-5f078add2abea76693a3. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 127ms · Unattributable 77ms · https://code.tidio.co/widget-v4/1_488_0/static/js/widget.5f078add2abea76693a3.js 55ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1061ms · Defer offscreen images −290ms · Reduce unused CSS −290ms · Serve images in next-gen formats −290ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 426ms · Unattributable 273ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 187ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 145ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 137ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d748abc7f5.js.pagespeed.ce.S 105ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1199ms · Reduce unused CSS −180ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 124ms
