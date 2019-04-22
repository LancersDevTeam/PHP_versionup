<?php

function normsdist( $x ) {

	// Returns the standard normal cumulative distribution
	// ---------------------------------------
	
	// Load tabulated values in an array
	include "ndist_tabulated.php" ;
	
	// Discriminate upon the absolute value, then the sign of $x
	$x = number_format( $x, 2 ) ;
	if ( abs( $x ) >= 3.09 ) {
		
		$output = 0 ;
			
	} elseif ( $x==0 ) {
		
		$output = 0.5 ;
			
	} elseif ( $x<0 ) {

		// find higher boundary (next highest value with 2 decimals)
		$x2 = number_format( ceil( 100*$x )/100, 2 ) ;
		$x2 = (string)$x2 ;
		
		// find lower boundary
		$x1 = number_format( $x2-0.01, 2 ) ;
		$x1 = (string)$x1 ;

		// linear interpolate
		$output = $values[$x1] + ( $values[$x2] - $values[$x1] )/0.01*( $x - $x1 ) ;
		
	} else {		// if x>0
	
		$output = 1 - normsdist( -$x ) ;
		
	}
	
	return number_format( $output, 4 ) ;
	
}


function normsinv( $y ) {

	// Returns the inverse standard normal cumulative distribution ( 0<y<1 )
	// ---------------------------------------
	
	// Load tabulated values in an array
	include "ndist_tabulated.php" ;
	
	// Discriminate upon whether $y is between 0 and 1, then upon its position relative to 0.5
	$y = number_format( $y, 4 ) ;
	if ( $y<=0 || $y>=1 ) {
	
		$output = FALSE ;
	
	} elseif ( $y<=0.5 ) {
	
		// find the largest index which value is smaller than $y:
		
		// filter array for values higher than $y
		$smaller = array() ;
		while ( list( $key, $value ) = each( $values ) ) {
			if ( $value <= $y ) {
				$smaller[$key] = $value ;
			}
		}
		// order $values in decreasing terms of the $values
		krsort( $smaller, SORT_NUMERIC ) ;
		reset( $smaller ) ;

		$x1 = (string)key( $smaller ) ;
		$x2 = (string)( $x1+0.01 ) ;

		// interpolate
		$output = $x1 + ( $y - $values[$x1] )/( $values[$x2] - $values[$x1] )*0.01 ;
			
	} else {	// meaning $x between 0.5 and 1
	
		$output = - normsinv( 1 - $y ) ;
		
	}

	return number_format( $output, 4 ) ;

}


?>
