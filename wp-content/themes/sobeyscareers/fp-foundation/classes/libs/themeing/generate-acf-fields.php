<?php
/**
 * Generate ACF Fields
 *
 * @package fp-foundation
 */

add_action( 'acf/init', 'generate_foundation_options_fields', 100 );
add_action( 'acf/init', 'generate_foundation_taxonomy_fields', 100 );
add_filter( 'acf/load_field/name=taxonomy_icon', 'foundation_acf_select_icon' );
add_action( 'admin_head', 'foundation_acf_custom_fonts' );

/**
 *  Generate inline styling for the ACF icon select field.
 */
function foundation_acf_custom_fonts() {
	$enabled_icons = generate_icons();

	if ( ! empty( $enabled_icons['css'] ) ) {
		$icons = explode( '}', $enabled_icons['css'] );
		echo '<style type="text/css">';
		echo '[data-name="taxonomy_icon"] .acf-input{width:100px;height:40px;line-height:40px;vertical-align:middle;position:relative;background:#fff;border:1px solid #ccc;overflow:hidden;z-index:}';
		echo '[data-name="taxonomy_icon"] .acf-input::after{content:"▼";font-size:0.5em;font-family:arial;position:absolute;top:50%;right:5px;transform:translate(0, -50%);}';
		echo '[data-name="taxonomy_icon"] .acf-input.hover::after{z-index:3;}';
		echo '[data-name="taxonomy_icon"] .acf-input.hover{overflow:visible;}';
		echo '[data-name="taxonomy_icon"] .acf-input.hover ul{background:white;border:1px solid #ccc;position:absolute;top:38px;left:-1px;width:100%;height:160px;overflow-y:scroll;z-index:4}';
		echo '[data-name="taxonomy_icon"] .acf-input .acf-bl > li{display:none;}';
		echo '[data-name="taxonomy_icon"] .acf-input.hover .acf-bl > li{display:list-item}';
		echo '[data-name="taxonomy_icon"] .acf-input.hover .acf-bl > li:hover{background-color:#f8f8f8;}';
		echo '[data-name="taxonomy_icon"] .acf-input label{display:block;font-size:0;height:40px;position:relative;}';
		echo '[data-name="taxonomy_icon"] .acf-input input{box-shadow:none;border:none;}';
		echo '[data-name="taxonomy_icon"] .acf-input .acf-bl > li.checked{display:list-item;}';
		echo '[data-name="taxonomy_icon"] .acf-input.hover .acf-bl > li.checked{background-color:#ebfaff;}';
		echo '[data-name="taxonomy_icon"] .acf-input.hover .acf-bl > li.checked .taxonomy-icon-preview{background-color:#ebfaff;}';
		echo '.taxonomy-icon-preview{position:absolute;left:0;background:#fff;top:0;height:35px;}';
		echo '.taxonomy-icon-preview [class^="icon-"]{font-size:35px;}';
		echo '[data-name="taxonomy_icon"] li:hover .taxonomy-icon-preview{background-color:#f8f8f8;}';
		foreach ( $icons as $icon ) {
			if ( ! empty( $icon ) ) {
				echo '.taxonomy-icon-preview ' . str_replace( '22px', '35px', $icon ) . '}'; //phpcs:ignore
			}
		};
		echo '</style>';

	}

}

/**
 * Create select icon options from the icon list stored in the db transient.
 *
 * @param string $field is the ACF Field being populated.
 * @see generate_icon_acf_list()
 *
 * @return array $field is the updated ACF field options list.
 */
function foundation_acf_select_icon( $field ) {
	$enabled_icons = generate_icon_acf_list();
	if ( ! empty( $enabled_icons ) ) {
		foreach ( $enabled_icons as $name => $text ) {
			$field['choices'][ $name ] = str_replace( 'fp-icon', '', $text );
		}
	}

	return $field;
}

/**
 * Create theme options fields including: favicon, default branding colours, custom theme colour sets, font sizing
 */
function generate_foundation_options_fields() {
	if ( function_exists( 'acf_add_local_field_group' ) ) :

		// Favicons.
		acf_add_local_field_group(
			array(
				'key'                   => 'group_5f15efaba5193',
				'title'                 => 'Favicons',
				'fields'                => array(
					array(
						'key'               => 'field_5f15efc2ad8dc',
						'label'             => 'Favicon',
						'name'              => 'favicon',
						'type'              => 'group',
						'instructions'      => 'Add favicons of multiple sizes',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'layout'            => 'block',
						'sub_fields'        => array(
							array(
								'key'               => 'field_5f15f032ad8dd',
								'label'             => 'Standard Favicon',
								'name'              => 'favicon_16',
								'type'              => 'image',
								'instructions'      => '16px * 16px',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'return_format'     => 'url',
								'preview_size'      => 'full',
								'library'           => 'all',
								'min_width'         => 16,
								'min_height'        => 16,
								'min_size'          => '',
								'max_width'         => 16,
								'max_height'        => 16,
								'max_size'          => '',
								'mime_types'        => 'png',
							),
							array(
								'key'               => 'field_5f15f080ad8de',
								'label'             => 'Medium Size',
								'name'              => 'favicon_32',
								'type'              => 'image',
								'instructions'      => '32px * 32px',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'return_format'     => 'url',
								'preview_size'      => 'full',
								'library'           => 'all',
								'min_width'         => 32,
								'min_height'        => 32,
								'min_size'          => '',
								'max_width'         => 32,
								'max_height'        => 32,
								'max_size'          => '',
								'mime_types'        => '',
							),
							array(
								'key'               => 'field_5f15f12bad8df',
								'label'             => 'Apple Touch Icon',
								'name'              => 'favicon_196',
								'type'              => 'image',
								'instructions'      => '196px * 196px',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'return_format'     => 'url',
								'preview_size'      => 'full',
								'library'           => 'all',
								'min_width'         => 196,
								'min_height'        => 196,
								'min_size'          => '',
								'max_width'         => 196,
								'max_height'        => 196,
								'max_size'          => '',
								'mime_types'        => '',
							),
						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			),
		);

		// Site Theming. Added later in this file as a set of subfields.

		$default_theme_options = array(
			array(
				'key'               => 'field_5dd562e781a79',
				'label'             => 'Fonts',
				'name'              => 'fonts',
				'type'              => 'group',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'sub_fields'        => array(
					array(
						'key'               => 'field_5d7ffec7d96c1',
						'label'             => 'Size',
						'name'              => 'size',
						'type'              => 'range',
						'instructions'      => 'This is the base font size for the site. H tags are scaled using this value.',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => 16,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => 'px',
						'min'               => 10,
						'max'               => 40,
					),
					array(
						'key'               => 'field_5d7ffec7d96d1',
						'label'             => 'Family',
						'name'              => 'font_family',
						'type'              => 'select',
						'instructions'      => 'This is the base font for the site.',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'placeholder'       => 'Font Family',
						'choices'           => array(),
						'default_value'     => '',
						'multiple'          => 0,
					),
					array(
						'key'               => 'field_5d7ffec7d96e1',
						'label'             => 'Variant',
						'name'              => 'font_variants',
						'type'              => 'checkbox',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'placeholder'       => 'Font Variant',
						'choices'           => array(
							'' => 'Save your font above first',
						),
						'default_value'     => '',
						'instructions'      => 'Click "Update" to save the chosen font and then choose from the available variants and save again.',
					),
				),
			),
			array(
				'key'               => 'field_5d7ffec7d96c9',
				'label'             => 'Main Font Colour',
				'name'              => 'main_font_colour',
				'type'              => 'color_picker',
				'instructions'      => 'Use this option for the main content font colour (paragraphs, lists)',
				'required'          => 1,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
			),
			array(
				'key'               => 'field_5d7fff1959a79',
				'label'             => 'Secondary Colour',
				'name'              => 'secondary_colour',
				'type'              => 'color_picker',
				'instructions'      => 'Use this option for the accent colour for elements such as: block quotes & pull quotes',
				'required'          => 1,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
			),
			array(
				'key'               => 'field_5d7ffd94043fc',
				'label'             => 'Button',
				'name'              => 'default_button',
				'type'              => 'group',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'layout'            => 'row',
				'sub_fields'        => array(
					array(
						'key'               => 'field_5d7ffda2043fd',
						'label'             => 'Default Colour',
						'name'              => 'default_colour',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffdb9043fe',
						'label'             => 'Hover Colour',
						'name'              => 'hover_colour',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffdca043ff',
						'label'             => 'Text Colour',
						'name'              => 'text_colour',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5dd2f9fbeda3a',
						'label'             => 'Text Hover Colour',
						'name'              => 'text_hover_colour',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),

				),
			),
			array(
				'key'               => 'field_5d7ffde404400',
				'label'             => 'Text Links',
				'name'              => 'text_links',
				'type'              => 'group',
				'instructions'      => 'This represents all text links in the body content of your pages/posts/components',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'layout'            => 'row',
				'sub_fields'        => array(
					array(
						'key'               => 'field_5d7ffe1604401',
						'label'             => 'Default Colour',
						'name'              => 'default_colour',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffe9504403',
						'label'             => 'Hover Colour',
						'name'              => 'hover_colour',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5a7fafe16044k1',
						'label'             => 'Link Focus Colour',
						'name'              => 'link_focus_colour',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffe1s04ak1a',
						'label'             => 'Link Hover State',
						'name'              => 'link_hover_state',
						'type'              => 'checkbox',
						'instructions'      => '',
						'required'          => 0,
						'multiple'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'choices'           => array(
							'underline' => 'Underline',
							'bold'      => 'Bold',
						),
						'default_value'     => 'default',
					),
				),
			),
			array(
				'key'               => 'field_5dd5656d3a79e',
				'label'             => 'Headings',
				'name'              => 'headings',
				'type'              => 'group',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'layout'            => 'row',
				'sub_fields'        => array(
					array(
						'key'               => 'field_5d7ffbb6043f3',
						'label'             => 'Heading 1',
						'name'              => 'default_h1',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffbb6043f3',
						'label'             => 'Heading 1',
						'name'              => 'default_h1',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffd17043f7',
						'label'             => 'Heading 2',
						'name'              => 'default_h2',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffd41043f8',
						'label'             => 'Heading 3',
						'name'              => 'default_h3',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffd50043f9',
						'label'             => 'Heading 4',
						'name'              => 'default_h4',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffd64043fa',
						'label'             => 'Heading 5',
						'name'              => 'default_h5',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
					array(
						'key'               => 'field_5d7ffd6f043fb',
						'label'             => 'Heading 6',
						'name'              => 'default_h6',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
				),
			),
		);

		// Make alternating background colour optional.
		if ( ! defined( 'EXCLUDE_THEME_ACF_OPTIONALS' ) || ! in_array( 'module_background_colour', EXCLUDE_THEME_ACF_OPTIONALS, true ) ) {
			$default_theme_options[] = array(
				'key'               => 'field_5d73ff1959a79',
				'label'             => 'Alternating Row Background Colour',
				'name'              => 'module_background_colour',
				'type'              => 'color_picker',
				'instructions'      => 'Use this option to set the alternating row background colour.',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
			);
			$default_theme_options[] = array(
				'key'               => 'field_ad7ffcc7d96e1',
				'label'             => 'Assign alternating background to Rows ( Odd / Even )',
				'name'              => 'module_background_colour_odd_even',
				'type'              => 'select',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'choices'           => array(
					'odd'  => 'Odd',
					'even' => 'Even',
				),
				'default_value'     => 'odd',
			);
		}

		// Make accent bar optional.
		if ( ! defined( 'EXCLUDE_THEME_ACF_OPTIONALS' ) || ! in_array( 'accent_bar_options', EXCLUDE_THEME_ACF_OPTIONALS, true ) ) {
			$default_theme_options[] = array(
				'key'               => 'field_5dd562e781c79',
				'label'             => 'Accent Bar Options',
				'name'              => 'accent_bar_options',
				'type'              => 'group',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'sub_fields'        => array(
					array(
						'key'               => 'field_ad7ffec7d96e1',
						'label'             => 'Display',
						'name'              => 'display',
						'type'              => 'select',
						'instructions'      => 'Choose what type of accent bar to display. ( Full with Carousel / CTA / Header / Z Pattern modules )',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'choices'           => array(
							'-arrow' => 'With Arrow',
							''       => 'No Arrow',
						),
						'default_value'     => '-arrow',
					),
				),
			);
		}

		// Make chevrons optional.
		if ( ! defined( 'EXCLUDE_THEME_ACF_OPTIONALS' ) || ! in_array( 'chevrons', EXCLUDE_THEME_ACF_OPTIONALS, true ) ) {
			$default_theme_options[] = array(
				'key'               => 'field_ad7ffec7a9631',
				'label'             => 'Chevrons',
				'name'              => 'chevrons',
				'type'              => 'true_false',
				'instructions'      => 'You can enable or disable chevrons on supported modules here.',
				'required'          => 0,
				'ui'                => 1,
				'ui_on_text'        => 'Enabled',
				'ui_off_text'       => 'Disabled',
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => 'on',
			);
		}

		/**
		 * Allow the upload of a default logo for the header/footer.
		 */
		acf_add_local_field_group(
			array(
				'key'                   => 'group_fp_1_default_theming',
				'title'                 => 'Site Theming',
				'fields'                => array(
					array(
						'key'               => 'field_5dd562e781a78',
						'label'             => 'Logo',
						'name'              => 'logo',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'return_format'     => 'url',
						'preview_size'      => 'medium',
						'library'           => 'all',
						'min_width'         => '',
						'min_height'        => '',
						'min_size'          => '',
						'max_width'         => '',
						'max_height'        => '',
						'max_size'          => '',
						'mime_types'        => '',
					),
					array(
						'key'               => 'field_5d7ffb69043f2',
						'label'             => 'Default Theme Options',
						'name'              => 'default_theme_options',
						'type'              => 'group',
						'instructions'      => 'Use these options to set the base/default theme colour options for your site.',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'layout'            => 'block',
						'sub_fields'        => $default_theme_options, // Add the original theme options in here as a series of sub fields.
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			),
		);

		// Component Theming. Used to generated the fields/colour combintations used in components via the fp-colour-picker custom field.
		acf_add_local_field_group(
			array(
				'key'                   => 'group_fp_2_component_options',
				'title'                 => 'Component Theming',
				'fields'                => array(
					array(
						'key'               => 'field_5d6edb67abec4',
						'label'             => 'Component Theme Colours',
						'name'              => 'theme_colours',
						'type'              => 'repeater',
						'instructions'      => 'Add additional theme options to set within components',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'collapsed'         => '',
						'min'               => 0,
						'max'               => 0,
						'layout'            => 'table',
						'button_label'      => 'Add Colour Set',
						'sub_fields'        => array(
							array(
								'key'               => 'field_5d6edb90abec8',
								'label'             => 'Colour Name',
								'name'              => 'colour_name',
								'type'              => 'text',
								'instructions'      => 'Only letters allowed.',
								'required'          => 1,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
								'maxlength'         => '',
							),
							array(
								'key'               => 'field_5d6ee1b09241f',
								'label'             => 'Apply to Elements',
								'name'              => 'applies_to',
								'type'              => 'select',
								'instructions'      => '',
								'required'          => 1,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'choices'           => array(
									'h1'         => 'h1',
									'h2'         => 'h2',
									'h3'         => 'h3',
									'h4'         => 'h4',
									'h5'         => 'h5',
									'h6'         => 'h6',
									'a'          => 'a',
									'background' => 'background',
									'button'     => 'button',
								),
								'default_value'     => array(),
								'allow_null'        => 0,
								'multiple'          => 1,
								'ui'                => 0,
								'return_format'     => 'value',
								'ajax'              => 0,
								'placeholder'       => '',
							),
							array(
								'key'               => 'field_5d6edbd4abecb',
								'label'             => 'Text Colour',
								'name'              => 'text_colour',
								'type'              => 'color_picker',
								'instructions'      => '',
								'required'          => 1,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5d6ee1b09241f',
											'operator' => '==contains',
											'value'    => 'a',
										),
									),
									array(
										array(
											'field'    => 'field_5d6ee1b09241f',
											'operator' => '==contains',
											'value'    => 'button',
										),
									),
									array(
										array(
											'field'    => 'field_5d6ee1b09241f',
											'operator' => '==contains',
											'value'    => 'background',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
							),
							array(
								'key'               => 'field_5d6edba9abec9',
								'label'             => 'Default Colour',
								'name'              => 'default_colour',
								'type'              => 'color_picker',
								'instructions'      => '',
								'required'          => 1,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
							),
							array(
								'key'               => 'field_5dd2f7b0c4eed',
								'label'             => 'Text Hover Colour',
								'name'              => 'text_hover_colour',
								'type'              => 'color_picker',
								'instructions'      => '',
								'required'          => 1,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5d6ee1b09241f',
											'operator' => '==contains',
											'value'    => 'a',
										),
									),
									array(
										array(
											'field'    => 'field_5d6ee1b09241f',
											'operator' => '==contains',
											'value'    => 'button',
										),
									),
									array(
										array(
											'field'    => 'field_5d6ee1b09241f',
											'operator' => '==contains',
											'value'    => 'background',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
							),
							array(
								'key'               => 'field_5d6edbbeabeca',
								'label'             => 'Hover Colour',
								'name'              => 'hover_colour',
								'type'              => 'color_picker',
								'instructions'      => '',
								'required'          => 1,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5d6ee1b09241f',
											'operator' => '==contains',
											'value'    => 'a',
										),
									),
									array(
										array(
											'field'    => 'field_5d6ee1b09241f',
											'operator' => '==contains',
											'value'    => 'button',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
							),
						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options',
						),
					),
				),
				'menu_order'            => 2,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			),
		);

		// Body background colour option.
		if ( defined( 'LOAD_THEME_ACF_OPTIONALS' ) && in_array( 'body_background_colour', LOAD_THEME_ACF_OPTIONALS, true ) ) {
			acf_add_local_field(
				array(
					'key'               => 'field_5ee12098e2955',
					'label'             => 'Body Background Color',
					'name'              => 'body_background_colour',
					'type'              => 'color_picker',
					'instructions'      => 'Set a custom background colour for the document body',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'parent'            => 'group_fp_1_default_theming',
				),
			);
		}
		// Header fonts and sizes options.
		if ( defined( 'LOAD_THEME_ACF_OPTIONALS' ) && in_array( 'heading_font_settings', LOAD_THEME_ACF_OPTIONALS, true ) ) {
			acf_add_local_field_group(
				array(
					'key'                   => 'group_fp_1_heading_options',
					'title'                 => 'Heading Typography Options',
					'fields'                => array(
						array(
							'key'               => 'field_5ee111c8e2946',
							'label'             => 'Heading Font Settings',
							'name'              => 'heading_font_settings',
							'type'              => 'group',
							'instructions'      => 'Set a different font from the body font. (Font-family: font-weight)',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'layout'            => 'block',
							'sub_fields'        => array(
								array(
									'key'               => 'field_5ee11eafe2949',
									'label'             => 'Heading 1 Font Family',
									'name'              => 'heading_1_font_family',
									'type'              => 'select',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11eafe2959',
									'label'             => 'Heading 1 Font Variant',
									'name'              => 'heading_1_font_variants',
									'type'              => 'select',
									'instructions'      => 'Click "Update" to save the chosen font and then choose from the available variants and save again.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11ec5e294a',
									'label'             => 'Heading 1 Font Size',
									'name'              => 'heading_1_font_size',
									'type'              => 'range',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => 36,
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => 'px',
									'min'               => '',
									'max'               => '',
									'step'              => '',
								),
								array(
									'key'               => 'field_5ee11f17e294b',
									'label'             => 'Heading 2 Font Family',
									'name'              => 'heading_2_font_family',
									'type'              => 'select',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11eafe295b',
									'label'             => 'Heading 2 Font Variant',
									'name'              => 'heading_2_font_variants',
									'type'              => 'select',
									'instructions'      => 'Click "Update" to save the chosen font and then choose from the available variants and save again.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11f74e2950',
									'label'             => 'Heading 2 Font Size',
									'name'              => 'heading_2_font_size',
									'type'              => 'range',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => 32,
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => 'px',
									'min'               => '',
									'max'               => '',
									'step'              => '',
								),
								array(
									'key'               => 'field_5ee11f2fe294c',
									'label'             => 'Heading 3 Font Family',
									'name'              => 'heading_3_font_family',
									'type'              => 'select',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11eafe295c',
									'label'             => 'Heading 3 Font Variant',
									'name'              => 'heading_3_font_variants',
									'type'              => 'select',
									'instructions'      => 'Click "Update" to save the chosen font and then choose from the available variants and save again.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11f85e2951',
									'label'             => 'Heading 3 Font Size',
									'name'              => 'heading_3_font_size',
									'type'              => 'range',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => 28,
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => 'px',
									'min'               => '',
									'max'               => '',
									'step'              => '',
								),
								array(
									'key'               => 'field_5ee11f3de294d',
									'label'             => 'Heading 4 Font Family',
									'name'              => 'heading_4_font_family',
									'type'              => 'select',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11eafe295d',
									'label'             => 'Heading 4 Font Variant',
									'name'              => 'heading_4_font_variants',
									'type'              => 'select',
									'instructions'      => 'Click "Update" to save the chosen font and then choose from the available variants and save again.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11f95e2952',
									'label'             => 'Heading 4 Font Size',
									'name'              => 'heading_4_font_size',
									'type'              => 'range',
									'instructions'      => 'Click "Update" to save the chosen font and then choose from the available variants and save again.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => 24,
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => 'px',
									'min'               => '',
									'max'               => '',
									'step'              => '',
								),
								array(
									'key'               => 'field_5ee11f4ee294e',
									'label'             => 'Heading 5 Font Family',
									'name'              => 'heading_5_font_family',
									'type'              => 'select',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11eafe295e',
									'label'             => 'Heading 5 Font Variant',
									'name'              => 'heading_5_font_variants',
									'type'              => 'select',
									'instructions'      => 'Click "Update" to save the chosen font and then choose from the available variants and save again.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11fa3e2953',
									'label'             => 'Heading 5 Font Size',
									'name'              => 'heading_5_font_size',
									'type'              => 'range',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => 22,
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => 'px',
									'min'               => '',
									'max'               => '',
									'step'              => '',
								),
								array(
									'key'               => 'field_5ee11f5ee294f',
									'label'             => 'Heading 6 Font Family',
									'name'              => 'heading_6_font_family',
									'type'              => 'select',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11eafe295f',
									'label'             => 'Heading 6 Font Variant',
									'name'              => 'heading_6_font_variants',
									'type'              => 'select',
									'instructions'      => 'Click "Update" to save the chosen font and then choose from the available variants and save again.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(),
									'default_value'     => '',
								),
								array(
									'key'               => 'field_5ee11fb3e2954',
									'label'             => 'Heading 6 Font Size',
									'name'              => 'heading_6_font_size',
									'type'              => 'range',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => 18,
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => 'px',
									'min'               => '',
									'max'               => '',
									'step'              => '',
									'parent'            => 'group_fp_1_default_theming',
								),
							),
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'options_page',
								'operator' => '==',
								'value'    => 'acf-options',
							),
						),
					),
					'menu_order'            => 1,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				),
			);
		}
	endif;
}

/**
 * Create an ACF Field to assign icons to cateogories, tags and navigation menu items.
 */
function generate_foundation_taxonomy_fields() {
	if ( function_exists( 'acf_add_local_field_group' ) ) :

		acf_add_local_field_group(
			array(
				'key'                   => 'group_5f315bb8f157b',
				'title'                 => 'Icon Select',
				'fields'                => array(
					array(
						'key'               => 'field_5f315be48d825',
						'label'             => 'Choose an Icon',
						'name'              => 'taxonomy_icon',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'choices'           => array(),
						'default_value'     => false,
						'allow_null'        => 1,
						'multiple'          => 0,
						'ui'                => 0,
						'return_format'     => 'value',
						'ajax'              => 0,
						'placeholder'       => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'taxonomy',
							'operator' => '==',
							'value'    => 'all',
						),
					),
					array(
						array(
							'param'    => 'nav_menu_item',
							'operator' => '==',
							'value'    => 'all',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			),
		);

	endif;
}
