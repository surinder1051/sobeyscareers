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

FLBuilderCSS::rule(array(
	'selector' => ".fl-node-$id .component_simple_video .video-defer .play-button-icon:before",
	'enable'   => !empty($settings->icon_color),
	'props'    => array(
		'color' => $settings->icon_color,
	),
));

FLBuilderCSS::rule(array(
	'selector' => ".fl-node-$id .component_simple_video .video-defer .play-button-icon",
	'enable'   => !empty($settings->play_bg_color),
	'props'    => array(
		'background-color' => $settings->play_bg_color,
	),
));

FLBuilderCSS::rule(array(
	'selector' => ".fl-node-$id .component_simple_video .video-defer-container",
	'enable'   => !empty($settings->fp_video_width),
	'props'    => array(
		'width' => $settings->fp_video_width . $settings->fp_video_width_unit,
		'max-width' => 'none !important'
	),
));
