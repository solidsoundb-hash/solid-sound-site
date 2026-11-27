"""Pull everything useful out of the owner's old sub-sites before they are removed (owner 21.11.2026:
«хочу убрать лишнее, но нужно оттуда всё вытянуть полезное»).

For each sub-site: WordPress REST API first (pages, posts, media — full text), otherwise a crawl of its own links.
Saves: old-sites/<sub>/index.md (every page: title, address, text), old-sites/<sub>/pages/*.md, media list,
and downloads the media files (images, audio, video up to 60 MB each) into old-sites/<sub>/media/.
The workflow puts the result on the branch `old-sites` (not main — it can be big).
Run: python3 tools/old-sites.py studio post templates test
"""
import html, json, os, re, sys, urllib.parse, urllib.request

UA = 'Mozilla/5.0 (Linux; Android 12; SM-A315F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Mobile Safari/537.36 SolidSoundQA/1.0'
OUT = 'old-sites'
MEDIA_RE = re.compile(r'\.(jpe?g|png|gif|webp|svg|mp3|wav|ogg|m4a|mp4|webm|mov|pdf)(\?|$)', re.I)


def get(url, binary=False, limit=None):
    req = urllib.request.Request(url, headers={'User-Agent': UA})
    with urllib.request.urlopen(req, timeout=60) as r:
        data = r.read(limit) if limit else r.read()
        return (data, r.geturl()) if binary else (data.decode('utf-8', 'replace'), r.geturl())


def text_of(h):
    h = re.sub(r'(?is)<(script|style|noscript|svg)[^>]*>.*?</\1>', ' ', h)
    h = re.sub(r'(?i)<br\s*/?>|</(p|div|li|h[1-6]|tr)>', '\n', h)
    h = re.sub(r'(?i)<h([1-6])[^>]*>', lambda m: '\n' + '#' * int(m.group(1)) + ' ', h)
    h = re.sub(r'(?i)<li[^>]*>', '\n- ', h)
    t = html.unescape(re.sub(r'<[^>]+>', ' ', h))
    t = '\n'.join(re.sub(r'[ \t]+', ' ', l).strip() for l in t.split('\n'))
    return re.sub(r'\n{3,}', '\n\n', t).strip()


def media_in(h, base):
    out = set()
    for m in re.finditer(r'(?:src|href|data-src|data-lazy-src|poster)=["\']([^"\']+)["\']', h, re.I):
        u = urllib.parse.urljoin(base, html.unescape(m.group(1)))
        if MEDIA_RE.search(u) and 'wp-includes' not in u and '/plugins/' not in u and '/themes/' not in u:
            out.add(u.split('#')[0])
    for m in re.finditer(r'(?:src|data-src|href)=["\']([^"\']*(?:youtube\.com|youtu\.be|vimeo\.com|soundcloud\.com|mixcloud\.com|drive\.google\.com|dropbox\.com)[^"\']*)["\']', h, re.I):
        out.add('EMBED ' + html.unescape(m.group(1)))
    for m in re.finditer(r'url\(["\']?([^"\')]+)["\']?\)', h):
        u = urllib.parse.urljoin(base, m.group(1))
        if MEDIA_RE.search(u) and '/themes/' not in u:
            out.add(u)
    return out


def wp_api(root):
    items = []
    for kind in ('pages', 'posts', 'portfolio', 'project', 'product'):
        for page in range(1, 20):
            try:
                body, _ = get(f'{root}wp-json/wp/v2/{kind}?per_page=100&page={page}&_fields=link,title,content,date,excerpt')
                arr = json.loads(body)
            except Exception:  # noqa: BLE001
                break
            if not isinstance(arr, list) or not arr:
                break
            for a in arr:
                items.append((kind, html.unescape(re.sub('<[^>]+>', '', (a.get('title') or {}).get('rendered', ''))), a.get('link', ''),
                              (a.get('content') or {}).get('rendered', ''), a.get('date', '')))
            if len(arr) < 100:
                break
    media = set()
    for page in range(1, 30):
        try:
            body, _ = get(f'{root}wp-json/wp/v2/media?per_page=100&page={page}&_fields=source_url')
            arr = json.loads(body)
        except Exception:  # noqa: BLE001
            break
        if not isinstance(arr, list) or not arr:
            break
        media.update(a['source_url'] for a in arr if a.get('source_url'))
        if len(arr) < 100:
            break
    return items, media


def crawl(root, host, cap=150):
    seen, queue, items, media = set(), [root], [], set()
    while queue and len(seen) < cap:
        u = queue.pop(0)
        if u in seen:
            continue
        seen.add(u)
        try:
            h, fin = get(u)
        except Exception as e:  # noqa: BLE001
            items.append(('page', f'(error {type(e).__name__})', u, '', ''))
            continue
        t = re.search(r'(?is)<title[^>]*>(.*?)</title>', h)
        body = re.search(r'(?is)<body[^>]*>(.*)</body>', h)
        items.append(('page', html.unescape(t.group(1).strip()) if t else u, fin, body.group(1) if body else h, ''))
        media |= media_in(h, fin)
        for m in re.finditer(r'href=["\']([^"\'#]+)', h, re.I):
            v = urllib.parse.urljoin(fin, html.unescape(m.group(1))).split('#')[0]
            p = urllib.parse.urlsplit(v)
            if p.hostname == host and not MEDIA_RE.search(v) and not re.search(r'/(wp-admin|wp-json|wp-login|feed|xmlrpc|wp-content|wp-includes)', p.path) and v not in seen:
                queue.append(v)
    return items, media


def slug(s):
    return (re.sub(r'[^a-z0-9а-яіїєґ]+', '-', s.lower()).strip('-') or 'page')[:60]


def main():
    for sub in sys.argv[1:]:
        host = f'{sub}.solid-sound.net'
        root = f'https://{host}/'
        d = os.path.join(OUT, sub)
        os.makedirs(os.path.join(d, 'pages'), exist_ok=True)
        items, media = wp_api(root)
        how = 'WordPress API'
        if not items:
            items, media2 = crawl(root, host)
            media |= media2
            how = 'обход ссылок'
        else:
            for _, _, link, content, _ in items:
                media |= media_in(content, link or root)
            try:
                h, fin = get(root)
                media |= media_in(h, fin)
                items.insert(0, ('home', 'Главная', fin, re.search(r'(?is)<body[^>]*>(.*)</body>', h).group(1), ''))
            except Exception:  # noqa: BLE001
                pass
        idx = [f'# {host} — что там есть ({how})', '', f'Страниц/записей: {len(items)} · файлов (картинки, аудио, видео): {len(media)}', '', '| Тип | Заголовок | Адрес | Слов |', '|---|---|---|---|']
        for i, (kind, title, link, content, date) in enumerate(items):
            txt = text_of(content)
            words = len(txt.split())
            name = f'{i:03d}-{slug(title)}.md'
            open(os.path.join(d, 'pages', name), 'w').write(f'# {title}\n\n{link}  {date}\n\n{txt}\n')
            idx.append(f'| {kind} | [{title.replace("|", "/")[:70]}](pages/{name}) | {link} | {words} |')
        idx += ['', '## Файлы', ''] + [f'- {m}' for m in sorted(media)]
        open(os.path.join(d, 'index.md'), 'w').write('\n'.join(idx) + '\n')
        md = os.path.join(d, 'media')
        os.makedirs(md, exist_ok=True)
        got = 0
        for m in sorted(media):
            if m.startswith('EMBED '):
                continue
            fn = slug(urllib.parse.unquote(urllib.parse.urlsplit(m).path.rsplit('/', 1)[-1].rsplit('.', 1)[0])) + '.' + MEDIA_RE.search(m).group(1).lower()
            try:
                data, _ = get(m, binary=True, limit=60_000_000)
                open(os.path.join(md, fn), 'wb').write(data)
                got += 1
            except Exception as e:  # noqa: BLE001
                print('media fail', m, e)
        print(f'{sub}: {len(items)} items, {len(media)} media, downloaded {got} ({how})', flush=True)


if __name__ == '__main__':
    main()
