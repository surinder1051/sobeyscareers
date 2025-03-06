<?php
/**
 * Remove Customize.
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'remove_customize' ) ) {
	/**
	 * Remove the customize menu option.
	 *
	 * @return void.
	 */
	function remove_customize() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'new-content' );
		$wp_admin_bar->remove_menu( 'customize' );
		$wp_admin_bar->remove_menu( 'new_draft' );
		$wp_admin_bar->remove_menu( 'theme-options' );
		$wp_admin_bar->remove_menu( 'updates' );
		$wp_admin_bar->remove_menu( 'seopress_custom_top_level' );
	}
	add_action( 'wp_before_admin_bar_render', 'remove_customize', 1000 );
}
