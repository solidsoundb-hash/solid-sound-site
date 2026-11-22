#!/usr/bin/env bash
# YouTube example probe (owner 21.11.2026: «это самый крутой пример, ставить первым»).
# Usage: bash tools/yt-probe.sh <video id>. Output: yt-probe/<id>/ (report.md, frames, audio halves analysis, mp4 if allowed).
set -u
ID=$1; OUT=yt-probe/$ID; mkdir -p "$OUT"; R=$OUT/report.md
U="https://www.youtube.com/watch?v=$ID"
echo "# YouTube $ID ($(date -u +%F))" > "$R"
echo -e "\n## oEmbed\n\`\`\`" >> "$R"; curl -s -m 30 "https://www.youtube.com/oembed?url=$U&format=json" >> "$R"; echo -e "\n\`\`\`" >> "$R"
ok=""
for c in "" "--extractor-args youtube:player_client=tv" "--extractor-args youtube:player_client=ios" "--extractor-args youtube:player_client=web_safari" "--extractor-args youtube:player_client=mweb"; do
  yt-dlp -q $c -f 'bv*[height<=720]+ba/b[height<=720]/b' --merge-output-format mp4 -o "$OUT/v.%(ext)s" --write-info-json "$U" 2>>"$OUT/err.txt" && { ok="$c"; break; }
done
if [ -z "$ok" ] && [ ! -f "$OUT/v.mp4" ]; then echo -e "\n**Скачать не удалось** (см. err.txt)" >> "$R"; tail -5 "$OUT/err.txt" >> "$R"; exit 0; fi
f=$(ls $OUT/v.* | grep -v json | head -1)
python3 - "$OUT/v.info.json" >> "$R" <<'PY' || true
import json,sys
d=json.load(open(sys.argv[1]))
print("\n## Видео\n- Название:",d.get("title"),"\n- Канал:",d.get("uploader"),"\n- Дата:",d.get("upload_date"),"\n- Длина, с:",d.get("duration"),"\n- Просмотры:",d.get("view_count"),"\n- Разрешение:",d.get("width"),"x",d.get("height"))
print("\n### Описание\n```\n"+(d.get("description") or "")[:2000]+"\n```")
ch=d.get("chapters") or []
if ch: print("\n### Главы"); [print("-",round(c["start_time"]),"с —",c["title"]) for c in ch]
PY
rm -f "$OUT/v.info.json"
d=$(ffprobe -v error -show_entries format=duration -of csv=p=0 "$f" | cut -d. -f1)
echo -e "\n## Громкость по секундным отрезкам (LUFS кратк., каждые 2 с)\n\`\`\`" >> "$R"
ffmpeg -v info -i "$f" -af ebur128 -f null - 2>&1 | grep -E 't: *[0-9]' | awk '{for(i=1;i<=NF;i++){if($i=="t:")t=$(i+1); if($i=="S:")s=$(i+1)}; if(int(t)%2==0 && int(t)!=last){print int(t)"s "s; last=int(t)}}' >> "$R"
echo '```' >> "$R"
echo -e "\n## Смены сцены (кадры)" >> "$R"
ffmpeg -v error -i "$f" -vf "select='gt(scene,0.35)',showinfo,scale=480:-1" -vsync vfr "$OUT/scene-%03d.jpg" 2>&1 | grep -o 'pts_time:[0-9.]*' | head -40 | tr '\n' ' ' >> "$R"
for p in 5 20 35 50 65 80 95; do t=$(( d * p / 100 )); ffmpeg -v error -y -ss "$t" -i "$f" -frames:v 1 -vf scale=640:-1 "$OUT/at-$p.jpg"; done
echo -e "\n\nКадры: at-5…at-95.jpg, scene-*.jpg" >> "$R"
# keep a small copy for the site if needed
ffmpeg -v error -y -i "$f" -c:v libx264 -crf 28 -preset veryfast -vf "scale=-2:min(720\,ih)" -c:a aac -b:a 128k -movflags +faststart "$OUT/$ID-720.mp4" && rm -f "$f"
ls -la "$OUT" >> "$R"
