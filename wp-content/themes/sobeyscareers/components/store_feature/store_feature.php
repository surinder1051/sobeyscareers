<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
    class store_feature extends fp\Component
    {

        public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $component                         = 'store_feature'; // Component slug should be same as this file base name
        public $component_name                    = 'Store Feature'; // Shown in BB sidebar.
        public $component_description             = 'Store Feature';
        public $component_category                = 'Store Locator';
        public $enable_css                        = true;
        public $enable_js                         = true;
        public $deps_css                          = array(); // WordPress Registered CSS Dependencies
        public $deps_js                           = array('jquery'); // WordPress Registered JS Dependencies
        // public $deps_css_remote       			= array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
        public $deps_js_remote                       = array('https://cdnjs.cloudflare.com/ajax/libs/FitText.js/1.2.0/jquery.fittext.min.js'); // WordPress Registered JS Dependencies
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
			
			**Dynamic Colour Selector Fields
			'type'        => 'fp-colour-picker',
			'element'     => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',

			background class in template class="-bg-[colour selected]"
			button/header class="[colour selected]"

			Additional choices for button: include a select field with options: [outline | solid( default )]
			Outline: class="outline [color selected]"

			**Custom SVG Icon Picker
			'type'        => 'fp-icon-picker',

			Install svg icons through BB font tool in a subdir called /images/

			*/

            $this->forms = array(
                array(
                    'feature_points',
                    array(
                        'title' => __('Feature Points', FP_TD),
                        'tabs'  => array(
                            'general'      => array(
                                'title'         => __('General', FP_TD),
                                'sections'      => array(
                                    'general'       => array(
                                        'title'         => '',
                                        'fields'        => array(
                                            'icon'         => array(
                                                'type'          => 'icon',
                                                'label'         => __('Icon', FP_TD),
                                            ),
                                            'text'         => array(
                                                'type'          => 'text',
                                                'label'         => __('Text', FP_TD),
                                                'default'        => 'Reduced Store Lighting',
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
                    'title'         => __('Settings', FP_TD),
                    'sections'      => array(
                        'attributes' => array(
                            'title'     => __('Attributes', FP_TD),
                            'fields'    => array(
                                'theme' => array(
                                    'type'        => 'select',
                                    'label'       => __('Theme', FP_TD),
                                    'description' => __('Toggle different versions of a store feature.', FP_TD),
                                    'default'     => 'default',
                                    'options'     => array(
                                        'default'      => __('Default', FP_TD),
                                        'image'      => __('Image', FP_TD),
                                    ),
                                    'toggle' => array(
                                        'default' => array(
                                            'fields'   => array('title', 'heading_title', 'subtitle', 'subtitle_typography', 'background_color', 'font_color', 'feature_points_list', 'cta_link'), // Which fields to show when Blue is selected
                                            //'sections' => array('my_section'),  // Which sections to show when Blue is selected
                                            //'tabs'     => array('tab-2'), // Which tabs to show when Blue is selected
                                        ),
                                        'image' => array(
                                            'fields'   => array('title', 'heading_title', 'background_color', 'background_image', 'button_text', 'button_typography', 'background_image_width', 'background_image_pos', 'font_color', 'cta_link'),
                                        ),
                                    ),
                                ),

                                // 'custom_typography' => array(
                                // 	'type'        => 'select',
                                // 	'label'       => __('Custom Typography', FP_TD),
                                // 	'description' => __('Toggle between setting custom typography or auto-resize text via JS.', FP_TD),
                                // 	'default'     => 'fitjs',
                                // 	'options'     => array(
                                // 		'custom'      => __('Custom', FP_TD ),
                                // 		'fitjs'      => __('FitJS', FP_TD ),
                                // 	),
                                // 	'toggle' => array(
                                // 		'custom' => array(
                                // 			'fields'   => array('title_typography','subtitle_typography', 'button_typography', 'feature_typography', 'feature_icon_typography'), // Which fields to show when Blue is selected
                                // 		),
                                // 		'fitjs' => array(
                                // 			'fields'   => array(),
                                // 		),
                                // 	),
                                // ),

                                'title' => array(
                                    'type'    => 'text',
                                    'label'   => __('Title', FP_TD),
                                    'default' => 'Sensory-Friendly Shopping',
                                    // 'connections'   => array( 'string', 'html', 'url' )
                                ),
                                'title_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Title Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'        => 'css',
                                        'selector'  => '.title',
                                    ),
                                ),
                                'subtitle' => array(
                                    'type'          => 'text',
                                    'label'         => __('Sub-Title', FP_TD),
                                    'default'       => 'Every Wednesday, 6-8pm',
                                ),
                                'subtitle_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Sub-Title Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'        => 'css',
                                        'selector'  => '.subtitle',
                                    ),
                                ),
                                'button_text' => array(
                                    'type'          => 'text',
                                    'label'         => __('Button Text', FP_TD),
                                    'default'       => '',
                                ),
                                'button_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Button Text Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'        => 'css',
                                        'selector'  => '.-theme-image .feature-cta-button',
                                    ),
                                ),
                                'background_color' => array(
                                    'type'          => 'color',
                                    'label'         => __('Background Color', FP_TD),
                                    'default'       => '48A647',
                                    'show_reset'    => true,
                                ),
                                'background_image' => array(
                                    'type'          => 'photo',
                                    'label'         => __('Background Image', FP_TD),
                                ),
                                // Turning off for now but we will need to add srcset images to this module
                                // 'background_image_size' => array(
                                // 	'type'        => 'select',
                                // 	'label'       => __('Background Image Size (Desktop)', FP_TD),
                                // 	'default'     => 'left',
                                // 	'options'     => get_registered_thumbnails(),
                                // 	'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['background_image_size'])) ? FP_MODULE_DEFAULTS[$this->component]['background_image_size'] : 'thumbnail', // opg default
                                // ),
                                // 'background_image_size_medium' => array(
                                // 	'type'        => 'select',
                                // 	'label'       => __('Background Image Size (Medium)', FP_TD),
                                // 	'default'     => 'left',
                                // 	'options'     => get_registered_thumbnails(),
                                // 	'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['background_image_size_medium'])) ? FP_MODULE_DEFAULTS[$this->component]['background_image_size_medium'] : 'thumbnail', // opg default
                                // ),
                                // 'background_image_size_responsive' => array(
                                // 	'type'        => 'select',
                                // 	'label'       => __('Background Image Size (Responsive)', FP_TD),
                                // 	'default'     => 'left',
                                // 	'options'     => get_registered_thumbnails(),
                                // 	'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['background_image_size_responsive'])) ? FP_MODULE_DEFAULTS[$this->component]['background_image_size_responsive'] : 'thumbnail', // opg default
                                // ),
                                'background_image_pos' => array(
                                    'type'        => 'select',
                                    'label'       => __('Background Image Position', FP_TD),
                                    'description' => __('Toggle between left or right.', FP_TD),
                                    'default'     => 'left',
                                    'options'     => array(
                                        'left'      => __('Left', FP_TD),
                                        'right'     => __('Right', FP_TD),
                                        'full'      => __('Full', FP_TD),
                                    ),
                                ),
                                'background_image_width' => array(
                                    'type'         => 'unit',
                                    'label'        => 'Backgroud Image Width',
                                    'units'           => array('%'),
                                    'default'      => '50',
                                    'default_unit' => '%', // Optional
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'selector'      => '.background-image',
                                        'property'      => 'width',
                                    ),
                                ),
                                'background_image_aria_label' => array(
                                    'type'          => 'text',
                                    'label'         => __('Background Aria Label', FP_TD),
                                ),
                                'font_color' => array(
                                    'type'          => 'color',
                                    'label'         => __('Font Color', FP_TD),
                                    'default'       => 'FFFFFF',
                                    'show_reset'    => true,
                                ),
                                'cta_link' => array(
                                    'type'    => 'link',
                                    'label'   => __('CTA Link', FP_TD),
                                    'default' => '',
                                ),
                                'feature_points_list' => array(
                                    'type'          => 'form',
                                    'label'         => __('Feature Points', FP_TD),
                                    'form'          => 'feature_points', // ID of a registered form.
                                    'multiple' => true,
                                ),
                                'feature_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Feature Text Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'        => 'css',
                                        'selector'  => '.component_store_feature .-theme-default .feature-list .feature-item-wrapper .feature-item .feature-text',
                                    ),
                                ),
                                'feature_icon_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Feature Icon Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'        => 'css',
                                        'selector'  => '.component_store_feature .-theme-default .feature-list .feature-item-wrapper .feature-item .feature-icon:before',
                                    ),
                                ),

                            ),
                        ),
                    ),
                ),
            );
        }

        // Sample as to how to pre-process data before it gets sent to the template

        // public function pre_process_data($atts, $module)
        // {
        // 	return $atts;
        // }
    }
    new store_feature;
}
