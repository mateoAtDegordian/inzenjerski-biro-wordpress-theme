#!/usr/bin/env bash

set -euo pipefail

theme_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
output_dir="${1:-"${theme_root}/dist"}"
theme_slug="ingbiro"
repository_slug="inzenjerski-biro-wordpress-theme"
version="$(sed -n 's/^Version:[[:space:]]*//p' "${theme_root}/style.css" | head -n 1 | tr -d '\r')"

if [[ ! "${version}" =~ ^[0-9]+\.[0-9]+\.[0-9]+([.-][0-9A-Za-z.-]+)?$ ]]; then
	echo "Invalid or missing theme version in style.css: ${version:-<empty>}" >&2
	exit 1
fi

mkdir -p "${output_dir}"
output_dir="$(cd "${output_dir}" && pwd)"
package_path="${output_dir}/${repository_slug}-${version}.zip"
staging_dir="$(mktemp -d)"

# Never update an older ZIP in place: removed/excluded files would otherwise
# remain as stale entries in the archive.
rm -f "${package_path}"

cleanup() {
	rm -rf "${staging_dir}"
}
trap cleanup EXIT

mkdir -p "${staging_dir}/${theme_slug}"
rsync \
	--archive \
	--delete \
	--exclude-from="${theme_root}/.distignore" \
	"${theme_root}/" \
	"${staging_dir}/${theme_slug}/"

(
	cd "${staging_dir}"
	zip -q -r "${package_path}" "${theme_slug}"
)

echo "${package_path}"
