<?php
/**
 * Track Versioning
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'TrackVersioning' ) ) {
	if ( ! class_exists( 'BBModuleUsage' ) ) {
		require 'class-bbmoduleusage.php';
	}
	if ( ! class_exists( 'TrackModuleUsage' ) ) {
		require 'class-trackmoduleusage.php';
	}
	/**
	 * Setup a cron to check the fp foundation version, and all component versions.
	 */
	class TrackVersioning extends BBModuleUsage {

		/**
		 * The wp-content folder path.
		 *
		 * @var string $output_path
		 */
		public $output_path = ABSPATH . 'wp-content/';

		/**
		 * Setup hooks
		 *
		 * @see self::generate_site_versioning()
		 * @see self::register_routes()
		 */
		public function __construct() {

			add_action( 'fp_generate_site_versioning', array( $this, 'generate_site_versioning' ) );

			if ( ! wp_next_scheduled( 'fp_generate_site_versioning' ) ) {
				wp_schedule_event( time(), 'daily', 'fp_generate_site_versioning' );
			}

			if ( isset( $_GET['generate_site_versioning'] ) ) { //phpcs:ignore
				add_action( 'init', array( $this, 'generate_site_versioning' ) );
			}

			if ( isset( $_GET['clear_fp_module_versions_usage'] ) ) { //phpcs:ignore
				delete_transient( 'fp_module_versions_usage' );
			}

			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		/**
		 * Create the REST API endpoint for generating the site versioning json output.
		 *
		 * @see generate_site_versioning()
		 */
		public function register_routes() {
			register_rest_route(
				'wp/v1',
				'/fp-versions',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'generate_site_versioning' ),
						'permission_callback' => '__return_true',
					),
				)
			);
		}

		/**
		 * Send a json encoded file to the output file defined in the constructor, and to the browser.
		 * Optional: allow the request to be made from any origin. Requires: FP_ENABLED_WILDCARD_CORS_IN_VERSION_TRACKING
		 *
		 * @see self::get_circle_config_version()
		 * @see self::get_wp_version()
		 * @see self:get_fpf_version()
		 * @see self::get_plugin_versions()
		 * @see self::get_module_versions()
		 * @see self::write_file()
		 */
		public function generate_site_versioning() {

			if ( defined( 'FP_ENABLED_WILDCARD_CORS_IN_VERSION_TRACKING' ) && FP_ENABLED_WILDCARD_CORS_IN_VERSION_TRACKING ) {
				header( 'Access-Control-Allow-Origin: *' );
			}

			$data = array();

			$data['circle']  = $this->get_circle_config_version();
			$data['wp']      = $this->get_wp_version();
			$data['fpf']     = $this->get_fpf_version();
			$data['plugins'] = $this->get_plugin_versions();
			$data['modules'] = $this->get_module_versions();

			header( 'Content-Type: application/json' );
			$data = json_encode( $data, JSON_PRETTY_PRINT ); //phpcs:ignore

			$this->write_file( $data );

			die( $data ); //phpcs:ignore
		}

		/**
		 * Write the versioning results to the path specified in the output path property.
		 *
		 * @param array $data (optional) is the version data to write to the file.
		 * @return void
		 */
		public function write_file( $data = array() ) {
			file_put_contents( $this->output_path . 'versions.json', $data ); //phpcs:ignore
			file_put_contents( $this->output_path . '.gitignore', 'versions.json' ); //phpcs:ignore
		}

		/**
		 * Get the version of circle ci from the project config file.
		 *
		 * @return string
		 */
		public function get_circle_config_version() {
			if ( file_exists( ABSPATH . '.circleci/config.yml' ) ) {
				return md5_file( ABSPATH . '.circleci/config.yml' );
			}
			return 'n/a';
		}

		/**
		 * Get the version of WP for the project.
		 *
		 * @return string
		 */
		public function get_wp_version() {
			require ABSPATH . WPINC . '/version.php';
			return $wp_version;
		}

		/**
		 * Get the version of foundation for the project.
		 *
		 * @return string
		 */
		public function get_fpf_version() {
			return FP_FOUNDATION_VERSION;
		}

		/**
		 * Get a list of all plugins and their versions for the project.
		 * Delete all descriptors except the name and the version.
		 *
		 * @return array
		 */
		public function get_plugin_versions() {

			$all_plugins    = get_plugins();
			$update_plugins = get_site_transient( 'update_plugins' );
			$active_plugins = get_option( 'active_plugins', array() );

			foreach ( $all_plugins as $key => $value ) {

				unset( $all_plugins[ $key ]['Description'] );
				unset( $all_plugins[ $key ]['Author'] );
				unset( $all_plugins[ $key ]['AuthorURI'] );
				unset( $all_plugins[ $key ]['TextDomain'] );
				unset( $all_plugins[ $key ]['DomainPath'] );
				unset( $all_plugins[ $key ]['RequiresWP'] );
				unset( $all_plugins[ $key ]['RequiresPHP'] );
				unset( $all_plugins[ $key ]['Title'] );
				unset( $all_plugins[ $key ]['AuthorName'] );
				unset( $all_plugins[ $key ]['PluginURI'] );
				unset( $all_plugins[ $key ]['Network'] );

				if ( isset( $update_plugins->response[ $key ] ) ) {
					$all_plugins[ $key ]['new_version'] = $update_plugins->response[ $key ]->new_version;
				}
				if ( in_array( $key, $active_plugins, true ) ) {
					$all_plugins[ $key ]['active'] = true;
				} else {
					$all_plugins[ $key ]['active'] = false;
				}
			}

			return $all_plugins;
		}

		/**
		 * Get a list of modules and their current versions.
		 *
		 * @return array
		 */
		public function get_module_versions() {
			global $fp_loaded_components;

			$results = array();

			// It wasn't there, so regenerate the data and save the transient.
			if ( is_array( $fp_loaded_components ) ) {
				$results = get_transient( 'fp_module_versions_usage' );
				if ( false === $results ) {
					// It wasn't there, so regenerate the data and save the transient.
					foreach ( $fp_loaded_components as $component ) {
						$component_name                        = basename( str_replace( '.php', '', $component ) );
						$module_class                          = 'fp\components\\' . $component_name;
						$module                                = new $module_class( true );
						$results[ $component_name ]['version'] = ( isset( $module->version ) ? $module->version : 'n/a' );
						$track_module_usage                    = new TrackModuleUsage();
						$results[ $component_name ]['used']    = $track_module_usage->get_module_counts( $component_name );
					}
					set_transient( 'fp_module_versions_usage', $results, 24 * 7 * HOUR_IN_SECONDS );
				}
			}
			return $results;
		}
	}
	$track_versioning = new TrackVersioning();
}
