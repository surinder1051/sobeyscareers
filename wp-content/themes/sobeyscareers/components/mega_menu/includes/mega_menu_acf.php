<?php
add_filter('acf/load_field/name=flyout_theme', 'mega_menu_acf_select_theme');
add_action('admin_head', 'mega_menu_acf_theme_style', 10, 2);

//generate inline styling for the ACF icon select field
function mega_menu_acf_theme_style() {
	general_global_theme_list();
	global $fp_themes;
	$themes = get_transient('theme_colours', $fp_themes);
	$backgrounds = array();

	if (!empty($themes)) {
		foreach( $themes as $key => $theme ) {
			if (strstr($key, 'background')) {
				$backgrounds[] = array($key, $theme['default_colour'], $theme['text_colour']);
			}
		}
	}

	if (!empty($backgrounds)) {
		echo '<style type="text/css">';
		echo '[data-name="flyout_theme"] .acf-radio-list label{display:inline-block;padding: 10px 5px;min-width:150px;position:relative;width:40%;}';
		echo '[data-name="flyout_theme"] .acf-radio-list label.selected::after{content:"*";}';
		echo '[data-name="flyout_theme"] .acf-radio-list label::before{content:"";display:block;height: }';
		echo '[data-name="flyout_theme"] .acf-input {height: 84px;overflow-x:hidden;overflow-y:scroll;max-width:154px}';
		echo '</style>';
		echo '<script type="text/javascript">';
		echo 'jQuery(document).ready(function($) {';
		echo 'if ($("div").find("[data-name=\'flyout_theme\']") ) {';
		echo 'var flyoutTheme = $("div").find("[data-name=\'flyout_theme\']");';
		echo 'var flyoutBackgrounds = ' . json_encode($backgrounds) . ';';
		echo 'if (typeof acf == \'object\') {';
		echo 'acf.addAction(\'show_field/name=flyout_theme\', function( field ) {' . "\r\n";
		echo 'var themeField = field.$el;' . "\r\n" ;
		echo 'console.log($(\'input\', themeField).length);' . "\r\n" ;
		echo 'if (!themeField.hasClass(\'mm-styled\') ) { ' . "\r\n" ;
			echo 'for (var i = 0; i < flyoutBackgrounds.length; i++) {' . "\r\n";
			echo 'var flLabel = $(\'input[value="\' + flyoutBackgrounds[i][0] + \'"]\', themeField).closest(\'label\');' . "\r\n";
			echo '$(flLabel).css({\'background\': flyoutBackgrounds[i][1] });' . "\r\n";
			echo '$(flLabel).css({\'color\': flyoutBackgrounds[i][2] });' . "\r\n";
			echo '}'; //end for
		echo '}'; //end is flyout theme styled
		echo '});'; //end acf show
		echo 'acf.addAction(\'load_field/name=flyout_theme\', function( field ) {' . "\r\n";
			echo 'var themeField = field.$el;' . "\r\n" ;
			echo 'console.log($(\'input\', themeField).length);' . "\r\n" ;
			echo 'if (!themeField.hasClass(\'mm-styled\') ) { ' . "\r\n" ;
				echo 'for (var i = 0; i < flyoutBackgrounds.length; i++) {' . "\r\n";
				echo 'var flLabel = $(\'input[value="\' + flyoutBackgrounds[i][0] + \'"]\', themeField).closest(\'label\');' . "\r\n";
				echo '$(flLabel).css({\'background\': flyoutBackgrounds[i][1] });' . "\r\n";
				echo '$(flLabel).css({\'color\': flyoutBackgrounds[i][2] });' . "\r\n";
				echo '}'; //end for
			echo '}'; //end is flyout theme styled
		echo '});'; //end acf field load
		echo '}'; //end acf loaded
		echo '}'; //end if flyout_theme exists
		echo '});';
		echo '</script>';
	}

}
function mega_menu_acf_select_theme( $field ) {


	general_global_theme_list();
	global $fp_themes;
	$themes = get_transient('theme_colours', $fp_themes);

	if (!empty($themes)) {
		foreach( $themes as $key => $theme ) {
			if (strstr($key, 'background')) {
				$field['choices'][ $key ] = $theme['colour_name'];
			}
		}
	}

	return $field;
}


if (function_exists('acf_add_local_field_group')) :

	acf_add_local_field_group(array(
		'key' => 'group_5d1df4ba28d00',
		'title' => 'Mega Menu',
		'fields' => array(
			array(
				'key' => 'field_601c2641fc821',
				'label' => 'Show Flyout Content',
				'name' => 'show_flyout_content',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => 'Show Custom Flyout Content on Hover',
				'default_value' => 1,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_601c268dfc822',
				'label' => 'Flyout Content',
				'name' => 'flyout_content',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_601c2641fc821',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'custom' => 'Custom Content',
					'recipe' => 'Recipe',
				),
				'default_value' => 'custom',
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_601c273efc823',
				'label' => 'Choose Recipe',
				'name' => 'recipe_flyout',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_601c2641fc821',
							'operator' => '==',
							'value' => '1',
						),
						array(
							'field' => 'field_601c268dfc822',
							'operator' => '==',
							'value' => 'recipe',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array(
					0 => 'recipe',
				),
				'taxonomy' => '',
				'allow_null' => 0,
				'multiple' => 0,
				'return_format' => 'object',
				'ui' => 1,
			),
			array(
				'key' => 'field_601c277dfc826',
				'label' => 'Choose Theme',
				'name' => 'flyout_theme',
				'type' => 'radio',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_601c2641fc821',
							'operator' => '==',
							'value' => '1',
						),
						array(
							'field' => 'field_601c268dfc822',
							'operator' => '!=',
							'value' => 'custom',
						)
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_5d1df4cdcfa39',
				'label' => 'Image',
				'name' => 'image',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5d1e07b077fcb',
							'operator' => '==empty',
						),
						array(
							'field' => 'field_601c268dfc822',
							'operator' => '==',
							'value' => 'custom',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array(
				'key' => 'field_5d1e07b077fcb',
				'label' => 'Link to Content',
				'name' => 'link_to_content',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						'field' => 'field_601c2641fc821',
						'operator' => '==',
						'value' => '1',
					),
					array(
						'field' => 'field_601c268dfc822',
						'operator' => '==',
						'value' => 'custom',
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => '',
				'taxonomy' => '',
				'allow_null' => 1,
				'multiple' => 0,
				'return_format' => 'object',
				'ui' => 1,
			),
			array(
				'key' => 'field_601c277dfc825',
				'label' => 'Show Post Date',
				'name' => 'post_date',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_601c2641fc821',
							'operator' => '==',
							'value' => '1',
						),
						array(
							'field' => 'field_601c268dfc822',
							'operator' => '==',
							'value' => 'custom',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 0,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_5d1e075277fca',
				'label' => 'Image Cover',
				'name' => 'image_cover',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						'field' => 'field_601c2641fc821',
						'operator' => '==',
						'value' => '1',
					),
					array(
						'field' => 'field_601c268dfc822',
						'operator' => '==',
						'value' => 'custom',
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'Cover Background' => 'Cover Background',
					'Top' => 'Top',
				),
				'default_value' => array(),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_5d1e072177fc6',
				'label' => 'Title',
				'name' => 'title',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5d1e07b077fcb',
							'operator' => '==empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5dba711df670f',
				'label' => 'Heading',
				'name' => 'heading',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						'field' => 'field_601c2641fc821',
						'operator' => '==',
						'value' => '1',
					),
					array(
						'field' => 'field_601c268dfc822',
						'operator' => '==',
						'value' => 'custom',
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5d1e072b77fc7',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						'field' => 'field_601c2641fc821',
						'operator' => '==',
						'value' => '1',
					),
					array(
						'field' => 'field_601c268dfc822',
						'operator' => '==',
						'value' => 'custom',
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'new_lines' => '',
			),
			array(
				'key' => 'field_5d1e073c77fc8',
				'label' => 'Link Title',
				'name' => 'link_title',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						'field' => 'field_601c2641fc821',
						'operator' => '==',
						'value' => '1',
					),
					array(
						'field' => 'field_601c268dfc822',
						'operator' => '==',
						'value' => 'custom',
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5d1e074377fc9',
				'label' => 'Learn More URL',
				'name' => 'learn_more_url',
				'type' => 'url',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5d1e07b077fcb',
							'operator' => '==empty',
						),
						array(
							'field' => 'field_601c268dfc822',
							'operator' => '==',
							'value' => 'custom',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
			),
			array(
				'key' => 'field_5d1e2b13f4d31',
				'label' => 'Background Color',
				'name' => 'background_color',
				'type' => 'color_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5d1e075277fca',
							'operator' => '==',
							'value' => 'Top',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '#8E1238',
			),
			array(
				'key' => 'field_6d28762ba44e5',
				'label' => 'Heading Color',
				'name' => 'heading_color',
				'type' => 'color_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5d1e075277fca',
							'operator' => '==',
							'value' => 'Top',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_5d28762ba44e4',
				'label' => 'Text Color',
				'name' => 'text_color',
				'type' => 'color_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5d1e075277fca',
							'operator' => '==',
							'value' => 'Top',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_5ebbccdf90dba',
				'label' => 'Header',
				'name' => 'header',
				'type' => 'group',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						'field' => 'field_601c2641fc821',
						'operator' => '==',
						'value' => '1',
					),
					array(
						'field' => 'field_601c268dfc822',
						'operator' => '==',
						'value' => 'custom',
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'layout' => 'table',
				'sub_fields' => array(
					array(
						'key' => 'field_5ebbcc9990db7',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								'field' => 'field_601c2641fc821',
								'operator' => '==',
								'value' => '1',
							),
							array(
								'field' => 'field_601c268dfc822',
								'operator' => '==',
								'value' => 'custom',
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5ebbccab90db8',
						'label' => 'Button Label',
						'name' => 'button_label',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								'field' => 'field_601c2641fc821',
								'operator' => '==',
								'value' => '1',
							),
							array(
								'field' => 'field_601c268dfc822',
								'operator' => '==',
								'value' => 'custom',
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5ebbccd290db9',
						'label' => 'Button URL',
						'name' => 'button_url',
						'type' => 'url',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								'field' => 'field_601c2641fc821',
								'operator' => '==',
								'value' => '1',
							),
							array(
								'field' => 'field_601c268dfc822',
								'operator' => '==',
								'value' => 'custom',
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'nav_menu_item',
					'operator' => '==',
					'value' => 'location/primary',
				),
			),
			array(
				array(
					'param' => 'nav_menu_item',
					'operator' => '==',
					'value' => 'location/primary-alt',
				),
			),
			array(
				array(
					'param' => 'nav_menu_item',
					'operator' => '==',
					'value' => 'location/primary___fr',
				),
			),
			array(
				array(
					'param' => 'nav_menu_item',
					'operator' => '==',
					'value' => 'location/primary-alt__fr',
				),
			),
			array(
				array(
					'param' => 'nav_menu_item',
					'operator' => '==',
					'value' => 'location/primary___en',
				),
			),
			array(
				array(
					'param' => 'nav_menu_item',
					'operator' => '==',
					'value' => 'location/primary-alt__en',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'modified' => 1589378599,
	));

endif;
