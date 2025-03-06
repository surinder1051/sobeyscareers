<?php
/**
 * Remove Comments
 *
 * @package fp-foundation
 */

/**
 * Remove the comments admin screen.
 */
add_action(
	'admin_menu',
	function () {
		remove_menu_page( 'edit-comments.php' );
	}
);

/**
 * Remove comments from posts and pages.
 */
add_action(
	'init',
	function () {
		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'page', 'comments' );
	},
	100
);

/**
 * Remove comments from the admin toolbar.
 */
add_action(
	'wp_before_admin_bar_render',
	function () {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}
);
