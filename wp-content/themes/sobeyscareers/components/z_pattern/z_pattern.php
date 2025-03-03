<?php

namespace fp\components;

use fp;

class z_pattern extends fp\Component
{

    public $component             = 'z_pattern';                                                                                                                                                                           // Component slug should be same as this file base name
    public $component_name        = 'Z-Pattern';                                                                                                                                                                           // Shown in BB sidebar.
    public $component_description = 'Z-Pattern with Link or Button';
    public $component_category    = 'FP Global';
    public $enable_css            = true;
    public $enable_js             = false;
    public $deps_css              = array();                                                                                                                                                                               // WordPress Registered CSS Dependencies
    public $deps_js               = array('jquery');                                                                                                                                                                       // WordPress Registered JS Dependencies
    public $fields                = array();                                                                                                                                                                               // Placeholder for fields used in BB Module & Shortcode
    public $bbconfig              = array();                                                                                                                                                                               // Placeholder for BB Module Registration
    public $base_dir              = __DIR__;
    public $variants              = array('-left-to-right', '-right-to-left', '-background-left', '-background-right', '-white-bg', '-grey-bg', '-link-style-button', '-link-style-text', '-square-edge', '-angle-edge');  // Component CSS Variants as per -> http://rscss.io/variants.html
    public $schema_version        = 3;                                                                                                                                                                                     // This needs to be updated manuall when we make changes to the this template so we can find out of date components
    public $load_in_header        = false;

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
                'links',
                array(
                    'title' => __('Link Attributes', FP_TD),
                    'tabs'  => array(
                        'general'      => array(
                            'title'         => __('General', FP_TD),
                            'sections'      => array(
                                'general'       => array(
                                    'title'         => '',
                                    'fields'        => array(
                                        'title' => array(
                                            'type' => 'text',
                                            'label'        => __('Title', FP_TD),
                                            'test_content' => array(
                                                'min' => $this->get_sample_text(1, 1131),
                                                'max' => $this->get_sample_text(8, 521),
                                            ),
                                            'maxlength' => 30,
                                        ),
                                        'link' => array(
                                            'type' => 'link',
                                            'label'        => __('Link', FP_TD),
                                            'test_content' => array(
                                                'min' => $this->get_sample_text(1, 1132),
                                                'max' => $this->get_sample_text(10, 526),
                                            ),
                                        ),
                                        'link_aria_label' => array(
                                            'type'     => 'text',
                                            'label'    => __('Aria Label', FP_TD),
                                        ),
                                        'target' => array(
                                            'type'        => 'select',
                                            'label'       => __('Target Window', FP_TD),
                                            'default'     => 'current',
                                            'options'     => array(
                                                'current'     => 'Current Window',
                                                'new'         => 'New Window',
                                            ),
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
            'fp-z_pattern-tab-1' => array(
                'title'         => __('Settings', FP_TD),
                'sections'      => array(
                    'general' => array(
                        'title'     => __('General', FP_TD),
                        'fields' => array(
                            'alignment' => array(
                                'type'        => 'select',
                                'label'       => __('Alignment', FP_TD),
                                'default'     => 'right',
                                'options'     => array(
                                    '-left-to-right'    => __('Left', FP_TD),
                                    '-right-to-left'       => __('Right', FP_TD),
                                    '-background-left'    => __('Background + Text Left', 'fl_builder'),
                                    '-background-right'    => __('Background + Text Right', 'fl_builder')
                                ),
                            ),
                        )
                    ),
                    'data_attributes' => array(
                        'title'     => __('Content', FP_TD),
                        'fields'    => array(
                            'heading' => array(
                                'type'     => 'text',
                                'label'    => __('Heading', FP_TD),
                                'default'  => __('Sample Heading', FP_TD),
                                'connections'   => array('string'),
                            ),
                            'heading_type' => array(
                                'type'     => 'select',
                                'label'    => __('Choose the Heading Type', FP_TD),
                                'default'  => __('h3', FP_TD),
                                'options'   => array(
                                    'h1' => 'Heading 1',
                                    'h2' => 'Heading 2',
                                    'h3' => 'Heading 3',
                                    'h4' => 'Heading 4',
                                    'h5' => 'Heading 5',
                                    'h6' => 'Heading 6',
                                ),
                            ),
                            'heading_typography' => array(
                                'type'       => 'typography',
                                'label'      => __('Headting Typography', FP_TD),
                                'responsive' => true,
                                'preview'    => array(
                                    'type'        => 'css',
                                    'selector'  => '.heading',
                                ),
                                'default'       => array(
                                    'family'        => 'Helvetica',
                                    'weight'        => 300
                                )
                            ),
                            'content' => array(
                                'rows'          => 2,
                                'type'     => 'editor',
                                'label'    => __('Content', FP_TD),
                                'maxlength' => 250,
                            ),
                        ),
                    ),
                    'cta_attributes' => array(
                        'title'     => __('CTA', FP_TD),
                        'fields'    => array(
                            'link_type' => array(
                                'type'        => 'select',
                                'label'       => __('Link Type  ( optional )', FP_TD),
                                'default'     => '-link-style-button',
                                'options'     => array(
                                    '-link-style-text'      => __('Text', FP_TD),
                                    '-link-style-button'        => __('Button', FP_TD),
                                ),
                                'toggle' => array(
                                    '-link-style-button' => array(
                                        'fields'   => array('link_title', 'link_url', 'target', 'button_color', 'link_aria_button'),
                                    ),
                                    '-link-style-text' => array(
                                        'fields'   => array('text_links'),
                                    )
                                ),
                            ),
                            'link_title' => array(
                                'type'     => 'text',
                                'label'    => __('Title', FP_TD),
                                'default'  => __('', FP_TD),
                                'test' => true,
                                'test_content' => array(
                                    'min' => $this->get_sample_text(5, 214),
                                    'max' => $this->get_sample_text(24, 158),
                                ),
                                'maxlength' => 30
                            ),
                            'link_aria_button' => array(
                                'type'     => 'text',
                                'label'    => __('Aria Label', FP_TD),
                            ),
                            'link_url' => array(
                                'type'     => 'link',
                                'label'    => __('Link', FP_TD),
                                'default'         => '',
                            ),
                            'button_color' => array(
                                'type'            => 'fp-colour-picker',
                                'label'            => __('Choose the button color', FP_TD),
                                'element'        => 'button',
                            ),
                            'target' => array(
                                'type'        => 'select',
                                'label'       => __('Target Window', FP_TD),
                                'default'     => 'current',
                                'options'     => array(
                                    'current'     => 'Current Window',
                                    'new'         => 'New Window',
                                ),
                            ),
                            'text_links' => array(
                                'type'     => 'form',
                                'form'        => 'links',
                                'label'    => __('Links', FP_TD),
                                'multiple'    => true
                            ),

                        ),
                    ),
                    'style_attributes' => array(
                        'title'     => __('Style', FP_TD),
                        'fields'    => array(
                            'theme' => array(
                                'label'    => __('Theme', FP_TD),
                                'type'            => 'fp-colour-picker',
                                'element'        => array('background'),
                            ),
                            'heading_color' => array(
                                'label'    => __('Heading Color', FP_TD),
                                'type'            => 'color',
                            ),
                            'text_image' => array(
                                'type'    => 'photo',
                                'label'   => __('Textbox Header Image', FP_TD),
                                'description'   => __('Optional: Add an image/icon above the text header. Max height 84px', FP_TD)
                            ),
                            'text_padding' => array(
                                'type'         => 'unit',
                                'label'        => 'Text Padding',
                                'default'      => 100,
                                'units'        => array('px'),
                                'default_unit' => 'px',              // Optional
                                'preview'      => array(
                                    'type'          => 'css',
                                    'selector'      => '.component_z_pattern .safety-container .text-container',
                                    'property'      => 'padding',
                                ),
                            ),
                            'background_image' => array(
                                'type'    => 'photo',
                                'label'   => __('Background Image', FP_TD),
                                'connections' => array('photo'),
                                'responsive' => true,
                                'show' => array(
                                    'fields' => array('background_image_size', 'background_image_size', 'image_edge')
                                )
                            ),
                            'background_image_size' => array(
                                'type'        => 'select',
                                'label'       => __('Background Image Size (Desktop)', FP_TD),
                                'default'     => 'left',
                                'options'     => get_registered_thumbnails(),
                                'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['background_image_size'])) ? FP_MODULE_DEFAULTS[$this->component]['background_image_size'] : 'thumbnail', // opg default
                            ),
                            'background_image_size_medium' => array(
                                'type'        => 'select',
                                'label'       => __('Background Image Size (Medium)', FP_TD),
                                'default'     => 'left',
                                'options'     => get_registered_thumbnails(),
                                'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['background_image_size_medium'])) ? FP_MODULE_DEFAULTS[$this->component]['background_image_size_medium'] : 'thumbnail', // opg default
                            ),
                            'background_image_size_responsive' => array(
                                'type'        => 'select',
                                'label'       => __('Background Image Size (Responsive)', FP_TD),
                                'default'     => 'left',
                                'options'     => get_registered_thumbnails(),
                                'default' => (defined('FP_MODULE_DEFAULTS') && !empty(FP_MODULE_DEFAULTS[$this->component]['background_image_size_responsive'])) ? FP_MODULE_DEFAULTS[$this->component]['background_image_size_responsive'] : 'thumbnail', // opg default
                            ),
                            'background_width' => array(
                                'type'         => 'unit',
                                'label'        => 'Text / Background Split',
                                'default' => 50,
                                'units'           => array('%'),
                                'default_unit' => '%', // Optional
                                'preview'       => array(
                                    'type'          => 'css',
                                    'selector'      => '.component_z_pattern .safety-container .text-container',
                                    'property'      => 'width',
                                ),
                            ),
                            'image_edge' => array(
                                'type'        => 'select',
                                'label'       => __('Image Edging', FP_TD),
                                'default'     => '-square-edge',
                                'options'     => array(
                                    '-square-edge'  => __('Square', FP_TD),
                                    '-angle-edge'   => __('Diagonal', FP_TD),
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
        $classes[] = $atts['alignment'];
        $classes[] = $atts['link_type'];
        $classes[] = $atts['image_edge'];

        if (isset($atts['theme']) && !empty($atts['theme'])) {
            $classes[] = $atts['theme'];
        }
        if (isset($atts['text_image']) && !empty($atts['text_image'])) {
            $textImage = wp_get_attachment_image_url($atts['text_image'], array(168, 84));
            if (false !== $textImage) {
                $atts['text_image_url'] = $textImage;
                $classes[] = 'text-bg-image';
            }
        }

        $atts['classes'] = implode(' ', $classes);

        return $atts;
    }
}
