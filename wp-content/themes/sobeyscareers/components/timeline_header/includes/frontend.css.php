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

// $settings->field_key->default_colour
// $settings->field_key->hover_colour
// $settings->field_key->text_colour
// $settings->field_key->text_hover_colour


FLBuilderCSS::typography_field_rule(array(
	'settings'	=> $settings,
	'setting_name' 	=> 'timeline_heading_typography',
	'selector' 	=> '.fl-node-' . $id . ' .component_timeline_header .timeline-header-content .timeline-header',
));


FLBuilderCSS::typography_field_rule(array(
	'settings'	=> $settings,
	'setting_name' 	=> 'timeline_content_typography',
	'selector' 	=> '.fl-node-' . $id . ' .component_timeline_header .timeline-header-content .text-box',
));

?>

<?php if ((bool) $settings->background_overlay && !empty($settings->background_opacity) ) : ?>
	.fl-node-<?php echo $id; ?> .component_timeline_header.-with-overlay:before {
		opacity: <?php echo ($settings->background_opacity/100); ?>;
	}
<?php endif; ?>