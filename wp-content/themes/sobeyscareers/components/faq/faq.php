<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
    class faq extends fp\Component
    {

        public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $version                           = '1.0.1';
        public $component                         = 'faq'; // Component slug should be same as this file base name
        public $component_name                    = 'FAQs'; // Shown in BB sidebar.
        public $component_description             = 'Display an Accordiont-style list of FAQ';
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
                    'category_item',
                    array(
                        'title' => __('Category Item', FP_TD),
                        'tabs'  => array(
                            'general'      => array(
                                'title'         => __('General', FP_TD),
                                'sections'      => array(
                                    'general'       => array(
                                        'title'         => '',
                                        'fields'        => array(
                                            'term' => array(
                                                'type'          => 'suggest',
                                                'label'         => __('Term', 'fl-builder'),
                                                'action'        => 'fl_as_terms', // Search posts.
                                                'data'          => 'faq-category', // Slug of the post type to search.
                                                'limit'         => 1, // Limits the number of selections that can be made.
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
                        'settings' => array(
                            'title'     => __('Settings', FP_TD),
                            'fields'    => array(
                                'category_items' => array(
                                    'type'          => 'form',
                                    'label'         => __('Category Items', FP_TD),
                                    'form'          => 'category_item', // ID of a registered form.
                                    'wpautop'       => false,
                                    'multiple'        => true,
                                    'preview_text'  => 'term',
                                ),
                                'active_icon' => array(
                                    'type'          => 'icon',
                                    'label'         => __('Active Icon', 'fl-builder'),
                                    'default'        => 'icon-arrow-right',
                                    'show_remove'   => true
                                ),
                                'active_color' => array(
                                    'type'          => 'color',
                                    'label'         => __('Active Color', FP_TD),
                                    'default'       => 'de3318',
                                    'show_reset'    => true,
                                ),
                                'open_icon' => array(
                                    'type'          => 'icon',
                                    'label'         => __('Open Icon', 'fl-builder'),
                                    'default'        => 'icon-expand',
                                    'show_remove'   => true
                                ),
                                'closed_icon' => array(
                                    'type'          => 'icon',
                                    'label'         => __('Closed Icon', 'fl-builder'),
                                    'default'        => 'icon-collapse',
                                    'show_remove'   => true
                                ),
                                'icon_color' => array(
                                    'type'          => 'color',
                                    'label'         => __('Icon Color', FP_TD),
                                    'default'       => 'de3318',
                                    'show_reset'    => true,
                                ),
                            ),
                        ),
                    ),
                ),
            );
        }

        protected function get_term_options()
        {
            $taxonomies = get_taxonomies(['public' => true], 'objects');
            $options = array();
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $tax => $tax_object) {
                    $options[$tax] = $tax_object->label;
                }
            }
            return $options;
        }

        // Sample as to how to pre-process data before it gets sent to the template

        public function pre_process_data($atts, $module)
        {
            extract($atts);
            $post_type = 'faq';
            $taxonomy = 'faq-category';
            $faq_listings = [];
            foreach ($category_items as $category) {
                $post_args = [
                    'post_type' => $post_type,
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => $taxonomy,
                            'field'    => 'term_id',
                            'terms'    => $category->term,
                        ),
                    ),
                ];
                $posts = get_posts($post_args);
                if (!empty($posts)) {
                    $term = get_term($category->term, $taxonomy);
                    $faq_listings[$term->name] = $posts;
                }
            }

            $atts['faqs'] = $faq_listings;

            return $atts;
        }
    }
    new faq;
}
