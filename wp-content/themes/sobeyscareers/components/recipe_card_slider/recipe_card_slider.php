<?php //phpcs:ignore
/**
 * FP Foundation custom component.
 *
 * @package fp-foundation
 */

namespace fp\components;

use fp;
use WP_Query;

if ( class_exists( 'fp\Component' ) ) {
	/**
	 * Recipe Card Slider
	 */
	class recipe_card_slider extends fp\Component { //phpcs:ignore

		/**
		 * The current module version
		 *
		 * @var string $version
		 */
		public $version = '2.0.2';
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
		public $component = 'recipe_card_slider';
		/**
		 * The human readable name used in module selection in admin, and in page edit mode.
		 *
		 * @var string $component_name
		 */
		public $component_name = 'Recipe Card Slider';
		/**
		 * The short description of what this module does.
		 *
		 * @var string $component_description
		 */
		public $component_description = 'Display recipe cards in a slider';
		/**
		 * How this module is categorized in page edit mode. This is important and should be updated properly.
		 *
		 * @var string $component_category
		 */
		public $component_category = 'Sobeys Recipes';
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
		public $enable_js = true;
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
		public $deps_js = array( 'jquery' );
		/**
		 * Should foundation load any remote dependencies for this file.
		 *
		 * @var array $deps_css
		 */
		public $deps_css_remote = array( '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
		/**
		 * Should foundation load any remote dependencies for this file.
		 *
		 * @var array $deps_js
		 */
		public $deps_js_remote = array( '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js' );
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
		public $defer_js = true;
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

			$this->forms = array();

			$this->fields = array(
				'tab-1' => array(
					'title'    => __( 'Settings', 'fp' ),
					'sections' => array(
						'content' => array(
							'title'  => __( 'Content', 'fp' ),
							'fields' => array(
								'title' => array(
									'type'    => 'text',
									'label'   => __( 'Title', 'fp' ),
									'default' => 'Related Content',
								),
							),
						),
						'styling' => array(
							'title'  => __( 'Styling', 'fp' ),
							'fields' => array(
								'title_tag'             => array(
									'type'    => 'select',
									'label'   => __( 'Title Tag', 'fp' ),
									'default' => 'h2',
									'options' => array(
										'h1' => 'H1',
										'h2' => 'H2',
										'h3' => 'H3',
										'h4' => 'H4',
										'h5' => 'H5',
										'h6' => 'H6',
									),
								),
								'title_typography'      => array(
									'type'       => 'typography',
									'label'      => __( 'Title Typography', 'fp' ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.title',
									),
								),
								'card_title_typography' => array(
									'type'       => 'typography',
									'label'      => __( 'Card Title Typography', 'fp' ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.card-title',
									),
								),
							),
						),
						'data'    => array(
							'title'  => __( 'Data', 'fp' ),
							'fields' => array(
								'recipe_tag_id'  => array(
									'type'   => 'suggest',
									'label'  => __( 'Recipe Tag', 'fp' ),
									'action' => 'fl_as_terms',
									'data'   => 'recipe-tag',
									'limit'  => 1,
								),
								'posts_per_page' => array(
									'type'    => 'unit',
									'label'   => __( 'Number of results to show', 'fp' ),
									'default' => '4',
									'slider'  => array(
										'min' => 2,
										'max' => 12,
									),
								),
							),
						),
					),
				),
			);
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
			global $post;

			if ( $post ) {
				$lang = ( defined( 'ICL_LANGUAGE_CODE' ) ) ? ICL_LANGUAGE_CODE : 'en';

				$recipe_card_args = array(
					'post_type'        => get_post_type( $post->ID ),
					'posts_per_page'   => $atts['posts_per_page'],
					'post_status'      => 'publish',
					'post__not_in'     => array( $post->ID ),
					'suppress_filters' => true,
					'orderby'          => 'rand',
					'tax_query'        => array(),
					'lang'             => $lang,
				);

				if ( $atts['recipe_tag_id'] ) {
					$recipe_card_args['tax_query'][] = array(
						'taxonomy' => 'recipe-tag',
						'field'    => 'term_id',
						'terms'    => $atts['recipe_tag_id'],
					);
				} else {

					if (  false === ( $taxonomies = get_transient( 'taxonomies_for_post_' . $post->ID ) ) ) {
						$taxonomies = get_object_taxonomies( $post, 'names' );
						// Store it for a day
						set_transient( 'taxonomies_for_post_' . $post->ID, $taxonomies, 1 * DAY_IN_SECONDS );
					}

					foreach ( $taxonomies as $taxonomy ) {
						$terms = get_the_terms( $post->ID, $taxonomy );
						if ( empty( $terms ) || ( ! empty( $terms ) && 'post_translations' === $terms[0]->taxonomy ) ) {
							continue;
						}

						$term_list = wp_list_pluck( $terms, 'slug' );
						$recipe_card_args['tax_query'][] = array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $term_list,
						);
					}
				}
				if ( count( $recipe_card_args['tax_query'] ) > 1 ) {
					$recipe_card_args['tax_query']['relation'] = ( defined( 'ICL_LANGUAGE_CODE' ) ) ? 'AND' : 'OR';
				}

				$query = new WP_Query( $recipe_card_args );

				$atts['posts'] = $query->posts;
			}

			return $atts;
		}

	}
	new recipe_card_slider();
}
