<?php 

if ( defined('WP_CLI') && WP_CLI ) {

class FP_Component_Distribution_CLI extends WP_CLI_Command {
	
	var $component_updater;

	/**
	 * List all the components and statuses.
	 *
	 * ## OPTIONS
	 * 
	 * 
	 * [--format=<json>]
	 * : Output results in JSON.
	 * 
	 * [--check-update]
	 * : Force check to fetch releases to check for new versions.
	 * 
	 * [--available]
	 * : Only display results with available updates.
	 *
	 * 
	 * ## EXAMPLES
	 *
	 *     wp fp-component list
	 *
	 * @subcommand list
	 * 
	 */
	function list( $args, $assoc_args ) {
		set_time_limit ( 0 );
		
		$this->component_updater = FP_Component_Distribution::getInstance();
		$check_updates = isset( $assoc_args['check-update'] ) ? true : false;
		$available = isset( $assoc_args['available'] ) ? true : false;
		$format = !empty( $assoc_args['format'] ) ? trim($assoc_args['format']) : false;
		
		if ($format === 'json') {
			ob_start();
		}
		$components = $this->component_updater->list_components($check_updates, $available);
		
		if ($format === 'json') {
			ob_get_clean();
		}
		$formatter = new \WP_CLI\Formatter( $assoc_args, array(
			'name',
			'version',
			'update',
			'update_version'
		));	

		if (!empty($components)) {
			$formatter->display_items( $components );
		}	
	}

	/**
	 * Used to update components.
	 *
	 * ## OPTIONS
	 *
	 * [--component=<name>]
	 * : Component name to update.
	 * 
	 * [--dry-run]
	 * : Check if there's update only.
	 * 
	 * [--format=<json>]
	 * : Output results in JSON.
	 * 
	 * [--priority]
	 * : Critical plugin updates only.
	 *
	 * 
	 * ## EXAMPLES
	 *
	 *     wp fp-component update
	 * 	   wp fp-component update --dry-run
	 * 	   wp fp-component update --dry-run --priority
	 *
	 * @subcommand update
	 * 
	 */
	function update( $args, $assoc_args ) {
		set_time_limit ( 0 );
		
		$this->component_updater = FP_Component_Distribution::getInstance();
		$single_component = !empty( $assoc_args['component'] ) ? trim( $assoc_args['component'] ) : '';
		$dry_run = isset( $assoc_args['dry-run'] ) ? true : false;
		$format = !empty( $assoc_args['format'] ) ? trim($assoc_args['format']) : false;
		$critical = isset( $assoc_args[ FP_Plugin_Distribution::CRITICAL_FLAG ] );

		if ($format === 'json') {
			ob_start();
		}

		$update_list = $this->component_updater->update_components( $single_component, $dry_run );

		if ($format === 'json') {
			ob_get_clean();
		}
	
		$formatter = new \WP_CLI\Formatter( $assoc_args, array(
			'name',
			'old_version',
			'new_version',
			'status',
			'path',
		));

		if (is_null($update_list) && !empty($single_component) && ($format !== 'json')) {
			WP_CLI::warning("component {$single_component} not found.");
			exit(1);
		}

		if (!empty($update_list)) {
			$formatter->display_items( $update_list );
		} else {
			if (($format === 'json')) {
				return;
			}
			if (!empty($single_component)) {
				WP_CLI::line("No updates found for {$single_component}.");
			} else {
				WP_CLI::line("No updates found.");
			}
		}
	}

	/**
	 * Used to update components.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Check if there's update only.
	 * 
	 * 
	 * [--format=<json>]
	 * : Output results in JSON.
	 *
	 * 
	 * ## EXAMPLES
	 *
	 *     wp fp-component update-fp-foundation
	 *
	 * @subcommand update-fp-foundation
	 * 
	 */
	function update_fp_foundation( $args, $assoc_args ) {
		set_time_limit ( 0 );
		
		$this->component_updater = FP_Component_Distribution::getInstance();
		$dry_run = isset( $assoc_args['dry-run'] ) ? true : false;
		$format = !empty( $assoc_args['format'] ) ? trim($assoc_args['format']) : false;
		$critical = isset( $assoc_args[ FP_Plugin_Distribution::CRITICAL_FLAG ] );

		if ($format === 'json') {
			ob_start();
		}

		$update_list = $this->component_updater->update_fp_foundation( $dry_run );

		if ($format === 'json') {
			ob_get_clean();
		}
	
		$formatter = new \WP_CLI\Formatter( $assoc_args, array(
			'name',
			'old_version',
			'new_version',
			'status',
			'path'
		));

		if (is_null($update_list) && !empty($single_component) && ($format !== 'json')) {
			WP_CLI::warning("component {$single_component} not found.");
			exit(1);
		}

		if (!empty($update_list)) {
			$formatter->display_items( $update_list );
		} else {
			if (($format === 'json')) {
				return;
			}
			if (!empty($single_component)) {
				WP_CLI::line("No updates found for {$single_component}.");
			} else {
				WP_CLI::line("No updates found.");
			}
		}
	}
}	
WP_CLI::add_command( 'fp-component', 'FP_Component_Distribution_CLI' );

}