"""Old addresses of solid-sound.net since 2018 (owner 21.11.2026, SEO: «Найди»).

1. Asks the Internet Archive (Wayback CDX API) for every page address it has ever saved for solid-sound.net.
2. Keeps real pages (no images/css/js, no wp-admin/feeds/cart actions, no tracking parameters).
3. Opens each address on the live site NOW and records: works (200), redirect (and where to), broken (404/410/5xx).
Output: reports/seo/old-urls.md (summary + broken list to fix with 301 redirects) and reports/seo/old-urls.csv.
Everything here is public information (addresses), no personal data.
Run: python3 tools/old-urls.py
"""
import concurrent.futures as cf, csv, json, os, re, sys, time, urllib.error, urllib.parse, urllib.request

OUT = 'reports/seo'
UA = 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Mobile Safari/537.36'
SKIP = re.compile(r'\.(css|js|mjs|map|png|jpe?g|gif|webp|avif|svg|ico|woff2?|ttf|eot|mp3|wav|ogg|mp4|webm|json|xml|txt|zip|rar|gz|pdf)$|'
                  r'/(wp-admin|wp-json|wp-includes|wp-content|cdn-cgi|xmlrpc\.php|wp-login\.php|wp-cron\.php|feed|comments/feed|embed|trackback)(/|$)|'
                  r'[?&](add-to-cart|add_to_wishlist|remove_item|wc-ajax|replytocom|s=|orderby|filter_|min_price|max_price|_wpnonce|action=)', re.I)
DROP_Q = re.compile(r'^(utm_|fbclid|gclid|yclid|mc_|ref$|srsltid|_ga|ss_)', re.I)


def cdx(host):
    q = urllib.parse.urlencode({'url': host, 'matchType': 'domain', 'output': 'json', 'fl': 'original,statuscode,timestamp,mimetype', 'collapse': 'urlkey', 'limit': '50000'})
    for i in range(4):
        try:
            with urllib.request.urlopen('https://web.archive.org/cdx/search/cdx?' + q, timeout=120) as r:
                rows = json.load(r)
                return rows[1:] if rows else []
        except Exception as e:  # noqa: BLE001
            print('cdx retry', host, e, flush=True)
            time.sleep(10 * (i + 1))
    return []


def norm(u):
    p = urllib.parse.urlsplit(u.strip())
    path = re.sub(r'/{2,}', '/', urllib.parse.unquote(p.path) or '/')
    q = [(k, v) for k, v in urllib.parse.parse_qsl(p.query, keep_blank_values=True) if not DROP_Q.match(k)]
    if q and not any(k in ('p', 'page_id', 'product', 'post_type', 'lang') for k, _ in q):
        q = []  # other parameters do not make a different page
    qs = urllib.parse.urlencode(q)
    return 'https://solid-sound.net' + urllib.parse.quote(path, safe='/%-._~') + ('?' + qs if qs else '')


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


OPENER = urllib.request.build_opener(NoRedirect)


def check(u):
    chain, cur = [], u
    for _ in range(6):
        req = urllib.request.Request(cur, headers={'User-Agent': UA, 'Accept': 'text/html'})
        try:
            r = OPENER.open(req, timeout=30)
            body = r.read(4000).decode('utf-8', 'replace')
            if 'Сторінка захищена' in body or 'captcha' in body.lower():
                return u, 'captcha', cur, chain
            return u, str(r.status), cur, chain
        except urllib.error.HTTPError as e:
            if e.code in (301, 302, 303, 307, 308) and e.headers.get('Location'):
                chain.append(str(e.code))
                cur = urllib.parse.urljoin(cur, e.headers['Location'])
                continue
            return u, str(e.code), cur, chain
        except Exception as e:  # noqa: BLE001
            return u, 'error: ' + type(e).__name__, cur, chain
    return u, 'loop', cur, chain


def main():
    seen = {}
    raw = cdx('solid-sound.net')
    years, hosts, mimes = {}, {}, {}
    for orig, st, ts, mt in raw:
        years[ts[:4]] = years.get(ts[:4], 0) + 1
        h = urllib.parse.urlsplit(orig).hostname or '?'
        hosts[h] = hosts.get(h, 0) + 1
        mimes[mt] = mimes.get(mt, 0) + 1
    print('archive raw:', len(raw), '| years', sorted(years.items()), '| hosts', sorted(hosts.items(), key=lambda x: -x[1])[:15], '| types', sorted(mimes.items(), key=lambda x: -x[1])[:8], flush=True)
    os.makedirs(OUT, exist_ok=True)
    with open(f'{OUT}/archive-overview.md', 'w') as f:
        f.write('# Архив интернета: что сохранено о solid-sound.net\n\n')
        f.write(f'Всего записей: {len(raw)}\n\n## По годам\n' + '\n'.join(f'- {y}: {n}' for y, n in sorted(years.items())) + '\n\n')
        f.write('## Поддомены\n' + '\n'.join(f'- {h}: {n}' for h, n in sorted(hosts.items(), key=lambda x: -x[1])) + '\n\n')
        f.write('## Типы\n' + '\n'.join(f'- {m}: {n}' for m, n in sorted(mimes.items(), key=lambda x: -x[1])[:15]) + '\n\n')
        f.write('## Все записи (адрес · год · код)\n' + '\n'.join(f'- {o} · {t[:4]} · {s}' for o, s, t, m in sorted(raw, key=lambda r: r[2])[:600]) + '\n')
    for orig, st, ts, mt in raw:
            if mt and 'html' not in mt:
                continue
            if (urllib.parse.urlsplit(orig).hostname or '').replace('www.', '') != 'solid-sound.net':
                continue
            if SKIP.search(urllib.parse.urlsplit(orig).path + ('?' + urllib.parse.urlsplit(orig).query if urllib.parse.urlsplit(orig).query else '')):
                continue
            n = norm(orig)
            y = ts[:4]
            if n not in seen:
                seen[n] = [y, y, st]
            else:
                seen[n][0] = min(seen[n][0], y)
                seen[n][1] = max(seen[n][1], y)
    urls = sorted(seen)
    print('archive pages:', len(urls), flush=True)
    sub = ['studio', 'post', 'templates', 'landingpt', 'test', 'www.studio', 'www.templates']
    with open(f'{OUT}/subdomains.md', 'w') as f:
        f.write('# Поддомены solid-sound.net — как открываются сейчас\n\n| Поддомен | Сейчас | Куда ведёт | Заголовок страницы |\n|---|---|---|---|\n')
        for s in sub:
            u = f'https://{s}.solid-sound.net/'
            r = check(u)
            title = ''
            try:
                req = urllib.request.Request(r[2], headers={'User-Agent': UA})
                body = urllib.request.urlopen(req, timeout=30).read(60000).decode('utf-8', 'replace')
                m = re.search(r'<title[^>]*>(.*?)</title>', body, re.S | re.I)
                title = re.sub(r'\s+', ' ', m.group(1)).strip()[:80] if m else ''
                noindex = 'noindex' in body.lower()
                title += ' (noindex)' if noindex else ''
            except Exception as e:  # noqa: BLE001
                title = type(e).__name__
            f.write(f'| {s} | {r[1]} | {r[2] if r[2] != u else ""} | {title} |\n')
            print('sub', s, r[1], r[2], title, flush=True)
    res = []
    with cf.ThreadPoolExecutor(6) as ex:
        for i, r in enumerate(ex.map(check, urls)):
            res.append(r)
            if i % 50 == 0:
                print(i, r[0], r[1], flush=True)
    os.makedirs(OUT, exist_ok=True)
    with open(f'{OUT}/old-urls.csv', 'w', newline='') as f:
        w = csv.writer(f)
        w.writerow(['old_url', 'first_seen', 'last_seen', 'now_status', 'redirects', 'final_url'])
        for u, st, fin, ch in res:
            w.writerow([u, seen[u][0], seen[u][1], st, '>'.join(ch), fin if fin != u else ''])
    ok = [r for r in res if r[1] == '200' and not r[3]]
    red = [r for r in res if r[1] == '200' and r[3]]
    bad = [r for r in res if r[1] not in ('200', 'captcha')]
    cap = [r for r in res if r[1] == 'captcha']
    rep = ['# Старые адреса сайта (архив интернета с 2018) — как открываются сейчас', '',
           f'Проверено {time.strftime("%Y-%m-%d %H:%M", time.gmtime())} UTC. Всего страниц в архиве: **{len(res)}**.', '',
           f'- открываются сразу: **{len(ok)}**', f'- открываются через переадресацию: **{len(red)}**',
           f'- **сломаны (нужна переадресация 301): {len(bad)}**', f'- не проверены (защита хостинга): {len(cap)}', '',
           '## Сломанные адреса (последний раз в архиве — чем новее, тем важнее)', '| Адрес | Был в архиве | Сейчас |', '|---|---|---|']
    for u, st, fin, ch in sorted(bad, key=lambda r: seen[r[0]][1], reverse=True)[:400]:
        rep.append(f'| {u.replace("https://solid-sound.net", "")} | {seen[u][0]}–{seen[u][1]} | {st} |')
    rep += ['', 'Полный список — `reports/seo/old-urls.csv`.']
    open(f'{OUT}/old-urls.md', 'w').write('\n'.join(rep) + '\n')
    print(f'ok {len(ok)} redirect {len(red)} broken {len(bad)} captcha {len(cap)}')


if __name__ == '__main__':
    main()
