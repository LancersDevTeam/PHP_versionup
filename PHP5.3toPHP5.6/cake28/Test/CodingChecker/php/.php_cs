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
    ->setRules(array(
        '@PSR2' => true,
        '@PHP56Migration' => true,
//        'cast_spaces' => array('space' => 'none'), // PHP CS Fixer 2.2.20 では設定不可
//        'combine_consecutive_issets' => true, // PHP CS Fixer 2.2.20 では設定不可
        'combine_consecutive_unsets' => true,
//        'ereg_to_preg' => true, // PHP 5.6対応後に設定
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_whitespace_before_comma_in_array' => true,
        'whitespace_after_comma_in_array' => true,
    ))
    ->setUsingCache(false)
    ->setFinder($finder);
