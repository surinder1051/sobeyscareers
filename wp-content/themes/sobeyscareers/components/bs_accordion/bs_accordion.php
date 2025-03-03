<?php

namespace fp\components;

use fp;

if ( class_exists( 'fp\Component' ) ) {
	class bs_accordion extends fp\Component {

		public $schema_version               = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components.
		public $version                      = '1.1.0';
		public $component                    = 'bs_accordion'; // Component slug should be same as this file base name.
		public $component_name               = 'Accordion with Repeater Content'; // Shown in BB sidebar.
		public $component_description        = 'Accordion with Stylized Repeater Content';
		public $component_category           = 'FP Global';
		public $component_load_category      = 'bootstrap';
		public $enable_css                   = true;
		public $enable_js                    = true;
		public $deps_css                     = array(); // WordPress Registered CSS Dependencies.
		public $deps_js                      = array( 'jquery' ); // WordPress Registered JS Dependencies.
		public $base_dir                     = __DIR__;
		public $fields                       = array(); // Placeholder for fields used in BB Module & Shortcode.
		public $bbconfig                     = array(); // Placeholder for BB Module Registration.
		public $variants                     = array( '-toggle-icon' ); // Component CSS Variants as per -> http://rscss.io/variants.html
		public $dynamic_data_feed_parameters = array( // Generates $atts[posts] object with dynamically populated data.
			'post_types'             => array( 'post', 'page', 'faq' ),
			'taxonomies'             => array( array( 'faq-category' => array( 'none-option' => true ) ) ),
			'order'                  => 'DESC',
			'orderby'                => 'menu_order',
			'posts_per_page_default' => '3',
			'posts_per_page_options' => array(
				'1'  => 1,
				'2'  => 2,
				'3'  => 3,
				'4'  => 4,
				'5'  => 5,
				'6'  => 6,
				'7'  => 7,
				'8'  => 8,
				'9'  => 9,
				'10' => 10,
				'15' => 15,
				'20' => 20,
			),
		);

		public function init_fields() {
			/*
			Documentation @ https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref

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
					'card',
					array(
						'title' => __( 'Card Content', FP_TD ),
						'tabs'  => array(
							'general' => array(
								'title'    => __( 'General', FP_TD ),
								'sections' => array(
									'card_content' => array(
										'fields' => array(
											'state' => array(
												'type'    => 'select',
												'label'   => __( 'State', FP_TD ),
												'default' => '',
												'options' => array(
													''     => __( 'Collapsed', FP_TD ),
													'show' => __( 'Expanded', FP_TD ),
												),
											),
											'title' => array(
												'type'  => 'text',
												'label' => __( 'Title', FP_TD ),
											),
											'text'  => array(
												'type'  => 'editor',
												'label' => __( 'Text', FP_TD ),
											),
										),
									),
									'subitem_grid' => array(
										'title'  => __( 'Subitem Grid', FP_TD ),
										'fields' => array(
											'grid_columns' => array(
												'type'   => 'unit',
												'label'  => __( 'Number of columns', FP_TD ),
												'responsive' => true,
												'slider' => array(
													'min' => 1,
													'max' => 4,
												),
											),
											'subitem_heading_typography' => array(
												'type'    => 'typography',
												'label'   => __( 'Subitem Heading Typography', FP_TD ),
												'responsive' => true,
												'preview' => array(
													'type' => 'css',
													'selector' => '.component_bs_accordion .card .card-body .card-content-grid .grid-heading',
												),
											),
											'subitems'     => array(
												'type'     => 'form',
												'form'     => 'subitem_content',
												'label'    => __( 'Subitem', FP_TD ),
												'multiple' => true,
												'preview'  => 'sub_item_heading',
											),
										),
									),
								),
							),
						),
					),
				),
				array(
					'subitem_content',
					array(
						'title' => __( 'Subitem Content', FP_TD ),
						'tabs'  => array(
							'general' => array(
								'title'    => __( 'General', FP_TD ),
								'sections' => array(
									'general' => array(
										'fields' => array(
											'sub_item_image'   => array(
												'type'  => 'photo',
												'label' => __( 'Choose images (in order of display)', FP_TD ),
												'show_remove' => true,
												'show'  => array( 'fields' => array( 'sub_item_image_width' ) ),
											),
											'sub_item_image_width' => array(
												'type'    => 'unit',
												'label'   => __( 'Set the image width', FP_TD ),
												'description' => 'px',
												'default' => 150,
											),
											'sub_item_heading' => array(
												'type'  => 'text',
												'label' => __( 'Heading', FP_TD ),
												'description' => __( 'Leave empty to hide', FP_TD ),
											),
											'sub_item_text'    => array(
												'type'  => 'editor',
												'label' => __( 'Descriptive Text', FP_TD ),
												'description' => __( 'Leave empty to hide', FP_TD ),
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
				'fp-bs_accordion-tab-1' => array(
					'title'    => __( 'Settings', FP_TD ),
					'sections' => array(
						'accordion' => array(
							'fields' => array(
								'title'     => array(
									'type'        => 'text',
									'label'       => __( 'Title', FP_TD ),
									'description' => __( '', FP_TD ),
									'default'     => 'Default',
								),
								'title_tag' => array(
									'type'    => 'select',
									'label'   => __( 'Title Tag', FP_TD ),
									'default' => 'h2',
									'options' => array(
										'h2' => 'H2',
										'h3' => 'H3',
										'h4' => 'H4',
										'h5' => 'H5',
										'h6' => 'H6',
									),
								),
							),
						),
						'cards'     => array(
							'title'  => __( 'Cards', FP_TD ),
							'fields' => array(
								'card_header_tag' => array(
									'type'    => 'select',
									'label'   => __( 'Card Header Tag', FP_TD ),
									'default' => 'h5',
									'options' => array(
										'h2' => 'H2',
										'h3' => 'H3',
										'h4' => 'H4',
										'h5' => 'H5',
										'h6' => 'H6',
									),
								),
								'show_card_icon'  => array(
									'type'    => 'select',
									'label'   => __( 'Show open/close icons?', FP_TD ),
									'default' => 'false',
									'options' => array(
										'true'  => __( 'Yes', FP_TD ),
										'false' => __( 'No', FP_TD ),
									),
									'toggle'  => array(
										'true' => array(
											'fields' => array( 'card_icon_color' ),
										),
									),
								),
								'card_icon_color' => array(
									'type'       => 'color',
									'label'      => __( 'Open/close icon color', FP_TD ),
									'show_reset' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_bs_accordion .card .card-header .accordion-header::after',
										'property' => 'color',
									),
								),
								'items'           => array(
									'type'         => 'form',
									'form'         => 'card',
									'label'        => __( 'Card', FP_TD ),
									'wpautop'      => false,
									'multiple'     => true,
									'preview_text' => 'title',
								),
							),
						),
					),
				),
			);
		}

		public function pre_process_data( $atts, $module ) {
			if ( 'true' === $atts['show_card_icon'] ) {
				$atts['classes'] .= '-toggle-icon ';
			}

			// Process the sub-items.
			if ( isset( $atts['items'] ) && is_array( $atts['items'] ) && count( $atts['items'] ) ) {
				// Add static data.
				foreach ( $atts['items'] as $key => $si ) {
					$sub_content = array();

					if ( ! empty( $si->subitems ) ) {
						foreach ( $si->subitems as $index => $sub_item ) {
							$ci = json_decode( $sub_item );

							if ( empty( $ci ) ) {
								continue;
							}

							$img_src = wp_get_attachment_url( intval( $ci->sub_item_image, 10 ) );
							$image   = '';
							if ( false !== $img_src ) {
								$alt   = get_post_meta( $ci->sub_item_image, '_wp_attachment_image_alt', true );
								$width = ( $ci->sub_item_image_width > 0 ) ? $ci->sub_item_image_width : 150;
								$image = '<img src="' . $img_src . '" alt="' . $alt . '" width="' . $width . '" />';

								$sub_content[ $index ]['image_width'] = $width;
							}
							$sub_content[ $index ]['image'] = $image;

							if ( isset( $ci->sub_item_heading ) ) {
								$sub_content[ $index ]['heading'] = $ci->sub_item_heading;
							}

							if ( isset( $ci->sub_item_text ) ) {
								$sub_content[ $index ]['subtext'] = nl2br( $ci->sub_item_text );
							}
						}

						if ( ! empty( $sub_content ) ) {
							$atts['items'][ $key ]->custom_content = $sub_content;
						}
					}
				}
			}

			// Append the dynamic posts.
			if ( isset( $atts['posts'] ) && is_array( $atts['posts'] ) ) {
				$atts['items'] = is_array( $atts['items'] ) ? $atts['items'] : array();
				foreach ( $atts['posts'] as $key => $post ) {
					$atts['items'][] = (object) array(
						'title' => $post->post_title,
						'text'  => $post->post_content,
					);
				}
			}

			return $atts;
		}
	}

	new bs_accordion();
}