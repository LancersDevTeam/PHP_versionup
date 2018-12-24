#!/bin/bash

TYPE=${1}
CIRCLE_BRANCH=${2}
CIRCLE_USERNAME=${3}
CIRCLE_BUILD_URL=${4}
CHATWORK_TOKEN=${5}
FILE_PATH=${6}

if [ -z "${CIRCLE_USERNAME}" ]; then
    exit 0
fi

title=""
body=""
json=`cat developers.json`
to=`php cake28/Test/CodingChecker/getChatWorkTo.php ${CIRCLE_USERNAME} "${json}"`
result=0

case ${TYPE} in
    "cifailed")
        result=1
        title="CircleCI Failed"
        to=`echo ${to} | sed -e 's|picon|To|'`
        cat << _EOT_ > /tmp/msg.txt
[info][title]${title}[/title]${to} your branch: ${CIRCLE_BRANCH}
${CIRCLE_BUILD_URL}[code]`cat ${FILE_PATH}`[/code][preview id=XXXXXX ht=130][/info]
_EOT_
    ;;
    "cisuccessful")
        exit 0;
# @TODO: 成功時は通知しない
#        title="CircleCI Successful"
#        cat << _EOT_ > /tmp/msg.txt
#[info][title]${title}[/title]${to} your branch: ${CIRCLE_BRANCH}
#${CIRCLE_BUILD_URL}[preview id=XXXXXX ht=XXX][/info]
#_EOT_
    ;;
esac

body=`cat /tmp/msg.txt`
echo 'send chatwork message.'
curl -X POST -H "X-ChatWorkToken:${CHATWORK_TOKEN}" \
    -d "body=${body}" "https://api.chatwork.com/v2/rooms/XXXXXX/messages"
echo ""
rm -rf /tmp/msg.txt

exit ${result}
