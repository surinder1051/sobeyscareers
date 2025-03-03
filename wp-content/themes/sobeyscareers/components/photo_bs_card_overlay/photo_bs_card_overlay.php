<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class photo_bs_card_overlay extends fp\Component
	{

		public $schema_version               = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version                      = '1.1.3';
		public $component                    = 'photo_bs_card_overlay'; // Component slug should be same as this file base name
		public $component_name               = 'Photo Overlay Cards'; // Shown in BB sidebar.
		public $component_description        = 'Create a grid of card callouts';
		public $component_category           = 'FP Cards';
		public $component_load_category      = 'bootstrap';
		public $enable_css                   = true;
		public $enable_js                    = false;
		public $deps_css                     = array('brand'); // WordPress Registered CSS Dependencies
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
					'fp_card_column',
					array(
						'title' => __('Callout Attributes', FP_TD),
						'tabs'  => array(
							'general' => array(
								'title'    => __('General', FP_TD),
								'sections' => array(
									'general' => array(
										'fields' => array(
											'callout_url' => array(
												'type'        => 'link',
												'show_target' => true,
												'label'       => __('Link To', FP_TD),
											),
											'callout_format' => array(
												'type'    => 'select',
												'label'   => __('Choose Format', FP_TD),
												'options' => array(
													'bg-image'  => __('Background Image', FP_TD),
													'bg-colour' => __('Background Colour', FP_TD)
												),
												'default' => 'bg-image',
												'toggle'  => array(
													'bg-image'  => array('fields' => array('callout_bg_image')),
													'bg-colour' => array('fields' => array('callout_bg_theme'))
												)
											),
											'callout_title' => array(
												'type'  => 'text',
												'label' => __('Optional: Override Post Title', FP_TD)
											),
											'callout_bg_image' => array(
												'type'  => 'photo',
												'label' => __('Optional: Override Featured Background Image', FP_TD)
											),
											'callout_bg_theme' => array(
												'type'    => 'fp-colour-picker',
												'label'   => __('Select Callout Theme', FP_TD),
												'element' => 'background'
											),
											'callout_button_theme' => array(
												'type'    => 'fp-colour-picker',
												'label'   => __('Select Button Theme', FP_TD),
												'element' => 'button',
											),
											'heading_color' => array(
												'label' => __('Heading Color', FP_TD),
												'type'  => 'color',
											),
											'callout_link_text' => array(
												'type'    => 'text',
												'label'   => __('Button Label', FP_TD),
												'default' => __('Read More', FP_TD)
											),
											'callout_aria_label' => array(
												'type'    => 'text',
												'label'   => __('Aria Label', FP_TD),
												'default' => __('Read More', FP_TD)
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
				'fp-photo_bs_card_overlay-tab-1' => array(
					'title'    => __('Settings', FP_TD),
					'sections' => array(
						'callout_basic' => array(
							'title'  => __('Basic Settings', FP_TD),
							'fields' => array(
								'callout_heading_type' => array(
									'type'    => 'select',
									'label'   => __('Heading Type', FP_TD),
									'options' => array(
										'h1' => __('H1', FP_TD),
										'h2' => __('H2', FP_TD),
										'h3' => __('H3', FP_TD),
										'h4' => __('H4', FP_TD),
										'h5' => __('H5', FP_TD),
										'h6' => __('H6', FP_TD),
									),
									'default' => 'h2',
								),
								'callout_heading_font_size' => array(
									'type'        => 'unit',
									'label'       => __('Set the heading font size', FP_TD),
									'responsive'  => true,
									'preview'     => array(
										'type'     => 'css',
										'selector' => '.card-title',
									),
									'description' => 'px'
								),
								'display_subheading' => array(
									'type'    => 'select',
									'label'   => __('Display Post Type subheading', FP_TD),
									'options' => array(
										'true'  => __('Yes', FP_TD),
										'false' => __('No', FP_TD),
									),
									'default' => 'true',
								),
							)
						),
						'callout_column_1' => array(
							'title'  => __('Column 1', FP_TD),
							'fields' => array(
								'callout_options_1' => array(
									'type'     => 'form',
									'label'    => __('callout content', FP_TD),
									'form'     => 'fp_card_column',
									'multiple' => true,
									'max'      => 2,
									'preview'  => 'callout_title'
								),
							)
						),
						'callout_column_2' => array(
							'title'  => __('Column 2', FP_TD),
							'fields' => array(
								'callout_options_2' => array(
									'type'     => 'form',
									'label'    => __('callout content', FP_TD),
									'form'     => 'fp_card_column',
									'multiple' => true,
									'max'      => 2,
									'preview'  => 'callout_title'
								),
							)
						),
						'callout_column_3' => array(
							'title'  => __('Column 3', FP_TD),
							'fields' => array(
								'callout_options_3' => array(
									'type'     => 'form',
									'label'    => __('callout content', FP_TD),
									'form'     => 'fp_card_column',
									'multiple' => true,
									'max'      => 2,
									'preview'  => 'callout_title'
								),
							)
						),
						'callout_column_4' => array(
							'title'  => __('Column 4', FP_TD),
							'fields' => array(
								'callout_options_4' => array(
									'type'     => 'form',
									'label'    => __('callout content', FP_TD),
									'form'     => 'fp_card_column',
									'multiple' => true,
									'max'      => 2,
									'preview'  => 'callout_title'
								),
							)
						),
					),
				),
			);
		}

		public function pre_process_data($atts, $module)
		{
			$atts['callout_heading_aria_level'] = str_replace('h', '', $atts['callout_heading_type']);

			$atts['callouts'] = array();
			$gridClass = array();
			$post_types = array('page', 'post', 'recipe', 'article', 'store', 'product', 'faq', 'cooking-tip', 'easy-meal', 'how-to', 'video', 'collection', 'local-farmer', 'localatlantic');
			for ($i = 1; $i < 5; $i++) {
				if (!empty($atts['callout_options_' . $i])) {
					$callout_item = $atts['callout_options_' . $i];
					$colSpan = (count($callout_item) == 2) ? 'half' : 'full';
					$gridClass[] = $colSpan;

					foreach ($callout_item as $index => $ci) {
						if (!empty($ci->callout_url)) {
							$cardClasses = array();
							$headingColour = '';
							$img_size = ($colSpan == 'full') ? array(480, 960) : 'square_480';
							$img_url = '';

							if ($ci->callout_format == 'bg-colour') {
								$callout_theme = generate_theme($ci->callout_bg_theme, 'background');
								$cardClasses[] = $ci->callout_bg_theme;
								$headingColour = ' style="color: #' . $ci->heading_color . ';"';
							} else if ($ci->callout_format == 'bg-image' && !empty($ci->callout_bg_image)) {
								$img_url = wp_get_attachment_image_url($ci->callout_bg_image, $img_size);
							}

							$cardClasses[] = 'card-item-' . $i . '-' . $index;
							$cardClasses[] = $ci->callout_format;
                            
							$cardData = array(
								'title'          => (!empty($ci->callout_title) ? $ci->callout_title : null),
								'post_type'      => '',
								'link'           => (!empty($ci->callout_url) ? $ci->callout_url : null),
								'link_target'    => (!empty($ci->callout_url_target) ? $ci->callout_url_target : null),
								'link_text'      => (!empty($ci->callout_link_text) ? $ci->callout_link_text : null),
								'aria_label'     => (!empty($ci->callout_aria_label) ? $ci->callout_aria_label : null),
								'button_theme'   => (!empty($ci->callout_button_theme) ? $ci->callout_button_theme : null),
								'classes'        => implode(' ', $cardClasses),
								'img'            => '',
								'heading_colour' => $headingColour
							);

							$calloutObject = get_page_by_path(basename(untrailingslashit($ci->callout_url)), OBJECT, $post_types);
							if (isset($calloutObject->ID)) {
								$cardData['title'] = $cardData['title'] ?: $calloutObject->post_title;

								if ($atts['display_subheading'] == 'true') {
									$post_type_obj = get_post_type_object($calloutObject->post_type);
									$cardData['post_type'] = $post_type_obj->label;
								}

								if ($ci->callout_format == 'bg-image' && empty($img_url)) {
									$img_url = get_the_post_thumbnail_url($calloutObject->ID, $img_size);
								}
							}

							$cardData['img'] = !empty($img_url) ? ' style="background-image: url(' .  $img_url . ');"' : '';

							$atts['callouts'][] = $cardData;
						}
					}

					unset($atts['callout_options_' . $i]);
				}
			}

			$atts['card_grid_class'] = implode('-', $gridClass);

			return $atts;
		}
	}
	new photo_bs_card_overlay;
}
