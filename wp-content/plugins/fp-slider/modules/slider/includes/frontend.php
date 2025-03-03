<?php
/**
 * Beaver Builder module frontend template output
 *
 * @package fp-slider
 */

echo do_shortcode(
	"[fp_slider
	class='" . $module->slug . "' 
	slides='" . $settings->slides . "' 
	icon_slug='" . $settings->icon_slug . "' 
	show_dots='" . $settings->show_dots . "' 
	dot_type='" . $settings->dot_type . "']"
);
