#!/bin/sh

CIRCLE_BRANCH=${1}
CIRCLE_USERNAME=${2}
CIRCLE_BUILD_URL=${3}
CHATWORK_TOKEN=${4}

ROOT_PATH=`pwd`

if [ ! -f /tmp/diff.log ]; then
    exit 0
fi
if [ -f /tmp/helpers.log ]; then
    echo '' > /tmp/helpers.log
fi

result=0
files=`cat /tmp/diff.log`

helpers=(`php ${ROOT_PATH}/cake28/Test/CodingCheker/php/getHelperNames.php`)

for file in ${files}; do
    if [ -f ${file} ] && [[ "${file}" =~ ctp$ ]]; then
        for helper in ${helpers[@]}; do
            pattern="\$${helper}\->"
            text=`echo ${file} | xargs grep ${pattern} | awk -F: '{print $1}' | sort | uniq`
            if test "${text}"; then
                echo "${file}" >> /tmp/helpers.log
                result=1
            fi
        done
    fi
done


if [ "${result}" = 1 ]; then
    text=`cat /tmp/helpers.log | sort | uniq`
    cat << EOS > /tmp/helpers.log
Helperの参照チェック
${text}

上記ファイルテンプレートファイルでHelperの直接指定(\$formなど)を検知しました
Viewオブジェクト参照型(\$this->Formなど)に置き換えてください
EOS

    sh ${ROOT_PATH}/cake28/Test/CodingCheker/send_messages.sh \
        "cifailed" \
        ${CIRCLE_BRANCH} \
        ${CIRCLE_USERNAME} \
        ${CIRCLE_BUILD_URL} \
        ${CHATWORK_TOKEN} \
        "/tmp/helpers.log"
fi

exit ${result}
