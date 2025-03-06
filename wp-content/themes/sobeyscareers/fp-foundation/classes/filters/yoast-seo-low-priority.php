<?php
/**
 * Yoast SEO filters.
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'jw_filter_yoast_seo_metabox' ) ) {
	/**
	 *
	 * Filter Yoast SEO Metabox Priority so it's always at the bottom of the edit screen.
	 *
	 * @author Jacob Wise.
	 * @link http://swellfire.com/code/filter-yoast-seo-metabox-priority
	 *
	 * @return string;
	 */
	function jw_filter_yoast_seo_metabox() {
		return 'low';
	}
	add_filter( 'wpseo_metabox_prio', 'jw_filter_yoast_seo_metabox' );
}
