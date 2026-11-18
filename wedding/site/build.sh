#!/bin/sh
# Собирает deploy.html (полная страница для хостинга) из index.html (версия для облачного черновика).
cd "$(dirname "$0")"
{ printf '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">\n'
  sed -n '1,/^<\/style>/p' index.html; echo '</head><body>'
  sed '1,/^<\/style>/d' index.html; echo '</body></html>'; } > deploy.html
