<?php
/**
 * Cleanup Profile
 *
 * @package fp-foundation.
 */

if ( ! function_exists( 'admin_colour_scheme' ) ) {
	/**
	 * Remove the ability to change the admin colour theme.
	 *
	 * @return void
	 */
	function admin_colour_scheme() {
		global $_wp_admin_css_colors;
		$_wp_admin_css_colors = 0; //phpcs:ignore
	}
	add_action( 'admin_head', 'admin_colour_scheme' );
}

/**
 * Remove the personal options html from the profile page.
 *
 * @param string $subject is the source html.
 *
 * @return string
 */
add_action(
	'admin_head',
	function () {
		ob_start(
			function ( $subject ) {

				$subject = preg_replace( '#<h[0-9]>' . __( 'Personal Options', 'fp' ) . '</h[0-9]>.+?/table>#s', '', $subject, 1 );
				return $subject;
			}
		);
	}
);

/**
 * Flush the output buffer from the function above
 *
 * @return void
 */
add_action(
	'admin_footer',
	function () {
		ob_end_flush();
	}
);
