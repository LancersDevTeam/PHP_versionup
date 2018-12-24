#!/bin/bash

set +x

if [ ! -f /tmp/tests.log ]; then
    exit 0
fi

simple_tests=`cat /tmp/tests.log | grep '^cake28/Test/Case'`
phpunit_tests=`cat /tmp/tests.log | grep '^cake28/Test/phpunit'`

for file in ${simple_tests}; do
    if [ -f ${file} ]; then
        case=`echo "${file}" | sed -e 's|cake28/Test/Case/\(.*\)\.test\.php|\1|'`
        echo "cake -noclear 1 testsuite app case ${case}"
        ./cake/console/cake -noclear 1 testsuite app case ${case} | awk '{print substr($0, index($0, "1/1"))}'
    fi
done

for file in ${phpunit_tests}; do
    php app/vendors/bin/phpunit ${file}
done

exit 0
