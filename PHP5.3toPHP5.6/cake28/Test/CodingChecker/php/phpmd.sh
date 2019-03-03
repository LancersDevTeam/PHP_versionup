#!/bin/bash

CIRCLE_BRANCH=${1}
CIRCLE_USERNAME=${2}
CIRCLE_BUILD_URL=${3}

ROOT_PATH=$(pwd)

if [ -f /tmp/phpmd.log ]; then
    rm -rf /tmp/phpmd.log
fi

if [ ! -f /tmp/diff.log ]; then
    exit 0
fi

files=$(cat /tmp/diff.log)

result=0
for file in ${files}; do
    if [ -f ${file} ]; then
        php bin/phpmd ${file} text cake28/Test/CodingChecker/php/phpmd/ruleset.xml >> /tmp/phpmd.log
    fi
done
for file in ${files}; do
    if [ -f ${file} ] && [[ ${file} =~ (php|ctp)$ ]]; then
        msg=$(php bin/phpmd ${file} text cake28/Test/CodingChecker/php/phpmd/ruleset.xml)
        check=$(echo ${msg} | cut -c 1-25)
        if [[ ${check} != 'No phpmd errors detected' ]] ; then
            echo "${msg}" >> /tmp/phpmd.log
            result=1
        fi
    fi
done

cat /tmp/phpmd.log

if [ ${result} = 1 ]; then
    echo $(cat /tmp/phpmd.log)
    sh ${ROOT_PATH}/cake28/Test/CodingChecker/send_messages.sh \
        "cifailed" \
        ${CIRCLE_BRANCH} \
        ${CIRCLE_USERNAME} \
        ${CIRCLE_BUILD_URL} \
        "/tmp/phpmd.log"
fi

exit 0
