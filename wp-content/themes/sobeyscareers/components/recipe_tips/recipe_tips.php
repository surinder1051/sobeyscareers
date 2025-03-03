<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
    class recipe_tips extends fp\Component
    {

        public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $version                           = '1.0.0';
        public $component                         = 'recipe_tips'; // Component slug should be same as this file base name
        public $component_name                    = 'Recipe Tips'; // Shown in BB sidebar.
        public $component_description             = 'Recipe post type tips.';
        public $component_category                = 'Sobeys Recipes';
        public $enable_css                        = true;
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
            // ),
            // 'order' => 'DESC',
            // 'orderby' => 'menu_order',
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
                                'title' => array(
                                    'type'    => 'text',
                                    'label'   => __('Title', FP_TD),
                                    'default' => 'Title',
                                    // 'connections'   => array( 'string', 'html', 'url' )
                                ),
                                'title_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => __('Title Typography', FP_TD),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'        => 'css',
                                        'selector'  => '.title',
                                    ),
                                    'default'       => array(
                                        'family'        => 'Helvetica',
                                        'weight'        => 300
                                    )
                                ),
                                'title_tag' => array(
                                    'type'        => 'select',
                                    'label'       => __('Title Tag', FP_TD),
                                    'default'     => 'h2',
                                    'options'     => array(
                                        'h1'     => __('H1', FP_TD),
                                        'h2'     => __('H2', FP_TD),
                                        'h3'     => __('H3', FP_TD),
                                        'h4'     => __('H4', FP_TD),
                                        'h5'     => __('H5', FP_TD),
                                        'h6'     => __('H6', FP_TD),
                                    ),
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
    new recipe_tips;
}
