<?php

// if (function_exists('acf_add_local_field_group')) :

// 	acf_add_local_field_group(array(
// 		'key' => 'group_5e96f7de4d623',
// 		'title' => 'Related',
// 		'fields' => array(
// 			array(
// 				'key' => 'field_5e96f7e11bc8d',
// 				'label' => 'Related Recipes',
// 				'name' => 'related_recipes',
// 				'type' => 'relationship',
// 				'instructions' => '',
// 				'required' => 0,
// 				'readonly' => 1,
// 				'conditional_logic' => 0,
// 				'wrapper' => array(
// 					'width' => '',
// 					'class' => '',
// 					'id' => '',
// 				),
// 				'post_type' => array(
// 					0 => 'recipe',
// 				),
// 				'taxonomy' => '',
// 				'filters' => array(
// 					0 => 'search',
// 					1 => 'post_type',
// 					2 => 'taxonomy',
// 				),
// 				'elements' => array(
// 					0 => 'featured_image',
// 				),
// 				'min' => '',
// 				'max' => '',
// 				'return_format' => 'object',
// 			),
// 			array(
// 				'key' => 'field_5e96f82238f8f',
// 				'label' => 'Related Products',
// 				'name' => 'related_products',
// 				'type' => 'relationship',
// 				'instructions' => '',
// 				'required' => 0,
// 				// 'readonly' => 1,
// 				'conditional_logic' => 0,
// 				'wrapper' => array(
// 					'width' => '',
// 					'class' => '',
// 					'id' => '',
// 				),
// 				'post_type' => array(
// 					0 => 'product',
// 				),
// 				'taxonomy' => '',
// 				'filters' => array(
// 					0 => 'search',
// 					1 => 'post_type',
// 					2 => 'taxonomy',
// 				),
// 				'elements' => array(
// 					0 => 'featured_image',
// 				),
// 				'min' => '',
// 				'max' => '',
// 				'return_format' => 'object',
// 			),
// 		),
// 		'location' => array(
// 			array(
// 				array(
// 					'param' => 'post_type',
// 					'operator' => '==',
// 					'value' => 'easy-meal',
// 				),
// 			),
// 			array(
// 				array(
// 					'param' => 'post_type',
// 					'operator' => '==',
// 					'value' => 'page',
// 				),
// 			),
// 			array(
// 				array(
// 					'param' => 'post_type',
// 					'operator' => '==',
// 					'value' => 'article',
// 				),
// 			),
// 		),
// 		'menu_order' => 0,
// 		'position' => 'normal',
// 		'style' => 'default',
// 		'label_placement' => 'top',
// 		'instruction_placement' => 'label',
// 		'hide_on_screen' => '',
// 		'active' => true,
// 		'description' => '',
// 		'modified' => 1587580649,
// 	));

// endif;


// // add_filter('acf/prepare_field/type=relationship', function ($field) {
// // 	// check to see if this is a read only relationship field
// // 	// the value is an array of post_ids
// // 	$post_ids = $field['value'];
// // 	// render them as a list
// // 	echo "<ul style='margin-left:5px;'>";
// // 	if ($post_ids) {
// // 		foreach ($post_ids as $post_id) {
// // 			$post = get_post($post_id);
// // 			$edit_link = get_edit_post_link($post_id);
// // 			echo "<li><a href='$edit_link'>" . $post->post_title . "</a></li>";
// // 		}
// // 	}
// // 	echo "</ul>";
// // 	// return false to stop default rendering
// // 	return false;
// // });
