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
	 * Bootstrap Nav component
	 */
	class bs_nav extends fp\Component {

		/**
		 * The current module version
		 *
		 * @var string $version
		 */
		public $version = '1.0.2';
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
		public $component = 'bs_nav';
		/**
		 * The human readable name used in module selection in admin, and in page edit mode.
		 *
		 * @var string $component_name
		 */
		public $component_name = 'Bootstrap Nav';
		/**
		 * The short description of what this module does.
		 *
		 * @var string $component_description
		 */
		public $component_description = 'Drop-in a WordPress menu. Can be expandable or just a list form.';
		/**
		 * How this module is categorized in page edit mode. This is important and should be updated properly.
		 *
		 * @var string $component_category
		 */
		public $component_category = 'FP Global';
		/**
		 * Undocumented variable
		 *
		 * @var string $component_load_category
		 */
		public $component_load_category = 'bootstrap';
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
			// 'posts_per_page_default' => '4',
			// 'posts_per_page_options' => array(
			// '1' => 1,
			// '2' => 2,
			// '3' => 3,
			// '4' => 4,
			// '5' => 5,
			// '6' => 6,
			// '7' => 7,
			// '8' => 8,
			// '9' => 9,
			// ),
			// 'post_types'             => array( 'recipe' ),
			// 'taxonomies'             => array(
			// array('category' => array()),
			// array('content_tag' => array('none-option' => true)),
			// ),
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
			$menus = array( '' => 'None' );
			$menus = array_merge( $menus, get_registered_nav_menus() );

			$this->fields = array(
				'fp-bs_nav-tab-1' => array(
					'title'    => __( 'Settings', FP_TD ),
					'sections' => array(
						'attributes' => array(
							'title'  => __( 'Attributes', FP_TD ),
							'fields' => array(
								'title'               => array(
									'type'        => 'text',
									'label'       => __( 'Title', FP_TD ),
									'description' => __( 'Nav Title', FP_TD ),
									'default'     => '',
								),
								'title_visible'       => array(
									'type'        => 'select',
									'label'       => __( 'Title Visibility', FP_TD ),
									'description' => __( 'For accessibilty, a title is required, but you can choose to visually hide it', FP_TD ),
									'default'     => 'true',
									'options'     => array(
										'true'  => __( 'Show', FP_TD ),
										'false' => __( 'Hide', FP_TD ),
									),
								),
								'title_tag'           => array(
									'type'    => 'select',
									'label'   => __( 'Title Tag', FP_TD ),
									'default' => 'h2',
									'options' => array(
										'h2' => __( 'H2', FP_TD ),
										'h3' => __( 'H3', FP_TD ),
										'h4' => __( 'H4', FP_TD ),
										'h5' => __( 'H5', FP_TD ),
										'h6' => __( 'H6', FP_TD ),
									),
								),
								'brand_content'       => array(
									'type'        => 'raw',
									'label'       => __( 'Brand HTML', FP_TD ),
									'description' => __( 'Logo, shortcode, or link can be inserted', FP_TD ),
									'default'     => '',
								),
								'menu'                => array(
									'type'        => 'select',
									'label'       => __( 'Select Menu', FP_TD ),
									'description' => __( 'Select a menu to load', FP_TD ),
									'default'     => 'option-1',
									'options'     => $menus,
								),
								'show_search'         => array(
									'type'        => 'select',
									'label'       => __( 'Show Search', FP_TD ),
									'description' => __( 'Show search input box', FP_TD ),
									'default'     => 'false',
									'options'     => array(
										'true'  => 'Yes',
										'false' => 'No',
									),
								),
								'show-navbar-toggler' => array(
									'type'        => 'select',
									'label'       => __( 'Show Mobile Toggler', FP_TD ),
									'description' => __( 'Show hamburger menu in mobile', FP_TD ),
									'default'     => 'true',
									'options'     => array(
										'true'  => __( 'Yes', FP_TD ),
										'false' => __( 'No', FP_TD ),
									),
								),
							),
						),
					),
				),
				'fp-bs_nav-tab-2' => array(
					'title'    => __( 'Style', FP_TD ),
					'sections' => array(
						'attributes' => array(
							'title'  => __( 'Style', FP_TD ),
							'fields' => array(
								'vertical'                 => array(
									'type'        => 'select',
									'label'       => __( 'Vertical', FP_TD ),
									'description' => __( 'Is the menu to be shown stacked?', FP_TD ),
									'default'     => 'false',
									'options'     => array(
										'true'  => __( 'Yes', FP_TD ),
										'false' => __( 'No', FP_TD ),
									),
									'toggle'      => array(
										'true'  => array(
											'fields' => array( 'enable_vertical_collapse' ),
										),
										'false' => array(),
									),
								),
								'disable_mobile_arrows'    => array(
									'type'        => 'select',
									'label'       => __( 'Disable Mobile Arrows', FP_TD ),
									'description' => __( 'Hide the arrows after the title on mobile.', FP_TD ),
									'default'     => 'false',
									'options'     => array(
										'true'  => __( 'Yes', FP_TD ),
										'false' => __( 'No', FP_TD ),
									),
								),
								'theme'                    => array(
									'type'        => 'select',
									'label'       => __( 'Theme', FP_TD ),
									'description' => __( 'Choose a dark / light theme', FP_TD ),
									'default'     => 'light',
									'options'     => array(
										'dark'  => __( 'Dark', FP_TD ),
										'light' => __( 'Light', FP_TD ),
										'none'  => __( 'None', FP_TD ),
									),
								),
								'enable_vertical_collapse' => array(
									'type'    => 'select',
									'label'   => __( 'Enable vertical menu expand/collapse', FP_TD ),
									'options' => array(
										'yes' => __( 'Yes', FP_TD ),
										'no'  => __( 'No', FP_TD ),
									),
									'toggle'  => array(
										'yes' => array(
											'fields' => array( 'vertical_open_icon', 'vertical_close_icon' ),
										),
										'no'  => array(),
									),
									'default' => 'no',
								),
								'vertical_open_icon'       => array(
									'type'    => 'icon',
									'label'   => __( 'Optional: Choose an icon to expand a menu', FP_TD ),
									'default' => 'fas fa-plus',
								),
								'vertical_close_icon'      => array(
									'type'    => 'icon',
									'label'   => __( 'Optional: Choose an icon to collapse a menu', FP_TD ),
									'default' => 'fas fa-minus',
								),
								'heading_color'            => array(
									'type'       => 'color',
									'label'      => __( 'Title Color', FP_TD ),
									'default'    => '000000',
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.collapse.navbar .title',
									),
									'show_reset' => true,
								),
								'title_typography'         => array(
									'type'       => 'typography',
									'label'      => __( 'Title Typography', FP_TD ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.title',
									),
									'default'    => array(
										'family'    => 'Helvetica',
										'font-size' => 12,
										'weight'    => 300,
									),
								),
								'link_color'               => array(
									'type'       => 'color',
									'label'      => __( 'Item Link Color', FP_TD ),
									'default'    => 'FFFFFF',
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.collapse.navbar a',
									),
									'show_reset' => true,
								),
								'hover_color'              => array(
									'type'       => 'color',
									'label'      => __( 'Item Hover Color', FP_TD ),
									'default'    => 'FFFFFF',
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.collapse.navbar a:hover',
									),
									'show_reset' => true,
								),
								'item_typography'          => array(
									'type'       => 'typography',
									'label'      => __( 'Item Typography', FP_TD ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.nav-link',
									),
									'default'    => array(
										'family'    => 'Helvetica',
										'font-size' => 11,
										'weight'    => 300,
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
			$atts['classes'] = 'navbar navbar-expand-lg';
			if ( ! empty( $atts['theme'] ) && ( 'none' !== $atts['theme'] ) ) {
				$atts['classes'] .= " navbar-{$atts['theme']} bg-{$atts['theme']}";
			}
			if ( ! empty( $atts['vertical'] ) && 'true' === $atts['vertical'] ) {
				$atts['classes'] .= ' vertical';
			}
			if ( isset( $atts['enable_vertical_collapse'] ) && 'yes' === $atts['enable_vertical_collapse'] ) {
				$atts['classes'] .= ' expand-collapse';
			}

			return $atts;
		}
	}

	new bs_nav();
}
