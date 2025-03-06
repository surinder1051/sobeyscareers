<?php
/**
 * This is the autoload file that runs FP Foundation.
 *
 * @package fp-foundation
 */

namespace fp;

define( 'FP_FOUNDATION_VERSION', '2.0.14' );

require_once __DIR__ . '/class-config.php';
require_once __DIR__ . '/class-component.php';
require_once __DIR__ . '/helpers/utilities.php';
require_once __DIR__ . '/libs/themeing/init.php';
require_once __DIR__ . '/hooks/admin_bar_add_menu.php';
require_once __DIR__ . '/libs/register_post_types.php';
require_once __DIR__ . '/libs/register_taxonomies.php';
// Add the gutenberg admin file.
require_once __DIR__ . '/hooks/admin_gutenberg_theme.php';


/**
 * Uncomment to enable module tests:
 * define('FP_MODULE_TESTS', load_foundation_config('constant', 'FP_MODULE_TESTS'));
 */

// Here we load the constants from foundation_config.json.
// For php 8, the files are renamed class-classname.php.
$constants = load_foundation_config( 'constant' );
if ( ! empty( $constants ) ) {
	foreach ( $constants as $key => $constant ) {
		define( $key, $constant );
	}
}

// Force load jquery.
define( 'LOAD_JS_JQUERY.INITIALIZE.JS', true ); //phpcs:ignore

// Force load some comments if BB is running.
if ( isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
	if ( ! defined( 'LOAD_JS_FP_JQUERY_VALIDATE_EQUAL.JS' ) ) {
		define( 'LOAD_JS_FP_JQUERY_VALIDATE_EQUAL.JS', true );
	}
	if ( ! defined( 'LOAD_JS_FP_JQUERY_VALIDATE_MAXCOUNT_REPEATERS.JS' ) ) {
		define( 'LOAD_JS_FP_JQUERY_VALIDATE_MAXCOUNT_REPEATERS.JS', true );
	}
}

// Force load facetWP helpers if this plugin is active.
if ( in_array( 'facetwp/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { //phpcs:ignore
	if ( ! defined( 'LOAD_JS_FACETWP_HELPER.JS' ) ) {
		define( 'LOAD_JS_FACETWP_HELPER.JS', true );
	}
}


$force_autoload = array(
	'helpers/reset-component-vars.php'            => true,
	'helpers/admin-css.php'                       => true,
	'helpers/frontend-css.php'                    => true,
	'helpers/adminbar-bg-color.php'               => true,
	'helpers/class-bbmoduleusage.php'             => true,
	'helpers/chevron-it.php'                      => true,
	'helpers/class-fpautologin.php'               => true,
	'helpers/class-generatemonitoredurls.php'     => true,
	'helpers/class-generatemonitoredurlscli.php'  => true,
	'filters/acf-json-sync-path-change.php'       => true,
	'filters/yoast-seo-low-priority.php'          => true,
	'filters/disable-bb-core-modules.php'         => true,
	'filters/get-text-french-fix.php'             => true,
	'hooks/body-classes.php'                      => true,
	'hooks/new-relic-404-patch.php'               => true,
	'hooks/qa-modules.php'                        => true,
	'hooks/wp-head.php'                           => true,
	'helpers/class-trackmoduleusage.php'          => true,
	'helpers/class-trackpluginusage.php'          => true,
	'helpers/class-trackversioning.php'           => true,
	'helpers/class-generatepluginpackages.php'    => true,
	'helpers/class-generatepluginpackagescli.php' => true,
	'assets/js/frontend/focus-visible-main/focus-visible.js' => true,
);

$config      = new Config();
$enqueue_dir = get_template_directory();

/**
 * Autoload the php classes in the fp-foundation classes directory.
 *
 * @param object $config is an instance of the config class.
 * @param string $enqueue_dir is the template directory path as defined by WP. This changes from project to project as the main theme is renamed.
 * @param array  $force_autoload is an array of classes to force load based on defined constants.
 */
function load_foundation_classes( $config, $enqueue_dir, $force_autoload ) {
	// Load custom classes from theme/fp-foundation/classes/* .
	$custom_classes = glob( $enqueue_dir . '/fp-foundation/classes/**/*.php' );

	foreach ( $custom_classes as $key => $custom_component_file ) {
		if ( ! is_string( $custom_component_file ) ) {
			continue;
		}
		if ( strpos( $custom_component_file, 'testing-framework' ) !== false ) {
			continue;
		}
		$constant_name = 'LOAD_' . strtoupper( basename( str_replace( '.php', '', $custom_component_file ) ) );

		$exploded_path = explode( 'fp-foundation/classes/', $custom_component_file );

		if ( strpos( $exploded_path[1], 'libs/' ) !== false ) {
			continue;
		}

		if ( defined( $constant_name ) && constant( $constant_name ) || strpos( $custom_component_file, 'fp-foundation' ) === false ) {
			require_once $custom_component_file;
			$config->track_loaded( $constant_name );
		} elseif ( isset( $exploded_path[1] ) && isset( $force_autoload[ $exploded_path[1] ] ) && ( ! defined( $constant_name ) || defined( $constant_name ) && ! $constant_name ) ) {
			// If php file is forced to autoload and there is no constant defined specifically NOT to, then load it.
			require_once $custom_component_file;
			$config->track_loaded( $constant_name );
		} else {
			$config->track_not_loaded( $constant_name );
		}
	}
}

/**
 * Autoload the js files in the fp-foundation assets directory.
 *
 * @param object $config is an instance of the config class.
 * @param string $enqueue_dir is the template directory path as defined by WP. This changes from project to project as the main theme is renamed.
 * @param array  $force_autoload is an array of files to force load based on defined constants.
 */
function load_foundation_js_assets( $config, $enqueue_dir, $force_autoload ) {
	// Load custom classes from theme/fp-foundation/classes/* .

	$foundation_js_assets = array_merge(
		glob( $enqueue_dir . '/fp-foundation/assets/js/**/*.*.js' ),
		glob( $enqueue_dir . '/fp-foundation/assets/js/**/**/*.*.js' ),
		glob( $enqueue_dir . '/fp-foundation/assets/js/**/*.js' ),
		glob( $enqueue_dir . '/fp-foundation/assets/js/**/**/*.js' ),
	);

	foreach ( $foundation_js_assets as $key => $asset ) {
		if ( ! is_string( $asset ) ) {
			continue;
		}
		$exploded_path = explode( '/fp-foundation/', $asset );

		$constant_name = 'LOAD_JS_' . strtoupper( basename( str_replace( '.php', '', $exploded_path[1] ) ) );
		$asset_url     = get_template_directory_uri() . '/fp-foundation/' . $exploded_path[1];

		if ( strpos( $exploded_path[1], 'libs/' ) !== false ) {
			continue;
		}

		if ( isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
			$action = 'wp_enqueue_scripts';
		} elseif ( strpos( $asset_url, '/js/admin/' ) !== false ) {
			$action = 'admin_enqueue_scripts';
		} else {
			$action = 'wp_enqueue_scripts';
		}

		// Register all fpf js assets for later use.
		add_action(
			$action,
			function () use ( $constant_name, $asset_url ) {
				wp_register_script( $constant_name, $asset_url, null, FP_FOUNDATION_VERSION, true );
				$slug = strtolower( str_replace( 'LOAD_JS_', '', $constant_name ) );
				$slug = str_replace( '.js', '', $slug );
				wp_register_script( $slug, $asset_url, null, FP_FOUNDATION_VERSION, true );
			}
		);

		if ( defined( $constant_name ) && constant( $constant_name ) ) {
			add_action(
				$action,
				function () use ( $constant_name ) {
					wp_enqueue_script( $constant_name );
				}
			);
			$config->track_loaded( $constant_name, 'js' );
		} elseif ( isset( $exploded_path[1] ) && isset( $force_autoload[ $exploded_path[1] ] ) && ( ! defined( $constant_name ) || defined( $constant_name ) && ! $constant_name ) ) {
			add_action(
				$action,
				function () use ( $constant_name ) {
					wp_enqueue_script( $constant_name );
				}
			);

			$config->track_loaded( $constant_name, 'js' );
		} else {
			$config->track_not_loaded( $constant_name, 'js' );
		}
	}
}

/**
 * Autoload all css files in the parent theme classes dir.
 *
 * @param string $enqueue_dir is the theme file path.
 */
function load_theme_classes( $enqueue_dir ) {
	$theme_classes = array();
	// Load custom classes from theme/classes.
	$theme_classes = array_merge( $theme_classes, glob( $enqueue_dir . '/classes/**/*.php' ) );

	// Load custom classes from theme/classes/* .
	$theme_classes = array_merge( $theme_classes, glob( $enqueue_dir . '/classes/*.php' ) );

	foreach ( $theme_classes as $key => $class ) {
		require_once $class;
	}
}

// Call all of the autoload functions.
load_foundation_js_assets( $config, $enqueue_dir, $force_autoload );
load_foundation_classes( $config, $enqueue_dir, $force_autoload );
load_theme_classes( $enqueue_dir );

/**
 * Load all classes in the theme component directory
 *
 * @param string $enqueue_dir is the theme path.
 */
function load_components( $enqueue_dir ) {
	$components = glob( $enqueue_dir . '/components/**/*.php' );

	$components = array_reverse( $components );
	global $fp_loaded_components;

	foreach ( $components as $key => $custom_component_file ) {
		$component_name = str_replace( 'class-', '', str_replace( '.php', '', basename( $custom_component_file ) ) );
		$module_class   = 'fp\components\\' . $component_name;
		if ( ! is_string( $custom_component_file ) ) {
			continue;
		}
		// Skip: this is the template file that contains the html (view).
		if ( strpos( $custom_component_file, '.tpl.' ) !== false ) {
			continue;
		}
		// Skip: this the template file for running tests.
		if ( strpos( $custom_component_file, '.test.' ) !== false ) {
			continue;
		}
		// Skip: extension classes.
		if ( strpos( $custom_component_file, 'class-extend' ) !== false ) {
			continue;
		}
		// Skip modules that have been loaded from the theme with same class name.
		if ( class_exists( $module_class ) ) {
			continue;
		}

		require_once $custom_component_file;

		$fp_loaded_components[] = $custom_component_file;

		if ( class_exists( 'FLBuilder' ) ) {
			// Register the module /w Beaver Builder.
			$component_name = str_replace( '.php', '', basename( $custom_component_file ) );
			add_action(
				'init',
				function () use ( $custom_component_file, $module_class ) {

					require_once $custom_component_file;
					if ( class_exists( $module_class ) ) {
						$module = new $module_class( true );

						try {
							// This function may not exist in all modules if they are older. So try to call the function s and fall back to false.
							if ( method_exists( $module, 'extend_js_theme' ) ) {
								$module->theme_override_js = $module->extend_js_theme();
							}
						} catch ( \Exception $e ) {
							$module->theme_override_js = false;
						}
					}
					if ( defined( 'FP_LOAD_SPECIFIC_MODULES' ) && ! empty( $module->component_load_category ) ) {
						$component_load_category = $module->component_load_category;
						if ( isset( FP_LOAD_SPECIFIC_MODULES[ $component_load_category ] ) && ! empty( FP_LOAD_SPECIFIC_MODULES[ $component_load_category ] ) ) {
							// If there is module filters for this category of modules check if this module exists.
							if ( ! in_array( '*', FP_LOAD_SPECIFIC_MODULES[ $component_load_category ], true ) && ! in_array( $module_class, FP_LOAD_SPECIFIC_MODULES[ $component_load_category ], true ) ) {
								// If * wildcard doesn't exist and module is NOT included in the array then it's not to be loaded.
								return;
							}
						} elseif ( ! empty( $component_load_category ) ) {
							// If there is a load category configured in the module but not defined in functions.php then don't load this module.
							return;
						}
					}

					if ( isset( $module ) && class_exists( 'FLBuilder' ) ) {
						\FLBuilder::register_module(
							$module_class,
							$module->fields
						);
					}
				}
			);
		}
	}
}

/**
 * Load css files within a component, including the defaults and files that extend the original compoenent.
 *
 * @param string $enqueue_dir is the theme directory path.
 */
function load_global_component_themes( $enqueue_dir ) {
	// Create a global component themes array to be used to enqueue css.
	global $component_themes;
	$component_themes       = array();
	$component_themes_files = array_merge( $component_themes, glob( $enqueue_dir . '/components/*/*_theme.scss' ) );
	$component_themes_files = array_merge( $component_themes_files, glob( $enqueue_dir . '/components/*/*_theme-*.scss' ) );
	foreach ( $component_themes_files as $key => $value ) {
		$value = explode( '/components/', $value );
		$value = explode( '/', $value[1] );
		$component_themes[ str_replace( '.scss', '', $value[1] ) ] = $value[0] . '/' . str_replace( '.scss', '.css', $value[1] );
	}
}

/**
 * Load js files within a component, including the defaults and files that extend the original component.
 *
 * @param string $enqueue_dir is the theme directory path.
 */
function load_global_component_js( $enqueue_dir ) {
	// Create a global component themes array to be used to enqueue css.
	global $component_js;
	$component_js       = array();
	$component_js_files = array_merge( $component_js, glob( $enqueue_dir . '/components/*/*_theme.js' ) );
	foreach ( $component_js_files as $key => $value ) {
		$value = explode( '/components/', $value );
		$value = explode( '/', $value[1] );
		// Load the global array if there is a theme.js file.
		$component_js[ str_replace( '_theme.js', '', $value[1] ) ]['theme'] = $value;
		// Load the concatented file into the global array if there is a theme.js and a concat file.
		if ( file_exists( $enqueue_dir . '/dist/components/' . $value[0] . '/' . str_replace( '_theme.js', '_concat.js', $value[1] ) ) ) {
			$component_js[ str_replace( '_theme.js', '', $value[1] ) ]['concat'] = $value[0] . '/' . str_replace( '_theme.js', '_concat.js', $value[1] );
		}
	}
}

/**
 * Autoload BB components if BB is loaded.
 *
 * @see load_components()
 * @see load_global_component_themes()
 */
if ( class_exists( 'FLBuilderModule' ) ) {
	load_components( $enqueue_dir );
	load_global_component_themes( $enqueue_dir );
	load_global_component_js( $enqueue_dir );
}

// Load the configs listing without errors.
if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() && isset( $_GET['fp-show-config'] ) ) { //phpcs:ignore
	add_action(
		'template_redirect',
		function() use ( $config ) {
			$config->show_config();
		}
	);
}
