<?php
include_once 'vendor/autoload.php';

use \Symfony\Component\Finder\Finder;

$helpers = array();
$list = array(
    'cake28/View/Helper'
);
foreach ($list as $in) {
    $helpers = array_merge($helpers, getHelperNames($in));
}
print implode(' ', $helpers);

function getHelperNames($in)
{
    $names = array();
    $finder = new Finder();
    $finder->in($in)->files()->name('*.php');
    foreach ($finder as $path) {
        $tmp = explode('/', $path);
        $file = array_pop($tmp);
        list($name, $ext) = explode('.', $file);
        $tmp = explode('_', $name);
        $helper = array_shift($tmp);
        foreach ($tmp as $value) {
            $helper .= ucfirst($value);
        }
        $names[] = $helper;
    }
    return $names;
}
