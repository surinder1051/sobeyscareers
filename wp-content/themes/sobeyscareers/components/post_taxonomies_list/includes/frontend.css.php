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
		'selector' => ".fl-node-$id .component_post_taxonomies_list .title",
		'props'    => array(
			'color' => $settings->title_color,
		),
	));
}

if (!empty($settings->title_typography)) {
	FLBuilderCSS::typography_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .component_post_taxonomies_list .title",
	));
}

if (!empty($settings->title_margin_top) || !empty($settings->title_margin_right) || !empty($settings->title_margin_bottom) || !empty($settings->title_margin_left)) {
	FLBuilderCSS::dimension_field_rule( array(
		'settings'     => $settings,
		'setting_name' => 'title_margin',
		'selector'     => ".fl-node-$id .component_post_taxonomies_list .title",
		'props'        => array(
			'margin-top'    => 'title_margin_top',
			'margin-right'  => 'title_margin_right',
			'margin-bottom' => 'title_margin_bottom',
			'margin-left'   => 'title_margin_left',
		),
	));
}

if (!empty($settings->term_color)) {
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_post_taxonomies_list ul li a",
		'props'    => array(
			'color' => $settings->term_color,
		),
	));
}

if (!empty($settings->term_typography)) {
	FLBuilderCSS::typography_field_rule(array(
		'settings'     => $settings,
		'setting_name' => 'term_typography',
		'selector'     => ".fl-node-$id .component_post_taxonomies_list ul li a",
	));
}

if (!empty($settings->term_padding_top) || !empty($settings->term_padding_right) || !empty($settings->term_padding_bottom) || !empty($settings->term_padding_left)) {
	FLBuilderCSS::dimension_field_rule( array(
		'settings'     => $settings,
		'setting_name' => 'term_padding',
		'selector'     => ".fl-node-$id .component_post_taxonomies_list ul li a",
		'props'        => array(
			'padding-top'    => 'term_padding_top',
			'padding-right'  => 'term_padding_right',
			'padding-bottom' => 'term_padding_bottom',
			'padding-left'   => 'term_padding_left',
		),
	));
}

if (!empty($settings->term_border)) {
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_post_taxonomies_list ul li a",
		'props'    => array(
			'display' => 'block',
		),
	));

	FLBuilderCSS::border_field_rule( array(
		'settings'      => $settings,
		'setting_name'  => 'term_border',
		'selector'      => ".fl-node-$id .component_post_taxonomies_list ul li a",
	));
}
