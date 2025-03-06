<?php
/**
 * Register Beaver Builder components and customize hooks.
 *
 * @package fp-foundation
 * Configuration Examples (top functions.php)
 * define('FP_MODULE_FILTERS', array(
 * 'z_pattern' => array(
 * 'remove' => array('button_color')
 * )
 * ));
 * define('FP_MODULE_DEFAULTS', array(
 * 'z_pattern' => array(
 * 'background_image_size' => 'banner_1260x600'
 * )
 * ));
 */

namespace fp;

use Exception;
use FLBuilder;
use FLBuilderModel;
use WP_Query;
use WP_REST_Server;
use FLBuilderModule;


global $fp_components, $fp_defer_css, $fp_defer_js;
$fp_components = array();
$fp_defer_css  = array();
$fp_defer_js   = array();

if ( class_exists( 'FLBuilderModule' ) ) {

	/**
	 * Custom class to enable FlowPress extensions on the BB settings module
	 */
	class Component extends FLBuilderModule {

		/**
		 * The component being registered.
		 *
		 * @var $component;
		 */
		public $component;
		/**
		 * Stores a list of registered components locally.
		 *
		 * @var $fp_components;
		 */
		protected $fp_components;
		/**
		 * Stores the dynamic data class for settings that require it eg: post loading
		 *
		 * @var $fp_dynamic_data
		 */
		protected $fp_dynamic_data;

		/**
		 * Component intialization. Load dynamic data php file to register dynamic content loading in the module settings if set.
		 * Loads modules into their custom categories, if set.
		 *
		 * @param boolean $init_fields Optional.
		 *
		 * @see self::load_global_module_overwrites()
		 * @see self::init()
		 * @see self::init_config()
		 * @see self:setup()
		 * @see self::init_fields()
		 * @see self::init_forms()
		 * @see self::filter_fields()
		 * @see DynamicData::init_data_provider_tab()
		 *
		 * @return false if the module doesn't exist
		 */
		public function __construct( $init_fields = false ) {

			require_once 'libs/class-dynamicdata.php';
			$this->fp_dynamic_data = new DynamicData( $this );

			if ( defined( 'FP_LOAD_SPECIFIC_MODULES' ) && ! empty( $this->component_load_category ) ) {
				if ( ! empty( FP_LOAD_SPECIFIC_MODULES[ $this->component_load_category ] ) ) {
					// If there is module filters for this category of modules check if this module exists.
					if ( ! in_array( '*', FP_LOAD_SPECIFIC_MODULES[ $this->component_load_category ], true ) && ! in_array( $this->component, FP_LOAD_SPECIFIC_MODULES[ $this->component_load_category ], true ) ) {
						// If * wildcard doesn't exist and module is NOT included in the array then it's not to be loaded.
						return false;
					}
				} elseif ( ! empty( $this->component_load_category ) ) {
					// If there is a load category configured in the module but not defined in functions.php then don't load this module.
					return false;
				}
			}
			$this->load_global_module_overwrites();
			$this->init();
			$this->init_config();

			$this->setup();
			$this->init_fields();
			$this->init_forms();
			$this->filter_fields();

			$this->fp_dynamic_data->init_data_provider_tab();
		}

		/**
		 * Initalize the module with extended settings.
		 *
		 * @see self::reg_sc()
		 * @see DynamicData::register_api_load_dynamic_paginated_data()
		 * @see self::register_assets()
		 * @see self::add_js_defer_attribute()
		 * @see self::enqueue_css()
		 * @see self::add_css_defer_attribute()
		 * @see self::variation_testing_pre_process_data()
		 */
		public function init() {
			global $fp_components;
			$this->fp_components = &$fp_components;

			// Check to make sure that modules aren't initiatlized more then once, or multiple actions will be fired.
			if ( isset( $this->fp_components[ $this->component ] ) ) {
				return;
			}

			$this->fp_components[ $this->component ]['variants'] = $this->variants;
			$this->fp_components[ $this->component ]['basedir']  = $this->base_dir;

			if ( empty( $this->version ) ) {
				$this->version = '0.1.0';
			}

			$this->fp_components[ $this->component ]['version'] = ! empty( $this->version ) ? $this->version : '0.1.0';
			$this->fp_components[ $this->component ]            = apply_filters( 'fp_component_vars_init', $this->fp_components[ $this->component ], $this->component, $this );

			add_action( 'init', array( $this, 'reg_sc' ) );
			add_action( 'rest_api_init', array( $this->fp_dynamic_data, 'register_api_load_dynamic_paginated_data' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

			// Adds overwrite hook for BB to user our existing shortcode instead of frontend.php file.
			add_filter( 'fl_builder_module_frontend_custom_' . $this->component, array( $this, 'render_sc' ), 10, 3 );

			add_filter(
				'fl_builder_render_module_html_content',
				function ( $content, $type, $settings, $module ) {
					if ( isset( $module->exclude_from_post_content ) && $module->exclude_from_post_content && ! isset( $_GET['test_module'] ) ) { //phpcs:ignore
						// Exclude from saving to post_content / but allow for testing of modules.
						echo '';
					} else {
						echo $content; //phpcs:ignore
					}
				},
				10,
				4
			);

			add_filter( 'script_loader_tag', array( $this, 'add_js_defer_attribute' ), 10, 2 );

			if ( isset( $this->load_in_header ) && $this->load_in_header ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css' ) );
			} else {
				add_filter( 'style_loader_tag', array( $this, 'add_css_defer_attribute' ), 10, 4 );
			}

			add_filter( 'pre_process_data', array( $this, 'variation_testing_pre_process_data' ), 10, 1 );

		}

		/**
		 * Placeholder for individual components to have their own setup function where we can put filters and other module setup code.
		 */
		public function setup(){}

		/**
		 * This allows for global module setting overwrites such as
		 * define('FP_MODULE_DEFAULTS', array(
		 * 'z_pattern' => array(
		 * 'load_in_header' => true,
		 *  )
		 * ));
		 *
		 * @param array $atts are the saved module settings.
		 */
		public function variation_testing_pre_process_data( $atts = array() ) {

			if ( ! empty( $_GET['variant'] ) ) { //phpcs:ignore
				$atts['classes'] .= ' ' . $_GET['variant']; //phpcs:ignore
			}

			return $atts;
		}

		/**
		 * Optional: Set module defaults for testing and load them.
		 *
		 * @return void on check fail.
		 */
		public function load_global_module_overwrites() {

			if ( ! defined( 'FP_MODULE_DEFAULTS' ) ) {
				return;
			}
			if ( empty( FP_MODULE_DEFAULTS[ $this->component ] ) ) {
				return;
			}

			foreach ( FP_MODULE_DEFAULTS[ $this->component ] as $key => $value ) {
				if ( property_exists( $this, $key ) ) {
					$this->$key = FP_MODULE_DEFAULTS[ $this->component ][ $key ];
				}
			}
		}

		/**
		 * Configure BB Module based on component config. Run this when BB editor is loaded.
		 * Autload component JS and CSS files when the editor is active.
		 *
		 * @see FLBuilder::__construct()
		 */
		public function init_config() {
			if ( isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
				global $component_themes;
				$min_css = '.min';

				$this->component_dist_dir = get_template_directory_uri() . '/dist/components/' . $this->component . '/';
				$this->add_css( FP_PREFIX . '-' . $this->component . '_css', $this->component_dist_dir . $this->component . $min_css . '.css' );

				$current_blog_id = ( is_multisite() ) ? get_current_blog_id() : 1;
				$theme_dir       = get_template_directory_uri() . '/dist/components/' . $this->component . '/';
				$theme_deps      = ( isset( $this->deps_css ) && is_array( $this->deps_css ) ) ? array_merge( $this->deps_css, array( FP_PREFIX . '-' . $this->component . '_css' ) ) : array( FP_PREFIX . '-' . $this->component . '_css' );

				// Load blog specific theme if exists, otherwise load general theme if exists.
				if ( $current_blog_id > 1 && isset( $component_themes[ $this->component . '_theme-' . $current_blog_id ] ) ) {
					$this->add_css( FP_PREFIX . '-' . $this->component . '_css_theme', $theme_dir . $this->component . '_theme-' . $current_blog_id . $min_css . '.css', $theme_deps );
				} elseif ( isset( $component_themes[ $this->component . '_theme' ] ) ) {
					$this->add_css( FP_PREFIX . '-' . $this->component . '_css_theme', $theme_dir . $this->component . '_theme' . $min_css . '.css', $theme_deps );
				}
			}
			parent::__construct(
				array(
					'name'            => $this->component_name, // These should be translated at the module level.
					'description'     => $this->component_description,
					'category'        => $this->component_category,
					'editor_export'   => true, // Defaults to true and can be omitted.
					'enabled'         => true, // Defaults to true and can be omitted.
					'partial_refresh' => true, // Defaults to false and can be omitted.
				)
			);
		}

		/**
		 * Extend parent init_fields, but do nothing extra.
		 */
		public function init_fields(){}

		/**
		 * Initialize beaver builder forms for sub module support.
		 *
		 * @see FLBuilder::register_settings_form()
		 */
		public function init_forms() {
			if ( isset( $this->forms ) && $this->forms && class_exists( 'FLBuilder' ) ) {
				foreach ( $this->forms as $form_settings ) {
					FLBuilder::register_settings_form(
						$form_settings[0],
						$form_settings[1]
					);
				}
			}
		}

		/**
		 * Further customize modules by removing unwanted fields from the settings forms.
		 *
		 * @param array  $array is the settings form array.
		 * @param string $val is the key (element) to remove.
		 *
		 * @see self::recursive_removal()
		 *
		 * @return array $array
		 */
		public function recursive_removal( &$array, $val ) {
			if ( is_array( $array ) ) {
				foreach ( $array as $key => &$array_element ) {
					if ( $key === $val ) {
						unset( $array[ $key ] );
					}
					if ( is_array( $array_element ) ) {
						$this->recursive_removal( $array_element, $val );
					} else {
						if ( $array_element === $val ) {
							unset( $array[ $key ] );
						}
					}
				}
				return $array;
			}
		}

		/**
		 * Filter existing fields, so sites can have slightly different setups for each module.
		 * define('FP_MODULE_FILTERS', array(
		 * 'z_pattern' => array(
		 * 'remove' => array('button_color')
		 * ) ));
		 *
		 * @see self::recursive_removal()
		 */
		public function filter_fields() {

			if ( ! defined( 'FP_MODULE_FILTERS' ) ) {
				return;
			}
			$filters = FP_MODULE_FILTERS;
			if ( isset( $filters[ $this->component ] ) ) {
				$component_filters = $filters[ $this->component ]['remove'];
				foreach ( $component_filters as $field_key ) {
					$this->recursive_removal( $this->fields, $field_key, 'fields' );
				}
			}
		}

		/**
		 * Set each custom component as a shortcode and then send the shortcode to the view render function.
		 *
		 * @see self::render_sc()
		 */
		public function reg_sc() {
			add_shortcode( $this->component, array( $this, 'render_sc' ) );
		}

		/**
		 * Register the custom component js and css files component.js, component.css
		 * If the component has defer turned on, add the handles to the global arrays.
		 * Check here if there is a theme js file, and a theme js override flag
		 * component_themes, component_js are set in autoload
		 *
		 * @see load_global_component_themes()
		 * @see load_global_component_js();
		 */
		public function register_assets() {
			global $post, $component_themes, $fp_defer_css, $fp_defer_js, $component_js;

			// Force only min files to be loaded, disabling gulp of dist/*.js files only doing min.js.
			$min_js  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			$min_css = ( defined( 'STYLE_DEBUG' ) && STYLE_DEBUG ) ? '' : '.min';

			$this->component_dir      = get_template_directory_uri() . '/components/' . $this->component . '/';
			$this->component_dist_dir = get_template_directory_uri() . '/dist/components/' . $this->component . '/';

			if ( ! isset( $this->deps_css ) ) {
				$this->deps_css = array();
			}
			if ( ! isset( $this->deps_js ) ) {
				$this->deps_js = array();
			}

			try {
				// This function may not exist in all modules if they are older. So try to call the function s and fall back to false.
				if ( method_exists( $this, 'extend_js_theme' ) ) {
					$this->theme_override_js = $this->extend_js_theme();
				}
			} catch ( \Exception $e ) {
				$this->theme_override_js = false;
			}

			// Register any js and css files from remote sources.
			if ( isset( $this->deps_js_remote ) && count( $this->deps_js_remote ) > 0 ) {
				foreach ( $this->deps_js_remote as $asset_url ) {
					$asset_url_id = wp_parse_url( $asset_url );
					$asset_url_id = str_replace( '.', '-', $asset_url_id['path'] );

					wp_register_script( FP_PREFIX . '-' . $asset_url_id, $asset_url, $this->deps_js, $this->version, true );

					$this->deps_js[] = FP_PREFIX . '-' . $asset_url_id;

					if ( isset( $this->defer_js ) && $this->defer_js ) {
						$fp_defer_js[] = FP_PREFIX . '-' . $asset_url_id;
					}
				}
			}

			if ( isset( $this->deps_css_remote ) && count( $this->deps_css_remote ) > 0 ) {
				foreach ( $this->deps_css_remote as $asset_url ) {
					$asset_url_id = wp_parse_url( $asset_url );
					$asset_url_id = str_replace( '.', '-', $asset_url_id['path'] );
					wp_register_style( FP_PREFIX . '-' . $asset_url_id, $asset_url, array(), $this->version );
					$this->deps_css[] = FP_PREFIX . '-' . $asset_url_id;

					if ( isset( $this->defer_css ) && $this->defer_css ) {
						$fp_defer_css[] = FP_PREFIX . '-' . $asset_url_id;
					}
				}
			}
			// Register Component CSS.
			wp_register_style(
				FP_PREFIX . '-' . $this->component . '_css',
				$this->component_dist_dir . $this->component . $min_css . '.css',
				$this->deps_css,
				$this->version,
			);
			if ( isset( $this->defer_css ) && $this->defer_css ) {
				$fp_defer_css[] = FP_PREFIX . '-' . $this->component . '_css';
			}

			// Register Component JS.

			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				if ( ! property_exists( $this, 'theme_override_js' ) || false === $this->theme_override_js ) {
					wp_register_script(
						FP_PREFIX . '-' . $this->component . '_js',
						$this->component_dir . $this->component . $min_js . '.js',
						$this->deps_js,
						$this->version,
						true,
					);
				}
				// Load the theme js file.
				if ( ( isset( $component_js[ $this->component ] ) ) ) {
					$theme_js_deps = ( ! property_exists( $this, 'theme_override_js' ) || false === $this->theme_override_js ) ? array( FP_PREFIX . '-' . $this->component . '_js' ) : array();
					wp_register_script(
						FP_PREFIX . '-' . $this->component . '_js_theme',
						$this->component_dir . $this->component . '_theme' . $min_js . '.js',
						$theme_js_deps,
						$this->version,
						true,
					);
				}
			} else {
				if ( ! property_exists( $this, 'theme_override_js' ) || false === $this->theme_override_js ) {
					if ( isset( $component_js[ $this->component ]['concat'] ) ) {
						$min_js = '_concat' . $min_js;
					}
					wp_register_script(
						FP_PREFIX . '-' . $this->component . '_js',
						$this->component_dist_dir . $this->component . $min_js . '.js',
						$this->deps_js,
						$this->version,
						true,
					);
					// Backwards compatibility - enable loading of the default AND theme js if there is no concat file.
					if ( ! isset( $component_js[ $this->component ]['concat'] ) && isset( $component_js[ $this->component ]['theme'] ) ) {
						$min_js = '_theme' . $min_js;
						wp_register_script(
							FP_PREFIX . '-' . $this->component . '_js_theme',
							$this->component_dist_dir . $this->component . $min_js . '.js',
							$this->deps_js,
							$this->version,
							true,
						);
					}
				} else {
					if ( isset( $component_js[ $this->component ]['theme'] ) ) {
						$min_js = '_theme' . $min_js;
					}
					wp_register_script(
						FP_PREFIX . '-' . $this->component . '_js',
						$this->component_dist_dir . $this->component . $min_js . '.js',
						$this->deps_js,
						$this->version,
						true,
					);
				}
			}
			if ( isset( $this->defer_js ) && $this->defer_js ) {
				$fp_defer_js[] = FP_PREFIX . '-' . $this->component . '_js';
				$fp_defer_js[] = FP_PREFIX . '-' . $this->component . '_js_theme';
			}

			$current_blog_id = ( is_multisite() ) ? get_current_blog_id() : 1;

			// Register any avialable component themes.

			if ( isset( $component_themes[ $this->component . '_theme' ] ) || isset( $component_themes[ $this->component . '_theme-' . $current_blog_id ] ) ) {

				$theme_dir  = get_template_directory_uri() . '/dist/components/' . $this->component . '/';
				$theme_deps = array_merge( $this->deps_css, array( FP_PREFIX . '-' . $this->component . '_css' ) );

				// Load blog specific theme if exists, otherwise load general theme if exists.
				if ( $current_blog_id > 1 && isset( $component_themes[ $this->component . '_theme-' . $current_blog_id ] ) ) {

					wp_register_style(
						FP_PREFIX . '-' . $this->component . '_css_theme',
						$theme_dir . $this->component . '_theme-' . $current_blog_id . $min_css . '.css',
						$theme_deps,
						$this->version,
					);

					$this->add_css( FP_PREFIX . '-' . $this->component . '_css_theme', $theme_dir . $this->component . '_theme-' . $current_blog_id . $min_css . '.css', $theme_deps );
				} elseif ( isset( $component_themes[ $this->component . '_theme' ] ) ) {

					wp_register_style(
						FP_PREFIX . '-' . $this->component . '_css_theme',
						$theme_dir . $this->component . '_theme' . $min_css . '.css',
						$theme_deps,
						$this->version,
					);

					$this->add_css( FP_PREFIX . '-' . $this->component . '_css_theme', $theme_dir . $this->component . '_theme' . $min_css . '.css', $theme_deps );
				}

				if ( isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
					$this->add_css( FP_PREFIX . '-' . $this->component . '_css_theme', $theme_dir . $this->component . '_theme' . $min_css . '.css', $theme_deps );
				}

				if ( isset( $this->defer_css ) && $this->defer_css ) {
					$fp_defer_css[] = FP_PREFIX . '-' . $this->component . '_css_theme';
				}
			}

			if ( isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
				$this->add_css( FP_PREFIX . '-' . $this->component . '_css', $this->component_dist_dir . $this->component . $min_css . '.css' );
			}

		}

		/**
		 * Enqueue any compoenent registered css files for logged in users.
		 * Requires the component enable_css flag to be true.
		 */
		public function enqueue_css() {
			if ( isset( $this->enable_css ) && $this->enable_css && is_user_logged_in() ) {
				wp_enqueue_style( FP_PREFIX . '-' . $this->component . '_css' );
				wp_enqueue_style( FP_PREFIX . '-' . $this->component . '_css_theme' );

				if ( in_array( 'brand', $this->deps_css ) ) { //phpcs:ignore
					$test = wp_style_is( 'brand', 'registered' );
					if ( ! $test ) {
						echo "Warning 'brand' font package is missing, upload to Beaver Builder Icon Package";
					}
				}
			}

			if ( isset( $this->deps_css_remote ) && count( $this->deps_css_remote ) > 0 ) {
				foreach ( $this->deps_css_remote as $asset_url ) {
					$asset_url_id = wp_parse_url( $asset_url );
					$asset_url_id = str_replace( '.', '-', $asset_url_id['path'] );
					wp_enqueue_style( FP_PREFIX . '-' . $asset_url_id );
				}
			}
		}

		/**
		 * Allow js files to be deferred in a module to help with performance. Scripts are not deferred when BB editor is active.
		 *
		 * @since 1.8.71
		 *
		 * @param string $tag is the html of the script tag.
		 * @param string $handle is the registered js asset.
		 *
		 * @return string new tag, or $tag
		 */
		public function add_js_defer_attribute( $tag, $handle ) {
			global $fp_defer_js;
			if ( ! empty( $fp_defer_js ) && ! isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
				if ( in_array( $handle, $fp_defer_js, true ) ) {
					return str_replace( ' src', " defer='defer' src", $tag );
				}
			}
			return $tag;
		}

		/**
		 * Allow css files to be deferred in a module to help with performance. Styles are not deferred when BB editor is active.
		 *
		 * @since 1.8.71
		 *
		 * @param string $tag is the html of the script tag.
		 * @param string $handle is the registered js asset.
		 *
		 * @return string new tag, or $tag
		 */
		public function add_css_defer_attribute( $tag, $handle ) {
			global $fp_defer_css;
			if ( ! empty( $fp_defer_css ) && ! isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
				if ( in_array( $handle, $fp_defer_css, true ) ) {
					return str_replace( " media='all'", " media='none' onload='if(media!=\"all\")media=\"all\"'", $tag );
				}
			}
			return $tag;
		}

		/**
		 * Enqueue any compoenent registered css and js files for all users.
		 * Requires the component enable_css and enable_js flags to be true.
		 */
		public function enqueue() {

			if ( isset( $this->enable_css ) && $this->enable_css ) {
				wp_enqueue_style( FP_PREFIX . '-' . $this->component . '_css' );

				// Enqueue global component themes if exist.
				wp_enqueue_style( FP_PREFIX . '-' . $this->component . '_css_theme' );
			}
			if ( isset( $this->enable_js ) && $this->enable_js ) {
				wp_enqueue_script( FP_PREFIX . '-' . $this->component . '_js' );

				// Enqueue theme js files if they exist.
				wp_enqueue_script( FP_PREFIX . '-' . $this->component . '_js_theme' );
			}

			// Make sure all js dependencies are loaded when module shortcode is used.
			if ( isset( $this->deps_js ) && count( $this->deps_js ) > 0 ) {
				foreach ( $this->deps_js as $dep ) {
					wp_enqueue_script( $dep );
				}
			}

			if ( isset( $this->deps_js_remote ) && count( $this->deps_js_remote ) > 0 ) {
				foreach ( $this->deps_js_remote as $asset_url ) {
					$asset_url_id = wp_parse_url( $asset_url );
					$asset_url_id = str_replace( '.', '-', $asset_url_id['path'] );
					wp_enqueue_script( FP_PREFIX . '-' . $asset_url_id );
				}
			}

			if ( isset( $this->deps_css_remote ) && count( $this->deps_css_remote ) > 0 ) {
				foreach ( $this->deps_css_remote as $asset_url ) {
					$asset_url_id = wp_parse_url( $asset_url );
					$asset_url_id = str_replace( '.', '-', $asset_url_id['path'] );
					wp_enqueue_style( FP_PREFIX . '-' . $asset_url_id );
				}
			}
		}

		/**
		 * Setup shortcode defaults based on component fields data.
		 *
		 * @param array $atts are the form fields settings.
		 */
		public function get_defaults( $atts ) {
			// use 'node_id' instead of 'id' because BB $ids are used within frontend.css.php and conflict with passed in ids.
			$defaults      = array(
				'classes' => '',
				'node_id' => null,
				'post_id' => null,
				'term_id' => null,
			);
			$original_atts = $atts;
			if ( isset( $this->fields ) && is_array( $this->fields ) ) {
				foreach ( $this->fields as $key => $tab ) {
					foreach ( $tab['sections'] as $key => $section ) {
						if ( isset( $section['fields'] ) && is_array( $section['fields'] ) ) {
							foreach ( $section['fields'] as $field_key => $field ) {
								if ( isset( $field['default'] ) ) {
									$defaults[ $field_key ] = $field['default'];
								} else {
									$defaults[ $field_key ] = null;
								}
							}
						}
					}
				}
			}

			if ( ! empty( $this->dynamic_data_feed_parameters['taxonomies'] ) ) {
				foreach ( $this->dynamic_data_feed_parameters['taxonomies'] as $taxonomies ) {
					global $post;
					foreach ( $taxonomies as $taxonomy_name => $taxonomy_data ) {
						if ( empty( $atts[ "taxonomy_$taxonomy_name" ] ) ) {
							$atts[ "taxonomy_$taxonomy_name" ] = ( ! empty( $post ) ) ? wp_get_post_terms(
								$post->ID,
								$taxonomy_name,
								array(
									'fields' => 'ids',
								)
							) : '';
						}
					}
				}
			}
			$atts = shortcode_atts(
				$defaults,
				$atts
			);
			if ( $original_atts ) {
				foreach ( $original_atts as $key => $value ) {
					if ( empty( $atts[ $key ] ) ) {
						$atts[ $key ] = $value;
					}
				}
			}
			return $atts;
		}

		/**
		 * Return any atts/settings modified by the component to BB to render in the theme file.
		 *
		 * @param array  $atts are the form settings.
		 * @param object $module is the instance of the module.
		 *
		 * @return array $atts
		 */
		public function pre_process_data( $atts, $module ) {
			return $atts;
		}

		/**
		 * This is a bit tricky, it allows for us to use regular shortcodes with conditional frontend.css.php BB files.
		 *
		 * @param array $atts are the form settings.
		 *
		 * @return string $id
		 */
		public function add_dynamic_inline_component_styles( $atts ) {
			global $added_inline_style;
			// First we need to generate a unique instance ID of the custom module css file based on passed in attributes.
			ob_start();
			$settings = (object) $atts;
			$id       = '';
			include $this->base_dir . '/includes/frontend.css.php';
			$id              = md5( ob_get_clean() );
			$atts['node_id'] = $id;

			// Then we use that ID to associate the css rules to that id.
			ob_start();
			$settings = (object) $atts;
			include $this->base_dir . '/includes/frontend.css.php';
			$css = ob_get_clean();

			if ( ! isset( $added_inline_style[ $id ] ) ) {
				// Only add inline style once.
				wp_add_inline_style( FP_PREFIX . '-' . $this->component . '_css', $css );
				$added_inline_style[ $id ] = true;
			}

			return $id;
		}

		/**
		 * Deprecated - remove from all modules.
		 */
		public function get_sample_text() {
			return '';
		}

		/**
		 * Add classes to the component module by name. Additional classes can be added via pre-process as $atts['classes']
		 *
		 * @param string $extra are optional additional classes.
		 */
		public function component_class( $extra = '' ) {
			$linted = 'component-' . str_replace( '_', '-', $this->component );
			if ( isset( $this->atts['classes'] ) && ! empty( $this->atts['classes'] ) ) {
				$linted .= ' ' . $this->atts['classes'];
			}
			echo "class=\"component_{$this->component} $linted $extra\""; // phpcs:ignore
		}

		/**
		 * Render the actual shortcode and kick of all necessary prerequisite functions]
		 *
		 * @param array       $atts are the BB form settings.
		 * @param object|null $module is the module instance (optional).
		 *
		 * @see self::get_defaults()
		 * @see fp_dynamic_data::pre_load_dynamic_data()
		 * @see self::pre_process_data()
		 * @see self::enqueue()
		 *
		 * @return string
		 */
		public function render_sc( $atts = array(), $module = null ) {
			global $wpdb;
			$num_queries_start = $wpdb->num_queries;
			$atts              = $this->get_defaults( $atts );
			$atts              = $this->fp_dynamic_data->pre_load_dynamic_data( $atts );

			// Disabled front end processing which causes issues from components firing on admin side.
			// Adding wp_doing_ajax Apr 27, 2021 as some gigya ajax modules were not rendering (recipe_card).
			if ( ! is_admin() || wp_doing_ajax() ) {
				$atts = $this->pre_process_data( $atts, $module );
				$atts = apply_filters( 'pre_process_data', $atts );
			}
			$this->atts = $atts;
			$node_id    = 'generic';

			$this->enqueue();

			if ( isset( $module->node ) ) {
				$node_id = $module->node;
			} elseif ( file_exists( $this->base_dir . '/includes/frontend.css.php' ) ) {
				$node_id = $this->add_dynamic_inline_component_styles( $atts );
			}

			if ( isset( $atts ) && ! empty( $atts ) ) {
				// If atts is empty then don't bother adding this as the component should be hidden.
				$atts['node_id'] = $node_id;
				extract( $atts ); // phpcs:ignore
			}

			if ( $module == null || empty( $module ) ) { // phpcs:ignore
				// Make sure that if shortcode is used that $module object is setup for use in tpl files.
				$module           = (object) array();
				$module->settings = json_decode( json_encode( $atts, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE ), false ); //phpcs:ignore
			}

			ob_start();
			if ( file_exists( $this->base_dir . '/' . $this->component . '_theme.tpl.php' ) ) {
				include $this->base_dir . '/' . $this->component . '_theme.tpl.php';
			} else {
				include $this->base_dir . '/' . $this->component . '.tpl.php';
			}
			$tpl_content = ob_get_contents();
			ob_end_clean();

			$shortcode_content = '';

			if ( empty( $atts['no_container'] ) || ( ! empty( $atts['no_container'] ) && 'false' === $atts['no_container'] ) ) {
				$shortcode_content = "<div class='fl-module-custom fl-module-" . $this->component . ' fl-node-' . $node_id . "' data-type='fp-module-" . $this->component . "'>";
			}
			$shortcode_content .= $tpl_content;
			if ( empty( $atts['no_container'] ) || ( ! empty( $atts['no_container'] ) && 'false' === $atts['no_container'] ) ) {
				$shortcode_content .= '</div>';
			}

			if ( ! empty( $_GET['debug_module'] ) ) { // phpcs:ignore
				$debug_data    = json_encode( $atts ); //phpcs:ignore
				$debug_content = "<span id='debug_module_data_" . $node_id . "'></span>
				<script>
				document.getElementById(\"debug_module_data_" . $node_id . "\").appendChild(
					renderjson($debug_data)
				);
				</script>";
				echo $debug_content; // phpcs:ignore
			}

			if ( isset( $_GET['test_queries'] ) ) { // phpcs:ignore
				// Log number of queries per module.
				error_log( 'Number of queries $this->component:' . ( $wpdb->num_queries - $num_queries_start ) ); // phpcs:ignore
				do_action( 'qm/debug', 'Number of queries $this->component = ' . ( $wpdb->num_queries - $num_queries_start ) ); // phpcs:ignore
			}

			return $shortcode_content;
		}
	}
}
