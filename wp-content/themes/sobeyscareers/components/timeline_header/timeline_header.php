<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class timeline_header extends fp\Component
	{

		public $schema_version        			= 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version               			= '1.0.0';
		public $component             			= 'timeline_header'; // Component slug should be same as this file base name
		public $component_name        			= 'Timeline Header'; // Shown in BB sidebar.
		public $component_description 			= 'Create a promo banner for the timeline';
		public $component_category    			= 'FP Timeline';
		public $enable_css            			= true;
		public $enable_js             			= true;
		public $deps_css              			= array( 'brand' ); // WordPress Registered CSS Dependencies
		public $deps_js               			= array('jquery'); // WordPress Registered JS Dependencies
		// public $deps_css_remote       			= array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote 		  			= array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir              			= __DIR__;
		public $fields                			= array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig              			= array(); // Placeholder for BB Module Registration
		public $variants              			= array('-compact', '-prefixed'); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content 		= true; // Exclude content of this module from being saved to post_content field
		// public $load_in_header		  			= true;
		public $dynamic_data_feed_parameters 	= array( // Generates $atts[posts] object with dynamically populated data
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

			Align - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#align-field
			Border - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#border-field
			button-group - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#button-group-field
			code - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#code-field
			color - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#color-field
			dimension - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#dimension-field
			editor - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#editor-field
			font - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#font-field
			form - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#form-field
			gradient - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#gradient-field
			icon - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#icon-field
			link - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#link-field
			loop - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#loop-settings-fields
			multiple-audios - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-audios-field
			multiple-photos - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-photos-field
			photo - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-field
			photo-sizes - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
			Post Type - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
			Select - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#select-field
			Service - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#service-fields
			shadow - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#shadow-field
			Suggest - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#suggest-field
			text - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#text-field
			Textarea - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#textarea-field
			Time - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-field
			Timezone - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-zone-field
			typography - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#typography-field
			unit - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#unit-field
			Video - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#video-field

			Repeater Fields
			'multiple'      => true,
			Not supported in Editor Fields, Loop Settings Fields, Photo Fields, and Service Fields.

			**Dynamic Colour Selector Fields
			'type'        => 'fp-colour-picker',
			'element'     => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',

			background class in template class="-bg-[colour selected]"
			button/header class="[colour selected]"

			Additional choices for button: include a select field with options: [outline | solid( default )]
			Outline: class="outline [color selected]"

			**Custom SVG Icon Picker
			'type'        => 'fp-icon-picker',

			Install svg icons through BB font tool in a subdir called /images/



			*/

			$this->fields = array(
				'timeline-header-tab-1' => array(
					'title'         => __('Settings', FP_TD),
					'sections'      => array(
						'timeline_header_background' => array(
							'title'     => __('Background Attributes', FP_TD),
							'fields'    => array(
								'background_image' => array(
									'type'        => 'photo',
									'label'       => __('Choose a background image (optional: one per breakpoint)', FP_TD),
									'responsive'     => true,
								),
								'background_overlay' => array(
									'type'        => 'select',
									'label'       => __('Optional: Set a background overlay', FP_TD),
									'options'     => array('0' => 'No', '1' => 'Yes'),
									'default'    => '0',
									'toggle'  => array(
										'0'	=> array(),
										'1'	=> array('fields' => array('background_opacity'))
									)
								),
								'background_opacity' => array(
									'type'        => 'unit',
									'label'       => __('Optional: Set a overlay opacity', FP_TD),
									'description'	=> '%',
									'default'		=> 30
								),
							),
						),
						'timeline_header_content' => array(
							'title'     => __('Attributes', FP_TD),
							'fields'    => array(
								'timeline_logo' => array(
									'type'    => 'photo',
									'label'   => __('Choose a icon/logo', FP_TD),
								),
								'timeline_heading' => array(
									'type'    => 'text',
									'label'   => __('Heading', FP_TD),
									'default' => 'Title',
								),
								'timeline_heading_tag' => array(
									'type'        => 'select',
									'label'       => __('Title Tag', FP_TD),
									'default'     => 'h2',
									'options'     => array(
										'h1'     => __('H1', 'fl-builder'),
										'h2'     => __('H2', 'fl-builder'),
										'h3'     => __('H3', 'fl-builder'),
									),
								),
								'timeline_heading_typography' => array(
									'type'    => 'typography',
									'label'   => __('Heading Typography', FP_TD),
									'responsive' => true,
									'default'       => array(
										'weight'        => 'bold',
										'text-align'	=> 'center',
										'text-transform' => 'uppercase',
										'color'			=> 'fff'
									),
									'preview'    => array(
										'type'	    => 'css',
										'selector'  => '.component_timeline_header .timeline-header',
									),
								),
								'timeline_content' => array(
									'type'          => 'textarea',
									'label'         => __('Description', FP_TD),
									'rows'          => 4,
									'default'       => 'Default Content',
								),
								'timeline_content_typography' => array(
									'type'          => 'typography',
									'label'         => __( 'Description Typography', 'fl-builder' ),
									'responsive' => true,
									'preview'    => array(
										'type'	    => 'css',
										'selector'  => '.component_timeline_header .text-box',
									),
									'default'       => array(
										'text-align'	=> 'center',
										'color'			=> 'fff'
									)
								),
							),
						),
						'timeline_header_navigation' => array(
							'title'     => __('Scroll Options', FP_TD),
							'fields'    => array(
								'timeline_header_scroll' => array(
									'type'        => 'select',
									'label'       => __('Select the row to scroll to', FP_TD),
									'options'     => array(
										'1' => __( 'Scroll to Row #1', 'fl-builder' ),
										'2' => __( 'Scroll to Row #2', 'fl-builder' ),
										'3' => __( 'Scroll to Row #3', 'fl-builder' ),
										'4' => __( 'Scroll to Row #4', 'fl-builder' ),
										'5' => __( 'Scroll to Row #5', 'fl-builder' ),
										'6' => __( 'Scroll to Row #6', 'fl-builder' ),
										'7' => __( 'Scroll to Row #7', 'fl-builder' ),
										'8' => __( 'Scroll to Row #8', 'fl-builder' ),
										'9' => __( 'Scroll to Row #9', 'fl-builder' ),
										'10' => __( 'Scroll to Row #10', 'fl-builder' ),
										'11' => __( 'Scroll to Row #11', 'fl-builder' ),
										'12' => __( 'Scroll to Row #12', 'fl-builder' ),
										'13' => __( 'Scroll to Row #13', 'fl-builder' ),
										'14' => __( 'Scroll to Row #14', 'fl-builder' ),
										'15' => __( 'Scroll to Row #15', 'fl-builder' ),
										'16' => __( 'Scroll to Row #16', 'fl-builder' ),
										'17' => __( 'Scroll to Row #17', 'fl-builder' ),
										'18' => __( 'Scroll to Row #18', 'fl-builder' ),
										'19' => __( 'Scroll to Row #19', 'fl-builder' ),
										'20' => __( 'Scroll to Row #20', 'fl-builder' ),
									)
								),
							),
						),
					),
				),
			);
		}

		// Sample as to how to pre-process data before it gets sent to the template

		public function pre_process_data( $atts, $module ) {

			$classes = array();
			$data = array();

			if (isset($atts['background_image']) && !empty($atts['background_image'])) {

				if (!empty($atts['background_image_medium_src'])) {
					$data[] = 'data-bg-medium="' . $atts['background_image_medium_src'] . '"';;
				} else {
					$data[] = 'data-bg-medium="' . \wp_get_attachment_image_url($atts['background_image'], 'banner_1260x600'). '"';
				}

				if (!empty($atts['background_image_responsive_src'])) {
					$data[] = 'data-bg-small="' . $atts['background_image_responsive_src'] . '"';
				} else {
					$data[] = 'data-bg-small="' .\wp_get_attachment_image_url($atts['background_image'], 'large-780'). '"';
				}

				$data[] = 'data-bg-default="' . \wp_get_attachment_image_url($atts['background_image'], 'slider_1920x700') . '"';
			}

			if (isset($atts['timeline_logo']) && !empty($atts['timeline_logo'])) {
				$atts['timeline_brand_img'] = \wp_get_attachment_image_url($atts['timeline_logo'], array(115, 150));
			}

			if ((bool)$atts['background_overlay'] ) {
				$classes[] = '-with-overlay';
			}

			$atts['classes'] = implode(' ', $classes);
			$atts['data']	= implode(' ', $data);

			return $atts;
		}

	}
	new timeline_header;
}
