<?php
/**
 * Track Module Usage
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'TrackModuleUsage' ) ) {
	if ( ! class_exists( 'BBModuleUsage' ) ) {
		include 'class-bbmoduleusage.php';
	}

	/**
	 * Send tracking data to FP Dashboard.
	 */
	class TrackModuleUsage extends BBModuleUsage {

		/**
		 * Send module usage data for this site to the FP Dashboard site.
		 * Dump the response data to the screen.
		 * Send the query var: ?track_module_usage to use
		 *
		 * @see get_module_counts()
		 *
		 * @return void
		 */
		public function track() {
			global $fp_loaded_components;

			$results = get_transient( 'component_usage' );

			if ( false === $results || isset( $_GET['clear_cache'] ) ) { //phpcs:ignore
				// It wasn't there, so regenerate the data and save the transient.
				foreach ( $fp_loaded_components as $component ) {
					$component             = basename( str_replace( '.php', '', $component ) );
					$results[ $component ] = $this->get_module_counts( $component );
				}
				set_transient( 'component_usage', $results, HOUR_IN_SECONDS );
			} else {
				echo esc_attr( 'Using cached component_usage data' );
			}

			$url = 'https://dashboard.v2.flowpress.com/wp-json/wp/v1/track-usage';

			$response = wp_remote_post(
				$url,
				array(
					'method'      => 'POST',
					'timeout'     => 120,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'sslverify'   => false,
					'headers'     => array(),
					'body'        => array(
						'url'     => get_site_url(),
						'results' => $results,
						'env'     => 'dev',
					),
				)
			);

			if ( isset( $_GET['track_module_usage'] ) ) { //phpcs:ignore

				echo wp_kses( '<h1>Sending results data to dashboard.</h1>', 'post' );

				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					echo wp_kses( "Something went wrong: {$error_message}", 'post' );
				} else {
					echo wp_kses( '<h2>Response:</h2>', 'post' );
					var_export( $response ); //phpcs:ignore
					echo wp_kses( $response['body'], 'post' );
					var_export( $results ); //phpcs:ignore
				}

				die( 'Done' );
			}

		}

		/**
		 * Get a count of the number of times a module is used on the site.
		 *
		 * @param string $module is the module to query.
		 *
		 * @return array $results.
		 */
		public function get_module_counts( $module ) {
			global $wpdb;

			$module_class = 'fp\components\\' . $module;
			$module_obj   = new $module_class();

			$results = array(
				'version'     => ( isset( $module_obj->version ) ? $module_obj->version : 'n/a' ),
				'total_count' => 0,
				'pages'       => array(),
			);

			$query = "SELECT pm.`post_id`, pm.`meta_value`, p.`post_type`
			FROM {$wpdb->prefix}postmeta as pm
			LEFT JOIN `{$wpdb->prefix}posts` as p ON p.`ID` = pm.`post_id`
			WHERE (pm.`meta_value` LIKE '%\"$module\"%') AND (pm.`meta_key` = '_fl_builder_data') AND p.`ID` > 0 ANd p.`post_status` = 'publish'";

			$data = $wpdb->get_results( $query ); //phpcs:ignore

			if ( ! empty( $data ) ) {
				foreach ( $data as $row ) {

					$url   = get_permalink( $row->post_id );
					$count = substr_count( $row->meta_value, "\"$module\"" );

					if ( ! isset( $results[ 'total_count_' . $row->post_type ] ) ) {
						$results[ 'total_count_' . $row->post_type ] = 1;
					} else {
						$results[ 'total_count_' . $row->post_type ]++;
					}

					$results['total_count'] += $count;

					$results['pages'][] = array(
						'url'   => $url,
						'count' => $count,
					);

				}
			}
			return $results;
		}

	}
	$track_module_usage = new TrackModuleUsage();

	if ( isset( $_GET['track_module_usage'] ) ) { //phpcs:ignore
		add_action( 'init', array( $track_module_usage, 'track' ) );
	}

	/**
	 * Turning off for now
	 * Schedule an action if it's not already scheduled
	 * if ( ! wp_next_scheduled( 'run_track_module_usage' ) ) {
	 * wp_schedule_event( time(), 'daily', 'run_track_module_usage' );
	 * }
	 */

	// Hook into that action that'll fire every six hours.
	add_action( 'run_track_module_usage', array( $track_module_usage, 'track' ) );


	do_action( 'add_item_fp_menu', 'Track Module Usage', '?track_module_usage=1' );
}
