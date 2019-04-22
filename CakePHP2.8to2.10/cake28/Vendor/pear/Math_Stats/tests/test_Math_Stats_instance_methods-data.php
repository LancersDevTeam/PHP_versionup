<?php
/**
 * Output data for the unit tests
 * 
 * @package Math_Stats
 * $Id$
 */

//
// for Math_Stats_Unit_Test::testGetData()
//

$testGetData_out1 = <<< EOD
[0 : 1]
[1 : 2]
[2 : 2]
[3 : 2]
[4 : 2.3]
[5 : 3]
[6 : 3.2]
[7 : 4]
[8 : 4.5]
[9 : 5]
[10 : 5.3]
[11 : 6]

EOD;

$testGetData_out2 = <<< EOD
[0 : -0.6965]
[1 : 0.0751]
[2 : 0.3516]
[3 : 0.6268]
[4 : 0.6268]
[5 : 1.165]

EOD;

$testGetData_out3 = <<< EOD
[0 : -0.6965]
[1 : 0]
[2 : 0]
[3 : 0.0751]
[4 : 0.3516]
[5 : 0.6268]
[6 : 0.6268]
[7 : 1.165]

EOD;

$testGetData_out4 = <<< EOD
[0.5 : 3]
[0.9 : 2]
[1.22 : 6]
[2.333 : 5]
[2.4 : 7]
[3 : 4]

EOD;

$testGetData_out5 = <<< EOD
[0.5 : 3]
[0.9 : 2]
[2.4 : 7]
[3 : 4]

EOD;

$testGetData_out6 = <<< EOD
[0 : 0.5]
[1 : 0.5]
[2 : 0.5]
[3 : 0.9]
[4 : 0.9]
[5 : 2.4]
[6 : 2.4]
[7 : 2.4]
[8 : 2.4]
[9 : 2.4]
[10 : 2.4]
[11 : 2.4]
[12 : 3]
[13 : 3]
[14 : 3]
[15 : 3]

EOD;

$testGetData_out7 = <<< EOD
[0 : 8]
[0.5 : 3]
[0.9 : 2]
[2.4 : 7]
[3 : 4]

EOD;

//
// for Math_Stats_Unit_Test::testCalcBasic()
//

$testCalcBasic_out1 = <<< EOD
[min : 1]
[max : 6]
[sum : 40.3]
[sum2 : 162.87]
[count : 12]
[mean : 3.35833333333]
[stdev : 1.58197709059]
[variance : 2.50265151515]
[range : 5]

EOD;

$testCalcBasic_out2 = <<< EOD
[min : -0.6965]
[max : 1.165]
[sum : 2.1488]
[sum2 : 2.7573563]
[count : 6]
[mean : 0.358133333333]
[stdev : 0.630523495729]
[variance : 0.397559878667]
[range : 1.8615]

EOD;

$testCalcBasic_out3 = <<< EOD
[min : -0.6965]
[max : 1.165]
[sum : 2.1488]
[sum2 : 2.7573563]
[count : 8]
[mean : 0.2686]
[stdev : 0.558081998583]
[variance : 0.311455517143]
[range : 1.8615]

EOD;

$testCalcBasic_out4 = <<< EOD
[min : 0.5]
[max : 3]
[sum : 51.085]
[sum2 : 114.834845]
[count : 27]
[mean : 1.89203703704]
[stdev : 0.836203254712]
[variance : 0.699235883191]
[range : 2.5]

EOD;

$testCalcBasic_out5 = <<< EOD
[min : 0.5]
[max : 3]
[sum : 32.1]
[sum2 : 78.69]
[count : 16]
[mean : 2.00625]
[stdev : 0.976025102136]
[variance : 0.952625]
[range : 2.5]

EOD;

$testCalcBasic_out6 = <<< EOD
[min : 0]
[max : 3]
[sum : 32.1]
[sum2 : 78.69]
[count : 24]
[mean : 1.3375]
[stdev : 1.24684384155]
[variance : 1.55461956522]
[range : 3]

EOD;

//
// for Math_Stats_Unit_Test::testCalcFull()
//

$testCalcFull_out1 = <<< EOD
[min : 1]
[max : 6]
[sum : 40.3]
[sum2 : 162.87]
[count : 12]
[mean : 3.35833333333]
[median : 3.1]
[mode : 
 [0 : 2]
]
[midrange : 3.5]
[geometric_mean : 2.98753652642]
[harmonic_mean : 2.60406264194]
[stdev : 1.58197709059]
[absdev : 1.33472222222]
[variance : 2.50265151515]
[range : 5]
[std_error_of_mean : 0.456677449552]
[skewness : 0.211767803758]
[kurtosis : -1.47708896609]
[coeff_of_variation : 0.471060175858]
[sample_central_moments : 
 [1 : 0]
 [2 : 2.29409722222]
 [3 : 0.838417824074]
 [4 : 9.5383947772]
 [5 : 6.8381651757]
]
[sample_raw_moments : 
 [1 : 3.35833333333]
 [2 : 13.5725]
 [3 : 61.8280833333]
 [4 : 303.246025]
 [5 : 1557.67866083]
]
[frequency : 
 [1 : 1]
 [2 : 3]
 [2.3 : 1]
 [3 : 1]
 [3.2 : 1]
 [4 : 1]
 [4.5 : 1]
 [5 : 1]
 [5.3 : 1]
 [6 : 1]
]
[quartiles : 
 [25 : 2]
 [50 : 3.1]
 [75 : 4.75]
]
[interquartile_range : 2.75]
[interquartile_mean : 2.875]
[quartile_deviation : 1.375]
[quartile_variation_coefficient : 40.7407407407]
[quartile_skewness_coefficient : 0.2]

EOD;

$testCalcFull_out2 = <<< EOD
[min : -0.6965]
[max : 1.165]
[sum : 2.1488]
[sum2 : 2.7573563]
[count : 6]
[mean : 0.358133333333]
[median : 0.4892]
[mode : 
 [0 : 0.6268]
]
[midrange : 0.23425]
[geometric_mean : The product of the data set is negative, geometric mean undefined.]
[harmonic_mean : 0.319605399284]
[stdev : 0.630523495729]
[absdev : 0.448066666667]
[variance : 0.397559878667]
[range : 1.8615]
[std_error_of_mean : 0.257410139229]
[skewness : -0.419944986921]
[kurtosis : -1.23078655976]
[coeff_of_variation : 1.76058310423]
[sample_central_moments : 
 [1 : 0]
 [2 : 0.331299898889]
 [3 : -0.105267964498]
 [4 : 0.279631008309]
 [5 : -0.160286776741]
]
[sample_raw_moments : 
 [1 : 0.358133333333]
 [2 : 0.459559383333]
 [3 : 0.296614643564]
 [4 : 0.400235760952]
 [5 : 0.363493755597]
]
[frequency : 
 [-0.6965 : 1]
 [0.0751 : 1]
 [0.3516 : 1]
 [0.6268 : 2]
 [1.165 : 1]
]
[quartiles : 
 [25 : -0.3107]
 [50 : 0.4892]
 [75 : 0.8959]
]
[interquartile_range : 1.2066]
[interquartile_mean : 0.420075]
[quartile_deviation : 0.6033]
[quartile_variation_coefficient : 206.185919344]
[quartile_skewness_coefficient : -0.325874357699]

EOD;

$testCalcFull_out3 = <<< EOD
[min : -0.6965]
[max : 1.165]
[sum : 2.1488]
[sum2 : 2.7573563]
[count : 8]
[mean : 0.2686]
[median : 0.21335]
[mode : 
 [0 : 0.6268]
 [1 : 0]
]
[midrange : 0.23425]
[geometric_mean : 0]
[harmonic_mean : cannot calculate a harmonic mean with data values of zero.]
[stdev : 0.558081998583]
[absdev : 0.42395]
[variance : 0.311455517143]
[range : 1.8615]
[std_error_of_mean : 0.197311782828]
[skewness : -0.0950243805522]
[kurtosis : -0.992375284912]
[coeff_of_variation : 2.07774385176]
[sample_central_moments : 
 [1 : 0]
 [2 : 0.2725235775]
 [3 : -0.0165169209322]
 [4 : 0.194748710291]
 [5 : -0.0312197058513]
]
[sample_raw_moments : 
 [1 : 0.2686]
 [2 : 0.3446695375]
 [3 : 0.222460982673]
 [4 : 0.300176820714]
 [5 : 0.272620316698]
]
[frequency : 
 [-0.6965 : 1]
 [0 : 2]
 [0.0751 : 1]
 [0.3516 : 1]
 [0.6268 : 2]
 [1.165 : 1]
]
[quartiles : 
 [25 : 0]
 [50 : 0.21335]
 [75 : 0.6268]
]
[interquartile_range : 0.6268]
[interquartile_mean : 0.28005]
[quartile_deviation : 0.3134]
[quartile_variation_coefficient : 100]
[quartile_skewness_coefficient : 0.319240587109]

EOD;

$testCalcFull_out4 = <<< EOD
[min : 0.5]
[max : 3]
[sum : 51.085]
[sum2 : 114.834845]
[count : 27]
[mean : 1.89203703704]
[median : 2.333]
[mode : 
 [0 : 2.4]
]
[midrange : 1.75]
[geometric_mean : 1.48888486575]
[harmonic_mean : 1.38224654591]
[stdev : 0.836203254712]
[absdev : 0.754993141289]
[variance : 0.699235883191]
[range : 2.5]
[std_error_of_mean : 0.160927391402]
[skewness : -0.321743512221]
[kurtosis : -1.4009978017]
[coeff_of_variation : 0.441959241993]
[sample_central_moments : 
 [1 : 0]
 [2 : 0.673338257888]
 [3 : -0.188124500214]
 [4 : 0.781801456542]
 [5 : -0.423201358026]
]
[sample_raw_moments : 
 [1 : 1.89203703704]
 [2 : 4.25314240741]
 [3 : 10.4069403031]
 [4 : 26.6355596552]
 [5 : 70.0907688278]
]
[frequency : 
 [0.5 : 3]
 [0.9 : 2]
 [1.22 : 6]
 [2.333 : 5]
 [2.4 : 7]
 [3 : 4]
]
[quartiles : 
 [25 : 1.22]
 [50 : 2.333]
 [75 : 2.4]
]
[interquartile_range : 1.18]
[interquartile_mean : 1.98805555556]
[quartile_deviation : 0.59]
[quartile_variation_coefficient : 32.5966850829]
[quartile_skewness_coefficient : -0.886440677966]

EOD;

$testCalcFull_out5 = <<< EOD
[min : 0.5]
[max : 3]
[sum : 32.1]
[sum2 : 78.69]
[count : 16]
[mean : 2.00625]
[median : 2.4]
[mode : 
 [0 : 2.4]
]
[midrange : 1.75]
[geometric_mean : 1.48248699844]
[harmonic_mean : 1.28285077951]
[stdev : 0.976025102136]
[absdev : 0.84140625]
[variance : 0.952625]
[range : 2.5]
[std_error_of_mean : 0.244006275534]
[skewness : -0.578555878286]
[kurtosis : -1.4499405466]
[coeff_of_variation : 0.486492262747]
[sample_central_moments : 
 [1 : 0]
 [2 : 0.8930859375]
 [3 : -0.537933105469]
 [4 : 1.40667025909]
 [5 : -1.41441237087]
]
[sample_raw_moments : 
 [1 : 2.00625]
 [2 : 4.918125]
 [3 : 12.9125625]
 [4 : 34.85893125]
 [5 : 95.666150625]
]
[frequency : 
 [0.5 : 3]
 [0.9 : 2]
 [2.4 : 7]
 [3 : 4]
]
[quartiles : 
 [25 : 0.9]
 [50 : 2.4]
 [75 : 2.7]
]
[interquartile_range : 1.8]
[interquartile_mean : 2.06666666667]
[quartile_deviation : 0.9]
[quartile_variation_coefficient : 50]
[quartile_skewness_coefficient : -0.666666666667]

EOD;

$testCalcFull_out6 = <<< EOD
[min : 0]
[max : 3]
[sum : 32.1]
[sum2 : 78.69]
[count : 24]
[mean : 1.3375]
[median : 0.9]
[mode : 
 [0 : 0]
]
[midrange : 1.5]
[geometric_mean : 0]
[harmonic_mean : cannot calculate a harmonic mean with data values of zero.]
[stdev : 1.24684384155]
[absdev : 1.17395833333]
[variance : 1.55461956522]
[range : 3]
[std_error_of_mean : 0.254510933395]
[skewness : 0.122636601767]
[kurtosis : -1.8513180639]
[coeff_of_variation : 0.932219694619]
[sample_central_moments : 
 [1 : 0]
 [2 : 1.48984375]
 [3 : 0.23771484375]
 [4 : 2.77618273926]
 [5 : 1.03204088745]
]
[sample_raw_moments : 
 [1 : 1.3375]
 [2 : 3.27875]
 [3 : 8.608375]
 [4 : 23.2392875]
 [5 : 63.77743375]
]
[frequency : 
 [0 : 8]
 [0.5 : 3]
 [0.9 : 2]
 [2.4 : 7]
 [3 : 4]
]
[quartiles : 
 [25 : 0]
 [50 : 0.9]
 [75 : 2.4]
]
[interquartile_range : 2.4]
[interquartile_mean : 1.005]
[quartile_deviation : 1.2]
[quartile_variation_coefficient : 100]
[quartile_skewness_coefficient : 0.25]

EOD;

//
// for Math_Stats_Unit_Test::mode()
//

$testMode_out1 = <<< EOD
[0 : 2]

EOD;

$testMode_out2 = <<< EOD
[0 : 0.6268]

EOD;

$testMode_out3 = <<< EOD
[0 : 0.6268]
[1 : 0]

EOD;

$testMode_out4 = <<< EOD
[0 : 2.4]

EOD;

$testMode_out5 = <<< EOD
[0 : 2.4]

EOD;

$testMode_out6 = <<< EOD
[0 : 0]

EOD;

//
// for Math_Stats_Unit_Test::testFrequency()
//

$testFrequency_out1 = <<< EOD
[1 : 1]
[2 : 3]
[2.3 : 1]
[3 : 1]
[3.2 : 1]
[4 : 1]
[4.5 : 1]
[5 : 1]
[5.3 : 1]
[6 : 1]

EOD;

$testFrequency_out2 = <<< EOD
[-0.6965 : 1]
[0.0751 : 1]
[0.3516 : 1]
[0.6268 : 2]
[1.165 : 1]

EOD;

$testFrequency_out3 = <<< EOD
[-0.6965 : 1]
[0 : 2]
[0.0751 : 1]
[0.3516 : 1]
[0.6268 : 2]
[1.165 : 1]

EOD;

$testFrequency_out4 = <<< EOD
[0.5 : 3]
[0.9 : 2]
[1.22 : 6]
[2.333 : 5]
[2.4 : 7]
[3 : 4]

EOD;

$testFrequency_out5 = <<< EOD
[0.5 : 3]
[0.9 : 2]
[2.4 : 7]
[3 : 4]

EOD;

$testFrequency_out6 = <<< EOD
[0 : 8]
[0.5 : 3]
[0.9 : 2]
[2.4 : 7]
[3 : 4]

EOD;

//
// for Math_Stats_Unit_Test::testQuartiles()
//

$testQuartiles_out1 = <<< EOD
[25 : 2]
[50 : 3.1]
[75 : 4.75]

EOD;

$testQuartiles_out2 = <<< EOD
[25 : -0.3107]
[50 : 0.4892]
[75 : 0.8959]

EOD;

$testQuartiles_out3 = <<< EOD
[25 : 0]
[50 : 0.21335]
[75 : 0.6268]

EOD;

$testQuartiles_out4 = <<< EOD
[25 : 1.22]
[50 : 2.333]
[75 : 2.4]

EOD;

$testQuartiles_out5 = <<< EOD
[25 : 0.9]
[50 : 2.4]
[75 : 2.7]

EOD;

$testQuartiles_out6 = <<< EOD
[25 : 0]
[50 : 0.9]
[75 : 2.4]

EOD;

//
// for Math_Stats_Unit_Test::testStudentize()
//

$testStudentize_out1 = <<< EOD
[0 : -1.49075062298]
[1 : -0.858630217475]
[2 : -0.858630217475]
[3 : -0.858630217475]
[4 : -0.668994095824]
[5 : -0.226509811972]
[6 : -0.100085730871]
[7 : 0.405610593531]
[8 : 0.721670796282]
[9 : 1.03773099903]
[10 : 1.22736712068]
[11 : 1.66985140454]

EOD;

$testStudentize_out2 = <<< EOD
[min : -1.49075062298]
[max : 1.66985140454]
[sum : 3.5527136788E-15]
[sum2 : 11]
[count : 12]
[mean : 2.96059473233E-16]
[median : -0.163297771422]
[mode : 
 [0 : -0.858630217475]
]
[midrange : 0.0895503907796]
[geometric_mean : The product of the data set is negative, geometric mean undefined.]
[harmonic_mean : -0.867212759398]
[stdev : 1]
[absdev : 0.843705152345]
[variance : 1]
[range : 3.16060202751]
[std_error_of_mean : 0.288675134595]
[skewness : 0.211767803758]
[kurtosis : -1.47708896609]
[coeff_of_variation : 3.37769972053E+15]
[sample_central_moments : 
 [1 : 0]
 [2 : 0.916666666667]
 [3 : 0.211767803758]
 [4 : 1.52291103391]
 [5 : 0.690142284491]
]
[sample_raw_moments : 
 [1 : 2.96059473233E-16]
 [2 : 0.916666666667]
 [3 : 0.211767803758]
 [4 : 1.52291103391]
 [5 : 0.690142284491]
]
[frequency : 
 [-1.49075062298 : 1]
 [-0.858630217475 : 3]
 [-0.668994095824 : 1]
 [-0.226509811972 : 1]
 [-0.100085730871 : 1]
 [0.405610593531 : 1]
 [0.721670796282 : 1]
 [1.03773099903 : 1]
 [1.22736712068 : 1]
 [1.66985140454 : 1]
]
[quartiles : 
 [25 : -0.858630217475]
 [50 : -0.163297771422]
 [75 : 0.879700897658]
]
[interquartile_range : 1.73833111513]
[interquartile_mean : -0.30552486266]
[quartile_deviation : 0.869165557566]
[quartile_variation_coefficient : 8250]
[quartile_skewness_coefficient : 0.2]

EOD;

$testStudentize_out3 = <<< EOD
[-1.66471133566 : 3]
[-1.18635873688 : 2]
[-0.803676657858 : 6]
[0.527339448248 : 5]
[0.607463508544 : 7]
[1.32499240671 : 4]

EOD;

$testStudentize_out4 = <<< EOD
[min : -1.66471133566]
[max : 1.32499240671]
[sum : -8.881784197E-16]
[sum2 : 25.9999999999]
[count : 27]
[mean : -3.28954970259E-17]
[median : 0.527339448248]
[mode : 
 [0 : 0.607463508544]
]
[midrange : -0.169859464475]
[geometric_mean : The product of the data set is negative, geometric mean undefined.]
[harmonic_mean : 2.06577804427]
[stdev : 0.999999999998]
[absdev : 0.902882327992]
[variance : 0.999999999997]
[range : 2.98970374237]
[std_error_of_mean : 0.19245008973]
[skewness : -0.321743512222]
[kurtosis : -1.4009978017]
[coeff_of_variation : -3.03992974847E+16]
[sample_central_moments : 
 [1 : 0]
 [2 : 0.96296296296]
 [3 : -0.321743512221]
 [4 : 1.59900219829]
 [5 : -1.03511305176]
]
[sample_raw_moments : 
 [1 : -3.28954970259E-17]
 [2 : 0.96296296296]
 [3 : -0.321743512221]
 [4 : 1.59900219829]
 [5 : -1.03511305176]
]
[frequency : 
 [-1.66471133566 : 3]
 [-1.18635873688 : 2]
 [-0.803676657858 : 6]
 [0.527339448248 : 5]
 [0.607463508544 : 7]
 [1.32499240671 : 4]
]
[quartiles : 
 [25 : -0.803676657858]
 [50 : 0.527339448248]
 [75 : 0.607463508544]
]
[interquartile_range : 1.4111401664]
[interquartile_mean : 0.114826769661]
[quartile_deviation : 0.705570083201]
[quartile_variation_coefficient : -719.187358918]
[quartile_skewness_coefficient : -0.886440677966]

EOD;

$testCenter_out3 = <<< EOD
[-1.39203703704 : 3]
[-0.992037037037 : 2]
[-0.672037037037 : 6]
[0.440962962963 : 5]
[0.507962962963 : 7]
[1.10796296296 : 4]

EOD;

$testCenter_out4 = <<< EOD
[min : -1.39203703704]
[max : 1.10796296296]
[sum : -1.9998225298E-11]
[sum2 : 18.180132963]
[count : 27]
[mean : -7.40675011036E-13]
[median : 0.440962962963]
[mode : 
 [0 : 0.507962962963]
]
[midrange : -0.14203703704]
[geometric_mean : The product of the data set is negative, geometric mean undefined.]
[harmonic_mean : 1.72741032413]
[stdev : 0.836203254712]
[absdev : 0.754993141289]
[variance : 0.699235883191]
[range : 2.5]
[std_error_of_mean : 0.160927391402]
[skewness : -0.321743512225]
[kurtosis : -1.4009978017]
[coeff_of_variation : -1.12897457353E+12]
[sample_central_moments : 
 [1 : 0]
 [2 : 0.673338257887]
 [3 : -0.188124500216]
 [4 : 0.781801456543]
 [5 : -0.423201358032]
]
[sample_raw_moments : 
 [1 : -7.40675011036E-13]
 [2 : 0.673338257887]
 [3 : -0.188124500217]
 [4 : 0.781801456544]
 [5 : -0.423201358035]
]
[frequency : 
 [-1.39203703704 : 3]
 [-0.992037037037 : 2]
 [-0.672037037037 : 6]
 [0.440962962963 : 5]
 [0.507962962963 : 7]
 [1.10796296296 : 4]
]
[quartiles : 
 [25 : -0.672037037037]
 [50 : 0.440962962963]
 [75 : 0.507962962963]
]
[interquartile_range : 1.18]
[interquartile_mean : 0.0960185185186]
[quartile_deviation : 0.59]
[quartile_variation_coefficient : -719.187358917]
[quartile_skewness_coefficient : -0.886440677966]

EOD;

//
// for Math_Stats_Unit_Test::testCenter()
//

$testCenter_out1 = <<< EOD
[0 : -2.35833333333]
[1 : -1.35833333333]
[2 : -1.35833333333]
[3 : -1.35833333333]
[4 : -1.05833333333]
[5 : -0.358333333333]
[6 : -0.158333333333]
[7 : 0.641666666667]
[8 : 1.14166666667]
[9 : 1.64166666667]
[10 : 1.94166666667]
[11 : 2.64166666667]

EOD;

$testCenter_out2 = <<< EOD
[min : -2.35833333333]
[max : 2.64166666667]
[sum : 5.3290705182E-15]
[sum2 : 27.5291666667]
[count : 12]
[mean : 4.4408920985E-16]
[median : -0.258333333333]
[mode : 
 [0 : -1.35833333333]
]
[midrange : 0.141666666667]
[geometric_mean : The product of the data set is negative, geometric mean undefined.]
[harmonic_mean : -1.37191071803]
[stdev : 1.58197709059]
[absdev : 1.33472222222]
[variance : 2.50265151515]
[range : 5]
[std_error_of_mean : 0.456677449552]
[skewness : 0.211767803758]
[kurtosis : -1.47708896609]
[coeff_of_variation : 3.56229571784E+15]
[sample_central_moments : 
 [1 : 0]
 [2 : 2.29409722222]
 [3 : 0.838417824074]
 [4 : 9.5383947772]
 [5 : 6.8381651757]
]
[sample_raw_moments : 
 [1 : 4.4408920985E-16]
 [2 : 2.29409722222]
 [3 : 0.838417824074]
 [4 : 9.5383947772]
 [5 : 6.8381651757]
]
[frequency : 
 [-2.35833333333 : 1]
 [-1.35833333333 : 3]
 [-1.05833333333 : 1]
 [-0.358333333333 : 1]
 [-0.158333333333 : 1]
 [0.641666666667 : 1]
 [1.14166666667 : 1]
 [1.64166666667 : 1]
 [1.94166666667 : 1]
 [2.64166666667 : 1]
]
[quartiles : 
 [25 : -1.35833333333]
 [50 : -0.258333333333]
 [75 : 1.39166666667]
]
[interquartile_range : 2.75]
[interquartile_mean : -0.483333333333]
[quartile_deviation : 1.375]
[quartile_variation_coefficient : 8250]
[quartile_skewness_coefficient : 0.2]

EOD;

?>
