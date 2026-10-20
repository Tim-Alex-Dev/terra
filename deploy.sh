#!/bin/bash
# cPanel Git deployment (.cpanel.yml): copies the built theme (master has dist/, made by npm run release) into the site's theme folder
set -euo pipefail
cd "$(dirname "$0")"
THEME_PATH="${DEPLOYPATH:-$HOME/public_html/wp-content/themes/terra/}"

if [ ! -f dist/css/main.css ]; then
	echo "dist/ is missing: deploy master after npm run release" >&2
	exit 1
fi

mkdir -p "$THEME_PATH"
if command -v rsync > /dev/null; then
	rsync -a --delete --exclude-from=deploy-exclude.txt ./ "$THEME_PATH"
else
	rm -rf "${THEME_PATH}dist"
	cp -R ./*.php style.css screenshot.png favicon.ico acf-json dist inc languages template-parts "$THEME_PATH"
fi
echo "Deployed $(git rev-parse --short HEAD) to $THEME_PATH"
