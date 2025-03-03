<?php

namespace fp\components;

use fp;
use WP_Query;

if (class_exists('fp\Component')) {
	class related extends fp\Component
	{

		public $schema_version                    = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version                           = '1.1.0';
		public $component                         = 'related'; // Component slug should be same as this file base name
		public $component_name                    = 'Related Content'; // Shown in BB sidebar.
		public $component_description             = 'Displays related content module using a custom shortcode.';
		public $component_category                = 'FP Global';
		public $enable_css                        = true;
		public $enable_js                         = true;
		public $deps_css                          = array(); // WordPress Registered CSS Dependencies
		public $deps_js                           = array('jquery', 'slick'); // WordPress Registered JS Dependencies
		// public $deps_css_remote       			= array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote 		  			= array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir                          = __DIR__;
		public $fields                            = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig                          = array(); // Placeholder for BB Module Registration
		public $variants                          = array(); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content 		= true; // Exclude content of this module from being saved to post_content field
		public $id                                = null;
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

			global $fp_post_types_keys;

			$this->forms = array(
				array(
					'my_form_field',
					array(
						'title' => __('My Form Field', FP_TD),
						'tabs'  => array(
							'general'      => array(
								'title'         => __('General', FP_TD),
								'sections'      => array(
									'general'       => array(
										'title'         => '',
										'fields'        => array(
											'label'         => array(
												'type'          => 'text',
												'label'         => __('Label', FP_TD),
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
				'fp-related-tab-1' => array(
					'title'         => __('Settings', FP_TD),
					'sections'      => array(
						'content' => array(
							'title'     => __('Content', FP_TD),
							'fields'    => array(
								'title' => array(
									'type'    => 'text',
									'label'   => __('Title', FP_TD),
									'default' => 'Default !!!!',
									'connections' => ['string']
								),
								'title_tag' => array(
									'type'     => 'select',
									'label'    => __('Choose the title tag', FP_TD),
									'default'  => __('h2', FP_TD),
									'options'   => array(
										'h1' => 'Heading 1',
										'h2' => 'Heading 2',
										'h3' => 'Heading 3',
										'h4' => 'Heading 4',
										'h5' => 'Heading 5',
										'h6' => 'Heading 6',
									),
								),
								'shortcode' => array(
									'type'    => 'text',
									'label'   => __('Shortcode', FP_TD),
									'default' => 'recipe_card',
									'description' => 'Shortcode can include additional parameters such as "recipe_card align=\'left\'"'
								),
								'no_container' => array(
									'default' => 'true',
									'type'     => 'select',
									'label'    => __('Disable sub module container', FP_TD),
									'options'   => array(
										'true' => 'True',
										'false' => 'False',
									),
								)
							),
						),
						'styling' => array(
							'title'     => __('Styling', FP_TD),
							'fields'    => array(
								'title_align' => array(
									'type'    => 'align',
									'label'   => __('Title Align', FP_TD),
									'default' => 'center',
								),
								'stacked' => array(
									'type'        => 'select',
									'label'       => __('Stacked', FP_TD),
									'description' => __('This stack the cards vertically', FP_TD),
									'default'     => 'false',
									'options'     => array(
										'true'      => __('True', FP_TD),
										'false'      => __('False', FP_TD),
									),
								),

								'enable_mobile_slider' => array(
									'type'        => 'select',
									'label'       => __('Enabled Mobile Slider', FP_TD),
									'description' => __('On mobile view a slider will be used to display one item at a time', FP_TD),
									'default'     => 'false',
									'options'     => array(
										'true'      => __('True', FP_TD),
										'false'      => __('False', FP_TD),
									),
								),
							)
						),
						'data' => array(
							'title'     => __('Data', FP_TD),
							'fields'    => array(
								'data_source' => array(
									'type'        => 'select',
									'label'       => __('Data Source', FP_TD),
									'description' => __('Manually or dynamically assigned related content.', FP_TD),
									'default'     => 'dynamic',
									'options'     => array(
										'dynamic'      => __('Dynamic', FP_TD),
										'manual'      => __('Manual', FP_TD),
									),
									'toggle' => array(
										'manual' => array(
											'fields' => array('manual_data_source')
										),
										'dynamic' => array(
											'fields' => array('number_of_dynamic_results')
										)
									),
								),
								'number_of_dynamic_results' => array(
									'type'        => 'select',
									'label'       => __('Number of Dynamic Results', FP_TD),
									'default'     => '4',
									'options'     => array(
										'1'      => 1,
										'2'      => 2,
										'3'      => 3,
										'4'      => 4,
										'5' => 5,
										'6' => 6,
									),
								),
								'manual_data_source' => array(
									'type'        => 'select',
									'label'       => __('Manual Data Source', FP_TD),
									'description' => __('Manually or dynamically assigned related content.', FP_TD),
									'default'     => 'related_articles',
									'options'     => array(
										'related_articles'      => __('Related Articles', FP_TD),
										'related_recipes'      => __('Related Recipes', FP_TD),
										'related_how_to'      => __('Related How To', FP_TD),
										'related_products'      => __('Related Products', FP_TD),
										'inline' => __('Inline Linked', FP_TD),
										// 'inline_static' => __('Inline Static', FP_TD ),
									),
									'toggle' => array(
										'inline' => array(
											'fields' => array('inline_data_source')
										),
										'inline_static' => array(
											'fields' => array('inline_static')
										)
									)
								),
								'inline_data_source' => array(
									'type'          => 'suggest',
									'label'         => __('Related Content', FP_TD),
									'action'        => 'fl_as_posts', // Search posts.
									'data'          => $fp_post_types_keys, // Slug of the post type to search.
									'limit'         => 5, // Limits the number of selections that can be made.
								),
								// 'inline_static' => array(
								// 	'type'          => 'form',
								// 	'label'         => __('Item', FP_TD ),
								// 	'form'          => 'my_form_field', // ID of a registered form.
								// )
							),
						),
					)
				)
			);
		}

		/**
		 * Helper function to get the local postID from a centralized post ID value.
		 *
		 * @param integer $centralized_post_id
		 * @return integer
		 */
		public function get_local_postID( $centralized_post_id = 0 ) {
			global $wpdb;
			$mapped_id_to_centralized_content = $wpdb->get_var("SELECT post_id from $wpdb->postmeta WHERE meta_key ='_fppi_imported_postID' AND meta_value = '$centralized_post_id' ");
			return $mapped_id_to_centralized_content;
		}

		// Sample as to how to pre-process data before it gets sent to the template

		public function pre_process_data($atts, $module)
		{
			global $post;

			if (!function_exists('fp\components\ci_get_related_posts')) {
				function ci_get_related_posts($post_id, $related_count, $args = array())
				{
					$lang = ( defined('ICL_LANGUAGE_CODE')) ? ICL_LANGUAGE_CODE : 'en';
					$args = wp_parse_args((array) $args, array(
						'orderby' => 'rand',
						'return'  => 'query', // Valid values are: 'query' (WP_Query object), 'array' (the arguments array)
					));

					$related_args = array(
						'post_type'      => get_post_type($post_id),
						'posts_per_page' => $related_count,
						'post_status'    => 'publish',
						'post__not_in'   => array($post_id),
						'suppress_filters' => true,
						'orderby'        => $args['orderby'],
						'tax_query'      => array(),
						'lang' => $lang
					);

					$post       = get_post($post_id,array('lang' => $lang));
					$taxonomies = get_object_taxonomies($post, 'names');

					foreach ($taxonomies as $taxonomy) {
						$terms = get_the_terms($post_id, $taxonomy);
						if (empty($terms) || (!empty($terms) && $terms[0]->taxonomy === 'post_translations')) {
							continue;
						}
						$term_list                   = wp_list_pluck($terms, 'slug');
						$related_args['tax_query'][] = array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $term_list
						);
					}

					if (count($related_args['tax_query']) > 1) {
						$related_args['tax_query']['relation'] = 'OR';
						if (defined('ICL_LANGUAGE_CODE')) {
							// We have to force AND for language so all related content is the same lang
							$related_args['tax_query']['relation'] = 'AND';
						}
					}

					if ($args['return'] == 'query') {
						return new WP_Query($related_args);
					} else {
						return $related_args;
					}
				}
			}

			$atts['centralized_content'] = false;

			if (!empty($atts['data_source']) && $atts['data_source'] == 'dynamic') {
				$atts['posts'] =  ci_get_related_posts($post->ID, $atts['number_of_dynamic_results'])->posts;
			} elseif (!empty($atts['manual_data_source']) && $atts['manual_data_source'] !== 'inline') {
				$atts['posts'] = get_field($atts['manual_data_source'], $post->ID);

				if ($atts['manual_data_source'] == 'related_recipes' || $atts['manual_data_source'] == 'related_products') {
					$atts['centralized_content'] = true;
				}
			} elseif (!empty($atts['inline_data_source'])) {
				$the_query = new WP_Query(array(
					'post_type' => 'any',
					'post__in'      => explode(',', $atts['inline_data_source'])
				));
				$atts['posts'] = $the_query->posts;
			}

			if (!empty($atts['enable_mobile_slider']) && $atts['enable_mobile_slider'] == "true") {
				if ('how-tos' != get_post_type($post->ID)) {
					$atts['classes'] .= ' mobile_carousel';
				}
			}

			return $atts;
		}
	}

	new related;
}
