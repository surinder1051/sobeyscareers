<?php
 /*
 * Description: Check when plugin or WP core updates are run and generate a log.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FP_Update_Checker {
	/**
	 * Current version.
	 *
	 * @var    string
	 * @since  0.1
	 */
	const VERSION = '0.2';

	/**
	 * Enable/Disable logging.
	 *
	 * @since  0.1
	 */
	const FP_UPDATE_CHECKER_LOG = null;

	/**
	 * Filename for JSON file to write/read.
	 *
	 * @var    string
	 * @since  0.1
	 */
	const FP_UPDATE_CHECKER_FILE = 'fp_update_checker.json';

	/**
	 * Constructor.
	 *
	 * @author FlowPress (DAC)
	 * @since  0.1
	 */
	public function __construct() {
		if ( defined( 'FP_UPDATE_CHECKER_LOG' ) && true === defined( 'FP_UPDATE_CHECKER_LOG' ) ) {
			$this->hooks();
		}
	}

	/**
	 * Initiate our hooks.
	 *
	 * @author FlowPress (DAC)
	 * @since  0.1.3
	 */
	public function hooks() {
		add_action( 'upgrader_process_complete', array( $this, 'updates_completed' ), 10, 2 );
	}

	/**
	 * This function runs when WordPress completes its upgrade process
	 * It iterates through each update and creates a JSON file. 
	 *
	 * @author FlowPress (DAC)
	 * @param $upgrader_object Array
	 * @param $options Array
	 * @since 0.1.3
	 */
	function updates_completed( $upgrader_object, $options ) {

        // Disabling for now... we hardly use this, might convert to API call instead
        return;

		$updates = array(
			'plugins_updated'      => 'no',
			'themes_updated'       => 'no',
			'core_updated'         => 'no',
			'translations_updated' => 'no',
			'date_last_udpated' => date( 'Y-m-d H:i:s' ),
		);

		// If an update has taken place and the updated type is plugins.
		if ( 'update' == $options['action'] && 'plugin' == $options['type']) {
			$updates['plugins_updated'] = $options['plugins'];
		} else if ( 'update' == $options['action'] && 'theme' == $options['type'] ) {
			$updates['themes_updated'] = $options['themes'];
		} else if ( 'update' == $options['action'] && 'core' == $options['type'] ) {
			$updates['core_updated'] = 'yes';
		}

		if ( 'update' == $options['action'] ) {
			switch ( $options['type'] ) {
				case 'plugin':
					$updates['plugins_updated'] = $options['plugins'];
					break;
				case 'theme':
					$updates['themes_updated'] = $options['themes'];
					break;
				case 'core':
					$updates['core_updated'] = 'yes';
					break;
				case 'translation':
					$updates['translations_updated'] = $options['translations'];
					break;
			}
		}

		global $wp_filesystem;

		// Get the directory where files should reside.
		$dir  = $wp_filesystem->find_folder( WP_CONTENT_DIR );
		// Get file to write to.
		$file = trailingslashit( $dir ) . self::FP_UPDATE_CHECKER_FILE;
		// Create JSON object from $updates array.
		$json = wp_json_encode( $updates );
		// Save JSON to file.
		$wp_filesystem->put_contents( $file, $json, FS_CHMOD_FILE );
	}
}

new FP_Update_Checker();
