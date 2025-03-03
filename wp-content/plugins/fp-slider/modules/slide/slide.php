<?php

namespace FpSlider;

class Slide
{
	public function __construct()
	{
	}

	function enqueue()
	{
	}

	function shortcode($atts, $content = null)
	{
		// get shortcode atts
		extract(shortcode_atts(array(
			'id'           => '',
			'count'        => '',
			'no_container' => '',
			'icon_slug'    => '',
		), $atts));

		$meta = get_fields($id);

		$display_options = !empty($meta['display_options']) ? $meta['display_options'] : 'text_image';
		$media           = strpos($display_options, 'image') !== false ? 'image' : 'video';
		$text_box        = strpos($display_options, 'text') !== false ? true : false;

		$text_box_heading     = !empty($meta['content']['heading']) ? $meta['content']['heading'] : null;
		$text_box_description = !empty($meta['content']['description']) ? $meta['content']['description'] : null;
		$text_box_button_text = !empty($meta['content']['button_text']) ? $meta['content']['button_text'] : null;
		$text_content_image   = !empty($meta['content']['content_image']['url']) ? $meta['content']['content_image']['url'] : null;
		$video_url            = !empty($meta['content']['video_url']) ? $meta['content']['video_url'] : null;
		$url                  = !empty($meta['content']['url']) ? $meta['content']['url'] : null;
		$link_target          = !empty($meta['content']['link_target']) ? $meta['content']['link_target'] : null;
		$mobile_image_id      = !empty($meta['content']['mobile_banner_image']['id']) ? $meta['content']['mobile_banner_image']['id'] : null;

		$slide_class   = "slider_box_wrap $display_options";
		$box_alignment = !empty($meta['styling']['text_box_alignment']) ? $meta['styling']['text_box_alignment'] : null;
		$slide_class  .= $box_alignment && $text_box == true ? " box_align_$box_alignment" : '';
		$slide_class  .= !empty($meta['custom_css_class']) ? ' ' . $meta['custom_css_class'] : '';

		$text_box_cel_class  = 'slider_text_box_cel';
		$text_box_cel_class .= $text_content_image ? ' with-bg' : '';

		$thumbnail_id = get_post_thumbnail_id($id);
		$alt          = $thumbnail_id ? trim(strip_tags(get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true))) : '';
		$src          = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : 'https://dummyimage.com/1920x700/808080/404040.jpg&text=slide';
		$srcset       = $thumbnail_id ? wp_get_attachment_image_srcset($thumbnail_id, 'full') : 'https://dummyimage.com/1920x700/808080/404040.jpg&text=slide 1920w, https://dummyimage.com/992x362/808080/404040.jpg&text=slide 992w, https://dummyimage.com/768x280/808080/404040.jpg&text=slide 768w';
		$sizes        = $thumbnail_id ? wp_get_attachment_image_sizes($thumbnail_id, 'full') : '(max-width: 1920px) 100vw, 1920px';

		if ($mobile_image_id) {
			$mobile_srcset = wp_get_attachment_image_srcset($mobile_image_id, 'full');
			$mobile_srcset = $mobile_srcset ? $mobile_srcset : wp_get_attachment_image_url($mobile_image_id, 'full') . ' 480w';
			$mobile_sizes  = wp_get_attachment_image_sizes($mobile_image_id, 'full');
		}

		if ($video_url) {
			$url_parts = parse_url($video_url);
			$videoID = false;
			$videoType = 'youtube';

			if (preg_match('/vimeo/', $url_parts['host'])) {
				$videoID = preg_replace('/\/(?:video\/)*/', '', $url_parts['path']);
				$videoType = 'vimeo';
			} else if (preg_match('/youtu\.*be/', $url_parts['host'])) {
				$videoID = isset($url_parts['query']) ? (strpos($url_parts['query'], '&') ? substr($url_parts['query'], 0, strpos($url_parts['query'], '&')) : $url_parts['query']) : $url_parts['path'];
				$videoID = preg_replace('/(\/(?:embed\/)*|v=)/', '', $videoID);
			}
		}

		ob_start();
		include plugin_dir_path(__FILE__) . 'slide.tpl.php';
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	public function run()
	{
		add_shortcode('fp_slide', [$this, 'shortcode']);
	}
}
