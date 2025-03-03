<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class icon_navigation extends fp\Component
	{

		public $schema_version               = 3; // This needs to be updated manually when we make changes to the this template so we can find out of date components
		public $version                      = '1.0.1';
		public $component                    = 'icon_navigation'; // Component slug should be same as this file base name
		public $component_name               = 'Icon Navigation List'; // Shown on cluster landing page.
		public $component_description        = 'Add a horizontal navigation with icons';
		public $component_category           = 'FP Navigation Items';
		public $enable_css                   = true;
		public $enable_js                    = true;
		public $deps_css                     = array('brand'); // WordPress Registered CSS Dependencies
		public $deps_js                      = array('jquery'); // WordPress Registered JS Dependencies
		// public $deps_css_remote              = array('https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote               = array('https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir                     = __DIR__;
		public $fields                       = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig                     = array(); // Placeholder for BB Module Registration
		public $variants                     = array(); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content    = true; // Exclude content of this module from being saved to post_content field
		// public $load_in_header               = true;
		public $dynamic_data_feed_parameters = array( // Generates $atts[posts] object with dynamically populated data
			// 'pagination_api'         => true, // enable ajax pagination
			// 'posts_per_page_default' => '3',
			// 'posts_per_page_options' => array(
			// 	'1' => 1,
			// 	'2' => 2,
			// 	'3' => 3,
			// 	'4' => 4,
			// 	'5' => 5,
			// 	'6' => 6,
			// 	'7' => 7,
			// 	'8' => 8,
			// 	'9' => 9,
			// ),
			// 'post_types' => array('post', 'page'),
			// 'taxonomies' => array(
			// 	array('category' => array()),
			// 	array('content_tag' => array('none-option' => true)),
			// )
		);

		public function init_fields()
		{

			// Documentation @ https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref

			/*

				Field Types: 

				https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference

				Align           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#align-field
				Border          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#border-field
				button-group    - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#button-group-field
				code            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#code-field
				color           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#color-field
				dimension       - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#dimension-field
				editor          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#editor-field
				font            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#font-field
				form            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#form-field
				gradient        - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#gradient-field
				icon            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#icon-field
				link            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#link-field
				loop            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#loop-settings-fields
				multiple-audios - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-audios-field
				multiple-photos - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-photos-field
				photo           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-field
				photo-sizes     - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
				Post Type       - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
				Select          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#select-field
				Service         - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#service-fields
				shadow          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#shadow-field
				Suggest         - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#suggest-field
				text            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#text-field
				Textarea        - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#textarea-field
				Time            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-field
				Timezone        - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-zone-field
				typography      - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#typography-field
				unit            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#unit-field
				Video           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#video-field

				Repeater Fields
				'multiple' => true,
				Not supported in Editor Fields, Loop Settings Fields, Photo Fields, and Service Fields.

				Dynamic Colour Selector Fields
				'type'    => 'fp-colour-picker',
				'element' => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',

				background class in template class="-bg-[colour selected]"
				button/header class="[colour selected]"

				Additional choices for button: include a select field with options: [outline | solid( default )]
				Outline: class="outline [color selected]"

				Custom SVG Icon Picker
				'type' => 'fp-icon-picker',

				Install svg icons through BB font tool in a subdir called /images/

				*/

			$this->forms = array(
				array(
					'icon_nav_item',
					array(
						'title' => __('Link Attributes', FP_TD),
						'tabs'  => array(
							'general' => array(
								'title'    => __('General', FP_TD),
								'sections' => array(
									'general' => array(
										'title'  => '',
										'fields' => array(
											'nav_item_title' => array(
												'type'    => 'text',
												'label'   => __('Set the link label', FP_TD),
												'default' => 'Department',
											),
											'nav_item_icon' => array(
												'type'    => 'icon',
												'label'   => __('Choose an icon', FP_TD),
												'default' => 'icon-bakery-active'
											),
											'nav_item_colour' => array(
												'type'  => 'color',
												'label' => __('Choose the Hover/Active colour', FP_TD),
											),
											'nav_item_link' => array(
												'type'    => 'link',
												'label'   => __('Set the link URL', FP_TD),
												'default' => site_url()
											),
										)
									)
								)
							)
						)
					)
				)
			);

			$this->fields = array(
				'fp-icon_navigation-tab-1' => array(
					'title'    => __('Icon Navigation Items', FP_TD),
					'sections' => array(
						'stats_content' => array(
							'title'  => __('Navigation Items Settings', FP_TD),
							'fields' => array(
								'fp_icon_nav_items' => array(
									'type'         => 'form',
									'label'        => __('New Item', FP_TD),
									'preview_text' => 'nav_item_title',
									'multiple'     => true,
									'max'          => 9,
									'min'          => 1,
									'form'         => 'icon_nav_item'
								),
								'fp_icon_nav_icon_size' => array(
									'type'        => 'unit',
									'label'       => __('Set the icon size', FP_TD),
									'description' => 'px',
									'default'     => 27,
									'responsive'  => true,
								),
								'fp_icon_nav_font_size' => array(
									'type'        => 'unit',
									'label'       => __('Choose the label font size', FP_TD),
									'description' => 'px',
									'default'     => 12,
									'responsive'  => true,
								),
								'fp_icon_nav_separator' => array(
									'type'    => 'color',
									'label'   => __('Set the navigation item separator colour', FP_TD),
									'default' => 'ddd'
								),
								'fp_icon_nav_breakpoint' => array(
									'type'    => 'unit',
									'label'   => __('Optional: Set the breakpoint from table to grid', FP_TD),
									'default' => 768
								),
							),
						),
					),
				)
			);
		}

		public function pre_process_data($atts, $module = '')
		{
			$classes = array();
			if (!empty($atts['fp_icon_nav_items'])) {
				$classes[] = 'grid-rows-' . ceil(count($atts['fp_icon_nav_items']) / 3);
				$classes[] = (count($atts['fp_icon_nav_items']) < 3 || count($atts['fp_icon_nav_items']) == 4) ? 'grid-cols-2' : 'grid-spacer-' . count($atts['fp_icon_nav_items']) % 3;
			}
			$atts['grid_class'] = implode(' ', $classes);

			if (empty($atts['fp_icon_nav_breakpoint'])) {
				$atts['fp_icon_nav_breakpoint'] = 768;
			}
			return $atts;
		}
	}
	new icon_navigation;
}
