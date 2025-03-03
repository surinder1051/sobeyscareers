<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class search_bar extends fp\Component
	{

		public $schema_version        			= 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $version							= '1.0.0';
		public $component             			= 'search_bar'; // Component slug should be same as this file base name
		public $component_name        			= 'Search Bar'; // Shown in BB sidebar.
		public $component_description 			= '';
		public $component_category    			= 'FP Safeway';
		public $enable_css            			= true;
		public $enable_js             			= true;
		public $deps_css              			= array('brand'); // WordPress Registered CSS Dependencies
		public $deps_js               			= array('jquery'); // WordPress Registered JS Dependencies
		// public $deps_css_remote       			= array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote 		  			= array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir              			= __DIR__;
		public $fields                			= array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig              			= array(); // Placeholder for BB Module Registration
		public $variants              			= array('-compact', '-prefixed'); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content 		= true; // Exclude content of this module from being saved to post_content field
		public $load_in_header		  			= true;
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

			https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref

			Code
			Color
			Editor
			Font
			Icon
			Link
			Loop
			Form
			Multiple Audios
			Multiple Photos
			Photo
			Photo Sizes
			Post Type
			Select
			Service
			Suggest
			Textarea
			Time
			Timezone
			Video

			Repeater Fields
			'multiple'      => true,
			Not supported in Editor Fields, Loop Settings Fields, Photo Fields, and Service Fields.


			*/

			$this->fields = array(
				'fp-search_bar-tab-1' => array(
					'title'         => __('Settings', FP_TD),
					'sections'      => array(
						'attributes' => array(
							'title'     => __('Attributes', FP_TD),
							'fields'    => array(
								'search_button_icon' => array(
									'type'        => 'icon',
									'label'       => __('Search Button Icon', FP_TD),
									'default'	  => 'fpicon-search-red'
								),
							),
						),
					),
				),

			);
		}

		// Sample as to how to pre-process data before it gets sent to the template

		// public function pre_process_data( $atts, $module ) {
		// 	$atts['fp_search_bar_content'] = 'cc';
		// 	return $atts;
		// }

	}

	new search_bar;
}
