<?php

namespace fp\components;

use fp;

class z_pattern_recipe extends fp\Component
{

	public $version               = '1.0.0';
    public $component             = 'z_pattern_recipe'; // Component slug should be same as this file base name
    public $component_name        = 'Z-Pattern Featured Recipe'; // Shown in BB sidebar.
    public $component_description = 'Z-Pattern for Featured Recipe';
    public $component_category    = 'FP Z Pattern';
    public $enable_css            = true;
    public $enable_js             = false;
    public $deps_css              = array('brand'); // WordPress Registered CSS Dependencies
    public $deps_js               = array(); // WordPress Registered JS Dependencies
    public $fields                = array(); // Placeholder for fields used in BB Module & Shortcode
    public $bbconfig              = array(); // Placeholder for BB Module Registration
    public $base_dir              = __DIR__;
    public $variants              = array('-left-to-right', '-right-to-left', '-background-left', '-background-right', '-white-bg', '-grey-bg', '-link-style-button', '-link-style-text', '-square-edge', '-angle-edge'); // Component CSS Variants as per -> http://rscss.io/variants.html
    public $schema_version        = 3; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
    public $id                    = null;

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
            'fp-z_pattern-tab-1' => array(
                'title'         => __('Settings', FP_TD),
                'sections'      => array(
                    'data_attributes' => array(
                        'title'     => __('Data', FP_TD),
                        'fields'    => array(
                            'featured_recipe' => array(
                                'type'    => 'suggest',
                                'label'    => __('Select Recipe', FP_TD),
                                'action'    => 'fl_as_posts',
                                'data'    => 'recipe',
                                'limit'    => 1,
                                'max'    => 1
                            ),
                            'content_theme' => array(
                                'type'    => 'fp-colour-picker',
                                'label'    => __('Select the content theme'),
                                'element'    => 'background'
                            ),
                            'heading_type' => array(
                                'type'    => 'select',
                                'label'   => __('Heading Type', FP_TD),
                                'options'    => array(
                                    'h1' => 'Heading 1',
                                    'h2' => 'Heading 2',
                                    'h3' => 'Heading 3',
                                ),
                                'default'        => 'h2',
                            ),
                            'heading_typography' => array(
                                'type'            => 'typography',
                                'label'           => __('Heding Style', FP_TD),
                                'description'    => 'px',
                                'responsive'    => array(
                                    'placeholder' => array(
                                        'default'    =>  42,
                                        'medium'     =>  35,
                                        'responsive' =>  32,
                                    ),
                                )
                            ),
                        ),
                    ),
                    'style_attributes' => array(
                        'title'     => __('Style', FP_TD),
                        'fields'    => array(
                            'background_image' => array(
                                'type'    => 'photo',
                                'label'   => __('Optional: Override Featured Image', FP_TD),
                            ),
                            'alignment' => array(
                                'type'        => 'select',
                                'label'       => __('Image Position', FP_TD),
                                'default'     => 'left',
                                'options'     => array(
                                    '-left-to-right'    => __('Left', FP_TD),
                                    '-right-to-left'       => __('Right', FP_TD),
                                    '-background-left'    => __('Background + Text Left', FP_TD),
                                    '-background-right'    => __('Background + Text Right', FP_TD)
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
                    'cta_attributes' => array(
                        'title'     => __('CTA', FP_TD),
                        'fields'    => array(
                            'button_title' => array(
                                'type'     => 'text',
                                'label'    => __('Button Text', FP_TD),
                                'default'  => __('Get the recipe', FP_TD),
                            ),
                            'button_aria' => array(
                                'type'     => 'text',
                                'label'    => __('Links', FP_TD),
                                'default'    => __('Get the recipe', FP_TD)
                            ),
                            'button_colour' => array(
                                'type'            => 'fp-colour-picker',
                                'label'            => __('Choose the button color', FP_TD),
                                'element'        => 'button',
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
        $classes[] = $atts['alignment'];
        $classes[] = $atts['image_edge'];
        $classes[] = $atts['content_theme'];
        $atts['classes'] = implode(' ', $classes);
        $atts['image_src'] =  '';
        $atts['content'] = array();

        if (!empty($atts['background_image'])) {
            $atts['image_src'] = wp_get_attachment_image_url($atts['background_image'], 'large-780');
        };

        if (!empty($atts['featured_recipe'])) {
            $atts['recipe'] = get_post($atts['featured_recipe']);
        } else {
            $recipe = get_posts(array('post_type' => 'recipe', 'number_posts' => 1));
            $atts['recipe'] = $recipe[0];
        }
        if (empty($atts['background_image']) && false !== ($fimg = get_the_post_thumbnail_url($atts['recipe']->ID, 'large-780'))) {
            $atts['image_src'] = $fimg;
        }

        if (isset($atts['recipe']->ID)) {
            $cookingOptions = get_field('cooking', $atts['recipe']->ID);
            $generalOptions = get_field('general', $atts['recipe']->ID);
            $atts['content']['prep_time'] = (isset($cookingOptions['prep_time'])) ? $cookingOptions['prep_time'] : get_field('prep_time', $atts['recipe']->ID);
            $atts['content']['total_time'] = (isset($cookingOptions['total_time'])) ? $cookingOptions['total_time'] : get_field('total_time', $atts['recipe']->ID);
            $atts['content']['yield'] = (isset($generalOptions['yield'])) ? $generalOptions['yield'] : get_field('yield', $atts['recipe']->ID);
        }

        return $atts;
    }
}
