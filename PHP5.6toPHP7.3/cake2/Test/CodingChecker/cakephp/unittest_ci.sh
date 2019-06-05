#!/bin/bash

CIRCLE_BRANCH=${1}
CIRCLE_USERNAME=${2}
CIRCLE_BUILD_URL=${3}

ROOT_PATH=`pwd`

files=$(cat /tmp/diff.log)

test=0
for file in ${files}; do
    if [ -f ${file} ] && [[ "${file}" =~ (php|ctp)$ ]]; then
        test=1
        break
    fi
    if [ -f ${file} ] && [[ "${file}" =~ (composer.json|composer.lock)$ ]]; then
        test=1
        break
    fi
done

if [ ${test} = 0 ]; then
    exit 0
fi

rm -f /tmp/test_failed.log

result=0
ok=0
failure=0

tests=$(
    find ${ROOT_PATH}/cake28/Test/Case/ \
    -type f \
    -name "*Test.php" \
    -not -name 'AllTestsTest.php' \
    -not -name 'ToHumanTimeTest.php' \
    -not -name 'UploadTest.php' \
    -not -name 'RewardTest.php' \
    -not -name 'AuthTest.php' \
    -not -name 'CancelTest.php' \
)

for test in $tests; do
    echo $test | tee /tmp/test.log
    /bin/bash ${ROOT_PATH}/cake28/Console/cake l_test $test 2>&1 | tee /tmp/test.log
    grep -i -e 'FAILURE' -e 'ERROR' /tmp/test.log > /dev/null 2>&1
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
    if [ $# -eq 3 ]; then
        sh ${ROOT_PATH}/cake28/Test/CodingChecker/send_messages.sh \
            "cifailed" \
            ${CIRCLE_BRANCH} \
            ${CIRCLE_USERNAME} \
            ${CIRCLE_BUILD_URL} \
            "/tmp/test_failed.log"
    fi
    echo "FAILURES! detail：/tmp/test_failed.log"
fi

echo "All Failures: $failure."

exit ${result}
