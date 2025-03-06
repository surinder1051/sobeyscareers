<?php //phpcs:ignore
/**
 * WP Engine caching functions.
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'Wpe_Shortcuts' ) ) {
	/**
	 * Add links to the admin menu bar to WPE site functions such as clearing the cache.
	 */
	class Wpe_Shortcuts {

		/**
		 * Add hooks if WPECommon class exists
		 *
		 * @see self::add_wpe_cache()
		 * @see self::reset_wpe_cache()
		 * @see self::reset_wpe_cache_notice()
		 */
		public function __construct() {

			if ( ! defined( 'ABSPATH' ) ) {
				exit;
			}

			if ( class_exists( 'WpeCommon' ) ) {
				add_action( 'admin_bar_menu', array( $this, 'add_wpe_cache' ), 100 );
				add_action( 'wp_head', array( $this, 'reset_wpe_cache' ) );
				add_action( 'admin_notices', array( $this, 'reset_wpe_cache_notice' ) );
			}

		}

		/**
		 * Add WPE Reset button to admin bar
		 *
		 * @param object $wp_admin_bar is the toolbar object.
		 *
		 * @return void
		 */
		public function add_wpe_cache( $wp_admin_bar ) {

			$wp_admin_bar->add_menu(
				array(
					'id'     => 'wpse',
					'parent' => null,
					'group'  => null,
					'title'  => '<span class="ab-icon"></span>Reset <span style="font-weight:bold; color:rgb(64, 186, 200);">WPE</span> Cache',
					'href'   => '?reset_wpe_cache=true',
					'meta'   => array(
						'target' => '_self',
						'html'   => '<style>.ab-icon:before {  content: "\f515"; top: 3px; }</style>',
						'rel'    => 'friend',
					),
				)
			);
		}

		/**
		 * Reset WPE Cache if url parameter 'reset_wpe_cache' is set
		 *
		 * @return void
		 */
		public function reset_wpe_cache() {

			if ( isset( $_GET['reset_wpe_cache'] ) && ( 'true' == $_GET['reset_wpe_cache'] ) ) { //phpcs:ignore
				WpeCommon::purge_memcached();
				WpeCommon::clear_maxcdn_cache();
				WpeCommon::purge_varnish_cache();
			}

		}

		/**
		 * Show admin notice when cache is reset
		 *
		 * @return void
		 */
		public function reset_wpe_cache_notice() {

			if ( isset( $_GET['reset_wpe_cache'] ) && ( 'true' == $_GET['reset_wpe_cache'] ) ) { //phpcs:ignore
				?>
					<div class="notice notice-success is-dismissible">
						<p><?php esc_attr_e( 'WP Engine cache has been reset.', 'fp' ); ?></p>
					</div>
				<?php
			}

		}

	}

	new Wpe_Shortcuts();
}
