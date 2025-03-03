<?php

/**
 * Log all plugin checks and updates.
 *
 * @link       www.flowpress.com
 * @since      1.0.0
 *
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/includes
 */

/**
 * Log all plugin checks and updates.
 * *
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/includes
 * @author     Jonathan Bouganim <jonathan@flowpress.com>
 */
class FP_Plugin_Distribution_Logger {

	const FP_DIST_LOGGER_LEVEL_DEBUG = 1;
	const FP_DIST_LOGGER_LEVEL_UPDATE = 0;
	const FP_DIST_LOGGER_LEVEL_ERROR = 0;

	protected $log_filename = 'automatic-update.log';
	protected $log_file;
	protected $log_output = array();
	protected $handle;
	protected static $instance = null;

	/**
	 * Ensures this class state and log is kept throughout a run using Singleton pattern.
	 * @return [FP_Plugin_Distribution_Logger]
	 */
	public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->log_file = apply_filters( 'fp_plugin_dist_log_file', WP_CONTENT_DIR . "/{$this->log_filename}" );
		$this->handle = fopen($this->log_file, 'a');

		// Distinct String to parse
		$this->log_output[] = sprintf("***Automatic Plugin Update***");
		$this->log_output[] = sprintf("[%s]", date_i18n('c') );

		$platform = 'not-set';
		if (defined('WP_CLI') && WP_CLI) {
			$platform = 'CLI';
		} else if ( is_admin() ) {
			include_once(ABSPATH . 'wp-includes/pluggable.php');
			$current_user = function_exists('is_user_logged_in') && is_user_logged_in() ? sprintf("[user:%s]", wp_get_current_user()->user_login ) : "";
			$platform = "WP-ADMIN{$current_user}";
		}

		$this->log_output[] = sprintf("[PLATFORM:%s]",  $platform);
		
	}

	/**
	 * Append to out log_output.
	 * @param  string $message log message
	 * @param  string $level debug level - update or debug.
	 * @return bool logged or not.
	 */
	public function write_to_log( $message = '', $level = 'update' ) {
		if ( ! (FP_PLUGIN_DIST_LOGGER_LEVEL >= self::get_debug_level($level)) )
			return false;
		
		$line = sprintf("[debug-level:%s]%s", $level, $message);
		// Don't log if it's a repeat.
		foreach ( $this->log_output as $log_line ) {
			if ( $log_line == $line )
				return false;
		}

		if ($level === 'error') {
			//trigger_error( sprintf("[fp-plugin-dist]%s", $line) , E_USER_WARNING);
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				if ( !empty($_SERVER['argv']) && in_array('--format=json', $_SERVER['argv']) ) {
					return false;
				}
				printf("[fp-plugin-dist]%s" . PHP_EOL, $line);
			}
		}

		$this->log_output[] = $line;

		return true;
	}

	public function close_log() {
		$output = implode("\n", $this->log_output);
		$output .= "\n";
		//fwrite($this->handle, $output);
		if ( is_resource($this->handle) ) {
			fclose($this->handle);
		}	
	}

	public function write_to_log_file() {
		$output = implode("\n", $this->log_output);
		$output .= "\n";
		if ( is_resource($this->handle) ) {
			fwrite($this->handle, $output);
		}
		//fclose($this->handle);
	}



	/**
	 * Return debug level - update or debug
	 * @param  string $level debug-level
	 * @return int class constant for debug level
	 */
	private static function get_debug_level( $level = 'update' ) {
		if ( $level === 'update' || $level === 'error' ) {
			return self::FP_DIST_LOGGER_LEVEL_UPDATE;
		} else {
			return self::FP_DIST_LOGGER_LEVEL_DEBUG;
		}
	}

	/**
	 * Check if we are using `wp plugin update`
	 * @param  string  $cli_command [description]
	 * @return boolean              [description]
	 */
	private static function is_cli_command( $cli_command = 'update' ) {
		$is_plugin_update = false;
		
		// If we are in CLI and we want to specify a version, let's see if the releases list it
		if ( (defined('WP_CLI') && WP_CLI) ) {
			if (count( $_SERVER['argv'] ) >= 3) {
				$unit = $_SERVER['argv'][1];
				$command = $_SERVER['argv'][2];

				$is_plugin_update = (($unit === 'plugin') && ($command === $cli_command));
			}
		}

		return $is_plugin_update;
	}
	

}
