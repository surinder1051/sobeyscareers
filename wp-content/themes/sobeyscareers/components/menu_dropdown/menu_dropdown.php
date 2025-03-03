<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
    class menu_dropdown extends fp\Component
    {

        public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
        public $version                           = '1.0.1';
        public $component                         = 'menu_dropdown'; // Component slug should be same as this file base name
        public $component_name                    = 'Menu Dropdown'; // Shown in BB sidebar.
        public $component_description             = 'Display a Menu with Tool-tip style menu items';
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
        public $load_in_header                      = true;
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
                    'menu_item',
                    array(
                        'title' => __('Menu Item', FP_TD),
                        'tabs'  => array(
                            'general'      => array(
                                'title'         => __('General', FP_TD),
                                'sections'      => array(
                                    'general'       => array(
                                        'title'         => '',
                                        'fields'        => array(
                                            'menu_text' => array(
                                                'type'          => 'text',
                                                'label'         => __('Menu Item Text', FP_TD),
                                                'default'        =>  __('Where to buy?', FP_TD),
                                            ),
                                            'title' => array(
                                                'type'    => 'text',
                                                'label'   => __('Title', FP_TD),
                                                'default' => __('You can purchase Compliments products across the country at these fine stores.', FP_TD), 
                                            ),
                                            'dropdown_items' => array(
                                                'type'          => 'form',
                                                'label'         => __('Dropdown Item', FP_TD),
                                                'form'          => 'dropdown_item', // ID of a registered form.
                                                'preview_text'  => 'link', // ID of a field to use for the preview text.
                                                'wpautop'   => false,
                                                'multiple'    => true,
                                                'preview_text'  => 'link',
                                                'default'         => array(
                                                    0 => array(
                                                        'link'             => 'https://www.sobeys.com',
                                                        'store_image'     => '',
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'dropdown_item',
                    array(
                        'title' => __('Menu Item', FP_TD),
                        'tabs'  => array(
                            'general'      => array(
                                'title'         => __('General', FP_TD),
                                'sections'      => array(
                                    'general'       => array(
                                        'title'         => '',
                                        'fields'        => array(
											'store_image' => array(
												'type'          => 'photo',
												'label'         => __('Store Image', FP_TD),
												'show_remove'   => true,
											),
											'link' => array(
												'type'          => 'link',
												'label'         => __('Link URL', FP_TD),
												'show_target'    => true,
												'show_nofollow'    => true,
											),
											'link_title' => array(
												'type'          => 'text',
												'label'         => __('Link URL/Image Alt', FP_TD),
												'description'    => __('Set a human readable aria-label/img alt eg: Go to the Voila Site', FP_TD)
											),
											'store_image_padding' => array(
												'type'          => 'dimension',
												'label'         => __('Set image padding', FP_TD),
												'description'    => 'px',
												'responsive'    => true,
											),
											'store_image_width' => array(
												'type'          => 'unit',
												'label'         => __('Set width', FP_TD),
												'description'    => 'px',
												'responsive'    => true,
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
                                'menu_items' => array(
                                    'type'          => 'form',
                                    'label'         => __('Menu Item', FP_TD),
                                    'form'          => 'menu_item', // ID of a registered form.
                                    'preview_text'  => 'menu_text', // ID of a field to use for the preview text.
                                    'wpautop'   => false,
                                    'multiple'    => true,
                                ),
                                'dropdown_icon' => array(
                                    'type'          => 'icon',
                                    'label'         => __('Dropdown Icon', 'fl-builder'),
                                    'default'        => 'icon-dropdown',
                                    'show_remove'   => true
                                ),
                                'menu_name' => array(
                                    'type'  => 'text',
                                    'label' => __('Menu Name (accessibility)', FP_TD),
                                    'default'   => __('Loyalty Programs', FP_TD),
								),
								'dd_menu_padding' => array(
									'type'          => 'dimension',
									'label'         => __('Set the dropdown menu padding', FP_TD),
									'description'    => 'px',
									'responsive'    => true,
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
    new menu_dropdown;
}
