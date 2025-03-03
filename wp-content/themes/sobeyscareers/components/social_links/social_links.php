<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class social_links extends fp\Component
	{

		public $schema_version               = 5; // This needs to be updated manually when we make changes to the this template so we can find out of date components
		public $version                      = '1.1.0';
		public $component                    = 'social_links'; // Component slug should be same as this file base name
		public $component_name               = 'Social Links'; // Shown in BB sidebar.
		public $component_description        = 'Display links to social media sites';
		public $component_category           = 'FlowPress Modules';
		public $enable_css                   = true;
		public $enable_js                    = false;
		public $deps_css                     = array(); // WordPress Registered CSS Dependencies
		public $deps_js                      = array(); // WordPress Registered JS Dependencies
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
			//     '1' => 1,
			//     '2' => 2,
			//     '3' => 3,
			//     '4' => 4,
			//     '5' => 5,
			//     '6' => 6,
			//     '7' => 7,
			//     '8' => 8,
			//     '9' => 9,
			// ),
			// 'post_types' => array('post', 'page'),
			// 'taxonomies' => array(
			//     array('category' => array()),
			//     array('content_tag' => array('none-option' => true)),
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

			*/

			$this->forms = array(
				array(
					'form_link',
					array(
						'title' => __('Social Link', FP_TD),
						'tabs'  => array(
							'general' => array(
								'title'    => __('General', FP_TD),
								'sections' => array(
									'general' => array(
										'title'  => '',
										'fields' => array(
											'icon' => array(
												'type'    => 'icon',
												'label'   => __('Icon', FP_TD),
												'default' => 'fas fa-share-alt',
											),
											'icon_font_size' => array(
												'type'         => 'unit',
												'units'        => array('px', 'rem'),
												'default_unit' => 'px',
												'responsive'   => true,
												'label'        => __('Icon Font Size', FP_TD),
											),
											'icon_font_line_height' => array(
												'type'         => 'unit',
												'units'        => array('px', 'rem', ''),
												'default_unit' => 'px',
												'responsive'   => true,
												'label'        => __('Icon Font Line Height', FP_TD),
											),
											'url' => array(
												'type'          => 'link',
												'label'         => __('URL', FP_TD),
												'show_target'   => true,
												'show_nofollow' => true,
											),
											'accessibility_label' => array(
												'type'  => 'text',
												'label' => __('Accessibilty Label eg: like us on facebook', FP_TD),
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
				'fp-social_links-tab-1' => array(
					'title'    => __('Settings', FP_TD),
					'sections' => array(
						'title' => array(
							'title'  => __('Title', FP_TD),
							'fields' => array(
								'title' => array(
									'type'    => 'text',
									'label'   => __('Title', FP_TD),
									'default' => 'Title',
								),
								'title_tag' => array(
									'type'    => 'select',
									'label'   => __('Title Tag', FP_TD),
									'default' => 'h2',
									'options' => array(
										'h2' => __('H2', FP_TD),
										'h3' => __('H3', FP_TD),
										'h4' => __('H4', FP_TD),
										'h5' => __('H5', FP_TD),
										'h6' => __('H6', FP_TD),
									),
								),
								'title_color' => array(
									'type'       => 'color',
									'label'      => __('Title color', FP_TD),
									'show_reset' => true,
								),
								'title_typography' => array(
									'type'       => 'typography',
									'label'      => __('Title Typography', FP_TD),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_social_links .title',
									),
								),
							),
						),
						'links' => array(
							'title'  => __('Social Links', FP_TD),
							'fields' => array(
								'link_size' => array(
									'type'         => 'unit',
									'units'        => array('px', 'rem'),
									'default_unit' => 'px',
									'placeholder'  => 35,
									'responsive'   => true,
									'label'        => __('Social Link size', FP_TD),
								),
								'link_margin' => array(
									'type'       => 'dimension',
									'label'      => __('Social Link margins', FP_TD),
									'responsive' => true,
									'units'      => array('px', 'rem'),
								),
								'link_theme' => array(
									'type'    => 'fp-colour-picker',
									'label'   => __('Social Link colour theme', FP_TD),
									'default' => '',
									'element' => 'background',
								),
								'icon_size' => array(
									'type'         => 'unit',
									'units'        => array('px', 'rem'),
									'default_unit' => 'px',
									'placeholder'  => 16,
									'responsive'   => true,
									'label'        => __('Icon Font size', FP_TD),
								),
								'social_links' => array(
									'type'         => 'form',
									'form'         => 'form_link', // ID of a registered form.
									'label'        => __('Link', FP_TD),
									'preview_text' => 'url', // ID of a field to use for the preview text.
									'multiple'     => true
								),
							),
						),
					),
				),
			);
		}

		public function pre_process_data($atts, $module)
		{
			foreach ($atts['social_links'] as $i => $link) {
				if (empty($link->accessibility_label) && !empty($link->url)) {
					preg_match('/(facebook|twitter|pinterest|instagram|linkedin)/', $link->url, $matches);
					if (isset($matches[0])) {
						$atts['social_links'][$i]->accessibility_label = __('Connect with us on', FP_TD) . ' ' . $matches[0];
					}
				}
				$atts['social_links'][$i]->accessibility_label .= $link->url_target == '_blank' ? ' (' . __('Opens in a new window', FP_TD) . ') ' : '';

				$atts['social_links'][$i]->rel = 'external';
				$atts['social_links'][$i]->rel .= $link->url_nofollow == 'yes' ? ' nofollow' : '';
			}

			return $atts;
		}
	}
	new social_links;
}
