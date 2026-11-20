# Скорость сайта — 2026-11-20 14:04 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /en/ (телефон): страница «прыгает» при загрузке (CLS 0.165, норма до 0.1)
- /en/ (компьютер): оценка упала 78 → 68
- /en/studio-calculator/ (телефон): оценка упала 89 → 80
- /en/studio-calculator/ (компьютер): оценка упала 91 → 80
- /en/studio-calculator/ (компьютер): главный блок появляется позже 1.2 → 1.7 с
- /en/wiki/ (телефон): оценка упала 83 → 74

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **27** (-4) | 8.3 с | 3.2 с | 1790 мс | 0.165 | 1751 КБ | 75 | 2.2 с |
| /en/ | компьютер | **68** (-10) | 3.0 с | 1.7 с | 50 мс | 0.022 | 1755 КБ | 79 | 3.8 с |
| /en/all-products/ | телефон | **0** (+0) | 0.0 с | 3.0 с | 0 мс | 0.002 | 1936 КБ | 97 | 2.8 с |
| /en/all-products/ | компьютер | **0** (+0) | 0.0 с | 1.7 с | 0 мс | 0.055 | 2007 КБ | 99 | 5.4 с |
| /product/infinity/ | телефон | **54** (+7) | 13.9 с | 3.0 с | 240 мс | 0.083 | 5388 КБ | 197 | 6.8 с |
| /product/infinity/ | компьютер | **65** (-4) | 4.2 с | 1.7 с | 0 мс | 0.043 | 5375 КБ | 202 | 1.6 с |
| /en/studio-calculator/ | телефон | **80** (-9) | 3.0 с | 2.7 с | 56 мс | 0 | 1270 КБ | 64 | 6.6 с |
| /en/studio-calculator/ | компьютер | **80** (-11) | 1.7 с | 1.4 с | 0 мс | 0.03 | 1270 КБ | 64 | 4.8 с |
| /en/wiki/ | телефон | **74** (-9) | 4.1 с | 2.7 с | 57 мс | 0 | 1268 КБ | 64 | 4.4 с |
| /en/wiki/ | компьютер | не измерено: Lighthouse was unable to reliably load the page you requested. Make sure you are testing the correct URL and that the server is properly responding to all requests. (Status code: 503) |||||||

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −2214ms · Reduce unused CSS −340ms · Reduce unused JavaScript −340ms · Serve images in next-gen formats −280ms · Properly size images −280ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB
- скрипты грузят процессор: /en/ 2109ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.2b2164eb44.js.pagespeed.ce.HhP 1873ms · Unattributable 1132ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 899ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 513ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 433ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −3812ms · Reduce unused CSS −170ms · Serve images in next-gen formats −170ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB
- скрипты грузят процессор: /en/ 368ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 263ms · Unattributable 156ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 107ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.2b2164eb44.js.pagespeed.ce.HhP 101ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 81ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −2790ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB
- скрипты грузят процессор: /en/all-products/ 2423ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1149ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 720ms · Unattributable 504ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 362ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 335ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −5363ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB
- скрипты грузят процессор: /en/all-products/ 676ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 237ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 226ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 200ms · Unattributable 148ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 83ms

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −6789ms · Reduce unused JavaScript −1380ms · Reduce unused CSS −520ms · Defer offscreen images −340ms · Properly size images −250ms · Serve images in next-gen formats −190ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/1142b16d07943be5e6f6aac90001f8f8df88fc8902aaaa8284d5c70a0 675KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-73dfec4f9debad8b 651KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ef05d7acecbabef2faaa 379KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 268KB · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1367ms · Unattributable 603ms · https://js.stripe.com/v3/?ver=3.0 409ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 365ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 309ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 248ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1609ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/1142b16d07943be5e6f6aac90001f8f8df88fc8902aaaa8284d5c70a0 675KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-73dfec4f9debad8b 651KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-8ef05d7acecbabef2faaa 379KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 307KB · https://js.stripe.com/v3/?ver=3.0 268KB · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 356ms · Unattributable 153ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 101ms · https://js.stripe.com/v3/?ver=3.0 95ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 86ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 68ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −6576ms · Reduce unused CSS −340ms · Defer offscreen images −250ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 558ms · Unattributable 297ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 228ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 189ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 169ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 131ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −4822ms · Reduce unused CSS −210ms · Serve images in next-gen formats −210ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 150ms · Unattributable 86ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 58ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −4411ms · Reduce unused CSS −570ms · Reduce unused JavaScript −340ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_484_0/static/js/chunk-WidgetIframe-7265abbbfc4f5d4e21bb. 137KB · https://connect.facebook.net/en_US/fbevents.js 97KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/wiki/ 565ms · Unattributable 433ms · https://code.tidio.co/widget-v4/1_484_0/static/js/widget.7265abbbfc4f5d4e21bb.js 236ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 216ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 180ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ac3ad69d43.js.pagespeed.ce.s 104ms
