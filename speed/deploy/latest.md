# Скорость сайта — 2026-11-25 17:33 UTC

После выкладки: 2026-11-25 17:31:03 site-leads: заявки форм в админке (Requests) + Reply-To в письме об отмене (медиана 3 замеров)

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ✅ Скорость не ухудшилась

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /product/infinity/ | телефон | **49** (-2) | 10.3 с | 2.7 с | 492 мс | 0 | 5338 КБ | 212 | 4.3 с |
| /en/all-products/ | телефон | **42** (+2) | 6.2 с | 3.0 с | 1254 мс | 0.036 | 1288 КБ | 86 | 1.7 с |

## Почему медленно (Lighthouse)

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −4347ms · Reduce unused JavaScript −1030ms · Reduce unused CSS −410ms · Defer offscreen images −270ms · Properly size images −250ms
- тяжёлые файлы: https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 768KB · https://newassets.hcaptcha.com/c/051e795ed753387779414716a5b365273b5ac68651114ec46440a5d62 714KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 457KB · https://pay.google.com/gp/p/ui/pay 423KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 294KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 252KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1788ms · https://js.stripe.com/v3/?ver=3.0 881ms · Unattributable 552ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 372ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 344ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 328ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1702ms · Reduce unused CSS −800ms · Defer offscreen images −540ms · Serve images in next-gen formats −540ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1642ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1588ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1567ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1194ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 583ms · Unattributable 573ms
