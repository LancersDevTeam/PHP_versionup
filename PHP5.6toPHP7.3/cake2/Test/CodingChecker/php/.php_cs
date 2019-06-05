<?php
$finder = PhpCsFixer\Finder::create()
    ->notName('.php_cs')
    ->notName('*.html')
    ->notName('*.md')
    ->notName('*.rb')
    ->notName('*.sh')
    ->notName('*.xml')
    ->exclude('tmp')
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@PHP73Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'align_double_arrow' => true,
            'align_equals' => true,
        ],
        // bracesが原因で、インデントが崩れてしまう。各項目の全パターンを試したが、エラーが直らなかった
        // bracesを空にすれば回避できるのでワークアラウンド的に対応
        // #19218 #19273
        'braces' => [],
        'cast_spaces' => ['space' => 'none'],
        'concat_space' => ['spacing' => 'one'],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'heredoc_to_nowdoc' => true,
        'include' => true,
        'increment_style' => ['style' => 'post'],
        'linebreak_after_opening_tag' => true,
        'lowercase_cast' => true,
        'lowercase_constants' => true,
        'lowercase_keywords' => true,
        'new_with_braces' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_leading_import_slash' => true,
        'no_superfluous_elseif' => false,
        'no_useless_else' => false,
        'no_whitespace_before_comma_in_array' => true,
        'phpdoc_summary' => false,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setUsingCache(false)
    ->setFinder($finder);
