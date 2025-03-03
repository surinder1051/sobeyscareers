<?php

if (function_exists('acf_add_local_field_group')) :

	acf_add_local_field_group(array(
		'key' => 'group_5f08601165fe5',
		'title' => 'Taxonomy - Imagery',
		'fields' => array(
			array(
				'key' => 'field_5f08602bbce1f',
				'label' => 'Icon',
				'name' => 'icon',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
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
				'max_width' => 150,
				'max_height' => 150,
				'max_size' => '.1',
				'mime_types' => 'png',
			),
			array(
				'key' => 'field_5f0860a0bce20',
				'label' => 'Banner',
				'name' => 'banner',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'preview_size' => 'medium',
				'library' => 'all',
				'min_width' => apply_filters('acf-taxonomy-banner-min-width', 600),
				'min_height' => apply_filters('acf-taxonomy-banner-min-height', 200),
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '.3',
				'mime_types' => 'jpg,jpeg',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'article-category',
				),
			),
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'recipe-tag',
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
	));

endif;