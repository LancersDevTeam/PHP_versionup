<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Jesus M. Castagnetto <jmcastagnetto@php.net>                |
// +----------------------------------------------------------------------+
//
// $Id$
//

/**
 * @package	Math_Stats
 */

require_once "Math/Stats.php";

// making some cummulative data sets

$data = array("3"=>4, "2.333"=>5, "1.22"=>6, "0.5"=>3, "0.9"=>2, "2.4"=>7);
$dnulls = array("3"=>4, "caca"=>2, "bar is not foo"=>6, "0.5"=>3, "0.9"=>2, "2.4"=>7);

// instantiate a Math_Stats object
$s = new Math_Stats();
$s->setData($data, STATS_DATA_CUMMULATIVE);

echo "*** Original cummulative data set\n";
print_r($data);
// let's print some simple statistics
echo "Simple stats from cummulative data, note the count\n";
print_r($s->calcBasic());

// now, lets generate an error by using the $dnulls array
echo "\n*** Another cummulative data set\n";
print_r($dnulls);
echo "Generating an error by using data with nulls\n";
print_r($s->setData($dnulls, STATS_DATA_CUMMULATIVE));

// let's ignore nulls
echo "Ignoring the nulls and trying again\n";
$s->setNullOption(STATS_IGNORE_NULL);
$s->setData($dnulls, STATS_DATA_CUMMULATIVE);
print_r($s->calcBasic());

// let's assume null == zero
echo "Assuming that nulls are zero\n";
$s->setNullOption(STATS_USE_NULL_AS_ZERO);
$s->setData($dnulls, STATS_DATA_CUMMULATIVE);
print_r($s->calcFull());
?>
