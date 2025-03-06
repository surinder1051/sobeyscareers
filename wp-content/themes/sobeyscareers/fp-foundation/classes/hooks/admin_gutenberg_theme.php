<?php
/**
 * Enable Gutenberg Theming
 *
 * @package fp-foundation
 */

if ( defined( 'ENABLE_GUTENBERG_THEME' ) && ENABLE_GUTENBERG_THEME ) {

	/**
	 * Add theme support for gutenberg, if enabled via functions.php with this constant.
	 */
	function fp_enable_gutenberg_themeing() {
		add_theme_support( 'disable-custom-colors' );
		add_theme_support( 'disable-custom-gradients' );

		if ( is_admin() ) {
			global $gb_colour_palette;

			$gb_colour_palette = get_option( '_fp_gutenberg_theme' );

			// We can customize this palette using the ACF theming options in the libs dir.
			if ( ! empty( $gb_colour_palette ) ) {
				add_theme_support( 'editor-color-palette', $gb_colour_palette );
			}
		}

		// Cusotmize the WP gutenberg styling with this file.
		add_theme_support( 'editor-styles' );
		add_editor_style( get_template_directory_uri() . '/dist/css/pages/gutenberg.min.css' );
	}

	add_action( 'after_setup_theme', 'fp_enable_gutenberg_themeing' );

	if ( defined( 'GUTENBERG_ALLOWED_BLOCKS' ) ) {

		/**
		 * Define which blocks are enabled. Can be default WP or custom blocks.
		 * Use this constant in functions.php: GUTENBERG_ALLOWED_BLOCKS
		 *
		 * @param array $allowed_blocks are the default blocks sent to the hook.
		 *
		 * @return array returns the functions constant to the hook
		 */
		function gb_allowed_block_types( $allowed_blocks ) {

			return GUTENBERG_ALLOWED_BLOCKS;

		}

		add_filter( 'allowed_block_types', 'gb_allowed_block_types' );
	}
}
