<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
    class recipe_characteristics extends fp\Component
    {

        public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $version                           = '1.0.0';
        public $component                         = 'recipe_characteristics'; // Component slug should be same as this file base name
        public $component_name                    = 'recipe_characteristics'; // Shown in BB sidebar.
        public $component_description             = 'List characteristics as tags for recipes or products.';
        public $component_category                = 'Sobeys Recipes';
        public $component_load_category         = 'recipes';
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


			*/

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
                                'title_align' => array(
                                    'type'    => 'align',
                                    'label'   => __('Title Align', FP_TD),
                                    'default' => 'center',
                                ),
                                'title_tag' => array(
                                    'type'        => 'select',
                                    'label'       => __('Title Tag', FP_TD),
                                    'default'     => 'h2',
                                    'options'     => array(
                                        'h2'     => __('H2', FP_TD),
                                        'h3'     => __('H3', FP_TD),
                                        'h4'     => __('H4', FP_TD),
                                        'h5'     => __('H5', FP_TD),
                                        'h6'     => __('H6', FP_TD),
                                    ),
                                ),
                                'taxonomy' => array(
                                    'type'        => 'fp-taxonomies-select-dropdown',
                                    'label'       => __('Taxonomies', FP_TD),
                                    'description' => __('Choose which taxnomies\'s terms you would like listed.', FP_TD),
                                    'multi-select' => false,
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
            global $post;
            $atts['terms'] = get_the_terms($post->ID, $atts['taxonomy']);
            return $atts;
        }
    }
    new recipe_characteristics;
}
