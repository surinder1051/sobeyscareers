<?php

if (function_exists('acf_add_local_field_group')) :

	acf_add_local_field_group(array(
		'key' => 'group_5eabf0acc3168',
		'title' => 'Disable Default Header / Footer',
		'fields' => array(
			array(
				'key' => 'field_5eabf0b8e1461',
				'label' => 'Disable Default Header / Footer',
				'name' => 'disable_default_header__footer',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'side',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));

endif;
