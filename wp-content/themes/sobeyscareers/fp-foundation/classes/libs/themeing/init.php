<?php //phpcs:ignore
/**
 * Theming Init
 *
 * @package fp-foundation
 */

/**
 * Add our theme typography options page
 *
 * @return void
 */
function add_acf_theme_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => __( 'Theme General Options', 'fp' ),
				'menu_title' => __( 'Theme Options', 'fp' ),
				'menu_slug'  => 'acf-options',
				'redirect'   => false,
			)
		);
	}
}
add_action( 'acf/init', 'add_acf_theme_options_page' );

if ( ! function_exists( 'generate_default_theme_options' ) ) {
	/**
	 * For when ACF doesn't work, return an array of default theme options formatted like ACF
	 *
	 * @return array $default_options
	 */
	function generate_default_theme_options() {
		$options_prefix         = 'options_default_theme_options_';
		$default_options        = array();
		$default_colour_options = array(
			'main_font_colour'                  => array(
				'children' => false,
			),
			'secondary_colour'                  => array(
				'children' => false,
			),
			'module_background_colour'          => array(
				'children' => false,
			),
			'module_background_colour_odd_even' => array(
				'children' => false,
			),
			'default_button'                    => array(
				'children' => array(
					'default_colour',
					'hover_colour',
					'text_colour',
					'text_hover_colour',
				),
			),
			'text_links'                        => array(
				'children' => array(
					'default_colour',
					'hover_colour',
					'focus_colour',
					'text_hover_state',
				),
			),
			'headings'                          => array(
				'children' => array(
					'default_h1',
					'default_h2',
					'default_h3',
					'default_h4',
					'default_h5',
					'default_h6',
				),
			),
			'fonts'                             => array(
				'children' => array(
					'size',
					'font_family',
					'font_variants',
				),
			),
			'body_background_colour'            => array(
				'children' => false,
			),
			'chevrons'                          => array(
				'children' => false,
			),
			'accent_bar_options'                => array(
				'children' => array(
					'display',
				),
			),
		);
		// Loop through each options set and recreate the ACF formatted fp_themes vars.
		foreach ( $default_colour_options as $opt_key => $opt_fields ) {
			if ( false === $opt_fields['children'] ) {
				$default_options[ $opt_key ] = get_option( $options_prefix . $opt_key, '' );
			} else {
				foreach ( $opt_fields['children'] as $sub_field ) {
					$sub_key                                 = str_replace( $opt_key . '_', '', $sub_field );
					$default_options[ $opt_key ][ $sub_key ] = get_option( $options_prefix . $opt_key . '_' . $sub_field, '' );
				}
			}
		}

		return $default_options;
	}
}

if ( ! function_exists( 'generate_custom_theme_options' ) ) {
	/**
	 * Generate an array of custom themes in the same format as ACF
	 *
	 * @return array
	 */
	function generate_custom_theme_options() {
		global $wpdb;
		$theme_options = array();
		$option_prefix = 'options_theme_colours_';

		$rows = $wpdb->get_results( "SELECT `option_name`, `option_value` FROM {$wpdb->prefix}options WHERE `option_name` LIKE 'options_theme_colours_%'", ARRAY_A ); //phpcs:ignore

		foreach ( $rows as $result ) {
			preg_match( '/_(\d+)_/', $result['option_name'], $option_id );
			if ( isset( $option_id[1] ) ) {
				$key                                    = str_replace( $option_prefix . $option_id[1] . '_', '', $result['option_name'] );
				$value                                  = ( $key == 'applies_to' && ! empty( $result['option_value'] ) ) ? unserialize( $result['option_value'] ) : $result['option_value']; //phpcs:ignore
				$theme_options[ $option_id[1] ][ $key ] = $value;
			}
		}
		ksort( $theme_options );
		return $theme_options;
	}
}

// Generate_theme is used to convert theme details based on selected theme in the theme selection bb field.
if ( ! function_exists( 'generate_theme' ) ) {

	/**
	 * Get a list of saved theme options. First check if they're stored in a transient. If not, get the values from ACF get_field.
	 */
	function general_global_theme_list() {
		global $fp_themes;

		$fp_themes = get_option( 'theme_colours', false );
		if ( false === $fp_themes || empty( $fp_themes ) ) {
			// Try the transient.
			$fp_themes = get_transient( 'theme_colours' );
		}

		if ( false === $fp_themes || empty( $fp_themes ) ) {
			$default_options = generate_default_theme_options();
			$theme_options   = generate_custom_theme_options();

			$fp_themes = $default_options;

			if ( is_array( $theme_options ) ) {
				foreach ( $theme_options as $key => $theme ) {
					if ( $theme['colour_name'] === $theme['colour_name'] ) {

						foreach ( $theme['applies_to'] as $el ) {
							$fp_themes[ $theme['colour_name'] . '_' . $el ] = $theme;
						}
					}
				}
			}
			// Update_options can be inconsistent, so it's always cleaner to delete and re-add.
			delete_option( 'theme_colours' );
			add_option( 'theme_colours', $fp_themes, '', 'no' );
		}
	}

	/**
	 * Generates a theme array that contains theme colors for different states of headings, buttons, links, backgrounds
	 *
	 * @param  string $theme_slug Slug saved by BB (red, blue, etc).
	 * @param  string $element_type fp-colour-picker field element value (button | h1 | h2 | h3 | h4 | h5 | h6 | background | main_colour | secondary_colour).
	 *
	 * @return array
	 */
	function generate_theme( $theme_slug, $element_type = '' ) {
		global $fp_themes;

		if ( is_array( $theme_slug ) || is_object( $theme_slug ) ) {
			// In certain cases like publishing the post the theme is already setup so we don't want to do it again.
			return $theme_slug;
		}

		$generated_theme = null;

		if ( empty( $fp_themes ) ) {
			general_global_theme_list();
		}

		// For backwards compatibility, we check to see if the theme slug has a combined class eg: blue blue_background.
		list($theme_name, $class_name) = ( strstr( $theme_slug, ' ' ) !== false ) ? explode( ' ', $theme_slug ) : array( $theme_slug, '' );

		// Need to also get the secondary or main font colour option.
		$theme_id = ( ! empty( $element_type ) ) ? $theme_name . '_' . $element_type : $theme_name;

		if ( isset( $fp_themes[ $theme_id ] ) ) {
			$generated_theme = json_decode( json_encode( $fp_themes[ $theme_id ] ), false ); //phpcs:ignore
		}

		return $generated_theme;
	}
}

if ( ! class_exists( 'FbDynamicThemeCss' ) ) {

	require_once __DIR__ . '/generate-acf-fields.php'; // This has to come first.
	require_once __DIR__ . '/class-fpcustomthemesass.php';
	require_once __DIR__ . '/class-fpcustomthemecss.php';
	require_once __DIR__ . '/class-fpcustomfavicon.php';
	require_once __DIR__ . '/fp-bb-icon-library.php';
	require_once __DIR__ . '/bb_acf_color_presets.php';
	require_once __DIR__ . '/bb_acf_font_presets.php';
	require_once __DIR__ . '/colour_utilities.php';

	if ( defined( 'ENABLE_GUTENBERG_THEME' ) ) {
		require_once __DIR__ . '/class-fpcustomthemegutenberg.php';
	}

	define( 'ICON_DIR', ABSPATH . 'wp-content/bb-icons/' );
	if ( ! file_exists( ICON_DIR ) ) {
		mkdir( ICON_DIR );
		file_put_contents( ICON_DIR . 'index.html', '' ); //phpcs:ignore
	}

	/**
	 * Autoload and enqueue theme css files, including component and theme options css files.
	 */
	class FbDynamicThemeCss {


		/**
		 * Run WP Hooks for BB rest API hooks, frontend scripts and admin actions
		 *
		 * @see self::fp_builder_component_select()
		 * @see self::register_routes()
		 * @see self::enqueue()
		 * @see self::admin_enqueue()
		 * @see self::add_admin_bar_shortcut()
		 */
		public function __construct() {
			add_filter( 'fl_builder_custom_fields', array( $this, 'fp_builder_component_select' ) );
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
			add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_shortcut' ) );
			add_action( 'fl_builder_ui_enqueue_scripts', array( $this,'enqueue_custom_fp_bb_toplayer_styles' ) );
		}

		/**
		 * Add a shortcut URL to the Theme Options in the admin toolbar.
		 *
		 * @param object $admin_bar is a WP default for the admin bar object.
		 */
		public function add_admin_bar_shortcut( $admin_bar ) {
			$admin_bar->add_menu(
				array(
					'id'    => 'theme-options',
					'title' => 'Theme Options',
					'href'  => '/wp-admin/admin.php?page=acf-options',
					'meta'  => array(
						'title' => __( 'Theme Options' ),
					),
				)
			);
		}

		/**
		 * Iterate through the custom icon sets to always enqueue custom uploaded "brand" icons.
		 * Use BB to determin the icomoon vs fontello folder structure.
		 *
		 * @see FLBuilderIcons::get_sets_for_current_site()
		 */
		public function enqueue_icon_sets() {
			if ( is_dir( ICON_DIR ) && class_exists( 'FLBuilderIcons' ) ) {
				$enabled_icons = FLBuilderIcons::get_sets_for_current_site();

				if ( ! empty( $enabled_icons ) ) {
					foreach ( $enabled_icons as $icon_set => $icon_data ) {

						if ( 'brand' === $icon_data['name'] ) {

							$icon_dir   = ICON_DIR . $icon_set;
							$icons_name = $icon_data['name'];
							$font_dir   = $icon_data['path'];
							$font_url   = $icon_data['url'];
							$icon_css   = $icon_data['stylesheet'];

							$fh = opendir( $font_dir );
							if ( false !== $fh ) {
								do_action( 'add_item_fp_menu', 'Icons Demo', '/wp-content/bb-icons/icon-brand/demo.html' );
								wp_register_style( $icons_name, $icon_css, array(), FP_FOUNDATION_VERSION );
								wp_enqueue_style( $icons_name );
								closedir( $fh );
							}
						}
					}
				}
			}
		}

		/**
		 * Front end css enqueue for supporting Beaver Builder's Top Layer
		 *
		 */
		public function enqueue_custom_fp_bb_toplayer_styles() {
			$styles = apply_filters( 'fp_bb_toplayer_styles', array( 'bb-field-colour-picker', 'bb-field-icon-picker', 'bb-field-checkbox' ) );
			foreach ( $styles as $handle ) {
				wp_enqueue_style( $handle );
			}
		}

		/**
		 * Front end enqueue custom stylesheets generated via Theme Options.
		 * Load multisite based on blog id
		 *
		 * @see self::enqueue_icon_sets()
		 */
		public function enqueue() {
			$id = ( get_current_blog_id() > 1 ) ? get_current_blog_id() : '';
			wp_register_style( 'theme-custom-styles', get_stylesheet_directory_uri() . '/dist/theme-css/custom-theme-style' . $id . '.css', array( 'general' ), FP_FOUNDATION_VERSION );
			wp_enqueue_style( 'theme-custom-styles' );

			if ( defined( 'ENABLE_GUTENBERG_THEME' ) && ENABLE_GUTENBERG_THEME ) {
				wp_register_style( 'gutenberg-theme', get_template_directory_uri() . '/dist/theme-css/gutenberg-theme-style' . $id . '.css', array( 'theme-custom-styles' ), FP_FOUNDATION_VERSION );
				wp_enqueue_style( 'gutenberg-theme' );

			}
			$acf_font_options = get_option( '_fp_load_google_fonts' );
			if ( ! empty( $acf_font_options ) ) {
				wp_register_style( 'fonts', 'https://fonts.googleapis.com/css?family=' . $acf_font_options . '&display=swap', array(), FP_FOUNDATION_VERSION );
				wp_enqueue_style( 'fonts' );
			}

			if ( isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
				wp_register_script( 'bb-field-clear-picker', get_template_directory_uri() . '/fp-foundation/classes/libs/themeing/bb-field-select.js', array(), FP_FOUNDATION_VERSION, true );
				wp_enqueue_script( 'bb-field-clear-picker' );

				wp_register_style( 'bb-field-colour-picker', get_template_directory_uri() . '/fp-foundation/classes/libs/themeing/bb-field-colour-picker.css', array(), FP_FOUNDATION_VERSION );
				wp_enqueue_style( 'bb-field-colour-picker' );

				wp_register_style( 'bb-field-icon-picker', get_template_directory_uri() . '/fp-foundation/classes/libs/themeing/bb-field-icon-picker.css', array(), FP_FOUNDATION_VERSION );
				wp_enqueue_style( 'bb-field-icon-picker' );

				wp_register_style( 'bb-field-checkbox', get_template_directory_uri() . '/fp-foundation/classes/libs/themeing/bb-field-checkbox.css', array(), FP_FOUNDATION_VERSION );
				wp_enqueue_style( 'bb-field-checkbox' );

				wp_register_script( 'bb-color-presets', get_template_directory_uri() . '/fp-foundation/assets/js/admin/beaver_builder/fp_bb_color_presets.js', array(), FP_FOUNDATION_VERSION, true );
				wp_enqueue_script( 'bb-color-presets' );
			}

			// Moved the icons enqueue to a new function to enable that styling to be added in the admin as well. Required for the ACF Icon picker.
			$this->enqueue_icon_sets();
		}

		/**
		 * Enqueue theme options and the font demo html file in the admin. Enqueue icon sets to be used in default and custom icon pickers.
		 *
		 * @see self::enqueue_icon_sets()
		 */
		public function admin_enqueue() {
			wp_register_script( 'fp-theme-option-js', get_template_directory_uri() . '/fp-foundation/classes/libs/themeing/themeing.js', array(), FP_FOUNDATION_VERSION, true );
			wp_enqueue_script( 'fp-theme-option-js' );
			do_action( 'add_item_fp_menu', 'Icons Demo', '/wp-content/bb-icons/icon-brand/demo.html' );

			// Enqueue icon sets in the admin for the ACF Icon picker.
			$this->enqueue_icon_sets();
		}

		/**
		 * Load the custom beaver builder form fields templates: fp-colour-picker and fp-icon-picker
		 *
		 * @return array
		 */
		public function fp_builder_component_select() {
			$fields['fp-colour-picker'] = trailingslashit( dirname( __FILE__ ) ) . 'bb-field-colour-select.php';
			$fields['fp-icon-picker']   = trailingslashit( dirname( __FILE__ ) ) . 'bb-field-icon-select.php';
			return $fields;
		}

		/**
		 * Register rest routes for returning theme options values for the fp-colour-picker custom BB settings field.
		 * Register rest route for returning icon options for the fp-icon-picker custom BB settings field.
		 * Resiter rest route for returning additional, custom fonts (e.g typekit) for the font selecton field.
		 *
		 * @see self::fp_builder_colour_values();
		 * @see self::fp_builder_icon_values()
		 * @see self::fp_get_font_data()
		 */
		public function register_routes() {
			register_rest_route(
				'wp/v1',
				'/fp-component-colour-select',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'fp_builder_colour_values' ),
						'args'                => array(
							'bb_element' => array(
								'default' => '',
							),
						),
						'permission_callback' => ( is_user_logged_in() ) ? '__return_true' : '__return_false',
					),
				)
			);
			register_rest_route(
				'wp/v1',
				'/fp-component-icon-select',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'fp_builder_icon_values' ),
						'args'                => array(),
						'permission_callback' => ( is_user_logged_in() ) ? '__return_true' : '__return_false',
					),
				)
			);
			register_rest_route(
				'wp/v1',
				'/fp-get-font-data',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'fp_get_font_data' ),
						'args'                => array(
							'name' => array(
								'default' => '',
							),
						),
						'permission_callback' => ( is_user_logged_in() ) ? '__return_true' : '__return_false',
					),
				)
			);
		}

		/**
		 * Get the font data for the current BB Font field
		 *
		 * @param array $request_data is the data from the js call.
		 *
		 * @return array|WP_Error object
		 */
		public function fp_get_font_data( $request_data ) {

			$parameters = $request_data->get_params();

			if ( isset( $parameters['name'] ) ) {

				$json             = fetch_google_webfonts();
				$field['choices'] = array();

				if ( isset( $json->items ) ) {
					foreach ( $json->items as $font ) {
						if ( $font->family === $parameters['name'] ) {
							return $font;
						}
					}
				} else {
					return new WP_Error( 'no_fonts', 'Failed to load google font data', array( 'status' => 404 ) );
				}
			} else {
				return new WP_Error( 'no_name', 'No font name specified', array( 'status' => 404 ) );
			}
		}

		/**
		 * Get the colour options set via ACF fields for the element specified in the component.
		 * eg: h1, h2, h3 (etc), button, a, background.
		 * Return value is automatically converted to json format by using the JSON endpoint and jQuery.json ajax call.
		 *
		 * @param array $request_data are the rest API data.
		 *
		 * @see generate_default_theme_options()
		 * @see generate_custom_theme_options()
		 * @return array
		 */
		public function fp_builder_colour_values( $request_data ) {
			$colours    = array();
			$parameters = $request_data->get_params();

			if ( isset( $parameters['bb_element'] ) ) {
				$default_theme_opts = generate_default_theme_options();
				$theme_options      = generate_custom_theme_options();
				$colours            = array();
				switch ( $parameters['bb_element'] ) {
					case 'button':
						if ( isset( $default_theme_opts['default_button']['default_colour'] ) ) {
							$colours[] = array(
								'name'           => 'Default',
								'theme'          => $default_theme_opts['default_button']['default_colour'],
								'data'           => 'button',
								'text'           => esc_html( 'Default' ),
								'index'          => 0,
								'default_hex'    => $default_theme_opts['default_button']['default_colour'],
								'hover_hex'      => $default_theme_opts['default_button']['hover_colour'],
								'text_hex'       => $default_theme_opts['default_button']['text_colour'],
								'text_hover_hex' => $default_theme_opts['default_button']['text_hover_colour'],
							);
						}
						break;
					case 'a':
						if ( isset( $default_theme_opts['text_links']['default_colour'] ) ) {
							$colours[] = array(
								'name'           => 'default',
								'theme'          => $default_theme_opts['text_links']['default_colour'],
								'data'           => 'a',
								'text'           => esc_html( 'Default' ),
								'index'          => 0,
								'default_hex'    => '#fff',
								'hover_hex'      => '#fff',
								'text_hex'       => $default_theme_opts['text_links']['default_colour'],
								'text_hover_hex' => $default_theme_opts['text_links']['hover_colour'],
							);
						}
						break;
					default:
						if ( isset( $default_theme_opts['headings'][ 'default_' . $parameters['bb_element'] ] ) ) {
							$colours[] = array(
								'name'           => 'default',
								'theme'          => $default_theme_opts['headings'][ 'default_' . $parameters['bb_element'] ],
								'text'           => esc_html( 'Default' ),
								'data'           => $parameters['bb_element'],
								'index'          => 0,
								'default_hex'    => '#fff',
								'hover_hex'      => '#fff',
								'text_hex'       => $default_theme_opts['headings'][ 'default_' . $parameters['bb_element'] ],
								'text_hover_hex' => $default_theme_opts['headings'][ 'default_' . $parameters['bb_element'] ],
							);
						}
						break;
				}
				foreach ( $theme_options as $color_opt ) :
					if ( in_array( $parameters['bb_element'], $color_opt['applies_to'], true ) ) :
						$data = $color_opt;
						$el   = $parameters['bb_element'];
						unset( $data['applies_to'] );
						unset( $data['colour_name'] );

						$text_hex       = $color_opt['text_colour'];
						$default_hex    = $color_opt['default_colour'];
						$text_hover_hex = $color_opt['text_hover_colour'];
						$hover_hex      = $color_opt['hover_colour'];

						if ( 'background' !== $el && 'button' !== $el && 'a' !== $el ) {
							$hover_hex      = '#fff';
							$default_hex    = '#fff';
							$text_hex       = $color_opt['default_colour'];
							$text_hover_hex = $color_opt['default_colour'];

							if ( '#ffffff' === $text_hex ) {
								$default_hex = '#757575';
								$hover_hex   = '#757575';
							}
						}

						$colours[] = array(
							'name'           => $color_opt['colour_name'],
							'theme'          => $color_opt['default_colour'],
							'data'           => $data,
							'text'           => esc_html( ucwords( $color_opt['colour_name'] ) ),
							'index'          => 1 + count( $colours ),
							'default_hex'    => $default_hex,
							'hover_hex'      => $hover_hex,
							'text_hex'       => $text_hex,
							'text_hover_hex' => $text_hover_hex,
						);
					endif;
				endforeach;
			}

			return $colours;
		}

		/**
		 * Get any custom svg icons (non-font assets) for the BB Icon picker. Set a transient for the icons if it doesn't exist.
		 * Use BB to get the brand icon folder path.
		 *
		 * @param array $request_data are the AJAX parameters.
		 *
		 * @see FLBuilderIcons::get_sets_for_current_site()
		 *
		 * @return array $icons
		 */
		public function fp_builder_icon_values( $request_data ) {
			$icons = array();

			$icon_list = get_transient( 'fp_theme_icons' );
			if ( false === $icon_list || empty( $icon_list ) ) {
				$icon_list = array();

				if ( is_dir( ICON_DIR ) ) {

					$enabled_icons = FLBuilderIcons::get_sets_for_current_site();

					if ( ! empty( $enabled_icons ) ) {
						foreach ( $enabled_icons as $icon_set => $icon_data ) {
							if ( 'brand' === $icon_data['name'] ) {
								$svg_file = scandir( $icon_data['path'] . '/images' );
								if ( ! empty( $svg_file ) ) {
									foreach ( $svg_file as $svg_icon ) {
										if ( preg_match( '/\.(svg)$/', $svg_icon ) ) {
											$icon_list[] = site_url( '/wp-content/bb-icons/' ) . $icon_set . '/images/' . rawurlencode( basename( $svg_icon, '.svg' ) ) . '.svg';
										}
									}
								}
							}
						}
						set_transient( 'fp_theme_icons', $icon_list, 60 * 60 * 3 );
					}
				}
			}

			if ( ! empty( $icon_list ) ) {
				foreach ( $icon_list as $index => $src ) {
					$icons[] = array(
						'src'   => $src,
						'index' => 1 + count( $icons ),
						'title' => basename( $src ),
					);
				}
			}

			return $icons;
		}
	}

	new FbDynamicThemeCss();
}
