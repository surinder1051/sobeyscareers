<?php
/**
 * Admin Enqueue Css
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'admin_style' ) ) {
	/**
	 * Enqueue foundation admin css files.
	 */
	function admin_style() {
		wp_enqueue_style( 'admin-styles', get_template_directory_uri() . '/fp-foundation/assets/css/fp_admin.css', array(), FP_FOUNDATION_VERSION );
	}
	add_action( 'admin_enqueue_scripts', 'admin_style' );
}
