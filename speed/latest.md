# Скорость сайта — 2026-11-21 23:41 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (телефон): оценка упала 32 → 27
- /en/all-products/ (телефон): главный блок появляется позже 0.0 → 5.1 с
- /en/all-products/ (компьютер): страница стала тяжелее 1550 → 2439 КБ

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **27** (-5) | 9.5 с | 4.5 с | 4151 мс | 0.038 | 1163 КБ | 73 | 4.7 с |
| /en/ | компьютер | **67** (+3) | 2.7 с | 1.5 с | 169 мс | 0.003 | 1168 КБ | 76 | 4.2 с |
| /en/all-products/ | телефон | **49** (+49) | 5.1 с | 3.1 с | 793 мс | 0.059 | 1261 КБ | 91 | 1.6 с |
| /en/all-products/ | компьютер | **75** (+0) | 2.1 с | 1.5 с | 88 мс | 0.013 | 2439 КБ | 113 | 2.6 с |
| /product/infinity/ | телефон | **59** (+6) | 9.3 с | 2.9 с | 237 мс | 0 | 5327 КБ | 216 | 2.0 с |
| /product/infinity/ | компьютер | **75** (+3) | 2.6 с | 1.5 с | 0 мс | 0.02 | 5362 КБ | 225 | 1.2 с |
| /en/studio-calculator/ | телефон | **84** (+3) | 3.5 с | 2.6 с | 82 мс | 0 | 1180 КБ | 67 | 1.0 с |
| /en/studio-calculator/ | компьютер | **85** (+1) | 1.5 с | 1.3 с | 0 мс | 0.078 | 1180 КБ | 67 | 1.1 с |
| /en/wiki/ | телефон | **83** (+1) | 3.5 с | 2.6 с | 60 мс | 0 | 663 КБ | 59 | 1.2 с |
| /en/wiki/ | компьютер | **85** (+3) | 1.5 с | 1.3 с | 0 мс | 0.002 | 663 КБ | 59 | 2.5 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −4739ms · Reduce unused JavaScript −620ms · Reduce unused CSS −310ms · Serve images in next-gen formats −310ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 4964ms · Unattributable 2580ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 2244ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 1083ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1027ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 513ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −4220ms · Serve images in next-gen formats −380ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 54KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 431ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 275ms · Unattributable 168ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 111ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 85ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 85ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1594ms · Defer offscreen images −1250ms · Reduce unused CSS −940ms · Serve images in next-gen formats −940ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 2069ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1082ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 950ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 860ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 458ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 444ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −2561ms · Reduce unused CSS −400ms · Defer offscreen images −390ms · Serve images in next-gen formats −390ms
- тяжёлые файлы: https://www.youtube-nocookie.com/s/player/6b3d0364/player_embed_es6.vflset/en_US/base.js 481KB · https://www.youtube-nocookie.com/s/_/ytembeds/_/js/k=ytembeds.base.en_US.yBU7730uEUs.2021. 224KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · https://www.youtube-nocookie.com/s/_/ytembeds/_/js/k=ytembeds.base.en_US.yBU7730uEUs.2021. 156KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB
- скрипты грузят процессор: /en/all-products/ 689ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 308ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 288ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 235ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 216ms · Unattributable 152ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1958ms · Reduce unused CSS −620ms · Reduce unused JavaScript −620ms · Defer offscreen images −310ms · Serve images in next-gen formats −310ms · Properly size images −310ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 767KB · https://newassets.hcaptcha.com/c/188238a86e9c9cf57546391f688e1ab93c666f3c31cf377b7ad680210 705KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 479KB · https://pay.google.com/gp/p/ui/pay 422KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 292KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 251KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1943ms · Unattributable 726ms · https://js.stripe.com/v3/?ver=3.0 671ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 354ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 277ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 199ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1173ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-495de1b4cd637383 767KB · https://newassets.hcaptcha.com/c/188238a86e9c9cf57546391f688e1ab93c666f3c31cf377b7ad680210 705KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ea606c890fb5bae9757e 479KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 316KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-0203d56c 251KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 614ms · https://js.stripe.com/v3/?ver=3.0 176ms · Unattributable 156ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 71ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 61ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 51ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1043ms · Reduce unused CSS −590ms · Defer offscreen images −270ms · Serve images in next-gen formats −270ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 773ms · Unattributable 715ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 414ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 347ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 185ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 156ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1099ms · Reduce unused CSS −200ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 42KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 216ms · Unattributable 127ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 61ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 55ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 55ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1207ms · Reduce unused CSS −570ms · Defer offscreen images −250ms · Serve images in next-gen formats −250ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 695ms · Unattributable 502ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 220ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 206ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 190ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d4925710a7.js.pagespeed.ce.E 150ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −2460ms · Reduce unused CSS −200ms · Serve images in next-gen formats −190ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/wiki/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 167ms · Unattributable 67ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 60ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 56ms
