<?php
/**
 * Beaver Builder module frontend template output
 *
 * @package fp-slider
 */

echo do_shortcode(
	"[fp_accordion_slider
	id='" . $id . "' 
	class='" . $module->slug . "' 
	slides='" . $settings->slides . "' 
	slide_icon='" . $settings->slide_icon . "' 
	slide_breakpoint='" . $settings->slide_breakpoint . "']"
);
