<?php
/**
 * Generate Plugin Packages CLI
 *
 * @package fp-foundation
 *
 * @since 1.8.71
 */

if ( ! class_exists( 'GeneratePluginPackagesCLI' ) ) {
	if ( ! class_exists( 'GeneratePluginPackages' ) ) {
		require trailingslashit( __DIR__ ) . 'class-generatepluginpackages.php';
	}
	/**
	 * Use CLI to run the Generate Plugin Packages hook.
	 */
	class GeneratePluginPackagesCLI {

		/**
		 * Check that the github token is set.
		 *
		 * @see GeneratePluginPackages::update_plugin_packages()
		 * @return void
		 */
		public function generate_plugin_packages() {
			if ( ! defined( 'FP_PLUGIN_GITHUB_TOKEN' ) || empty( FP_PLUGIN_GITHUB_TOKEN ) ) {
				WP_CLI::error( 'Error: Missing FP_PLUGIN_GITHUB_TOKEN constant in wp-config.php (in 1Pass)' );
				return;
			}

			do_action( 'update_plugin_packages' );

		}

	}

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::add_command( 'fpf', 'GeneratePluginPackagesCLI' );
	}
}
