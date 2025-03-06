<?php
/**
 * Filter create an acf-json folder in the theme for dynamic syncing when a new set of custom fields are created.
 *
 * @package fp-foundation
 */

add_filter(
	'acf/settings/save_json',
	function () {
		if ( ! is_dir( WP_CONTENT_DIR . '/acf-json/' ) ) {
			mkdir( WP_CONTENT_DIR . '/acf-json/' );
		}
		return WP_CONTENT_DIR . '/acf-json';
	}
);
add_filter(
	'acf/settings/load_json',
	function ( $paths ) {
		$paths[] = WP_CONTENT_DIR . '/acf-json';
		return $paths;
	}
);
