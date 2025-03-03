<?php

namespace FpSlider;

class Slider 
{
	public function __construct()
	{
	}
	
	function enqueue()
	{
		wp_enqueue_style('fp-slider-style', get_template_directory_uri() . '/dist/plugin/fp-slider/modules/slider/slider.css');
		// 'fp-embed-video' script is registered in FP Foundation themes
		wp_enqueue_script('fp-slider-script', plugin_dir_url(__FILE__) . 'slider.js', ['jquery', 'fp-embed-video'], null, false);
	}
	
	function shortcode($atts, $content = null)
	{
		$this->enqueue();

		// get shortcode atts
		extract(shortcode_atts(array(
			'class'        => 'bbmodule-slider',
			'slides'       => '',
			'icon_slug'    => '',
			'show_dots'    => 'true',
			'dot_type'     => 'line',
		), $atts));

		$classes = array();

		if (is_numeric($slides)) {
			$slides = [$slides];
		} elseif (!empty($slides) && is_string($slides)) {
			$slides = explode(',', $slides);
		}
		
		if (is_array($slides)) {
			foreach ($slides as $key => $slide_id) {
				$meta = get_fields($slide_id);
				if (isset($meta['content']['content_image']['url']) && !empty($meta['content']['content_image']['url'])) {
					$classes[] = ' with-text-bg';
				}

				$can_show_regionalized_content = apply_filters('can_show_regionalized_content', $slide_id);
				if (!$can_show_regionalized_content) {
					unset($slides[$key]);
				}
			}

			$slides = array_values($slides);
		} else {
			echo "Missing slides";
		}

		if ($show_dots) {
			$classes[] = ' dot-style-' . $dot_type;
		}

		$class .= implode(' ', array_unique($classes));

		ob_start();
		include plugin_dir_path(__FILE__). 'slider.tpl.php';
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	public function run()
	{
		add_shortcode('fp_slider', [$this, 'shortcode']);
	}
}
