<?php
/**
 * Slider Beaver Builder module class
 *
 * @package fp-slider
 */

/**
 * Slider
 */
class Slider extends FLBuilderModule {

	/**
	 * __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Slider', FP_TD ),
				'description'     => __( 'Display a custom slick slider', FP_TD ),
				'category'        => __( 'FP Sliders', FP_TD ),
				'partial_refresh' => true,
			)
		);
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'Slider',
	array(
		'slider_settings' => array(
			'title'    => __( 'Settings', FP_TD ),
			'sections' => array(
				'slides' => array(
					'title'  => __( 'Slides', FP_TD ),
					'fields' => array(
						'slides' => array(
							'type'   => 'suggest',
							'label'  => __( 'Slides', FP_TD ),
							'action' => 'fl_as_posts', // Search posts.
							'data'   => 'slide', // Slug of the post type to search.
							'limit'  => 20, // Limits the number of selections that can be made.
						),
					),
				),
				'slider' => array(
					'title'  => __( 'Slider Settings', FP_TD ),
					'fields' => array(
						'autoplay'     => array(
							'type'    => 'select',
							'label'   => __( 'Autoplay', FP_TD ),
							'options' => array(
								'true'  => __( 'On', FP_TD ),
								'false' => __( 'Off', FP_TD ),
							),
							'default' => 'true',
						),
						'show_arrows'  => array(
							'type'    => 'select',
							'label'   => __( 'Show Arrows', FP_TD ),
							'options' => array(
								'true'  => __( 'Yes', FP_TD ),
								'false' => __( 'No', FP_TD ),
							),
							'default' => 'false',
							'toggle'  => array(
								'true' => array(
									'fields' => array(
										'arrow_width',
										'arrow_height',
										'arrow_font_size',
										'arrow_theme',
									),
								),
							),
						),
						'show_dots'    => array(
							'type'    => 'select',
							'label'   => __( 'Show Dots', FP_TD ),
							'options' => array(
								'true'  => __( 'Yes', FP_TD ),
								'false' => __( 'No', FP_TD ),
							),
							'default' => 'true',
							'toggle'  => array(
								'true' => array(
									'fields' => array(
										'dot_theme',
										'dot_position',
										'dot_type',
									),
								),
							),
						),
						'dot_position' => array(
							'type'    => 'select',
							'label'   => __( 'Dot Placement', FP_TD ),
							'default' => 'overlay',
							'options' => array(
								'overlay' => __( 'Overlayed on slider', FP_TD ),
								'below'   => __( 'Below slider', FP_TD ),
							),
							'toggle'  => array(
								'below' => array(
									'fields' => array( 'show_play' ),
								),
							),
						),
						'show_play'    => array(
							'type'    => 'select',
							'label'   => __( 'Show Play/Pause button', FP_TD ),
							'options' => array(
								'true'  => __( 'Yes', FP_TD ),
								'false' => __( 'No', FP_TD ),
							),
							'default' => 'false',
							'toggle'  => array(
								'true' => array(
									'fields' => array( 'play_theme' ),
								),
							),
						),
					),
				),
			),
		),
		'slider_styling' => array(
			'title'    => __( 'Styling', FP_TD ),
			'sections' => array(
				'slider'   => array(
					'title'  => __( 'Slider', FP_TD ),
					'fields' => array(
						'slide_peek_toggle' => array(
							'type'    => 'select',
							'label'   => __( 'Slide peek', FP_TD ),
							'default' => 'none',
							'options' => array(
								'none'  => __( 'None', FP_TD ),
								'right' => __( 'Next Slide', FP_TD ),
								'both'  => __( 'Left and Right', FP_TD ),
							),
							'toggle'  => array(
								'right' => array( 'fields' => array( 'slide_peek', 'slide_margin' ) ),
								'both'  => array( 'fields' => array( 'slide_peek', 'slide_margin' ) ),
							),
						),
						'slide_peek'        => array(
							'type'         => 'unit',
							'label'        => __( 'Slide peek width', FP_TD ),
							'units'        => array( 'px', '%' ),
							'default_unit' => '%',
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slick-list',
								'property' => 'padding-right',
							),
						),
						'slide_margin'      => array(
							'type'         => 'unit',
							'label'        => __( 'Slide peek margin', FP_TD ),
							'units'        => array( 'px', 'rem' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slick-slide',
								'property' => 'margin-right',
							),
						),
						'arrow_width'       => array(
							'type'         => 'unit',
							'label'        => __( 'Arrow button width', FP_TD ),
							'units'        => array( 'px', 'rem' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slick-arrow',
								'property' => 'width',
							),
						),
						'arrow_height'      => array(
							'type'         => 'unit',
							'label'        => __( 'Arrow button height', FP_TD ),
							'units'        => array( 'px', 'rem' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slick-arrow',
								'property' => 'height',
							),
						),
						'arrow_font_size'   => array(
							'type'         => 'unit',
							'label'        => __( 'Arrow font size', FP_TD ),
							'units'        => array( 'px', 'rem' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slick-arrow::after',
								'property' => 'font-size',
							),
						),
						'arrow_theme'       => array(
							'type'    => 'fp-colour-picker',
							'label'   => __( 'Arrow color theme', FP_TD ),
							'element' => 'button',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slick-arrow',
							),
						),
						'dot_theme'         => array(
							'type'    => 'fp-colour-picker',
							'label'   => __( 'Dot color theme', FP_TD ),
							'element' => 'button',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slick-dots',
							),
						),
						'dot_type'          => array(
							'type'    => 'select',
							'label'   => __( 'Dot Type', FP_TD ),
							'default' => 'line',
							'options' => array(
								'line' => __( 'Lines', FP_TD ),
								'dot'  => __( 'Dots', FP_TD ),
							),
						),
						'play_theme'        => array(
							'type'    => 'fp-colour-picker',
							'label'   => __( 'Play/Pause button color theme', FP_TD ),
							'element' => 'button',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slick-nav.has-play .play',
							),
						),
					),
				),
				'text_box' => array(
					'title'  => __( 'Text Box', FP_TD ),
					'fields' => array(
						'text_box_padding'       => array(
							'type'         => 'dimension',
							'label'        => __( 'Text Box padding', FP_TD ),
							'units'        => array( 'px', 'rem' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box',
								'property' => 'padding',
							),
						),
						'text_box_content_align' => array(
							'type'       => 'align',
							'label'      => __( 'Text Box content alignment', FP_TD ),
							'default'    => 'center',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box',
								'property' => 'text-align',
							),
						),
						'text_box_theme'         => array(
							'type'    => 'fp-colour-picker',
							'label'   => __( 'Text Box color theme', FP_TD ),
							'element' => 'background',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box',
							),
						),
						'heading_typography'     => array(
							'type'       => 'typography',
							'label'      => __( 'Heading Typography', FP_TD ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_heading',
							),
						),
						'heading_margin'         => array(
							'type'         => 'unit',
							'label'        => __( 'Heading bottom margin', FP_TD ),
							'units'        => array( 'px', 'rem' ),
							'default'      => 20,
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_heading',
								'property' => 'margin-bottom',
							),
						),
						'heading_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Heading color', FP_TD ),
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_heading',
								'property' => 'color',
							),
						),
						'description_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Description Typography', FP_TD ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_description',
							),
						),
						'description_margin'     => array(
							'type'         => 'unit',
							'label'        => __( 'Description bottom margin', FP_TD ),
							'units'        => array( 'px', 'rem' ),
							'default'      => 40,
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_description',
								'property' => 'margin-bottom',
							),
						),
						'description_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Description color', FP_TD ),
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_description',
								'property' => 'color',
							),
						),
						'button_theme'           => array(
							'type'    => 'fp-colour-picker',
							'label'   => __( 'Button color theme', FP_TD ),
							'element' => 'button',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel a.slider_btn',
							),
						),
					),
				),
				'video'    => array(
					'title'  => __( 'Video Player', FP_TD ),
					'fields' => array(
						'icon_slug'     => array(
							'type'        => 'icon',
							'label'       => __( 'Custom Play Icon', FP_TD ),
							'show_remove' => true,
							'description' => __( 'Set the a custom play icon by providing an icon slug.', FP_TD ),
						),
						'icon_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Play Icon Color', FP_TD ),
							'default'    => 'FFFFFF',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_img .slider_video .video-defer .play-button-icon:before',
								'property' => 'color',
							),
						),
						'icon_bg_color' => array(
							'type'       => 'color',
							'label'      => __( 'Play Icon Background Color', FP_TD ),
							'default'    => '404040',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bbmodule-slider .slider .slider_box_wrap .slider_img .slider_video .video-defer .play-button-icon',
								'property' => 'background-color',
							),
						),
					),
				),
			),
		),
	)
);
