<?php
/*
Plugin Name: FlowPress Rest API Performance
Plugin URI: http://www.flowpress.com/
Description: Disables plugins on select FP Rest API requests to speed up response times.
Version: 1.0
Author: FlowPress Inc.
Author URI: http://www.flowpress.com/
*/

// Look at request URI to determine when to use the plugin filter
$current_url = $_SERVER['REQUEST_URI'];

$url_fragments = array(
	// Used in Sobeys Flyer PLugin
	'/sobeys-rest-api/',
	// Used in Store Locator Plugin
	'/storelocator/v1/',
	'/general-store/',
	'/wp-json/wp/v2/store/',
);

if ( fp_strpos_any( $current_url, $url_fragments ) ) {
	add_filter( 'option_active_plugins', 'fp_api_request_disable_plugin' );
}

/**
 * Search a haystack for x needles and return true if any are found
 *
 * @param string  $haystack
 * @param array   $needles
 * @param integer $offset
 * @return boolean
 */
function fp_strpos_any( string $haystack, array $needles, int $offset = 0 ): bool {
	foreach ( $needles as $needle ) {
		if ( strpos( $haystack, $needle, $offset ) !== false ) {
			return true; // stop on first true result
		}
	}
	return false;
}

/**
 * The list of plugins to exclude when making certain REST API requests
 *
 * @param array $plugins
 * @return array The filtered list of plugins that can be active for the request.
 */
function fp_api_request_disable_plugin( $plugins ) {

	// Default is to use a short whitelist and prevent most plugins from loading during requests.
	$use_whitelist = true;

	// If using blacklist.
	// Blacklist can be used to debug plugins that may need to be loaded.
	if ( false === $use_whitelist ) {
		$blacklist = array(
			'polylang-pro/polylang.php',
			'admin-columns-pro/admin-columns-pro.php',
			'advanced-custom-fields-pro/acf.php',
			'bb-theme-builder/bb-theme-builder.php',
			'bffa-recipe-like/bffa_recipe_like.php',
			'classic-editor/classic-editor.php',
			'coblocks/class-coblocks.php',
			'cookie-policy/cookie-policy.php',
			'facetwp-beaver-builder/facetwp-beaver-builder.php',
			'facetwp/index.php',
			'formidable-pro/formidable-pro.php',
			'formidable/formidable.php',
			'fp-bb-timeline-module/fp-bb-timeline-module.php',
			'fp-new-relic-browser/fp-new-relic-browser.php',
			'fp-post-importer/fp-post-importer.php',
			'fp-post-regionalization/fp-post-regionalization.php',
			'fp-slider/fp-slider.php',
			'gmo-banner-integration/gmo-banner-integration.php',
			'google-document-embedder/gviewer.php',
			'gtm-tag-manager/gtm_tag_manager.php',
			'mailgun/mailgun.php',
			'regenerate-thumbnails/regenerate-thumbnails.php',
			'roast-calculator/roast_calculator.php',
			'sobeys-cooking-classes/sobeys_cooking_classes.php',
			'sobeys-feeds/sobeys-feeds.php',
			'sobeys-food-alerts/sobeys-food-alerts.php',
			'sobeys-grilling-quiz/sobeys_grilling_quiz.php',
			'sobeys-image-cleanup/sobeys-image-cleanup.php',
			'sobeys-lightbox-popup/sobeys-lightbox-popup.php',
			'sobeys-quiz/sobeys-quiz.php',
			'sobeys-ratings/sobeys-ratings.php',
			'sobeys-ready-for-you/sobeys-ready-for-you.php',
			'sobeys-recipe-schema/sobeys-recipe-schema.php',
			'sobeys-region-holiday-feed/sobeys-region-holiday-feed.php',
			'sobeys-sf-enquiry-form/sobeys-sf-enquiry-form.php',
			'sobeys-sso-client/sobeys-sso-client.php',
			'sobeys-store-importer/sobeys-store-importer.php',
			'sobeys-subscribe/sobeys-subscribe.php',
			'sobeys-wpcontent-client/plugin-sobeys-wpcontent-client.php',
			'tru-advent-calendar/tru-advent-calendar.php',
			'tru-custom-bags/tru-custom-bags.php',
			'tru-grilling-quiz/tru-grilling-quiz.php',
			'video-thumbnails-pro/video-thumbnails-pro.php',
			'video-thumbnails/video-thumbnails.php',
			'wordpress-importer/wordpress-importer.php',
			'wordpress-seo-premium/wp-seo-premium.php',
			'wordpress-seo/wp-seo.php',
			'wp-crontrol/wp-crontrol.php',
			'wp-migrate-db-pro/wp-migrate-db-pro.php',
		);

		foreach ( $blacklist as $plugin ) {
			$key = array_search( $plugin, $plugins );
			if ( false !== $key ) {
				unset( $plugins[ $key ] );
			}
		}

		$plugins = array_values( $plugins );
	
	} else {
		// Using Whitelist.
		// Whitelist was tested as the minimum plugins to load generally to prevent errors.
		$plugins = array(
			// Required or 500 server error.
			'bb-plugin/fl-builder.php',
			'bb-regionalization/bb-regionalization.php',
			// Required for flyer calls.
			'sobeys-flyer/sobeys-flipp-flyer.php',
			// Required for store locator calls.
			'sobeys-store-locator/sobeys-store-locator.php',
			'advanced-custom-fields-pro/acf.php',
			'polylang-pro/polylang.php'
		);
	}

	return $plugins;
}