#!/usr/bin/env bash
# Runs all starter unit tests (pure functions, no WordPress needed).
# Requirements: php (CLI) and node in PATH. Usage: bash .claude/tests/run.sh
cd "$(dirname "$0")" || exit 1
status=0
for t in svg-support.test.php help-func.test.php acf.test.php components.test.php; do
	echo "== $t"; php "$t" | tail -n 1 | grep -q "ALL PASS" && echo "PASS" || { php "$t"; status=1; }
done
echo "== js-functions.test.mjs"; node js-functions.test.mjs | tail -n 1 | grep -q "ALL PASS" && echo "PASS" || { node js-functions.test.mjs; status=1; }
exit $status
