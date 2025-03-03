<?php

namespace fp\components;

use fp;

if ( class_exists( 'fp\Component' ) ) {
	class bs_card extends fp\Component {



		public $schema_version          = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version                 = '1.8.0';
		public $component               = 'bs_card'; // Component slug should be same as this file base name
		public $component_name          = 'bs_card'; // Shown in BB sidebar.
		public $component_description   = 'A content card that can be used in a loop or as a single CTA item';
		public $component_category      = 'FP Global';
		public $component_load_category = 'bootstrap';
		public $enable_css              = true;
		public $enable_js               = true;
		public $deps_css                = array(); // WordPress Registered CSS Dependencies
		public $deps_js                 = array( 'jquery' ); // WordPress Registered JS Dependencies
		// public $deps_css_remote       			= array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote 		  			= array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir = __DIR__;
		public $fields   = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig = array(); // Placeholder for BB Module Registration
		public $variants = array( '-border', '-image-background', '-theme-light', '-theme-dark' ); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content 		= true; // Exclude content of this module from being saved to post_content field
		// public $load_in_header		  			= true;
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
					'link',
					array(
						'title' => __( 'Link', FP_TD ),
						'tabs'  => array(
							'general' => array(
								'title'    => __( 'General', FP_TD ),
								'sections' => array(
									'general' => array(
										'title'  => 'Title',
										'fields' => array(
											'link_title' => array(
												'type'  => 'text',
												'label' => __( 'Link Title', FP_TD ),
											),
											'link_aria'  => array(
												'type'  => 'text',
												'label' => __( 'Aria Label', FP_TD ),
											),
											'link_url'   => array(
												'type'  => 'link',
												'post_type' => array( 'page', 'post' ),
												'show_target' => true,
												'label' => __( 'Link To', FP_TD ),
											),
											'link_style' => array(
												'type'    => 'select',
												'options' => array(
													'text' => __( 'Text', FP_TD ),
													'button' => __( 'Button', FP_TD ),
												),
												'label'   => __( 'Choose the link style', FP_TD ),
												'toggle'  => array(
													'text' => array(
														'fields' => array( 'link_text_theme' ),
													),
													'button' => array(
														'fields' => array( 'link_button_theme' ),
													),
												),
												'default' => 'text',
											),
											'link_button_theme' => array(
												'type'    => 'fp-colour-picker',
												'element' => 'button',
												'label'   => __( 'Choose the button style', FP_TD ),
											),
											'link_text_theme' => array(
												'type'    => 'fp-colour-picker',
												'element' => 'a',
												'label'   => __( 'Choose the text link style', FP_TD ),
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
				'fp-bs_card-tab-1' => array(
					'title'    => __( 'Settings', FP_TD ),
					'sections' => array(
						'content'    => array(
							'title'  => __( 'Content', FP_TD ),
							'fields' => array(
								'title'                    => array(
									'type'    => 'text',
									'label'   => __( 'Title', FP_TD ),
									'default' => 'Praesent venenatis arcu maximus diam tincidunt mollis.',
								),
								'heading_type'             => array(
									'type'    => 'select',
									'label'   => __( 'Choose the heading type for the title', FP_TD ),
									'default' => __( 'h5', FP_TD ),
									'options' => array(
										'h1' => 'H1',
										'h2' => 'H2',
										'h3' => 'H3',
										'h4' => 'H4',
										'h5' => 'H5',
										'h6' => 'H6',
									),
									'default' => 'h2',
								),
								'title_color'              => array(
									'type'       => 'color',
									'label'      => __( 'Title Color', FP_TD ),
									'default'    => 'FFFFFF',
									'show_reset' => true,
								),
								'title_typography'         => array(
									'type'       => 'typography',
									'label'      => __( 'Title Typography', FP_TD ),
									'responsive' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.card-title',
									),
									'default'    => array(
										'family' => 'Helvetica',
										'weight' => 300,
									),
								),
								'description'              => array(
									'type'          => 'editor',
									'label'         => __( 'Description', FP_TD ),
									'default'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ',
									'media_buttons' => false,
								),
								'description_padding'      => array(
									'type'       => 'dimension',
									'label'      => __( 'Description padding', FP_TD ),
									'unit'       => 'px',
									'responsive' => true,
								),
								'description_expand'       => array(
									'type'    => 'select',
									'label'   => __( 'Click to expand description?', FP_TD ),
									'options' => array(
										'yes' => __( 'Yes', FP_TD ),
										'no'  => __( 'No', FP_TD ),
									),
									'default' => 'no',
									'toggle'  => array(
										'yes' => array(
											'fields' => array( 'description_expand_limit', 'description_expand_icon', 'description_hide_icon' ),
										),
										'no'  => array(),
									),
								),
								'description_expand_limit' => array(
									'type'  => 'unit',
									'label' => __( 'Max Number of characters to show', FP_TD ),
								),
								'description_expand_icon'  => array(
									'type'    => 'icon',
									'label'   => __( 'Choose the expand icon', FP_TD ),
									'default' => 'fas fa-plus-circle',
								),
								'description_hide_icon'    => array(
									'type'    => 'icon',
									'label'   => __( 'Choose the collapse icon', FP_TD ),
									'default' => 'fas fa-minus-circle',
								),
								'card_min_width'           => array(
									'type'    => 'text',
									'label'   => __( 'Card Min Width', FP_TD ),
									'default' => false,
								),
								'image'                    => array(
									'type'        => 'photo',
									'label'       => __( 'Photo', FP_TD ),
									'default'     => '',
									'show_remove' => true,
									'show'        => array(
										'fields' => array( 'image_min_height', 'image_position' ),
									),
								),
								'image_min_height'         => array(
									'type'        => 'unit',
									'label'       => __( 'Image Min Height', FP_TD ),
									'description' => 'px',
									'default'     => '160',
								),
								'image_max_height'         => array(
									'type'        => 'unit',
									'label'       => __( 'Image Max Height', FP_TD ),
									'description' => 'px',
									'default'     => '420',
								),
								'image_position'           => array(
									'type'    => 'select',
									'label'   => __( 'Image Position', FP_TD ),
									'options' => array(
										'top'        => __( 'Top', FP_TD ),
										'background' => __( 'Background', FP_TD ),
									),
									'default' => 'top',
								),
								'image_size'               => array(
									'type'    => 'select',
									'label'   => __( 'Image Size', FP_TD ),
									'default' => 'left',
									'options' => get_registered_thumbnails(),
									'default' => ( defined( 'FP_MODULE_DEFAULTS' ) && ! empty( FP_MODULE_DEFAULTS[ $this->component ]['image_size'] ) ) ? FP_MODULE_DEFAULTS[ $this->component ]['image_size'] : 'thumbnail', // opg default
								),
								'no_container'             => array(
									'type'        => 'select',
									'label'       => __( 'Hide Container Div', FP_TD ),
									'description' => __( 'Turn off automatic container created by BB.', FP_TD ),
									'default'     => 'false',
									'options'     => array(
										'true'  => __( 'False', FP_TD ),
										'false' => __( 'True', FP_TD ),
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
										'true' => array(
											'fields' => array( 'category_taxonomy' ),
										),
									),
								),
								'category_taxonomy'        => array(
									'type'    => 'text',
									'label'   => __( 'Category Taxonomy', FP_TD ),
									'default' => 'Category',
								),
								'meta_position'            => array(
									'type'    => 'select',
									'label'   => __( 'Date/Category position', FP_TD ),
									'default' => 'below',
									'options' => array(
										'above' => __( 'Above post title', FP_TD ),
										'below' => __( 'Below post title', FP_TD ),
									),
								),
							),
						),
						'attributes' => array(
							'title'  => __( 'Attributes', FP_TD ),
							'fields' => array(
								'enable_overlay'      => array(
									'type'    => 'select',
									'label'   => __( 'Enable Overlay', FP_TD ),
									'options' => array(
										'true'  => __( 'True', FP_TD ),
										'false' => __( 'False', FP_TD ),
									),
									'default' => 'true',
								),
								'overlay_only_image'  => array(
									'type'        => 'select',
									'label'       => __( 'Overlay Only Image', FP_TD ),
									'description' => __( 'Show overlay only on top of the image', FP_TD ),
									'default'     => 'false',
									'options'     => array(
										'true'  => __( 'True', FP_TD ),
										'false' => __( 'False', FP_TD ),
									),
								),
								'overlay_cta_text'    => array(
									'type'    => 'text',
									'label'   => __( 'Overlay CTA Text', FP_TD ),
									'default' => __( 'Learn More', FP_TD ),
								),
								'overlay_cta_url'     => array(
									'type'          => 'link',
									'label'         => __( 'Overlay CTA URL', FP_TD ),
									'show_target'   => true,
									'show_nofollow' => false,
								),
								'overlay_cta_colour'  => array(
									'type'  => 'color',
									'label' => __( 'Overlay Background Colour', FP_TD ),
								),
								'overlay_cta_opacity' => array(
									'type'    => 'unit',
									'label'   => __( 'Overlay Background Opacity', FP_TD ),
									'default' => '0.8',
								),
								'overlay_cta_theme'   => array(
									'type'    => 'fp-colour-picker',
									'label'   => __( 'Overlay Button Colour', FP_TD ),
									'element' => 'button',
								),
								'theme'               => array(
									'type'    => 'select',
									'label'   => __( 'Theme', FP_TD ),
									'options' => array(
										'light' => __( 'Light', FP_TD ),
										'dark'  => __( 'Dark', FP_TD ),
										'none'  => __( 'None', FP_TD ),
									),
									'default' => 'dark',
								),
								'border'              => array(
									'type'    => 'select',
									'label'   => __( 'Border', FP_TD ),
									'options' => array(
										'true'  => __( 'Show', FP_TD ),
										'false' => __( 'Hide', FP_TD ),
									),
									'default' => 'false',
								),
							),
						),
						'links'      => array(
							'title'  => __( 'Links', FP_TD ),
							'fields' => array(
								'link_section_title' => array(
									'type'    => 'text',
									'label'   => __( 'Link Section Title', FP_TD ),
									'default' => '',
								),
								'link_heading_type'  => array(
									'type'        => 'select',
									'label'       => __( 'Choose the heading type', FP_TD ),
									'default'     => __( 'h6', FP_TD ),
									'options'     => array(
										'h1' => 'H1',
										'h2' => 'H2',
										'h3' => 'H3',
										'h4' => 'H4',
										'h5' => 'H5',
										'h6' => 'H6',
									),
									'default'     => 'h3',
									'description' => __( 'For accessibility, this should be only one level down from the main heading. eg: Title Heading Type = h3, choose h4' ),
								),
								'text_links'         => array(
									'type'         => 'form',
									'form'         => 'link', // ID of a registered form.
									'label'        => __( 'Link(s)', FP_TD ),
									'preview_text' => 'link_title', // ID of a field to use for the preview text.
									'multiple'     => true,
								),
							),
						),
					),
				),
			);
		}

		public function pre_process_data( $atts, $module ) {
			$atts['classes']  = '-theme-' . $atts['theme'];
			$atts['classes'] .= ' -image-' . $atts['image_position'];
			if ( $atts['border'] == 'true' ) {
				$atts['classes'] .= ' -border';
			}
			if ( ! empty( $atts['term_id'] ) ) {
				$term                    = get_term( $atts['term_id'] );
				$atts['title']           = $term->name;
				$atts['description']     = $term->description;
				$atts['image']           = get_field( 'image', $term )['ID'];
				$object                  = (object) array();
				$object->link_url        = $atts['overlay_cta_url'] = get_term_link( $term );
				$object->link_url_target = '_self';
				$object->link_title      = ( ! empty( $atts['link_title'] ) ) ? $atts['link_title'] : __( 'Learn More', FP_TD );
				$atts['text_links']      = array( $object );
			}

			if ( ! empty( $atts['id'] ) ) {
				$post = get_post( $atts['id'] );
				if ( $post ) {
					setup_postdata( $post );
					$permalink = get_permalink( $post );

					$atts['title']           = $post->post_title;
					$atts['description']     = fp_extract_excerpt( $post );
					$atts['overlay_cta_url'] = $permalink;
					$atts['image']           = get_post_thumbnail_id( $post );
					$atts['date']            = $post->post_date;

					if ( $atts['enable_overlay'] == 'false' ) {
						$atts['text_links'] = array(
							(object) array(
								'link_title'      => apply_filters( 'bs_card_shortcode_text_link_title', __( 'Read More', FP_TD ), $post->post_type ),
								'link_url_target' => '_self',
								'link_url'        => $permalink,
							),
						);
					}

					if ( ! empty( $atts['show_category'] ) && $atts['show_category'] == 'true' ) {
						$terms = get_the_terms( $post->ID, $atts['category_taxonomy'] );
						if ( ! is_wp_error( $terms ) && $terms && count( $terms ) > 0 ) {
							$atts['first_term_in_category'] = $terms[0];
						}
					}
				}
			}

			if ( ! isset( $atts['image_size'] ) && isset( FP_MODULE_DEFAULTS[ $this->component ]['image_size'] ) ) {
				$atts['image_size'] = FP_MODULE_DEFAULTS[ $this->component ]['image_size'];
			}

			$atts['image_props'] = wp_get_attachment_image_src( $atts['image'], $atts['image_size'], '', array( 'class' => '' ) );

			if ( $atts['image'] || $atts['image_src'] ) {
				$cardImg = wp_get_attachment_image_src( $atts['image'], $atts['image_size'], '', array( 'class' => '' ) );
				if ( ! $cardImg ) {
					$cardImg = array(
						0 => "https://via.placeholder.com/300x{$atts['image_max_height']}",
						1 => 300,
						2 => $atts['image_max_height'],
					);
				}
			}

			$atts['card_img_bg']  = '';
			$atts['card_img_top'] = '';

			if ( $atts['image_position'] == 'background' && isset( $cardImg[0] ) ) {
				if ( ! empty( $image_src ) ) {
					$atts['card_img_bg'] = "background-image: url(\"$image_src\")";
				} else {
					$atts['card_img_bg'] = "background-image: url(\"$cardImg[0]\")";
				}
			}

			if ( $atts['image_position'] == 'top' && isset( $cardImg[0] ) ) {
				if ( ! empty( $image_src ) ) {
					$atts['card_img_top'] = "background-image: url(\"$image_src\")";
				} else {
					$atts['card_img_top'] = "background-image: url(\"$cardImg[0]\")";
				}
			}

			return $atts;
		}
	}

	new bs_card();
}
