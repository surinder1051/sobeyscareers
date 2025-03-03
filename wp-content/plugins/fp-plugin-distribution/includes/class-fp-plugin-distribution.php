<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.flowpress.com
 * @since      1.0.0
 *
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/includes
 * @author     Jonathan Bouganim <jonathan@flowpress.com>
 */
class FP_Plugin_Distribution {

	const CRITICAL_FLAG = 'priority';

	protected $is_critical = false;
	protected $critical_plugins = [];

	protected $component_manager;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      FP_Plugin_Distribution_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The admin that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      FP_Plugin_Distribution_Admin    $loader    Maintains all admin functionality.
	 */
	protected $admin;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The listing of all the packages this plugin manages.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $packages    The listing of all the packages this plugin manages.
	 */
	protected $packages;

	/**
	 * The listing of all the package settings this plugin manages.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $packages    The listing of all the package settings this plugin manages.
	 */
	protected $settings;

	/**
	 * Singleton instance.
	 *
	 * @var self
	 */
	private static $instance;

	/**
	 * Singleton get.
	 * @return object
	 */
	public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
	}

	/**
	 * Get critical status.
	 *
	 * @return boolean
	 */
	public function is_critical() {
		return apply_filters('fp_plugin_dist_is_critical', $this->is_critical);
	}

	/**
	 * Get critical plugin version.
	 *
	 * @return string|boolean
	 */
	public function get_critical_plugin( $plugin_slug = '' ) {
		return isset( $this->critical_plugins[ $plugin_slug ] )	? $this->critical_plugins[ $plugin_slug ] : false;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		self::$instance = $this;
		if ( defined( 'FP_PLUGIN_DIST_VERSION' ) ) {
			$this->version = FP_PLUGIN_DIST_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'fp-plugin-distribution';
		
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->set_locale();
		$this->setup_api_filters();

		if ( !defined( 'FP_PLUGIN_PACKAGE_NAME' ) || empty(FP_PLUGIN_PACKAGE_NAME) ) {
			$error_msg = "Constant - FP_PLUGIN_PACKAGE_NAME not defined.";
			fp_dist_log($error_msg, 'error');
			return;
		}	

		$error_msg = "Constant - FP_PLUGIN_GITHUB_TOKEN not defined.";
		if ( !defined( 'FP_PLUGIN_GITHUB_TOKEN' ) || empty(FP_PLUGIN_GITHUB_TOKEN) ) {
			$this->admin->add_admin_notice($error_msg, 'warning');
			fp_dist_log($error_msg, 'error');
			return;
		} else {
			$this->admin->delete_notice($error_msg, true);
		}

		// If we are in CLI and we want to specify a version, let's see if the releases list it
		if ( (defined('WP_CLI') && WP_CLI) ) {
			foreach ( $_SERVER['argv'] as $argument ) {
				if ( preg_match('#^\-\-'.self::CRITICAL_FLAG.'#i', $argument, $matches) ) {
					fp_dist_log("Specifying critical releases only", 'debug');
					$this->is_critical = true;
					
				} 
			}
		}

		// Fetch list of critical public plugins.
		if ( $this->is_critical() ) {
			$url = "https://dashboard.v2.flowpress.com/wp-json/wp/v2/high-priority-plugins";
			$api_url = apply_filters('fp_plugin_dist_critical_json_api_url', $url );
			$api_args = apply_filters('fp_plugin_dist_critical_json_api_args', [] );
			fp_dist_log("Specifying urgent releases only, fetching from {$api_url}", 'debug');
			$response = wp_remote_get( $api_url, $api_args );
			$critical_plugins_json = wp_remote_retrieve_body( $response );
			if (empty($critical_plugins_json)) {
				fp_dist_log("Failed to fetch critical releases from API", 'error');
			} else {
				$critical_plugins = json_decode( $critical_plugins_json, true );
				if (empty( $critical_plugins['high_priority_plugins'] )) {
					fp_dist_log("Failed to fetch critical plugin array from API", 'error');
				} else {
					$this->critical_plugins = $critical_plugins['high_priority_plugins'];
				}
			}

			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'clearTransient' ), 98, 1 );
		}
		
		$package_json = apply_filters('fp_plugin_dist_json_path', WP_CONTENT_DIR . "/" . FP_PLUGIN_PACKAGE_NAME . ".json" );
		if ( ! file_exists($package_json) ) {
			$error_msg = "Could not find plugin package manager JSON with path `{$package_json}`, attempting to create a sample...";
			fp_dist_log($error_msg, 'error');

			// Create sample JSON if it doesn't exist
			$blank_json_path = FP_PLUGIN_PATH . FP_PLUGIN_PACKAGE_NAME . '.json';
			$blank_json = file_get_contents( $blank_json_path );
			file_put_contents( $package_json, $blank_json );
			if ( ! file_exists($package_json) ) {
				$error_msg = "Could not create blank JSON in wp-content: `{$package_json}`.";
				fp_dist_log($error_msg, 'error');
				return;
			}
		}

		// Quitting if we still don't have a JSON file to read.
		$error_msg = "Could not find plugin package manager JSON with path `{$package_json}`...";
		if ( ! file_exists($package_json) ) {
			$this->admin->add_admin_notice($error_msg, 'warning');
			return;
		}
		$this->admin->delete_notice($error_msg, true);

		$this->settings = json_decode( file_get_contents( $package_json ), true );
		$validation_failed = false;
		$validation_fields = ['packages', 'platforms', 'log-core', 'name'];
		foreach($validation_fields as $validation_field) {
			if ( ! isset( $this->settings[ $validation_field ] ) ) {
				$error_msg = "Missing '{$validation_field}' field in package JSON path `{$package_json}`.";
				fp_dist_log($error_msg, 'error');
				$validation_failed[] = $validation_field;
			}
		}
		
		if (!empty($validation_failed)) {
			$fields = implode(", ", $validation_failed);
			$error_msg = "Package JSON validation failed, invalid fields: {$fields}.";
			$this->admin->add_admin_notice($error_msg, 'warning');
			return;
		} else {
			// If we are here, we are ll good.
			$this->admin->delete_all_notices();
		}
		$this->packages = $this->settings['packages'];

		add_filter( 'site_transient_update_plugins', array( $this, 'setTransientPost' ), 99 );
		//add_action('init', array($this, 'load_packages') );
		$this->load_packages();
	}

	public function setup_api_filters() {
		add_filter( 'fp_plugin_dist_releases_github_api_results', array( $this, 'setup_fp_dashboard_api_filter' ), 10, 4 );
		add_filter( 'fp_plugin_dist_releases_github_api_results', array( $this, 'setup_github_api_filter' ), 15, 4 );
	}

	/**
	 * Filter to fetch from the FP Dashboard API
	 *
	 * @param array|WP_Error $results
	 * @param string $accessToken
	 * @param string $repo_name
	 * @param string $org_name
	 * @return WP_Error|array
	 */
	public function setup_fp_dashboard_api_filter( $results = [], $accessToken = '', $repo_name = '', $org_name = '' ) {
		if ( ! defined('FP_DASH_RELEASES_PATH') ) {
			return false;
		}

		$repo_path = "{$org_name}/{$repo_name}/releases";

		$url = FP_DASH_RELEASES_PATH . sanitize_title($repo_path) . ".json";
		$args = [];

		add_filter( 'https_ssl_verify', '__return_false' );
		$response = wp_remote_get( $url, $args );
		//add_filter( 'https_ssl_verify', '__return_true' );
		
		if (is_wp_error($response)) {
			return $response;
		}
		$code = wp_remote_retrieve_response_code( $response );
		if ($code === 200) {
			$githubAPIResults = wp_remote_retrieve_body( $response );
			$results = @json_decode( $githubAPIResults );
			if (!is_array($results)) {
				return new WP_Error($code, "Invalid JSON response for $url");
			}
		} else {
			return new WP_Error($code, "cannot find releases at {$url}");
		}
		
		return $results;
	}

	/**
	 * Filter to fetch from the GitHub API
	 *
	 * @param array|WP_Error $results
	 * @param string $accessToken
	 * @param string $repo_name
	 * @param string $org_name
	 * @return WP_Error|array
	 */
	public function setup_github_api_filter( $results = [], $accessToken = '', $repo_name = '', $org_name = '' ) {
		$method_name = __FUNCTION__;
		// We don't want to fetch from GitHub API if we don't have an error and have an array.
		if (is_wp_error($results)) {
			fp_dist_log("{$method_name}: cannot fetch releases from previous call for $repo_name, response: " . $results->get_error_message(), 'error');
		}
		else if (is_array($results)) {
			return $results;
		}
		// Query the GitHub API/FP-Dasboard
		$url = "https://api.github.com/repos/{$org_name}/{$repo_name}/releases";
		$args = [];
		fp_dist_log("{$method_name}: query github, {$url}", 'debug');
		if ( ! empty( $accessToken ) )
		{	
			$args = [
				'headers' => [
					'Authorization' => "token {$accessToken}"
				]
			];
		} else {
			fp_dist_log("{$method_name}: missing GitHub AccessToken", 'debug');
		}

		$response = wp_remote_get( $url, $args );
		
		if (is_wp_error($response)) {
			fp_dist_log("{$method_name}: cannot fetch releases at {$url}, response: " . $response->get_error_message(), 'error');
			return $response;
		}
		$code = wp_remote_retrieve_response_code( $response );
		if ($code === 200) {
			$githubAPIResults = wp_remote_retrieve_body( $response );
			$results = @json_decode( $githubAPIResults );
			if (!is_array($results)) {
				return new WP_Error($code, "Invalid JSON response for $url");
			}
		} else {
			return new WP_Error($code, "cannot find releases at {$url}");
		}
		
		return $results;
	}

	/**
	 * If it's a critical flag, let's clear the saved updates and only use ones we add.
	 *
	 * @param object $transient
	 * @return object
	 */
	public function clearTransient( $transient ) {
		$method_name = __FUNCTION__;
		fp_dist_log("{$method_name} start", 'debug');
		
		if ( !$transient ) {
			return $transient;
		}

		if (isset($transient->response)) {
			$transient->response = [];
		}
		return $transient;
	}

	/**
	* Push in plugin version information to get the update notification from the post.
	*
	* @param  object $transient
	* @return object
	*/
	public function setTransientPost( $transient )
	{	
		$method_name = __FUNCTION__;
		fp_dist_log("{$method_name} start", 'debug');

		if ( !$transient ) {
			return $transient;
		}

		$value = get_transient('fp_update_plugins');
		if (empty($value)) {
			return $transient;
		}

		return $value;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - FP_Plugin_Distribution_Loader. Orchestrates the hooks of the plugin.
	 * - FP_Plugin_Distribution_i18n. Defines internationalization functionality.
	 * - FP_Plugin_Distribution_Admin. Defines all hooks for the admin area.
	 * - FP_Plugin_Distribution_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for logging all our events.
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fp-plugin-distribution-logger.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fp-plugin-distribution-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fp-plugin-distribution-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fp-plugin-distribution-admin.php';

		/**
		 * The class responsible for parsing MD markup for our lightbox plugin display.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'libs/fp-parsedown.php';

		/**
		 * The class responsible for parsing semver versioning and other composer classes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		/**
		 * The class responsible for defining all actions that occur in the core plugin update filters.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fp-plugin-distribution-manager.php';

		/**
		 * The class responsible for updating components and fp-foundation.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fp-plugin-distribution-component.php';

		/**
		 * The class responsible for extending functionality to component CLI.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fp-plugin-distribution-cli-component.php';

		/**
		 * The class responsible for extending functionality to plugin CLI.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fp-plugin-distribution-cli-plugin.php';

		$this->component_manager = FP_Component_Distribution::getInstance();
		$this->loader = Fp_Plugin_Distribution_Loader::getInstance();
		$this->admin = FP_Plugin_Distribution_Admin::getInstance();
		
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the FP_Plugin_Distribution_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new FP_Plugin_Distribution_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$this->loader->add_action( 'admin_notices', $this->admin, 'display_admin_notices' );
		$this->loader->add_action( 'wp_ajax_'.FP_Plugin_Distribution_Admin::AJAX_DISMISS_HOOK, $this->admin, 'ajax_dismiss_notice' );
		$this->loader->add_action( 'admin_notices', $this->admin, 'admin_footer_script' );
		//$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
		//$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the plugin manager functionality, only needed for the admin area.
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_packages() {
		if ( (defined('WP_CLI') && WP_CLI) && ! in_array('cli', $this->settings['platforms'] ) )
			return;

		if ( is_admin() && ! in_array('admin', $this->settings['platforms'] ) ) {
			fp_dist_log("load_packages: not defined platform, breaking...", 'error');
			return;
		}
		
		if ( ! is_admin() && ! (defined('WP_CLI') && WP_CLI) ) {
			fp_dist_log("load_packages: not defined CLI, breaking...", 'error');
			return;	
		}

		$should_update_core = defined( 'FP_PLUGIN_DIST_UPDATE_CORE' ) ? FP_PLUGIN_DIST_UPDATE_CORE : ( isset($this->settings['log-core']) && ($this->settings['log-core'] == true));
		$core_packages = array();
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$all_plugins = get_plugins();
		foreach($all_plugins as $plugin_file => $plugin) {

            // If this is an update for a specific plugin then only iterate over that plugin
            if (isset($GLOBALS['argv']) && isset($GLOBALS['argv']['1']) && $GLOBALS['argv']['1'] == 'plugin' && $GLOBALS['argv']['2'] == 'update' && isset($GLOBALS['argv']['3']) && ! preg_match('#^--#', $GLOBALS['argv']['3']) ) {
                if (strpos($plugin_file, $GLOBALS['argv']['3'] . '/') === false) {
                    continue;
                }
            }

			$plugin_parts = explode("/", $plugin_file);
			$folder_name = strtolower( $plugin_parts[0] );
			$plugin_file = strtolower( $plugin_parts[1] );
			$plugin_slug = basename($plugin_file, ".php");
			if ( $folder_name == 'fp-plugin-distribution')
				continue;
			
			if ( isset( $this->packages[ $folder_name ] ) && ($this->packages[ $folder_name ]['plugin-slug'] !== $plugin_slug) ) {
				$this->packages[ $folder_name ]['plugin-slug'] = $plugin_slug;
			}
            
			if ($should_update_core) {
				$core_packages[ $folder_name ] = array(
					'folder_name' => $folder_name,
					'plugin-slug' => $plugin_slug,
					'version' => "*.*.*",
					'access_token' => "",
					'core' => true,
					'branch' => "master",
					'auto-update' => 'true',
					'github-project-name' => "",
					'github-username' => "",
				);
			}
			
		}

		if (!empty($core_packages)) {
			$existing_packages = $this->packages;
			// JSON packages will override the core versions.
			$this->packages = array_merge( $core_packages, $existing_packages );
		}

		foreach ( $this->packages as $folder_name => $package_settings ) {

             // If this is an update for a specific plugin then only iterate over that plugin
             if (isset($GLOBALS['argv']) && isset($GLOBALS['argv']['1']) && $GLOBALS['argv']['1'] == 'plugin' && $GLOBALS['argv']['2'] == 'update' && isset($GLOBALS['argv']['3']) && ! preg_match('#^--#', $GLOBALS['argv']['3'])) {
                if (strpos($folder_name, $GLOBALS['argv']['3']) === false) {
                    continue;
                }
            }

			fp_dist_log("load_packages: loading JSON, settings for plugin: {$folder_name}...", 'debug');
			$package_settings['folder_name'] = $folder_name;
			$plugin_manager = new FP_Plugin_Distribution_Manager( $package_settings, $this );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    FP_Plugin_Distribution_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
