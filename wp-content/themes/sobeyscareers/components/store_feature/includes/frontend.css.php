/**
* This file should contain frontend styles that
* will be applied to individual module instances.
*
* You have access to three variables in this file:
*
* $module An instance of your module class.
* $id The module's ID.
* $settings The module's settings.
*
* Note: When used from beaver builder, a cached version of this file will be
* crated that's unique to the instance in the /uploads/bb-plugin/cache/
* ,however when used by a regular shortcode an inline style will in turn be
* generated and put on the page where it's been used, no cached file will be
* created.
*
* Example:


theme:"default"
title:"Sensory-Friendly Shopping"
title_tag:"h2"
title_align:"center"
subtitle:"Every Wednesday, 6-8pm"
subtitle_align:"center"
button_text:""
background_color:"48A647"
background_image:""
background_image_src:""
background_image_pos:"left"
background_image_width:"50"
font_color:"ffffff"
cta_link:"https://www.google.com"
feature_points_list:array(3)
responsive_display:""
visibility_display:""
visibility_user_capability:""
visibility_logic:array(0)
animation:array(3)
container_element:"div"

*/

<?php

// To use a active theme that can be updated via Options page for a XXX field you need to generate it at runtime.
// element can be ('element'     => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',)
// $settings->field_key = generate_theme($settings->field_key, element);

?>

.fl-node-<?php echo $id; ?> .component_store_feature .-theme-default {
<?php if ($settings->background_color) : ?>
	background-color: #<?php echo $settings->background_color; ?>;
<?php endif; ?>

<?php if ($settings->theme == 'default') : ?>
	color: #<?php echo $settings->font_color ?>;
<?php endif; ?>
}

<?php if ($settings->background_color) : ?>
	.fl-node-<?php echo $id; ?> .-theme-image .feature-image-wrapper {
	background-color: #<?php echo $settings->background_color; ?>;
	}
<?php endif; ?>

<?php if ($settings->background_image_width) : ?>
	.fl-node-<?php echo $id; ?> .-theme-image .feature-image-wrapper {
	height: 100%;
	}
	.fl-node-<?php echo $id; ?> .-theme-image .feature-image-wrapper img {
	width: <?php echo $settings->background_image_width; ?>% !important;
	height: 100%;
	}
<?php endif; ?>

.fl-node-<?php echo $id; ?> .-theme-image.image-pos-full .feature-image-wrapper img {
<?php if ($settings->background_image_width) : ?>
	width: 100% !important;
<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .-theme-image.image-pos-full .feature-summary {
<?php if ($settings->background_color) : ?>
	background-color: transparent !important;
<?php endif; ?>

<?php if ($settings->font_color) : ?>
	color: #<?php echo $settings->font_color ?> !important;
<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .-theme-image .feature-summary {
<?php if ($settings->background_color) : ?>
	background-color: #<?php echo $settings->background_color; ?> !important;
<?php endif; ?>

<?php if ($settings->font_color) : ?>
	color: #<?php echo $settings->font_color ?> !important;
<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .-theme-image.image-pos-left .feature-summary {

<?php if ($settings->background_image_width) : ?>
	<?php $remaining_width = 100 - intval($settings->background_image_width); ?>
	width: <?php echo $remaining_width; ?>% !important;
<?php endif; ?>

<?php if ($settings->background_image_width) : ?>
	left: <?php echo $settings->background_image_width; ?>% !important;
<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .-theme-image.image-pos-right .feature-summary {

<?php if ($settings->background_image_width) : ?>
	<?php $remaining_width = 100 - intval($settings->background_image_width); ?>
	width: <?php echo $remaining_width; ?>% !important;
<?php endif; ?>

<?php if ($settings->background_image_width) : ?>
	right: <?php echo $settings->background_image_width; ?>% !important;
	left: 0;
<?php endif; ?>
}


.fl-node-<?php echo $id; ?> .-theme-image .feature-summary .feature-cta-button {
<?php if ($settings->background_color) : ?>
	color: #<?php echo $settings->background_color; ?> !important;
<?php endif; ?>
}

<?php
FLBuilderCSS::typography_field_rule(array(
	'settings'	=> $settings,
	'setting_name' 	=> 'title_typography',
	'selector' 	=> ".fl-node-$id .title",
));
?>

<?php
FLBuilderCSS::typography_field_rule(array(
	'settings'	=> $settings,
	'setting_name' 	=> 'subtitle_typography',
	'selector' 	=> ".fl-node-$id .subtitle",
));
?>

<?php
FLBuilderCSS::typography_field_rule(array(
	'settings'	=> $settings,
	'setting_name' 	=> 'feature_typography',
	'selector' 	=> ".fl-node-$id .component_store_feature .-theme-default .feature-list .feature-item-wrapper .feature-item .feature-text",
));
?>

<?php
FLBuilderCSS::typography_field_rule(array(
	'settings'	=> $settings,
	'setting_name' 	=> 'feature_icon_typography',
	'selector' 	=> ".fl-node-$id .component_store_feature .-theme-default .feature-list .feature-item-wrapper .feature-item .feature-icon:before",
));
?>

<?php
FLBuilderCSS::typography_field_rule(array(
	'settings'	=> $settings,
	'setting_name' 	=> 'button_typography',
	'selector' 	=> ".fl-node-$id .-theme-image .feature-cta-button",
));
?>