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

// making some data sets
$data = array (2,2.3,4.5,2,2,3.2,5.3,3,4,5,1,6);
$dnulls = array (1.1650,null, "foo",0.6268, 0.6268, 0.0751, 0.3516, -0.6965);

// instantiating a Math_Stats object
$s = new Math_Stats();

echo "*** Original data set\n";
print_r($data);
$s->setData($data);
echo "Basic statistics\n";
print_r($s->calcBasic());

echo "\n*** A data set with nulls\n";
print_r($dnulls);

echo "Let's generate an error\n";
print_r($s->setData($dnulls));
echo "Ignoring nulls and trying again\n";
$s->setNullOption(STATS_IGNORE_NULL);
$s->setData($dnulls);
echo "---> data after ignoring (removing) nulls\n";
print_r($s->getData());
echo "---> stats\n";
print_r($s->calcBasic());

echo "Assuming nulls are zeros and doing a full stats calculation\n";
$s->setNullOption(STATS_USE_NULL_AS_ZERO);
$s->setData($dnulls);
echo "---> data after setting nulls to zero\n";
print_r($s->getData());
echo "---> stats\n";
print_r($s->calcFull());
?>
