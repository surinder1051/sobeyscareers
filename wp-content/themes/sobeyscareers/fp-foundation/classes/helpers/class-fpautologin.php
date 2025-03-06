<?php
/**
 * FP Auto Login
 * Should only be used on local.
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'FPAutoLogin' ) ) {
	/**
	 * Set up an autologin via WP Config and use WP CLI to login.
	 */
	class FPAutoLogin {
		/**
		 * Add hooks if auto login is enabled.
		 *
		 * Constants required: AUTO_LOGIN_ENABLE(true), AUTO_LOGIN_USER, AUTO_LOGIN_PASS
		 *
		 * @see self::set_current_user()
		 *
		 * @return void
		 */
		public function __construct() {
			if ( defined( 'AUTO_LOGIN_ENABLE' ) && AUTO_LOGIN_ENABLE ) {
				add_action( 'init', array( $this, 'set_current_user' ) );
			}
		}

		/**
		 * Set current user session.
		 *
		 * @throws Exception If constants AUTO_LOGIN_USER, AUTO_LOGIN_Pass don't exist when auto login is enabled.
		 */
		public function set_current_user() {
			if ( ! is_admin() || is_user_logged_in() ) {
				return;
			}

			if ( isset( $_SERVER['REQUEST_URI'] ) && '/' !== $_SERVER['REQUEST_URI'] ) {
				return;
			}

			if ( is_user_logged_in() ) {
				return;
			}

			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				return;
			}

			if ( ! defined( 'AUTO_LOGIN_USER' ) ) {
				throw new Exception( 'AUTO_LOGIN_USER missing from wp-config', 1 );
			}

			if ( ! defined( 'AUTO_LOGIN_PASS' ) ) {
				throw new Exception( 'AUTO_LOGIN_PASS missing from wp-config', 1 );
			}

			$creds                  = array();
			$creds['user_login']    = AUTO_LOGIN_USER;
			$creds['user_password'] = AUTO_LOGIN_PASS;
			$creds['remember']      = true;
			$user                   = wp_signon( $creds );

			if ( is_wp_error( $user ) ) {
				echo esc_attr( $user->get_error_message() );
			}

			wp_safe_redirect( esc_url( get_admin_url() ) );
			exit;
		}
	}

	new FPAutoLogin();
}
