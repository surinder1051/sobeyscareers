<?php
/**
 * Post Remove
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'post_remove' ) ) {
	/**
	 * Creating functions post_remove for removing menu item
	 */
	function post_remove() {
		remove_menu_page( 'edit.php' );
	}

	if ( ! defined( 'FP_ALLOW_POSTS' ) || false === FP_ALLOW_POSTS ) {
		add_action( 'admin_menu', 'post_remove' );
	}
}
