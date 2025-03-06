<?php
/**
 * Helper functions for displaying and working with SVGs.
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'fp_svg_check_filetype' ) ) {
	/**
	 * Enable custom mime types needs this filter check.
	 *
	 * @param array  $data is the meta data about the file being checked.
	 * @param string $file is the full path to the file in the temp directory.
	 * @param string $filename of the original uploaded file.
	 * @param array  $mimes are the allowed mime types.
	 *
	 * @return array
	 */
	function fp_svg_check_filetype( $data, $file, $filename, $mimes ) {

		$filetype = wp_check_filetype( $filename, $mimes );

		return array(
			'ext'             => $filetype['ext'],
			'type'            => $filetype['type'],
			'proper_filename' => $data['proper_filename'],
		);

	}
	add_filter( 'wp_check_filetype_and_ext', 'fp_svg_check_filetype', 10, 4 );
}

if ( ! function_exists( 'fp_custom_mime_types' ) ) {
	/**
	 * Enable custom mime types.
	 *
	 * @param array $mimes Current allowed mime types.
	 * @return array Updated allowed mime types.
	 */
	function fp_custom_mime_types( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;

	}
	add_filter( 'upload_mimes', 'fp_custom_mime_types' );
}

if ( ! function_exists( 'fp_display_svg' ) ) {
	/**
	 * Create shortcode for SVG.
	 * Usage [svg icon="facebook-square" title="facebook" desc="like us on facebook" fill="#000000" height="20px" width="20px"].
	 *
	 * @param array $args The parameters needed to display the SVG.
	 */
	function fp_display_svg( $args = array() ) {

		// Make sure $args are an array.
		if ( empty( $args ) ) {
			return esc_html__( 'Please define default parameters in the form of an array.', 'fp-foundation' );
		}

		// Define an icon.
		if ( false === array_key_exists( 'icon', $args ) ) {
			return esc_html__( 'Please define an SVG icon filename.', 'fp-foundation' );
		}

		// Set defaults.
		$defaults = array(
			'icon'   => '',
			'title'  => '',
			'desc'   => '',
			'fill'   => '',
			'height' => '',
			'width'  => '',
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		// Figure out which title to use.
		$title = ( $args['title'] ) ? $args['title'] : $args['icon'];

		// Generate random IDs for the title and description.
		$random_number = wp_rand( 0, 99999 );
		$title_id      = 'title-' . sanitize_title( $title ) . '-' . $random_number;
		$desc_id       = 'desc-' . sanitize_title( $title ) . '-' . $random_number;

		// Set ARIA.
		$aria_hidden     = ' aria-hidden="true"';
		$aria_labelledby = '';
		if ( $args['title'] && $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="' . $title_id . ' ' . $desc_id . '"';
			$aria_hidden     = '';
		}

		// Set SVG parameters.
		$fill   = ( $args['fill'] ) ? ' fill="' . $args['fill'] . '"' : '';
		$height = ( $args['height'] ) ? ' height="' . $args['height'] . '"' : '';
		$width  = ( $args['width'] ) ? ' width="' . $args['width'] . '"' : '';

		// Start a buffer.
		ob_start();
		?>
		<svg class="icon icon-<?php echo esc_attr( $args['icon'] ); ?>"
		<?php
		echo force_balance_tags( $height ); // phpcs:ignore.
		echo force_balance_tags( $width ); // phpcs:ignore.
		echo force_balance_tags( $fill ); // phpcs:ignore.
		echo force_balance_tags( $aria_hidden ); // phpcs:ignore.
		echo force_balance_tags( $aria_labelledby ); //phpcs:ignore.
		?>
		>
			<title id="<?php echo esc_attr( $title_id ); ?>">
				<?php echo esc_html( $title ); ?>
			</title>
			<?php
				// Display description if available.
				if ( $args['desc'] ) :
					?>
				<desc id="<?php echo esc_attr( $desc_id ); ?>">
					<?php echo esc_html( $args['desc'] ); ?>
				</desc>
			<?php endif; ?>

			<?php
				// Use absolute path in the Customizer so that icons show up in there.
				if ( is_customize_preview() ) :
				?>
				<use xlink:href="<?php echo esc_url( get_parent_theme_file_uri( '/assets/images/svg-icons.svg#icon-' . esc_html( $args['icon'] ) ) ); ?>"></use>
			<?php else : ?>
				<use xlink:href="#icon-<?php echo esc_html( $args['icon'] ); ?>"></use>
			<?php endif; ?>
		</svg>

	<?php
		// Get the buffer and echo.
		echo ob_get_clean(); //phpcs:ignore.
	}
	add_shortcode( 'svg', 'fp_display_svg' );
}
