#!/bin/bash
# Saves robot results to a results branch (shots-ab, …), retrying when another robot pushed there at the same moment
# (22.11: probe and page-shot got their own queues, so they may finish together).
# usage: BOT_NAME=x GH_TOKEN=… bash .github/save-branch.sh <branch> "<commit message>" <dir>...
#   every <dir> is copied to the branch root, replacing the folder with the same name there.
B=$1; M=$2; shift 2
R="https://x-access-token:${GH_TOKEN}@github.com/${GITHUB_REPOSITORY}.git"
for i in 1 2 3 4 5 6; do
  git worktree remove -f _sb 2>/dev/null; rm -rf _sb
  if git fetch -q origin "$B" 2>/dev/null; then git worktree add -q -f --detach _sb FETCH_HEAD
  else git worktree add -q -f --detach _sb && (cd _sb && git checkout -q --orphan tmp && git rm -rqf . >/dev/null; true); fi
  for d in "$@"; do [ -e "$d" ] || continue; rm -rf "_sb/$(basename "$d")"; cp -r "$d" _sb/; done
  if (cd _sb && git config user.name "${BOT_NAME:-bot}" && git config user.email actions@users.noreply.github.com \
      && git add -A && { git commit -qm "$M" || { echo "nothing to save"; exit 0; }; } && git push -q "$R" HEAD:"$B"); then
    git worktree remove -f _sb 2>/dev/null; echo "saved to $B"; exit 0
  fi
  echo "push failed (try $i), retrying"; sleep $((i * 7))
done
echo "could not save to $B after 6 tries"; exit 1
