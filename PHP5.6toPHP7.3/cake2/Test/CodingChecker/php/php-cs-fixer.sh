#!/bin/bash

CIRCLE_BRANCH=${1}
CIRCLE_USERNAME=${2}
CIRCLE_BUILD_URL=${3}

ROOT_PATH=$(pwd)

rm -f /tmp/php-cs-fixer.log

if [ ! -f /tmp/diff.log ]; then
    exit 0
fi

echo "ソースコードを以下のコマンドで整形してください" > /tmp/php-cs-fixer.log

result=0
files=$(cat /tmp/diff.log)
for file in ${files}; do
    if [[ "${file}" =~ /Vendor/ ]]; then
        continue
    fi
    
    if [[ "${file}" =~ ctp$ ]] || [[ "${file}" =~ php$ ]]; then
        msg="$(./bin/php-cs-fixer --dry-run --allow-risky=yes -vvv fix ${file} --config='cake28/Test/CodingChecker/php/.php_cs' 2>&1)"
        check=$(echo "${msg}" | grep "^[EFI?]$")
        if [[ "${check}" =~ [EFI?] ]]; then
            echo "./bin/php-cs-fixer -vvv fix ${file} --config='cake28/Test/CodingChecker/php/.php_cs'" >> /tmp/php-cs-fixer.log
            echo "NG: ${file}"
            result=1
        else
            echo "OK: ${file}"
        fi
    fi
done

if [ ${result} = 1 ]; then
    echo "$(cat /tmp/php-cs-fixer.log)"
    sh ${ROOT_PATH}/cake28/Test/CodingChecker/send_messages.sh \
        "cifailed" \
        ${CIRCLE_BRANCH} \
        ${CIRCLE_USERNAME} \
        ${CIRCLE_BUILD_URL} \
        "/tmp/php-cs-fixer.log"
fi

exit ${result}
