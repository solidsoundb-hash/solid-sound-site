// E-MAILS (owner 21.11.2026): will our letters (orders, sign-up, password, newsletters) reach the inbox and not spam?
// 1) domain mail settings via public DNS (no key): MX, SPF, DKIM (Brevo / Google / common selectors), DMARC;
// 2) Brevo delivery stats for the last 7 days (sent, delivered, bounced, spam complaints, blocked) — needs the secret
//    BREVO_API_KEY (read-only use). Without it the part is shown as ⏸.
'use strict';
const DOMAIN = 'solid-sound.net';
let dnsDown = 0;
async function dns(name, type) {
  for (const u of [`https://dns.google/resolve?name=${encodeURIComponent(name)}&type=${type}`, `https://cloudflare-dns.com/dns-query?name=${encodeURIComponent(name)}&type=${type}`]) {
    const r = await fetch(u, { headers: { accept: 'application/dns-json' }, signal: AbortSignal.timeout(15000) }).then(x => x.json()).catch(() => null);
    if (r && typeof r.Status === 'number') return (r.Answer || []).map(a => String(a.data).replace(/^"|"$/g, '').replace(/" "/g, ''));
  }
  dnsDown++; return [];
}
module.exports = async function emails() {
  const mx = await dns(DOMAIN, 'MX'); const txt = await dns(DOMAIN, 'TXT'); const spf = txt.filter(t => /^v=spf1/i.test(t));
  const dmarc = (await dns('_dmarc.' + DOMAIN, 'TXT')).filter(t => /^v=DMARC1/i.test(t));
  const dkim = []; for (const s of ['brevo1', 'brevo2', 'mail', 'google', 'default', 'k1', 'selector1', 'selector2', 's1', 'dkim'])
    if ((await dns(s + '._domainkey.' + DOMAIN, 'TXT')).concat(await dns(s + '._domainkey.' + DOMAIN, 'CNAME')).length) dkim.push(s);
  const brevoCode = txt.some(t => /brevo-code|sendinblue-code/i.test(t));
  const rows = [], alerts = [];
  if (dnsDown) return { md: '## ✉️ Письма: дойдут ли до клиента\n\n⏸ Сервис DNS не ответил — настройки почты не проверены (это не ошибка сайта).\n', metrics: {}, alerts: [] };
  const R = (ok, name, note) => rows.push(`| ${ok === true ? '✅' : ok === false ? '🔴' : '🟡'} | ${name} | ${note} |`);
  R(mx.length > 0, 'Почтовый сервер (MX)', mx.length ? mx.map(m => m.split(' ').pop()).join(', ') : 'нет — на адреса @' + DOMAIN + ' письма не придут');
  R(spf.length === 1 ? (/brevo|sendinblue/i.test(spf[0]) ? true : null) : false, 'SPF (кому можно слать от нашего имени)',
    spf.length === 1 ? '`' + spf[0] + '`' + (/brevo|sendinblue/i.test(spf[0]) ? '' : ' — нет Brevo (include:spf.brevo.com), письма Brevo могут уйти в спам') : spf.length ? 'несколько записей SPF — это ошибка, должна быть одна' : 'нет — письма чаще попадают в спам');
  R(dkim.length ? true : false, 'DKIM (подпись писем)', dkim.length ? 'найдено: ' + dkim.join(', ') : 'не нашёл подпись ни по одному обычному имени — проверить в Brevo «Senders & domains»');
  R(dmarc.length ? (/p=(quarantine|reject)/i.test(dmarc[0]) ? true : null) : false, 'DMARC (политика против подделки)', dmarc.length ? '`' + dmarc[0] + '`' + (/p=none/i.test(dmarc[0]) ? ' — только наблюдение (p=none), можно ужесточить позже' : '') : 'нет — Gmail и Yahoo с 2024 требуют DMARC для рассылок');
  R(brevoCode || null, 'Домен подтверждён в Brevo', brevoCode ? 'да' : 'код подтверждения не виден в DNS (может быть подтверждён иначе)');
  if (!mx.length || !spf.length || !dmarc.length) alerts.push('Письма: не хватает настроек почты домена (MX/SPF/DMARC)');
  let md = '## ✉️ Письма: дойдут ли до клиента\n\n| | Настройка | Состояние |\n|---|---|---|\n' + rows.join('\n') + '\n\n';
  const key = process.env.BREVO_API_KEY; const metrics = { mail_dns_ok: rows.filter(r => r.startsWith('| ✅')).length };
  if (!key) md += '⏸ Статистика доставки Brevo — нужен ключ: секрет `BREVO_API_KEY` в GitHub (Settings → Secrets → Actions). Ключ создаётся в Brevo: SMTP & API → API keys.\n';
  else {
    const end = new Date().toISOString().slice(0, 10), start = new Date(Date.now() - 7 * 864e5).toISOString().slice(0, 10);
    const s = await fetch(`https://api.brevo.com/v3/smtp/statistics/aggregatedReport?startDate=${start}&endDate=${end}`, { headers: { 'api-key': key, accept: 'application/json' } }).then(r => r.json()).catch(e => ({ message: e.message }));
    if (s.code || s.message) md += '🔴 Brevo не ответил: ' + (s.message || s.code) + '\n';
    else {
      const bad = (s.hardBounces || 0) + (s.softBounces || 0) + (s.blocked || 0) + (s.spamReports || 0); const rate = s.requests ? Math.round(bad / s.requests * 1000) / 10 : 0;
      md += `**Brevo, последние 7 дней (письма из сайта):** отправлено ${s.requests || 0}, доставлено ${s.delivered || 0}, открыто ${s.uniqueOpens || 0}, отскоки ${(s.hardBounces || 0) + (s.softBounces || 0)}, заблокировано ${s.blocked || 0}, жалоб на спам ${s.spamReports || 0}. Проблемных: **${rate}%**${rate > 5 ? ' 🔴 (норма до 2–5%)' : ' ✅'}\n`;
      Object.assign(metrics, { mail_sent: s.requests || 0, mail_bad_pct: rate }); if (rate > 5) alerts.push(`Письма: ${rate}% не доходят`);
    }
  }
  return { md, metrics, alerts };
};
if (require.main === module) module.exports().then(r => console.log(r.md));
