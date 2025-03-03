<?php
/**
 * Plugin Name: Automated Password Reset
 * Plugin URI:  https://github.com/FlowPress/
 * Description: Automate password resets based on user last-login
 * Version:     1.0.0
 * Author:      FlowPress
 * Author URI:  https://www.flowpress.com
 * Text Domain: FP_TD
 *
 * @package apr
 *
 * Define these constants as Unix timestamps in wp-config.php to customize:
 * - APR_INACTIVE_CHECK: The length of time between checks for inactive users. Defaults to 7 days.
 * - APR_INACTIVE_TIMEFRAME: The length of time a user must be inactive before their password gets cycled.  Defaults to 30 days.
 */

/**
 * Schedule inactive user password cycling
 */
add_action(
	'init',
	function() {
		add_action( 'apr_inactive_user_check', 'apr_cycle_inactive_user_passwords' );

		if ( ! wp_next_scheduled( 'apr_inactive_user_check' ) ) {
			wp_schedule_event( time(), 'apr_check', 'apr_inactive_user_check' );
		}
	}
);

/**
 * Add custom cron schedule for inactive user check
 */
add_filter(
	'cron_schedules',
	function ( $schedules ) {
		$schedules['apr_check'] = array(
			'interval' => defined( 'APR_INACTIVE_CHECK' ) ? APR_INACTIVE_CHECK : WEEK_IN_SECONDS,
			'display'  => __( 'Inactive User Check' ),
		);

		return $schedules;
	},
	1
);

/**
 * Cycle inactive user passwords
 *
 * @return void
 */
function apr_cycle_inactive_user_passwords() {
	$timeframe      = time() - ( defined( 'APR_INACTIVE_TIMEFRAME' ) ? APR_INACTIVE_TIMEFRAME : MONTH_IN_SECONDS );
	$inactive_users = array();

	// Loop through all users to find inactive users.
	$users = get_users();
	foreach ( $users as $user ) {
		$last_login = 0;

		// Get user sessions and find the most recent.
		$sessions = get_user_meta( $user->ID, 'session_tokens' );
		$sessions = reset( $sessions );
		if ( ! empty( $sessions ) ) {
			foreach ( $sessions as $session ) {
				$last_login = $session['login'] > $last_login ? $session['login'] : $last_login;
			}
		}

		// Add user ID to inactive users if last login is less than the inactive timeframe.
		if ( $last_login < $timeframe ) {
			$inactive_users[] = $user->ID;
		}
	}

	// Loop through inactive users and set randomly generate passwords.
	foreach ( $inactive_users as $user_id ) {
		wp_set_password( wp_generate_password(), $user_id );
	}
}
