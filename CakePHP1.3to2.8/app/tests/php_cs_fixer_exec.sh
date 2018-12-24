IFS=$'\n'; COMMIT_SCA_FILES=($(git diff --name-only --diff-filter=ACMRTUXB origin/master )); unset IFS
./app/vendors/composers/friendsofphp/php-cs-fixer/php-cs-fixer fix --config=app/.php_cs -v --path-mode=intersection "${COMMIT_SCA_FILES[@]}"
