#!/usr/bin/env bash
set -euo pipefail

echo "Running InPeace code style fixes (requires php + composer installed)..."
composer install --no-interaction --prefer-dist
composer phpcbf:fix || true
composer cs:fix || true
composer analyze || true
composer test || true

echo "Done. Review changed files and run git add/commit as needed."