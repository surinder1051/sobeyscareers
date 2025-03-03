<?php

namespace fp\components;

use fp;
use stdClass;

if ( class_exists( 'fp\Component' ) ) {
	class bs_card_simple extends fp\Component {


		public $schema_version          = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version                 = '1.1.5';
		public $component               = 'bs_card_simple'; // Component slug should be same as this file base name
		public $component_name          = 'Simple Card Row'; // Shown in BB sidebar.
		public $component_description   = 'Cards with Background Image, Heading and Button';
		public $component_category      = 'FP Cards';
		public $component_load_category = 'bootstrap';
		public $enable_css              = true;
		public $enable_js               = false;
		public $deps_css                = array( 'brand' ); // WordPress Registered CSS Dependencies
		public $deps_js                 = array(); // WordPress Registered JS Dependencies
		// public $deps_css_remote              = array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote               = array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir = __DIR__;
		public $fields   = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig = array(); // Placeholder for BB Module Registration
		public $variants = array( '-compact' ); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content    = true; // Exclude content of this module from being saved to post_content field
		// public $load_in_header               = true;
		public $dynamic_data_feed_parameters = array( // Generates $atts[posts] object with dynamically populated data
			// 'pagination_api'         => true, // enable ajax pagination
			// 'posts_per_page_default' => '3',
			// 'posts_per_page_options' => array(
			// '1' => 1,
			// '2' => 2,
			// '3' => 3,
			// '4' => 4,
			// '5' => 5,
			// '6' => 6,
			// '7' => 7,
			// '8' => 8,
			// '9' => 9,
			// ),
			// 'post_types' => array('post', 'page'),
			// 'taxonomies' => array(
			// array('category' => array()),
			// array('content_tag' => array('none-option' => true)),
			// )
		);

		public function init_fields() {
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

			Dynamic Colour Selector Fields
			'type'    => 'fp-colour-picker',
			'element' => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',

			background class in template class="-bg-[colour selected]"
			button/header class="[colour selected]"

			Additional choices for button: include a select field with options: [outline | solid( default )]
			Outline: class="outline [color selected]"

			Custom SVG Icon Picker
			'type' => 'fp-icon-picker',

			Install svg icons through BB font tool in a subdir called /images/

			*/

			$this->forms = array(
				array(
					'simple_card_item',
					array(
						'title' => __( 'Callout Attributes', FP_TD ),
						'tabs'  => array(
							'general' => array(
								'title'    => __( 'General', FP_TD ),
								'sections' => array(
									'general' => array(
										'fields' => array(
											'callout_url' => array(
												'type'  => 'link',
												'show_target' => true,
												'label' => __( 'Link To', FP_TD ),
											),
											'callout_title' => array(
												'type'  => 'text',
												'label' => __( 'Optional: Override Post Title', FP_TD ),
											),
											'callout_heading_color' => array(
												'type'  => 'color',
												'label' => __( 'Heading Colour', FP_TD ),
											),
											'callout_description' => array(
												'type'  => 'text',
												'label' => __( 'Optional: Description', FP_TD ),
											),
											'callout_bg_color' => array(
												'type'  => 'color',
												'label' => __( 'Optional: Text background colour', FP_TD ),
											),
											'callout_bg_image' => array(
												'type'  => 'photo',
												'label' => __( 'Optional: Override Featured Background Image', FP_TD ),
											),
											'callout_button_theme' => array(
												'type'    => 'fp-colour-picker',
												'label'   => __( 'Select Button Theme', FP_TD ),
												'element' => 'button',
											),
											'callout_link_text' => array(
												'type'    => 'text',
												'label'   => __( 'Button Label', FP_TD ),
												'default' => __( 'Read More', FP_TD ),
											),
											'callout_aria_label' => array(
												'type'    => 'text',
												'label'   => __( 'Aria Label', FP_TD ),
												'default' => __( 'Read More', FP_TD ),
											),
											'callout_image_overlay' => array(
												'type'    => 'select',
												'label'   => __( 'Add gradient overlay to image', FP_TD ),
												'options' => array(
													'1' => __( 'Yes', FP_TD ),
													'0' => __( 'No', FP_TD ),
												),
												'default' => '0',
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
				'fp-bs_card_simple-tab-1' => array(
					'title'    => __( 'Settings', FP_TD ),
					'sections' => array(
						'simple_card_basic' => array(
							'title'  => __( 'Basic Settings', FP_TD ),
							'fields' => array(
								'scard_heading_type'       => array(
									'type'    => 'select',
									'label'   => __( 'Heading Type', FP_TD ),
									'options' => array(
										'h1' => __( 'Heading 1', FP_TD ),
										'h2' => __( 'Heading 2', FP_TD ),
										'h3' => __( 'Heading 3', FP_TD ),
										'h4' => __( 'Heading 4', FP_TD ),
										'h4' => __( 'Heading 5', FP_TD ),
										'h6' => __( 'Heading 6', FP_TD ),
									),
									'default' => 'h2',
								),
								'scard_heading_typography' => array(
									'type'       => 'typography',
									'label'      => __( 'Heading Typography', FP_TD ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_bs_card_simple .card-grid-row .card .card-img-overlay .card-content .card-title',
									),
								),
								'scard_text_typography'    => array(
									'type'       => 'typography',
									'label'      => __( 'Optional: Description Typography', FP_TD ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_bs_card_simple .card-grid-row .card .card-img-overlay .card-content .card-text',
									),
								),
								'overlay_padding'          => array(
									'type'       => 'dimension',
									'label'      => __( 'Card overlay padding', FP_TD ),
									'responsive' => true,
									'slider'     => true,
									'units'      => array( 'px', 'rem' ),
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_bs_card_simple .card-grid-row .card .card-img-overlay',
										'property' => 'padding',
									),
								),
								'variant'                  => array(
									'type'    => 'select',
									'label'   => __( 'Breakpoint styling variant', FP_TD ),
									'options' => array(
										'-compact' => __( 'Compact', FP_TD ),
										'default'  => __( 'Stacked', FP_TD ),
									),
									'default' => 'default',
								),
							),
						),
						'simple_card_items' => array(
							'title'  => __( 'Add Cards (Max 3)', FP_TD ),
							'fields' => array(
								'sc_callout_options' => array(
									'type'     => 'form',
									'label'    => __( 'Add Card', FP_TD ),
									'form'     => 'simple_card_item',
									'multiple' => true,
									'max'      => 3,
									'preview'  => 'callout_title',
								),
							),
						),
					),
				),
			);
		}

		public function pre_process_data( $atts, $module ) {
			$atts['callouts'] = array();
			$gridClass        = array(
				'1' => '-one',
				'2' => '-two',
				'3' => '-three',
			);
			$post_types       = array( 'page', 'post', 'recipe', 'article', 'store', 'product', 'faq', 'cooking-tip', 'easy-meal', 'how-to', 'video', 'collection' );

			$atts['card_grid_class'] = '';

			if ( ! empty( $atts['sc_callout_options'] ) ) {
				$callout_items = $atts['sc_callout_options'];
				if ( ! empty( $callout_items[0]->callout_url ) ) {
					$atts['card_grid_class'] = $gridClass[ count( $callout_items ) ];

					foreach ( $callout_items as $index => $ci ) {
						if ( ! empty( $ci->callout_url ) ) {
							$cardClasses = array();

							$img     = '';
							$imgSize = 'tile_560x400';

							$img = wp_get_attachment_image_url( $ci->callout_bg_image, $imgSize );

							$calloutObject = get_page_by_path( untrailingslashit( str_replace( site_url() . '/', '', $ci->callout_url ) ), OBJECT, $post_types );

							$cardClasses[] = 'card-item-' . $index;

							$cardData = array(
								'title'       => $ci->callout_title,
								'link_target' => $ci->callout_url_target,
								'format'      => $ci,
								'classes'     => implode( ' ', $cardClasses ),
								'img'         => $img,
								'overlay'     => $ci->callout_image_overlay,
							);

							if ( isset( $calloutObject->ID ) ) {
								if ( empty( $ci->callout_bg_image ) && false !== ( $fimg = get_the_post_thumbnail_url( $calloutObject->ID, $imgSize ) ) ) {
									$cardData['img'] = $fimg;
								}

								$post_type_obj = get_post_type_object( get_post_type( $calloutObject->ID ) );

								$cardData['title']     = ( empty( $ci->callout_title ) ) ? $calloutObject->post_title : $ci->callout_title;
								$cardData['post_type'] = $post_type_obj->label;
							}
							$atts['callouts'][] = $cardData;
						}
					}
				}
			}

			if ( ! empty( $atts['variant'] ) && ( '-compact' == $atts['variant'] ) ) {
				$atts['classes'] .= ' -compact';
			}

			return $atts;
		}
	}
	new bs_card_simple();
}
