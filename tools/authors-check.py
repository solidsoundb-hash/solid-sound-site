#!/usr/bin/env python3
"""Менеджер авторов (25.11.2026): проверка товаров — обложка, демо, описание, цена/формат.
Только чтение: открытый API магазина (/wp-json/wc/store/v1/products) + скачивание обложки и демо.
Авторы товаров — из /wp-json/wp/v2/product (если сайт отдаёт поле author), владелец — OWNER_IDS.
Правила — как у робота загрузки (modules/creators.php): обложка квадрат >= 1000 px, <= 10 МБ; демо MP3 1–10 мин, >= 128 kbps.
Отчёт: reports/authors/products.md (+ .json). Запуск: строка в .github/authors-check.txt или по расписанию.
"""
import urllib.parse
import html, json, os, re, shutil, struct, subprocess, sys, time, urllib.error, urllib.request

SITE = os.environ.get('SITE', 'https://solid-sound.net').rstrip('/')
OWNER_IDS = {int(x) for x in os.environ.get('OWNER_IDS', '1').split(',') if x.strip()}
TEAM_IDS = {int(x) for x in os.environ.get('TEAM_IDS', '6').split(',') if x.strip()}  # 6 = uagen.music (уточнить у владельца)
OUT = os.environ.get('OUT', 'reports/authors')
ONLY = {int(x) for x in os.environ.get('ONLY_IDS', '').split(',') if x.strip()}  # ступень 1: один товар (например 1102)
LIMIT = int(os.environ.get('LIMIT', '0') or 0)  # ступень 2: 5–10 товаров
UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0'


def get(url, limit=None, tries=4):
    for i in range(tries):
        try:
            url = urllib.parse.quote(url, safe=":/?&=%#+,;@~")  # кириллица в именах файлов (обложка 1102)
            req = urllib.request.Request(url, headers={'User-Agent': UA, 'Accept': '*/*'})
            with urllib.request.urlopen(req, timeout=60) as r:
                data = r.read(limit) if limit else r.read()
                return r.status, dict(r.headers), data
        except urllib.error.HTTPError as e:
            if e.code in (429, 503, 502) and i < tries - 1:
                time.sleep(15 * (i + 1)); continue
            return e.code, dict(e.headers or {}), b''
        except Exception as e:  # noqa
            if i < tries - 1:
                time.sleep(10); continue
            return 0, {}, str(e).encode()
    return 0, {}, b''


def img_size(b):
    """(w, h) для JPEG/PNG/WebP по заголовку, без сторонних библиотек."""
    if b[:8] == b'\x89PNG\r\n\x1a\n':
        return struct.unpack('>II', b[16:24])
    if b[:4] == b'RIFF' and b[8:12] == b'WEBP':
        c = b[12:16]
        if c == b'VP8X':
            return 1 + int.from_bytes(b[24:27], 'little'), 1 + int.from_bytes(b[27:30], 'little')
        if c == b'VP8 ':
            w, h = struct.unpack('<HH', b[26:30]); return w & 0x3fff, h & 0x3fff
        if c == b'VP8L':
            v = int.from_bytes(b[21:25], 'little'); return (v & 0x3fff) + 1, ((v >> 14) & 0x3fff) + 1
    if b[:2] == b'\xff\xd8':
        i = 2
        while i < len(b) - 9:
            if b[i] != 0xff:
                i += 1; continue
            m = b[i + 1]
            if m in (0xc0, 0xc1, 0xc2, 0xc3, 0xc5, 0xc6, 0xc7, 0xc9, 0xca, 0xcb, 0xcd, 0xce, 0xcf):
                h, w = struct.unpack('>HH', b[i + 5:i + 9]); return w, h
            if m in (0xd8, 0x01) or 0xd0 <= m <= 0xd7:
                i += 2; continue
            i += 2 + struct.unpack('>H', b[i + 2:i + 4])[0]
    return None


def text_of(h):
    h = re.sub(r'(?s)<(script|style)[^>]*>.*?</\1>', ' ', h or '')
    h = re.sub(r'\[[^\]]+\]', ' ', h)
    return re.sub(r'\s+', ' ', html.unescape(re.sub('<[^>]+>', ' ', h))).strip()


def probe_mp3(url):
    st, hd, data = get(url)
    if st != 200 or not data:
        return {'ok': False, 'err': f'демо не открывается (код {st})'}
    if not shutil.which('ffprobe'):
        return {'ok': True, 'bytes': len(data), 'dur': None, 'kbps': None}
    tmp = '/tmp/ss-demo.mp3'
    open(tmp, 'wb').write(data)
    try:
        o = json.loads(subprocess.run(['ffprobe', '-v', 'error', '-show_entries', 'format=duration,bit_rate',
                                       '-of', 'json', tmp], capture_output=True, text=True, timeout=60).stdout or '{}')
        f = o.get('format', {})
        return {'ok': True, 'bytes': len(data), 'dur': float(f.get('duration') or 0), 'kbps': int(f.get('bit_rate') or 0) // 1000}
    except Exception as e:  # noqa
        return {'ok': True, 'bytes': len(data), 'dur': None, 'kbps': None, 'err': str(e)}


def main():
    os.makedirs(OUT, exist_ok=True)
    st, _, raw = get(f'{SITE}/wp-json/wc/store/v1/products?per_page=100')
    try:
        products = json.loads(raw)
        assert isinstance(products, list)
    except Exception:
        open(f'{OUT}/products.md', 'w').write(f'# Товары — проверка\n\n❌ Магазин не отдал список товаров (код {st}). Повторить позже.\n')
        print('store api failed', st); return 1
    authors, names = {}, {}
    st2, _, raw2 = get(f'{SITE}/wp-json/wp/v2/product?per_page=100&_fields=id,author&status=publish')
    try:
        authors = {p['id']: p.get('author') for p in json.loads(raw2)}
    except Exception:
        pass
    if ONLY:
        products = [p for p in products if p.get('id') in ONLY]
    if LIMIT:
        products = products[:LIMIT]
    rows = []
    for p in products:
        time.sleep(2)
        pid, name = p.get('id'), html.unescape(p.get('name', ''))
        aid = authors.get(pid)
        who = 'владелец' if aid in OWNER_IDS else ('команда?' if aid in TEAM_IDS else (f'автор #{aid}' if aid else 'неизвестно'))
        issues, info = [], {}
        # обложка
        imgs = p.get('images') or []
        if not imgs:
            issues.append('🔴 нет обложки')
        else:
            s, hd, b = get(imgs[0]['src'])
            size = len(b)
            wh = img_size(b) if s == 200 else None
            info['cover'] = f'{wh[0]}×{wh[1]}, {size // 1024} КБ' if wh else f'код {s}'
            if s != 200:
                issues.append(f'🔴 обложка не открывается (код {s})')
            elif not wh:
                issues.append('🟠 формат обложки не распознан')
            else:
                if wh[0] != wh[1]:
                    issues.append(f'🟠 обложка не квадратная ({wh[0]}×{wh[1]})')
                if min(wh) < 1000:
                    issues.append(f'🟠 обложка меньше 1000 px ({wh[0]}×{wh[1]})')
            if size > 10 * 1024 * 1024:
                issues.append(f'🟠 обложка тяжелее 10 МБ ({size // 1048576} МБ)')
            if not (imgs[0].get('alt') or '').strip():
                issues.append('🟡 у обложки нет подписи (alt)')
        # демо
        allh = (p.get('description') or '') + ' ' + (p.get('short_description') or '')
        mp3s = re.findall(r'https?://[^"\'\s<>\]]+?\.mp3', allh)
        if not mp3s:
            issues.append('🔴 нет демо (MP3) на странице')
        else:
            d = probe_mp3(html.unescape(mp3s[0]))
            if not d['ok']:
                issues.append('🔴 ' + d['err'])
            else:
                if d.get('dur') is not None:
                    info['demo'] = f"{int(d['dur'] // 60)}:{int(d['dur'] % 60):02d}, {d['kbps']} kbps"
                    if d['dur'] < 60:
                        issues.append(f"🟠 демо короче 1 мин ({int(d['dur'])} с)")
                    if d['dur'] > 600:
                        issues.append('🟡 демо длиннее 10 мин')
                    if d['kbps'] and d['kbps'] < 112:  # VBR: файл «128 kbps» в среднем даёт 113–127 (25.11)
                        issues.append(f"🟠 демо ниже 128 kbps ({d['kbps']})")
        # описание
        t = text_of(allh)
        info['text'] = f'{len(t)} знаков'
        if len(t) < 150:
            issues.append(f'🟠 описание почти пустое ({len(t)} знаков)')
        elif len(t) < 400:
            issues.append(f'🟡 короткое описание ({len(t)} знаков)')
        # цена, категории
        pr = p.get('prices') or {}
        if not p.get('is_purchasable'):
            issues.append('🟡 товар нельзя купить (is_purchasable = нет)')
        info['cats'] = ', '.join(c.get('name', '') for c in (p.get('categories') or [])) or '—'
        # категорию не проверяем: Store API отдаёт [] даже при заполненной (товар 1102, 25.11) — ложная тревога
        if not p.get('tags'):
            issues.append('🟡 нет тегов')
        info['price'] = (str(int(pr.get('price', '0')) / (10 ** pr.get('currency_minor_unit', 2))) + ' ' + pr.get('currency_code', '')) if pr.get('price') else '—'
        rows.append({'id': pid, 'name': name, 'url': p.get('permalink'), 'who': who, 'issues': issues, 'info': info})

    def sev(r):
        return (0 if r['who'].startswith('автор') else 1, -sum(3 if i[0] == '🔴' else 2 if i[0] == '🟠' else 1 for i in r['issues']))
    rows.sort(key=sev)
    na = sum(1 for r in rows if r['who'].startswith('автор'))
    L = [f'# Товары — проверка ({time.strftime("%Y-%m-%d %H:%M UTC", time.gmtime())})', '',
         f'Всего товаров: {len(rows)} · от авторов: {na} · авторы известны: {"да" if authors else "нет (сайт не отдал поле author)"}', '',
         'Правила: обложка квадрат ≥ 1000 px и ≤ 10 МБ; демо MP3 1–10 мин, ≥ 128 kbps; описание ≥ 400 знаков. Файлы для скачивания '
         'роботу без входа не видны — их проверяет робот загрузки при Add Product.', '',
         '| Товар | Чей | Обложка | Демо | Текст | Цена | Замечания |', '|---|---|---|---|---|---|---|']
    for r in rows:
        i = r['info']
        L.append(f"| [{r['name']}]({r['url']}) | {r['who']} | {i.get('cover', '—')} | {i.get('demo', '—')} | {i.get('text', '—')} | "
                 f"{i.get('price', '—')} | {'<br>'.join(r['issues']) or '✅'} |")
    open(f'{OUT}/products.md', 'w', encoding='utf8').write('\n'.join(L) + '\n')
    json.dump(rows, open(f'{OUT}/products.json', 'w', encoding='utf8'), ensure_ascii=False, indent=1)
    print('\n'.join(L))
    return 0


if __name__ == '__main__':
    sys.exit(main())
