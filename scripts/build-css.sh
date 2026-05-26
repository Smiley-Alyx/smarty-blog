#!/usr/bin/env sh
set -eu

ROOT="$(CDPATH= cd -- "$(dirname "$0")/.." && pwd)"
SCSS="$ROOT/assets/scss/style.scss"
CSS="$ROOT/public/assets/css/style.css"

if [ ! -f "$SCSS" ]; then
    echo "SCSS source not found: $SCSS" >&2
    exit 1
fi

mkdir -p "$(dirname "$CSS")"

if ! command -v sass >/dev/null 2>&1; then
    echo "sass CLI not found." >&2
    echo "Run inside Docker: docker compose exec php php cli build-css" >&2
    echo "Or rebuild PHP image: docker compose build php" >&2
    exit 1
fi

sass "$SCSS" "$CSS" --style=expanded --no-source-map

echo "Built $CSS"
