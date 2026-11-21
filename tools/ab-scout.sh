#!/usr/bin/env bash
# Before/after examples scout (owner 21.11.2026: «сопоставить и совместить, лишнее убрать, всё с кнопкой до/после»).
# For every known example: length, 4 frames (10 %, 40 %, 60 %, 90 %) and the loudness curve, so we can see where
# "before" ends and "after" begins. SoundCloud tracks from the old post-production site: title + length.
# Output: ab-scout/ (report.md + jpg) — the workflow puts it on the branch `ab-scout`.
set -u
OUT=ab-scout; mkdir -p "$OUT"
UP=https://solid-sound.net/wp-content/uploads
VIDS="Mixing-Example-Anouch-Space-Echo.mp4 Mixing-Cases-Feeling-Good.mp4 Mixing-Example-Anouch-Eco-Techno.mp4 Mixing-Example-Escape.mp4 02-Example-Before-After.mp4 Example-In-My-Mind.mp4 Mixing-Example-AVA-KEDAVRA.mp4.mp4"
SC="419751676 419751680 423695187 423699429 459976563 459976653 462271863 462271899 462895656 462897129"
YT="AoHIenCHQY4 O18lHFvxZeQ SEFtbYJUvvo XRc4lvaQjk8 b2YDThiwATY doQtYHcl61Q lpjlnxQxvAY npat5BHwLvA vYIh0YHw33A vwpfL1M9vhs"
R="$OUT/report.md"
echo "# Примеры до/после — разведка ($(date -u +%F))" > "$R"
echo -e "\n## Видео на сайте (uploads)\n| Файл | Длина, с | Кадры 10/40/60/90 % | Громкость по 10 % отрезкам (LUFS) |\n|---|---|---|---|" >> "$R"
for v in $VIDS; do
  n=${v%%.*}; f="$OUT/$n.mp4"
  curl -sSL -m 300 -A "Mozilla/5.0 (Linux; Android 12) Chrome/124 Mobile" -o "$f" "$UP/$v" || { echo "| $v | ошибка загрузки | | |" >> "$R"; continue; }
  d=$(ffprobe -v error -show_entries format=duration -of csv=p=0 "$f" | cut -d. -f1)
  [ -z "$d" ] && { echo "| $v | не видео | | |" >> "$R"; continue; }
  imgs=""
  for p in 10 40 60 90; do t=$(( d * p / 100 )); ffmpeg -v error -y -ss "$t" -i "$f" -frames:v 1 -vf scale=480:-1 "$OUT/$n-$p.jpg" && imgs="$imgs [$p%]($n-$p.jpg)"; done
  seg=$(( d / 10 )); [ "$seg" -lt 1 ] && seg=1; lu=""
  for i in 0 1 2 3 4 5 6 7 8 9; do
    l=$(ffmpeg -v info -ss $(( i * seg )) -t "$seg" -i "$f" -af ebur128 -f null - 2>&1 | grep -E '^\s+I:' | tail -1 | awk '{print $2}')
    lu="$lu ${l:-?}"
  done
  echo "| $v | $d |$imgs |$lu |" >> "$R"
  rm -f "$f"
done
echo -e "\n## SoundCloud (старый сайт post.)\n| Трек | Название | Длина, с |\n|---|---|---|" >> "$R"
for t in $SC; do
  j=$(yt-dlp -q --skip-download --dump-json "https://api.soundcloud.com/tracks/$t" 2>/dev/null || true)
  ti=$(echo "$j" | python3 -c 'import sys,json;d=json.load(sys.stdin);print(d.get("title",""),"|",d.get("uploader",""),"|",d.get("webpage_url",""))' 2>/dev/null)
  du=$(echo "$j" | python3 -c 'import sys,json;print(int(json.load(sys.stdin).get("duration") or 0))' 2>/dev/null)
  echo "| $t | ${ti:-не открылся} | ${du:-} |" >> "$R"
done
echo -e "\n## YouTube (старый сайт post.)\n| Видео | Название | Длина, с |\n|---|---|---|" >> "$R"
for y in $YT; do
  j=$(yt-dlp -q --skip-download --dump-json "https://www.youtube.com/watch?v=$y" 2>/dev/null || true)
  ti=$(echo "$j" | python3 -c 'import sys,json;d=json.load(sys.stdin);print(d.get("title",""))' 2>/dev/null)
  du=$(echo "$j" | python3 -c 'import sys,json;print(int(json.load(sys.stdin).get("duration") or 0))' 2>/dev/null)
  echo "| $y | ${ti:-не открылся} | ${du:-} |" >> "$R"
done
echo done
