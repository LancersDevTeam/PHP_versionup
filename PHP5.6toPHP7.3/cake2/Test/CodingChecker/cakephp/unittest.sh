#!/bin/bash

ROOT_PATH=`pwd`

rm -f /tmp/test_failed.log

result=0
ok=0
failure=0

tests=$(
    find ${ROOT_PATH}/cake28/Test/Case/ \
    -type f \
    -name "*Test.php" \
    -not -name 'AllTestsTest.php' \
)

for test in $tests; do
    echo $test | tee /tmp/test.log
    /bin/bash ${ROOT_PATH}/cake28/Console/cake l_test $test 2>&1 | tee /tmp/test.log
    grep -e 'FAILURES!' -e 'Error:' /tmp/test.log > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        result=1
        failure=`expr $failure + 1`
        echo $test >> /tmp/test_failed.log
        cat /tmp/test.log >> /tmp/test_failed.log
        echo >> /tmp/test_failed.log
    fi
    echo '======================================================================' | tee /tmp/test.log
done

if [ ${result} = 1 ]; then
    echo "FAILURES! detail：/tmp/test_failed.log"
fi

echo "All Failures: $failure."

exit ${result}
