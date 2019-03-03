#!/bin/bash

#====================================
# コミットコードの複雑度を警告する
#====================================
# コミットされるファイルのうち、.phpで終わるものをチェック
#
# [やっていること]
# NPathが200以上の関数の抽出とechoでの出力
# 少なくなったら他の条件も出力させる
#

if [ ! -f /tmp/diff.log ]; then
    exit 0
fi

files=`cat /tmp/diff.log`
for file in ${files}; do
    if [ -f ${file} ]; then
        # phpmdの結果を表示する
        phpmdtext=`php bin/phpmd ${file} text codesize | grep NPath | cut -f 2 | cut -d ' ' -f 3,9`
        if test "${phpmdtext}"; then
            echo "\n\n${file}:\n${phpmdtext}"
        fi
    fi
done

exit 0
