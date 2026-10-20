#!/bin/bash
# cPanel Git deployment (.cpanel.yml): builds the theme on the server and copies it into the site's theme folder
set -euo pipefail
cd "$(dirname "$0")"
THEME_PATH="${DEPLOYPATH:-$HOME/public_html/wp-content/themes/terra/}"

# Node.js 20.19+: in PATH, CloudLinux "Setup Node.js App" (/opt/alt/alt-nodejs*) or nvm
node_ok() {
	"$1" -e 'const [a, b] = process.versions.node.split(".").map(Number); process.exit(a > 20 || (a === 20 && b >= 19) ? 0 : 1)' 2> /dev/null
}
NODE_BIN=""
if command -v node > /dev/null && node_ok node; then
	NODE_BIN="$(dirname "$(command -v node)")"
else
	for dir in $(ls -d /opt/alt/alt-nodejs*/root/usr/bin "$HOME"/.nvm/versions/node/*/bin 2> /dev/null | sort -V -r); do
		if node_ok "$dir/node"; then
			NODE_BIN="$dir"
			break
		fi
	done
fi
if [ -z "$NODE_BIN" ]; then
	echo "Node.js 20.19+ not found: install it in cPanel > Setup Node.js App" >&2
	exit 1
fi
export PATH="$NODE_BIN:$PATH"
echo "Node $(node -v), npm $(npm -v)"

npm ci --no-audit --no-fund
npx gulp prod

mkdir -p "$THEME_PATH"
rsync -a --delete --exclude-from=deploy-exclude.txt ./ "$THEME_PATH"
echo "Deployed to $THEME_PATH"
