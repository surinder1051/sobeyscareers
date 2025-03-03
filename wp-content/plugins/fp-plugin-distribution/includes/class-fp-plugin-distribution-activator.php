<?php

/**
 * Fired during plugin activation
 *
 * @link       www.flowpress.com
 * @since      1.0.0
 *
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/includes
 * @author     Jonathan Bouganim <jonathan@flowpress.com>
 */
class FP_Plugin_Distribution_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( function_exists('wp_clean_plugins_cache') ) {
			wp_clean_plugins_cache(true);
		}
	}

}
