"""Hosting statistics from the server's access logs (owner 21.11.2026: «аналитика — подключи что сможешь сам»).

Finds the access logs over FTP (the same login the deploy robot uses), reads the last N days and writes ONLY an
anonymous summary: visits and page views per day, countries, pages, sources, devices. The repository is public:
raw logs, IP addresses and user agents are never saved — visitors are counted by a salted hash kept in memory.
Bots are left out (user agent words + no-page requests). Countries: free db-ip.com "country lite" database.
Run: python3 tools/hosting-stats.py [days]   (env FTP_HOST / FTP_USER / FTP_PASS). Output: reports/hosting/.
"""
import collections, datetime as dt, gzip, io, os, posixpath, re, secrets, sys, hashlib, urllib.request, ftplib

sys.path.insert(0, os.path.dirname(__file__))
from ftp_sync import Conn, listing  # noqa: E402

DAYS = int(sys.argv[1]) if len(sys.argv) > 1 else 30
OUT = 'reports/hosting'
SALT = secrets.token_bytes(16)
LINE = re.compile(r'^(\S+) \S+ \S+ \[([^\]]+)\] "(\S+) (\S+)[^"]*" (\d{3}) (\S+)(?: "([^"]*)" "([^"]*)")?')
BOT = re.compile(r'bot|crawl|spider|slurp|curl|wget|python|httpclient|go-http|java/|headless|lighthouse|pingdom|uptime|monitor|scan|'
                 r'facebookexternalhit|preview|feed|okhttp|axios|node-fetch|php/|libwww|ahrefs|semrush|mj12|dataforseo|petal|bytespider|gptbot|claude|perplexity|playwright', re.I)
ASSET = re.compile(r'\.(css|js|mjs|map|png|jpe?g|gif|webp|avif|svg|ico|woff2?|ttf|eot|mp3|wav|ogg|mp4|webm|json|xml|txt|zip|gz)(\?|$)', re.I)
SKIP_PATH = re.compile(r'^/(wp-admin|wp-json|wp-content|wp-includes|wp-cron\.php|xmlrpc\.php|wp-login\.php|\?wc-ajax|cdn-cgi|\.well-known)|admin-ajax|/feed/?$', re.I)


def find_logs(c):
    """Look for access logs in the usual places of this hosting; print what is there (for the run log)."""
    found, seen = [], set()
    queue = ['', 'solid-sound.net', 'logs', 'solid-sound.net/logs', 'log', 'solid-sound.net/log']
    while queue:
        p = queue.pop(0)
        if p in seen or p.count('/') > 3:
            continue
        seen.add(p)
        try:
            dirs, files = c.run(listing, p or '.')
        except ftplib.all_errors:
            continue
        print(f'[{p or "/"}] dirs: {", ".join(sorted(dirs)[:30])} | files: {len(files)}')
        for f in files:
            if re.search(r'access.*log|\.log(\.\d+)?(\.gz)?$|log.*\.gz$', f, re.I) and not re.search(r'error', f, re.I):
                found.append(posixpath.join(p, f))
        for d in dirs:
            if re.search(r'log', d, re.I):
                queue.append(posixpath.join(p, d))
    return found


def fetch(c, path):
    buf = io.BytesIO()
    c.run(lambda f, p: (buf.seek(0), buf.truncate(), f.retrbinary('RETR ' + p, buf.write)), path)
    data = buf.getvalue()
    if path.endswith('.gz') or data[:2] == b'\x1f\x8b':
        data = gzip.decompress(data)
    return data.decode('utf-8', 'replace').splitlines()


def geo_reader():
    try:
        import maxminddb
    except ImportError:
        return None
    today = dt.date.today()
    for d in (today, today.replace(day=1) - dt.timedelta(days=1)):
        url = f'https://download.db-ip.com/free/dbip-country-lite-{d:%Y-%m}.mmdb.gz'
        try:
            raw = gzip.decompress(urllib.request.urlopen(url, timeout=60).read())
            return maxminddb.open_database(io.BytesIO(raw), mode=maxminddb.MODE_FD)
        except Exception as e:  # noqa: BLE001
            print('geo db:', url, e)
    return None


def country(geo, ip, cache={}):
    if not geo:
        return '?'
    if ip not in cache:
        try:
            r = geo.get(ip) or {}
            cache[ip] = (r.get('country') or {}).get('names', {}).get('en') or '?'
        except Exception:  # noqa: BLE001
            cache[ip] = '?'
    return cache[ip]


def main():
    c = Conn()
    logs = find_logs(c)
    print('log files:', logs or 'none found')
    os.makedirs(OUT, exist_ok=True)
    if not logs:
        open(f'{OUT}/latest.md', 'w').write(f'# Хостинг: статистика\n\n{dt.datetime.utcnow():%Y-%m-%d %H:%M} UTC — журналы посещений по FTP не найдены. Список папок — в журнале робота (Actions → Hosting stats).\n')
        return
    since = dt.datetime.now(dt.timezone.utc) - dt.timedelta(days=DAYS)
    geo = geo_reader()
    views, visitors = collections.Counter(), collections.defaultdict(set)
    pages, refs, countries, devices, statuses = collections.Counter(), collections.Counter(), collections.Counter(), collections.Counter(), collections.Counter()
    vis_country, seen_v = {}, set()
    bots = lines = 0
    for path in logs:
        try:
            rows = fetch(c, path)
        except Exception as e:  # noqa: BLE001
            print('skip', path, e)
            continue
        for ln in rows:
            m = LINE.match(ln)
            if not m:
                continue
            lines += 1
            ip, ts, meth, url, st, _, ref, ua = m.groups()
            try:
                t = dt.datetime.strptime(ts, '%d/%b/%Y:%H:%M:%S %z')
            except ValueError:
                continue
            if t < since:
                continue
            ua, ref = ua or '', ref or ''
            if BOT.search(ua) or not ua:
                bots += 1
                continue
            if meth != 'GET' or ASSET.search(url) or SKIP_PATH.search(url) or not st.startswith(('2', '3')):
                continue
            day = t.strftime('%Y-%m-%d')
            v = hashlib.sha256(SALT + ip.encode() + ua.encode()).hexdigest()[:16]
            views[day] += 1
            visitors[day].add(v)
            p = url.split('?')[0]
            p = re.sub(r'^/(ua|de|fr|pt|es|it)(?=/|$)', r'/\1', p)[:80] or '/'
            pages[p] += 1
            statuses[st] += 1
            if v not in seen_v:
                seen_v.add(v)
                vis_country[v] = country(geo, ip)
                devices['phone' if re.search(r'Mobile|Android|iPhone', ua) else 'computer'] += 1
            host = re.sub(r'^https?://(www\.)?', '', ref).split('/')[0]
            if host and 'solid-sound.net' not in host:
                refs[host] += 1
            elif not host:
                refs['(direct / app)'] += 1
    countries.update(vis_country.values())
    total_v = len(seen_v)
    rep = [f'# Хостинг: статистика за {DAYS} дн. (по журналам сервера)', '',
           f'Обновлено {dt.datetime.utcnow():%Y-%m-%d %H:%M} UTC. Без ботов ({bots} запросов отброшено). Посетитель = адрес + браузер (без сохранения адресов).', '',
           f'**Посетителей: {total_v} · просмотров страниц: {sum(views.values())}**', '',
           '## По дням', '| День | Посетители | Просмотры |', '|---|---|---|']
    rep += [f'| {d} | {len(visitors[d])} | {views[d]} |' for d in sorted(views)]
    rep += ['', '## Страны (посетители)', '| Страна | Посетители |', '|---|---|'] + [f'| {k} | {n} |' for k, n in countries.most_common(25)]
    rep += ['', '## Откуда пришли (переходы)', '| Источник | Переходы |', '|---|---|'] + [f'| {k} | {n} |' for k, n in refs.most_common(20)]
    rep += ['', '## Страницы (топ-30)', '| Страница | Просмотры |', '|---|---|'] + [f'| {k} | {n} |' for k, n in pages.most_common(30)]
    rep += ['', '## Устройства', ' · '.join(f'{k}: {n}' for k, n in devices.most_common())]
    if not geo:
        rep += ['', '_Страны не определены: база db-ip.com не скачалась._']
    txt = '\n'.join(rep) + '\n'
    open(f'{OUT}/latest.md', 'w').write(txt)
    open(f'{OUT}/{dt.date.today():%Y-%m-%d}.md', 'w').write(txt)
    print(f'lines {lines}, visitors {total_v}, views {sum(views.values())}')


if __name__ == '__main__':
    main()
