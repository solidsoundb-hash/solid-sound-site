# Скорость сайта — 2026-11-25 16:31 UTC

После выкладки: 2026-11-25T16:29:00Z SEO п.8 (задача «Задач», владелец 25.11: проверенное выкладывать самим): alt у логотипа шапки («Solid Sound»), значков оплаты (Mastercard…) (медиана 3 замеров)

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /product/infinity/ (телефон): оценка упала 57 → 51

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /product/infinity/ | телефон | **51** (-6) | 9.9 с | 2.7 с | 547 мс | 0 | 5065 КБ | 212 | 1.7 с |
| /en/all-products/ | телефон | **40** | 6.3 с | 3.0 с | 1181 мс | 0.036 | 1288 КБ | 86 | 2.8 с |

## Почему медленно (Lighthouse)

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1667ms · Reduce unused JavaScript −1170ms · Reduce unused CSS −270ms · Properly size images −270ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/cdba93a00fb313b4c31953c0ec66e9ba478c187a10fef88bebbcf8180 690KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 2056ms · https://js.stripe.com/v3/?ver=3.0 915ms · Unattributable 720ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ee082ae5e4.js.pagespeed.ce.o 419ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 374ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 356ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −2762ms · Reduce unused CSS −820ms · Defer offscreen images −540ms · Serve images in next-gen formats −540ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 2087ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1633ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1575ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1279ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ee082ae5e4.js.pagespeed.ce.o 521ms · Unattributable 488ms
