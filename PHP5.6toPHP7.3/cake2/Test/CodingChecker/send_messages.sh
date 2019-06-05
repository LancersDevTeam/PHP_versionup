#!/bin/bash

TYPE=${1}
CIRCLE_BRANCH=${2}
CIRCLE_USERNAME=${3}
CIRCLE_BUILD_URL=${4}
FILE_PATH=${5}
SLACK_CHANNEL="#dev_coding_check"
SLACK_WEBHOOK="https://hooks.slack.com/services/xxxxxxxxxxxxxxx"
SLACK_BOTICON=":xxxxxx:"

echo "----- send_message.sh -----"
echo "TYPE: ${TYPE}"
echo "CIRCLE_BRANCH: ${CIRCLE_BRANCH}"
echo "CIRCLE_USERNAME: ${CIRCLE_USERNAME}"
echo "CIRCLE_BUILD_URL: ${CIRCLE_BUILD_URL}"
echo "FILE_PATH: ${FILE_PATH}"

if [ -z "${CIRCLE_USERNAME}" ]; then
    echo "USERNAME is empty."
    exit 0
fi

json=`cat developers.json`
to=`php cake28/Test/CodingChecker/getSlackTo.php ${CIRCLE_USERNAME} "${json}"`
result=0
echo "SLACK_USERNAME: ${to}"

case ${TYPE} in
    "cifailed")
        result=1
        cat << _EOT_ > /tmp/msg.txt
{
  "icon_emoji": "${SLACK_BOTICON}",
  "channel": "${SLACK_CHANNEL}",
  "text": "${to} *CircleCI Failed*: \`${CIRCLE_BRANCH}\`\n${CIRCLE_BUILD_URL}\n\`\`\`\n`cat ${FILE_PATH}`\n\`\`\`"
}
_EOT_
    ;;
    # 成功時は通知しない
    "cisuccessful")
        exit 0;
    ;;
esac

body=`cat /tmp/msg.txt`
echo 'send slack message.'
curl -H "Content-Type: application/json" -d "${body}" "${SLACK_WEBHOOK}"
echo ""

exit ${result}
