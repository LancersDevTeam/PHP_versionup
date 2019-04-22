<?php
//
// +----------------------------------------------------------------------+
// | PHP version 4.0                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2001 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
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
 * Unit test for the Math_Stats class
 *
 * @package Math_Stats
 */

define('__PRECISION', 12);
define('__DELTA', pow(10, -1 * (__PRECISION - 4)));

// make sure we have the correct number of decimal figures
ini_set('precision', __PRECISION);

/**
 * Unit test class Math_Stats
 *
 */
class Math_StatsTest extends PHPUnit_Framework_TestCase
{
/*{{{*/

    public $s1;
    public $s2a;
    public $s2b;
    public $s3;
    public $s4a;
    public $s4b;

    // simple data set
    public $data1 = array(2, 2.3, 4.5, 2, 2, 3.2, 5.3, 3, 4, 5, 1, 6);
    // data set with nulls
    public $data2 = array(1.1650, null, "foo", 0.6268, 0.6268, 0.0751, 0.3516, -0.6965);
    // cummulative data set
    public $data3 = array("3" => 4, "2.333" => 5, "1.22" => 6, "0.5" => 3, "0.9" => 2, "2.4" => 7);
    // cummulative data set with nulls
    public $data4 = array("3" => 4, "plink" => 2, "bar is not foo" => 6, "0.5" => 3, "0.9" => 2, "2.4" => 7);

    public function Math_Stats_UnitTest($name)
    {
/*{{{*/
        $this->PHPUnit_TestCase($name);
    }

/*}}}*/

    public function setUp()
    {
/*{{{*/
        // simple data sets
        $this->s1 = new \PEAR\Math\Stats(\PEAR\Math\Stats::STATS_REJECT_NULL);
        $this->s1->setData($this->data1);
        $this->s2a = new \PEAR\Math\Stats(\PEAR\Math\Stats::STATS_IGNORE_NULL);
        $this->s2a->setData($this->data2);
        $this->s2b = new \PEAR\Math\Stats(\PEAR\Math\Stats::STATS_USE_NULL_AS_ZERO);
        $this->s2b->setData($this->data2);
        // cummulative data sets
        $this->s3 = new \PEAR\Math\Stats(\PEAR\Math\Stats::STATS_REJECT_NULL);
        $this->s3->setData($this->data3, \PEAR\Math\Stats::STATS_DATA_CUMMULATIVE);
        $this->s4a = new \PEAR\Math\Stats(\PEAR\Math\Stats::STATS_IGNORE_NULL);
        $this->s4a->setData($this->data4, \PEAR\Math\Stats::STATS_DATA_CUMMULATIVE);
        $this->s4b = new \PEAR\Math\Stats(\PEAR\Math\Stats::STATS_USE_NULL_AS_ZERO);
        $this->s4b->setData($this->data4, \PEAR\Math\Stats::STATS_DATA_CUMMULATIVE);
    }

/*}}}*/

    public function tearDown()
    {
/*{{{*/
        unset($this->s1);
        unset($this->s2a);
        unset($this->s2b);
        unset($this->s3);
        unset($this->s4a);
        unset($this->s4b);
    }

/*}}}*/

    public function testGetData()
    {
/*{{{*/
        $this->assertEquals($GLOBALS['testGetData_out1'], $this->formatArray($this->s1->getData()));
        $this->assertEquals($GLOBALS['testGetData_out2'], $this->formatArray($this->s2a->getData()));
        $this->assertEquals($GLOBALS['testGetData_out3'], $this->formatArray($this->s2b->getData()));
        $this->assertEquals($GLOBALS['testGetData_out4'], $this->formatArray($this->s3->getData()));
        $this->assertEquals($GLOBALS['testGetData_out5'], $this->formatArray($this->s4a->getData()));
        $this->assertEquals($GLOBALS['testGetData_out6'], $this->formatArray($this->s4a->getData(true)));
        $this->assertEquals($GLOBALS['testGetData_out7'], $this->formatArray($this->s4b->getData()));
    }

/*}}}*/

    public function testCalcBasic()
    {
/*{{{*/
        $this->assertEquals($GLOBALS['testCalcBasic_out1'],
            $this->formatArray($this->s1->calcBasic(false)));
        $this->assertEquals($GLOBALS['testCalcBasic_out2'],
            $this->formatArray($this->s2a->calcBasic(false)));
        $this->assertEquals($GLOBALS['testCalcBasic_out3'],
            $this->formatArray($this->s2b->calcBasic(false)));
        $this->assertEquals($GLOBALS['testCalcBasic_out4'],
            $this->formatArray($this->s3->calcBasic(false)));
        $this->assertEquals($GLOBALS['testCalcBasic_out5'],
            $this->formatArray($this->s4a->calcBasic(false)));
        $this->assertEquals($GLOBALS['testCalcBasic_out6'],
            $this->formatArray($this->s4b->calcBasic(false)));
    }

/*}}}*/

    public function testCalcFull()
    {
/*{{{*/
        $this->assertEquals($GLOBALS['testCalcFull_out1'],
            $this->formatArray($this->s1->calcFull(false)));

        $this->setExpectedException('PEAR_Exception', 'The product of the data set is negative, geometric mean undefined.');
        $this->formatArray($this->s2a->calcFull(false));

        $this->assertEquals($GLOBALS['testCalcFull_out3'],
            $this->formatArray($this->s2b->calcFull(false)));

        $this->assertEquals($GLOBALS['testCalcFull_out4'],
            $this->formatArray($this->s3->calcFull(false)));

        $this->assertEquals($GLOBALS['testCalcFull_out5'],
            $this->formatArray($this->s4a->calcFull(false)));

        $this->assertEquals($GLOBALS['testCalcFull_out6'],
            $this->formatArray($this->s4b->calcFull(false)));
    }

/*}}}*/

    public function testMin()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(1),
            $this->formatNumber($this->s1->min()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.6965),
            $this->formatNumber($this->s2a->min()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.6965),
            $this->formatNumber($this->s2b->min()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.5),
            $this->formatNumber($this->s3->min()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.5),
            $this->formatNumber($this->s4a->min()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0),
            $this->formatNumber($this->s4b->min()),
            '', __DELTA);
    }

/*}}}*/

    public function testMax()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(6),
            $this->formatNumber($this->s1->max()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.165),
            $this->formatNumber($this->s2a->max()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.165),
            $this->formatNumber($this->s2b->max()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(3),
            $this->formatNumber($this->s3->max()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(3),
            $this->formatNumber($this->s4a->max()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(3),
            $this->formatNumber($this->s4b->max()),
            '', __DELTA);
    }

/*}}}*/

    public function testSum()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(40.3),
            $this->formatNumber($this->s1->sum()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.1488),
            $this->formatNumber($this->s2a->sum()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.1488),
            $this->formatNumber($this->s2b->sum()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(51.085),
            $this->formatNumber($this->s3->sum()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(32.1),
            $this->formatNumber($this->s4a->sum()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(32.1),
            $this->formatNumber($this->s4b->sum()),
            '', __DELTA);
    }

/*}}}*/

    public function testSum2()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(162.87),
            $this->formatNumber($this->s1->sum2()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.7573563),
            $this->formatNumber($this->s2a->sum2()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.7573563),
            $this->formatNumber($this->s2b->sum2()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(114.834845),
            $this->formatNumber($this->s3->sum2()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(78.69),
            $this->formatNumber($this->s4a->sum2()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(78.69),
            $this->formatNumber($this->s4b->sum2()),
            '', __DELTA);
    }

/*}}}*/

    public function testSumN()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(741.937),
            $this->formatNumber($this->s1->sumN(3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.77968786139),
            $this->formatNumber($this->s2a->sumN(3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.40141456571),
            $this->formatNumber($this->s2b->sumN(4)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(280.987388185),
            $this->formatNumber($this->s3->sumN(3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(206.601),
            $this->formatNumber($this->s4a->sumN(3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(557.7429),
            $this->formatNumber($this->s4b->sumN(4)),
            '', __DELTA);
    }

/*}}}*/

    public function testProduct()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(505543.68),
            $this->formatNumber($this->s1->product()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.00841770739124),
            $this->formatNumber($this->s2a->product()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0),
            $this->formatNumber($this->s2b->product()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(46478.287296),
            $this->formatNumber($this->s3->product()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(544.32),
            $this->formatNumber($this->s4a->product()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0),
            $this->formatNumber($this->s4b->product()),
            '', __DELTA);
    }

/*}}}*/

    public function testProductN()
    {
/*{{{*/
        $this->assertEquals('255574412388',
            (string) $this->s1->productN(2),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(7.08577977246E-05),
            $this->formatNumber($this->s2a->productN(2)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0),
            $this->formatNumber($this->s2b->productN(3)),
            '', __DELTA);
        $this->assertEquals('428617.299597',
            (string) $this->s3->productN(2),
            '', __DELTA);
        $this->assertEquals('5714.053632',
            (string) $this->s4a->productN(3),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0),
            $this->formatNumber($this->s4b->productN(2)),
            '', __DELTA);
    }

/*}}}*/

    public function testCount()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(12),
            $this->formatNumber($this->s1->count()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(6),
            $this->formatNumber($this->s2a->count()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(8),
            $this->formatNumber($this->s2b->count()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(27),
            $this->formatNumber($this->s3->count()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(16),
            $this->formatNumber($this->s4a->count()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(24),
            $this->formatNumber($this->s4b->count()),
            '', __DELTA);
    }

/*}}}*/

    public function testMean()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(3.35833333333),
            $this->formatNumber($this->s1->mean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.358133333333),
            $this->formatNumber($this->s2a->mean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.2686),
            $this->formatNumber($this->s2b->mean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.89203703704),
            $this->formatNumber($this->s3->mean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.00625),
            $this->formatNumber($this->s4a->mean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.3375),
            $this->formatNumber($this->s4b->mean()),
            '', __DELTA);
    }

/*}}}*/

    public function testRange()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(5),
            $this->formatNumber($this->s1->range()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.8615),
            $this->formatNumber($this->s2a->range()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.8615),
            $this->formatNumber($this->s2b->range()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.5),
            $this->formatNumber($this->s3->range()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.5),
            $this->formatNumber($this->s4a->range()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(3),
            $this->formatNumber($this->s4b->range()),
            '', __DELTA);
    }

/*}}}*/

    public function testVariance()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(2.50265151515),
            $this->formatNumber($this->s1->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.397559878667),
            $this->formatNumber($this->s2a->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.311455517143),
            $this->formatNumber($this->s2b->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.699235883191),
            $this->formatNumber($this->s3->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.952625),
            $this->formatNumber($this->s4a->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.55461956522),
            $this->formatNumber($this->s4b->variance()),
            '', __DELTA);
    }

/*}}}*/

    public function testStDev()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(1.58197709059),
            $this->formatNumber($this->s1->stDev()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.630523495729),
            $this->formatNumber($this->s2a->stDev()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.558081998583),
            $this->formatNumber($this->s2b->stDev()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.836203254712),
            $this->formatNumber($this->s3->stDev()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.976025102136),
            $this->formatNumber($this->s4a->stDev()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.24684384155),
            $this->formatNumber($this->s4b->stDev()),
            '', __DELTA);
    }

/*}}}*/

    public function testVarianceWithMean()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(2.50454545455),
            $this->formatNumber($this->s1->varianceWithMean(3.4)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.39966326),
            $this->formatNumber($this->s2a->varianceWithMean(0.4)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.312582328571),
            $this->formatNumber($this->s2b->varianceWithMean(0.3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.699301730769),
            $this->formatNumber($this->s3->varianceWithMean(1.9)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.952666666667),
            $this->formatNumber($this->s4a->varianceWithMean(2.0)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.55869565217),
            $this->formatNumber($this->s4b->varianceWithMean(1.4)),
            '', __DELTA);
    }

/*}}}*/

    public function testStDevWithMean()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(1.58257557625),
            $this->formatNumber($this->s1->stDevWithMean(3.4)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.632189259637),
            $this->formatNumber($this->s2a->stDevWithMean(0.4)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.559090626439),
            $this->formatNumber($this->s2b->stDevWithMean(0.3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.836242626735),
            $this->formatNumber($this->s3->stDevWithMean(1.9)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.976046446982),
            $this->formatNumber($this->s4a->stDevWithMean(2.0)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.24847733346),
            $this->formatNumber($this->s4b->stDevWithMean(1.4)),
            '', __DELTA);
    }

/*}}}*/

    public function testAbsDev()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(2.50265151515),
            $this->formatNumber($this->s1->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.39755987867),
            $this->formatNumber($this->s2a->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.31145551714),
            $this->formatNumber($this->s2b->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.69923588319),
            $this->formatNumber($this->s3->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.952625),
            $this->formatNumber($this->s4a->variance()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.55461956522),
            $this->formatNumber($this->s4b->variance()),
            '', __DELTA);
    }

/*}}}*/

    public function testAbsDevWithMean()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(1.34166666667),
            $this->formatNumber($this->s1->absDevWithMean(3.4)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.448066666667),
            $this->formatNumber($this->s2a->absDevWithMean(0.4)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.42395),
            $this->formatNumber($this->s2b->absDevWithMean(0.3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.753518518519),
            $this->formatNumber($this->s3->absDevWithMean(1.9)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.84375),
            $this->formatNumber($this->s4a->absDevWithMean(2.0)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.17916666667),
            $this->formatNumber($this->s4b->absDevWithMean(1.4)),
            '', __DELTA);
    }

/*}}}*/

    public function testSkewness()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(0.211767803758),
            $this->formatNumber($this->s1->skewness()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.419944986921),
            $this->formatNumber($this->s2a->skewness()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.0950243805522),
            $this->formatNumber($this->s2b->skewness()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.321743512221),
            $this->formatNumber($this->s3->skewness()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.578555878286),
            $this->formatNumber($this->s4a->skewness()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.122636601767),
            $this->formatNumber($this->s4b->skewness()),
            '', __DELTA);
    }

/*}}}*/

    public function testKurtosis()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(-1.47708896609),
            $this->formatNumber($this->s1->kurtosis()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-1.23078655976),
            $this->formatNumber($this->s2a->kurtosis()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.992375284912),
            $this->formatNumber($this->s2b->kurtosis()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-1.4009978017),
            $this->formatNumber($this->s3->kurtosis()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-1.4499405466),
            $this->formatNumber($this->s4a->kurtosis()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-1.8513180639),
            $this->formatNumber($this->s4b->kurtosis()),
            '', __DELTA);
    }

/*}}}*/

    public function testMedian()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(3.1),
            $this->formatNumber($this->s1->median()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.4892),
            $this->formatNumber($this->s2a->median()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.21335),
            $this->formatNumber($this->s2b->median()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.333),
            $this->formatNumber($this->s3->median()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.4),
            $this->formatNumber($this->s4a->median()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.9),
            $this->formatNumber($this->s4b->median()),
            '', __DELTA);
    }

/*}}}*/

    public function testMode()
    {
/*{{{*/
        $this->assertEquals($GLOBALS['testMode_out1'], $this->formatArray($this->s1->mode()));
        $this->assertEquals($GLOBALS['testMode_out2'], $this->formatArray($this->s2a->mode()));
        $this->assertEquals($GLOBALS['testMode_out3'], $this->formatArray($this->s2b->mode()));
        $this->assertEquals($GLOBALS['testMode_out4'], $this->formatArray($this->s3->mode()));
        $this->assertEquals($GLOBALS['testMode_out5'], $this->formatArray($this->s4a->mode()));
        $this->assertEquals($GLOBALS['testMode_out6'], $this->formatArray($this->s4b->mode()));
    }

/*}}}*/

    public function testMidrange()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(3.5),
            $this->formatNumber($this->s1->midrange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.23425),
            $this->formatNumber($this->s2a->midrange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.23425),
            $this->formatNumber($this->s2b->midrange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.75),
            $this->formatNumber($this->s3->midrange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.75),
            $this->formatNumber($this->s4a->midrange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.5),
            $this->formatNumber($this->s4b->midrange()),
            '', __DELTA);
    }

/*}}}*/

    public function testGeometricMean()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(2.98753652642),
            $this->formatNumber($this->s1->geometricMean()),
            '', __DELTA);
        $this->setExpectedException('PEAR_Exception', 'The product of the data set is negative, geometric mean undefined.');
        $this->s2a->geometricMean();

        $this->assertEquals($this->formatNumber(0),
            $this->formatNumber($this->s2b->geometricMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.48888486575),
            $this->formatNumber($this->s3->geometricMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.48248699844),
            $this->formatNumber($this->s4a->geometricMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0),
            $this->formatNumber($this->s4b->geometricMean()),
            '', __DELTA);
    }

/*}}}*/

    public function testHarmonicMean()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(2.98753652642),
            $this->formatNumber($this->s1->geometricMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.319605399284),
            $this->formatNumber($this->s2a->harmonicMean()),
            '', __DELTA);

        $this->setExpectedException('PEAR_Exception', 'cannot calculate a harmonic mean with data values of zero.');
        $this->s2b->harmonicMean();

        $this->assertEquals($this->formatNumber(1.38224654591),
            $this->formatNumber($this->s3->harmonicMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.28285077951),
            $this->formatNumber($this->s4a->harmonicMean()),
            '', __DELTA);

        $this->setExpectedException('PEAR_Exception', 'cannot calculate a harmonic mean with data values of zero.');
        $this->s4b->harmonicMean();
    }

/*}}}*/

    public function testSampleCentralMoment()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(2.29409722222),
            $this->formatNumber($this->s1->sampleCentralMoment(2)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.331299898889),
            $this->formatNumber($this->s2a->sampleCentralMoment(2)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.0165169209322),
            $this->formatNumber($this->s2b->sampleCentralMoment(3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.188124500214),
            $this->formatNumber($this->s3->sampleCentralMoment(3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.8930859375),
            $this->formatNumber($this->s4a->sampleCentralMoment(2)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.48984375),
            $this->formatNumber($this->s4b->sampleCentralMoment(2)),
            '', __DELTA);
    }

/*}}}*/

    public function testSampleRawMoment()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(13.5725),
            $this->formatNumber($this->s1->sampleRawMoment(2)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.459559383333),
            $this->formatNumber($this->s2a->sampleRawMoment(2)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.222460982673),
            $this->formatNumber($this->s2b->sampleRawMoment(3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(10.4069403031),
            $this->formatNumber($this->s3->sampleRawMoment(3)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(4.918125),
            $this->formatNumber($this->s4a->sampleRawMoment(2)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(3.27875),
            $this->formatNumber($this->s4b->sampleRawMoment(2)),
            '', __DELTA);
    }

/*}}}*/

    public function testCoeffOfVariation()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(0.471060175858),
            $this->formatNumber($this->s1->coeffOfVariation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.76058310423),
            $this->formatNumber($this->s2a->coeffOfVariation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.07774385176),
            $this->formatNumber($this->s2b->coeffOfVariation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.441959241993),
            $this->formatNumber($this->s3->coeffOfVariation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.486492262747),
            $this->formatNumber($this->s4a->coeffOfVariation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.932219694619),
            $this->formatNumber($this->s4b->coeffOfVariation()),
            '', __DELTA);
    }

/*}}}*/

    public function testStdErrorOfMean()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(0.456677449552),
            $this->formatNumber($this->s1->stdErrorOfMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.257410139229),
            $this->formatNumber($this->s2a->stdErrorOfMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.197311782828),
            $this->formatNumber($this->s2b->stdErrorOfMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.160927391402),
            $this->formatNumber($this->s3->stdErrorOfMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.244006275534),
            $this->formatNumber($this->s4a->stdErrorOfMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.254510933395),
            $this->formatNumber($this->s4b->stdErrorOfMean()),
            '', __DELTA);
    }

/*}}}*/

    public function testFrequency()
    {
/*{{{*/
        $this->assertEquals($GLOBALS['testFrequency_out1'], $this->formatArray($this->s1->frequency()));
        $this->assertEquals($GLOBALS['testFrequency_out2'], $this->formatArray($this->s2a->frequency()));
        $this->assertEquals($GLOBALS['testFrequency_out3'], $this->formatArray($this->s2b->frequency()));
        $this->assertEquals($GLOBALS['testFrequency_out4'], $this->formatArray($this->s3->frequency()));
        $this->assertEquals($GLOBALS['testFrequency_out5'], $this->formatArray($this->s4a->frequency()));
        $this->assertEquals($GLOBALS['testFrequency_out6'], $this->formatArray($this->s4b->frequency()));
    }

/*}}}*/

    public function testQuartiles()
    {
/*{{{*/
        $this->assertEquals($GLOBALS['testQuartiles_out1'], $this->formatArray($this->s1->quartiles()));
        $this->assertEquals($GLOBALS['testQuartiles_out2'], $this->formatArray($this->s2a->quartiles()));
        $this->assertEquals($GLOBALS['testQuartiles_out3'], $this->formatArray($this->s2b->quartiles()));
        $this->assertEquals($GLOBALS['testQuartiles_out4'], $this->formatArray($this->s3->quartiles()));
        $this->assertEquals($GLOBALS['testQuartiles_out5'], $this->formatArray($this->s4a->quartiles()));
        $this->assertEquals($GLOBALS['testQuartiles_out6'], $this->formatArray($this->s4b->quartiles()));
    }

/*}}}*/

    public function testInterQuartileMean()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(2.875),
            $this->formatNumber($this->s1->interquartileMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.420075),
            $this->formatNumber($this->s2a->interquartileMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.28005),
            $this->formatNumber($this->s2b->interquartileMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.98805555556),
            $this->formatNumber($this->s3->interquartileMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.06666666667),
            $this->formatNumber($this->s4a->interquartileMean()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.005),
            $this->formatNumber($this->s4b->interquartileMean()),
            '', __DELTA);
    }

/*}}}*/

    public function testInterquartileRange()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(2.75),
            $this->formatNumber($this->s1->interquartileRange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.2066),
            $this->formatNumber($this->s2a->interquartileRange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.6268),
            $this->formatNumber($this->s2b->interquartileRange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.18),
            $this->formatNumber($this->s3->interquartileRange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.8),
            $this->formatNumber($this->s4a->interquartileRange()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.4),
            $this->formatNumber($this->s4b->interquartileRange()),
            '', __DELTA);
    }

/*}}}*/

    public function testQuartileDeviation()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(1.375),
            $this->formatNumber($this->s1->quartileDeviation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.6033),
            $this->formatNumber($this->s2a->quartileDeviation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.3134),
            $this->formatNumber($this->s2b->quartileDeviation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.59),
            $this->formatNumber($this->s3->quartileDeviation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.9),
            $this->formatNumber($this->s4a->quartileDeviation()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.2),
            $this->formatNumber($this->s4b->quartileDeviation()),
            '', __DELTA);
    }

/*}}}*/

    public function testQuartileVariationCoefficient()
    {
/*{{{*/
        /*
        echo "quartileVariationCoefficient\n";
        echo $this->s1->quartileVariationCoefficient() ."\n";
        echo $this->s2a->quartileVariationCoefficient()."\n";
        echo $this->s2b->quartileVariationCoefficient()."\n";
        echo $this->s3->quartileVariationCoefficient() ."\n";
        echo $this->s4a->quartileVariationCoefficient()."\n";
        echo $this->s4b->quartileVariationCoefficient()."\n";
         */
        $this->assertEquals($this->formatNumber(40.7407407407),
            $this->formatNumber($this->s1->quartileVariationCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(206.185919344),
            $this->formatNumber($this->s2a->quartileVariationCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(100),
            $this->formatNumber($this->s2b->quartileVariationCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(32.5966850829),
            $this->formatNumber($this->s3->quartileVariationCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(50),
            $this->formatNumber($this->s4a->quartileVariationCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(100),
            $this->formatNumber($this->s4b->quartileVariationCoefficient()),
            '', __DELTA);
    }

/*}}}*/

    public function testQuartileSkewnessCoefficient()
    {
/*{{{*/
        /*
        echo "quartileSkewnessCoefficient\n";
        echo $this->s1->quartileSkewnessCoefficient() ."\n";
        echo $this->s2a->quartileSkewnessCoefficient()."\n";
        echo $this->s2b->quartileSkewnessCoefficient()."\n";
        echo $this->s3->quartileSkewnessCoefficient() ."\n";
        echo $this->s4a->quartileSkewnessCoefficient()."\n";
        echo $this->s4b->quartileSkewnessCoefficient()."\n";
         */
        $this->assertEquals($this->formatNumber(0.2),
            $this->formatNumber($this->s1->quartileSkewnessCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.3258743577),
            $this->formatNumber($this->s2a->quartileSkewnessCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.31924058711),
            $this->formatNumber($this->s2b->quartileSkewnessCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.88644067797),
            $this->formatNumber($this->s3->quartileSkewnessCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(-0.66666666667),
            $this->formatNumber($this->s4a->quartileSkewnessCoefficient()),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.25),
            $this->formatNumber($this->s4b->quartileSkewnessCoefficient()),
            '', __DELTA);
    }

/*}}}*/

    public function testPercentile()
    {
/*{{{*/
        $this->assertEquals($this->formatNumber(2),
            $this->formatNumber($this->s1->percentile(25)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.6268),
            $this->formatNumber($this->s2a->percentile(60)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(0.8959),
            $this->formatNumber($this->s2b->percentile(80)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(1.22),
            $this->formatNumber($this->s3->percentile(25)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.4),
            $this->formatNumber($this->s4a->percentile(60)),
            '', __DELTA);
        $this->assertEquals($this->formatNumber(2.4),
            $this->formatNumber($this->s4b->percentile(80)),
            '', __DELTA);
    }

/*}}}*/

    public function testStudentize()
    {
/*{{{*/
        $this->s1->studentize();

        $this->setExpectedException('PEAR_Exception', 'The product of the data set is negative, geometric mean undefined.');
        $this->formatArray($this->s1->getData());

        $this->assertEquals($GLOBALS['testStudentize_out2'],
            $this->formatArray($this->s1->calcFull(false)));
        $this->s3->studentize();
        $this->assertEquals($GLOBALS['testStudentize_out3'],
            $this->formatArray($this->s3->getData()));
        $this->assertEquals($GLOBALS['testStudentize_out4'],
            $this->formatArray($this->s3->calcFull(false)));
    }

/*}}}*/

    public function testCenter()
    {
/*{{{*/
        $this->s1->center();
        $this->assertEquals($GLOBALS['testCenter_out1'],
            $this->formatArray($this->s1->getData()));

        $this->setExpectedException('PEAR_Exception', 'The product of the data set is negative, geometric mean undefined.');
        $this->formatArray($this->s1->calcFull(false));

        $this->s3->center();
        $this->assertEquals($GLOBALS['testCenter_out3'],
            $this->formatArray($this->s3->getData()));
        $this->assertEquals($GLOBALS['testCenter_out4'],
            $this->formatArray($this->s3->calcFull(false)));
    }

/*}}}*/

    public function formatNumber($n)
    {
/*{{{*/
        return (float) sprintf('%.' . (__PRECISION - 1) . 'f', $n);
    }

/*}}}*/

    public function formatArray($arr, $spcs = 0)
    {
/*{{{*/
        $out = '';
        foreach ($arr as $key => $val) {
            $out .= str_repeat(" ", $spcs) . "[$key : ";
            if (is_array($val)) {
                $out .= "\n" . $this->formatArray($val, ($spcs + 1)) . "]\n";
            } else {
                $out .= $val . "]\n";
            }
        }
        return $out;
    }

/*}}}*/

} /*}}}*/
