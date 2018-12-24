#!/bin/bash

set -e

branch=${1}

declare -a tmps=(
    /tmp/diff.log
    /tmp/tests.log
)

for file in ${tmps}; do
    if [ -f "${file}" ]; then
        rm -rf ${file}
    fi
done

echo "branch: ${branch}"

set +e
git diff -p --name-only --reverse --format="" origin/master..origin/${branch} | sort | uniq > /tmp/diff.log
tests=`cat /tmp/diff.log | grep -e '^cake28/Test/Case' -e '^app/test/phpunit' | grep 'php$'`
if [ "${tests}" ]; then
    "${tests}" > /tmp/tests.log
fi
set -e

echo "--------------------------------------------------"
echo "diff files:"
echo "`cat /tmp/diff.log`"
if [ -f /tmp/tests.log ]; then
    echo "--------------------------------------------------"
    echo "tests:"
    echo "`cat /tmp/tests.log`"
fi

exit 0
