<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class loop_terms extends fp\Component
	{

		public $schema_version        			= 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version							= '1.0.1';
		public $component             			= 'loop_terms'; // Component slug should be same as this file base name
		public $component_name        			= 'loop_terms'; // Shown in BB sidebar.
		public $component_description 			= 'This is a wrapper component to display selected modules within a custom taxonomy loop';
		public $component_category    			= 'FP Global';
		public $enable_css            			= false;
		public $enable_js             			= false;
		public $deps_css              			= array(); // WordPress Registered CSS Dependencies
		public $deps_js               			= array(); // WordPress Registered JS Dependencies
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

			$this->fields = array(
				'tab-1' => array(
					'title'         => __('Settings', FP_TD),
					'sections'      => array(
						'attributes' => array(
							'title'     => __('Attributes', FP_TD),
							'fields'    => array(
								'shortcode' => array(
									'type'    => 'text',
									'label'   => __('Shortcode', FP_TD),
									'default' => 'bs_card',
									// 'connections'   => array( 'string', 'html', 'url' )
								),
								'no_container' => array(
									'type'        => 'select',
									'label'       => __('Hide Container Div', FP_TD),
									'description' => __('Turn off automatic container created by BB.', FP_TD),
									'default'     => 'true',
									'options'     => array(
										'true'      => __('False', 'fl-builder'),
										'false'      => __('True', 'fl-builder'),
									),
								),
								'taxonomies' => array(
									'type'        => 'fp-taxonomies-select-dropdown',
									'label'       => __('Taxonomies', FP_TD),
									'description' => __('Choose which taxonomies\'s terms you would like listed.', FP_TD),
									'multi-select' => true,
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
			$atts['terms'] = get_terms($atts['taxonomies'], array(
				'hide_empty' => false,
			));

			if (is_wp_error($atts['terms'])) {
				$atts['terms'] = array();
				return $atts;
			}

			foreach ($atts['terms'] as $key => $term) {
				// $button_text = get_field('how_to_button_text', $postId);
				// if (empty($button_text)) {
				// 	$button_text = get_the_title();
				// }
				// $atts['terms'][$key]->image = get_field('image', $term)['ID'];
				// ['sizes']['tile_640x420']
				// var_dump($atts['terms'][$key]->image);
			}

			// var_dump($atts);
			return $atts;
		}
	}
	new loop_terms;
}
