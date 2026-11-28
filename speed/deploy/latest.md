# Скорость сайта — 2026-11-28 10:30 UTC

После выкладки: 2026-11-28T10:28:27Z SEO: title статей вики «Вопрос? …» короче (режется после «?»). Вид не меняется. Откат: revert (медиана 3 замеров)

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ⚠️ Стало хуже / проблемы

- /product/infinity/ (телефон): оценка упала 59 → 54
- /en/all-products/ (телефон): оценка упала 48 → 43

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /product/infinity/ | телефон | **54** (-5) | 9.9 с | 2.7 с | 442 мс | 0 | 5062 КБ | 213 | 1.3 с |
| /en/all-products/ | телефон | **43** (-5) | 6.3 с | 2.9 с | 1205 мс | 0.036 | 1289 КБ | 86 | 1.3 с |

## Почему медленно (Lighthouse)

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1284ms · Reduce unused JavaScript −1280ms · Reduce unused CSS −530ms · Serve images in next-gen formats −260ms · Properly size images −260ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/a0c76af380619214caa3425ae35e673db96b8e22092d8f992a3c015fb 681KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/a7305732738ccf3b7862eab7fe6d8f7b136a48f4/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 219KB
- скрипты грузят процессор: /product/infinity/ 1794ms · https://js.stripe.com/v3/?ver=3.0 845ms · Unattributable 715ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d748abc7f5.js.pagespeed.ce.S 397ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 387ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 344ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −1335ms · Defer offscreen images −790ms · Reduce unused CSS −790ms · Serve images in next-gen formats −790ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 56KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 1753ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1490ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1455ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1147ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.d748abc7f5.js.pagespeed.ce.S 538ms · Unattributable 444ms
