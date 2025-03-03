<?php
if ( ! function_exists( 'translation_date_format' ) ) {

	function translation_date_format( $the_date ) {

		if ( function_exists( 'pll_current_language' ) ) {
			$siteLang = pll_current_language();
		} else {
			$siteLang = substr( get_option( 'WPLANG' ), 0, 2 );
			if ( empty( $siteLang ) ) {
				$siteLang = 'en';
			}
		}

		// Catch ajax calls
		if ( empty( $sitelang ) && ! empty( $_POST['lang'] ) ) {
			$siteLang = $_POST['lang'];
		}

		if ( $siteLang == 'fr' ) {
			$date_parts = explode( ' ', str_replace( ',', '', $the_date ) );
			$months     = array( 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre' );
			if ( in_array( $date_parts[0], $months ) ) {
				return $date_parts[1] . ' ' . $date_parts[0] . ', ' . $date_parts[2];
			} else {
				$timestamp = strtotime( $the_date );
				return date( 'j', $timestamp ) . ' ' . $months[ date( 'n', $timestamp ) - 1 ] . ', ' . date( 'Y', $timestamp );
			}
		} else {
			// Add default wp formatting
			$f = get_option( 'date_format' );
			return date( $f, strtotime( $the_date ) );
		}
	}
	//add_action( 'get_the_date', 'translation_date_format', 10, 1 );

}
