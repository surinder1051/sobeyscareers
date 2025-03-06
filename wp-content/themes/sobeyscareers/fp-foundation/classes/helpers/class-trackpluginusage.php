<?php
/**
 * Track Plugin Usage
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'TrackpluginUsage' ) ) {

	/**
	 * Track which plugins are activated and what the current version is.
	 * Can be run on a schedule or using ?track_plugin_usage=1
	 */
	class TrackpluginUsage {

		/**
		 * Query plugins, status and version and send it to the FP dashboard via REST API.
		 */
		public function track() {
			$all_plugins = get_plugins();

			$update_plugins = get_site_transient( 'update_plugins' );
			$active_plugins = get_option( 'active_plugins', array() );

			foreach ( $all_plugins as $key => $value ) {
				if ( isset( $update_plugins->response[ $key ] ) ) {
					$all_plugins[ $key ]['new_version'] = $update_plugins->response[ $key ]->new_version;
				}
				if ( in_array( $key, $active_plugins, true ) ) {
					$all_plugins[ $key ]['active'] = true;
				} else {
					$all_plugins[ $key ]['active'] = false;
				}
			}

			require ABSPATH . WPINC . '/version.php';
			$url = 'https://dashboard.v2.flowpress.com/wp-json/wp/v1/track-usage-plugin';

			$response = wp_remote_post(
				$url,
				array(
					'method'      => 'POST',
					'timeout'     => 600,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'sslverify'   => false,
					'headers'     => array(),
					'body'        => array(
						'url'         => get_site_url(),
						'results'     => $all_plugins,
						'fpf_version' => defined( 'FP_FOUNDATION_VERSION' ) ? FP_FOUNDATION_VERSION : 'n/a',
						'wp_version'  => $wp_version,
					),
				)
			);

			if ( isset( $_GET['track_plugin_usage'] ) ) { //phpcs:ignore

				echo wp_kses( '<h1>Sending results data to dashboard.</h1>', 'post' );

				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					echo wp_kses( 'Something went wrong: ' . $error_message, 'post' );
				} else {
					echo wp_kses( '<h2>Response:</h2>', 'post' );
					echo wp_kses( $response['body'], 'post' );
				}

				die();
			}

		}
	}
	$track_plugin_usage = new TrackpluginUsage();

	if ( isset( $_GET['track_plugin_usage'] ) ) { //phpcs:ignore
		add_action( 'init', array( $track_plugin_usage, 'track' ) );
	}

	/**
	 * Turning off for now
	 * // Schedule an action if it's not already scheduled
	 * if ( ! wp_next_scheduled( 'run_track_plugin_usage' ) ) {
	 * wp_schedule_event( time(), 'daily', 'run_track_plugin_usage' );
	 * }
	 */

	// Hook into that action that'll fire every six hours.
	add_action( 'run_track_plugin_usage', array( $track_plugin_usage, 'track' ) );
	do_action( 'add_item_fp_menu', 'Track plugin Usage', '?track_plugin_usage=1' );
}
