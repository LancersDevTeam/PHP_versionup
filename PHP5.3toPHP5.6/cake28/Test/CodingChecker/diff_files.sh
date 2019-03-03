#!/bin/bash

set -e

branch=${1}

declare -a tmps=(
    /tmp/diff.log
)

for file in ${tmps}; do
    if [ -f "${file}" ]; then
        rm -rf ${file}
    fi
done

echo "branch: ${branch}"

set +e
git diff -p --name-only --reverse --format="" origin/master...origin/${branch} | sort | uniq > /tmp/diff.log
set -e

echo "--------------------------------------------------"
echo "diff files:"
echo "`cat /tmp/diff.log`"

exit 0
