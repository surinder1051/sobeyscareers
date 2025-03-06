<?php
/**
 * FacetWP Helper
 *
 * @package fp-foundation
 */

if ( defined( 'LOAD_JS_FACETWP_HELPER.JS' ) ) {
	/**
	 * Set some localization vars for the facetwp page for translation.
	 */
	function localize_facetwp_translations() {
		$aria_label = array( 'label' => __( 'Go to page', FP_TD ) ); //phpcs:ignore
		wp_localize_script( 'LOAD_JS_FACETWP_HELPER.JS', 'fwp_ariaLabel', $aria_label );
	}

	add_action( 'wp_enqueue_scripts', 'localize_facetwp_translations' );
}

/**
 * Add a label for accessibility, but hide it from standard view.
 *
 * @param string $html is the facetwp generated html for the sort facet.
 * @param array  $params are the properties of the facet.
 */
add_filter(
	'facetwp_sort_html',
	function( $html, $params ) {
		$html = '<label class="visuallyhidden screen-reader-text">' . __( 'Sort By', FP_TD ) . '</label>' . $html; //phpcs:ignore
		return $html;
	},
	10,
	2
);
