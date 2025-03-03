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
* Note: When used from beaver builder
a cached version of this file will be
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

$settings->fp_quick_link_theme = generate_theme($settings->fp_quick_link_theme, 'a');
if (!empty($settings->fp_quick_link_theme)) {
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_quick_links .quicklinks-label, .fl-node-$id .component_quick_links ul li, .fl-node-$id .component_quick_links a",
		'enabled'  => !empty($settings->fp_quick_link_theme->text_colour),
		'props'    => array(
			'color' => str_replace('#', '', $settings->fp_quick_link_theme->text_colour),
		),
	));

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_quick_links a:hover",
		'enabled'  => !empty($settings->fp_quick_link_theme->text_hover_colour),
		'props'    => array(
			'color' => str_replace('#', '', $settings->fp_quick_link_theme->text_hover_colour),
		),
	));
}

FLBuilderCSS::typography_field_rule(array(
	'settings'     => $settings,
	'enabled'      => !empty($settings->title_typography),
	'setting_name' => 'title_typography',
	'selector'     => ".fl-node-$id .component_quick_links .quicklinks-label",
));

FLBuilderCSS::typography_field_rule(array(
	'settings'     => $settings,
	'enabled'      => !empty($settings->link_typography),
	'setting_name' => 'link_typography',
	'selector'     => ".fl-node-$id .component_quick_links ul li",
));

FLBuilderCSS::rule(array(
	'selector' => ".fl-node-$id .component_quick_links .quicklinks-inner",
	'enabled'  => !empty($settings->fp_quick_links_align),
	'props'    => array(
		'text-align' => $settings->fp_quick_links_align,
	),
));
