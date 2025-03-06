<?php
/**
 * New Relic Backend Tracking
 * Decription: Seperate traffic in New Relic by logged in state and WP backend state.
 * Requires: New Relic extension, NR_APP_NAME constant
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'newrelic_admin' ) ) {
	/**
	 * Load monitoring for wp-admin.
	 */
	function newrelic_admin() {
		newrelic_set_appname( NR_APP_NAME . ' - /wp-admin' );
	}
}

if ( ! function_exists( 'newrelic_front' ) ) {
	/**
	 * Load monitoring on the front end.
	 */
	function newrelic_front() {
		if ( is_user_logged_in() ) {
			newrelic_set_appname( NR_APP_NAME . ' - Logged In' );
		} else {
			newrelic_set_appname( NR_APP_NAME );
		}
	}
}

if ( defined( 'NR_APP_NAME' ) && extension_loaded( 'newrelic' ) ) {
	add_action( 'admin_init', 'newrelic_admin', 1 );
	if ( ! is_admin() ) {
		// Load the action only if frontend.
		add_action( 'init', 'newrelic_front' );
	}
}
