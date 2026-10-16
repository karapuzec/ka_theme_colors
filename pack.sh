#!/bin/sh
# builds ka_theme_colors.ocmod.zip (the same way as pack_*.bat in Ka Extensions)
cd "$(dirname "$0")"
rm -f ka_theme_colors.ocmod.zip
zip -qr ka_theme_colors.ocmod.zip install.json readme.txt admin kamod system
