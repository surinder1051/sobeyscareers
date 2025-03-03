<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {

    class facts extends fp\Component
    {

        public $component             = 'facts'; // Component slug should be same as this file base name
        public $schema_version        = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $version               = '1.0.1';
        public $component_name        = '[4] Facts'; // Shown in BB sidebar.
        public $component_description = 'Four Facts';
        public $component_category    = 'FP Global';
        public $enable_css            = true;
        public $enable_js             = false;
        public $deps_css              = array(); // WordPress Registered CSS Dependencies
        public $deps_js               = array('jquery'); // WordPress Registered JS Dependencies
        public $fields                = array(); // Placeholder for fields used in BB Module & Shortcode
        public $bbconfig              = array(); // Placeholder for BB Module Registration
        public $base_dir              = __DIR__;
        public $variants              = array('-no-facts-support-text'); // Component CSS Variants as per -> http://rscss.io/variants.html
        public $id                    = null;
        public $themeOpts;

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

            $this->forms = array(
                array(
                    'fact',
                    array(
                        'title' => __('Fact Attributes', FP_TD),
                        'tabs'  => array(
                            'general'      => array(
                                'title'         => __('General', FP_TD),
                                'sections'      => array(
                                    'general'       => array(
                                        'title'         => '',
                                        'fields'        => array(
                                            'fact_heading' => array(
                                                'type' => 'text',
                                                'label'        => __('Heading', FP_TD),
                                                'test_content' => array(
                                                    'min' => $this->get_sample_text(1, 1131),
                                                    'max' => $this->get_sample_text(8, 521),
                                                ),
                                                'maxlength' => 10,
                                            ),
                                            'fact_sub_heading' => array(
                                                'type' => 'text',
                                                'label'        => __('Sub Heading', FP_TD),
                                                'test_content' => array(
                                                    'min' => $this->get_sample_text(1, 1132),
                                                    'max' => $this->get_sample_text(10, 526),
                                                ),
                                                'maxlength' => 18,
                                            ),
                                            'fact_content' => array(
                                                'type'         => 'editor',
                                                'label'        => __('Supporting Text', FP_TD),
                                                'rows'         => 2,
                                                'test'         => true,
                                                'test_content' => array(
                                                    'emtpy' => '',
                                                    'min' => $this->get_sample_text(5, 1132),
                                                    'max' => $this->get_sample_text(60, 526),
                                                ),
                                                'media_buttons' => false,
                                                'maxlength' => 60,
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
                'fp-facts-tab-1' => array(
                    'title'         => __('Settings', FP_TD),
                    'sections'      => array(
                        'style_attributes' => array(
                            'title'     => __('Style Attributes ', FP_TD),
                            'fields'    => array(
                                'style_background' => array(
                                    'type'        => 'fp-colour-picker',
                                    'label'       => __('Background', FP_TD),
                                    'default'     => '',
                                    'element'     => 'background',
                                ),
                                'style_support_text' => array(
                                    'type'        => 'select',
                                    'label'       => __('Support Text', FP_TD),
                                    'default'     => '',
                                    'options'     => array(
                                        ''                         => __('Show', FP_TD),
                                        '-no-facts-support-text'    => __('Hide', FP_TD),
                                    ),
                                ),
                            ),
                        ),
                        'data_attributes' => array(
                            'title'     => __('Data Attributes ', FP_TD),
                            'fields'    => array(
                                'heading' => array(
                                    'label'       => __('Heading', FP_TD),
                                    'default'     => __('Sample Heading', FP_TD),
                                    'type' => 'text',
                                    'test' => true,
                                    'test_content' => array(
                                        'min' => $this->get_sample_text(5, 131),
                                        'max' => $this->get_sample_text(24, 221),
                                    ),
                                    'maxlength' => 50,
                                ),
                                'heading_tag' => array(
                                    'type'     => 'select',
                                    'label'    => __('Choose the heading tag', FP_TD),
                                    'default'  => __('h2', FP_TD),
                                    'options'   => array(
                                        'h1' => 'Heading 1',
                                        'h2' => 'Heading 2',
                                        'h3' => 'Heading 3',
                                    ),
                                ),
                                'fact_heading_tag' => array(
                                    'type'     => 'select',
                                    'label'    => __('Choose the fact heading tag', FP_TD),
                                    'default'  => __('h3', FP_TD),
                                    'options'   => array(
                                        'h2' => 'Heading 2',
                                        'h3' => 'Heading 3',
                                        'h4' => 'Heading 4',
                                    ),
                                ),
								'fact_heading_style' => array(
                                    'type'     => 'typography',
                                    'label'    => __('Choose the fact heading font style', FP_TD),
									'responsive' => true
                                ),
								'fact_heading_colour' => array(
                                    'type'     => 'color',
                                    'label'    => __('Choose the fact heading font colour', FP_TD),
                                ),
                                'facts' => array(
                                    'multiple' => true,
                                    'type'    => 'form',
                                    'label'   => __('Fact', FP_TD),
                                    'form'          => 'fact', // ID of a registered form.
                                    'preview_text'  => 'fact_heading', // ID of a field to use for the preview text.
                                    'default'         => array(
                                        0 => array(
                                            'fact_heading'         => 'Fact 1',
                                            'fact_sub_heading'     => 'subheading',
                                            'fact_content'         => '<p>la lacus elementum vitae. Pellentesque id sodales justo. Eti</p>',
                                        ),
                                        1 => array(
                                            'fact_heading'         => 'Fact 2',
                                            'fact_sub_heading'     => 'subheading',
                                            'fact_content'         => '<p>la lacus elementum vitae. Pellentesque id sodales justo. Eti</p>',
                                        ),
                                        2 => array(
                                            'fact_heading'         => 'Fact 3',
                                            'fact_sub_heading'     => 'subheading',
                                            'fact_content'         => '<p>la lacus elementum vitae. Pellentesque id sodales justo. Eti</p>',
                                        ),
                                        3 => array(
                                            'fact_heading'         => 'Fact 4',
                                            'fact_sub_heading'     => 'subheading',
                                            'fact_content'         => '<p>la lacus elementum vitae. Pellentesque id sodales justo. Eti</p>',
                                        ),
                                    ),
                                    'limit' => 4
                                ),
                            ),
                        ),
                    ),
                ),
            );
        }

        // Sample as to how to pre-process data before it gets sent to the template

        public function pre_process_data($atts, $module = '')
        {
            $factClass = array('one-fact', 'two-facts', 'three-facts', '');
            $classes[] = '-bg-' . $atts['style_background'];
            $classes[] = $atts['style_support_text'];
            $classes[] = 'component-facts'; //use the hyphenated version to fix linting errors
            $classes[] = $factClass[count($atts['facts']) - 1];
            $atts['classes'] = implode(' ', $classes);

            return $atts;
        }
    }

    new facts;
}