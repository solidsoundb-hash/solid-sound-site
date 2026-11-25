# Документы сайта для любой схемы оплаты — 25.11.2026

Автор: «Юрист» (Claude). Задача от «Задач» (владелец 25.11: «делай всё»). **На сайт не публиковать**, пока не выбрана схема оплаты
(А — ФОП жены + Plata by mono; Б — французская micro-entreprise + Stripe). Тексты для клиентов — по-английски.
Метки: **[точно]**, **[вероятно]**, **[спросить]** — как в `oplata-i-vyplaty-avtoram-2026-11-24.md`.
Личных данных здесь нет (репозиторий публичный) — только поля `{{…}}`, заполнить на сайте.

## 0. Что сейчас на сайте и что поменять (проверено 25.11.2026 через WordPress, только чтение)

| Страница | Что хорошо | Что не так | Что сделать |
|---|---|---|---|
| **Terms of Sale** (2169, /en/terms/) | Есть отказ от права на отзыв для цифровых товаров (L221-28 / ст. 16(m)), услуги 14 дней, соответствие, ответственность | 1) Продавец «Søld Søund Studio, Cannes» — **нет юр. лица** (имя, SIREN / код ФОП). 2) «Prices are set in US dollars» — а магазин в **EUR**. 3) «Some products are created by independent creators; Solid Sound handles the sale, payment» — это как раз та модель, которую на тесте через ФОП делать нельзя. 4) Медиатор «details on request» — во Франции нужно **назвать медиатора** (Code de la consommation L612-1, L616-1) **[точно]**. 5) Пишем про галочку в оформлении — **проверить, что она реально есть** | Заменить на текст из раздела 1 |
| **Legal Notice** (2170) | Есть сайт, e-mail, хостинг | Для Франции (LCEN, ст. 6-III) не хватает: полного имени/названия, адреса, телефона, SIREN, номера НДС (если есть), ответственного за публикацию, адреса и телефона хостинга **[точно]** | Блок реквизитов из раздела 3 |
| **Refund Policy** (35) | Хороший, понятный, 30 дней на «не работает» | Нет срока возврата денег при отказе от услуги «в пределах 14 дней» ✔ есть; нет **кнопки/формы отказа** (см. ниже); не упомянут способ оплаты по схеме | Обновить по разделу 2 |
| **License** (385) | Понятные «можно/нельзя» | 1) **Право Великобритании** и суды UK — противоречит Terms (право Франции); 2) «Licensor = site, its owners… or authors of templates» — непонятно, кто лицензиар; 3) старый текст 2022 | Право и лицензиар — по схеме (раздел 1, п. 9); для товаров авторов — «sublicensed by the seller» |
| **Creator Terms** (1972) | 70 % / 85 % основателям, неэксклюзив, автор сохраняет авторство | 1) Цены и порог **$25** — владелец говорил €25 (`modules/creator-tabs.php`); 2) «Solid Sound handles the sale» + выплаты PayPal/банком — **схема выплат не выбрана**, обещать нельзя; 3) нет налоговой части (15 % у нерезидентов при схеме А, сбор данных DAC7 при схеме Б); 4) нет договора-лицензии | До выбора схемы: добавить строку «Payouts start once our payment setup for creators is live; we will email you» и не принимать новые продажи авторов (кнопка «скоро»). После — договор из раздела 4 |

**Новое с 19.06.2026 (ЕС):** директива 2023/2673 требует на сайте **«кнопку отказа»** от онлайн-договора («Withdraw from contract here»),
где действует право на отзыв — у нас это студийные услуги **[вероятно — проверить, как Франция её ввела]**.
→ Разработчику: простая страница/форма «Cancel an order» (номер заказа + e-mail + кнопка → письмо-подтверждение).

**Галочка при оформлении (обязательна для цифровых товаров) [точно]** — без неё покупатель сохраняет 14 дней на отзыв даже после скачивания:
> ☐ I want my download right away. I agree that the content is delivered immediately and I understand that I lose my 14-day right of withdrawal once the download is made available.

Подтверждение этого — в письме о заказе (durable medium) **[точно, ст. 8(7) директивы 2011/83]**. Для услуг — отдельная галочка:
> ☐ Please start work on my order now. I understand that if I cancel within 14 days I pay for the work already done, and that I lose the right to cancel once the service is fully delivered.

## 1. Terms of Sale — новый текст (EN, с вариантами А/Б)

> **Terms of Sale** — *Last updated: {{date}}*
>
> **1. Seller.** Products on solid-sound.net are sold by:
> — *[Variant A]* **{{full name}}, sole proprietor (FOP), Ukraine**, taxpayer no. {{РНОКПП — only on the site}}, address {{address}}.
> — *[Variant B]* **{{full name}}, entrepreneur individuel (micro-entreprise), France**, SIREN {{SIREN}}, address {{address}}{{, VAT no. — or: "VAT not applicable, art. 293 B of the CGI"}}.
> Contact: {{e-mail}}, {{phone}}. Full details: [Legal Notice](/legal-notice/).
>
> **2. What we sell.** Downloadable products (Ableton Live templates, presets, sample packs, music) and online studio services (mixing, mastering, production). *[Until the creator scheme is chosen:]* All products in our store are sold by the seller named above. *[Later, licence model:]* Some products are created by independent creators and licensed to us for sale; the seller named above sells them in its own name and is responsible to you for them.
>
> **3. Licence.** You buy a licence to use the content, not ownership. Use is governed by our [License Agreement](/license/).
>
> **4. Prices and payment.** Prices are set in euros (EUR) and may be shown in your local currency for information. The price at checkout, including any VAT due in your country, is the final price. Payment is taken when you place the order through {{Plata by mono (monobank) / Stripe}}. Your bank may charge a currency conversion fee.
>
> **5. Delivery.** Download links appear right after payment on the order page, in your account and in your confirmation e-mail. Studio services are delivered as described on the service page.
>
> **6. Right of withdrawal.** *Digital products:* at checkout you ask us to deliver immediately and confirm that you lose your 14-day right of withdrawal once the download is made available (Directive 2011/83/EU, art. 16(m)). We confirm this in your order e-mail. *Studio services:* you may cancel within 14 days of ordering. If you asked us to start earlier, you pay for the work done until you cancel; once the service is fully delivered, the right ends. To cancel, use the [Cancel an order](/cancel-order/) form or e-mail us with your order number.
>
> **7. If something does not work.** You have the legal guarantee of conformity for digital content and services. If a file is damaged, incomplete or not as described, contact us and we will fix, replace or refund it ([Refund Policy](/refund_returns/)).
>
> **8. Studio services.** Scope, price, delivery time and revisions are shown on the service page or agreed in writing before work starts. You confirm you have the rights to the material you send. Delivery times are estimates. Your files stay confidential and are used only for your order.
>
> **9. Law and disputes.** *[A]* These terms are governed by the law of Ukraine. *[B]* These terms are governed by French law. If you are a consumer, you keep the protection of the mandatory rules of your country of residence. Please contact us first — most issues are solved within days. *[B]* You may also contact our consumer mediator for free: {{mediator name, website}}.
>
> **10. Liability.** We are liable for foreseeable damage caused by our breach of these terms. We are not liable for problems caused by your software, hardware or third-party plugins. Nothing limits liability that cannot be limited by law.
>
> **11. Personal data.** See our [Privacy Policy](/privacy-policy/).

Примечания: [А] право Украины — для покупателей из ЕС всё равно действуют их обязательные нормы (Рим I, ст. 6) **[точно]**; закон Украины
«О защите прав потребителей» 2023 также содержит исключение для цифрового контента **[вероятно, спросить бухгалтера/юриста]**.
[Б] медиатор потребителей обязателен для продавца во Франции — выбрать из списка CECMC (есть недорогие, от ~€50–100/год) **[спросить: цена]**.

## 2. Refund Policy — правки (EN)
Текущий текст оставить, заменить/добавить:
> **How to ask.** Use the [Cancel an order](/cancel-order/) form or e-mail {{e-mail}} with your order number. Approved refunds go back to the original payment method ({{card via Plata by mono / Stripe}}) within 14 days. If you paid in another currency, the amount returned in your currency may differ slightly because of exchange rates set by your bank.
>
> **Before you buy.** Every product page lists the DAW and version, and any third-party plugins it needs. Not sure? Ask us before you buy — we answer fast.

## 3. Блок реквизитов продавца (Legal Notice) — поля-заглушки

| Поле (EN на сайте) | Вариант А — ФОП | Вариант Б — micro-entreprise |
|---|---|---|
| Seller / Publisher | {{ПІБ}}, sole proprietor (FOP), Ukraine | {{Nom Prénom}}, entrepreneur individuel (micro-entreprise) |
| Registration no. | {{РНОКПП / номер запису в ЄДР}} | SIREN {{…}} (RCS/RNE) |
| VAT | Not a VAT payer in Ukraine; EU VAT: {{non-Union OSS no., if registered}} | {{n° TVA}} or "TVA non applicable, art. 293 B du CGI" |
| Address | {{адрес}} | {{adresse}} |
| E-mail / Phone | {{…}} | {{…}} |
| Director of publication | {{ПІБ}} | {{Nom}} |
| Hosting | adm.tools — {{юр. название хостинга, адрес, телефон}} | то же |
| Payment provider | Plata by mono, JSC "Universal Bank" | Stripe Payments Europe Ltd |
| Consumer mediator | — (contact us) | {{название, сайт}} |

Брендовое имя «Søld Søund Studio» можно оставить как торговое имя: «Solid Sound is a trading name of {{seller}}» **[точно — так можно]**.

## 4. Лицензионный договор с автором (роялти) — шаблон, UA + EN

Используется в схеме А (ФОП) для авторов-ФОП и иностранцев, и как основа для схемы Б. Перед подписанием — проверка бухгалтером
(налог у нерезидента, лимиты НБУ). Подписание — электронно (галочка в кабинете + PDF на e-mail) **[спросить: для нерезидентов достаточно ли]**.

---
**ЛІЦЕНЗІЙНИЙ ДОГОВІР № {{…}} / LICENSE AGREEMENT No. {{…}}**
{{місто/city}}, {{дата/date}}

**Ліцензіар / Licensor:** {{ПІБ або назва автора / author's name}}, {{країна податкового резидентства / country of tax residence}}, {{податковий номер / tax ID}} (далі — «Автор» / the "Author").
**Ліцензіат / Licensee:** {{ПІБ продавця}}, {{ФОП (Україна) / micro-entreprise (France)}}, {{код / SIREN}} (далі — «Продавець» / the "Seller").

**1. Предмет / Subject.**
UA: Автор надає Продавцю невиключну ліцензію на твори, перелічені в Додатку 1 або завантажені Автором у кабінет на solid-sound.net (далі — «Твори»): право відтворювати, публікувати, демонструвати прев'ю та продавати кінцевим покупцям субліцензії на використання Творів на умовах License Agreement сайту, на території всього світу, на строк дії договору.
EN: The Author grants the Seller a non-exclusive, worldwide licence for the works listed in Annex 1 or uploaded by the Author to their account on solid-sound.net (the "Works"): to reproduce, publish, show previews of and sell to end customers sublicences to use the Works under the site's License Agreement, for the term of this agreement.

**2. Гарантії Автора / Author's warranties.**
UA: Автор є автором Творів або має всі права на них; Твори не містять чужих семплів, пресетів чи мелодій без права на розповсюдження; Автор відповідає за претензії третіх осіб щодо Творів.
EN: The Author created the Works or holds all rights in them; the Works contain no third-party samples, presets or melodies without a redistribution right; the Author is responsible for third-party claims about the Works.

**3. Винагорода (роялті) / Royalty.**
UA: Продавець сплачує Автору роялті {{70 %}} (для перших 20 авторів — {{85 %}} протягом 12 місяців) від ціни продажу Твору без ПДВ, комісії платіжного провайдера та сум повернень. Звіт і виплата — щомісяця до {{15}} числа наступного місяця, якщо сума ≥ €25; менша сума переноситься. Податок з роялті, який Продавець зобов'язаний утримати за законом країни Продавця (зокрема 15 % податку на репатріацію в Україні або менша ставка за міжнародним договором за наявності довідки про резидентство Автора), утримується з виплати.
EN: The Seller pays the Author a royalty of {{70%}} ({{85%}} for 12 months for the first 20 creators) of each sale price of a Work, excluding VAT, payment-provider fees and refunds. Statement and payment monthly by the {{15th}} of the following month if the amount is at least €25; smaller amounts roll over. Any tax the Seller must withhold under the law of its country (including Ukraine's 15% withholding tax, or a lower treaty rate if the Author provides a certificate of tax residence) is deducted from the payment.

**4. Спосіб виплати / Payment method.**
UA: Банківський переказ на рахунок Автора {{IBAN — в кабінеті}} у {{EUR/UAH}}. Банківські комісії — {{за рахунок отримувача / поділ}}. Автор сам сплачує податки зі своєї винагороди у своїй країні.
EN: Bank transfer to the Author's account {{IBAN — in account}} in {{EUR/UAH}}. Bank fees: {{borne by the Author / shared}}. The Author pays their own taxes on the royalty in their country.

**5. Ціна і продаж / Price and sale.**
UA: Ціну Твору встановлює Автор у кабінеті; Продавець може проводити знижки й акції, попередивши Автора, і роялті рахується від фактичної ціни продажу. Продаж здійснює Продавець від власного імені.
EN: The Author sets the price of each Work in their account; the Seller may run discounts and promotions with notice to the Author, and the royalty is calculated on the actual sale price. The Seller sells in its own name.

**6. Авторство / Authorship.**
UA: Автор зберігає майнові та немайнові авторські права; ім'я Автора зазначається на сторінці Твору. Продавець може використовувати назву, обкладинку й прев'ю Творів для реклами.
EN: The Author keeps all copyright and moral rights; the Author's name is shown on the product page. The Seller may use the titles, covers and previews of the Works for promotion.

**7. Повернення і претензії / Refunds and claims.**
UA: Повернення коштів покупцям — за Refund Policy сайту; відповідна роялті не нараховується або віднімається з наступної виплати. За скаргою про порушення прав Продавець може тимчасово зняти Твір.
EN: Customer refunds follow the site's Refund Policy; the related royalty is not due or is deducted from the next payment. If a rights complaint is received, the Seller may temporarily remove the Work.

**8. Строк і припинення / Term and termination.**
UA: Договір діє безстроково; будь-яка сторона може припинити його, повідомивши іншу за 30 днів через e-mail. Покупці, які вже придбали Твори, зберігають свої ліцензії. Нараховане роялті виплачується.
EN: This agreement has no fixed term; either party may end it with 30 days' e-mail notice. Customers who have already bought Works keep their licences. Accrued royalties are paid.

**9. Дані для звітності / Reporting data.**
UA: Автор надає свої дані (ПІБ, адреса, податковий номер, країна резидентства, IBAN), потрібні Продавцю для податкової звітності {{та звітності цифрової платформи (DAC7 / закон № 4903-IX), якщо застосовується}}.
EN: The Author provides the data (name, address, tax ID, country of residence, IBAN) the Seller needs for tax reporting {{and digital-platform reporting (DAC7 / Ukrainian Law No. 4903-IX), if applicable}}.

**10. Право і спори / Law and disputes.**
UA: Право {{України / Франції}}. Спори — спершу переговори, потім суд за місцезнаходженням Продавця.
EN: Governed by the law of {{Ukraine / France}}. Disputes: negotiation first, then the courts at the Seller's location.

**11. Мова / Language.** UA: Договір складено українською та англійською; у разі розбіжностей переважає {{українська}}. EN: Made in Ukrainian and English; in case of conflict the {{Ukrainian}} text prevails.

Підписи / Signatures: Автор / Author ______  Продавець / Seller ______
*Додаток 1 / Annex 1: перелік Творів / list of Works (or "all Works uploaded to the Author's account").*

---
Почему лицензия, а не агентский договор: при агентском договоре налоговая считает доходом ФОП всю сумму, а удержание комиссии — неденежным
расчётом (см. документ 24.11). При лицензии продаёт ФОП от своего имени (доход — его, налог 5 % со всей суммы — **заложить в цену/долю автора**),
а автору платит роялти — обычный расход **[вероятно — подтвердить бухгалтером]**. Для 3-й группы ЕП расходы налог не уменьшают:
при 70 % автору и 5 % налогу + ~2 % эквайринг + 15 % у иностранца (за счёт автора) нам остаётся ≈23 % **[расчёт]**.

## 5. Что сделать по шагам (после выбора схемы, только с «ок» владельца)
1. Выбрать А или Б → заполнить поля `{{…}}` (данные — владелец, вписывает прямо на сайте, не в репозиторий).
2. Разработчик: галочки в оформлении (цифровые товары / услуги), страница «Cancel an order», письмо-подтверждение с текстом галочки.
3. Копирайтер: перевести Terms/Refund на языки сайта (кроме юр. договора с автором).
4. Б: выбрать медиатора потребителей. А: спросить бухгалтера про закон о защите прав потребителей и чек ПРРО в письме.
5. Creator Terms: временно — строка про запуск выплат позже; после выбора схемы — договор из раздела 4 в кабинет автора (галочка + PDF).

## Идея для продаж (правило 6б)
Под кнопкой «Buy» — короткая строка доверия: **«Instant download · Works as described or your money back (30 days) · Secure card payment»**.
Это уже есть в Refund Policy, просто не видно у кнопки → ожидаю больше доверия у новых покупателей (у нас 0 отзывов). Стоимость — 15 минут разработчика.

Источники (проверено 25.11.2026): директива 2011/83/EU ст. 8(7), 16(m) — [EUR-Lex](https://eur-lex.europa.eu/eli/dir/2011/83/oj);
директива 2023/2673 («кнопка отказа», применяется с 19.06.2026) — [EUR-Lex](https://eur-lex.europa.eu/eli/dir/2023/2673/oj);
LCEN ст. 6-III (обязательные сведения на сайте) и Code de la consommation L612-1 (медиатор) — [Légifrance](https://www.legifrance.gouv.fr);
текущие страницы сайта — WordPress ID 2169, 2170, 35, 385, 1972 (чтение 25.11.2026).
