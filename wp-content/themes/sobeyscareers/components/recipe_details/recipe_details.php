<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class recipe_details extends fp\Component
	{
		public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $version                           = '1.0.1';
        public $component                         = 'recipe_details'; // Component slug should be same as this file base name
        public $component_name                    = 'Recipe Details'; // Shown in BB sidebar.
        public $component_description             = 'Details with icons of recipe meta.';
        public $component_category                = 'Sobeys Recipes';
        public $component_load_category           = 'recipes';
        public $enable_css                        = true;
        public $enable_js                         = false;
        public $deps_css                          = array('brand'); // WordPress Registered CSS Dependencies
        public $deps_js                           = array('jquery'); // WordPress Registered JS Dependencies
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
				'static_cooking_text' => array(
					'title'    => __( 'Settings', FP_TD ),
					'sections' => array(
						'accordion' => array(
							'fields' => array(
								'total_time_text' => array(
									'type'        => 'text',
									'label'       => __( 'Total Time Text', FP_TD ),
									'description' => __( '', FP_TD ),
									'default'     => __( 'Total Time', FP_TD )
								)
							)
						)
					)
				)
			);
		}

        /**
         * Given minutes value, convert to hr and min format.
         *
         * @param integer $time
         * @param string $format
         * @return string
         */
        public static function convertToHoursMins($time, $format = '%02d:%02d')
		{
            $hours = floor($time / 60);
            $minutes = ($time % 60);

            if ($minutes == 0) {
                $output_format = '%2d h';

                $hoursToMinutes = sprintf($output_format, $hours);
            } elseif ($hours == 0) {
                if ($minutes < 10) {
                    $minutes = '0'.$minutes;
                }

                if ($minutes == 1) {
                    $output_format = ' %2d min';
                } else {
                    $output_format = ' %2d mins';
                }

                $hoursToMinutes = sprintf($output_format, $minutes);
            } else {
                if ($hours == 1) {
                    $output_format = '%2d h %2d m';
                } else {
                    $output_format = '%2d h %2d m';
                }

                $hoursToMinutes = sprintf($output_format, $hours, $minutes);
            }

            return $hoursToMinutes;
		}

        /**
         * Some time meta has hours, this pulls that out so we can properly parse and display time in the same format across all sites.
         *
         * @param string $value
         * @return float|boolean
         */
        public static function get_hour_value( $value = '' ) {
            if ( preg_match('/(\d+)\s+(?:hours|hour|hrs|hr|h)/i', $value, $matches) ) {
                return (float) $matches[1];
            }
            return false;
        }

        /**
         * Remove the hour string if any and return the numeric value so we can parse the time.
         *
         * @param string $value
         * @return float|boolean
         */
        public static function get_minute_value( $value = '' ) {
            $remove_hours = preg_replace('/(\d+)\s+(?:hours|hour|hrs|hr|h)/i', "", $value );
            return (float) preg_replace("/[^0-9-]/", "", $remove_hours);
        }

		public function pre_process_data($atts, $module)
		{
			global $post;

			$atts['field_data'] = [
				[
					'field' => 'settings_yield',
					'icon' => 'icon-serve',
					'label' => __('Makes', FP_TD),
				],
				[
					'field' => 'cooking_skill_level',
					'icon' => 'icon-kitchen-active',
					'label' => __('Level', FP_TD),
				],
				[
					'field' => 'cooking_marinate_time',
					'icon' => 'icon-marinate',
					'label' => __('Marinate Time', FP_TD),
					'convert_mins' => true,
				],
				[
					'field' => 'cooking_prep_time',
					'icon' => 'icon-prep-time',
					'label' => __('Prep Time', FP_TD),
					'convert_mins' => true,
				],
				[
					'field' => 'cooking_cook_time',
					'icon' => 'icon-total-time',
					'label' => __('Cooking Time', FP_TD),
					'convert_mins' => true,
				],
				[
					'field' => 'cooking_total_time',
					'icon' => 'icon-total-time',
					'label' => __('Total Time', FP_TD),
					'convert_mins' => true,
				],
				[
					'field' => 'general_total_time', // Easy Meal Recipes ( Only Sobeys.com )
					'icon' => 'icon-total-time',
					'label' => __('Total Time', FP_TD),
					'convert_mins' => true,
				],
				[
					'field' => 'general_temperature',
					'icon' => 'icon-store-meals-to-go',
					'label' => __('Temperature', FP_TD),
				],
				[
					'field' => 'general_yield',
					'icon' => 'icon-serve',
					'label' => __('Serves', FP_TD),
				],
			];

			foreach ($atts['field_data'] as $key => &$single_data) {
				if ($value = get_post_meta($post->ID, $single_data['field'], true)) {
					if (strpos($value, ':') !== false) {
						// There seems to be label in value
						$value = explode(':', $value);
						if (strtolower($value[0]) == strtolower($single_data['label'])) {
							$value = trim($value[1]);
						} else {
							// Overwrite label with label that was in the value
							$single_data['label'] = $value[0];
							$value = trim($value[1]);
						}
					}
                    // If it's a time meta..
					if (isset($single_data['convert_mins']) && $single_data['convert_mins']) {
                        // Check if there's hour value in the string, if not, just process as minutes.
                        $hours = self::get_hour_value( $value );
                        if (empty($hours)) {
                            $minutes = self::get_minute_value( $value );
                            $single_data['value'] = trim( self::convertToHoursMins( $minutes ) );
                        } else {
                            // If there's hour value, add that to the minute value then process time format.
                            $minutes = self::get_minute_value( $value );
                            $total_minutes = (60 * $hours) + $minutes;
                            $single_data['value'] = trim( self::convertToHoursMins( $total_minutes ) );
                        }
						
					} else {
						$single_data['value'] = $value;
					}
				} else {
					unset($atts['field_data'][$key]);
				}
			}

			return $atts;
		}
	}

	new recipe_details();
}
