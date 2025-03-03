<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class loop extends fp\Component
	{

		public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version							  = '1.0.0';
		public $component                         = 'loop'; // Component slug should be same as this file base name
		public $component_name                    = 'loop'; // Shown in BB sidebar.
		public $component_description             = 'Module used to loop over shortcodes or other modules';
		public $component_category                = 'FlowPress Modules';
		public $enable_css                        = true;
		public $enable_js                         = false;
		public $deps_css                          = array(); // WordPress Registered CSS Dependencies
		public $deps_js                           = array(); // WordPress Registered JS Dependencies
		// public $deps_css_remote       			= array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote 		  			= array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir                          = __DIR__;
		public $fields                            = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig                          = array(); // Placeholder for BB Module Registration
		public $variants                          = array(); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content 		= true; // Exclude content of this module from being saved to post_content field
		// public $load_in_header		  			= true;
		public $dynamic_data_feed_parameters     = array( // Generates $atts[posts] object with dynamically populated data
			// 'pagination_api' => true, // enable ajax pagination
			'posts_per_page_default' => '3',
			'posts_per_page_options' => array(
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
				'6' => 6,
				'7' => 7,
				'8' => 8,
				'9' => 9,
			),
			'post_types' => array(),
			'taxonomies' => array(
				// array('category' => array('none-option' => true)),
				// array('recipe_type' => array('none-option' => true)),
				// array('recipe_feature' => array('none-option' => true)),
				// array('preperation_time' => array('none-option' => true)),
				// array('recipe_difficulties' => array('none-option' => true)),
				// array('recipe_theme' => array('none-option' => true)),
			)
		);

		public function init_fields()
		{

			global $fp_taxonomie_keys, $fp_post_types_keys;
			if (is_array($fp_taxonomie_keys)) {
				foreach ($fp_taxonomie_keys as $key => $value) {
					$this->dynamic_data_feed_parameters['taxonomies'][] =  array($value => array('none-option' => true));
				}
			}
			$this->dynamic_data_feed_parameters['post_types'] = $fp_post_types_keys;

			$this->fields = array(
				'fp-loop-tab-1' => array(
					'title'         => __('Settings', FP_TD),
					'sections'      => array(
						'attributes' => array(
							'title'     => __('Attributes', FP_TD),
							'fields'    => array(
								'shortcode' => array(
									'type'    => 'text',
									'label'   => __('Shortcode', FP_TD),
									'default' => 'recipe_card',
									'description' => ('Enter in shortcode that will be used to generate the individual loop content')
								),
								'no_container' => array(
									'type'        => 'select',
									'label'       => __('Hide Container Div', FP_TD),
									'description' => __('Turn off automatic container created by BB.', FP_TD),
									'default'     => 'true',
									'options'     => array(
										'true'      => __('False', FP_TD),
										'false'      => __('True', FP_TD),
									),
								),
								'enable_mobile_slider' => array(
									'type'        => 'select',
									'label'       => __('Enabled Mobile Slider', FP_TD),
									'description' => __('On mobile view a slider will be used to display one item at a time', FP_TD),
									'default'     => 'false',
									'options'     => array(
										'true'      => __('True', FP_TD),
										'false'      => __('False', FP_TD),
									),
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

			if (!empty($atts['enable_mobile_slider']) && $atts['enable_mobile_slider']) {
				// This enables a class to be added so that JS can pickup and make this container into a mobile carousel
				$atts['classes'] .= ' mobile_carousel';
			}

			return $atts;
		}
	}

	new loop;
}
