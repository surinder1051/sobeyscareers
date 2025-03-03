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
*/

<?php

// To use a active theme that can be updated via Options page for a XXX field you need to generate it at runtime.
// element can be ('element'     => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',)
// $settings->field_key = generate_theme($settings->field_key, element);

FLBuilderCSS::typography_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'slides_heading_typography',
	'selector'     => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-body .card-title",
));

FLBuilderCSS::typography_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'slides_description_typography',
	'selector'     => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-body .card-description"
));

if (!empty($settings->slide_dots_theme)) {
	$settings->slide_dots_theme = generate_theme($settings->slide_dots_theme, 'background');
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .slick-dots li.slick-active button",
		'enable'   => !empty($settings->slide_dots_theme->default_colour),
		'props'    => array(
			'background-color' => str_replace('#', '', $settings->slide_dots_theme->default_colour),
		),
	));
}

if (!empty($settings->slide_overlay_theme)) {
	$overlayColour = FLBuilderColor::hex_to_rgb($settings->slide_overlay_theme);
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .card-overlay",
		'props'    => array(
			'background-color' => 'rgba(' . $overlayColour['r'] . ', ' . $overlayColour['g'] . ', ' . $overlayColour['b'] . ', .8)',
		),
	));
}

if (!empty($settings->slide_padding_top || $settings->slide_padding_right || $settings->slide_padding_bottom || $settings->slide_padding_left)) {
	FLBuilderCSS::dimension_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'slide_padding',
		'selector'     => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .slick-slide",
		'props'        => array(
			'padding-top'    => 'slide_padding_top',
			'padding-right'  => 'slide_padding_right',
			'padding-bottom' => 'slide_padding_bottom',
			'padding-left'   => 'slide_padding_left',
		),
	));

	if ($settings->slide_layout == '-title-card') {
		FLBuilderCSS::dimension_field_rule(array(
			'settings'     => $settings,
			'setting_name' => 'slide_padding',
			'selector'     => ".fl-node-$id .component_multi_item_carousel .carousel-header",
			'props'        => array(
				'padding-top'    => 'slide_padding_top',
				'padding-right'  => 'slide_padding_right',
				'padding-bottom' => 'slide_padding_bottom',
				'padding-left'   => 'slide_padding_left',
			),
		));
	}
}

if (!empty($settings->slide_border)) {
	FLBuilderCSS::border_field_rule(array(
		'settings'  => $settings,
		'setting_name'  => 'slide_border',
		'selector'  => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card",
	));
}

if (!empty($settings->slide_image_padding_top || $settings->slide_image_padding_right || $settings->slide_image_padding_bottom || $settings->slide_image_padding_left)) {
	FLBuilderCSS::dimension_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'slide_image_padding',
		'selector'     => ".fl-node-$id .component-content-wrapper .carousel-item .card .card-image-wrap",
		'props'        => array(
			'padding-top'    => 'slide_image_padding_top',
			'padding-right'  => 'slide_image_padding_right',
			'padding-bottom' => 'slide_image_padding_bottom',
			'padding-left'   => 'slide_image_padding_left',
		),
	));
}

if (!empty($settings->slide_image_border)) {
	FLBuilderCSS::border_field_rule(array(
		'settings'  => $settings,
		'setting_name'  => 'slide_image_border',
		'selector'  => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-image-wrap",
	));
}

if (!empty($settings->slide_content_padding_top || $settings->slide_content_padding_right || $settings->slide_content_padding_bottom || $settings->slide_content_padding_left)) {
	FLBuilderCSS::dimension_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'slide_content_padding',
		'selector'     => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-body",
		'props'        => array(
			'padding-top'    => 'slide_content_padding_top',
			'padding-right'  => 'slide_content_padding_right',
			'padding-bottom' => 'slide_content_padding_bottom',
			'padding-left'   => 'slide_content_padding_left',
		),
	));
}

if (!empty($settings->slide_content_border)) {
	FLBuilderCSS::border_field_rule(array(
		'settings'  => $settings,
		'setting_name'  => 'slide_content_border',
		'selector'  => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-body",
	));
}

if (!empty($settings->slide_title_padding_top || $settings->slide_title_padding_right || $settings->slide_title_padding_bottom || $settings->slide_title_padding_left)) {
	FLBuilderCSS::dimension_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'slide_title_padding',
		'selector'     => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-body .card-title",
		'props'        => array(
			'padding-top'    => 'slide_title_padding_top',
			'padding-right'  => 'slide_title_padding_right',
			'padding-bottom' => 'slide_title_padding_bottom',
			'padding-left'   => 'slide_title_padding_left',
		),
	));
}

if (!empty($settings->slide_title_border)) {
	FLBuilderCSS::border_field_rule(array(
		'settings'  => $settings,
		'setting_name'  => 'slide_title_border',
		'selector'  => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-body .card-title",
	));
}

if (!empty($settings->slide_description_padding_top || $settings->slide_description_padding_right || $settings->slide_description_padding_bottom || $settings->slide_description_padding_left)) {
	FLBuilderCSS::dimension_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'slide_description_padding',
		'selector'     => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-body .card-description",
		'props'        => array(
			'padding-top'    => 'slide_description_padding_top',
			'padding-right'  => 'slide_description_padding_right',
			'padding-bottom' => 'slide_description_padding_bottom',
			'padding-left'   => 'slide_description_padding_left',
		),
	));
}

if (!empty($settings->slide_description_border)) {
	FLBuilderCSS::border_field_rule(array(
		'settings'  => $settings,
		'setting_name'  => 'slide_description_border',
		'selector'  => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-body .card-description",
	));
}

if ($settings->enable_dots == 1 && $settings->round_dots == 1) {
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .slick-dots li button",
		'props'    => array(
			'width'         => '15px',
			'height'        => '15px',
			'border'        => '1px solid #DDD',
			'border-radius' => '50%',
			'padding'       => '0',
		),
	));
}

if ($settings->slide_image_fit == 'center') {
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-image-wrap",
		'props'    => array(
			'height'    => 'auto',
		),
	));

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_multi_item_carousel .component-content-wrapper .carousel-item .card .card-image-wrap .card-img-top",
		'props'    => array(
			'height'    => 'auto',
			'width'     => 'auto',
			'max-width' => '100%',
			'margin'    => '0 auto',
		),
	));
}
