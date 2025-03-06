<?php
/**
 * WP Head Actions for Social Media
 *
 * Add meta tags in the header for Google Site Verfiication and FB site verification if the constants are enabled.
 *
 * @package fp-foundation
 */

add_action(
	'wp_head',
	function() {
		if ( function_exists( 'get_field' ) ) {
			$favicon = get_field( 'favicon', 'option' );
			if ( is_array( $favicon ) ) {
				if ( isset( $favicon['favicon_196'] ) && ! empty( $favicon['favicon_196'] ) ) {
					?>
					<link rel="apple-touch-icon" sizes="196x196" href="<?php echo esc_url( $favicon['favicon_196'] ); ?>">
					<?php
				}
				if ( isset( $favicon['favicon_16'] ) && ! empty( $favicon['favicon_16'] ) ) {
					?>
					<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $favicon['favicon_16'] ); ?>">
					<?php
				}
				if ( isset( $favicon['favicon_32'] ) && ! empty( $favicon['favicon_32'] ) ) {
					?>
					<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $favicon['favicon_32'] ); ?>">
					<?php
				}
			}
		}
		if ( defined( 'GOOGLE_SITE_VERIFICATION' ) ) {
			echo "\n<meta name='google-site-verification' content='" . esc_attr( GOOGLE_SITE_VERIFICATION ) . "' />\n";
		}
		if ( defined( 'FACEBOOK_SITE_VERIFICATION' ) ) {
			echo "\n<meta name='facebook-domain-verification' content='" . esc_attr( FACEBOOK_SITE_VERIFICATION ) . "' />\n";
		}
	}
);
