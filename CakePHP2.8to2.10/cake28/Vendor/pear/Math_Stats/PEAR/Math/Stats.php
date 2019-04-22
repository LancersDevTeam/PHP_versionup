<?php
namespace PEAR\Math;

/**
 * @package Math_Stats
 * @author Jesus M. Castagnetto <jmcastagnetto@php.net>
 */

/**
 * A class to calculate descriptive statistics from a data set.
 * Data sets can be simple arrays of data, or a cummulative hash.
 * The second form is useful when passing large data set,
 * for example the data set:
 *
 * <pre>
 * $data1 = array (1,2,1,1,1,1,3,3,4.1,3,2,2,4.1,1,1,2,3,3,2,2,1,1,2,2);
 * </pre>
 *
 * can be epxressed more compactly as:
 *
 * <pre>
 * $data2 = array('1'=>9, '2'=>8, '3'=>5, '4.1'=>2);
 * </pre>
 *
 * Example of use:
 *
 * <pre>
 * $s = new \Math\Stats();
 * $s->setData($data1);
 * // or
 * // $s->setData($data2, self::STATS_DATA_CUMMULATIVE);
 * $stats = $s->calcBasic();
 * echo 'Mean: '.$stats['mean'].' StDev: '.$stats['stdev'].' <br />\n';
 *
 * // using data with nulls
 * // first ignoring them:
 * $data3 = array(1.2, 'foo', 2.4, 3.1, 4.2, 3.2, null, 5.1, 6.2);
 * $s->setNullOption(self::STATS_IGNORE_NULL);
 * $s->setData($data3);
 * $stats3 = $s->calcFull();
 *
 * // and then assuming nulls == 0
 * $s->setNullOption(self::STATS_USE_NULL_AS_ZERO);
 * $s->setData($data3);
 * $stats3 = $s->calcFull();
 * </pre>
 *
 * Originally this class was part of NumPHP (Numeric PHP package)
 *
 * @author  Jesus M. Castagnetto <jmcastagnetto@php.net>
 * @version 0.9
 * @access  public
 * @package Math_Stats
 */
class Stats
{

// Constants for defining the statistics to calculate
    /**
     * STATS_BASIC to generate the basic descriptive statistics
     */
    const STATS_BASIC = 1;
/**
 * STATS_FULL to generate also higher moments, mode, median, etc.
 */
    const STATS_FULL = 2;

// Constants describing the data set format
    /**
     * STATS_DATA_SIMPLE for an array of numeric values. This is the default.
     * e.g. $data = array(2,3,4,5,1,1,6);
     */
    const STATS_DATA_SIMPLE = 0;
/**
 * STATS_DATA_CUMMULATIVE for an associative array of frequency values,
 * where in each array entry, the index is the data point and the
 * value the count (frequency):
 * e.g. $data = array(3=>4, 2.3=>5, 1.25=>6, 0.5=>3)
 */
    const STATS_DATA_CUMMULATIVE = 1;

// Constants defining how to handle nulls
    /**
     * STATS_REJECT_NULL, reject data sets with null values. This is the default.
     * Any non-numeric value is considered a null in this context.
     */
    const STATS_REJECT_NULL = -1;
/**
 * STATS_IGNORE_NULL, ignore null values and prune them from the data.
 * Any non-numeric value is considered a null in this context.
 */
    const STATS_IGNORE_NULL = -2;
/**
 * STATS_USE_NULL_AS_ZERO, assign the value of 0 (zero) to null values.
 * Any non-numeric value is considered a null in this context.
 */
    const STATS_USE_NULL_AS_ZERO = -3;

    // properties

    /**
     * The simple or cummulative data set.
     * Null by default.
     *
     * @access  private
     * @var array
     */
    private $_data = null;

    /**
     * Expanded data set. Only set when cummulative data
     * is being used. Null by default.
     *
     * @access  private
     * @var array
     */
    private $_dataExpanded = null;

    /**
     * Flag for data type, one of STATS_DATA_SIMPLE or
     * STATS_DATA_CUMMULATIVE. Null by default.
     *
     * @access  private
     * @var int
     */
    private $_dataOption = null;

    /**
     * Flag for null handling options. One of STATS_REJECT_NULL,
     * STATS_IGNORE_NULL or STATS_USE_NULL_AS_ZERO
     *
     * @access  private
     * @var int
     */
    private $_nullOption;

    /**
     * Array for caching result values, should be reset
     * when using setData()
     *
     * @access private
     * @var array
     */
    private $_calculatedValues = array();

    /**
     * Constructor for the class
     *
     * @access  public
     * @param   optional    int $nullOption how to handle null values
     * @return  object  Math_Stats
     */
    public function __construct($nullOption = self::STATS_REJECT_NULL)
    {

        $this->_nullOption = $nullOption;
    }

    /**
     * Sets and verifies the data, checking for nulls and using
     * the current null handling option
     *
     * @access public
     * @param   array   $arr    the data set
     * @param   optional    int $opt    data format: STATS_DATA_CUMMULATIVE or STATS_DATA_SIMPLE (default)
     * @return  mixed   true on success, a PEAR_Error object otherwise
     */
    public function setData($arr, $opt = self::STATS_DATA_SIMPLE)
    {

        if (!is_array($arr)) {
            throw new \PEAR_Exception('invalid data, an array of numeric data was expected');
        }
        $this->_data = null;
        $this->_dataExpanded = null;
        $this->_dataOption = null;
        $this->_calculatedValues = array();
        if ($opt == self::STATS_DATA_SIMPLE) {
            $this->_dataOption = $opt;
            $this->_data = array_values($arr);
        } else if ($opt == self::STATS_DATA_CUMMULATIVE) {
            $this->_dataOption = $opt;
            $this->_data = $arr;
            $this->_dataExpanded = array();
        }
        return $this->_validate();
    }

    /**
     * Returns the data which might have been modified
     * according to the current null handling options.
     *
     * @access  public
     * @param boolean $expanded whether to return a expanded list, default is false
     * @return  mixed   array of data on success, a PEAR_Error object otherwise
     * @see _validate()
     */
    public function getData($expanded = false)
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE && $expanded) {
            return $this->_dataExpanded;
        } else {
            return $this->_data;
        }
    }

    /**
     * Sets the null handling option.
     * Must be called before assigning a new data set containing null values
     *
     * @access  public
     * @return  mixed   true on success, a PEAR_Error object otherwise
     * @see _validate()
     */
    public function setNullOption($nullOption)
    {

        if ($nullOption == self::STATS_REJECT_NULL
            || $nullOption == self::STATS_IGNORE_NULL
            || $nullOption == self::STATS_USE_NULL_AS_ZERO) {
            $this->_nullOption = $nullOption;
            return true;
        } else {
            throw new \PEAR_Exception('invalid null handling option expecting: ' .
                'STATS_REJECT_NULL, STATS_IGNORE_NULL or STATS_USE_NULL_AS_ZERO');
        }
    }

    /**
     * Transforms the data by substracting each entry from the mean and
     * dividing by its standard deviation. This will reset all pre-calculated
     * values to their original (unset) defaults.
     *
     * @access public
     * @return mixed true on success, a PEAR_Error object otherwise
     * @see mean()
     * @see stDev()
     * @see setData()
     */
    public function studentize()
    {

        try {
            $mean = $this->mean();
        } catch (\PEAR_Exception $e) {
            return $mean;
        }
        try {
            $std = $this->stDev();
        } catch (\PEAR_Exception $e) {
            return $std;
        }
        if ($std == 0) {
            throw new \PEAR_Exception('cannot studentize data, standard deviation is zero.');
        }
        $arr = array();
        if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
            foreach ($this->_data as $val => $freq) {
                $newval = ($val - $mean) / $std;
                $arr["$newval"] = $freq;
            }
        } else {
            foreach ($this->_data as $val) {
                $newval = ($val - $mean) / $std;
                $arr[] = $newval;
            }
        }
        return $this->setData($arr, $this->_dataOption);
    }

    /**
     * Transforms the data by substracting each entry from the mean.
     * This will reset all pre-calculated values to their original (unset) defaults.
     *
     * @access public
     * @return mixed true on success, a PEAR_Error object otherwise
     * @see mean()
     * @see setData()
     */
    public function center()
    {

        try {
            $mean = $this->mean();
        } catch (\PEAR_Exception $e) {
            return $mean;
        }
        $arr = array();
        if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
            foreach ($this->_data as $val => $freq) {
                $newval = $val - $mean;
                $arr["$newval"] = $freq;
            }
        } else {
            foreach ($this->_data as $val) {
                $newval = $val - $mean;
                $arr[] = $newval;
            }
        }
        return $this->setData($arr, $this->_dataOption);
    }

    /**
     * Calculates the basic or full statistics for the data set
     *
     * @access  public
     * @param   int $mode   one of STATS_BASIC or STATS_FULL
     * @param boolean $returnErrorObject whether the raw PEAR_Error (when true, default),
     *                  or only the error message will be returned (when false), if an error happens.
     * @return  mixed   an associative array of statistics on success, a PEAR_Error object otherwise
     * @see calcBasic()
     * @see calcFull()
     */
    public function calc($mode, $returnErrorObject = true)
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }

        if ($mode == self::STATS_BASIC) {

            return $this->calcBasic($returnErrorObject);
        } elseif ($mode == self::STATS_FULL) {
            return $this->calcFull($returnErrorObject);
        } else {
            throw new \PEAR_Exception('incorrect mode, expected STATS_BASIC or STATS_FULL');
        }
    }

    /**
     * Calculates a basic set of statistics
     *
     * @access  public
     * @param boolean $returnErrorObject whether the raw PEAR_Error (when true, default),
     *                  or only the error message will be returned (when false), if an error happens.
     * @return  mixed   an associative array of statistics on success, a PEAR_Error object otherwise
     * @see calc()
     * @see calcFull()
     */
    public function calcBasic($returnErrorObject = true)
    {
        return array(
            'min' => $this->__format($this->min(), $returnErrorObject),
            'max' => $this->__format($this->max(), $returnErrorObject),
            'sum' => $this->__format($this->sum(), $returnErrorObject),
            'sum2' => $this->__format($this->sum2(), $returnErrorObject),
            'count' => $this->__format($this->count(), $returnErrorObject),
            'mean' => $this->__format($this->mean(), $returnErrorObject),
            'stdev' => $this->__format($this->stDev(), $returnErrorObject),
            'variance' => $this->__format($this->variance(), $returnErrorObject),
            'range' => $this->__format($this->range(), $returnErrorObject),
        );

    }

    /**
     * Calculates a full set of statistics
     *
     * @access  public
     * @param boolean $returnErrorObject whether the raw PEAR_Error (when true, default),
     *                  or only the error message will be returned (when false), if an error happens.
     * @return  mixed   an associative array of statistics on success, a PEAR_Error object otherwise
     * @see calc()
     * @see calcBasic()
     */
    public function calcFull($returnErrorObject = true)
    {

        return array(
            'min' => $this->__format($this->min(), $returnErrorObject),
            'max' => $this->__format($this->max(), $returnErrorObject),
            'sum' => $this->__format($this->sum(), $returnErrorObject),
            'sum2' => $this->__format($this->sum2(), $returnErrorObject),
            'count' => $this->__format($this->count(), $returnErrorObject),
            'mean' => $this->__format($this->mean(), $returnErrorObject),
            'median' => $this->__format($this->median(), $returnErrorObject),
            'mode' => $this->__format($this->mode(), $returnErrorObject),
            'midrange' => $this->__format($this->midrange(), $returnErrorObject),
            'geometric_mean' => $this->__format($this->geometricMean(), $returnErrorObject),
            'harmonic_mean' => $this->__format($this->harmonicMean(), $returnErrorObject),
            'stdev' => $this->__format($this->stDev(), $returnErrorObject),
            'absdev' => $this->__format($this->absDev(), $returnErrorObject),
            'variance' => $this->__format($this->variance(), $returnErrorObject),
            'range' => $this->__format($this->range(), $returnErrorObject),
            'std_error_of_mean' => $this->__format($this->stdErrorOfMean(), $returnErrorObject),
            'skewness' => $this->__format($this->skewness(), $returnErrorObject),
            'kurtosis' => $this->__format($this->kurtosis(), $returnErrorObject),
            'coeff_of_variation' => $this->__format($this->coeffOfVariation(), $returnErrorObject),
            'sample_central_moments' => array(
                1 => $this->__format($this->sampleCentralMoment(1), $returnErrorObject),
                2 => $this->__format($this->sampleCentralMoment(2), $returnErrorObject),
                3 => $this->__format($this->sampleCentralMoment(3), $returnErrorObject),
                4 => $this->__format($this->sampleCentralMoment(4), $returnErrorObject),
                5 => $this->__format($this->sampleCentralMoment(5), $returnErrorObject),
            ),
            'sample_raw_moments' => array(
                1 => $this->__format($this->sampleRawMoment(1), $returnErrorObject),
                2 => $this->__format($this->sampleRawMoment(2), $returnErrorObject),
                3 => $this->__format($this->sampleRawMoment(3), $returnErrorObject),
                4 => $this->__format($this->sampleRawMoment(4), $returnErrorObject),
                5 => $this->__format($this->sampleRawMoment(5), $returnErrorObject),
            ),
            'frequency' => $this->__format($this->frequency(), $returnErrorObject),
            'quartiles' => $this->__format($this->quartiles(), $returnErrorObject),
            'interquartile_range' => $this->__format($this->interquartileRange(), $returnErrorObject),
            'interquartile_mean' => $this->__format($this->interquartileMean(), $returnErrorObject),
            'quartile_deviation' => $this->__format($this->quartileDeviation(), $returnErrorObject),
            'quartile_variation_coefficient' => $this->__format($this->quartileVariationCoefficient(), $returnErrorObject),
            'quartile_skewness_coefficient' => $this->__format($this->quartileSkewnessCoefficient(), $returnErrorObject),
        );
    }

    /**
     * Calculates the minimum of a data set.
     * Handles cummulative data sets correctly$this->_data[0]
     *
     * @access  public
     * @return  mixed   the minimum value on success, a PEAR_Error object otherwise
     * @see calc()
     * @see max()
     */
    public function min()
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }

        if (!array_key_exists('min', $this->_calculatedValues)) {
            if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
                $min = min(array_keys($this->_data));
            } else {
                $min = min($this->_data);
            }

            $this->_calculatedValues['min'] = $min;
        }

        return $this->_calculatedValues['min'];
    }

    /**
     * Calculates the maximum of a data set.
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the maximum value on success, a PEAR_Error object otherwise
     * @see calc()
     * @see min()
     */
    public function max()
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        if (!array_key_exists('max', $this->_calculatedValues)) {
            if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
                $max = max(array_keys($this->_data));
            } else {
                $max = max($this->_data);
            }
            $this->_calculatedValues['max'] = $max;
        }
        return $this->_calculatedValues['max'];
    }

    /**
     * Calculates SUM { xi }
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the sum on success, a PEAR_Error object otherwise
     * @see calc()
     * @see sum2()
     * @see sumN()
     */
    public function sum()
    {

        if (!array_key_exists('sum', $this->_calculatedValues)) {
            try {
                $sum = $this->sumN(1);
                $this->_calculatedValues['sum'] = $sum;
            } catch (\PEAR_Exception $e) {
                return $sum;
            }

        }
        return $this->_calculatedValues['sum'];
    }

    /**
     * Calculates SUM { (xi)^2 }
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the sum on success, a PEAR_Error object otherwise
     * @see calc()
     * @see sum()
     * @see sumN()
     */
    public function sum2()
    {

        if (!array_key_exists('sum2', $this->_calculatedValues)) {
            try {
                $sum2 = $this->sumN(2);
                $this->_calculatedValues['sum2'] = $sum2;
            } catch (\PEAR_Exception $e) {
                return $sum2;
            }
        }
        return $this->_calculatedValues['sum2'];
    }

    /**
     * Calculates SUM { (xi)^n }
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @param   numeric $n  the exponent
     * @return  mixed   the sum on success, a PEAR_Error object otherwise
     * @see calc()
     * @see sum()
     * @see sum2()
     */
    public function sumN($n)
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        $sumN = 0;
        if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
            foreach ($this->_data as $val => $freq) {
                $sumN += $freq * pow((double) $val, (double) $n);
            }
        } else {
            foreach ($this->_data as $val) {
                $sumN += pow((double) $val, (double) $n);
            }
        }
        return $sumN;
    }

    /**
     * Calculates PROD { (xi) }, (the product of all observations)
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  numeric|array|PEAR_Error  the product as a number or an array of numbers
     *                                    (if there is numeric overflow) on success,
     *                                    a PEAR_Error object otherwise
     * @see productN()
     */
    public function product()
    {

        if (!array_key_exists('product', $this->_calculatedValues)) {
            try {
                $product = $this->productN(1);
                $this->_calculatedValues['product'] = $product;
            } catch (\PEAR_Exception $e) {
                return $product;
            }

        }
        return $this->_calculatedValues['product'];
    }

    /**
     * Calculates PROD { (xi)^n }, which is the product of all observations
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @param   numeric $n  the exponent
     * @return  numeric|array|PEAR_Error  the product as a number or an array of numbers
     *                                    (if there is numeric overflow) on success,
     *                                    a PEAR_Error object otherwise
     * @see product()
     */
    public function productN($n)
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        $prodN = 1.0;
        $partial = array();
        if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
            foreach ($this->_data as $val => $freq) {
                if ($val == 0) {
                    return 0.0;
                }
                $prodN *= $freq * pow((double) $val, (double) $n);
                if ($prodN > 10000 * $n) {
                    $partial[] = $prodN;
                    $prodN = 1.0;
                }
            }
        } else {
            foreach ($this->_data as $val) {
                if ($val == 0) {
                    return 0.0;
                }
                $prodN *= pow((double) $val, (double) $n);
                if ($prodN > 10 * $n) {
                    $partial[] = $prodN;
                    $prodN = 1.0;
                }
            }
        }
        if (!empty($partial)) {
            $partial[] = $prodN;
            // try to reduce to a single value
            $tmp = 1.0;
            foreach ($partial as $val) {
                $tmp *= $val;
                // cannot reduce, return an array
                if (is_infinite($tmp)) {
                    return $partial;
                }
            }
            return $tmp;
        } else {
            return $prodN;
        }

    }

    /**
     * Calculates the number of data points in the set
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the count on success, a PEAR_Error object otherwise
     * @see calc()
     */
    public function count()
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        if (!array_key_exists('count', $this->_calculatedValues)) {
            if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
                $count = count($this->_dataExpanded);
            } else {
                $count = count($this->_data);
            }
            $this->_calculatedValues['count'] = $count;
        }
        return $this->_calculatedValues['count'];
    }

    /**
     * Calculates the mean (average) of the data points in the set
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the mean value on success, a PEAR_Error object otherwise
     * @see calc()
     * @see sum()
     * @see count()
     */
    public function mean()
    {

        if (!array_key_exists('mean', $this->_calculatedValues)) {
            try {
                $sum = $this->sum();
                try {
                    $count = $this->count();
                } catch (\PEAR_Exception $e) {
                    return $count;
                }
                $this->_calculatedValues['mean'] = $sum / $count;
            } catch (\PEAR_Exception $e) {
                return $sum;
            }

        }
        return $this->_calculatedValues['mean'];
    }

    /**
     * Calculates the range of the data set = max - min
     *
     * @access public
     * @return mixed the value of the range on success, a PEAR_Error object otherwise.
     */
    public function range()
    {

        if (!array_key_exists('range', $this->_calculatedValues)) {
            try {
                $min = $this->min();
                try {
                    $max = $this->max();
                } catch (\PEAR_Exception $e) {
                    return $max;
                }
                $this->_calculatedValues['range'] = $max - $min;

            } catch (\PEAR_Exception $e) {
                return $min;
            }

        }
        return $this->_calculatedValues['range'];

    }

    /**
     * Calculates the variance (unbiased) of the data points in the set
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the variance value on success, a PEAR_Error object otherwise
     * @see calc()
     * @see __sumdiff()
     * @see count()
     */
    public function variance()
    {

        if (!array_key_exists('variance', $this->_calculatedValues)) {
            try {
                $variance = $this->__calcVariance();
            } catch (\PEAR_Exception $e) {
                return $variance;
            }

            $this->_calculatedValues['variance'] = $variance;
        }
        return $this->_calculatedValues['variance'];
    }

    /**
     * Calculates the standard deviation (unbiased) of the data points in the set
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the standard deviation on success, a PEAR_Error object otherwise
     * @see calc()
     * @see variance()
     */
    public function stDev()
    {

        if (!array_key_exists('stDev', $this->_calculatedValues)) {
            try {
                $variance = $this->variance();
            } catch (\PEAR_Exception $e) {
                return $variance;
            }

            $this->_calculatedValues['stDev'] = sqrt($variance);
        }
        return $this->_calculatedValues['stDev'];
    }

    /**
     * Calculates the variance (unbiased) of the data points in the set
     * given a fixed mean (average) value. Not used in calcBasic(), calcFull()
     * or calc().
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @param   numeric $mean   the fixed mean value
     * @return  mixed   the variance on success, a PEAR_Error object otherwise
     * @see __sumdiff()
     * @see count()
     * @see variance()
     */
    public function varianceWithMean($mean)
    {

        return $this->__calcVariance($mean);
    }

    /**
     * Calculates the standard deviation (unbiased) of the data points in the set
     * given a fixed mean (average) value. Not used in calcBasic(), calcFull()
     * or calc().
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @param   numeric $mean   the fixed mean value
     * @return  mixed   the standard deviation on success, a PEAR_Error object otherwise
     * @see varianceWithMean()
     * @see stDev()
     */
    public function stDevWithMean($mean)
    {
        try {
            $varianceWM = $this->varianceWithMean($mean);
        } catch (\PEAR_Exception $e) {
            return $varianceWM;
        }

        return sqrt($varianceWM);
    }

    /**
     * Calculates the absolute deviation of the data points in the set
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the absolute deviation on success, a PEAR_Error object otherwise
     * @see calc()
     * @see __sumabsdev()
     * @see count()
     * @see absDevWithMean()
     */
    public function absDev()
    {

        if (!array_key_exists('absDev', $this->_calculatedValues)) {
            try {
                $absDev = $this->__calcAbsoluteDeviation();
            } catch (\PEAR_Exception $e) {
                return $absDev;
            }

            $this->_calculatedValues['absDev'] = $absDev;
        }
        return $this->_calculatedValues['absDev'];
    }

    /**
     * Calculates the absolute deviation of the data points in the set
     * given a fixed mean (average) value. Not used in calcBasic(), calcFull()
     * or calc().
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @param   numeric $mean   the fixed mean value
     * @return  mixed   the absolute deviation on success, a PEAR_Error object otherwise
     * @see __sumabsdev()
     * @see absDev()
     */
    public function absDevWithMean($mean)
    {

        return $this->__calcAbsoluteDeviation($mean);
    }

    /**
     * Calculates the skewness of the data distribution in the set
     * The skewness measures the degree of asymmetry of a distribution,
     * and is related to the third central moment of a distribution.
     * A normal distribution has a skewness = 0
     * A distribution with a tail off towards the high end of the scale
     * (positive skew) has a skewness > 0
     * A distribution with a tail off towards the low end of the scale
     * (negative skew) has a skewness < 0
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the skewness value on success, a PEAR_Error object otherwise
     * @see __sumdiff()
     * @see count()
     * @see stDev()
     * @see calc()
     */
    public function skewness()
    {

        if (!array_key_exists('skewness', $this->_calculatedValues)) {
            try {
                $count = $this->count();
                try {
                    $stDev = $this->stDev();
                    try {
                        $sumdiff3 = $this->__sumdiff(3);
                    } catch (\PEAR_Exception $e) {
                        return $sumdiff3;
                    }
                } catch (\PEAR_Exception $e) {
                    return $stDev;
                }
            } catch (\PEAR_Exception $e) {
                return $count;
            }

            $this->_calculatedValues['skewness'] = ($sumdiff3 / ($count * pow($stDev, 3)));
        }
        return $this->_calculatedValues['skewness'];
    }

    /**
     * Calculates the kurtosis of the data distribution in the set
     * The kurtosis measures the degrees of peakedness of a distribution.
     * It is also called the "excess" or "excess coefficient", and is
     * a normalized form of the fourth central moment of a distribution.
     * A normal distributions has kurtosis = 0
     * A narrow and peaked (leptokurtic) distribution has a
     * kurtosis > 0
     * A flat and wide (platykurtic) distribution has a kurtosis < 0
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the kurtosis value on success, a PEAR_Error object otherwise
     * @see __sumdiff()
     * @see count()
     * @see stDev()
     * @see calc()
     */
    public function kurtosis()
    {

        if (!array_key_exists('kurtosis', $this->_calculatedValues)) {

            try {
                $count = $this->count();
                try {
                    $stDev = $this->stDev();
                    try {
                        $sumdiff4 = $this->__sumdiff(4);
                    } catch (\PEAR_Exception $e) {
                        return $sumdiff4;
                    }
                } catch (\PEAR_Exception $e) {
                    return $stDev;
                }
            } catch (\PEAR_Exception $e) {
                return $count;
            }

            $this->_calculatedValues['kurtosis'] = ($sumdiff4 / ($count * pow($stDev, 4))) - 3;
        }
        return $this->_calculatedValues['kurtosis'];
    }

    /**
     * Calculates the median of a data set.
     * The median is the value such that half of the points are below it
     * in a sorted data set.
     * If the number of values is odd, it is the middle item.
     * If the number of values is even, is the average of the two middle items.
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the median value on success, a PEAR_Error object otherwise
     * @see count()
     * @see calc()
     */
    public function median()
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        if (!array_key_exists('median', $this->_calculatedValues)) {
            if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
                $arr = &$this->_dataExpanded;
            } else {
                $arr = &$this->_data;
            }
            try {
                $n = $this->count();
            } catch (\PEAR_Exception $e) {
                return $n;
            }

            $h = intval($n / 2);
            if ($n % 2 == 0) {
                $median = ($arr[$h] + $arr[$h - 1]) / 2;
            } else {
                $median = $arr[$h];
            }
            $this->_calculatedValues['median'] = $median;
        }
        return $this->_calculatedValues['median'];
    }

    /**
     * Calculates the mode of a data set.
     * The mode is the value with the highest frequency in the data set.
     * There can be more than one mode.
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   an array of mode value on success, a PEAR_Error object otherwise
     * @see frequency()
     * @see calc()
     */
    public function mode()
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        if (!array_key_exists('mode', $this->_calculatedValues)) {
            if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
                $arr = $this->_data;
            } else {
                $arr = $this->frequency();
            }
            arsort($arr);
            $mcount = 1;
            foreach ($arr as $val => $freq) {
                if ($mcount == 1) {
                    $mode = array($val);
                    $mfreq = $freq;
                    $mcount++;
                    continue;
                }
                if ($mfreq == $freq) {
                    $mode[] = $val;
                }

                if ($mfreq > $freq) {
                    break;
                }

            }
            $this->_calculatedValues['mode'] = $mode;
        }
        return $this->_calculatedValues['mode'];
    }

    /**
     * Calculates the midrange of a data set.
     * The midrange is the average of the minimum and maximum of the data set.
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the midrange value on success, a PEAR_Error object otherwise
     * @see min()
     * @see max()
     * @see calc()
     */
    public function midrange()
    {

        if (!array_key_exists('midrange', $this->_calculatedValues)) {
            try {
                $min = $this->min();
                try {
                    $max = $this->max();
                } catch (\PEAR_Exception $e) {
                    return $max;
                }
            } catch (\PEAR_Exception $e) {
                return $min;
            }

            $this->_calculatedValues['midrange'] = (($max + $min) / 2);
        }
        return $this->_calculatedValues['midrange'];
    }

    /**
     * Calculates the geometrical mean of the data points in the set
     * Handles cummulative data sets correctly
     *
     * @access public
     * @return mixed the geometrical mean value on success, a PEAR_Error object otherwise
     * @see calc()
     * @see product()
     * @see count()
     */
    public function geometricMean()
    {

        if (!array_key_exists('geometricMean', $this->_calculatedValues)) {
            try {
                $count = $this->count();
            } catch (\PEAR_Exception $e) {
                return $count;
            }
            try {
                $prod = $this->product();
            } catch (\PEAR_Exception $e) {
                return $prod;
            }
            if (is_array($prod)) {
                $geomMean = 1.0;
                foreach ($prod as $val) {
                    $geomMean *= pow($val, 1 / $count);
                }
                $this->_calculatedValues['geometricMean'] = $geomMean;
            } else {
                if ($prod == 0.0) {
                    return 0.0;
                }
                if ($prod < 0) {
                    throw new \PEAR_Exception('The product of the data set is negative, geometric mean undefined.');
                }
                $this->_calculatedValues['geometricMean'] = pow($prod, 1 / $count);
            }
        }
        return $this->_calculatedValues['geometricMean'];
    }

    /**
     * Calculates the harmonic mean of the data points in the set
     * Handles cummulative data sets correctly
     *
     * @access public
     * @return mixed the harmonic mean value on success, a PEAR_Error object otherwise
     * @see calc()
     * @see count()
     */
    public function harmonicMean()
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        if (!array_key_exists('harmonicMean', $this->_calculatedValues)) {
            try {
                $count = $this->count();
            } catch (\PEAR_Exception $e) {
                return $count;
            }
            $invsum = 0.0;
            if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
                foreach ($this->_data as $val => $freq) {
                    if ($val == 0) {
                        throw new \PEAR_Exception('cannot calculate a ' .
                            'harmonic mean with data values of zero.');
                    }
                    $invsum += $freq / $val;
                }
            } else {
                foreach ($this->_data as $val) {
                    if ($val == 0) {
                        throw new \PEAR_Exception('cannot calculate a ' .
                            'harmonic mean with data values of zero.');
                    }
                    $invsum += 1 / $val;
                }
            }
            $this->_calculatedValues['harmonicMean'] = $count / $invsum;
        }
        return $this->_calculatedValues['harmonicMean'];
    }

    /**
     * Calculates the nth central moment (m{n}) of a data set.
     *
     * The definition of a sample central moment is:
     *
     *     m{n} = 1/N * SUM { (xi - avg)^n }
     *
     * where: N = sample size, avg = sample mean.
     *
     * @access public
     * @param integer $n moment to calculate
     * @return mixed the numeric value of the moment on success, PEAR_Error otherwise
     */
    public function sampleCentralMoment($n)
    {

        if (!is_int($n) || $n < 1) {
            throw new \PEAR_Exception('moment must be a positive integer >= 1.');
        }

        if ($n == 1) {
            return 0;
        }
        try {
            $count = $this->count();
        } catch (\PEAR_Exception $e) {
            return $count;
        }
        if ($count == 0) {
            throw new \PEAR_Exception("Cannot calculate {$n}th sample moment, " .
                'there are zero data entries');
        }
        try {
            $sum = $this->__sumdiff($n);
        } catch (\PEAR_Exception $e) {
            return $sum;
        }
        return ($sum / $count);
    }

    /**
     * Calculates the nth raw moment (m{n}) of a data set.
     *
     * The definition of a sample central moment is:
     *
     *     m{n} = 1/N * SUM { xi^n }
     *
     * where: N = sample size, avg = sample mean.
     *
     * @access public
     * @param integer $n moment to calculate
     * @return mixed the numeric value of the moment on success, PEAR_Error otherwise
     */
    public function sampleRawMoment($n)
    {

        if (!is_int($n) || $n < 1) {
            throw new \PEAR_Exception('moment must be a positive integer >= 1.');
        }

        try {
            $count = $this->count();
        } catch (\PEAR_Exception $e) {
            return $count;
        }
        if ($count == 0) {
            throw new \PEAR_Exception("Cannot calculate {$n}th raw moment, " .
                'there are zero data entries.');
        }
        try {
            $sum = $this->sumN($n);
        } catch (\PEAR_Exception $e) {
            return $sum;
        }
        return ($sum / $count);
    }

    /**
     * Calculates the coefficient of variation of a data set.
     * The coefficient of variation measures the spread of a set of data
     * as a proportion of its mean. It is often expressed as a percentage.
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   the coefficient of variation on success, a PEAR_Error object otherwise
     * @see stDev()
     * @see mean()
     * @see calc()
     */
    public function coeffOfVariation()
    {

        if (!array_key_exists('coeffOfVariation', $this->_calculatedValues)) {
            try {
                $mean = $this->mean();
            } catch (\PEAR_Exception $e) {
                return $mean;
            }

            if ($mean == 0.0) {
                throw new \PEAR_Exception('cannot calculate the coefficient ' .
                    'of variation, mean of sample is zero');
            }
            try {
                $stDev = $this->stDev();
            } catch (\PEAR_Exception $e) {
                return $stDev;
            }

            $this->_calculatedValues['coeffOfVariation'] = $stDev / $mean;
        }
        return $this->_calculatedValues['coeffOfVariation'];
    }

    /**
     * Calculates the standard error of the mean.
     * It is the standard deviation of the sampling distribution of
     * the mean. The formula is:
     *
     * S.E. Mean = SD / (N)^(1/2)
     *
     * This formula does not assume a normal distribution, and shows
     * that the size of the standard error of the mean is inversely
     * proportional to the square root of the sample size.
     *
     * @access  public
     * @return  mixed   the standard error of the mean on success, a PEAR_Error object otherwise
     * @see stDev()
     * @see count()
     * @see calc()
     */
    public function stdErrorOfMean()
    {

        if (!array_key_exists('stdErrorOfMean', $this->_calculatedValues)) {
            try {
                $count = $this->count();
            } catch (\PEAR_Exception $e) {
                return $count;
            }
            try {
                $stDev = $this->stDev();
            } catch (\PEAR_Exception $e) {
                return $stDev;
            }
            $this->_calculatedValues['stdErrorOfMean'] = $stDev / sqrt($count);
        }
        return $this->_calculatedValues['stdErrorOfMean'];
    }

    /**
     * Calculates the value frequency table of a data set.
     * Handles cummulative data sets correctly
     *
     * @access  public
     * @return  mixed   an associative array of value=>frequency items on success, a PEAR_Error object otherwise
     * @see min()
     * @see max()
     * @see calc()
     */
    public function frequency()
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        if (!array_key_exists('frequency', $this->_calculatedValues)) {
            if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
                $freq = $this->_data;
            } else {
                $freq = array();
                foreach ($this->_data as $val) {
                    if (!isset($freq["$val"])) {
                        $freq["$val"] = 0;
                    }
                    $freq["$val"]++;
                }
                ksort($freq);
            }
            $this->_calculatedValues['frequency'] = $freq;
        }
        return $this->_calculatedValues['frequency'];
    }

    /**
     * The quartiles are defined as the values that divide a sorted
     * data set into four equal-sized subsets, and correspond to the
     * 25th, 50th, and 75th percentiles.
     *
     * @access public
     * @return mixed an associative array of quartiles on success, a PEAR_Error otherwise
     * @see percentile()
     */
    public function quartiles()
    {

        if (!array_key_exists('quartiles', $this->_calculatedValues)) {
            try {
                $q1 = $this->percentile(25);
                try {
                    $q2 = $this->percentile(50);
                    try {
                        $q3 = $this->percentile(75);
                    } catch (\PEAR_Exception $e) {
                        return $q3;
                    }
                } catch (\PEAR_Exception $e) {
                    return $q2;
                }

            } catch (\PEAR_Exception $e) {
                return $q1;
            }

            $this->_calculatedValues['quartiles'] = array(
                '25' => $q1,
                '50' => $q2,
                '75' => $q3,
            );
        }
        return $this->_calculatedValues['quartiles'];
    }

    /**
     * The interquartile mean is defined as the mean of the values left
     * after discarding the lower 25% and top 25% ranked values, i.e.:
     *
     *  interquart mean = mean(<P(25),P(75)>)
     *
     *  where: P = percentile
     *
     * @todo need to double check the equation
     * @access public
     * @return mixed a numeric value on success, a PEAR_Error otherwise
     * @see quartiles()
     */
    public function interquartileMean()
    {

        if (!array_key_exists('interquartileMean', $this->_calculatedValues)) {
            try {
                $quart = $this->quartiles();
            } catch (\PEAR_Exception $e) {
                return $quart;
            }
            $q3 = $quart['75'];
            $q1 = $quart['25'];
            $sum = 0;
            $n = 0;
            foreach ($this->getData(true) as $val) {
                if ($val >= $q1 && $val <= $q3) {
                    $sum += $val;
                    $n++;
                }
            }
            if ($n == 0) {
                throw new \PEAR_Exception('error calculating interquartile mean, ' .
                    'empty interquartile range of values.');
            }
            $this->_calculatedValues['interquartileMean'] = $sum / $n;
        }
        return $this->_calculatedValues['interquartileMean'];
    }

    /**
     * The interquartile range is the distance between the 75th and 25th
     * percentiles. Basically the range of the middle 50% of the data set,
     * and thus is not affected by outliers or extreme values.
     *
     *  interquart range = P(75) - P(25)
     *
     *  where: P = percentile
     *
     * @access public
     * @return mixed a numeric value on success, a PEAR_Error otherwise
     * @see quartiles()
     */
    public function interquartileRange()
    {

        if (!array_key_exists('interquartileRange', $this->_calculatedValues)) {
            try {
                $quart = $this->quartiles();
            } catch (\PEAR_Exception $e) {
                return $quart;
            }
            $q3 = $quart['75'];
            $q1 = $quart['25'];
            $this->_calculatedValues['interquartileRange'] = $q3 - $q1;
        }
        return $this->_calculatedValues['interquartileRange'];
    }

    /**
     * The quartile deviation is half of the interquartile range value
     *
     *  quart dev = (P(75) - P(25)) / 2
     *
     *  where: P = percentile
     *
     * @access public
     * @return mixed a numeric value on success, a PEAR_Error otherwise
     * @see quartiles()
     * @see interquartileRange()
     */
    public function quartileDeviation()
    {

        if (!array_key_exists('quartileDeviation', $this->_calculatedValues)) {
            try {
                $iqr = $this->interquartileRange();
            } catch (\PEAR_Exception $e) {
                return $iqr;
            }
            $this->_calculatedValues['quartileDeviation'] = $iqr / 2;
        }
        return $this->_calculatedValues['quartileDeviation'];
    }

    /**
     * The quartile variation coefficient is defined as follows:
     *
     *  quart var coeff = 100 * (P(75) - P(25)) / (P(75) + P(25))
     *
     *  where: P = percentile
     *
     * @todo need to double check the equation
     * @access public
     * @return mixed a numeric value on success, a PEAR_Error otherwise
     * @see quartiles()
     */
    public function quartileVariationCoefficient()
    {

        if (!array_key_exists('quartileVariationCoefficient', $this->_calculatedValues)) {
            try {
                $quart = $this->quartiles();
            } catch (\PEAR_Exception $e) {
                return $quart;
            }
            $q3 = $quart['75'];
            $q1 = $quart['25'];
            $d = $q3 - $q1;
            $s = $q3 + $q1;
            $this->_calculatedValues['quartileVariationCoefficient'] = 100 * $d / $s;
        }
        return $this->_calculatedValues['quartileVariationCoefficient'];
    }

    /**
     * The quartile skewness coefficient (also known as Bowley Skewness),
     * is defined as follows:
     *
     *  quart skewness coeff = (P(25) - 2*P(50) + P(75)) / (P(75) - P(25))
     *
     *  where: P = percentile
     *
     * @todo need to double check the equation
     * @access public
     * @return mixed a numeric value on success, a PEAR_Error otherwise
     * @see quartiles()
     */
    public function quartileSkewnessCoefficient()
    {

        if (!array_key_exists('quartileSkewnessCoefficient', $this->_calculatedValues)) {
            try {
                $quart = $this->quartiles();
            } catch (\PEAR_Exception $e) {
                return $quart;
            }
            $q3 = $quart['75'];
            $q2 = $quart['50'];
            $q1 = $quart['25'];
            $d = $q3 - 2 * $q2 + $q1;
            $s = $q3 - $q1;
            $this->_calculatedValues['quartileSkewnessCoefficient'] = $d / $s;
        }
        return $this->_calculatedValues['quartileSkewnessCoefficient'];
    }

    /**
     * The pth percentile is the value such that p% of the a sorted data set
     * is smaller than it, and (100 - p)% of the data is larger.
     *
     * A quick algorithm to pick the appropriate value from a sorted data
     * set is as follows:
     *
     * - Count the number of values: n
     * - Calculate the position of the value in the data list: i = p * (n + 1)
     * - if i is an integer, return the data at that position
     * - if i < 1, return the minimum of the data set
     * - if i > n, return the maximum of the data set
     * - otherwise, average the entries at adjacent positions to i
     *
     * The median is the 50th percentile value.
     *
     * @todo need to double check generality of the algorithm
     *
     * @access public
     * @param numeric $p the percentile to estimate, e.g. 25 for 25th percentile
     * @return mixed a numeric value on success, a PEAR_Error otherwise
     * @see quartiles()
     * @see median()
     */
    public function percentile($p)
    {
        try {
            $count = $this->count();
        } catch (\PEAR_Exception $e) {
            return $count;
        }

        if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
            $data = &$this->_dataExpanded;
        } else {
            $data = &$this->_data;
        }
        $obsidx = $p * ($count + 1) / 100;
        if (intval($obsidx) == $obsidx) {
            return $data[($obsidx - 1)];
        } elseif ($obsidx < 1) {
            return $data[0];
        } elseif ($obsidx > $count) {
            return $data[($count - 1)];
        } else {
            $left = floor($obsidx - 1);
            $right = ceil($obsidx - 1);
            return ($data[$left] + $data[$right]) / 2;
        }
    }

    // private methods

    /**
     * Utility function to calculate: SUM { (xi - mean)^n }
     *
     * @access private
     * @param   numeric $power  the exponent
     * @param   optional    double   $mean   the data set mean value
     * @return  mixed   the sum on success, a PEAR_Error object otherwise
     *
     * @see stDev()
     * @see variaceWithMean();
     * @see skewness();
     * @see kurtosis();
     */
    public function __sumdiff($power, $mean = null)
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        if (is_null($mean)) {
            try {
                $mean = $this->mean();
            } catch (\PEAR_Exception $e) {
                return $mean;
            }

        }
        $sdiff = 0;
        if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
            foreach ($this->_data as $val => $freq) {
                $sdiff += $freq * pow((double) ($val - $mean), (double) $power);
            }
        } else {
            foreach ($this->_data as $val) {
                $sdiff += pow((double) ($val - $mean), (double) $power);
            }

        }
        return $sdiff;
    }

    /**
     * Utility function to calculate the variance with or without
     * a fixed mean
     *
     * @access private
     * @param $mean the fixed mean to use, null as default
     * @return mixed a numeric value on success, a PEAR_Error otherwise
     * @see variance()
     * @see varianceWithMean()
     */
    public function __calcVariance($mean = null)
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        try {
            $sumdiff2 = $this->__sumdiff(2, $mean);
            try {
                $count = $this->count();
            } catch (\PEAR_Exception $e) {
                return $count;
            }
        } catch (\PEAR_Exception $e) {
            return $sumdiff2;
        }

        if ($count == 1) {
            throw new \PEAR_Exception('cannot calculate variance of a singe data point');
        }
        return ($sumdiff2 / ($count - 1));
    }

    /**
     * Utility function to calculate the absolute deviation with or without
     * a fixed mean
     *
     * @access private
     * @param $mean the fixed mean to use, null as default
     * @return mixed a numeric value on success, a PEAR_Error otherwise
     * @see absDev()
     * @see absDevWithMean()
     */
    public function __calcAbsoluteDeviation($mean = null)
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        try {
            $count = $this->count();
            try {
                $sumabsdev = $this->__sumabsdev($mean);
            } catch (\PEAR_Exception $e) {
                return $sumabsdev;
            }
        } catch (\PEAR_Exception $e) {
            return $count;
        }

        return $sumabsdev / $count;
    }

    /**
     * Utility function to calculate: SUM { | xi - mean | }
     *
     * @access  private
     * @param   optional    double   $mean   the mean value for the set or population
     * @return  mixed   the sum on success, a PEAR_Error object otherwise
     *
     * @see absDev()
     * @see absDevWithMean()
     */
    public function __sumabsdev($mean = null)
    {

        if ($this->_data == null) {
            throw new \PEAR_Exception('data has not been set');
        }
        if (is_null($mean)) {
            $mean = $this->mean();
        }
        $sdev = 0;
        if ($this->_dataOption == self::STATS_DATA_CUMMULATIVE) {
            foreach ($this->_data as $val => $freq) {
                $sdev += $freq * abs($val - $mean);
            }
        } else {
            foreach ($this->_data as $val) {
                $sdev += abs($val - $mean);
            }
        }
        return $sdev;
    }

    /**
     * Utility function to format a PEAR_Error to be used by calc(),
     * calcBasic() and calcFull()
     *
     * @access private
     * @param mixed $v value to be formatted
     * @param boolean $returnErrorObject whether the raw PEAR_Error (when true, default),
     *                  or only the error message will be returned (when false)
     * @return mixed if the value is a PEAR_Error object, and $useErrorObject
     *              is false, then a string with the error message will be returned,
     *              otherwise the value will not be modified and returned as passed.
     */
    public function __format($v, $useErrorObject = true)
    {

        if (is_a($v, '\PEAR_Exception') && $useErrorObject == false) {
            return $v->getMessage();
        } else {
            return $v;
        }
    }

    /**
     * Utility function to validate the data and modify it
     * according to the current null handling option
     *
     * @access  private
     * @return  mixed true on success, a PEAR_Error object otherwise
     *
     * @see setData()
     */
    public function _validate()
    {

        $cummulativeData = ($this->_dataOption == self::STATS_DATA_CUMMULATIVE);
        foreach ($this->_data as $key => $value) {
            $d = ($cummulativeData) ? $key : $value;
            $v = ($cummulativeData) ? $value : $key;
            if (!is_numeric($d)) {
                switch ($this->_nullOption) {
                    case self::STATS_IGNORE_NULL:
                        unset($this->_data["$key"]);
                        break;
                    case self::STATS_USE_NULL_AS_ZERO:
                        if ($cummulativeData) {
                            unset($this->_data["$key"]);
                            // TODO: shift up?
                            if (!isset($this->_data[0])) {
                                $this->_data[0] = 0;
                            }
                            $this->_data[0] += $v;
                        } else {
                            $this->_data[$key] = 0;
                        }
                        break;
                    case self::STATS_REJECT_NULL:
                    default:
                        throw new \PEAR_Exception('data rejected, contains NULL values');
                        break;
                }
            }
        }

        // expand cummulative data
        if ($cummulativeData) {
            ksort($this->_data);
            $this->_dataExpanded = array();
            // code below avoids using array_pad, because in PHP 4 that
            // function has a hard-coded limit of 1048576 array items
            // see php-src/ext/standard/array.c)

            //$array_pad_magic_limit = 1048576;
            foreach ($this->_data as $val => $freq) {
                // try an ugly kludge
                for ($k = 0; $k < $freq; $k++) {
                    $this->_dataExpanded[] = $val;
                }
                /* the code below causes a core dump
                $valArr = array_fill(0, $freq, $val);
                $this->_dataExpanded = array_merge($this->_dataExpanded, $valArr);
                 */
                /* the code below gives incorrect values
            // kludge to cover for array_pad's *features*
            $newcount = count($this->_dataExpanded) + $freq;
            while ($newcount > $array_pad_magic_limit) {
            $this->_dataExpanded = array_pad($this->_dataExpanded, $array_pad_magic_limit, $val);
            $newcount -= $array_pad_magic_limit;
            }
            $this->_dataExpanded = array_pad($this->_dataExpanded, $newcount, $val);
             */
            }
            //sort($this->_dataExpanded);
        } else {
            sort($this->_data);
        }
        return true;
    }

}

// vim: ts=4:sw=4:et:
// vim6: fdl=1: fdm=marker:
