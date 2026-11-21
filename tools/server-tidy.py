"""Take an old sub-site off the air WITHOUT deleting it (owner 21.11.2026: «хочу удалить этот сайт» — landingpt).

The sub-site's folder on the hosting (solid-sound.net/<name>) is moved to solid-sound.net/_removed/<name>-<date>.
The sub-domain stops showing the site, the files stay on the server as a backup (move back = undo).
Only folders from ALLOWED can be moved; the main site (www) can never be touched.
Run: python3 tools/server-tidy.py move <name>   |   python3 tools/server-tidy.py undo <name>-<date>
"""
import datetime as dt, ftplib, os, posixpath, sys

sys.path.insert(0, os.path.dirname(__file__))
from ftp_sync import Conn, listing  # noqa: E402

ROOT = 'solid-sound.net'
ALLOWED = {'landingpt'}  # add a name here only after the owner asked for it


def size(c, path, depth=0):
    n = files = 0
    try:
        dirs, fs = c.run(listing, path)
    except ftplib.all_errors:
        return 0, 0
    files += len(fs)
    for f in fs:
        try:
            n += c.f.size(posixpath.join(path, f)) or 0
        except ftplib.all_errors:
            pass
    if depth < 6:
        for d in dirs:
            a, b = size(c, posixpath.join(path, d), depth + 1)
            n += a
            files += b
    return n, files


def main():
    cmd, name = sys.argv[1], sys.argv[2].strip('/')
    c = Conn()
    dirs, _ = c.run(listing, ROOT)
    print('folders in', ROOT, ':', ', '.join(sorted(dirs)))
    if cmd == 'move':
        if name not in ALLOWED or name in ('www', ''):
            sys.exit(f'refused: {name} is not in ALLOWED')
        if name not in dirs:
            sys.exit(f'{name}: no such folder (already moved?)')
        top_d, top_f = c.run(listing, posixpath.join(ROOT, name))  # quick look only: a full size count over FTP takes too long
        print(f'{name}: top level {len(top_d)} folders, {len(top_f)} files: {", ".join(sorted(top_d + top_f)[:20])}')
        if '_removed' not in dirs:
            c.f.mkd(posixpath.join(ROOT, '_removed'))
        dst = posixpath.join(ROOT, '_removed', f'{name}-{dt.date.today():%Y-%m-%d}')
        c.f.rename(posixpath.join(ROOT, name), dst)
        print('moved to', dst)
    elif cmd == 'undo':
        base = name.rsplit('-', 3)[0]
        c.f.rename(posixpath.join(ROOT, '_removed', name), posixpath.join(ROOT, base))
        print('restored', base)
    d2, _ = c.run(listing, ROOT)
    print('now:', ', '.join(sorted(d2)))


if __name__ == '__main__':
    main()
