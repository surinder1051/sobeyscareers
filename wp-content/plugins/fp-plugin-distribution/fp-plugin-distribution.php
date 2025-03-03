<?php

/**
 *
 * @link              www.flowpress.com
 * @package           FP_Plugin_Distribution
 *
 * @wordpress-plugin
 * Plugin Name:       FlowPress Plugin Distribution
 * Plugin URI:        www.flowpress.com
 * Description:       Centralized distribution manager to manage and update components/fp-foundation theme and plugins from various sources.
 * Version:           1.7.0
 * Author:            Jonathan Bouganim
 * Author URI:        www.flowpress.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fp-plugin-distribution
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'FP_PLUGIN_DIST_VERSION', '1.7.0' );
define( 'FP_PLUGIN_PACKAGE_NAME', 'fp-plugin-packages' );
define( 'FP_COMPONENT_PACKAGE_NAME', 'fp-component-packages' );
define( 'FP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

$fp_env_log_debug = getenv('FP_PLUGIN_DIST_LOG_DEBUG');
if ( empty($fp_env_logger_level) ) {
	define( 'FP_PLUGIN_DIST_LOGGER_LEVEL', 1 );  // 0 for update, 1 for debug + update
} else {
	define( 'FP_PLUGIN_DIST_LOGGER_LEVEL', (int) preg_match("#true#i", $fp_env_logger_level) );  // 0 for update, 1 for debug + update
}

$fp_env_update_core = getenv('FP_PLUGIN_DIST_UPDATE_CORE');
if ( ! empty($fp_env_update_core) ) {
	define( 'FP_PLUGIN_DIST_UPDATE_CORE', (bool) ($fp_env_update_core == "true") ); 
}

if ( ! defined('FP_PLUGIN_GITHUB_TOKEN') ) {
	$fp_github_token = getenv('FP_PLUGIN_GITHUB_TOKEN');
	if ( empty($fp_github_token) ) {
		define( 'FP_PLUGIN_GITHUB_TOKEN', false );
	} else {
		define( 'FP_PLUGIN_GITHUB_TOKEN', $fp_github_token );
	}
}

if ( ! defined('FP_PLUGIN_GITHUB_USER') ) {
	$fp_github_user = getenv('FP_PLUGIN_GITHUB_USER');
	if ( empty($fp_github_user) ) {
		define( 'FP_PLUGIN_GITHUB_USER', 'flowpress' );
	} else {
		define( 'FP_PLUGIN_GITHUB_USER', $fp_github_user );
	}
}

if ( ! defined('FP_DASH_RELEASES_PATH') ) {
	define( 'FP_DASH_RELEASES_PATH', 'https://dashboard.flowpress.com/wp-content/uploads/cached-releases/' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fp-plugin-distribution-activator.php
 */
function activate_fp_plugin_distribution() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fp-plugin-distribution-activator.php';
	FP_Plugin_Distribution_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fp-plugin-distribution-deactivator.php
 */
function deactivate_fp_plugin_distribution() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fp-plugin-distribution-deactivator.php';
	FP_Plugin_Distribution_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fp_plugin_distribution' );
register_deactivation_hook( __FILE__, 'deactivate_fp_plugin_distribution' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fp-plugin-distribution.php';
require plugin_dir_path( __FILE__ ) . 'functions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fp_plugin_distribution() {

	$plugin = FP_Plugin_Distribution::getInstance();
	$plugin->run();

}
run_fp_plugin_distribution();
