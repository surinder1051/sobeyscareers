<?php
/**
 * Helper function to enqueue browser scripts after the npm file is loaded
 *
 * @package fp-foundation
 */

add_action(
	'wp_enqueue_scripts',
	function() {
		if ( file_exists( trailingslashit( get_template_directory() ) . 'dist/js/bundled/browser-detect-combined.min.js' ) ) {
			wp_register_script( 'browser-detect-combined', trailingslashit( get_template_directory_uri() ) . 'dist/js/bundled/browser-detect-combined.min.js', array( 'jquery' ), '0.2.28', true );
			wp_enqueue_script( 'browser-detect-combined' );
			wp_dequeue_script( 'browser-detect' );
		}
	}
);
