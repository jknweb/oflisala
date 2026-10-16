#!/usr/bin/env bash
# Construit les archives installables du thème et de l'extension dans wordpress/dist/.
#   dist/oc-lisala-core.zip  extension « OC Lisala – Contenus du club »
#   dist/oc-lisala.zip       thème (contient aussi l'extension, installable en un clic)
set -euo pipefail
cd "$(dirname "$0")"
rm -rf dist && mkdir -p dist

# Extension
zip -rqX dist/oc-lisala-core.zip oc-lisala-core -x '*.DS_Store' '*.gitkeep'

# Thème (avec l'extension embarquée dans inc/plugins/)
cp dist/oc-lisala-core.zip oc-lisala/inc/plugins/oc-lisala-core.zip
zip -rqX dist/oc-lisala.zip oc-lisala -x '*.DS_Store' '*.gitkeep'
rm oc-lisala/inc/plugins/oc-lisala-core.zip

ls -l dist
