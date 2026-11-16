#!/usr/bin/env python3
"""Fast deploy plan (owner 16.11.2026: «чтобы за минуты выкладывалось»).

  deploy_plan.py <base_commit> <out_dir>

Compares the commit that is live on the server (<base_commit>, read from the server marker file) with HEAD and
writes three lists of paths relative to plugin/solid-sound-custom:
  upload.txt  files to upload, in a safe order: modules and assets first, then the rest, the loaders last
              (build/assets.php, build/custom.php, solid-sound-custom.php) — so a loader never points to a missing file;
  backup.txt  files that already exist on the server and will be overwritten (backed up first, put back on rollback);
  added.txt   files that are new on the server (deleted on rollback).
Deleted files are left on the server (harmless; old hashed assets may still be referenced by cached pages).
Exit code 2 = cannot plan (unknown base) -> the robot does a full deploy instead.
"""
import os, subprocess, sys

P = 'plugin/solid-sound-custom/'
PATHS = [P + 'build', P + 'includes', P + 'backup', P + 'solid-sound-custom.php']
LAST = ['build/assets.php', 'build/custom.php', 'solid-sound-custom.php']


def order(rel):
    if rel in LAST:
        return (9, LAST.index(rel))
    if rel.startswith('build/modules/'):
        return (0, rel)
    if rel.startswith('build/assets/'):
        return (1, rel)
    return (5, rel)


def main():
    base, out = sys.argv[1].strip(), sys.argv[2]
    if not base or subprocess.run(['git', 'cat-file', '-e', base + '^{commit}'], capture_output=True).returncode:
        print('unknown base commit -> full deploy'); sys.exit(2)
    diff = subprocess.run(['git', 'diff', '--name-status', '-M', base, 'HEAD', '--'] + PATHS,
                          capture_output=True, text=True, check=True).stdout.splitlines()
    up, bak, add = set(), set(), set()
    for line in diff:
        parts = line.split('\t')
        st = parts[0][0]
        if st == 'D':
            continue
        path = parts[-1]
        if not path.startswith(P):
            continue
        rel = path[len(P):]
        up.add(rel)
        (bak if st in 'MT' else add).add(rel)
    os.makedirs(out, exist_ok=True)
    for name, items in (('upload.txt', sorted(up, key=order)), ('backup.txt', sorted(bak)), ('added.txt', sorted(add))):
        with open(os.path.join(out, name), 'w') as fh:
            fh.write('\n'.join(items) + ('\n' if items else ''))
    print(f'fast deploy: {len(up)} files to upload ({len(bak)} changed, {len(add)} new)')
    for r in sorted(up, key=order):
        print('  ', r)


if __name__ == '__main__':
    main()
