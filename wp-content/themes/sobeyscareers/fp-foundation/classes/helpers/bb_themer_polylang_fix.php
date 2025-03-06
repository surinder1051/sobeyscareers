<?php
/**
 * BB Themer Polylang fix.
 *
 * @package fp-foundation
 */

/**
 * This is a fix for polylang not working with BB themer, translated templates don't propery load.
 *
 * @param string $query is the current wp query statement.
 *
 * @return string $query
 */
add_filter(
	'query',
	function ( $query ) {

	if ( false !== strpos( $query, "WHERE pm.meta_key = '_fl_theme_builder_locations" ) ) {
		global $polylang;
		if ( ! isset( $polylang ) ) {
			return $query;
		}
		$available_lang = $polylang->model->get_languages_list();

		$fr_term_id = null;

		$current_lang = pll_current_language();

		foreach ( $available_lang as $lang ) {
			// This has to be secondary lang slug.
			if ( $lang->slug === $current_lang ) {
				$fr_term_id = $lang->term_id;
			}
		}
		if ( $fr_term_id ) {
			$query = str_replace( 'INNER JOIN', 'JOIN wp_term_relationships ON pm.post_id = object_id INNER JOIN', $query );
			$query = str_replace( 'WHERE', "WHERE wp_term_relationships.term_taxonomy_id IN ($fr_term_id) AND", $query );
		}
	}
	return $query;
	},
	10,
	1
);
