<?php
/**
 * Action hooks and filters.
 *
 * A place to put hooks and filters that aren't necessarily template tags.
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'fp_body_classes' ) ) {
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function fp_body_classes( $classes ) {

		// Give all pages a unique class.
		if ( is_page() ) {
			$classes[] = 'page-' . basename( get_permalink() );
		}

		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Since 1.8.71.
		if ( is_multisite() ) {
			$classes[] = 'template-theme-' . get_current_blog_id();
		}

		return $classes;
	}
	add_filter( 'body_class', 'fp_body_classes' );
}
