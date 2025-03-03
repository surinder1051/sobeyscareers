<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class social_share extends fp\Component
	{

		public $schema_version               = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version                      = '1.3.2';
		public $component                    = 'social_share'; // Component slug should be same as this file base name
		public $component_name               = 'Social Share'; // Shown in BB sidebar.
		public $component_description        = 'Display links for social media sharing';
		public $component_category           = 'FlowPress Modules';
		public $enable_css                   = true;
		public $enable_js                    = false;
		public $deps_css                     = array(); // WordPress Registered CSS Dependencies
		public $deps_js                      = array(); // WordPress Registered JS Dependencies
		// public $deps_css_remote              = array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote               = array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir                     = __DIR__;
		public $fields                       = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig                     = array(); // Placeholder for BB Module Registration
		public $variants                     = array('-compact', '-vertical', '-show-print-option'); // Component CSS Variants as per -> http://rscss.io/variants.html
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
			// 'post_types' => array('post','page'), 
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


			*/

			$this->fields = array(
				'fp-social_share-tab-1' => array(
					'title'    => __('Settings', FP_TD),
					'sections' => array(
						'layout' => array(
							'title'  => __('Module Layout', FP_TD),
							'fields' => array(
								'variant' => array(
									'type'    => 'select',
									'label'   => __('Variant', FP_TD),
									'default' => 'default',
									'options' => array(
										'default'   => __('Default', FP_TD),
										'-compact'  => __('Compact', FP_TD),
										'-vertical' => __('Vertical', FP_TD),
									),
								),
								'padding' => array(
									'type'         => 'dimension',
									'label'        => 'Padding',
									'units'        => array('px', '%', 'rem'),
									'default_unit' => 'px',
									'responsive'   => true,
									'slider'       => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_social_share .social-share',
										'property' => 'padding',
									),
								),
								'border' => array(
									'type'       => 'border',
									'label'      => 'Border',
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_social_share',
										'property' => 'border',
									),
								),
							)
						),
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
									'label' => __('Title Color', FP_TD),
									'type'  => 'color',
								),
								'title_typography' => array(
									'type'       => 'typography',
									'label'      => __('Title Typography', FP_TD),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_social_share .title',
									),
								),
							),
						),
						'shares' => array(
							'title'  => __('Share Icons', FP_TD),
							'fields' => array(
								'button_size' => array(
									'type'         => 'unit',
									'units'        => array('px', 'rem'),
									'default_unit' => 'px',
									'placeholder'  => 60,
									'responsive'   => true,
									'slider'       => true,
									'label'        => __('Button size', FP_TD),
									'preview'      => array(
										'type'     => 'css',
										'rules'    => array(
											array(
												'selector' => '.component_social_share .social-share .social-icon',
												'property' => 'height'
											),
											array(
												'selector' => '.component_social_share .social-share .social-icon',
												'property' => 'width'
											),
											array(
												'selector' => '.component_social_share .social-share .social-icon::before',
												'property' => 'line-height'
											),
											array(
												'selector' => '.component_social_share.-show-print-option .social-share.print .social-icon::before',
												'property' => 'font-size',
											),
										),
									),
								),
								'button_spacing' => array(
									'type'         => 'unit',
									'units'        => array('px', 'rem'),
									'default_unit' => 'px',
									'placeholder'  => 8,
									'responsive'   => true,
									'slider'       => true,
									'label'        => __('Button spacing', FP_TD),
									'preview'      => array(
										'type'     => 'css',
										'rules'    => array(
											array(
												'selector' => '.component_social_share .social-share',
												'property' => 'padding-left'
											),
											array(
												'selector' => '.component_social_share.-show-print-option .social-share.print',
												'property' => 'margin-left',
											),
										),
									),
								),
								'icon_size' => array(
									'type'         => 'unit',
									'units'        => array('px', 'rem'),
									'default_unit' => 'px',
									'placeholder'  => 24,
									'responsive'   => true,
									'slider'       => true,
									'label'        => __('Icon Font size', FP_TD),
									'preview'      => array(
										'type'     => 'css',
										'selector' => '.component_social_share .social-share .social-icon::before',
										'property' => 'font-size',
									),
								),
								'theme' => array(
									'label'   => __('Choose theme color for default state', FP_TD),
									'default' => 'outline',
									'type'    => 'fp-colour-picker',
									'element' => 'button',
								),
								'services' => array(
									'type'         => 'select',
									'label'        => __('Services to display', FP_TD),
									'multi-select' => true,
									'default'      => array('facebook', 'twitter', 'email'),
									'options'      => array(
										'facebook'  => __('Facebook', FP_TD),
										'twitter'   => __('Twitter', FP_TD),
										'linkedin'  => __('LinkedIn', FP_TD),
										'pinterest' => __('Pinterest', FP_TD),
										'email'     => __('Email', FP_TD),
										'print'     => __('Print', FP_TD)
									),
								),
							),
						),
					),
				),
			);
		}

		public function pre_process_data($atts, $module)
		{
			wp_enqueue_style('font-awesome-5');

			global $post;
			$atts['email_url'] = urlencode("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
			$atts['email_title'] = urlencode($post->post_title);
			$atts['email_body'] = urlencode(__("Check out this ", FP_TD) . __($post->post_type, FP_TD). __(" at ", FP_TD)) . $atts['email_url'];

			$atts['theme'] = generate_theme($atts['theme'], 'button');

			if (in_array('print', $atts['services'])) {
				$atts['classes'] .= " -show-print-option";
			}

			if (!empty($atts['variant']) && ('default' != $atts['variant'])) {
				$atts['classes'] .= ' ' . $atts['variant'];
			}

			return $atts;
		}
	}
	new social_share;
}
