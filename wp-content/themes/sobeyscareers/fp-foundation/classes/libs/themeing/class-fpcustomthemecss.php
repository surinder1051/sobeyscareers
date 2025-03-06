<?php
/**
 * FP Custom Theme CSS
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'FpCustomThemeCss' ) ) {
	/**
	 * This gnerates a theme/dist/theme-css/custom-theme-style.css file which covers the basics of color assignments.
	 * Themes should be based on css rules to be built on.
	 */
	class FpCustomThemeCss {

		/**
		 * The resulting css file.
		 *
		 * @var string $output_file
		 */
		protected $output_file = '';

		/**
		 * Setup hooks
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'acf/save_post', array( $this, 'create_dynamic_css' ), 20 );
			add_action( 'admin_notices', array( $this, 'acf_check' ) );
		}

		/**
		 * Setup the class var with the output file based on blog id
		 *
		 * @see self::create_dynamic_css()
		 */
		public function init() {
			$id                = ( get_current_blog_id() > 1 ) ? get_current_blog_id() : '';
			$this->output_file = trailingslashit( get_template_directory() ) . 'dist/theme-css/custom-theme-style' . $id . '.css';
			if ( ! file_exists( $this->output_file ) ) {
				$this->create_dynamic_css( 'options' );
			}
		}

		/**
		 * Check if ACF is activated. Throw an admin notice if it's not.
		 */
		public function acf_check() {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			if ( ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
				do_action( 'fp_admin_notice', 'FP-Foundation requires ACF Pro to be installed.', 'error' );
			}

		}

		/**
		 * This file isn't gulped, so we include the rem values with the px values manually. Uses base 16.
		 *
		 * @param integer $int is the px size.
		 *
		 * @return string
		 */
		protected function rem( $int ) {
			return ( (int) $int * 0.0625 ) . 'rem';
		}

		/**
		 * Create a google font load string based on heading options selected and store it as an option
		 *
		 * @param array $enqueue_fonts are the custom heading fonts.
		 * @param array $main_fonts are the default body fonts.
		 */
		public function google_font_load_option( $enqueue_fonts, $main_fonts = array() ) {
			$googlef = array();

			if ( ! empty( $main_fonts ) ) {
				$font_list = array_merge_recursive( $enqueue_fonts, $main_fonts );
			} else {
				$font_list = $enqueue_fonts;
			}

			foreach ( $font_list as $gf => $g_prop ) {
				$font_family = str_replace( ', sans-serif', '', $gf );
				if ( defined( 'THEME_CUSTOM_FONTS' ) && in_array( $font_family, THEME_CUSTOM_FONTS, true ) ) {
					continue;
				}
				$gr_qs = str_replace( ' ', '+', $gf );
				if ( isset( $g_prop['weight'] ) && ! empty( $g_prop['weight'] ) ) {
					$gr_qs .= ':';
					$gr_qs .= implode( ',', array_unique( $g_prop['weight'] ) );
				}
				$googlef[] = $gr_qs;
			}

			if ( ! empty( $googlef ) ) {
				$load_fonts = implode( '|', $googlef );
				delete_option( '_fp_load_google_fonts' );
				add_option( '_fp_load_google_fonts', $load_fonts );
			} else {
				delete_option( '_fp_load_google_fonts' );
			}
		}

		/**
		 * Create a font option to store default font options, and any custom heading font options
		 *
		 * @param array $main_fonts are the default_options fonts.
		 *
		 * @see self::google_font_load_option()
		 *
		 * @return array $heading_css
		 */
		public function headings_dynamic_css( $main_fonts ) {

			$options_prefix = 'options_heading_font_settings_heading_';

			// Here we create the google fonts query string to load selected fonts and variants.
			$enqueue_fonts = array();

			// Here we create a loop for the output buffer array.
			$headings_css = array();

			for ( $i = 1; $i < 7; $i++ ) {
				$is_google_font        = false;
				$heading_font_family   = get_option( $options_prefix . $i . '_font_family', '' );
				$heading_font_size     = get_option( $options_prefix . $i . '_font_size', '' );
				$heading_font_variants = get_option( $options_prefix . $i . '_font_variants', '' );

				if ( ! empty( $heading_font_family ) || ! empty( $heading_font_size ) ) {

					if ( ! empty( $heading_font_family ) ) {
						$font_weight = ( ! empty( $heading_font_variants ) ) ? $heading_font_variants : '400';

						// Google fonts don't have a fallback, so this tests against system font selection.
						if ( strstr( $heading_font_family, ',' ) === false ) {
							if ( ! isset( $enqueue_fonts[ $heading_font_family ] ) ) {
								$enqueue_fonts[ $heading_font_family ] = array(
									'variant' => array( 'normal' ),
									'weight'  => array(),
								);
							}
							$font_family_css                    = $heading_font_family . ', sans-serif';
							$headings_css[ 'h' . $i ]['family'] = $font_family_css;
							$is_google_font                     = true;
						}
						if ( 'italic' === $font_weight ) {
							if ( true === $is_google_font ) {
								$enqueue_fonts[ $heading_font_family ]['weight'][] = '400i';
							}
							$headings_css[ 'h' . $i ]['style'] = 'italic';
						} else {
							if ( false !== strstr( $font_weight, 'i' ) ) {
								$headings_css[ 'h' . $i ]['style'] = 'italic';
							}
							if ( true === $is_google_font ) {
								$enqueue_fonts[ $heading_font_family ]['weight'][] = $font_weight;
							}
							$headings_css[ 'h' . $i ]['weight'] = str_replace( 'i', '', $font_weight );
						}
					}
					if ( ! empty( $heading_font_size ) ) {
						$headings_css[ 'h' . $i ]['size'][] = $heading_font_size . 'px';
						$headings_css[ 'h' . $i ]['size'][] = $this->rem( $heading_font_size );
					}
				}
			}
			if ( ! empty( $enqueue_fonts ) ) {
				// Create the stored option which will be called in enqueue.php.
				$this->google_font_load_option( $enqueue_fonts, $main_fonts );
			}

			return $headings_css;
		}

		/**
		 * On post save, create the dynamic css file. The post type has to be options.
		 *
		 * @param integer|string $post_id is the post ID.
		 */
		public function create_dynamic_css( $post_id ) {
			if ( 'options' === $post_id ) {

				include_once ABSPATH . 'wp-admin/includes/plugin.php';
				// Delete cached theme colours transient.
				delete_transient( 'theme_colours' ); // Old version.
				delete_option( 'theme_colours' ); // Always clean this out on save.

				if ( ! function_exists( 'get_field' ) ) {
					// ACF Must be off so we can't go on.
					error_log( 'ACF has to be on to create dynamic css' ); //phpcs:ignore
					return;
				}

				ob_start( 'ob_gzhandler' );
				// // header("Content-type: text/css; charset: UTF-8");

				$default_options       = ( function_exists( 'generate_default_theme_options' ) ) ? generate_default_theme_options() : get_field( 'default_theme_options', 'options' );
				$main                  = ( isset( $default_options['main_font_colour'] ) ) ? $default_options['main_font_colour'] : '#000';
				$secondary             = ( isset( $default_options['secondary_colour'] ) ) ? $default_options['secondary_colour'] : '';
				$row_background_colour = ( isset( $default_options['module_background_colour'] ) ) ? $default_options['module_background_colour'] : '';
				$row_background_type   = ( isset( $default_options['module_background_colour_odd_even'] ) ) ? $default_options['module_background_colour_odd_even'] : 'odd';
				$default_fonts         = ( isset( $default_options['fonts'] ) ) ? $default_options['fonts'] : array();
				$theme_options         = ( function_exists( 'generate_custom_theme_options' ) ) ? generate_custom_theme_options() : get_field( 'theme_colours', 'options' );
				$buttons               = array();
				$links                 = array();
				$headings              = array();
				$heading_defaults      = array();
				$backgrounds           = array();
				$enqueue_main_fonts    = array();

				general_global_theme_list(); // Regenerate the theme_colours option.

				// Add main font selection to the font enqueue.
				if ( ! empty( $default_fonts['font_variants'] ) && ! empty( $default_fonts['font_family'] ) ) {
					if ( strstr( $default_fonts['font_family'], ',' ) === false ) {

						// System fonts have a built in fallback so we don't need to enqueue those.
						$enqueue_main_fonts[ $default_fonts['font_family'] ] = array();
						foreach ( $default_fonts['font_variants'] as $font_variant ) {

							// By default italic = 400i, so sent the numeric representation instead of the word.
							$enqueue_main_fonts[ $default_fonts['font_family'] ]['weight'][] = ( 'italic' === $font_variant ) ? '400i' : $font_variant;
						}
					}
				}

				for ( $i = 1; $i < 7; $i++ ) {
					if ( ! empty( $default_options['headings'][ 'default_h' . $i ] ) ) {
						$heading_defaults[ 'h' . $i ] = $default_options['headings'][ 'default_h' . $i ];
					}
					if ( defined( 'LOAD_THEME_ACF_OPTIONALS' ) && in_array( 'heading_font_settings', LOAD_THEME_ACF_OPTIONALS, true ) ) {

						// Send the main fonts selection to create a single, combined google font enqueue string.
						$heading_typography = $this->headings_dynamic_css( $enqueue_main_fonts );
					}
				}
				// If heading fonts aren't set, then send the main fonts selection to create the google font enqueue string.
				if ( ! isset( $heading_typography ) && ! empty( $enqueue_main_fonts ) ) {
					$this->google_font_load_option( $enqueue_main_fonts );
				}

				if ( defined( 'LOAD_THEME_ACF_OPTIONALS' ) && in_array( 'body_background_colour', LOAD_THEME_ACF_OPTIONALS, true ) ) :
					$body_background_colour = get_field( 'body_background_colour', 'options' );
					if ( ! empty( $body_background_colour ) ) : ?>
						body {
							background-color: <?php echo esc_attr( $body_background_colour ); ?>;
						}
						<?php
					endif;
				endif;
				?>

				<?php if ( ! empty( $row_background_colour ) && ! empty( $row_background_type ) ) : ?>
					.grey-bg,
					.fl-row:nth-child(<?php echo esc_attr( $row_background_type ); ?>) {
						background-color: <?php echo esc_attr( $row_background_colour ); ?>;
					}
				<?php elseif ( ! empty( $row_background_colour ) ) : ?>
					.grey-bg,
					.fl-row:nth-child(odd){
						background-color: <?php echo esc_attr( $row_background_colour ); ?>;
					}
				<?php endif; ?>

				<?php

				if ( ! empty( $theme_options ) ) {
					foreach ( $theme_options as $colour_opt ) {
						if ( in_array( 'a', $colour_opt['applies_to'], true ) ) :
							$links[] = $colour_opt;
							endif;
						if ( in_array( 'button', $colour_opt['applies_to'], true ) ) :
							$buttons[] = $colour_opt;
							endif;
						if ( in_array( 'background', $colour_opt['applies_to'], true ) ) :
							$backgrounds[] = $colour_opt;
							endif;
						if ( in_array( 'h1', $colour_opt['applies_to'], true ) ) :
							$headings['h1'][] = $colour_opt;
							endif;
						if ( in_array( 'h2', $colour_opt['applies_to'], true ) ) :
							$headings['h2'][] = $colour_opt;
							endif;
						if ( in_array( 'h3', $colour_opt['applies_to'], true ) ) :
							$headings['h3'][] = $colour_opt;
							endif;
						if ( in_array( 'h4', $colour_opt['applies_to'], true ) ) :
							$headings['h4'][] = $colour_opt;
							endif;
						if ( in_array( 'h5', $colour_opt['applies_to'], true ) ) :
							$headings['h5'][] = $colour_opt;
							endif;
						if ( in_array( 'h6', $colour_opt['applies_to'], true ) ) :
							$headings['h6'][] = $colour_opt;
							endif;
					};
				}

				?>

				<?php if ( ! empty( $default_fonts ) ) : ?>
					html,
					html body {
						font-size: <?php echo esc_attr( $this->rem( (int) $default_fonts['size'] ) ); ?>;
						line-height: <?php echo esc_attr( $this->rem( 1.5 * (int) $default_fonts['size'] ) ); ?>;
						<?php if ( isset( $default_fonts['font_family'] ) && ! is_numeric( $default_fonts['font_family'] ) ) : ?>
						font-family: <?php echo esc_attr( $default_fonts['font_family'] ); ?>, sans-serif;
						<?php endif; ?>
						font-weight: 400;
					}
				<?php endif; ?>

				.assistive-text.skip-content:focus {
					color: <?php echo esc_attr( $secondary ); ?>;
				}

				<?php foreach ( $heading_defaults as $heading_type => $head_colour ) : ?>

					<?php echo esc_attr( $heading_type ); ?>,
					.<?php echo esc_attr( $heading_type ); ?> {
					color: <?php echo esc_attr( $head_colour ); ?>;
					<?php if ( isset( $heading_typography[ $heading_type ] ) ) : ?>
							<?php
							foreach ( $heading_typography[ $heading_type ] as $prop => $value ) :
								if ( 'size' !== $prop ) :
									?>
								font-<?php echo esc_attr( $prop ); ?>: <?php echo esc_attr( $value ); ?>;
									<?php
								else :
									?>
								font-size: <?php echo esc_attr( $value[0] ); ?>;
								font-size: <?php echo esc_attr( $value[1] ); ?>;
									<?php
								endif;
							endforeach;
							?>
					<?php endif; ?>
					}
					<?php if ( 'h3' === $heading_type ) : ?>
						.header {
						color: <?php echo esc_attr( $head_colour ); ?>;
						}
					<?php endif; ?>

				<?php endforeach; ?>

				<?php foreach ( $headings as $heading_type => $head_var ) : ?>
					<?php foreach ( $head_var as $head_opt ) : ?>
						<?php echo esc_attr( $heading_type ); ?>.<?php echo esc_attr( $head_opt['colour_name'] ); ?>,
						.<?php echo esc_attr( $head_opt['colour_name'] ); ?> <?php echo esc_attr( $heading_type ); ?>,
						.<?php echo esc_attr( $heading_type ); ?>.<?php echo esc_attr( $head_opt['colour_name'] ); ?> {
							color: <?php echo esc_attr( $head_opt['default_colour'] ); ?>;
						}
					<?php endforeach; ?>
				<?php endforeach; ?>

				<?php if ( ! empty( $main ) ) : ?>
					body,
					p:not(.fl-icon-text),
					ol,
					ul,
					.menu-toggle,
					button,
					input,
					.nav-menu,
					.nav_link,
					.tag,
					li,
					.entry-content p,
					.entry-content li,
					.entry-content div:not(.h1):not(.h2):not(.h3):not(.h4):not(.h5):not(.h6),
					.entry-content cite,
					.entry-content dl,
					.entry-content address,
					.entry-content td,
					.entry-content pre,
					.header-content p,
					.header-content li,
					.header-content div:not(.h1):not(.h2):not(.h3):not(.h4):not(.h5):not(.h6),
					.header-content cite,
					.header-content dl,
					.header-content address,
					.header-content td,
					.header-content pre {
					color: <?php echo esc_attr( $main ); ?>;
					}

				<?php endif; ?>

				<?php if ( ! empty( $default_options['text_links']['default_colour'] ) ) : ?>
					::-webkit-selection,
					::-moz-selection,
					::selection,
					table.focus,
					table.selected {
					background: <?php echo esc_attr( $default_options['text_links']['default_colour'] ); ?>;
					<?php if ( isset( $default_options['text_links']['text_colour'] ) ) : ?>
						color: <?php echo esc_attr( $default_options['text_links']['text_colour'] ); ?>;
					<?php else : ?>
						color: #000;
					<?php endif; ?>
					}

					a, a:visited, .entry-header a, .post-navigation a, .entry-header a:visited, .post-navigation a:visited,
					a.wp-block-button__link:hover,
					a.wp-block-button__link:visited:hover {
					color: <?php echo esc_attr( $default_options['text_links']['default_colour'] ); ?>;
					}
					a.button.no-style:hover, a.button.no-style:visited:hover,
					a:hover, a:visited:hover,
					.entry-header a:hover, .post-navigation a:hover, .entry-header a:visited:hover, .post-navigation a:visited:hover {
					color: <?php echo esc_attr( $default_options['text_links']['hover_colour'] ); ?>;
					text-decoration:none;
					}
					abbr,
					dfn,
					acronym {
					border-bottom: <?php echo esc_attr( $this->rem( 1 ) ); ?> dotted <?php echo esc_attr( $default_options['text_links']['default_colour'] ); ?>;
					}
					dt::before {
					background-color:<?php echo esc_attr( $default_options['text_links']['default_colour'] ); ?>;
					}
					.post-password-form input:focus, .entry-content .search-form label input:focus {
					border-bottom: <?php echo esc_attr( $this->rem( 1 ) ); ?> solid <?php echo esc_attr( $default_options['text_links']['default_colour'] ); ?>;
					}

					<?php
					// Text link focus colour.

					if ( ! empty( $default_options['text_links']['link_focus_colour'] ) ) :
						?>
						:focus,
						.focus-visible {
							outline-color: <?php echo esc_attr( $default_options['text_links']['link_focus_colour'] ); ?>;
						}
					<?php endif; ?>

				<?php endif; ?>

				<?php
				// Text link - hover states.

				if ( isset( $default_options['text_links'] ) && ! empty( $default_options['text_links']['link_hover_state'] ) ) :
					?>
					a.button.no-style:hover, a.button.no-style:visited:hover,
					a:hover, a:visited:hover,
					.entry-header a:hover, .post-navigation a:hover, .entry-header a:visited:hover, .post-navigation a:visited:hover {
						<?php if ( in_array( 'bold', $default_options['text_links']['link_hover_state'], true ) ) : ?>
							font-weight:700;
						<?php endif; ?>
						<?php if ( in_array( 'underline', $default_options['text_links']['link_hover_state'], true ) ) : ?>
							text-decoration:underline;
						<?php endif; ?>
					}
				<?php endif; ?>

				<?php if ( ! empty( $links ) ) : ?>
					<?php foreach ( $links as $link_opt ) : ?>

						a.<?php echo esc_attr( $link_opt['colour_name'] ); ?>,
						a.<?php echo esc_attr( $link_opt['colour_name'] ); ?>:visited {
						color: <?php echo esc_attr( $link_opt['default_colour'] ); ?>;
						}
						a.<?php echo esc_attr( $link_opt['colour_name'] ); ?>:hover,
						a.<?php echo esc_attr( $link_opt['colour_name'] ); ?>:visited:hover {
						color: <?php echo esc_attr( $link_opt['hover_colour'] ); ?>;
						}

					<?php endforeach; ?>
				<?php endif; ?>

				<?php if ( ! empty( $default_options['default_button']['default_colour'] ) ) : ?>
					a.wp-block-button__link,
					a.wp-block-button__link:visited,
					a.wp-block-button__link:hover,
					a.wp-block-button__link:visited:hover,
					button,
					a.button,
					.fl-builder-content a.fl-button,
					a.wp-block-file__button,
					a.button:visited,
					.fl-builder-content a.fl-button:visited,
					a.wp-block-file__button:visited, input[type='submit'],input[type='button'],
					article.grid .post-more-link a,
					article.grid .post-more-link a:visited {
						background-color: <?php echo esc_attr( $default_options['default_button']['default_colour'] ); ?>;
						color: <?php echo esc_attr( $default_options['default_button']['text_colour'] ); ?>;
					}
					a.button.outline,
					a.fl-button.outline,
					a.wp-block-file__button.outline,
					a.button.outline:visited,
					a.fl-button.outline:visited,
					a.wp-block-file__button.outline:visited,
					input[type='submit'].outline, button.outline, input[type='button'].outline {
						background-color: #fff;
						border: <?php echo esc_attr( $this->rem( 1 ) ); ?> solid <?php echo esc_attr( $default_options['default_button']['default_colour'] ); ?>;
						color: <?php echo esc_attr( $main ); ?>;
					}
					button:hover,
					a.button:hover,
					.fl-builder-content a.fl-button:hover,
					a.wp-block-file__button:hover,
					a.button:visited:hover,
					a.fl-button:visited:hover,
					a.wp-block-file__button:visited:hover,
					input[type='submit']:hover,
					input[type='button']:hover,
					article.grid .post-more-link a:hover,
					article.grid .post-more-link a:visited:hover,
					a.button.outline:hover,
					a.fl-button.outline:hover,
					a.wp-block-file__button.outline:hover,
					button.brand.hover {
						background-color: <?php echo esc_attr( $default_options['default_button']['hover_colour'] ); ?>;
						color: <?php echo esc_attr( $default_options['default_button']['text_hover_colour'] ); ?>;
					}
					.wp-block-pullquote{
						border-top: <?php echo esc_attr( $this->rem( 4 ) ); ?> solid <?php echo esc_attr( $default_options['default_button']['default_colour'] ); ?>;
					}
					.entry-header .sticky code {
						border-left: <?php echo esc_attr( $this->rem( 4 ) ); ?> solid <?php echo esc_attr( $default_options['default_button']['default_colour'] ); ?>;
					}
				<?php endif; ?>

				<?php if ( ! empty( $buttons ) ) : ?>

					<?php foreach ( $buttons as $button_opt ) : ?>
						a.wp-block-button__link.<?php echo esc_attr( $button_opt['colour_name'] ); ?>,
						a.wp-block-button__link.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:visited {
						color: <?php echo esc_attr( $button_opt['text_colour'] ); ?>;
						}
						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>,
						a.fl-button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>,
						a.wp-block-file__button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>,
						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:visited,
						a.fl-button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:visited,
						a.wp-block-file__button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:visited,
						input[type='submit'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>,
						input[type='submit'].button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>,
						input[type='button'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>,
						input[type='button'].button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>,
						button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>,
						article.grid .post-more-link a.<?php echo esc_attr( $button_opt['colour_name'] ); ?>, article.grid .post-more-link a.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:visited
						{
						background-color: <?php echo esc_attr( $button_opt['default_colour'] ); ?>;
						color: <?php echo esc_attr( $button_opt['text_colour'] ); ?>;
						}
						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						a.fl-button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						a.wp-block-file__button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						a.button:visited.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						a.fl-button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:visited:hover,
						a.wp-block-file__button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:visited:hover,
						input[type='submit'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						input[type='submit'].button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						input[type='button'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						input[type='button'].button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						article.grid .post-more-link a.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:hover,
						article.grid .post-more-link a.<?php echo esc_attr( $button_opt['colour_name'] ); ?>:visited:hover {
						background-color: <?php echo esc_attr( $button_opt['hover_colour'] ); ?>;
						color: <?php echo esc_attr( $button_opt['text_hover_colour'] ); ?>;
						}

						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline,
						a.fl-button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline,
						a.wp-block-file__button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline,
						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:visited,
						a.fl-button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:visited,
						a.wp-block-file__button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:visited,
						input[type='submit'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline,
						button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline,
						article.grid .post-more-link a.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline,
						article.grid .post-more-link a.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:visited {
						background-color: #fff;
						border: <?php echo esc_attr( $this->rem( 1 ) ); ?> solid <?php echo esc_attr( $button_opt['default_colour'] ); ?>;
						color: <?php echo esc_attr( $button_opt['default_colour'] ); ?>;
						}

						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:hover,
						a.fl-button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:hover,
						a.wp-block-file__button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:hover,
						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:visited:hover,
						a.fl-button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:visited:hover,
						a.wp-block-file__button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:visited:hover,
						input[type='submit'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:hover,
						button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline:hover {
						background-color: <?php echo esc_attr( $button_opt['default_colour'] ); ?>;
						color: <?php echo esc_attr( $button_opt['text_colour'] ); ?>;
						}

						a.button.Default.outline.transparent,
						a.button.Default.outline.transparent:hover,
						button.Default.outline.transparent,
						button.Default.outline.transparent:hover,
						input[type='submit'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent,
						input[type='submit'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent:hover,
						button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent,
						button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent:hover,
						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent,
						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent:hover {
							background-color: transparent;
						}

						input[type='submit'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent,
						button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent:not(:hover),
						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent:not(:hover) {
							color: <?php echo esc_attr( $button_opt['text_colour'] ); ?>;
						}

						input[type='submit'].<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent:hover,
						button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent:hover,
						a.button.<?php echo esc_attr( $button_opt['colour_name'] ); ?>.outline.transparent:hover {
							color: <?php echo esc_attr( $button_opt['text_hover_colour'] ); ?>;
							border-color: <?php echo esc_attr( $button_opt['hover_colour'] ); ?>;
						}

					<?php endforeach; ?>
				<?php endif; ?>

				<?php if ( ! empty( $backgrounds ) ) : ?>
					<?php foreach ( $backgrounds as $bg_opt ) : ?>
						.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>,
						.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg,
						.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background,
						.entry-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>,
						.entry-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg,
						.entry-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background,
						.header-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>,
						.header-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg,
						.header-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background {
						background-color: <?php echo esc_attr( $bg_opt['default_colour'] ); ?>;
						color: <?php echo esc_attr( $bg_opt['text_colour'] ); ?> !important;
						}
						.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> p,
						.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg p,
						.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background p,
						.entry-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> p,
						.entry-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg p,
						.entry-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background p,
						.header-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> p,
						.header-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg p,
						.header-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background p {
						color: <?php echo esc_attr( $bg_opt['text_colour'] ); ?>;
						}
						.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a,
						.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a:visited,
						.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a,
						.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a:visited,
						.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a,
						.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a:visited,
						.entry-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a,
						.entry-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a:visited,
						.entry-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a,
						.entry-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a:visited,
						.entry-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a,
						.entry-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a:visited,
						.header-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a,
						.header-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a:visited,
						.header-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a,
						.header-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a:visited,
						.header-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a,
						.header-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a:visited {
						color: <?php echo esc_attr( $bg_opt['text_colour'] ); ?>;
						}
						.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a:hover,
						.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a:visited:hover,
						.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a:hover,
						.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a:visited:hover,
						.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a:hover,
						.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a:visited:hover,
						.entry-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a:hover,
						.entry-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a:visited:hover,
						.entry-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a:hover,
						.entry-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a:visited:hover,
						.entry-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a:hover,
						.entry-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a:visited:hover,
						.header-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a:hover,
						.header-content div.-bg-<?php echo esc_attr( $bg_opt['colour_name'] ); ?> a:visited:hover,
						.header-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a:hover,
						.header-content div.-<?php echo esc_attr( $bg_opt['colour_name'] ); ?>-bg a:visited:hover,
						.header-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a:hover,
						.header-content div.<?php echo esc_attr( $bg_opt['colour_name'] ); ?>_background a:visited:hover {
						color: <?php echo esc_attr( $bg_opt['text_hover_colour'] ); ?>;
						}

					<?php endforeach; ?>
				<?php endif; ?>

				<?php if ( ! empty( $secondary ) ) : ?>
					.accent_bar,
					.accent-bar-left::before,
					.accent-bar-center::before,
					.accent-bar-short::before,
					.accent-bar-tall::before {
						background-color:<?php echo esc_attr( $secondary ); ?>;
					}
					.accent-bar-center-arrow::before,
					.accent-bar-left-arrow::before {
						background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 147 10"><g fill="<?php echo str_replace( '#', '%23', $secondary ); //phpcs:ignore ?>"><path d="m0 0v10h122.62l7.2-5-7.2-5z"/><path d="m139.8 0h-9.09l7.2 5-7.2 5h9.09l7.2-5z"/></g></svg>');
					}

					blockquote,.wp-block-quote { border-left: <?php echo esc_attr( $this->rem( 5 ) ); ?> solid <?php echo esc_attr( $secondary ); ?>; }
					blockquote.pull-right,.wp-block-qouote.pull-right{border-right: <?php echo esc_attr( $this->rem( 5 ) ); ?> solid <?php echo esc_attr( $secondary ); ?>;}
					.blockquote-reverse { border-right: <?php echo esc_attr( $this->rem( 5 ) ); ?> solid <?php echo esc_attr( $secondary ); ?>; }
					.entry-content .inner-breadcrumb button { color: <?php echo esc_attr( $main ); ?>; }
					.inner-breadcrumb button:hover { border: <?php echo esc_attr( $this->rem( 1 ) ); ?> solid <?php echo esc_attr( $secondary ); ?>; }
					.secondary {background-color: <?php echo esc_attr( $secondary ); ?>}
					<?php
				endif;

				$output = ob_get_contents();

				// Remove whitespace.
				$buffer = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $output );
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
	new FpCustomThemeCss();
}
