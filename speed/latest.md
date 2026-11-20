# Скорость сайта — 2026-11-20 14:26 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (телефон): страница «прыгает» при загрузке (CLS 0.159, норма до 0.1)
- /en/all-products/ (телефон): страница «прыгает» при загрузке (CLS 0.189, норма до 0.1)

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **27** (+0) | 6.9 с | 3.3 с | 2137 мс | 0.159 | 1235 КБ | 66 | 2.6 с |
| /en/ | компьютер | **76** (+8) | 2.5 с | 1.3 с | 107 мс | 0.013 | 1239 КБ | 71 | 1.4 с |
| /en/all-products/ | телефон | **0** (+0) | 0.0 с | 3.0 с | 0 мс | 0.189 | 1418 КБ | 88 | 5.7 с |
| /en/all-products/ | компьютер | **0** (+0) | 0.0 с | 1.2 с | 0 мс | 0.056 | 1527 КБ | 91 | 10.1 с |
| /product/infinity/ | телефон | **50** (-4) | 14.3 с | 2.7 с | 497 мс | 0.081 | 4878 КБ | 186 | 1.2 с |
| /product/infinity/ | компьютер | **72** (+7) | 3.1 с | 1.4 с | 0 мс | 0.05 | 5114 КБ | 196 | 1.9 с |
| /en/studio-calculator/ | телефон | **83** (+3) | 2.9 с | 2.2 с | 230 мс | 0 | 1261 КБ | 64 | 2.3 с |
| /en/studio-calculator/ | компьютер | **93** (+13) | 1.2 с | 1.0 с | 0 мс | 0.03 | 1269 КБ | 64 | 1.2 с |
| /en/wiki/ | телефон | **89** (+15) | 2.4 с | 2.2 с | 134 мс | 0 | 752 КБ | 56 | 2.3 с |
| /en/wiki/ | компьютер | **89** | 1.4 с | 1.2 с | 0 мс | 0.002 | 752 КБ | 56 | 1.6 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −2574ms · Reduce unused CSS −520ms · Reduce unused JavaScript −260ms · Serve images in next-gen formats −260ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.2b38acb177.js.pagespeed.ce.Xb 50KB · /en/ 49KB
- скрипты грузят процессор: /wp-content/plugins/solid-sound-custom/build/assets/home-js.2b2164eb44.js.pagespeed.ce.HhP 3176ms · /en/ 3016ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 1218ms · Unattributable 922ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 422ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 383ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1438ms · Serve images in next-gen formats −410ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.2b38acb177.js.pagespeed.ce.Xb 50KB · /en/ 49KB
- скрипты грузят процессор: /en/ 417ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.2b2164eb44.js.pagespeed.ce.HhP 196ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 139ms · Unattributable 130ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 123ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 92ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −5708ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 3077ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1456ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1054ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 620ms · Unattributable 618ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 228ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −10109ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/all-products/ 46KB
- скрипты грузят процессор: /en/all-products/ 968ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 343ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 342ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 293ms · Unattributable 153ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 56ms

### /product/infinity/ — mobile
- можно выиграть: Reduce unused JavaScript −1720ms · Reduce initial server response time −1235ms · Reduce unused CSS −780ms · Defer offscreen images −520ms · Properly size images −410ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/8ec71d08704a3e8916b531ccd30bbaffaf77154337b9e8dcf81791d12 674KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-73dfec4f9debad8b 651KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ef05d7acecbabef2faaa 379KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 268KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-863ee51b61b3fee6e0ad27ab3a9f5220.js 184KB
- скрипты грузят процессор: /product/infinity/ 1725ms · Unattributable 653ms · https://js.stripe.com/v3/?ver=3.0 627ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 456ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 449ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 310ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1898ms · Reduce unused JavaScript −290ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-73dfec4f9debad8b 766KB · https://newassets.hcaptcha.com/c/8ec71d08704a3e8916b531ccd30bbaffaf77154337b9e8dcf81791d12 674KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ef05d7acecbabef2faaa 452KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 292KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB · https://js.stripe.com/v3/fingerprinted/js/shared-863ee51b61b3fee6e0ad27ab3a9f5220.js 205KB
- скрипты грузят процессор: /product/infinity/ 465ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 163ms · https://js.stripe.com/v3/?ver=3.0 141ms · Unattributable 99ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 97ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 94ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −2255ms · Reduce unused CSS −260ms · Defer offscreen images −220ms · Serve images in next-gen formats −220ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 723ms · Unattributable 503ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 367ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 263ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 263ms · https://connect.facebook.net/en_US/fbevents.js 145ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1159ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 231ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 79ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 71ms · Unattributable 60ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 56ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −2258ms · Reduce unused CSS −260ms · Defer offscreen images −220ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 611ms · Unattributable 353ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 322ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 229ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 182ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 135ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −1595ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /en/wiki/ 36KB
- скрипты грузят процессор: /en/wiki/ 239ms · Unattributable 81ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 79ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 57ms
