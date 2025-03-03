<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class bs_card_horizontal_dynamic extends fp\Component
	{
		public $schema_version        			= 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version							= '1.1.2';
		public $component             			= 'bs_card_horizontal_dynamic'; // Component slug should be same as this file base name
		public $component_name        			= 'bs_card_horizontal_dynamic'; // Shown in BB sidebar.
		public $component_description 			= 'This component is only accessed via dynamic shortcode';
		public $component_category    			= 'FP Global';
		public $component_load_category 		= 'bootstrap';
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

			$this->fields = array(
				'fp-bs_card_horizontal_dynamic-tab-1' => array(
					'title'         => __('Settings', FP_TD),
					'sections'      => array(
						'attributes' => array(
							'title'     => __('Attributes', FP_TD),
							'fields'    => array(
								'id' => array(
									'type'    => 'text',
									'label'   => __('Id', FP_TD),
									'default' => '',
								),
								'thumbnail_image_size' => array(
									'type'        => 'select',
									'label'       => __('Thumbnail Image Size', FP_TD),
									'default'     => 'left',
									'options'     => get_registered_thumbnails(),
									'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['thumbnail_image_size'])) ? FP_MODULE_DEFAULTS[$this->component]['thumbnail_image_size'] : 'thubmnail', // opg default
								),
								'title_tag' => array(
									'type'     => 'select',
									'label'    => __('Choose the title tag', FP_TD),
									'default'  => 'h2',
									'options'   => array(
										'h1' => 'Heading 1',
										'h2' => 'Heading 2',
										'h3' => 'Heading 3',
										'h4' => 'Heading 4',
										'h5' => 'Heading 5',
										'h6' => 'Heading 6',
									),
								),
								'show_date' => array(
									'type'        => 'select',
									'label'       => __('Show Date', FP_TD),
									'description' => __('Show post publish date', FP_TD),
									'default'     => 'false',
									'options'     => array(
										'true'      => __('False', FP_TD),
										'false'      => __('True', FP_TD),
							),
						),
								'show_read_more' => array(
									'type'        => 'select',
									'label'       => __('Show Read More', FP_TD),
									'description' => __('Turn off automatic container created by BB.', FP_TD),
									'default'     => 'false',
									'options'     => array(
										'true'      => __('False', FP_TD),
										'false'      => __('True', FP_TD),
					),
				),
								'read_more_label' => array(
									'type'    => 'text',
									'label'   => __('Read More Label', FP_TD),
									'default' => __('Read More', FP_TD),
								),
							),
						),
					),
				),
			);
		}

		// Sample as to how to pre-process data before it gets sent to the template

		public function pre_process_data($atts, $module)
		{
			if (!empty($atts['id'])) {
				$atts['post'] = get_post($atts['id']);
			} else {
				global $post;
				$atts['post'] = $post;
			}
			setup_postdata($atts['post']);
			return $atts;
		}
	}

	new bs_card_horizontal_dynamic;
}
