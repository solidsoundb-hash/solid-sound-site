#!/usr/bin/env python3
"""FTP sync for the deploy robot, standard library only (no apt packages needed).

  ftp_sync.py download <remote_dir> <local_dir>     copy a server folder down (backup)
  ftp_sync.py upload   <local_path> <remote_path>   upload a file or a folder (creates folders)
  ftp_sync.py restore  <local_dir>  <remote_dir>    upload a folder and delete server files that are not in it
  ftp_sync.py get      <remote_file> <local_file>   download one file
  ftp_sync.py purge    <remote_dir>                 delete everything inside a server folder (page cache)

Credentials: env FTP_HOST, FTP_USER, FTP_PASS. Uses FTPS when the server offers it, plain FTP otherwise.
Every network operation has a timeout and is retried.
"""
import ftplib, os, posixpath, sys, time

TIMEOUT = 30


def connect():
    host, user, pw = os.environ['FTP_HOST'], os.environ['FTP_USER'], os.environ['FTP_PASS']
    host = host.replace('ftp://', '').strip('/')
    port = 21
    if ':' in host:
        host, port = host.rsplit(':', 1)
        port = int(port)
    try:
        f = ftplib.FTP_TLS(timeout=TIMEOUT)
        f.connect(host, port)
        f.login(user, pw)
        f.prot_p()
    except Exception:
        f = ftplib.FTP(timeout=TIMEOUT)
        f.connect(host, port)
        f.login(user, pw)
    f.set_pasv(True)
    return f


class Conn:
    def __init__(self):
        self.f = connect()

    def run(self, fn, *a):
        for i in range(4):
            try:
                return fn(self.f, *a)
            except (ftplib.error_temp, OSError, EOFError) as e:
                if i == 3:
                    raise
                print(f'  retry after {e!r}', flush=True)
                time.sleep(3 * (i + 1))
                try:
                    self.f.close()
                except Exception:
                    pass
                self.f = connect()


def listing(f, path):
    """-> (dirs, files) names in a remote folder."""
    dirs, files = [], []
    try:
        for name, facts in f.mlsd(path):
            if name in ('.', '..'):
                continue
            (dirs if facts.get('type') == 'dir' else files).append(name)
        return dirs, files
    except ftplib.error_perm:
        pass
    for full in f.nlst(path):
        name = posixpath.basename(full.rstrip('/'))
        if name in ('.', '..'):
            continue
        try:
            cur = f.pwd()
            f.cwd(posixpath.join(path, name))
            f.cwd(cur)
            dirs.append(name)
        except ftplib.error_perm:
            files.append(name)
    return dirs, files


def download(c, remote, local):
    os.makedirs(local, exist_ok=True)
    dirs, files = c.run(listing, remote)
    for n in files:
        with open(os.path.join(local, n), 'wb') as out:
            c.run(lambda f, p: (out.seek(0), out.truncate(), f.retrbinary('RETR ' + p, out.write)), posixpath.join(remote, n))
    for d in dirs:
        download(c, posixpath.join(remote, d), os.path.join(local, d))


def mkdirs(c, remote):
    parts, cur = remote.strip('/').split('/'), ''
    for p in parts:
        cur = posixpath.join(cur, p) if cur else p
        try:
            c.run(lambda f, x: f.mkd(x), cur)
        except ftplib.error_perm:
            pass  # exists


def put(c, local, remote):
    def go(f, l, r):
        with open(l, 'rb') as fh:
            f.storbinary('STOR ' + r, fh)
    c.run(go, local, remote)


def upload(c, local, remote):
    if os.path.isfile(local):
        mkdirs(c, posixpath.dirname(remote))
        put(c, local, remote)
        print('  put', remote, flush=True)
        return
    mkdirs(c, remote)
    for root, dirs, files in os.walk(local):
        rel = os.path.relpath(root, local).replace(os.sep, '/')
        rdir = remote if rel == '.' else posixpath.join(remote, rel)
        for d in dirs:
            mkdirs(c, posixpath.join(rdir, d))
        for n in files:
            put(c, os.path.join(root, n), posixpath.join(rdir, n))
    print('  uploaded', local, '->', remote, flush=True)


def prune(c, local, remote):
    dirs, files = c.run(listing, remote)
    for n in files:
        if not os.path.isfile(os.path.join(local, n)):
            c.run(lambda f, p: f.delete(p), posixpath.join(remote, n))
            print('  deleted', posixpath.join(remote, n), flush=True)
    for d in dirs:
        ld = os.path.join(local, d)
        if os.path.isdir(ld):
            prune(c, ld, posixpath.join(remote, d))
        else:
            prune(c, '/nonexistent', posixpath.join(remote, d))
            c.run(lambda f, p: f.rmd(p), posixpath.join(remote, d))


def main():
    cmd, a = sys.argv[1], sys.argv[2]
    b = sys.argv[3] if len(sys.argv) > 3 else ''
    c = Conn()
    if cmd == 'download':
        download(c, a, b)
    elif cmd == 'upload':
        upload(c, a, b)
    elif cmd == 'purge':
        # empty a server folder (page cache) but keep the folder itself
        prune(c, '/nonexistent', a)
    elif cmd == 'get':
        os.makedirs(os.path.dirname(b) or '.', exist_ok=True)
        with open(b, 'wb') as out:
            c.run(lambda f, p: (out.seek(0), out.truncate(), f.retrbinary('RETR ' + p, out.write)), a)
    elif cmd == 'restore':
        upload(c, a, b)
        prune(c, a, b)
    else:
        sys.exit('unknown command ' + cmd)
    c.f.quit()


if __name__ == '__main__':
    main()
