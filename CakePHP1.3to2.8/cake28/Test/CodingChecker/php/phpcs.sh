#!/bin/bash

CIRCLE_BRANCH=${1}
CIRCLE_USERNAME=${2}
CIRCLE_BUILD_URL=${3}
CHATWORK_TOKEN=${4}

ROOT_PATH=`pwd`

if [ -f /tmp/phpcs.log ]; then
    rm -rf /tmp/phpcs.log
fi

if [ ! -f /tmp/diff.log ]; then
    exit 0
fi

echo "ソースコードを以下のコマンドで整形してください" > /tmp/phpcs.log

result=0
files=`cat /tmp/diff.log`
for file in ${files}; do
    if [[ "${file}" =~ php$ ]]; then
        msg=`php app/vendors/bin/phpcs --standard=cake28/Test/CodingCheker/php/phpcs/ruleset.xml ${file}`
        check=`echo ${msg} | grep '\[x\]'`
        if [ "${check}" ]; then
            # ファイル直接指定は拡張子確認は無視される
            echo "php app/vendors/bin/phpcbf --standard=cake28/Test/CodingCheker/php/phpcs/ruleset.xml ${file}" >> /tmp/phpcs.log
            echo "NG: ${file}"
            result=1
        else
            echo "OK: ${file}"
        fi
    fi
done

if [ ${result} = 1 ]; then
    echo "`cat /tmp/phpcs.log`"
    sh ${ROOT_PATH}/cake28/Test/CodingCheker/send_messages.sh \
        "cifailed" \
        ${CIRCLE_BRANCH} \
        ${CIRCLE_USERNAME} \
        ${CIRCLE_BUILD_URL} \
        ${CHATWORK_TOKEN} \
        "/tmp/phpcs.log"
fi

exit ${result}
