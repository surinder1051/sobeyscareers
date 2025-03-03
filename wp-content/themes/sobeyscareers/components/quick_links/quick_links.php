<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class quick_links extends fp\Component
	{

		public $schema_version               = 3; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version                      = '1.2.0';
		public $component                    = 'quick_links'; // Component slug should be same as this file base name
		public $component_name               = 'Quick Links Navigation'; // Shown on cluster landing page.
		public $component_description        = 'Add a small inline navigation section';
		public $component_category           = 'FP Safeway';
		public $enable_css                   = true;
		public $enable_js                    = false;
		public $deps_css                     = array(); // WordPress Registered CSS Dependencies
		public $deps_js                      = array(); // WordPress Registered JS Dependencies
		// public $deps_css_remote              = array('https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote               = array('https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir                     = __DIR__;
		public $fields                       = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig                     = array(); // Placeholder for BB Module Registration
		public $variants                     = array('-inline'); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content    = true; // Exclude content of this module from being saved to post_content field
		// public $load_in_header               = true;
		public $dynamic_data_feed_parameters = array( // Generates $atts[posts] object with dynamically populated data
			// 'pagination_api' => true, // enable ajax pagination
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
					'quick_link_item',
					array(
						'title' => __('Stats Attributes', FP_TD),
						'tabs'  => array(
							'general' => array(
								'title'    => __('General', FP_TD),
								'sections' => array(
									'general' => array(
										'title'  => '',
										'fields' => array(
											'link_label' => array(
												'type'      => 'text',
												'label'     => __('Link text (Limit 20)', FP_TD),
												'maxlength' => '20',
												'size'      => '20'
											),
											'link_url' => array(
												'type'    => 'link',
												'label'   => __('Set the URL', FP_TD),
												'default' => '',
											),
											'link_aria_label' => array(
												'type'    => 'text',
												'label'   => __('Aria Label', FP_TD),
												'default' => __('Read More', FP_TD)
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
				'fp-quick-links-tab-1' => array(
					'title'    => __('Links', FP_TD),
					'sections' => array(
						'general' => array(
							'title'  => '',
							'fields' => array(
								'fp_quick_link_layout' => array(
									'type'    => 'select',
									'label'   => __('Choose a navigation layout', FP_TD),
									'options' => array(
										'inline'  => __('Inline', FP_TD),
										'newline' => __('Stacked', FP_TD)
									),
									'default' => 'inline'
								),
								'fp_quick_links_align' => array(
									'type'    => 'align',
									'label'   => __('Content Alignment', FP_TD),
									'default' => 'right'
								),
								'fp_quick_link_theme' => array(
									'type'    => 'fp-colour-picker',
									'label'   => __('Choose colour theme', FP_TD),
									'element' => 'a'
								),
							),
						),
						'title' => array(
							'title'  => __('Title', FP_TD),
							'fields' => array(
								'fp_quick_link_title' => array(
									'type'    => 'text',
									'label'   => __('Navigation Title', FP_TD),
									'default' => __('Trending Now', FP_TD),
								),
								'title_typography' => array(
									'type'       => 'typography',
									'label'      => __('Title Typography', FP_TD),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component-quick-links .quicklinks-label',
									),
								),
							),
						),
						'links' => array(
							'title'  => __('Links', FP_TD),
							'fields' => array(
								'fp_quick_link_item' => array(
									'type'         => 'form',
									'label'        => __('Add Links', FP_TD),
									'preview_text' => 'link_label',
									'multiple'     => true,
									'maxlength'    => '4',
									'form'         => 'quick_link_item'
								),
								'link_typography' => array(
									'type'       => 'typography',
									'label'      => __('Link Typography', FP_TD),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component-quick-links ul li',
									),
								),
							),
						),
					),
				)
			);
		}

		public function pre_process_data($atts, $module)
		{
			if (!empty($atts['fp_quick_link_layout']) && ('inline' == $atts['fp_quick_link_layout'])) {
				$atts['classes'] .= ' -inline';
			}

			return $atts;
		}
	}

	new quick_links;
}
