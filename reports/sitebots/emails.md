## ✉️ Письма: дойдут ли до клиента

| | Настройка | Состояние |
|---|---|---|
| ✅ | Почтовый сервер (MX) | mx.ukraine.com.ua. |
| 🟡 | SPF (кому можно слать от нашего имени) | `v=spf1 include:_spf.ukraine.com.ua ~all` — нет Brevo (include:spf.brevo.com), письма Brevo могут уйти в спам |
| ✅ | DKIM (подпись писем) | найдено: brevo1, brevo2 |
| 🟡 | DMARC (политика против подделки) | `v=DMARC1; p=none; rua=mailto:rua@dmarc.brevo.com` — только наблюдение (p=none), можно ужесточить позже |
| ✅ | Домен подтверждён в Brevo | да |

⏸ Статистика доставки Brevo — нужен ключ: секрет `BREVO_API_KEY` в GitHub (Settings → Secrets → Actions). Ключ создаётся в Brevo: SMTP & API → API keys.
