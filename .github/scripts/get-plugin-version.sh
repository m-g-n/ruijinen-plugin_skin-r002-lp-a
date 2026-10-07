#!/usr/bin/env bash
# プラグインのルートファイルのヘッダー（Version:）からバージョンを取得して出力する.
set -euo pipefail

plugin_file="${1:-ruijinen-skin-r002-lp.php}"

if [[ ! -f "$plugin_file" ]]; then
	echo "::error::プラグインのルートファイルが見つかりません: ${plugin_file}" >&2
	exit 1
fi

version="$(sed -n -E 's/^[[:space:]]*\*?[[:space:]]*Version:[[:space:]]*([^[:space:]]+).*$/\1/p' "$plugin_file" | head -n 1 | tr -d '\r')"

if [[ ! "$version" =~ ^[0-9]+(\.[0-9]+)*$ ]]; then
	echo "::error file=${plugin_file}::Version ヘッダーを取得できないか、形式が不正です: '${version}'" >&2
	exit 1
fi

echo "$version"
