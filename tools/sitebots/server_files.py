"""SERVER FILES (owner 21.11.2026): what lies on the hosting and is not needed. READ-ONLY, deletes nothing.

- disk use by folder (www, wp-content/uploads by year, plugins, backups…);
- the biggest files;
- risky files reachable from the web (database dumps, archives, logs, wp-config copies, phpinfo) — security too;
- old backup folders (_removed, backup plugins);
- uploads not known to the WordPress media library (no attachment with that name) = candidates for cleanup.
Needs the FTP secrets (FTP_HOST/USER/PASS). Output: Markdown to stdout, metrics to <SITEBOTS_OUT>/.files-metrics.json.
"""
import ftplib, json, os, posixpath, re, sys, time, urllib.request

sys.path.insert(0, os.path.join(os.path.dirname(__file__), '..'))
from ftp_sync import connect  # noqa: E402

ROOT = 'solid-sound.net'
WWW = ROOT + '/www'
MAX_ENTRIES = 120000
RISKY = re.compile(r'(\.sql(\.gz)?|\.bak|\.old|\.orig|~|\.swp|\.log|\.tar(\.gz)?|\.tgz|\.7z|\.rar|wp-config.*\.(txt|php\.\w+)|phpinfo\.php|\.env|adminer.*\.php)$', re.I)
BACKUP_DIRS = re.compile(r'^(_removed|ai1wm-backups|updraft|backups?|backup-.*|wpvividbackups|backwpup.*|duplicator.*|wp-snapshots|cache-old)$', re.I)
THUMB = re.compile(r'-\d+x\d+(?=\.\w+$)|-scaled(?=\.\w+$)|-rotated(?=\.\w+$)')
UA = 'Mozilla/5.0 (compatible; SolidSoundBots/1.0)'

f = connect()
entries = []  # (path, size, is_dir)


errors = []


def ls(path):
    """-> [(name, {'type','size'})] via MLSD, else LIST (unix format); reconnects once on a dropped connection."""
    global f
    for attempt in range(2):
        try:
            try:
                return list(f.mlsd(path))
            except ftplib.error_perm:
                rows = []
                f.retrlines('LIST -a ' + path, rows.append)
                out = []
                for r in rows:
                    m = re.match(r'^([dl-])[rwxsStT-]{9}\S*\s+\d+\s+\S+\s+\S+\s+(\d+)\s+\w+\s+\d+\s+[\d:]+\s+(.+)$', r)
                    if m:
                        name = m.group(3).split(' -> ')[0]
                        out.append((name, {'type': 'dir' if m.group(1) == 'd' else 'file' if m.group(1) == '-' else 'link', 'size': m.group(2)}))
                return out
        except Exception as e:
            if attempt:
                errors.append(f'{path}: {str(e)[:80]}')
                return []
            try:
                f.close()
            except Exception:
                pass
            time.sleep(3)
            f = connect()


def walk(path, depth=0):
    if len(entries) > MAX_ENTRIES or depth > 12:
        return
    items = ls(path)
    for name, facts in items:
        if name in ('.', '..'):
            continue
        p = posixpath.join(path, name)
        if facts.get('type') == 'dir':
            entries.append((p, 0, True))
            if name in ('node_modules', '.git'):
                continue
            walk(p, depth + 1)
        elif facts.get('type') == 'file':
            entries.append((p, int(facts.get('size') or 0), False))


walk(ROOT)
files = [e for e in entries if not e[2]]
total = sum(e[1] for e in files)
mb = lambda b: f'{b / 1048576:.1f} МБ' if b < 1073741824 else f'{b / 1073741824:.2f} ГБ'


def size_under(prefix):
    return sum(e[1] for e in files if e[0].startswith(prefix + '/'))


folders = {}
for top in [WWW + '/wp-content/uploads', WWW + '/wp-content/plugins', WWW + '/wp-content/themes', WWW + '/wp-content/cache', WWW + '/wp-content']:
    folders[top] = size_under(top)
for d in sorted({posixpath.dirname(e[0]) for e in files if e[0].count('/') == 2} | {e[0] for e in entries if e[2] and e[0].count('/') == 1}):
    folders.setdefault(d, size_under(d))
years = {}
for e in files:
    m = re.match(re.escape(WWW) + r'/wp-content/uploads/(\d{4})/', e[0])
    years[m.group(1) if m else ''] = years.get(m.group(1) if m else '', 0) + (e[1] if m else 0)

risky = [e for e in files if RISKY.search(posixpath.basename(e[0])) and e[0].startswith(WWW + '/') and '/node_modules/' not in e[0] and not posixpath.basename(e[0]).startswith('.ht')]
ht_copies = [e for e in files if posixpath.basename(e[0]).startswith('.ht') and RISKY.search(posixpath.basename(e[0]))]
backups = [e for e in entries if e[2] and BACKUP_DIRS.match(posixpath.basename(e[0]))]
big = sorted(files, key=lambda e: -e[1])[:15]

# uploads vs media library (public REST API: file names of every attachment)
known, api_ok = set(), True
page = 1
while page < 200:
    try:
        req = urllib.request.Request(f'https://solid-sound.net/wp-json/wp/v2/media?per_page=100&page={page}&_fields=source_url', headers={'User-Agent': UA})
        data = json.load(urllib.request.urlopen(req, timeout=60))
    except Exception:
        if page == 1:
            api_ok = False
        break
    if not data:
        break
    for x in data:
        known.add(posixpath.basename(x.get('source_url', '')).lower())
    page += 1
    time.sleep(0.8)
orphans = []
if api_ok and known:
    for e in files:
        if not e[0].startswith(WWW + '/wp-content/uploads/'):
            continue
        rest = e[0][len(WWW + '/wp-content/uploads/'):]
        if not re.match(r'\d{4}/\d{2}/', rest) and '/' in rest:
            continue  # plugin folders inside uploads (wc-logs, woocommerce_uploads …) are not media
        base = THUMB.sub('', posixpath.basename(e[0])).lower()
        if base not in known and posixpath.basename(e[0]).lower() not in known and not base.startswith(('ss-', '.')) and not base.endswith(('.txt', '.json', '.htaccess', '.php', '.html')):
            orphans.append(e)
orphans.sort(key=lambda e: -e[1])

out = ['## 🗄 Сервер: что лежит на хостинге и не нужно', '',
       f'Файлов: **{len(files)}**, всего **{mb(total)}**' + (' (обход остановлен на лимите — показано не всё)' if len(entries) > MAX_ENTRIES else '') + '. Робот только смотрит, ничего не удаляет.', '',
       '| Папка | Размер |', '|---|---|']
for k, v in sorted(folders.items(), key=lambda kv: -kv[1])[:14]:
    out.append(f'| `{k}` | {mb(v)} |')
out += ([f'⚠️ Не удалось прочитать папок: {len(errors)} (например `{errors[0]}`) — размеры могут быть занижены.'] if errors else []) + ['', 'Загрузки по годам: ' + ', '.join(f'{y} — {mb(v)}' for y, v in sorted(years.items()) if y), '']
if risky:
    out += [f'### 🔴 Опасные файлы, доступные из интернета ({len(risky)})', 'Копии базы, архивы, логи, копии wp-config — их может скачать кто угодно. Удалить или перенести выше папки www.', '']
    out += [f'- `{e[0][len(WWW):]}` — {mb(e[1])}' for e in sorted(risky, key=lambda e: -e[1])[:30]] + ['']
if ht_copies:
    out += ['### 🟡 Копии .htaccess (сервер обычно не отдаёт .ht*, но лучше убрать)'] + [f'- `{e[0][len(ROOT):]}`' for e in ht_copies] + ['']
if backups:
    out += [f'### 🟠 Папки старых копий ({len(backups)})'] + [f'- `{e[0]}` — {mb(size_under(e[0]))}' for e in backups] + ['']
out += ['### Самые большие файлы', '| Файл | Размер |', '|---|---|'] + [f'| `{e[0]}` | {mb(e[1])} |' for e in big] + ['']
if not api_ok:
    out += ['⏸ Медиатека не ответила — сравнение загрузок с медиатекой пропущено.', '']
elif orphans:
    out += [f'### 🟡 Загрузки, которых нет в медиатеке ({len(orphans)} файлов, {mb(sum(e[1] for e in orphans))})',
            'Кандидаты на удаление: файл лежит в uploads, но в медиатеке WordPress такого нет. Перед удалением — проверить поиском по сайту.', '']
    out += [f'- `{e[0][len(WWW + "/wp-content/uploads/"):]}` — {mb(e[1])}' for e in orphans[:40]] + ([f'- … и ещё {len(orphans) - 40}'] if len(orphans) > 40 else []) + ['']
print('\n'.join(out))
_m = ({'srv_total_mb': round(total / 1048576), 'srv_risky': len(risky), 'srv_backup_dirs': len(backups), 'srv_orphans': len(orphans), 'srv_orphans_mb': round(sum(e[1] for e in orphans) / 1048576)})
_o = os.environ.get('SITEBOTS_OUT', 'reports/sitebots')
os.makedirs(_o, exist_ok=True)
open(os.path.join(_o, '.files-metrics.json'), 'w').write(json.dumps(_m))
