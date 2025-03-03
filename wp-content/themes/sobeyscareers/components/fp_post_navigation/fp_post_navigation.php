<?php //phpcs:ignore
/**
 * FP Post Navigation.
 *
 * @package fp-foundation
 */

namespace fp\components;

use fp;

if ( class_exists( 'fp\Component' ) ) {
	if ( file_exists( trailingslashit( __DIR__ ) . 'class-extend-fp_post_navigation.php' ) ) {
		require trailingslashit( __DIR__ ) . 'class-extend-fp_post_navigation.php';
	}
	/**
	 * Next and previous post with thumbnails
	 */
	class fp_post_navigation extends fp\Component { //phpcs:ignore

		/**
		 * The current module version
		 *
		 * @var string $version
		 */
		public $version = '1.1.3';
		/**
		 * This needs to be updated manually when we make changes to the this template so we know if it was updated when foundation was updated.
		 * This should be the foundation version.
		 *
		 * @var string $schema_version
		 */
		public $schema_version = 8;
		/**
		 * The component slug - should be same as this file base name for the custom tpl to override the BB frontend.php file.
		 *
		 * @var string $component
		 */
		public $component = 'fp_post_navigation';
		/**
		 * The human readable name used in module selection in admin, and in page edit mode.
		 *
		 * @var string $component_name
		 */
		public $component_name = 'Single Post Navigation (with thumbnails)';
		/**
		 * The short description of what this module does.
		 *
		 * @var string $component_description
		 */
		public $component_description = 'Display previous/next post navigation on single post pages.';
		/**
		 * How this module is categorized in page edit mode. This is important and should be updated properly.
		 *
		 * @var string $component_category
		 */
		public $component_category = 'FP Navigation';
		/**
		 * Should foundation load the component css file.
		 * It's important to disable if not being used for better performance.
		 *
		 * @var bool $enable_css
		 */
		public $enable_css = true;
		/**
		 * Should foundation load the component js file.
		 * It's important to disable if not being used for better performance.
		 *
		 * @var bool $enable_js
		 */
		public $enable_js = false;
		/**
		 * Should foundation load any dependencies for this file.
		 *
		 * @var array $deps_css
		 */
		public $deps_css = array();
		/**
		 * Should foundation load any dependencies for this file.
		 * Always include jQuery here, if the module uses jquery.
		 *
		 * @var array $deps_js
		 */
		public $deps_js = array();

		/**
		 * Should foundation defer loading of the component css file.
		 * Turn off for mega menus, and hero components if they're typically above the fold.
		 *
		 * @var bool $defer_css
		 */
		public $defer_css = true;
		/**
		 * Should foundation defer loading of the component js file.
		 * Turn off for mega menus, and hero components if they're typically above the fold.
		 *
		 * @var bool $defer_js
		 */
		public $defer_js = false;
		/**
		 * Used for autoloading
		 *
		 * @var string $base_dir
		 */
		public $base_dir = __DIR__;
		/**
		 * Initialize the component BB fields array
		 *
		 * @var array $fields
		 */
		public $fields = array();
		/**
		 * Initialize the bb module config variable.
		 *
		 * @var array $bbconfig
		 */
		public $bbconfig = array();
		/**
		 * List any user display variants as per -> http://rscss.io/variants.html
		 *
		 * @var array $variants
		 */
		public $variants = array();
		/**
		 * Create a settings tab in BB to pull posts by post type, and number of post
		 * Generates $atts[posts] object with dynamically populated data. Use/edit any of the following parameters by adding them to the array.
		 *
		 * 'pagination_api' => true, // enable ajax pagination
		 * 'posts_per_page_default' => '3',
		 * 'posts_per_page_options' => array( '1' => 1, '2' => 3, '3' => 3, ),
		 * 'post_types' => array('post','page'),
		 * 'max_overwrites' => 9, // enable custom select of posts and overwrite the default.-
		 * 'taxonomies' => array( array( 'category' => array() ), array('post_tag' => array( 'none-option' => true ) ),
		 * 'order' => 'DESC',
		 * 'orderby' => 'menu_order',
		 * 'fetch_taxonomies' => true // Return the taxonomies in the post data for display.
		 *
		 * @var bool $load_in_header
		 */
		public $dynamic_data_feed_parameters = array();

		/**
		 * Are there any remote css files to load.
		 * Uncomment to use.
		 * eg: 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'
		 *
		 * @var array $deps_css_remote
		 *
		 * public $deps_css_remote = array();
		 */

		/**
		 * Are there any remote js files to load.
		 * Uncomment to use.
		 * eg: 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'
		 *
		 * @var array $deps_js_remote
		 *
		 * public $deps_js_remote = array();
		 */

		/**
		 * This will allow for testin iframes to be set at this height automatically.
		 * Uncomment if using the testing framework.
		 *
		 * @var integer $min_height
		 *
		 * public $min_height = 600;
		 */

		/**
		 * Exclude content of this module from being saved to post_content field
		 * Uncomment if using the testing framework.
		 *
		 * @var bool $exclude_from_post_content
		 *
		 * public $exclude_from_post_content = false;
		 */

		/**
		 * Exclude content of this module from being saved to post_content field
		 * Uncomment if using the testing framework.
		 *
		 * @var bool $load_in_header
		 *
		 * public $load_in_header = true;
		 */

		/**
		 * Put filters and other setup code into the setup function, this function runs multiple times so be careful with running queries here and consider caching.
		 * Comment out if not using.
		 *
		 * Uncomment: public function setup() {
		 * if ( class_exists( 'fp\components\Extend_fp_post_navigation' ) ) {
		 * fp\components\Extend_fp_post_navigation::extend_dynamic_parameters( $this->dynamic_data_feed_parameters );
		 * }
		 * }
		 */


		/**
		 * Only field setup arrays should exist in this function.
		 * Documentation @ https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref
		 * Field Types: https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference
		 *
		 * Align - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#align-field
		 * Border - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#border-field
		 * button-group - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#button-group-field
		 * code - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#code-field
		 * color - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#color-field
		 * dimension - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#dimension-field
		 * editor - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#editor-field
		 * font - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#font-field
		 * form - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#form-field
		 * gradient - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#gradient-field
		 * icon - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#icon-field
		 * link - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#link-field
		 * loop - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#loop-settings-fields
		 * multiple-audios - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-audios-field
		 * multiple-photos - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-photos-field
		 * photo - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-field
		 * photo-sizes - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
		 * Post Type - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
		 * Select - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#select-field
		 * Service - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#service-fields
		 * shadow - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#shadow-field
		 * Suggest - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#suggest-field
		 * text - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#text-field
		 * Textarea - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#textarea-field
		 * Time - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-field
		 * Timezone - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-zone-field
		 * typography - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#typography-field
		 * unit - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#unit-field
		 * Video - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#video-field
		 *
		 * Repeater Fields: 'multiple'      => true (Not supported in Editor Fields, Loop Settings Fields, Photo Fields, and Service Fields).
		 *
		 * Dynamic Colour Selector Fields params:
		 * 'type'        => 'fp-colour-picker',
		 * 'element'     => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',
		 *
		 * Dynamic Colour Selector Fields use:
		 * background class in template class="-bg-[colour selected]"
		 * button/header class="[colour selected]"
		 * Additional choices for button: include a select field with options: [outline | solid( default )] eg: Outline: class="outline [color selected]"
		 *
		 * Custom SVG Icon Picker
		 * 'type'        => 'fp-icon-picker',
		 *
		 * Install svg icons through BB font tool in a subdir called /images/ eg: wp-content/bb-icons/brand/images
		 */
		public function init_fields() {

			$this->fields = array(
				'fp-post_navigation-tab-1' => array(
					'title'    => __( 'Settings', 'fp' ),
					'sections' => array(
						'typography' => array(
							'title'  => __( 'Typography', 'fp' ),
							'fields' => array(
								'nav_color'       => array(
									'label'       => __( 'Nav Title Color', 'fp' ),
									'type'        => 'color',
									'description' => 'used for "Previous/Next Post" text and arrow',
									'preview'     => array(
										'type'     => 'css',
										'selector' => '.component_fp_post_navigation .fp-nav-link .nav-link-text .nav-text',
									),
								),
								'nav_typography'  => array(
									'type'       => 'typography',
									'label'      => __( 'Nav Title Typography', 'fp' ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_fp_post_navigation .fp-nav-link .nav-link-text .nav-text',
									),
								),
								'arrow_size'      => array(
									'type'         => 'unit',
									'units'        => array( 'px', 'rem' ),
									'default_unit' => 'px',
									'placeholder'  => 16,
									'responsive'   => true,
									'slider'       => true,
									'label'        => __( 'Arrow size', 'fp' ),
									'preview'      => array(
										'type'     => 'css',
										'selector' => '.component_fp_post_navigation .fp-nav-link .nav-link-text .nav-link [class^="icon-arrow-"]:before',
										'property' => 'font-size',
									),
								),
								'link_color'      => array(
									'type'    => 'fp-colour-picker',
									'label'   => __( 'Link Title colour theme', 'fp' ),
									'default' => '',
									'element' => 'a',
								),
								'link_typography' => array(
									'type'       => 'typography',
									'label'      => __( 'Link Title Typography', 'fp' ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_fp_post_navigation .fp-nav-link .nav-link-text .nav-link .link-title',
									),
								),
							),
						),
						'layout'     => array(
							'title'  => __( 'Module Layout', 'fp' ),
							'fields' => array(
								'padding' => array(
									'type'         => 'dimension',
									'label'        => 'Padding',
									'units'        => array( 'px', '%', 'rem' ),
									'default_unit' => 'px',
									'responsive'   => true,
									'slider'       => true,
								),
								'border'  => array(
									'type'       => 'border',
									'label'      => 'Border',
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_fp_post_navigation .post-nav-wrapper',
									),
								),
							),
						),
					),
				),
			);
			if ( class_exists( 'fp\components\Extend_fp_post_navigation' ) ) {
				fp\components\Extend_fp_post_navigation::extend_init_forms( $this->forms );
				fp\components\Extend_fp_post_navigation::extend_init_fields( $this->fields );
			}
		}

		/**
		 * Set the navigation link attributes for title, url and thumbnail
		 *
		 * @param bool $prev is the nav item previous or next.
		 *
		 * @return array $post_nav_atts
		 */
		public function set_post_nav_atts( $prev = true ) {
			$post_nav_atts = array();

			$post_object = get_adjacent_post( false, '', $prev );
			if ( isset( $post_object->ID ) ) {
				$post_nav_atts['url']       = get_permalink( $post_object );
				$post_nav_atts['thumbnail'] = '';
				$post_nav_atts['title']     = $post_object->post_title;
				$post_nav_atts['class']     = $prev ? 'prev' : 'next';

				$post_thumbnail_url = get_the_post_thumbnail_url( $post_object, array( 100, 100 ) );
				if ( false !== $post_thumbnail_url ) {
					$post_nav_atts['thumbnail'] = $post_thumbnail_url;
					$post_nav_atts['class']    .= ' -has-thumbnail';
				}
			}

			return $post_nav_atts;
		}

		/**
		 * Pre-process data before it gets sent to the template.
		 *
		 * @param array       $atts are the saved settings.
		 * @param object|null $module is a module instance.
		 *
		 * @return array
		 */
		public function pre_process_data( $atts, $module ) {
			$post_id = get_the_ID();
			if ( $post_id ) {
				$atts['links'] = array(
					'prev' => $this->set_post_nav_atts( true ),
					'next' => $this->set_post_nav_atts( false ),
				);
			}

			if ( class_exists( 'fp\components\Extend_fp_post_navigation' ) ) {
				$atts = fp\components\Extend_fp_post_navigation::pre_process_data( $atts, $module );
			}

			return $atts;
		}
	}
	new fp_post_navigation();
}
