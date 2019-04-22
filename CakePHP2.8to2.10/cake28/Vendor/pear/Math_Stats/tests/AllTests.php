<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Math_Stats_AllTests::main');
}

require_once 'Math_StatsTest.php';

class Math_Stats_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PEAR - Math_Stats');

        $suite->addTestSuite('Math_StatsTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Math_Stats_AllTests::main') {
    Math_Stats_AllTests::main();
}
