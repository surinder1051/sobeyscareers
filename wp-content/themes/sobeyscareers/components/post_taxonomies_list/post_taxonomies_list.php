<?php

namespace fp\components;

use fp;

if (class_exists('fp\Component')) {
	class post_taxonomies_list extends fp\Component
	{

		public $schema_version               = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version                      = '1.2.0';
		public $component                    = 'post_taxonomies_list'; // Component slug should be same as this file base name
		public $component_name               = 'Post Taxonomies List'; // Shown in BB sidebar.
		public $component_description        = 'Display a list of taxonomy term links';
		public $component_category           = 'FP Global';
		public $enable_css                   = true;
		public $enable_js                    = true;
		public $deps_css                     = array(); // WordPress Registered CSS Dependencies
		public $deps_js                      = array('jquery'); // WordPress Registered JS Dependencies
		// public $deps_css_remote              = array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote               = array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir                     = __DIR__;
		public $fields                       = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig                     = array(); // Placeholder for BB Module Registration
		public $variants                     = array(); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content    = true; // Exclude content of this module from being saved to post_content field
		// public $load_in_header               = true;
		public $dynamic_data_feed_parameters = array( // Generates $atts[posts] object with dynamically populated data
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
			// 'post_types' => array('post', 'page'),
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

			Align           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#align-field
			Border          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#border-field
			button-group    - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#button-group-field
			code            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#code-field
			color           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#color-field
			dimension       - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#dimension-field
			editor          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#editor-field
			font            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#font-field
			form            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#form-field
			gradient        - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#gradient-field
			icon            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#icon-field
			link            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#link-field
			loop            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#loop-settings-fields
			multiple-audios - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-audios-field
			multiple-photos - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-photos-field
			photo           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-field
			photo-sizes     - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
			Post Type       - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
			Select          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#select-field
			Service         - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#service-fields
			shadow          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#shadow-field
			Suggest         - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#suggest-field
			text            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#text-field
			Textarea        - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#textarea-field
			Time            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-field
			Timezone        - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-zone-field
			typography      - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#typography-field
			unit            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#unit-field
			Video           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#video-field

			Repeater Fields
			'multiple' => true,
			Not supported in Editor Fields, Loop Settings Fields, Photo Fields, and Service Fields.

			*/

			$this->fields = array(
				'fp-post_taxonomies_list-tab-1' => array(
					'title'    => __('Settings', FP_TD),
					'sections' => array(
						'title' => array(
							'title'  => __('Title', FP_TD),
							'fields' => array(
								'title' => array(
									'type'    => 'text',
									'label'   => __('Title', FP_TD),
									'default' => 'Categories',
								),
								'title_tag' => array(
									'type'    => 'select',
									'label'   => __('Title Tag', FP_TD),
									'default' => 'h2',
									'options' => array(
										'h2' => __('H2', FP_TD),
										'h3' => __('H3', FP_TD),
										'h4' => __('H4', FP_TD),
										'h5' => __('H5', FP_TD),
										'h6' => __('H6', FP_TD),
									),
								),
								'title_color' => array(
									'type'       => 'color',
									'label'      => __('Title Color', FP_TD),
									'show_reset' => true,
								),
								'title_typography' => array(
									'type'       => 'typography',
									'label'      => __('Title Typography', FP_TD),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_post_taxonomies_list .title',
									),
								),
								'title_margin' => array(
									'type'         => 'dimension',
									'label'        => __('Title Margin', FP_TD),
									'units'        => array('px', '%', 'rem'),
									'default_unit' => 'px',
									'responsive'   => true,
									'slider'       => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_post_taxonomies_list .title',
										'property' => 'margin',
									),
								),
							),
						),
						'term_list' => array(
							'title'  => __('Taxonomy Term List', FP_TD),
							'fields' => array(
								'taxonomies' => array(
									'type'         => 'fp-taxonomies-select-dropdown',
									'label'        => __('Choose the taxonomy to display terms from.', FP_TD),
									'multi-select' => true,
								),
								'show_all_terms' => array(
									'type'    => 'select',
									'label'   => __('Show all terms or only assigned to current post', FP_TD),
									'default' => 'all',
									'options' => array(
										'all'      => __('All', FP_TD),
										'assigned' => __('Assigned', FP_TD),
									),
								),
								'show_hierarchy' => array(
									'type'    => 'select',
									'label'   => __('Show all terms or only top-level', FP_TD),
									'default' => 'all',
									'options' => array(
										'all' => __('All', FP_TD),
										'top' => __('Top-level', FP_TD),
									),
								),
								'term_color' => array(
									'type'       => 'color',
									'label'      => __('Term Link Color', FP_TD),
									'show_reset' => true,
								),
								'term_typography' => array(
									'type'       => 'typography',
									'label'      => __('Term Link Typography', FP_TD),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_post_taxonomies_list ul li a',
									),
								),
								'term_padding' => array(
									'type'         => 'dimension',
									'label'        => __('Term Link Padding', FP_TD),
									'units'        => array('px', '%', 'rem'),
									'default_unit' => 'px',
									'responsive'   => true,
									'slider'       => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_post_taxonomies_list ul li a',
										'property' => 'padding',
									),
								),
								'term_border' => array(
									'type'       => 'border',
									'label'      => __('Term Link Border', FP_TD),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_post_taxonomies_list ul li a',
									),
								),
							),
						),
					),
				),

			);
		}

		public function pre_process_data($atts, $module)
		{
			global $post;

			if (!empty($atts['taxonomies']) && count($atts['taxonomies']) > 0) {
				$args = array(
					'hide_empty' => false,
					'fields' => 'all',
					'count' => true,
				);

				if ($atts['show_hierarchy'] == 'top') {
					$args['parent'] = 0;
				}

				if ($atts['show_all_terms'] == 'all') {
					$args['taxonomy'] = $atts['taxonomies'];

					$atts['terms'] = get_terms($args);
				} else {
					$atts['terms'] = wp_get_post_terms($post->ID, $atts['taxonomies'], $args);
				}
			}

			return $atts;
		}
	}
}
