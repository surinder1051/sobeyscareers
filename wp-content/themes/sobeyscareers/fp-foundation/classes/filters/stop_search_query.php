<?php
/**
 * Query functions.
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'mb_bail_main_wp_query' ) ) {
	/**
	 * This helper is used for FacetWP so that the original search query doesn't run when FactWP will force its own query to run.
	 *
	 * @param string          $sql is the WP query statement.
	 * @param object WP_Query $wp_query is the WP query object.
	 *
	 * @return string
	 */
	function mb_bail_main_wp_query( $sql, WP_Query $wp_query ) {
		if ( is_search() && $wp_query->is_main_query() && ! is_admin() ) {
			// Prevent SELECT FOUND_ROWS() query.
			$wp_query->query_vars['no_found_rows'] = true;

			// Prevent post term and meta cache update queries.
			$wp_query->query_vars['cache_results'] = false;

			return false;
		}
		return $sql;
	}
	add_filter( 'posts_request', 'mb_bail_main_wp_query', 10, 2 );
}
