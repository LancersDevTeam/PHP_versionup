#!/bin/bash

CIRCLE_BRANCH=${1}
CIRCLE_USERNAME=${2}
CIRCLE_BUILD_URL=${3}

ROOT_PATH=$(pwd)

rm -f /tmp/ngword.log

if [ ! -f /tmp/diff.log ]; then
    exit 0
fi

echo "NGワードを検出しました。" > /tmp/ngword.log

result=0
files=$(cat /tmp/diff.log)
for file in ${files}; do
    if [ -f ${file} ] && [[ "${file}" =~ (php|ctp)$ ]]; then

        if [[ "${file}" =~ LancersHelper.php ]] ; then
            continue;
        fi

        msg=$(egrep -n "エスクロー|仮入金|仮押さえ" ${file} | sed -e "s/\* //")
        if [ "${msg}" ] ; then
            echo ${file}:${msg} >> /tmp/ngword.log
            echo 1 > /tmp/failed
            result=1
        fi
    fi
done

if [ ${result} = 1 ]; then
    echo "$(cat /tmp/ngword.log)"
    sh ${ROOT_PATH}/cake28/Test/CodingChecker/send_messages.sh \
        "cifailed" \
        ${CIRCLE_BRANCH} \
        ${CIRCLE_USERNAME} \
        ${CIRCLE_BUILD_URL} \
        "/tmp/ngword.log"
fi

exit ${result}
