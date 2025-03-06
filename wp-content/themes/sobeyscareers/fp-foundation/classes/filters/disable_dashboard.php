<?php
/**
 * Dashboard permissions.
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'check_if_allowed_dashboard_user' ) ) {
	/**
	 * Redirect non-admins to the homepage after logging into the site.
	 * If constant ALLOWED_DASHBOARD_ROLES is defined then use allowed roles defined in that array
	 */
	function check_if_allowed_dashboard_user() {
		$user = wp_get_current_user();

		if ( defined( 'ALLOWED_DASHBOARD_ROLES' ) ) {
			$redirect = ( is_array( $user->roles ) && array_intersect( ALLOWED_DASHBOARD_ROLES, $user->roles ) ) ? false : true;
		} else {
			$redirect = ( is_array( $user->roles ) && in_array( 'administrator', $user->roles, true ) ) ? false : true;
		}

		if ( is_admin() && $redirect && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			wp_safe_redirect( home_url() );
			exit;
		}
	}
	add_action( 'init', 'check_if_allowed_dashboard_user' );
}
