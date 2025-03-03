<?php
/**
 * FP Foundation custom component.
 *
 * @package fp-foundation
 */

namespace fp\components;

use fp;

if ( class_exists( 'fp\Component' ) ) {
	/**
	 * Recipe Card component
	 */
	class recipe_card extends fp\Component {

		/**
		 * The current module version
		 *
		 * @var string $version
		 */
		public $version = '1.1.1';
		/**
		 * This needs to be updated manually when we make changes to the this template so we know if it was updated when foundation was updated.
		 * This should be the foundation version.
		 *
		 * @var string $schema_version
		 */
		public $schema_version = 5;
		/**
		 * The component slug - should be same as this file base name for the custom tpl to override the BB frontend.php file.
		 *
		 * @var string $component
		 */
		public $component = 'recipe_card';
		/**
		 * The human readable name used in module selection in admin, and in page edit mode.
		 *
		 * @var string $component_name
		 */
		public $component_name = 'Recipe Card';
		/**
		 * The short description of what this module does.
		 *
		 * @var string $component_description
		 */
		public $component_description = 'Display a recipe card';
		/**
		 * How this module is categorized in page edit mode. This is important and should be updated properly.
		 *
		 * @var string $component_category
		 */
		public $component_category = 'Sobeys Recipes';
		/**
		 * Undocumented variable
		 *
		 * @var string $component_load_category
		 */
		public $component_load_category = 'recipes';
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
		public $deps_css = array( 'brand' );
		/**
		 * Should foundation load any dependencies for this file.
		 * Always include jQuery here, if the module uses jquery.
		 *
		 * @var array $deps_js
		 */
		public $deps_js = array( 'jquery' );

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
		 * Generates $atts[posts] object with dynamically populated data
		 *
		 * @var array $dynamic_data_feed_parameters
		 */
		public $dynamic_data_feed_parameters = array(
			// 'pagination_api' => true, // enable ajax pagination
			'posts_per_page_default' => '4',
			'posts_per_page_options' => array(
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
				'6' => 6,
				'7' => 7,
				'8' => 8,
				'9' => 9,
			),
			'post_types'             => array( 'recipe' ),
			'taxonomies'             => array(
				// array('category' => array()),
				// array('content_tag' => array('none-option' => true)),
			),
		);

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
				'fp-recipe_card-tab-1' => array(
					'title'    => __( 'Settings', FP_TD ),
					'sections' => array(
						'attributes' => array(
							'title'  => __( 'Attributes', FP_TD ),
							'fields' => array(
								'id'           => array(
									'type'    => 'text',
									'label'   => __( 'Title', FP_TD ),
									'default' => __( 'Default !!!!', FP_TD ),
								),
								'title_tag'    => array(
									'type'    => 'select',
									'label'   => __( 'Choose the title tag', FP_TD ),
									'default' => __( 'h5', FP_TD ),
									'options' => array(
										'h1' => 'Heading 1',
										'h2' => 'Heading 2',
										'h3' => 'Heading 3',
										'h4' => 'Heading 4',
										'h5' => 'Heading 5',
										'h6' => 'Heading 6',
									),
								),
								'title_length' => array(
									'type'    => 'text',
									'label'   => __( 'Title Length', FP_TD ),
									'default' => '60',
								),
								'no_container' => array(
									'type'    => 'text',
									'label'   => __( 'Title', FP_TD ),
									'default' => __( 'Default !!!!', FP_TD ),
								),
								'button_text'  => array(
									'type'    => 'text',
									'label'   => __( 'Button Text', FP_TD ),
									'default' => '',
								),
								'button_aria'  => array(
									'type'  => 'text',
									'label' => __( 'Aria Label (Learn More)', FP_TD ),
								),
								'index'        => array(
									'type'        => 'text',
									'label'       => __( 'Index', FP_TD ),
									'description' => __( 'Used for collection 1/12 counts', FP_TD ),
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
			$atts['post'] = get_post( $atts['id'] );

			if ( ! empty( $atts['button_aria'] ) ) {
				$atts['button_aria'] = sprintf( $atts['button_aria'], get_the_title( $atts['id'] ) );
			}

			$atts['button_aria'] = ( ! empty( $atts['button_aria'] ) ) ? $atts['button_aria'] : sprintf( __( 'View %s ' . $atts['post']->post_type, 'recipe_card' ), get_the_title( $atts['id'] ) );

			if ( empty( $atts['button_text'] ) && isset( $atts['post'] ) && $atts['post'] ) {
				$button_text         = __( sprintf( 'View %s', ucfirst( $atts['post']->post_type ) ) );
				$atts['button_text'] = apply_filters( 'overlay_view_button_text', $button_text, $atts['post']->post_type, $atts['post'] );
			}

			$atts['field_data'] = array(
				array(
					'field'             => 'cooking_total_time',
					'icon'              => 'icon-total-time',
					'label'             => __( 'Total Time', FP_TD ),
					'add_minute_suffix' => true,
					'strip_non_numeric' => false,
				),
				array(
					'field'             => 'general_yield',
					'icon'              => 'icon-serve',
					'label'             => __( 'Serves', FP_TD ),
					'strip_non_numeric' => true,
				),
				array(
					'field'             => 'general_temperature',
					'icon'              => 'icon-store-meals-to-go',
					'label'             => __( 'Temp', FP_TD ),
					'strip_non_numeric' => false,
				),
				array(
					'field'             => 'general_total_time',
					'icon'              => 'icon-serve',
					'label'             => __( 'Total Time', FP_TD ),
					'add_minute_suffix' => false,
					'strip_non_numeric' => false,
				),

			);

			if ( is_singular( 'collection' ) ) {
				$atts['hide_details'] = true;
			}

			$atts['enable_gigya_favorate'] = false;
			$gigya_favorate_post_types     = apply_filters( 'gigya_favorate_post_types', array() );

			if ( isset( $gigya_favorate_post_types ) && is_array( $gigya_favorate_post_types ) && in_array( $atts['post']->post_type, $gigya_favorate_post_types ) ) {
				$atts['enable_gigya_favorate'] = true;
			}

			$atts['legacy_post_id'] = get_post_meta( $atts['post']->ID, 'legacy_post_id', true );

			return $atts;
		}
	}

	new recipe_card();
}
