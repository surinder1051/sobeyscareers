<?php

namespace fp\components;

use fp;

if ( class_exists( 'fp\Component' ) ) {
	class simple_video extends fp\Component {

		public $schema_version        = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version               = '1.1.2';
		public $component             = 'simple_video'; // Component slug should be same as this file base name
		public $component_name        = 'Simple Video'; // Shown in BB sidebar.
		public $component_description = 'Module to insert a YouTube/Vimeo video';
		public $component_category    = 'FP Global';
		public $enable_css            = true;
		public $enable_js             = true;
		public $deps_css              = array( 'font-awesome' ); // WordPress Registered CSS Dependencies
		public $deps_js               = array( 'jquery' ); // WordPress Registered JS Dependencies
		// public $deps_css_remote              = array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote               = array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir = __DIR__;
		public $fields   = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig = array(); // Placeholder for BB Module Registration
		public $variants = array(); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content    = true; // Exclude content of this module from being saved to post_content field
		// public $load_in_header               = true;
		public $dynamic_data_feed_parameters = array( // Generates $atts[posts] object with dynamically populated data
			// 'pagination_api'         => true, // enable ajax pagination
			// 'posts_per_page_default' => '3',
			// 'posts_per_page_options' => array(
			// '1' => 1,
			// '2' => 2,
			// '3' => 3,
			// '4' => 4,
			// '5' => 5,
			// '6' => 6,
			// '7' => 7,
			// '8' => 8,
			// '9' => 9,
			// ),
			// 'post_types' => array('post', 'page'),
			// 'taxonomies' => array(
			// array('category' => array()),
			// array('content_tag' => array('none-option' => true)),
			// )
		);

		public function init_fields() {
			 // Documentation @ https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref

			/*
			Field Types:

			https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference

			Align           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#align-field
			Border          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#border-field
			button-group    - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#button-group-field
			code            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#code-field
			color           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#color-field
			dimension       - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#dimension-field
			editor          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#editor-field
			font            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#font-field
			form            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#form-field
			gradient        - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#gradient-field
			icon            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#icon-field
			link            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#link-field
			loop            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#loop-settings-fields
			multiple-audios - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-audios-field
			multiple-photos - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-photos-field
			photo           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-field
			photo-sizes     - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
			Post Type       - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
			Select          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#select-field
			Service         - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#service-fields
			shadow          - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#shadow-field
			Suggest         - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#suggest-field
			text            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#text-field
			Textarea        - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#textarea-field
			Time            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-field
			Timezone        - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-zone-field
			typography      - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#typography-field
			unit            - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#unit-field
			Video           - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#video-field

			Repeater Fields
			'multiple' => true,
			Not supported in Editor Fields, Loop Settings Fields, Photo Fields, and Service Fields.

			Dynamic Colour Selector Fields
			'type'    => 'fp-colour-picker',
			'element' => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',

			background class in template class="-bg-[colour selected]"
			button/header class="[colour selected]"

			Additional choices for button: include a select field with options: [outline | solid( default )]
			Outline: class="outline [color selected]"

			Custom SVG Icon Picker
			'type' => 'fp-icon-picker',

			Install svg icons through BB font tool in a subdir called /images/

			*/

			$this->fields = array(
				'simple_video_tab-1' => array(
					'title'    => __( 'Settings', FP_TD ),
					'sections' => array(
						'settings' => array(
							'title'  => __( 'Settings', FP_TD ),
							'fields' => array(
								'fp_video_url'   => array(
									'type'        => 'link',
									'label'       => __( 'Video URL', FP_TD ),
									'default'     => '',
									'placeholder' => 'https://',
									'connections' => array( 'url' ),
									'help'        => __( 'Accepts YouTube or Vimeo URLs', FP_TD ),
								),
								'fp_video_thumb' => array(
									'label'       => __( 'Video Thumbnail', FP_TD ),
									'type'        => 'photo',
									'show_remove' => true,
									'placeholder' => '',
									'connections' => array( 'photo' ),
									'description' => __( 'Set the image that displays when the video is not being played', FP_TD ),
								),
								'fp_video_width' => array(
									'type'         => 'unit',
									'label'        => __( 'Width', FP_TD ),
									'units'        => array( 'px', 'vw', '%' ),
									'default_unit' => '%', // Optional
									'preview'      => array(
										'type'     => 'css',
										'selector' => '.component_simple_video .video-defer-container',
										'property' => 'width',
									),
								),
								'fp_icon_slug'   => array(
									'type'        => 'icon',
									'label'       => __( 'Custom Play Icon', FP_TD ),
									'show_remove' => true,
									'description' => __( 'Set the a custom play icon by providing an icon slug.', FP_TD ),
								),
								'icon_color'     => array(
									'type'       => 'color',
									'label'      => __( 'Play Icon Color', FP_TD ),
									'default'    => 'FFFFFF',
									'show_alpha' => true,
									'show_reset' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_simple_video .video-defer .play-button-icon:before',
										'property' => 'color',
									),
								),
								'play_bg_color'  => array(
									'type'       => 'color',
									'label'      => __( 'Play Icon Background Color', FP_TD ),
									'default'    => '404040',
									'show_alpha' => true,
									'show_reset' => true,
									'preview'    => array(
										'type'     => 'css',
										'selector' => '.component_simple_video .video-defer .play-button-icon',
										'property' => 'background-color',
									),
								),
							),
						),
					),
				),
			);
		}

		public function pre_process_data( $atts, $module ) {
			if ( ! empty( $atts['fp_video_thumb'] ) ) {
				$atts['fp_video_thumb_src']    = wp_get_attachment_image_url( $atts['fp_video_thumb'], 'full' );
				$atts['fp_video_thumb_srcset'] = wp_get_attachment_image_srcset( $atts['fp_video_thumb'], 'full' );
				$atts['fp_video_thumb_sizes']  = wp_get_attachment_image_sizes( $atts['fp_video_thumb'], 'full' );
			}

			// Overwrite Video URL with post data, if available
			if ( is_singular( 'video' ) ) {
				global $post;
				$atts['fp_video_url'] = get_post_meta( $post->ID, 'video_url', true );
			}

			if ( ! empty( $atts['fp_video_url'] ) ) {
				$url_parts         = parse_url( $atts['fp_video_url'] );
				$atts['videoID']   = false;
				$atts['videoType'] = 'youtube';

				if ( preg_match( '/vimeo/', $url_parts['host'] ) ) {
					$atts['videoID']   = preg_replace( '/\/(?:video\/)*/', '', $url_parts['path'] );
					$atts['videoType'] = 'vimeo';
				} elseif ( preg_match( '/youtu\.*be/', $url_parts['host'] ) ) {
					$videoID         = isset( $url_parts['query'] ) ? ( strpos( $url_parts['query'], '&' ) ? substr( $url_parts['query'], 0, strpos( $url_parts['query'], '&' ) ) : $url_parts['query'] ) : $url_parts['path'];
					$atts['videoID'] = preg_replace( '/(\/(?:embed\/)*|v=)/', '', $videoID );
				}
			}

			return $atts;
		}
	}
	new simple_video();
}