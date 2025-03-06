<?php //phpcs:ignore
/**
 * FP Foundation custom component.
 *
 * @package fp-foundation
 */

namespace fp\components;

use fp;

if ( class_exists( 'fp\Component' ) ) {
	if ( file_exists( trailingslashit( __DIR__ ) . 'class-extend-hiroy.php' ) ) {
		require trailingslashit( __DIR__ ) . 'class-extend-hiroy.php';
	}
	/**
	 * Sample component Hiroy
	 */
	class hiroy extends fp\Component { //phpcs:ignore

		/**
		 * The current module version
		 *
		 * @var string $version
		 */
		public $version = '1.0';
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
		public $component = 'hiroy';
		/**
		 * The human readable name used in module selection in admin, and in page edit mode.
		 *
		 * @var string $component_name
		 */
		public $component_name = 'hiroy';
		/**
		 * The short description of what this module does.
		 *
		 * @var string $component_description
		 */
		public $component_description = 'Update with a human readable description';
		/**
		 * How this module is categorized in page edit mode. This is important and should be updated properly.
		 *
		 * @var string $component_category
		 */
		public $component_category = 'FP Global';
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
		public $defer_js = true;

		/**
		 * Should a theme js file override the main component js file. If false, a concatenated file is used.
		 *
		 * @see self::extend_js_theme()
		 *
		 * @var bool $theme_override_js
		 */
		public $theme_override_js = false;

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
		public $variants = array( '-compact', '-prefixed' );
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
		 * if ( class_exists( 'fp\components\Extend_hiroy' ) ) {
		 * fp\components\Extend_hiroy::extend_dynamic_parameters( $this->dynamic_data_feed_parameters );
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

			$this->forms = array(
				array(
					'my_form_field',
					array(
						'title' => __( 'My Form Field', 'fl-builder' ),
						'tabs'  => array(
							'general' => array(
								'title'    => __( 'General', 'fl-builder' ),
								'sections' => array(
									'general' => array(
										'title'  => '',
										'fields' => array(
											'label' => array(
												'type'  => 'text',
												'label' => __( 'Label', 'fl-builder' ),
											),
										),
									),
								),
							),
						),
					),
				),
			);

			$this->fields = array(
				'tab-1' => array(
					'title'    => __( 'Settings', 'fp' ),
					'sections' => array(
						'section_2'  => array(
							'title'  => __( 'Section 2', 'fp' ),
							'fields' => array(
								'punctuation' => array(
									'type'        => 'text',
									'label'       => __( 'Hidden Text Field', 'fp' ),
									'description' => __( 'This can be shown by selecting option 2 in the dropdown box', 'fp' ),
									'default'     => 'Default',
									/* 'connections'   => array( 'string', 'html', 'url' ) */ //phpcs:ignore
								),
							),
						),
						'attributes' => array(
							'title'  => __( 'Attributes', 'fp' ),
							'fields' => array(
								'title'            => array(
									'type'    => 'text',
									'label'   => __( 'Title', 'fp' ),
									'default' => 'Title',
									/* 'connections'   => array( 'string', 'html', 'url' ) */ //phpcs:ignore
								),
								'title_typography' => array(
									'type'       => 'typography',
									'label'      => __( 'Title Typography', 'fl-builder' ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.title',
									),
									'default'    => array(
										'family' => 'Helvetica',
										'weight' => 300,
									),
								),
								'title_tag'        => array(
									'type'    => 'select',
									'label'   => __( 'Title Tag', 'fp' ),
									'default' => 'h2',
									'options' => array(
										'h1' => __( 'H1', 'fl-builder' ),
										'h2' => __( 'H2', 'fl-builder' ),
										'h3' => __( 'H3', 'fl-builder' ),
										'h4' => __( 'H4', 'fl-builder' ),
										'h5' => __( 'H5', 'fl-builder' ),
										'h6' => __( 'H6', 'fl-builder' ),
									),
								),
								'content'          => array(
									'type'          => 'editor',
									'label'         => __( 'Extra Content', 'fp' ),
									'media_buttons' => true,
									'rows'          => 2,
									'default'       => 'Default Content',
								),
								'background_color' => array(
									'type'       => 'color',
									'label'      => __( 'Color Picker', 'fl-builder' ),
									'default'    => '333333',
									'show_reset' => true,
								),
								'background'       => array(
									'type'    => 'fp-colour-picker',
									'label'   => __( 'Background', 'fp' ),
									'element' => 'background',
								),
								'theme'            => array(
									'type'        => 'select',
									'label'       => __( 'Select Field', 'fp' ),
									'description' => __( 'This will toggle tabs at the top', 'fp' ),
									'default'     => 'option-1',
									'options'     => array(
										'Blue' => __( 'Blue', 'fl-builder' ),
										'Pink' => __( 'Pink', 'fl-builder' ),
									),
									'toggle'      => array(
										'Blue' => array(
											'fields'   => array( 'my_field_1', 'my_field_2' ), // Which fields to show when Blue is selected.
											'sections' => array( 'my_section' ),  // Which sections to show when Blue is selected.
											'tabs'     => array( 'tab-2' ), // Which tabs to show when Blue is selected.
										),
										'Pink' => array(
											'tabs'     => array( 'tab-2', 'tab-3' ),
											'sections' => array( 'section_2' ),
										),
									),
								),
								'my_form_field'    => array(
									'type'         => 'form',
									'label'        => __( 'My Form', 'fl-builder' ),
									'form'         => 'my_form_field', // ID of a registered form.
									'preview_text' => 'label', // ID of a field to use for the preview text.
								),
								'multiple_text'    => array(
									'type'     => 'text',
									'multiple' => true,
									'label'    => __( 'Multiple Text', 'fp' ),
									'default'  => 'Default !!!!',
								),
							),
						),
					),
				),
				'tab-2' => array(
					'title'    => __( 'Tab 2 Settings', 'fp' ),
					'sections' => array(
						'Attributes & Content' => array(
							'title'  => __( 'Attributes', 'fp' ),
							'fields' => array(),
						),
					),
				),
				'tab-3' => array(
					'title'       => __( 'Tab 3 Settings', 'fp' ),
					'description' => __( 'This can be shown by selecting option 2 in the dropdown box', 'fp' ),
					'sections'    => array(
						'Attributes & Content' => array(
							'title'  => __( 'Attributes', 'fp' ),
							'fields' => array(),
						),
					),
				),
			);

			if ( class_exists( 'fp\components\Extend_hiroy' ) ) {
				fp\components\Extend_hiroy::extend_init_forms( $this->forms );
				fp\components\Extend_hiroy::extend_init_fields( $this->fields );
			}
		}

		/**
		 * Extend the theme js file to override the main js file.
		 * By default, this method will enqueue a concatenated file if both the main js file and theme js file exist.
		 * Otherwise, just the standard component js file is enqueued.
		 *
		 * @see fp\Component::register_assets()
		 *
		 * @return true|false
		 */
		public function extend_js_theme() {
			if ( class_exists( 'fp\components\Extend_hiroy' ) ) {
				if ( method_exists( 'fp\components\Extend_hiroy', 'extend_js_theme' ) ) {
					return fp\components\Extend_hiroy::extend_js_theme();
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		/**
		 * Pre-process data before it gets sent to the template.
		 *
		 * @param array       $atts are the saved settings.
		 * @param object|null $module is a module instance.
		 *
		 * @return array
		 */

		/**
		 * Uncomment out to use
		 * public function pre_process_data( $atts, $module ) {
		 * $atts['content'] = 'cc';
		 *
		 * if ( class_exists( 'fp\components\Extend_hiroy' ) ) {
		 * $atts = fp\components\Extend_hiroy::pre_process_data( $atts, $module );
		 * }
		 * return $atts;
		 * }
		 */

	}
	new hiroy();
}
