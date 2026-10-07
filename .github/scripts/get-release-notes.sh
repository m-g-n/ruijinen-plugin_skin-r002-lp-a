#!/usr/bin/env bash
# README.md の「# 変更履歴」から指定バージョンの項目（## <version> 〜 次の見出しの手前）を出力する.
set -euo pipefail

version="${1:?バージョンを指定してください}"
readme="${2:-README.md}"

if [[ ! -f "$readme" ]]; then
	echo "::error::${readme} が見つかりません" >&2
	exit 1
fi

notes="$(awk -v version="$version" '
	/^#{1,2}[[:space:]]/ {
		if (in_section) { exit }
		heading = $0
		sub(/^##[[:space:]]+/, "", heading)
		sub(/[[:space:]]+$/, "", heading)
		if ($0 ~ /^##[[:space:]]/ && heading == version) { in_section = 1; next }
	}
	in_section { print }
' "$readme" | sed -e '/./,$!d' | tr -d '\r')"

if [[ -z "$notes" ]]; then
	echo "::warning file=${readme}::変更履歴に ${version} の記載がありません" >&2
	notes="- （README.md の変更履歴に ${version} の記載がありません）"
fi

printf '%s\n' "$notes"
