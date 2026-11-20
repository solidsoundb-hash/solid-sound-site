# Скорость сайта — 2026-11-20 14:41 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (телефон): главный блок появляется позже 6.9 → 8.6 с
- /en/ (компьютер): оценка упала 76 → 68
- /en/all-products/ (телефон): главный блок появляется позже 0.0 → 6.6 с
- /en/all-products/ (телефон): страница «прыгает» при загрузке (CLS 0.189, норма до 0.1)
- /en/studio-calculator/ (телефон): оценка упала 83 → 75
- /en/studio-calculator/ (телефон): главный блок появляется позже 2.9 → 4.1 с
- /en/studio-calculator/ (компьютер): оценка упала 93 → 81
- /en/studio-calculator/ (компьютер): страница «прыгает» при загрузке (CLS 0.101, норма до 0.1)
- /en/wiki/ (телефон): оценка упала 89 → 77
- /en/wiki/ (телефон): главный блок появляется позже 2.4 → 3.8 с

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **38** (+11) | 8.6 с | 2.9 с | 1073 мс | 0.035 | 1235 КБ | 68 | 4.2 с |
| /en/ | компьютер | **68** (-8) | 2.9 с | 1.5 с | 105 мс | 0.013 | 1239 КБ | 71 | 4.4 с |
| /en/all-products/ | телефон | **34** (+34) | 6.6 с | 2.9 с | 1155 мс | 0.189 | 1418 КБ | 88 | 1.4 с |
| /en/all-products/ | компьютер | **0** (+0) | 0.0 с | 1.5 с | 0 мс | 0.067 | 1519 КБ | 94 | 3.0 с |
| /product/infinity/ | телефон | **49** (-1) | 14.6 с | 2.9 с | 481 мс | 0.016 | 5116 КБ | 191 | 4.4 с |
| /product/infinity/ | компьютер | **68** (-4) | 3.4 с | 1.5 с | 0 мс | 0.046 | 5110 КБ | 196 | 2.4 с |
| /en/studio-calculator/ | телефон | **75** (-8) | 4.1 с | 2.6 с | 203 мс | 0 | 1269 КБ | 64 | 1.2 с |
| /en/studio-calculator/ | компьютер | **81** (-12) | 1.5 с | 1.3 с | 0 мс | 0.101 | 1269 КБ | 64 | 2.8 с |
| /en/wiki/ | телефон | **77** (-12) | 3.8 с | 2.5 с | 194 мс | 0 | 755 КБ | 56 | 1.3 с |
| /en/wiki/ | компьютер | **85** (-4) | 1.5 с | 1.5 с | 0 мс | 0.006 | 752 КБ | 56 | 1.4 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −4169ms · Reduce unused JavaScript −940ms · Serve images in next-gen formats −780ms · Reduce unused CSS −310ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.d4ad1d5026.js.pagespeed.ce.LL 50KB · /en/ 49KB
- скрипты грузят процессор: /wp-content/plugins/solid-sound-custom/build/assets/home-js.2b2164eb44.js.pagespeed.ce.HhP 1971ms · /en/ 1934ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1478ms · Unattributable 773ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 675ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 365ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −4407ms · Reduce unused CSS −390ms · Serve images in next-gen formats −390ms · Reduce unused JavaScript −200ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.d4ad1d5026.js.pagespeed.ce.LL 50KB · /en/ 49KB
- скрипты грузят процессор: /en/ 472ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.2b2164eb44.js.pagespeed.ce.HhP 143ms · Unattributable 135ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 132ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 98ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 75ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1432ms · Serve images in next-gen formats −940ms · Defer offscreen images −630ms · Reduce unused CSS −630ms · Reduce unused JavaScript −630ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 2820ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1584ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 880ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 718ms · Unattributable 484ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 354ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −3048ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 1168ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 323ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 270ms · Unattributable 121ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 106ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 64ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −4388ms · Reduce unused JavaScript −1570ms · Reduce unused CSS −480ms · Properly size images −460ms · Defer offscreen images −310ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-73dfec4f9debad8b 765KB · https://newassets.hcaptcha.com/c/c9d3a11cd5d78990419b5dcde7f392c8f411b227ffbf345ee89052d71 678KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ef05d7acecbabef2faaa 452KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 292KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-863ee51b61b3fee6e0ad27ab3a9f5220.js 204KB
- скрипты грузят процессор: /product/infinity/ 1725ms · Unattributable 683ms · https://js.stripe.com/v3/?ver=3.0 637ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 530ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 464ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 312ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −2449ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-73dfec4f9debad8b 765KB · https://newassets.hcaptcha.com/c/c9d3a11cd5d78990419b5dcde7f392c8f411b227ffbf345ee89052d71 678KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ef05d7acecbabef2faaa 452KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 291KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-863ee51b61b3fee6e0ad27ab3a9f5220.js 204KB
- скрипты грузят процессор: /product/infinity/ 433ms · https://js.stripe.com/v3/?ver=3.0 150ms · Unattributable 145ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 116ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 99ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 85ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1247ms · Reduce unused CSS −630ms · Reduce unused JavaScript −310ms · Defer offscreen images −250ms · Serve images in next-gen formats −250ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 712ms · Unattributable 377ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 285ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 247ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 220ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 207ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −2818ms · Reduce unused CSS −190ms · Serve images in next-gen formats −190ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 225ms · Unattributable 106ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 77ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 74ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1294ms · Reduce unused CSS −580ms · Defer offscreen images −310ms · Serve images in next-gen formats −310ms · Reduce unused JavaScript −270ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 100KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 597ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 378ms · Unattributable 369ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 231ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 203ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 153ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1416ms · Reduce unused CSS −200ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 203ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 73ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 63ms · Unattributable 56ms
