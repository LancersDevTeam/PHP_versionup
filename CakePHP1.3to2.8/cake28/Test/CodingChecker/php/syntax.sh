#!/bin/bash

CIRCLE_BRANCH=${1}
CIRCLE_USERNAME=${2}
CIRCLE_BUILD_URL=${3}
CHATWORK_TOKEN=${4}

ROOT_PATH=`pwd`

if [ -f /tmp/syntax.log ]; then
    rm -rf /tmp/syntax.log
fi

if [ ! -f /tmp/diff.log ]; then
    exit 0
fi

echo "シンタックスエラーを検知しました。" > /tmp/syntax.log

result=0
files=`cat /tmp/diff.log`
for file in ${files}; do
    if [ -f ${file} ] && [[ "${file}" =~ (php|ctp)$ ]]; then
        msg=`php -l "${file}"`
        check=`echo ${msg} | cut -c 1-25 `
        if [ "${check}" != 'No syntax errors detected' ] ; then
            echo ${msg} >> /tmp/syntax.log
            echo 1 > /tmp/failed
            result=1
        fi
    fi
done

if [ ${result} = 1 ]; then
    echo "`cat /tmp/syntax.log`"
    sh ${ROOT_PATH}/cake28/Test/CodingCheker/send_messages.sh \
        "cifailed" \
        ${CIRCLE_BRANCH} \
        ${CIRCLE_USERNAME} \
        ${CIRCLE_BUILD_URL} \
        ${CHATWORK_TOKEN} \
        "/tmp/syntax.log"
fi

exit ${result}
