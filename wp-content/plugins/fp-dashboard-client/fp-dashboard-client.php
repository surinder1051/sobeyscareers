<?php

// Plugin Name: Flowpress Dashboard Client
// Plugin URI: http://flowpress.ca/
// Description: FP Dashboard Client
// Version: 0.1.7
// Author: Mario Dabek, Flowpress
// Requires at least: 3.5
// Tested up to: 4.9
// Stable tag: 4.9
//
// API calls
// ------------
// site_url
// wp_version
// object_cache
// https
// multisite
// theme_name
// theme_version
// theme_directory
// parent_theme_name
// templates
// admins
// number_of_users
// number_of_comments
// taxonomies
// number_of_taxonomies
// number_of_terms
// post_types
// number_of_post_types
// db_tables
// number_of_db_tables
// flowpress_tools_installed
// number_of_uploads
// uploads_dir_size
// memory
// memory_used
// thumbnails
// thumbnail_size_count
// performance_admin_render_time
// performance_admin_number_of_queries
// performance_homepage_render_time
// performance_homepage_number_of_queries
// phpversion
// active_users
// get_new_comments
// website_size

// Constants
// FP_UPDATE_CHECKER_LOG
// FP_TRACK_LAST_LOGIN

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'fp-dashboard-security.php';
require_once 'fp-dashboard-api.php';
require_once 'fp-dashboard-data.php';
require_once 'fp-dashboard-custom-queries.php';

class fp_client {

	var $user_name     = 'fp_client';
	var $user_email    = 'fp_client@flowpress.com';
	var $user_password = '';

	function __construct() {

		add_action( 'admin_notices', array( $this, 'no_pass_defined' ) );

		if ( defined( 'FP_DASHBOARD_CLIENT_USERNAME' ) ) {
			$this->user_name = FP_DASHBOARD_CLIENT_USERNAME;
		}

		if ( defined( 'FP_DASHBOARD_CLIENT_USEREMAIL' ) ) {
			$this->user_email = FP_DASHBOARD_CLIENT_USEREMAIL;
		}

		if ( defined( 'FP_DASHBOARD_CLIENT_PASSWORD' ) ) {
			$this->user_password = FP_DASHBOARD_CLIENT_PASSWORD;
		}

		if ( defined( 'FP_DASHBOARD_CLIENT_PASSWORD' ) ) {
			add_action( 'admin_notices', array( $this, 'check_fp_user' ) );
		}

		if ( ! defined( 'FP_TRACK_LAST_LOGIN' ) ) {
			define( 'FP_TRACK_LAST_LOGIN', true );
		}
		if ( ! defined( 'FP_UPDATE_CHECKER_LOG' ) ) {
			define( 'FP_UPDATE_CHECKER_LOG', false );
		}

		$this->hooks();

	}

	function hooks() {

		$fp_client_security = new fp_client_security();
		$fp_client_api      = new fp_client_api();
		$fp_client_data     = new fp_client_data();

		add_action( 'rest_api_init', array( $fp_client_api, 'api_endpoint' ) );

		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'admin_init', array( $this, 'custom_queries_init' ) );

		add_filter( 'determine_current_user', array( $fp_client_security, 'json_basic_auth_handler' ), 20 );
		add_filter( 'rest_authentication_errors', array( $fp_client_security, 'json_basic_auth_error' ) );

		register_activation_hook( __FILE__, array( $this, 'activated' ) );
	}

	// Register required user

	function check_fp_user() {

		if ( isset( $_GET['create_user'] ) ) {
			$uid = $this->create_user();
		}

		if ( ! $this->user_exists() ) {
			?>
		<div class="notice notice-warning">
			<p>FP Dashboard Client user does not exist, click here to <a href="/wp-admin/options-general.php?page=<?php echo basename( __FILE__ ); ?>%2Ffp-dashboard-client.php&create_user">create user.</a></p>
		</div>
			<?php
		}
	}

	function user_exists() {
		$uid = get_user_by( 'slug', $this->user_name );
		return $uid;
	}

	function no_pass_defined() {

		if ( ! $this->user_exists() ) {

			if ( ! defined( 'FP_DASHBOARD_CLIENT_PASSWORD' ) ) {
				?>
			<div class="notice notice-warning">
				<p>FP Dashboard Client user needs FP_DASHBOARD_CLIENT_PASSWORD to be defined in wp-config.php</p>
			</div>
				<?php
			}
		}
	}

	function activated() {

		$custom_queries_option = get_option( 'custom_queries_option' );

		if ( ! $custom_queries_option ) {
			add_option( 'custom_queries_option', '["{\"arg_1\":\"userList_example\",\"arg_2\":\"*\",\"arg_3\":\"wp_users\"}"]' );
		}

		$this->create_user();

	}

	function create_user() {

		if ( defined( 'FP_DASHBOARD_CLIENT_PASSWORD' ) ) {
			$user_id = username_exists( $this->user_name );
			if ( ! $user_id && email_exists( $this->user_email ) == false ) {
				$user_id = wp_create_user( $this->user_name, $this->user_password, $this->user_email );
			}
			return $user_id;
		}

	}

	// Register custom queries settings page

	function custom_queries_init() {
		// option_group (section-id), option_name, sanitize_callback
		register_setting( 'custom-queries-settings-section', 'custom_queries_option' );
		// id, title, callback, page
		add_settings_section(
			'custom-queries-settings-section',
			'',
			array( $this, 'fp__custom_queries_settings_callback' ),
			'fp-custom-queries-settings'
		);
		// id, title, callback, page, section, args (optional)
		add_settings_field(
			'custom_queries_option',
			'Custom Queries JSON',
			array( $this, 'cq_callback' ),
			'fp-custom-queries-settings',
			'custom-queries-settings-section'
		);
	}

	function cq_callback() {
		$options = esc_attr( get_option( 'custom_queries_option' ) );
		?>
		<textarea cols='40' rows='5' name='custom_queries_option'><?php echo $options; ?></textarea>
		<?php
	}

	function fp__custom_queries_settings_callback() {
		echo 'Setup Custom Queries for FP Dashboard Client API Calls';
	}

	// Enqueue the plugin scripts & CSS for settings page

	function enqueue_scripts() {
		wp_enqueue_style( 'fp_dashboard_client', plugins_url( '/fp-dashboard-client.css', __FILE__ ), array() );
		wp_enqueue_script( 'fp_dashboard_client_js', plugins_url( '/fp-dashboard-client.js', __FILE__ ), array( 'jquery' ) );
	}

	// Add plugin settings page to admin Settings Menu

	function setup_menu() {
		$fp_client_custom_queries = new fp_client_custom_queries();
		add_options_page( 'FP Dashboard Client', 'FP Dashboard Client', 'manage_options', __FILE__, array( $fp_client_custom_queries, 'settings_page' ) );
		$this->enqueue_scripts();
	}

}

new fp_client();

// Tracking Users Last Login
require_once 'fp-last-login.php';

// Tracking last WordPress core or plugin update date
require_once 'fp-update-checker.php';
