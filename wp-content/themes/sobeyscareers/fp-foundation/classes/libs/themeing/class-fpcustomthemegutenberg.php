<?php
/**
 * FP Custom Theme Gutenberg
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'FpCustomThemeGutenberg' ) ) {
	/**
	 * This generates a scss file that stores the default colour options as accessible sass variables
	 */
	class FpCustomThemeGutenberg {

		/**
		 * The gutenberg css file to save to.
		 *
		 * @var string
		 */
		protected $output_file = '';
		/**
		 * The gutenberg css file to save to.
		 *
		 * @var integer
		 */
		protected $blog_id = '';

		/**
		 * Set the class properties and call the acf save hook
		 *
		 * @see self::create_gutenberg_palette()
		 */
		public function __construct() {
			$this->blog_id     = ( get_current_blog_id() > 1 ) ? get_current_blog_id() : '';
			$this->output_file = trailingslashit( get_template_directory() ) . 'dist/theme-css/gutenberg-theme-style' . $this->blog_id . '.css';

			add_action( 'acf/save_post', array( $this, 'create_gutenberg_palette' ), 20 );
		}

		/**
		 * Create the gutenberg css file on options save. We can use get_field here since we know acf is loaded.
		 *
		 * @param integer|string $post_id is the page being edited/saved.
		 */
		public function create_gutenberg_palette( $post_id ) {

			if ( 'options' === $post_id ) {
				$default_options = get_field( 'default_theme_options', 'options' );

				if ( isset( $default_options['main_font_colour'] ) && ! empty( $default_options['main_font_colour'] ) ) {
					$main      = $default_options['main_font_colour'];
					$secondary = $default_options['secondary_colour'];

					$heading_defaults = array();

					$hex_values = array( $main, $secondary );

					$colourrs = array(
						array(
							'name'  => esc_html__( 'Main Font Colour', 'fp' ),
							'slug'  => 'main-font-colour',
							'color' => $main,
						),
						array(
							'name'  => esc_html__( 'Secondary Colour', 'fp' ),
							'slug'  => 'secondary-colour',
							'color' => $secondary,
						),

					);

					for ( $i = 1; $i < 7; $i++ ) {
						if ( ! empty( $default_options['headings'][ 'default_h' . $i ] ) ) {
							if ( ! in_array( $default_options['headings'][ 'default_h' . $i ], $hex_values, true ) ) {
								$colourrs[]   = array(
									'name'  => esc_attr( 'H' . $i . ' Colour' ),
									'slug'  => 'heading' . $i . '-colour',
									'color' => $default_options['headings'][ 'default_h' . $i ],
								);
								$hex_values[] = $default_options['headings'][ 'default_h' . $i ];
							}
						}
					}

					if ( ! empty( $default_options['text_links']['default_colour'] ) ) {
						if ( ! in_array( $default_options['text_links']['default_colour'], $hex_values, true ) ) {
							$colourrs[]   = array(
								'name'  => esc_html__( 'Text Links Default Colour', 'fp' ),
								'slug'  => 'text-links-default-colour',
								'color' => $default_options['text_links']['default_colour'],
							);
							$hex_values[] = $default_options['text_links']['default_colour'];
						}
						if ( ! in_array( $default_options['text_links']['hover_colour'], $hex_values, true ) ) {
							$colourrs[]   = array(
								'name'  => esc_html__( 'Text Links Hover Colour', 'fp' ),
								'slug'  => 'text-links-hover-colour',
								'color' => $default_options['text_links']['hover_colour'],
							);
							$hex_values[] = $default_options['text_links']['hover_colour'];
						}
					}

					if ( ! empty( $default_options['default_button']['default_colour'] ) ) {
						if ( ! in_array( $default_options['default_button']['default_colour'], $hex_values, true ) ) {
							$colourrs[]   = array(
								'name'  => esc_html__( 'Button Default Colour', 'fp' ),
								'slug'  => 'button-default-colour',
								'color' => $default_options['default_button']['default_colour'],
							);
							$hex_values[] = $default_options['default_button']['default_colour'];
						}
						if ( ! in_array( $default_options['default_button']['hover_colour'], $hex_values, true ) ) {
							$colourrs[]   = array(
								'name'  => esc_html__( 'Button Hover Colour', 'fp' ),
								'slug'  => 'button-hover-colour',
								'color' => $default_options['default_button']['hover_colour'],
							);
							$hex_values[] = $default_options['default_button']['hover_colour'];
						}
						if ( ! in_array( $default_options['default_button']['text_colour'], $hex_values, true ) ) {
							$colourrs[]   = array(
								'name'  => esc_html__( 'Button Text Colour', 'fp' ),
								'slug'  => 'button-text-colour',
								'color' => $default_options['default_button']['text_colour'],
							);
							$hex_values[] = $default_options['default_button']['text_colour'];
						}
						if ( ! in_array( $default_options['default_button']['text_hover_colour'], $hex_values, true ) ) {
							$colourrs[]   = array(
								'name'  => esc_html__( 'Button Text Hover Colour', 'fp' ),
								'slug'  => 'button-text-hover-colour',
								'color' => $default_options['default_button']['text_hover_colour'],
							);
							$hex_values[] = $default_options['default_button']['text_hover_colour'];
						}
					}

					$theme_options = get_field( 'theme_colours', 'options' );

					if ( ! empty( $theme_options ) ) {
						foreach ( $theme_options as $colour_opt ) {
							$name    = $colour_opt['colour_name'];
							$element = $colour_opt['applies_to'];

							if ( isset( $colour_opt['text_colour'] ) && ! in_array( $colour_opt['text_colour'], $hex_values, true ) && ! empty( $colour_opt['text_colour'] ) ) {
								$colourrs[]   = array(
									'name'  => esc_attr( str_replace( '-', ' ', $name ) ) . __( ' Text', 'fp' ),
									'slug'  => str_replace( ' ', '-', $name ) . '-text',
									'color' => $colour_opt['text_colour'],
								);
								$hex_values[] = $colour_opt['text_colour'];
							}
							if ( isset( $colour_opt['default_colour'] ) && ! in_array( $colour_opt['default_colour'], $hex_values, true ) && ! empty( $colour_opt['default_colour'] ) ) {
								$colourrs[]   = array(
									'name'  => esc_attr( str_replace( '-', ' ', $name ) ) . __( ' Default', 'fp' ),
									'slug'  => str_replace( ' ', '-', $name ) . '-default',
									'color' => $colour_opt['default_colour'],
								);
								$hex_values[] = $colour_opt['default_colour'];
							}
							if ( isset( $colour_opt['text_hover_colour'] ) && ! in_array( $colour_opt['text_hover_colour'], $hex_values, true ) && ! empty( $colour_opt['text_hover_colour'] ) ) {
								$colourrs[]   = array(
									'name'  => esc_attr( str_replace( '-', ' ', $name ) ) . __( ' Text Hover', 'fp' ),
									'slug'  => str_replace( ' ', '-', $name ) . '-text-hover',
									'color' => $colour_opt['text_hover_colour'],
								);
								$hex_values[] = $colour_opt['text_hover_colour'];
							}
							if ( isset( $colour_opt['hover_colour'] ) && ! in_array( $colour_opt['hover_colour'], $hex_values, true ) && ! empty( $colour_opt['hover_colour'] ) ) {
								$colourrs[]   = array(
									'name'  => esc_attr( str_replace( '-', ' ', $name ) ) . __( ' Hover', 'fp' ),
									'slug'  => str_replace( ' ', '-', $name ) . 'hover',
									'color' => $colour_opt['hover_colour'],
								);
								$hex_values[] = $colour_opt['hover_colour'];
							}
						}
					}

					// Createa db option to pass to GB admin colour palette hook.
					update_option( '_fp_gutenberg_theme', $colourrs );

					// Create the guternberg style specific theme options.
					$this->create_gutenberg_editor_styles( $colourrs );
				}
			}
		}

		/**
		 * Output the colour options to the gutenberg dist theme css file.
		 *
		 * @param array $colourrs is the theme options array.
		 */
		public function create_gutenberg_editor_styles( $colourrs ) {
			$this->output_file = trailingslashit( get_template_directory() ) . 'dist/theme-css/gutenberg-theme-style' . $this->blog_id . '.css';

			if ( ! empty( $colourrs ) ) {
				ob_start( 'ob_gzhandler' );
				foreach ( $colourrs as $colour ) {
?>
					.has-<?php echo esc_attr( $colour['slug'] ); ?>-background-color {
						background-color: <?php echo esc_attr( $colour['color'] ); ?> !important;
					}
					.has-<?php echo esc_attr( $colour['slug'] ); ?>-color {
						color: <?php echo esc_attr( $colour['color'] ); ?> !important;
					}
<?php
				}

				$output = ob_get_contents();

				$buffer = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $output );

				$buffer = "/*this file is auto generated*/" . "\r" . $buffer; //phpcs:ignore

				ob_end_clean();

				if ( ! is_dir( dirname( $this->output_file ) ) ) {
					mkdir( dirname( $this->output_file ) );
				}

				// Put the contents of the css file in a dist file.
				$fh = fopen( $this->output_file, 'w+' ); //phpcs:ignore
				if ( $fh ) {
					fwrite( $fh, $buffer ); //phpcs:ignore
				}
				fclose( $fh ); //phpcs:ignore
			}
		}

	}
	new FpCustomThemeGutenberg();
}
