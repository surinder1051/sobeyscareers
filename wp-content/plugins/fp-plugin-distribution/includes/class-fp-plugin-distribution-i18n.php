<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.flowpress.com
 * @since      1.0.0
 *
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/includes
 * @author     Jonathan Bouganim <jonathan@flowpress.com>
 */
class FP_Plugin_Distribution_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fp-plugin-distribution',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
