#!/bin/bash
# Saves bot results to the repo, retrying when another robot pushed at the same moment.
# usage: bash .github/save.sh "<commit message>" <path>...
msg="$1"; shift
git config user.name "${BOT_NAME:-bot}"; git config user.email "actions@users.noreply.github.com"
git add "$@"
git commit -qm "$msg" || { echo "nothing to save"; exit 0; }
for i in 1 2 3 4 5 6; do
  if git pull --rebase -q origin main && git push -q; then echo "saved"; exit 0; fi
  git rebase --abort 2>/dev/null; sleep $((i * 7))
done
echo "could not push after 6 tries"; exit 1
