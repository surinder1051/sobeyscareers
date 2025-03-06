<?php
/**
 * Colour Utilites
 * Used for CSS to darken or lighten a colour
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'adjustBrightness' ) ) {
	/**
	 * Used for CSS to darken or lighten a colour
	 *
	 * @package fp-foundation
	 *
	 * @param string $hex_code is the colour value in hexadecimal.
	 * @param string $adjust_percent how much to lighten or darken.
	 *
	 * @return string|void
	 */
	function adjustBrightness( $hex_code = '', $adjust_percent = '' ) { //phpcs:ignore
		if ( empty( $hex_code ) || empty( $adjust_percent ) ) {
			return;
		}
		$hex_code = ltrim( $hex_code, '#' );
		if ( strlen( $hex_code ) === 3 ) {
			$hex_code = $hex_code[0] . $hex_code[0] . $hex_code[1] . $hex_code[1] . $hex_code[2] . $hex_code[2];
		}
		$hex_code = array_map( 'hexdec', str_split( $hex_code, 2 ) );
		foreach ( $hex_code as &$color ) {
			$adjustable_limit = $adjust_percent < 0 ? $color : 255 - $color;
			$adjust_amount    = ceil( $adjustable_limit * $adjust_percent );

			$color = str_pad( dechex( $color + $adjust_amount ), 2, '0', STR_PAD_LEFT );
		}
		return '#' . implode( $hex_code );
	}
}

if ( ! function_exists( 'hex2rgba' ) ) {
	/**
	 * Convert a hexadecimal colour value to an rgb value with our without opacity (a)
	 *
	 * @param string     $color is the hex colour value.
	 * @param float|bool $opacity is the optional opacity value.
	 *
	 * @return string rgba value
	 */
	function hex2rgba( $color, $opacity = false ) {

	$default = 'rgb(0,0,0)';

	// Return default if no color provided.
	if ( empty( $color ) ) {
		return $default;
	}

	// Sanitize $color if "#" is provided.
		if ( '#' === $color[0] ) {
			$color = substr( $color, 1 );
		}

		// Check if color has 6 or 3 characters and get values.
		if ( 6 === strlen( $color ) ) {
				$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( 3 === strlen( $color ) ) {
				$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
				return $default;
		}

		// Convert hexadec to rgb.
		$rgb = array_map( 'hexdec', $hex );

		// Check if opacity is set(rgba or rgb).
		if ( isset( $opacity ) ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		// Return rgb(a) color string.
		return $output;
	}
}

