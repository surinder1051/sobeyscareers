<?php
/**
 * New Relic 404 Patch
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'new_relic_404_patch' ) ) {
	/**
	 * This sets the url for 404 pages so New Relic can report on them.
	 */
	function new_relic_404_patch() {
		if ( is_404() ) {
			if ( function_exists( 'newrelic_add_custom_parameter' ) ) {
				$actual_link = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . ( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' );
				newrelic_add_custom_parameter( 'Actual URL', $actual_link );
			}
		}
	}
	add_action( 'template_redirect', 'new_relic_404_patch' );
}
