# Письмо E1 — приветствие для списка «Free Pack» (черновик, 28.11.2026)

Кому: список Brevo «Free Pack» (id 3) — только те, кто сам оставил почту на `/en/free/` (на 28.11 — 3 подписчика).
Отправка — только после «ок» владельца (`docs/owner-decisions.md`). Лучше всего — как автоматическое письмо сразу после подписки (настроить в Brevo → Automations; коннектор Brevo пока не видит автоматизации — нужен повторный вход владельца).
Цель: человек открыл пакет → попробовал → вернулся на сайт (шаблон или бесплатный разбор трека). Без «купи», пока оплата выключена.

**Subject:** Your free Ableton pack is here 🎧
**Preheader:** Template + Serum presets — and 3 tips to get a club-ready low end.

---

Hey {{ contact.FIRSTNAME | default : "there" }},

Thanks for grabbing the Solid Sound Starter Pack. Your download is here:
**[Download the pack →](https://solid-sound.net/en/free/?utm_source=brevo&utm_medium=email&utm_campaign=e1_welcome&utm_content=download)**

Inside: a full Ableton techno project (stock devices only) and Serum presets.

**3 things to try first**
1. **Solo the kick and the rumble.** Look at how the reverb send is low-passed around 150 Hz and ducked by the kick. That's where most of the weight comes from.
2. **Check the master loudness.** Streaming turns everything down to about −14 LUFS, so a cleaner mix beats a louder one.
3. **Swap one sound.** Load one of the Serum presets on the lead track and rebuild the drop around it. It's the fastest way to make the track yours.

**Want honest notes on your own track?**
Send us a WIP and our engineers will tell you what's holding the mix back. The first feedback is free.
**[Get free feedback →](https://solid-sound.net/en/free-demo-feedback/?utm_source=brevo&utm_medium=email&utm_campaign=e1_welcome&utm_content=feedback)**

Happy producing,
The Solid Sound team

_You're getting this because you downloaded our free pack. [Unsubscribe]({{ unsubscribe }})_

---

Проверить перед отправкой: ссылка «Download» реально отдаёт пакет (или ведёт туда, где он скачивается); подпись — «команда», без имени владельца (правило 17.11); тест себе через Brevo.
