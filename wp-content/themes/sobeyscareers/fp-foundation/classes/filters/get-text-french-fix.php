<?php
/**
 * Multi-language site filters.
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( is_plugin_active( 'polylang-pro/polylang.php' ) && is_plugin_active( 'my-wp-translate/my-wp-translate.php' ) ) {
	// This filter helps us to get two languages out of My WP Translate, it disables translations for the default site.
	add_filter(
		'gettext',
		function( $translated_text, $text, $domain ) {
			$locale = get_locale();
			if ( 'fr_CA' === $locale || ( isset( $_POST['lang'] ) && 'fr' === $_POST['lang'] ) ) { // phpcs:ignore.
				return $translated_text;
			}
			return $text;
		},
		31,
		3
	);
}
