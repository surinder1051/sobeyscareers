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

if (!empty($settings->title_color)) {
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_social_links .title",
		'props'    => array(
			'color' => $settings->title_color,
		),
	));
}

if (!empty($settings->title_typography)) {
	FLBuilderCSS::typography_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .component_social_links .title",
	));
}

if (!empty($settings->link_size)) {
	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'link_size',
		'selector'     => ".fl-node-$id .component_social_links .social-link",
		'prop'         => 'height',
	));

	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'link_size',
		'selector'     => ".fl-node-$id .component_social_links .social-link",
		'prop'         => 'width',
	));

	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'link_size',
		'selector'     => ".fl-node-$id .component_social_links .social-link",
		'prop'         => 'line-height',
	));
}

if (!empty($settings->link_margin_top || $settings->link_margin_right || $settings->link_margin_bottom || $settings->link_margin_left)) {
	FLBuilderCSS::dimension_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'link_margin',
		'selector'     => ".fl-node-$id .component_social_links .social-link",
		'props'        => array(
			'margin-top'    => 'link_margin_top',
			'margin-right'  => 'link_margin_right',
			'margin-bottom' => 'link_margin_bottom',
			'margin-left'   => 'link_margin_left',
		),
	));
}

if (!empty($settings->link_theme)) {
	$settings->link_theme = generate_theme($settings->link_theme, 'background');
	$default_colour = str_replace('#', '', $settings->link_theme->default_colour);
	$hover_colour = str_replace('#', '', $settings->link_theme->hover_colour);
	$text_colour = str_replace('#', '', $settings->link_theme->text_colour);
	$text_hover_colour = str_replace('#', '', $settings->link_theme->text_hover_colour);

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_social_links .social-link",
		'props'    => array(
			'color' => $text_colour,
			'background-color' => $default_colour,
		),
	));

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_social_links .social-link:hover",
		'props'    => array(
			'color' => $text_hover_colour,
			'background-color' => $hover_colour,
		),
	));
}

if (!empty($settings->icon_size)) {
	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'icon_size',
		'selector'     => ".fl-node-$id .component_social_links .social-link",
		'prop'         => 'font-size',
	));
}

foreach ($settings->social_links as $social_link) {
	$icon_class = str_replace(' ', '.', $social_link->icon);

	if (!empty($social_link->icon_font_line_height)) {
		FLBuilderCSS::responsive_rule(array(
			'settings'     => $social_link,
			'setting_name' => 'icon_font_line_height',
			'selector'     => ".fl-node-$id .component_social_links .social-link.$icon_class",
			'prop'         => 'line-height',
		));
	}

	if (!empty($social_link->icon_font_size)) {
		FLBuilderCSS::responsive_rule(array(
			'settings'     => $social_link,
			'setting_name' => 'icon_font_size',
			'selector'     => ".fl-node-$id .component_social_links .social-icon.$icon_class",
			'prop'         => 'font-size',
		));
	}
}
