<?php
/**
 * Remove Admin Bar
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'remove_admin_bar' ) ) {
	/**
	 * Remove the admin bar if a user role isn't an administrator OR not in a constant: ALLOWED_ADMIN_BAR_ROLES
	 *
	 * @return void
	 */
	function remove_admin_bar() {
		$user = wp_get_current_user();

		if ( defined( 'ALLOWED_ADMIN_BAR_ROLES' ) ) {
			$disable = ( is_array( $user->roles ) && array_intersect( ALLOWED_ADMIN_BAR_ROLES, $user->roles ) ) ? false : true;
		} else {
			$disable = ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? false : true; //phpcs:ignore
		}

		if ( $disable && ! is_admin() ) {
			show_admin_bar( false );
		}
	}
	add_action( 'after_setup_theme', 'remove_admin_bar' );
}
