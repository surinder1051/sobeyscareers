<div <?php $this->component_class() ?> data-js-animated_images>
	<?php if (!empty($left_image)) : ?>
	<?php $left_image_src = wp_get_attachment_image_src( $left_image, $left_image_size );
	$left_image_id = get_post($left_image);
	$left_image_alt = get_post_meta($left_image, '_wp_attachment_image_alt', true);
	$left_image_title = $left_image_id->post_title; ?>
	<img class="left_img_anim rotateInDownRightAnim_1 non_display_view animated animation-element" src="<?php echo $left_image_src[0] ?>" alt="<?php echo $left_image_alt ?>" title="<?php echo $left_image_title ?>"/>
	<?php endif; ?>

	<?php if (!empty($static_image)) : ?>
	<?php $static_image_src = wp_get_attachment_image_src( $static_image, $static_image_size );
	$static_image_id = get_post($static_image);
	$static_image_alt = get_post_meta($static_image, '_wp_attachment_image_alt', true);
	$static_image_title = $static_image_id->post_title; ?>
	<img class="static_img" src="<?php echo $static_image_src[0] ?>" alt="<?php echo $static_image_alt ?>" title="<?php echo $static_image_title ?>"/>
	<?php endif; ?>

	<?php if (!empty($right_image)) : ?>
	<?php $right_image_src = wp_get_attachment_image_src( $right_image, $right_image_size );
	$right_image_id = get_post($right_image);
	$right_image_alt = get_post_meta($right_image, '_wp_attachment_image_alt', true);
	$right_image_title = $right_image_id->post_title; ?>
	<img class="right_img_anim rotateInDownLeftAnim_1 non_display_view animated animation-element" src="<?php echo $right_image_src[0] ?>" alt="<?php echo $right_image_alt ?>" title="<?php echo $right_image_title ?>"/>
	<?php endif; ?>
</div>