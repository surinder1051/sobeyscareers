<?php
/**
 * Add some content shortcodes
 *
 * @package fp-foundation
 */

/**
 * Add the current year.
 *
 * @return string
 */
add_shortcode(
	'year',
	function () {
		return date( 'Y' ); //phpcs:ignore
	}
);

/**
 * Wrap the search terms in a span element.
 *
 * @return string
 */
add_shortcode(
	'search_string',
	function() {
		if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) { //phpcs:ignore
			return '<span class="search_string">' . esc_attr( $_GET['s'] ) . '</span>'; //phpcs:ignore
		}
		return '';
	}
);
