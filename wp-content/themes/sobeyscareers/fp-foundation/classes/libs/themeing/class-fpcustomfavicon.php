<?php
/**
 * Favicon Head Scripts
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'FpCustomFavicon' ) ) {
	/**
	 * Custom Favicon implmentation using theme options as source
	 */
	class FpCustomFavicon {

		/**
		 * Call the wp_head hook to add favicon links
		 *
		 * @see self::setup_favicon()
		 */
		public function __construct() {
			add_action( 'wp_head', array( $this, 'setup_favicon' ), 1 );
			add_action( 'admin_head', array( $this, 'setup_favicon' ) );
		}

		/**
		 * Get the favicon ACF option data and create head links.
		 */
		public function setup_favicon() {
			$favicon = ( function_exists( 'get_field' ) ) ? get_field( 'favicon', 'option' ) : array();

			if ( isset( $favicon['favicon_196'] ) && ! empty( $favicon['favicon_196'] ) ) :
				?>
				<link rel="apple-touch-icon" sizes="196x196" href="<?php echo esc_url( $favicon['favicon_196'] ); ?>">
				<?php
			endif;

			if ( isset( $favicon['favicon_16'] ) && ! empty( $favicon['favicon_16'] ) ) :
				?>
				<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $favicon['favicon_16'] ); ?>">
				<?php
			endif;

			if ( isset( $favicon['favicon_32'] ) && ! empty( $favicon['favicon_32'] ) ) :
				?>
				<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $favicon['favicon_32'] ); ?>">
				<?php
			endif;

		}

	}
	new FpCustomFavicon();
}
