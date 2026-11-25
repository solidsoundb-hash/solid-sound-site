# Скорость сайта — 2026-11-25 11:10 UTC

После выкладки: 2026-11-25T11:00:09Z SEO п.6 (владелец 25.11: «готовое и проверенное выкладывайте сами», задача от «Задач»): на /all-products/ шапка «Turn Your Ideas…» — div вм (медиана 3 замеров)

Lighthouse на роботе GitHub (ключа PageSpeed нет). Оценка 90–100 — отлично, 50–89 — средне, ниже 50 — плохо. «Главный блок» (LCP) — когда видно основное: норма до 2.5 с.

## ✅ Скорость не ухудшилась

| Страница | Устройство | Оценка | Главный блок (LCP) | Первый показ | Блокировка | Сдвиги (CLS) | Вес | Запросов | Ответ сервера |
|---|---|---|---|---|---|---|---|---|---|
| /product/infinity/ | телефон | **57** | 10.7 с | 2.9 с | 262 мс | 0 | 5072 КБ | 211 | 2.7 с |

## Почему медленно (Lighthouse)

### /product/infinity/ — mobile
- можно выиграть: Reduce initial server response time −2666ms · Reduce unused JavaScript −1380ms · Reduce unused CSS −400ms · Properly size images −250ms · Serve images in next-gen formats −230ms
- тяжёлые файлы: https://newassets.hcaptcha.com/c/841109ec7de383beac2befed7d1276159e6e979e04491826a861a577d 705KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-accessory-render-e614396dc94b5762 653KB · https://pay.google.com/gp/p/ui/pay 423KB · https://js.stripe.com/v3/fingerprinted/js/controller-with-preconnect-f2a26be5952c5bcf14a6b 384KB · https://newassets.hcaptcha.com/captcha/v1/7b6de2b378178a05e8aa25e42494ec5883aa37f6/static/ 320KB · https://js.stripe.com/v3/?ver=3.0 270KB · https://js.stripe.com/v3/fingerprinted/js/elements-inner-payment-method-messaging-fe505415 247KB · https://hcaptcha.com/1/api.js?onload=captchaLoad&render=explicit 218KB
- скрипты грузят процессор: /product/infinity/ 1653ms · https://js.stripe.com/v3/?ver=3.0 645ms · Unattributable 547ms · /wp-content/plugins/solid-sound-custom/build/assets/ss-global.ee082ae5e4.js.pagespeed.ce.o 377ms · /wp-content/themes/Impreza/js/us.core.min.js,qver=8.7.pagespeed.ce.ACVwiv8arX.js 301ms · https://www.googletagmanager.com/gtag/js?id=G-4J1F7EGCCW 276ms
