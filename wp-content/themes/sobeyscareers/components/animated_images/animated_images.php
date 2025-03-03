<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class animated_images extends fp\Component
	{

		public $schema_version        			= 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version                         	= '1.0.1';
		public $component             			= 'animated_images'; // Component slug should be same as this file base name
		public $component_name        			= 'Animated Images'; // Shown in BB sidebar.
		public $component_description 			= 'Exposes rotating images on scroll.';
		public $component_category    			= 'FP Global';
		public $enable_css            			= true;
		public $enable_js             			= true;
		public $deps_css              			= array(); // WordPress Registered CSS Dependencies
		public $deps_js               			= array('jquery'); // WordPress Registered JS Dependencies
		// public $deps_css_remote       			= array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote 		  			= array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir              			= __DIR__;
		public $fields                			= array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig              			= array(); // Placeholder for BB Module Registration
		public $variants              			= array(); // Component CSS Variants as per -> http://rscss.io/variants.html
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
				'tab-1' => array(
					'title'         => __('Settings', FP_TD),
					'sections'      => array(
						'attributes' => array(
							'title'     => __('Attributes', FP_TD),
							'fields'    => array(
								'static_image' => array(
									'type'    => 'photo',
									'label'   => __('Static Image', FP_TD),
									'default' => '',
									'show_remove'   => true,
								),
								'static_image_size' => array(
									'type'        => 'select',
									'label'       => __('Static Image Size', FP_TD),
									'default'     => 'full',
									'options'     => get_registered_thumbnails(),
									'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['image_size'])) ? FP_MODULE_DEFAULTS[$this->component]['image_size'] : 'full',
								),
								'left_image' => array(
									'type'     => 'photo',
									'label'    => __('Left Image', FP_TD),
									'default'  => '',
									'show_remove'   => true,
								),
								'left_image_size' => array(
									'type'        => 'select',
									'label'       => __('Left Image Size', FP_TD),
									'default'     => 'full',
									'options'     => get_registered_thumbnails(),
									'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['image_size'])) ? FP_MODULE_DEFAULTS[$this->component]['image_size'] : 'full',
								),
								'right_image' => array(
									'type'     => 'photo',
									'label'    => __('Right Image', FP_TD),
									'default'  => '',
									'show_remove'   => true,
								),
								'right_image_size' => array(
									'type'        => 'select',
									'label'       => __('Right Image Size', FP_TD),
									'default'     => 'full',
									'options'     => get_registered_thumbnails(),
									'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['image_size'])) ? FP_MODULE_DEFAULTS[$this->component]['image_size'] : 'full',
								),
							),
						),
					),
				),
			);
		}

		// Sample as to how to pre-process data before it gets sent to the template

		// public function pre_process_data( $atts, $module ) {
		// 	$atts['content'] = 'cc';
		// 	return $atts;
		// }

	}
	new animated_images;
}
