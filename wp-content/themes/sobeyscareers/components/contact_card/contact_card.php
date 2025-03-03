<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
    class contact_card extends fp\Component
    {

        public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $component                         = 'contact_card'; // Component slug should be same as this file base name
        public $component_name                    = 'contact_card'; // Shown in BB sidebar.
        public $component_description             = 'Description goes here';
        public $component_category                = 'FP Global';
        public $enable_css                        = true;
        public $enable_js                         = true;
        public $deps_css                          = array(); // WordPress Registered CSS Dependencies
        public $deps_js                           = array('jquery'); // WordPress Registered JS Dependencies
        // public $deps_css_remote       			= array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
        // public $deps_js_remote 		  			= array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
        public $base_dir                          = __DIR__;
        public $fields                            = array(); // Placeholder for fields used in BB Module & Shortcode
        public $bbconfig                          = array(); // Placeholder for BB Module Registration
        public $variants                          = array('-compact', '-prefixed'); // Component CSS Variants as per -> http://rscss.io/variants.html
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

            $this->forms = array(
                array(
                    'contact_form',
                    array(
                        'title' => __('Contacts', FP_TD),
                        'tabs'  => array(
                            'general'      => array(
                                'title'         => __('General', FP_TD),
                                'sections'      => array(
                                    'general'       => array(
                                        'title'         => '',
                                        'fields'        => array(
                                            'location'         => array(
                                                'type'          => 'text',
                                                'label'         => __('Location', FP_TD),
                                            ),
                                            'contact'         => array(
                                                'type'          => 'text',
                                                'label'         => __('Contact', FP_TD),
                                            ),
                                            'phone'         => array(
                                                'type'          => 'text',
                                                'label'         => __('Phone', FP_TD),
                                            ),
                                            'email'         => array(
                                                'type'          => 'text',
                                                'label'         => __('Email', FP_TD),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            );

            $this->fields = array(
                'tab-1' => array(
                    'title'         => __('Content', FP_TD),
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
                                'title2' => array(
                                    'type'    => 'text',
                                    'label'   => __('Title2', FP_TD),
                                    'default' => 'Title2',
                                    // 'connections'   => array( 'string', 'html', 'url' )
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
                                'contacts' => array(
                                    'type'          => 'form',
                                    'label'         => __('Contacts', FP_TD),
                                    'form'          => 'contact_form', // ID of a registered form.
                                    'multiple' => true,
                                    'preview_text'  => 'label', // ID of a field to use for the preview text.
                                ),
                            ),
                        ),
                    ),
                ),
                'style' => array(
                    'title'         => __('Style', FP_TD),
                    'sections'      => array(
                        'heading' => array(
                            'title'     => __('Heading', FP_TD),
                            'fields'    => array(
                                'heading_style' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Heading Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'        => 'css',
                                        'selector'  => '.title',
                                    ),
                                ),
                                'heading2_style' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Heading Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'        => 'css',
                                        'selector'  => '.title2',
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
            $atts['content'] = 'cc';
            // var_dump($atts);
            return $atts;
        }
    }
    new contact_card;
}
