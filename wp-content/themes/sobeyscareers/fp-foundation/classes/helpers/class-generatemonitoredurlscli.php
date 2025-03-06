<?php
/**
 * Generate Monitored URLs CLI
 *
 * @package fp-foundation
 * @since 1.8.71
 */

namespace fp;

use WP_CLI; //phpcs:ignore

if ( ! class_exists( 'fp\GenerateMonitoredUrls' ) ) {
	require trailingslashit( __DIR__ ) . 'class-generatemonitoredurls.php';
}

if ( ! class_exists( 'GenerateMonitoredUrlsCli' ) ) {
	/**
	 * Generate monitored URLS using WP Cli
	 */
	class GenerateMonitoredUrlsCli {

		/**
		 * Check for the github token constant, and if found run the generate_monitored_urls action hook.
		 *
		 * @return void
		 */
		public function generate_monitored_urls() {
			if ( empty( FP_PLUGIN_GITHUB_TOKEN ) ) {
				WP_CLI::error( 'Error: Missing FP_PLUGIN_GITHUB_TOKEN constant in wp-config.php (in 1Pass)' );
				return;
			}
			do_action( 'generate_monitored_urls' );
		}

	}

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		$generate_monitored_urls_cli = new GenerateMonitoredUrlsCli();
		WP_CLI::add_command( 'fpf', $generate_monitored_urls_cli );
	}
}
