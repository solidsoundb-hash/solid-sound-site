# Скорость сайта — 2026-11-26 06:50 UTC

После выкладки: 2026-11-26 06:48:09 отмена заказа в 2 шага (Confirm withdrawal here), язык EN, радиус кнопки; H1 каталога снова видимый (баннер) (медиана 3 замеров)

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ✅ Скорость не ухудшилась

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /product/infinity/ | телефон | **53** (+4) | 10.3 с | 2.8 с | 416 мс | 0.068 | 5054 КБ | 214 | 1.5 с |
| /en/all-products/ | телефон | **43** (+1) | 4.8 с | 3.1 с | 1257 мс | 0.058 | 1288 КБ | 87 | 3.8 с |

## Почему медленно (Lighthouse)

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −1535ms · Reduce unused JavaScript −1370ms · Reduce unused CSS −620ms · Serve images in next-gen formats −310ms · Properly size images −310ms · Defer offscreen images −160ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/e8834341727135fe0bad7df35ca2a2f4531587b2907ef6023bf59df97 688KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 321KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 2250ms · https://js.stripe.com/v3/?ver=3.0 937ms · Unattributable 685ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 450ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 311ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 292ms

### /en/all-products/ — mobile
- можно выиграть: Reduce initial server response time −3837ms · Defer offscreen images −930ms · Reduce unused CSS −930ms · Serve images in next-gen formats −620ms
- тяжёлые файлы: https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 179KB · /wp-content/themes/Impreza/fonts/fa-solid-900.woff2?ver=8.7 134KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007.png.pagespeed.ic.u9b4g-4Kgc.webp 80KB · /wp-content/uploads/x619e28af-c8e8-4029-a58d-54ac6f895007-400x400.png.pagespeed.ic._2mWI_K 75KB · /wp-content/themes/Impreza/css/A.style.min.css,qver=8.7.pagespeed.cf.BEABbXGU5z.css 73KB · /en/all-products/ 55KB · /wp-content/uploads/%D0%9C%D0%BE%D0%BD%D1%82%D0%B0%D0%B6%D0%BD%D0%B0%D1%8F-%D0%BE%D0%B1%D0 50KB · /wp-content/plugins/js_composer/assets/css/A.js_composer.min.css,qver=6.8.0.pagespeed.cf.C 46KB
- скрипты грузят процессор: /en/all-products/ 2723ms · /wp-content/plugins/solid-sound-custom/build/assets/theme-player-js.868bec0162.js.pagespee 1601ms · /wp-includes/js/jquery/jquery.min.js,qver=3.7.1.pagespeed.ce.gm63foawKr.js 1303ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 1300ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.117e5f0386.js.pagespeed.ce.v 788ms · Unattributable 504ms
