<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class recipe_nutrition_facts extends fp\Component
	{

		public $schema_version               = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version                      = '1.0.2';
		public $component                    = 'recipe_nutrition_facts'; // Component slug should be same as this file base name
		public $component_name               = 'Recipe Nutrition Facts'; // Shown in BB sidebar.
		public $component_description        = 'Lists the nutritional facts of a product or recipe.';
		public $component_category           = 'Sobeys Recipes';
		public $component_load_category      = 'recipes';
		public $enable_css                   = true;
		public $enable_js                    = false;
		public $deps_css                     = array(); // WordPress Registered CSS Dependencies
		public $deps_js                      = array(); // WordPress Registered JS Dependencies
		// public $deps_css_remote              = array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote               = array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
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

			// if (function_exists('pll_register_string')) {
			// 	pll_register_string('Calories', 'Calories', FP_TD, false);
			// 	pll_register_string('Per','Per', FP_TD, false);
			// 	pll_register_string('Calories','Calories', FP_TD, false);
			// 	pll_register_string('Fat','Fat', FP_TD, false);
			// 	pll_register_string('Saturated Fat','Saturated Fat', FP_TD, false);
			// 	pll_register_string('Monounsaturated Fats','Monounsaturated Fats', FP_TD, false);
			// 	pll_register_string('Trans','Trans', FP_TD, false);
			// 	pll_register_string('Trans','Trans', FP_TD, false);
			// 	pll_register_string('Carbs','Carbs', FP_TD, false);
			// 	pll_register_string('Fibre','Fibre', FP_TD, false);
			// 	pll_register_string('Sugar','Sugar', FP_TD, false);
			// 	pll_register_string('Sugar Alcohols','Sugar Alcohols', FP_TD, false);
			// 	pll_register_string('Cholesterol','Cholesterol', FP_TD, false);
			// 	pll_register_string('Protein','Protein', FP_TD, false);
			// 	pll_register_string('Iron','Iron', FP_TD, false);
			// 	pll_register_string('Sodium','Sodium', FP_TD, false);
			// 	pll_register_string('Potassium','Potassium', FP_TD, false);
			// 	pll_register_string('Calcium','Calcium', FP_TD, false);
			// 	pll_register_string('Omega 3','Omega 3', FP_TD, false);
			// 	pll_register_string('Omega 6','Omega 6', FP_TD, false);
			// 	pll_register_string('Vitamin A','Vitamin A', FP_TD, false);
			// 	pll_register_string('Vitamin C','Vitamin C', FP_TD, false);
			// }

			$this->fields = array(
				'fp-recipe_nutrition_facts-tab-1' => array(
					'title'    => __('Settings', FP_TD),
					'sections' => array(
						'attributes' => array(
							'title'  => __('Attributes', FP_TD),
							'fields' => array(
								'title' => array(
									'type'    => 'text',
									'label'   => __('Title', FP_TD),
									'default' => __('Nutritional Facts', FP_TD),
								),
								'title_tag' => array(
									'type'    => 'select',
									'label'   => __('Title Tag', FP_TD),
									'default' => 'h2',
									'options' => array(
										'h2' => 'H2',
										'h3' => 'H3',
										'h4' => 'H4',
										'h5' => 'H5',
										'h6' => 'H6',
									),
								),
								'title_typography' => array(
									'type'       => 'typography',
									'label'      => __('Title Typography', FP_TD),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.title',
									),
									'default'    => array(
										'family'     => 'Helvetica',
										'font-size'  => 12,
										'weight'     => 300
									)
								),
								'heading_color' => array(
									'type'        => 'color',
									'label'       => __('Title Color', FP_TD),
									'default'     => '000000',
									'preview'     => array(
										'type'      => 'css',
										'selector'  => '.title',
									),
									'show_reset'  => true,
								),
								'background_color' => array(
									'type'       => 'color',
									'label'      => __('Background Color', FP_TD),
									'default'    => 'FFFFFF',
									'show_reset' => true,
								),
								'show_daily_value' => array(
									'type'    => 'select',
									'label'   => __('Show Daily Value', FP_TD),
									'default' => 'false',
									'options' => array(
										'false' => __('No', FP_TD),
										'true'  => __('Yes', FP_TD),
									),
								),
							),
						),
					),
				),
			);

			if (!has_filter('nutrition_facts_unit', array($this, 'nutrition_facts_data'))) {
				add_filter('nutrition_facts_unit', array($this, 'nutrition_facts_data'), 10, 4);
			}
		}

		public function nutrition_facts_unit($type, $value)
		{
			if (strpos($value, 'g') === false) {
				$value = $value . " " . $type;
			}
			return $value;
		}

		public function nutrition_facts_data($value, $key)
		{
			switch ($key) {
				case "nutrition_info_nutrition_description":
				case "nutrition_info_calories":
				case "nutrition_info_potassium":
					$value = $value;
					break;
				case 'nutrition_info_fat':
				case 'nutrition_info_saturated_fat':
				case 'nutrition_info_carbs':
				case 'nutrition_info_sugar':
				case 'nutrition_info_protein':
				case 'nutrition_info_fibre':
					$value = $this->nutrition_facts_unit('g', $value);
					break;
				case "nutrition_info_sodium":
				case "nutrition_info_cholesterol":
					$value = $this->nutrition_facts_unit('mg', $value);
					break;
				default:
					$value = $this->nutrition_facts_unit('', $value);
			}
			return $value;
		}

		public function pre_process_data($atts, $module)
		{
			global $post;

			$atts['meta'] = get_post_meta($post->ID);

			$atts['show_these_fields'] = array(
				array(
					'title' => __('Nutrition Description'),
					'key'   => 'nutrition_info_nutrition_description'
				),
				array(
					'title' => __('Serving Size'),
					'key'   => 'nutrition_info_serving_size'
				),
				array(
					'title' => __('Servings per tray'),
					'key'   => 'nutrition_info_servings_per_tray'
				),
				array(
					'title' => __('Per', FP_TD),
					'key'   => 'general_serving_size_amount'
				),
				array(
					'title' => __('Calories', FP_TD),
					'key'   => 'nutrition_info_calories'
				),
				array(
					'title' => __('Fat', FP_TD),
					'key'   => 'nutrition_info_fat'
				),
				array(
					'title' => __('Saturated Fat', FP_TD),
					'key'   => 'nutrition_info_saturated_fat'
				),
				array(
					'title' => __('Monounsaturated Fats', FP_TD),
					'key'   => 'nutrition_info_monounsaturated_fats'
				),
				array(
					'title' => __('Trans', FP_TD),
					'key'   => 'nutrition_info_trans_fat' // for Compliments
				),
				array(
					'title' => __('Trans', FP_TD),
					'key'   => 'nutrition_info_trans' // for Sobeys
				),
				array(
					'title' => __('Carbs', FP_TD),
					'key'   => 'nutrition_info_carbs'
				),
				array(
					'title' => __('Fibre', FP_TD),
					'key'   => 'nutrition_info_fibre'
				),
				array(
					'title' => __('Sugar', FP_TD),
					'key'   => 'nutrition_info_sugar'
				),
				array(
					'title' => __('Sugar Alcohols', FP_TD),
					'key'   => 'nutrition_info_sugar_alcohols'
				),
				array(
					'title' => __('Cholesterol', FP_TD),
					'key'   => 'nutrition_info_cholesterol'
				),
				array(
					'title' => __('Protein', FP_TD),
					'key'   => 'nutrition_info_protein'
				),
				array(
					'title' => __('Iron', FP_TD),
					'key'   => 'nutrition_info_iron'
				),
				array(
					'title' => __('Sodium', FP_TD),
					'key'   => 'nutrition_info_sodium'
				),
				array(
					'title' => __('Potassium', FP_TD),
					'key'   => 'nutrition_info_potassium'
				),
				array(
					'title' => __('Calcium', FP_TD),
					'key'   => 'nutrition_info_calcium'
				),
				array(
					'title' => __('Omega 3', FP_TD),
					'key'   => 'nutrition_info_omega_3'
				),
				array(
					'title' => __('Omega 6', FP_TD),
					'key'   => 'nutrition_info_omega_6'
				),
				array(
					'title' => __('Vitamin A', FP_TD),
					'key'   => 'nutrition_info_vitamin_a'
				),
				array(
					'title' => __('Vitamin C', FP_TD),
					'key'   => 'nutrition_info_vitamin_c'
				),
			);

			$atts['show_daily_value'] = !empty($atts['show_daily_value']) && $atts['show_daily_value'] == 'true' ? true : false;

			return $atts;
		}
	}
}
