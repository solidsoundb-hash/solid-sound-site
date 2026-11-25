# Скорость сайта — 2026-11-25 04:46 UTC

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ✅ Скорость не ухудшилась

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /en/ | телефон | **37** (+4) | 8.6 с | 3.1 с | 1315 мс | 0.001 | 1153 КБ | 69 | 1.7 с |
| /en/ | компьютер | **75** (+4) | 2.6 с | 1.5 с | 6 мс | 0.002 | 1158 КБ | 73 | 1.5 с |
| /en/all-products/ | телефон | **59** (+17) | 4.5 с | 3.1 с | 478 мс | 0.002 | 1288 КБ | 87 | 1.8 с |
| /en/all-products/ | компьютер | **78** (+2) | 2.0 с | 1.6 с | 48 мс | 0.013 | 1328 КБ | 93 | 1.8 с |
| /product/infinity/ | телефон | **64** (+12) | 10.8 с | 2.8 с | 57 мс | 0 | 5307 КБ | 212 | 1.7 с |
| /product/infinity/ | компьютер | **72** (-3) | 3.0 с | 1.5 с | 0 мс | 0.022 | 5304 КБ | 218 | 1.5 с |
| /en/studio-calculator/ | телефон | **87** (+0) | 2.8 с | 2.5 с | 9 мс | 0 | 1177 КБ | 66 | 1.4 с |
| /en/studio-calculator/ | компьютер | **85** (-2) | 1.5 с | 1.3 с | 0 мс | 0.078 | 1177 КБ | 66 | 1.4 с |
| /en/wiki/ | телефон | **87** (-2) | 2.9 с | 2.5 с | 15 мс | 0 | 645 КБ | 55 | 1.6 с |
| /en/wiki/ | компьютер | **84** (-4) | 1.5 с | 1.3 с | 0 мс | 0.002 | 654 КБ | 55 | 3.2 с |

## Почему медленно (Lighthouse)

### /en/ — mobile
- можно выиграть: Reduce initial server response time −1687ms · Reduce unused CSS −930ms · Reduce unused JavaScript −310ms · Properly size images −210ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 53KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 2390ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1142ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.674778f5eb.js.pagespeed.ce.g 739ms · Unattributable 730ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 542ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 537ms

### /en/ — desktop
- можно выиграть: Reduce initial server response time −1497ms · Reduce unused CSS −250ms · Reduce unused JavaScript −210ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png 90KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/ 53KB · /wp-content/uploads/Intricate_Logo_JPG-400x400.jpg 50KB · /wp-content/plugins/solid-sound-custom/build/assets/home-app.c63eb6f6e0.js.pagespeed.ce.sf 50KB · /wp-content/uploads/Infinity_Logo_JPG-400x400.jpg 48KB
- скрипты грузят процессор: /en/ 255ms · Unattributable 144ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 74ms · /wp-content/plugins/solid-sound-custom/build/assets/react.d949f1c368.js.pagespeed.ce.6RsmF 71ms · /wp-content/plugins/solid-sound-custom/build/assets/home-js.0edfd39d07.js.pagespeed.ce.MGT 53ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1833ms · Defer offscreen images −930ms · Reduce unused CSS −930ms · Serve images in next-gen formats −620ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 54KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1204ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 867ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 768ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 637ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.674778f5eb.js.pagespeed.ce.g 436ms · Unattributable 356ms

### /en/all-products/ — desktop
- можно выиграть: Reduce initial server response time −1815ms · Defer offscreen images −200ms · Reduce unused CSS −200ms · Serve images in next-gen formats −200ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-600x600.png.pagespeed.ic.LMmsGBS 173KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 54KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 352ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 313ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 226ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 141ms · Unattributable 113ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 55ms

### /product/infinity/ — mobile
- можно выиграть: Reduce unused JavaScript −1670ms · Reduce initial server response time −1658ms · Reduce unused CSS −930ms · Properly size images −540ms · Serve images in next-gen formats −460ms · Defer offscreen images −390ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 766KB · https://newassets.hcaptcha.com/c/670037ee498bf605f2c1d3b69d3098ebc8fbef4860b5ec2c670374580 692KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 457KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 294KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 251KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1204ms · Unattributable 668ms · https://js.stripe.com/v3/?ver=3.0 584ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 259ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.674778f5eb.js.pagespeed.ce.g 229ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 171ms

### /product/infinity/ — desktop
- можно выиграть: Reduce initial server response time −1543ms · Reduce unused JavaScript −180ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 766KB · https://newassets.hcaptcha.com/c/670037ee498bf605f2c1d3b69d3098ebc8fbef4860b5ec2c670374580 692KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 456KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 294KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 251KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 332ms · Unattributable 144ms · https://js.stripe.com/v3/?ver=3.0 110ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 57ms

### /en/studio-calculator/ — mobile
- можно выиграть: Reduce initial server response time −1439ms · Defer offscreen images −310ms · Reduce unused CSS −310ms · Serve images in next-gen formats −310ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_486_0/static/js/widget.d94c078352d7cc9b702d.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_486_0/static/js/chunk-WidgetIframe-d94c078352d7cc9b702d. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: Unattributable 442ms · /en/studio-calculator/ 380ms · https://code.tidio.co/widget-v4/1_486_0/static/js/widget.d94c078352d7cc9b702d.js 209ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 177ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 165ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.674778f5eb.js.pagespeed.ce.g 92ms

### /en/studio-calculator/ — desktop
- можно выиграть: Reduce initial server response time −1437ms · Reduce unused CSS −210ms
- тяжёлые файлы: https://code.tidio.co/widget-v4/1_486_0/static/js/widget.d94c078352d7cc9b702d.js 241KB · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · https://code.tidio.co/widget-v4/1_486_0/static/js/chunk-WidgetIframe-d94c078352d7cc9b702d. 137KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · https://code.tidio.co/widget-v4/fonts/inter_UcCo3FwrK3iLTcviYwYZ8UA3.woff2 72KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /en/studio-calculator/ 41KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB
- скрипты грузят процессор: /en/studio-calculator/ 109ms · Unattributable 68ms

### /en/wiki/ — mobile
- можно выиграть: Reduce initial server response time −1616ms · Defer offscreen images −310ms · Reduce unused CSS −310ms · Serve images in next-gen formats −310ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · /en/wiki/ 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · https://fonts.gstatic.com/l/font?kit=7cHAv4okm5zmbvwkAx_sfcEuiD8jvvL-NfhOdgdoow1KVvIp4hYku 32KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 449ms · Unattributable 241ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 205ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 143ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 83ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.674778f5eb.js.pagespeed.ce.g 83ms

### /en/wiki/ — desktop
- можно выиграть: Reduce initial server response time −3189ms · Reduce unused CSS −200ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 40KB · https://fonts.gstatic.com/s/exo2/v26/7cHmv4okm5zmbtYoK-4W4nIp.woff2 40KB · /en/wiki/ 40KB · https://fonts.gstatic.com/s/roboto/v51/KFO7CnqEu92Fr1ME7kSn66aGLdTylUAMa3yUBHMdazQ.woff2 37KB · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 29KB
- скрипты грузят процессор: /en/wiki/ 98ms · Unattributable 77ms
