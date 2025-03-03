<?php

namespace fp\components;

use fp;

if ( class_exists( 'fp\Component' ) ) {
	class multi_item_carousel extends fp\Component {

		public $schema_version          = 7; // This needs to be updated manuall when we make changes to the this template so we can find out of date components.
		public $version                 = '1.4.6';
		public $component               = 'multi_item_carousel'; // Component slug should be same as this file base name.
		public $component_name          = 'Multi-Item Carousel'; // Shown in BB sidebar.
		public $component_description   = 'Create a slider from multiple sources';
		public $component_category      = 'FP Dynamic Components';
		public $component_load_category = 'dyanmic';
		public $enable_css              = true;
		public $enable_js               = true;
		public $deps_css                = array( 'brand', 'dashicons' ); // WordPress Registered CSS Dependencies.
		public $deps_js                 = array( 'jquery' ); // WordPress Registered JS Dependencies.
		public $deps_css_remote         = array( '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' ); // WordPress Registered CSS Dependencies.
		public $deps_js_remote          = array( '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js' ); // WordPress Registered JS Dependencies.
		public $base_dir                = __DIR__;
		public $fields                  = array(); // Placeholder for fields used in BB Module & Shortcode.
		public $bbconfig                = array(); // Placeholder for BB Module Registration.
		public $variants                = array( '-ordered-list', '-showcase', '-title-card' ); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content    = true; // Exclude content of this module from being saved to post_content field
		// public $load_in_header               = true;
		public $dynamic_data_feed_parameters = array( // Generates $atts[posts] object with dynamically populated data.
			'posts_per_page_default' => 6,
			'posts_per_page_options' => array(
				'3'  => '3',
				'4'  => '4',
				'5'  => '5',
				'6'  => '6',
				'8'  => '8',
				'9'  => '9',
				'10' => '10',
				'12' => '12',
			),
			'post_types'             => array( 'article', 'store', 'product', 'faq', 'cooking-tip', 'easy-meal', 'how-to', 'video', 'collection', 'local-farmer' ),
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
					'multi_static_slide',
					array(
						'title' => __( 'Slide Attributes', FP_TD ),
						'tabs'  => array(
							'general' => array(
								'title'    => __( 'General', FP_TD ),
								'sections' => array(
									'general' => array(
										'fields' => array(
											'carousel_item_title' => array(
												'type'    => 'text',
												'label'   => __( 'Optional: Title', FP_TD ),
												'default' => __( 'Slide', FP_TD ),
											),
											'carousel_item_description' => array(
												'type'    => 'text',
												'label'   => __( 'Optional: Description', FP_TD ),
												'default' => __( 'Slide Description', FP_TD ),
											),
											'carousel_item_date' => array(
												'type'    => 'text',
												'label'   => __( 'Optional: Date', FP_TD ),
												'default' => __( 'Slide Date', FP_TD ),
											),
											'carousel_item_category' => array(
												'type'    => 'text',
												'label'   => __( 'Optional: Category', FP_TD ),
												'default' => __( 'Slide Category', FP_TD ),
											),
											'carousel_item_link' => array(
												'type'  => 'link',
												'label' => __( 'Add CTA Link', FP_TD ),
											),
											'carousel_item_button_text' => array(
												'type'    => 'text',
												'label'   => __( 'Set Button Text', FP_TD ),
												'default' => __( 'Learn More', FP_TD ),
											),
											'carousel_item_button_type' => array(
												'type'    => 'select',
												'label'   => __( 'Set Button Type', FP_TD ),
												'options' => array(
													'-simple-link'          => 'Simple Link',
													'-video-link'           => 'Video Link',
													'-simple-link-with-cta' => 'Simple Link With CTA',
												),
												'default' => '-simple-link',
											),
											'carousel_item_aria' => array(
												'type'  => 'text',
												'label' => __( 'Set the Aria Label', FP_TD ),
											),
											'carousel_item_button_target' => array(
												'type'    => 'select',
												'label'   => __( 'Set Button Target', FP_TD ),
												'options' => array(
													'_self'  => 'Same Window',
													'_blank' => 'New Window',
												),
												'default' => '_self',
											),
											'carousel_item_photo' => array(
												'type'  => 'photo',
												'label' => __( 'Choose a background photo', FP_TD ),
											),
											'carousel_item_hover_photo' => array(
												'type'  => 'photo',
												'label' => __( 'Choose a hover photo', FP_TD ),
											),
											'thumbnail_hover_image_size' => array(
												'type'    => 'select',
												'label'   => __( 'Thumbnail Image Size', FP_TD ),
												'default' => 'thumbnail',
												'options' => get_registered_thumbnails(),
												'default' => ( defined( 'FP_MODULE_DEFAULTS' ) && ! empty( FP_MODULE_DEFAULTS[ $this->component ]['thumbnail_hover_image_size'] ) ) ? FP_MODULE_DEFAULTS[ $this->component ]['thumbnail_hover_image_size'] : 'thubmnail', // opg default
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
				'fp-multi_carousel-tab-1' => array(
					'title'    => __( 'Settings', FP_TD ),
					'sections' => array(
						'carousel_options'        => array(
							'title'  => __( 'Set Carousel Options', FP_TD ),
							'fields' => array(
								'slide_layout'             => array(
									'type'    => 'select',
									'label'   => __( 'Choose a layout', FP_TD ),
									'options' => array(
										'default'       => 'Default',
										'-title-card'   => 'Static Title Card',
										'-showcase'     => 'Showcase',
										'-ordered-list' => 'Ordered List',
									),
									'default' => 'default',
									'toggle'  => array(
										'default'       => array(
											'fields' => array( 'slides_per_scroll', 'slides_to_show', 'slide_show_arrows' ),
										),
										'-title-card'   => array(
											'fields' => array( 'slides_per_scroll', 'slides_to_show', 'title_card_link', 'title_card_link_text' ),
										),
										'-showcase'     => array(
											'fields' => array( 'slide_show_arrows' ),
										),
										'-ordered-list' => array(
											'fields' => array( 'slides_per_scroll', 'slides_to_show', 'slide_show_arrows' ),
										),
									),
									'preview' => array(
										'type' => 'none',
									),
								),
								'slides_per_scroll'        => array(
									'type'    => 'select',
									'label'   => __( 'Slides Per Scroll', FP_TD ),
									'options' => array(
										'1' => '1',
										'2' => '2',
										'3' => '3',
										'4' => '4',
										'5' => '5',
										'6' => '6',
									),
									'default' => '3',
									'preview' => array(
										'type' => 'none',
									),
								),
								'slides_per_scroll_mobile' => array(
									'type'    => 'select',
									'label'   => __( 'Slides Per Scroll (Mobile)', FP_TD ),
									'options' => array(
										'1' => '1',
										'2' => '2',
									),
									'default' => '1',
									'preview' => array(
										'type' => 'none',
									),
								),
								'slides_to_show'           => array(
									'type'    => 'select',
									'label'   => __( 'Slides To Show', FP_TD ),
									'options' => array(
										'1' => '1',
										'2' => '2',
										'3' => '3',
										'4' => '4',
										'5' => '5',
										'6' => '6',
									),
									'default' => '3',
									'preview' => array(
										'type' => 'none',
									),
								),
								'slides_to_show_mobile'    => array(
									'type'    => 'select',
									'label'   => __( 'Slides To Show (Mobile)', FP_TD ),
									'options' => array(
										'1' => '1',
										'2' => '2',
									),
									'default' => '1',
									'preview' => array(
										'type' => 'none',
									),
								),
								'slide_show_description'   => array(
									'type'    => 'select',
									'label'   => __( 'Show the post/slide description/excerpt?', FP_TD ),
									'options' => array(
										'1' => 'Yes',
										'0' => 'No',
									),
									'default' => '0',
									'toggle'  => array(
										'1' => array(
											'fields' => array( 'slides_description_typography', 'slide_description_border', 'slide_description_padding' ),
										),
									),
									'preview' => array(
										'type' => 'none',
									),
								),
								'enable_overlay'           => array(
									'type'    => 'select',
									'label'   => __( 'Enable Overlay', FP_TD ),
									'options' => array(
										'1' => 'Yes',
										'0' => 'No',
									),
									'default' => '0',
									'toggle'  => array(
										'1' => array(
											'fields' => array( 'slide_overlay_theme' ),
										),
									),
									'preview' => array(
										'type' => 'none',
									),
								),
								'slide_show_arrows'        => array(
									'type'    => 'select',
									'label'   => __( 'Set the arrows style', FP_TD ),
									'options' => array(
										'-page-arrows'   => 'White Buttons/Arrows',
										'-page-chevrons' => 'Full Height/Chevrons',
									),
									'default' => '-page-arrows',
									'preview' => array(
										'type' => 'none',
									),
								),
								'enable_dots'              => array(
									'type'    => 'select',
									'label'   => __( 'Enable Dots', FP_TD ),
									'options' => array(
										'1' => 'Yes',
										'0' => 'No',
									),
									'toggle'  => array(
										'1' => array(
											'fields' => array( 'slide_dots_theme', 'round_dots' ),
										),
									),
									'preview' => array(
										'type' => 'none',
									),
								),
							),
						),
						'multi_carousel_override' => array(
							'title'  => __( 'Static Content Options', FP_TD ),
							'fields' => array(
								'multi_static_slides' => array(
									'type'         => 'form',
									'label'        => __( 'Static Content Override', FP_TD ),
									'form'         => 'multi_static_slide',
									'multiple'     => true,
									'max'          => 12,
									'preview_text' => 'carousel_item_title',
									'preview'      => array(
										'type' => 'none',
									),
								),
							),
						),
						'attributes'              => array(
							'title'  => __( 'Attributes', FP_TD ),
							'fields' => array(
								'title'                    => array(
									'type'    => 'text',
									'label'   => __( 'Title', FP_TD ),
									'default' => '',
								),
								'title_tag'                => array(
									'type'    => 'select',
									'label'   => __( 'Title Tag', FP_TD ),
									'default' => 'h3',
									'options' => array(
										'h2' => __( 'H2', FP_TD ),
										'h3' => __( 'H3', FP_TD ),
										'h4' => __( 'H4', FP_TD ),
										'h5' => __( 'H5', FP_TD ),
										'h6' => __( 'H6', FP_TD ),
									),
								),
								'slide_overlay_theme'      => array(
									'type'    => 'color',
									'label'   => __( 'Choose the hover overlay colour', FP_TD ),
									'preview' => array(
										'type' => 'none',
									),
								),
								'slide_dots_theme'         => array(
									'type'    => 'fp-colour-picker',
									'label'   => __( 'Choose the dots colour', FP_TD ),
									'element' => 'background',
									'preview' => array(
										'type' => 'none',
									),
								),
								'round_dots'               => array(
									'type'    => 'select',
									'label'   => __( 'Dot style', FP_TD ),
									'options' => array(
										'1' => 'Round',
										'0' => 'Flat',
									),
									'default' => '0',
									'preview' => array(
										'type' => 'none',
									),
								),
								'slide_padding'            => array(
									'type'         => 'dimension',
									'label'        => 'Slide padding',
									'units'        => array( 'px', 'rem' ),
									'default_unit' => '%',
									'responsive'   => true,
									'preview'      => array(
										'type' => 'none',
									),
								),
								'slide_border'             => array(
									'type'       => 'border',
									'label'      => 'Slide border',
									'responsive' => true,
									'preview'    => array(
										'type' => 'none',
									),
								),
								'slide_image_padding'      => array(
									'type'         => 'dimension',
									'label'        => 'Slide image padding',
									'units'        => array( 'px', 'rem' ),
									'default_unit' => '%',
									'responsive'   => true,
									'preview'      => array(
										'type' => 'none',
									),
								),
								'slide_image_border'       => array(
									'type'       => 'border',
									'label'      => 'Slide image border',
									'responsive' => true,
									'preview'    => array(
										'type' => 'none',
									),
								),
								'slide_image_fit'          => array(
									'type'    => 'select',
									'label'   => __( 'Slide image fit', FP_TD ),
									'options' => array(
										'stretch' => 'Stetch to fit',
										'center'  => 'Center image',
									),
									'default' => 'stretch',
									'preview' => array(
										'type' => 'none',
									),
								),
								'slide_content_padding'    => array(
									'type'         => 'dimension',
									'label'        => 'Slide content padding',
									'units'        => array( 'px', 'rem' ),
									'default_unit' => '%',
									'responsive'   => true,
									'preview'      => array(
										'type' => 'none',
									),
								),
								'slide_content_border'     => array(
									'type'       => 'border',
									'label'      => 'Slide content border',
									'responsive' => true,
									'preview'    => array(
										'type' => 'none',
									),
								),
								'slides_heading_typography' => array(
									'type'       => 'typography',
									'label'      => __( 'Title Typography', FP_TD ),
									'responsive' => true,
									'preview'    => array(
										'type' => 'none',
									),
								),
								'slide_title_border'       => array(
									'type'       => 'border',
									'label'      => 'Slide title border',
									'responsive' => true,
									'preview'    => array(
										'type' => 'none',
									),
								),
								'slide_title_padding'      => array(
									'type'         => 'dimension',
									'label'        => 'Slide title padding',
									'units'        => array( 'px', 'rem' ),
									'default_unit' => '%',
									'responsive'   => true,
									'preview'      => array(
										'type' => 'none',
									),
								),
								'slides_description_typography' => array(
									'type'       => 'typography',
									'label'      => __( 'Description Typography', FP_TD ),
									'responsive' => true,
									'preview'    => array(
										'type' => 'none',
									),
								),
								'slide_description_length' => array(
									'type'        => 'unit',
									'label'       => 'Slide Description Length',
									'description' => 'Characters, rounded to the nearest word-break',
									'default'     => 65,
									'slider'      => true,
									'preview'     => array(
										'type' => 'none',
									),
								),
								'slide_description_border' => array(
									'type'       => 'border',
									'label'      => 'Slide description border',
									'responsive' => true,
									'preview'    => array(
										'type' => 'none',
									),
								),
								'slide_description_padding' => array(
									'type'         => 'dimension',
									'label'        => 'Slide description padding',
									'units'        => array( 'px', 'rem' ),
									'default_unit' => '%',
									'responsive'   => true,
									'preview'      => array(
										'type' => 'none',
									),
								),
								'cta_style'                => array(
									'type'    => 'select',
									'label'   => __( 'Slide CTA type', FP_TD ),
									'options' => array(
										'-button' => 'Button',
										'-link'   => 'Text Link',
									),
									'default' => 'link',
									'preview' => array(
										'type' => 'none',
									),
								),
								'show_date'                => array(
									'type'    => 'select',
									'label'   => __( 'Show Post Date', FP_TD ),
									'default' => 'false',
									'options' => array(
										'true'  => __( 'True', FP_TD ),
										'false' => __( 'False', FP_TD ),
									),
								),
								'show_category'            => array(
									'type'    => 'select',
									'label'   => __( 'Show Category', FP_TD ),
									'default' => 'false',
									'options' => array(
										'true'  => __( 'True', FP_TD ),
										'false' => __( 'False', FP_TD ),
									),
									'toggle'  => array(
										'true' => array( 'fields' => array( 'category_taxonomy' ) ),
									),
								),
								'category_taxonomy'        => array(
									'type'    => 'text',
									'label'   => __( 'Category Taxonomy', FP_TD ),
									'default' => 'category',
								),
								'title_card_link'          => array(
									'type'          => 'link',
									'label'         => __( 'Title Card Link', FP_TD ),
									'show_target'   => true,
									'show_nofollow' => true,
								),
								'title_card_link_text'     => array(
									'type'    => 'text',
									'label'   => __( 'Title Card Link Text', FP_TD ),
									'default' => 'View All',
								),
								'title_card_link_aria'     => array(
									'type'    => 'text',
									'label'   => __( 'Title Card Link Aria', FP_TD ),
									'default' => 'View All',
								),
							),
						),
					),
				),
			);
		}

		public function pre_process_data( $atts, $module ) {
			$classes[] = $atts['slide_layout'];
			$classes[] = $atts['slide_layout'] == '-title-card' ? '-page-arrows' : $atts['slide_show_arrows'];

			$atts['classes'] = implode( ' ', $classes );

			$atts['enable_dots'] = $atts['enable_dots'] == '1' ? 'true' : '0';

			$atts['prev_arrow'] = $atts['slide_layout'] == '-title-card' ? '.carousel-header .slick-prev' : '<button class=\"slick-prev slick-arrow\" aria-label=\"Previous\" type=\"button\">Previous</button>';
			$atts['next_arrow'] = $atts['slide_layout'] == '-title-card' ? '.carousel-header .slick-next' : '<button class=\"slick-next slick-arrow\" aria-label=\"Next\" type=\"button\">Next</button>';

			$atts['slides_per_scroll']        = $atts['slide_layout'] == '-showcase' ? '1' : $atts['slides_per_scroll'];
			$atts['slides_to_show']           = $atts['slide_layout'] == '-showcase' ? '1' : $atts['slides_to_show'];
			$atts['slides_per_scroll_mobile'] = $atts['slide_layout'] == '-showcase' ? '1' : $atts['slides_per_scroll_mobile'];
			$atts['slides_to_show_mobile']    = $atts['slide_layout'] == '-showcase' ? '1' : $atts['slides_to_show_mobile'];
			$atts['center_mode']              = $atts['slide_layout'] == '-showcase' ? 'true' : '0';
			$atts['variable_width']           = $atts['slide_layout'] == '-showcase' ? 'true' : 'false';
			$atts['width']                    = $atts['slide_layout'] == '-showcase' ? 950 : 640;
			$atts['height']                   = $atts['slide_layout'] == '-showcase' ? 550 : 420;

			if ( ! empty( $atts['multi_static_slides'] ) && count( $atts['multi_static_slides'] ) >= 3 ) {
				$atts['posts'] = array();
				foreach ( $atts['multi_static_slides'] as $si ) {
					$atts['posts'][] = (object) array(
						'post_title'                 => $si->carousel_item_title,
						'post_excerpt'               => $si->carousel_item_description,
						'post_date'                  => $si->carousel_item_date,
						'post_category'              => $si->carousel_item_category,
						'post_type'                  => 'custom',
						'permalink'                  => $si->carousel_item_link,
						'thumbnail_id'               => $si->carousel_item_photo,
						'thumbnail_hover_id'         => $si->carousel_item_hover_photo,
						'thumbnail_hover_image_size' => $si->thumbnail_hover_image_size,
						'button_text'                => $si->carousel_item_button_text,
						'button_type'                => $si->carousel_item_button_type,
						'button_target'              => $si->carousel_item_button_target,
						'button_aria'                => ( isset( $si->carousel_item_aria ) && ! empty( $si->carousel_item_aria ) ) ? $si->carousel_item_aria : $si->carousel_item_button_text . ': ' . $si->carousel_item_title,
					);
				}
			}

			return $atts;
		}
	}
	new multi_item_carousel();
}
