#!/bin/bash
# Clean preview videos for the «What's new» slider (owner 22.11.2026: «без ютубовских кнопок, чистое видео после обложки»).
# For every product whose description has a YouTube video: download it, cut the black borders burned into the video,
# take ~20 s, no sound, 960x540 H.264 (~1–2 MB) → out/<youtube-id>.mp4, uploaded to wp-content/uploads/ss-previews/.
# The slider (modules/new-slider.php) plays the file when it exists, else falls back to the YouTube embed.
# Env: PREVIEW_IDS="id1 id2" to redo only these; FORCE=1 to redo files that already exist on the site.
set -u
mkdir -p out work
IDS="${PREVIEW_IDS:-}"
if [ -z "$IDS" ]; then
  IDS=$( { curl -s -A "Mozilla/5.0 SolidSoundBots/1.0" "https://solid-sound.net/en/all-products/?nc=$RANDOM";
           curl -s -A "Mozilla/5.0 SolidSoundBots/1.0" "https://solid-sound.net/wp-json/wc/store/v1/products?per_page=100"; } \
    | grep -oE 'data-yt=\\?"[A-Za-z0-9_-]{11}|(youtube(-nocookie)?\.com\\?/(embed\\?/|watch\?v=|shorts\\?/)|youtu\.be\\?/)[A-Za-z0-9_-]{11}' | grep -oE '[A-Za-z0-9_-]{11}$' | sort -u)
fi
echo "videos: $IDS"
for id in $IDS; do
  if [ "${FORCE:-}" != 1 ] && curl -s -o /dev/null -w '%{http_code}' -I "https://solid-sound.net/wp-content/uploads/ss-previews/$id.mp4" | grep -q 200; then echo "$id: already on the site"; continue; fi
  rm -f work/$id.*
  yt-dlp -q --no-playlist -f "bv*[height<=1080][ext=mp4]/bv*[height<=1080]/b" --download-sections "*0-75" -o "work/$id.%(ext)s" "https://www.youtube.com/watch?v=$id" || { echo "$id: DOWNLOAD FAILED"; continue; }
  src=$(ls work/$id.* | head -1)
  dur=$(ffprobe -v error -show_entries format=duration -of csv=p=0 "$src" | cut -d. -f1); dur=${dur:-30}
  start=$(( dur > 40 ? 12 : (dur > 25 ? 4 : 0) ))
  crop=$(ffmpeg -ss $start -i "$src" -t 12 -vf cropdetect=30:2:0 -f null - 2>&1 | grep -oE 'crop=[0-9]+:[0-9]+:[0-9]+:[0-9]+' | sort | uniq -c | sort -rn | head -1 | awk '{print $2}')
  vf="${crop:+$crop,}scale=960:540:force_original_aspect_ratio=increase,crop=960:540,fps=30,format=yuv420p"
  ffmpeg -v error -y -ss $start -i "$src" -t 20 -vf "$vf" -an -c:v libx264 -preset slow -crf 30 -profile:v main -movflags +faststart "out/$id.mp4" \
    && echo "$id: ok ${crop:-no-crop} $(du -k out/$id.mp4 | cut -f1) KB" || echo "$id: ENCODE FAILED"
done
ls -la out
