# Скорость сайта — 2026-11-22 07:58 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ✅ Скорость не ухудшилась

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **39** (+8) | 7.0 с | 2.7 с | 1456 мс | 0.034 | 1163 КБ | 72 | 1.2 с |
| /en/ | компьютер | **77** (+15) | 2.5 с | 1.3 с | 93 мс | 0.002 | 1168 КБ | 76 | 1.0 с |
| /en/all-products/ | телефон | **47** (+9) | 5.1 с | 3.2 с | 957 мс | 0.051 | 1298 КБ | 91 | 1.4 с |
| /en/all-products/ | компьютер | **79** (+6) | 1.7 с | 1.4 с | 168 мс | 0.007 | 1338 КБ | 98 | 1.3 с |
| /product/infinity/ | телефон | **57** (+6) | 8.7 с | 2.9 с | 323 мс | 0.003 | 5317 КБ | 220 | 1.3 с |
| /product/infinity/ | компьютер | **76** (+2) | 2.5 с | 1.4 с | 0 мс | 0.054 | 5315 КБ | 224 | 1.2 с |
| /en/studio-calculator/ | телефон | **85** (+4) | 3.2 с | 2.3 с | 169 мс | 0 | 1172 КБ | 67 | 1.0 с |
| /en/studio-calculator/ | компьютер | **88** (+3) | 1.4 с | 1.2 с | 0 мс | 0.078 | 1174 КБ | 67 | 1.0 с |
| /en/wiki/ | телефон | **90** (+7) | 2.6 с | 2.3 с | 100 мс | 0 | 663 КБ | 59 | 1.1 с |
| /en/wiki/ | компьютер | **89** (+5) | 1.4 с | 1.2 с | 0 мс | 0.003 | 663 КБ | 59 | 1.4 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1165ms · Reduce unused CSS −580ms · Serve images in next-gen formats −290ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 2287ms · /en/ 1938ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 761ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 684ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 594ms · Unattributable 569ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −999ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 425ms · Unattributable 182ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 144ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 126ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 121ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 97ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1355ms · Reduce unused CSS −1160ms · Defer offscreen images −870ms · Serve images in next-gen formats −580ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1763ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1525ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1466ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1134ms · Unattributable 476ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 463ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1293ms · Defer offscreen images −330ms · Serve images in next-gen formats −330ms · Reduce unused CSS −210ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 466ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 432ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 341ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 323ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 306ms · Unattributable 131ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1307ms · Reduce unused CSS −860ms · Reduce unused JavaScript −860ms · Defer offscreen images −290ms · Serve images in next-gen formats −290ms · Properly size images −290ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 767KB · https://newassets.hcaptcha.com/c/688adc219847215dcd8df6528cd8ac69b84e8a040ce7ec903167c0499 701KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 454KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 292KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 251KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1886ms · https://js.stripe.com/v3/?ver=3.0 802ms · Unattributable 779ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 336ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 324ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 256ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1156ms · Reduce unused CSS −180ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 767KB · https://newassets.hcaptcha.com/c/688adc219847215dcd8df6528cd8ac69b84e8a040ce7ec903167c0499 700KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 454KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 292KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 251KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 502ms · https://js.stripe.com/v3/?ver=3.0 195ms · Unattributable 177ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 95ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 72ms · /wp-content/plugins/woocommerce-payments/dist/product-details.js?ver=11.2.1 69ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1039ms · Reduce unused CSS −530ms · Defer offscreen images −240ms · Serve images in next-gen formats −240ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 699ms · Unattributable 338ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 303ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 262ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 240ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 199ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1035ms · Reduce unused CSS −180ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 201ms · Unattributable 127ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 76ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 64ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 58ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1083ms · Defer offscreen images −290ms · Reduce unused CSS −290ms · Serve images in next-gen formats −290ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 606ms · Unattributable 244ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 242ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 225ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 216ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 127ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1362ms · Reduce unused CSS −170ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 165ms · Unattributable 76ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 63ms
