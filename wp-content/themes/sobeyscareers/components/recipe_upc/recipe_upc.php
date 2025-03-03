<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
    class recipe_upc extends fp\Component
    {

        public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $version                           = '1.0.1';
        public $component                         = 'recipe_upc'; // Component slug should be same as this file base name
        public $component_name                    = 'Recipe UPC'; // Shown in BB sidebar.
        public $component_description             = 'UPC code render.';
        public $component_category                = 'Sobeys Recipes';
        public $component_load_category           = 'recipes';
        public $enable_css                        = false;
        public $enable_js                         = false;
        public $deps_css                          = array(); // WordPress Registered CSS Dependencies
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
                'settings' => array(
                    'title'         => __('Settings', FP_TD),
                    'sections'      => array(
                        'attributes' => array(
                            'title'     => __('Attributes', FP_TD),
                        ),
                    ),
                ),
            );

            if (!has_filter('strip_multiple', array($this, 'pre_process_data'))) {
				add_filter('strip_multiple', array($this, 'pre_process_data'), 10, 4);
			}
        }


        public function strip_multiple( $value ) {

			if ( strpos( $value, ';' ) !== false ) {
                $value = explode( ';', $value );
                return $value[0];
            }
            else{
                return $value;
            }
		}

        public function pre_process_data( $atts, $module ) {
            
            global $post;

            // Get a valid upc and strip multiple values to take only the first
            $upc = get_post_meta( $post->ID, 'general_upc-a', true );

            if( $upc == '' ){

                $upc = get_post_meta( $post->ID, 'general_ean-13', true );

                if( $upc == '' ){
                    $upc = get_post_meta( $post->ID, 'general_ean-8', true );
                }

            }

            if( $upc !== '' ){            
                $atts['upc'] = $this->strip_multiple( $upc );              
            }
            
            
        	return $atts;
        }

    }

    new recipe_upc;
}
