<?php
/**
 * Searchable Post Types
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'searchfilter' ) ) {
	/**
	 * Only allow for these post types to be searchable.
	 * Searchable post_types is set in config.json
	 *
	 * @param object $query is the WP query object.
	 *
	 * @return object
	 */
	function searchfilter( $query ) {
		if ( $query->is_search && ! is_admin() ) {
			global $fp_searchable_post_types;
			$query->set( 'post_type', array( $fp_searchable_post_types ) );
		}

		return $query;
	}

	add_filter( 'pre_get_posts', 'searchfilter' );
}


/**
 * FacetWP needs default search post types query vars set in order to match ajax subsequent calls vs inital preloaded.
 */
add_filter(
	'facetwp_query_args',
	function( $query_args, $facet ) {

		global $fp_searchable_post_types;
		$query_args['post_type'] = $fp_searchable_post_types;
		return $query_args;

	},
	10,
	2
);
