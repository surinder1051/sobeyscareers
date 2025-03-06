<?php
/**
 * Theme Image sizes
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'prefix_remove_default_images' ) ) {

	/**
	 * Remove default image sizes here so we're not creating more images than we need.
	 *
	 * @param array $sizes are the registered image sizes.
	 *
	 * @return array
	 */
	function prefix_remove_default_images( $sizes ) {
		unset( $sizes['small'] ); // 150px
		unset( $sizes['medium'] ); // 300px
		unset( $sizes['large'] ); // 1024px
		unset( $sizes['medium_large'] ); // 768px
		return $sizes;
	}
	add_filter( 'intermediate_image_sizes_advanced', 'prefix_remove_default_images' );
}
