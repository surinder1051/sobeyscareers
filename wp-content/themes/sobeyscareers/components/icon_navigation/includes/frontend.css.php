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

if (!empty($settings->fp_icon_nav_items)) {
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_icon_navigation .display-table .icon-nav-item",
		'props'    => array(
			'width' => 100 / count($settings->fp_icon_nav_items) . '%',
		),
	));

	foreach ($settings->fp_icon_nav_items as $inIndex => $navItem) {
		FLBuilderCSS::rule(array(
			'selector' => ".fl-node-$id .component_icon_navigation #iconNavItem-$id-$inIndex a::before",
			'props'    => array(
				'background' => '#' . $navItem->nav_item_colour,
			),
		));

		FLBuilderCSS::rule(array(
			'selector' => ".component_icon_navigation #iconNavItem-$id-$inIndex a.active .navicon",
			'props'    => array(
				'color' => $navItem->nav_item_colour,
			),
		));
	}
}

if (!empty($settings->fp_icon_nav_separator)) {
	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_icon_navigation .icon-navigation-row.display-table .icon-nav-item",
		'props'    => array(
			'border-right-color' => $settings->fp_icon_nav_separator,
		),
	));

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_icon_navigation .icon-navigation-row.display-table .icon-nav-item:first-child",
		'props'    => array(
			'border-left-color' => $settings->fp_icon_nav_separator,
		),
	));

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_icon_navigation .icon-navigation-row.display-grid a:after",
		'props'    => array(
			'background' => '#' . $settings->fp_icon_nav_separator,
		),
	));

	FLBuilderCSS::rule(array(
		'selector' => ".fl-node-$id .component_icon_navigation .icon-navigation-row.display-grid .icon-nav-item",
		'props'    => array(
			'border-bottom-color' => $settings->fp_icon_nav_separator,
		),
	));
}

if (!empty($settings->fp_icon_nav_icon_size)) {
	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'fp_icon_nav_icon_size',
		'selector'     => ".fl-node-$id .component_icon_navigation .display-table .icon-nav-item a .navicon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	));
}

if (!empty($settings->fp_icon_nav_font_size)) {
	FLBuilderCSS::responsive_rule(array(
		'settings'     => $settings,
		'setting_name' => 'fp_icon_nav_font_size',
		'selector'     => ".fl-node-$id .component_icon_navigation .display-table .icon-nav-item a .nav-label",
		'prop'         => 'font-size',
		'unit'         => 'px',
	));
}
?>