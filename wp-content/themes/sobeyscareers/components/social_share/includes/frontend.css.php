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
lalala
*/

<?php

$settings->theme = generate_theme($settings->theme, 'button');
if (!empty($settings->theme)) {
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_social_share .social-share .social-icon",
		'enable'   => !empty($settings->theme->default_colour),
		'props'    => array(
			'background-color' => str_replace('#', '', $settings->theme->default_colour),
			'border-color' => str_replace('#', '', $settings->theme->default_colour),
		),
	));

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_social_share .social-share .social-icon",
		'enable'   => !empty($settings->theme->text_colour),
		'props'    => array(
			'color' => str_replace('#', '', $settings->theme->text_colour),
		),
	));

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_social_share .social-share:hover .social-icon",
		'enable'   => !empty($settings->theme->hover_colour),
		'props'    => array(
			'background-color' => str_replace('#', '', $settings->theme->hover_colour),
			'border-color' => str_replace('#', '', $settings->theme->hover_colour),
		),
	));

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_social_share .social-share:hover .social-icon",
		'enable'   => !empty($settings->theme->text_hover_colour),
		'props'    => array(
			'color' => str_replace('#', '', $settings->theme->text_hover_colour),
		),
	));
}

fp_apply_style($id, '.title', 'color', $settings->title_color);

if (!empty($settings->title_typography)) {
	FLBuilderCSS::typography_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .component_social_share .title",
	));
}

if (!empty($settings->padding_top) || !empty($settings->padding_right) || !empty($settings->padding_bottom) || !empty($settings->padding_left)) {
	FLBuilderCSS::dimension_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'padding',
		'selector'     => ".fl-node-$id.fl-module-social_share .component_social_share",
		'props'        => array(
			'padding-top'    => 'padding_top',
			'padding-right'  => 'padding_right',
			'padding-bottom' => 'padding_bottom',
			'padding-left'   => 'padding_left',
		),
	));
}

if (!empty($settings->border)) {
	FLBuilderCSS::border_field_rule(array(
		'settings'  => $settings,
		'setting_name'  => 'border',
		'selector'  => ".fl-node-$id.fl-module-social_share .component_social_share",
	));
}

if (!empty($settings->button_size)) {
	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'button_size',
		'selector'     => ".fl-node-$id .component_social_share .social-share .social-icon",
		'prop'         => 'height',
	));

	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'button_size',
		'selector'     => ".fl-node-$id .component_social_share .social-share .social-icon",
		'prop'         => 'width',
	));

	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'button_size',
		'selector'     => ".fl-node-$id .component_social_share .social-share .social-icon::before",
		'prop'         => 'line-height',
	));

	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'button_size',
		'selector'     => ".fl-node-$id .component_social_share.-show-print-option .social-share.print .social-icon::before",
		'prop'         => 'font-size',
	));
}

if (!empty($settings->button_spacing)) {
	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'button_spacing',
		'selector'     => ".fl-node-$id .component_social_share .social-share",
		'prop'         => 'padding-left',
	));

	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'button_spacing',
		'selector'     => ".fl-node-$id .component_social_share.-show-print-option .social-share.print",
		'prop'         => 'margin-left',
	));
}

if (!empty($settings->icon_size)) {
	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'icon_size',
		'selector'     => ".fl-node-$id .component_social_share .social-share .social-icon::before",
		'prop'         => 'font-size',
	));
}
?>