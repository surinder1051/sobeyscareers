<?php
/**
 * Frontend Style
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'frontend_style' ) ) {
	/**
	 * Enqueue some default styling for facetwp
	 */
	function frontend_style() {
		wp_enqueue_style( 'frontend-styles', get_template_directory_uri() . '/fp-foundation/assets/css/fp_frontend.css', array(), FP_FOUNDATION_VERSION );
	}
	add_action( 'wp_enqueue_scripts', 'frontend_style' );
}
