#!/bin/bash

if [ ! -f /tmp/diff.log ]; then
    exit 0
fi

files=`cat /tmp/diff.log`

for file in ${files}; do
    if [ -f ${file} ]; then
        php app/vendors/bin/phpmd ${file} text cake28/Test/CodingCheker/php/phpmd/ruleset.xml
    fi
done

exit 0
