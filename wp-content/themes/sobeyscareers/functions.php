<?php

/**
 * FlowPress functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package fp
 */

//  Make this theme seem as it's BB theme so that BB doesnt' force loading default button classes
define('FL_THEME_VERSION', 1);
define('FP_TD', 'fp');

// To show avialable classes to autoload login and visit with ?fp-show-config=1

// PHP FP Foundation Helpers
define('LOAD_WP_HEAD', true);
define('LOAD_CLASS-WP-BOOTSTRAP-NAVWALKER', true);
define('LOAD_BB_FIELD_POST_SELECT_DROPDOWN', true);
define("LOAD_SHORTCODES", true);
define("LOAD_STYALIZED_SELECT_DROPDOWNS", true);
define("LOAD_STOP_SEARCH_QUERY", true);
define("LOAD_ADMIN_REMOVE_COMMENTS", true);
define("LOAD_REMOVE_JQ_MIGRATE", true);
define("LOAD_SVG", true);
define("LOAD_REMOVE_DEFAULT_IMAGE_SIZES", true);
define("LOAD_REST_SECURITY", true);
define("LOAD_NR_SEPERATE_BACKEND_TRACKING", true);
define("LOAD_ADMIN_MENU_CLEANUP", true);
define("LOAD_HIDE-JQ-MIGRATE", true);
define("LOAD_WP_HEAD_REMOVE_GENERATOR", true);
define("LOAD_ADMIN_MENU_REMOVE_POSTS", true);
define("LOAD_REMOVE_CORE_EMOJI", true);
define("LOAD_ADMIN_BAR_DISABLE_CUSTOMIZE", true);
define("LOAD_FLUSH_WPE_CACHE", true);
define("LOAD_FACET_PAGER_HTML", true);

// JS  FP Foundation Helpers
define("LOAD_JS_FP_RESTRICT_EDITOR_HEIGHT.JS", array('fl-builder', 'jquery'));
define("LOAD_JS_FP.JQUERY.VALIDATE.EQUAL.JS", true);
define("LOAD_JS_FP.JQUERY.VALIDATE.MAXCOUNT.REPEATERS.JS", true);
define("LOAD_JS_FP_BB_AJAX_SELECT_POSTS.JS", true);
define("LOAD_JS_FP_BB_AJAX_SELECT_TAXONOMIES.JS", true);
define("LOAD_JS_FP_BB_FORCE_ALT_TAGS.JS", true);

// IE11 Warning pop-up
define("LOAD_IE11_WARNING", true);

define("ENABLE_FREAMEWORK_POSTTYPE_TAXONOMY_REGISTRATION", true);

define("ENABLE_GUTENBERG_THEME", true);
define("LOAD_FP-MODIFIED-DATE-ENQUEUE", true);

define("LOAD_BB_THEMER_POLYLANG_FIX", true);

define("GUTENBERG_ALLOWED_BLOCKS", array(
	'core/image',
	'core/paragraph',
	'core/heading',
	'core/list',
	'core/quote',
	'coblocks/accordion'
));

define('FP_MODULE_DEFAULTS', array(
	'z_pattern' => array(
		'background_image_size' => 'banner_1260x350',
		'background_image_size_medium' => 'banner_1260x350',
		'background_image_size_responsive' => 'banner_1260x350',
		'load_in_header' => true,
	)
));

add_filter('slide_size_extra_large', function () {
	return 'slider_1920x630';
});

add_filter('acf-taxonomy-banner-min-width', function () {
	return 1200;
});

add_filter('acf-taxonomy-banner-min-height', function () {
	return 350;
});

add_filter('default_option_mtswpt_theme_fwp-front',function(){
	// FacetWP domain is different then domain stored by My Wp Translte in db we need to match them
	return get_option('mtswpt_plugin_facetwp',[]);
});

add_filter('default_option_mtswpt_theme_fwp-front_strings',function(){
	// FacetWP domain is different then domain stored by My Wp Translte in db we need to match them
	return get_option('mtswpt_plugin_facetwp_strings',[]);
});


// FP-Foundation autoloads all necessary classes from
// theme/fp-foundation/classes/**  AND theme/classes/** directory
if (file_exists(__DIR__ . '/fp-foundation/classes/autoload.php')) {
	include __DIR__ . '/fp-foundation/classes/autoload.php';
}

$acf_fields = glob(get_stylesheet_directory() . '/acf/*.php');

foreach ($acf_fields as $key => $acf_file) {
	if (!is_string($acf_file)) {
		continue;
	}
	include_once($acf_file);
}

if (!function_exists('fp_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function fp_setup()
	{
		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * You will also need to update the Gulpfile with the new text domain
		 * and matching destination POT file.
		 */
		load_theme_textdomain('fp', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/**
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		add_theme_support('post-thumbnails');

		add_image_size('tile_520x240', 520, 240, true);
		add_image_size('tile_640x420', 640, 420, true); // 1.523809523809524
		add_image_size('tile_420x280', 420, 280, true); //

		// add_image_size('banner_480x360', 480, 360, true);
		add_image_size('banner_1260x350', 1260, 350, true);
		add_image_size('banner_1260x500', 1260, 500, true);

		// // Slider responsive
		add_image_size('slider_1920x630', 1920, 630, true);
		add_image_size('banner_1290x600', 1290, 600, true);
		add_image_size('1920x350', 1920, 350, true);
		// add_image_size('slider_1200x438', 1200, 438, true);
		add_image_size('slider_992x362', 992, 362, true);
		add_image_size('slider_768x280', 768, 280, true);
		add_image_size('slider_480x370', 480, 370, true);

		// Register navigation menus.
		register_nav_menus(
			array(
				'top-left' => 'Top Left',
				'top-right' => 'Top Right',
				'primary' => 'Primary',
				'primary-alt' => 'Primary Alternative',
				'footer-right'  => 'Footer Right',
				'footer-bottom-col1'  => 'Footer Bottom Column 1',
				'footer-bottom-col2'  => 'Footer Bottom Column 2',
				'footer-bottom-col3'  => 'Footer Bottom Column 3',
				'footer-bottom-col4'  => 'Footer Bottom Column 4',
				'footer-bottom-col5'  => 'Footer Bottom Column 5',
				'mobile-menu'  => 'Mobile Menu',
				'footer-trademark'  => 'Footer Trademark Row',
			)
		);

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);



		// if (function_exists('pll_register_string')) {
		// 	pll_register_string("products", "products_s", FP_TD, false);
		// }


	}
endif; // fp_setup
add_action('after_setup_theme', 'fp_setup');

// Delete attachments when deleting posts
add_action('before_delete_post', function ($id) {
	$attachments = get_attached_media('', $id);
	foreach ($attachments as $attachment) {
		wp_delete_attachment($attachment->ID, 'true');
	}
});

function my_bb_custom_fonts($system_fonts)
{
	$system_fonts['Futura PT Bold'] = array(
		'fallback' => 'Futura PT Bold', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Futura PT Book'] = array(
		'fallback' => 'Futura PT Book', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Intro Bold'] = array(
		'fallback' => 'Intro_Bold', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Intro Black'] = array(
		'fallback' => 'Intro_Black', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Intro Book'] = array(
		'fallback' => 'Intro_Book', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Intro Regular'] = array(
		'fallback' => 'Intro_Regular', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Roboto Light'] = array(
		'fallback' => 'roboto_light', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Roboto Bold'] = array(
		'fallback' => 'roboto_bold', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Palanquin Regular'] = array(
		'fallback' => 'palanquin_regular', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Palanquin Bold'] = array(
		'fallback' => 'palanquin_bold', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Roboto Medium'] = array(
		'fallback' => 'roboto_medium', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Roboto Regular'] = array(
		'fallback' => 'roboto_regular', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Roboto Regular'] = array(
		'fallback' => 'roboto_regular', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Palanquin Dark Bold'] = array(
		'fallback' => 'Palanquin Dark Bold', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Palanquin Dark Medium'] = array(
		'fallback' => 'Palanquin Dark Medium', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Palanquin Dark SemiBold'] = array(
		'fallback' => 'Palanquin Dark SemiBold', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	$system_fonts['Palanquin Dark Regular'] = array(
		'fallback' => 'Palanquin Dark Regular', 'sans-serif',
		'weights' => array(
			'normal',
		),
	);
	return $system_fonts;
}

//Add to Beaver Builder Theme Customizer
add_filter('fl_theme_system_fonts', 'my_bb_custom_fonts');

//Add to Page Builder modules
add_filter('fl_builder_font_families_system', 'my_bb_custom_fonts');


add_filter('overlay_view_button_text', function ($button_text, $post_type, $post) {
	switch ($post_type) {
		case 'article':
			$button_text = __('Find out more', FP_TD);
			break;

		case 'recipe':
			$button_text = __('Get the recipe', FP_TD);
			break;
		default:
			# code...
			break;
	}
	return $button_text;
}, 10, 3);

add_filter('breadcrumb_seperator', function ($icon) {
	$icon = '<i class="icon-arrow-breadcrumb" aria-hidden="true"></i>';
	return $icon;
}, 10, 3);

// Add placeholder on search
add_filter('search_placeholder', function () {
	return __('Search articles and recipes...',FP_TD);
});

// Force gutenberg only for articles
add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);
function prefix_disable_gutenberg($current_status, $post_type)
{
	// Use your post type key instead of 'product'
	if ($post_type !== 'article') return false;
	return $current_status;
}

add_filter('facetwp_shortcode_html', function ($output, $atts) {
	if ($output == '<div class="facetwp-counts"></div>') {
		$output = __('Showing ') . $output;
	}
	return $output;
}, 10, 2);



// Make sure we delete attachments of posts when the post is deleted
add_action('before_delete_post', function ($post_id) {
	$args = array(
		'post_type'   => 'attachment',
		'post_parent' => $post_id,
		'post_status' => 'any',
		'nopaging'    => true,

		// Optimize query for performance.
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);
	$query = new WP_Query($args);

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();

			wp_delete_attachment($query->post->ID, true);
		}
	}

	wp_reset_postdata();
});

add_filter('recipe_card_thumbnail_size', function ($default) {
	return 'product_418x240';
}, 10, 1);


// Only allow for these post types to be searchable
function searchfilter($query)
{
	if ($query->is_search && !is_admin()) {
		$query->set('post_type', array('article', 'page','recipe'));
	}

	return $query;
}

add_filter('pre_get_posts', 'searchfilter');

remove_filter('the_content', 'wpautop');

add_filter('fp_filter_excerpts', '__return_false');

/* Remove default option label from facet sort by */
add_filter( 'facetwp_sort_options', function( $options, $params ) {
	$options['default']['label'] = __('Most Relevant', FP_TD);
	return $options;
}, 10, 2 );

/* Facetwp post_type label string translation */
add_filter( 'facetwp_facet_render_args', function( $args ) {
	if ( 'post_types' == $args['facet']['name'] ) {
		$translations = [
			'Recipe' => __('Recipe', FP_TD),
			'Pages' => __('Pages', FP_TD),
			'Article' => __('Article', FP_TD)
		];

		if ( ! empty( $args['values'] ) ) {
			foreach ( $args['values'] as $key => $val ) {
				$display_value = $val['facet_display_value'];
				if ( isset( $translations[ $display_value ] ) ) {
					$args['values'][ $key ]['facet_display_value'] = $translations[ $display_value ];
				}
			}
		}
	}
	return $args;

});

add_action('init', function() {
	pll_register_string( 'very-easy', 'very easy', FP_TD, false );
	pll_register_string( 'cp-very-easy', 'Very Easy', FP_TD, false );
	pll_register_string('medium', 'medium');
	pll_register_string('easy', 'easy');
	pll_register_string('cp-medium', 'Medium');
	pll_register_string('cp-easy', 'Easy');
});



/** Job page search action filter **/
add_filter('sobeys_jm_search_action_fr',function($slug){
	$slug = "emplois/recherche";
	return $slug;
});

// Formidable changed error msg
add_filter( 'frm_global_invalid_msg', 'change_invalid_message' );
function change_invalid_message( $message ) {
    $message = __('There was a problem with your submission. Errors are marked below.', FP_TD);
    return $message;
}
