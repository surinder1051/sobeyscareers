<?php
/**
 * Plugin Name: FP Slider
 * Plugin URI:  https://github.com/FlowPress/plugin-fp-slider
 * Description: Custom slick slider implementation for FP Foundation framework
 * Version:     2.4.8
 * Author:      FlowPress
 * Author URI:  https://www.flowpress.com
 * Text Domain: FP_TD
 *
 * @package fp-slider
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'ABSPATH' ) || exit;

// setup constants.
define( 'FPSLIDER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'FPSLIDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FPSLIDER_PLUGIN_VERSION', get_plugin_data( __FILE__ )['Version'] );

require_once FPSLIDER_PLUGIN_PATH . '/class-init.php';

$init = new FpSlider\Init();
$init->run();
