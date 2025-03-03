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

if ( $settings->slide_peek_toggle == 'right' || $settings->slide_peek_toggle == 'both' ) {
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'slide_peek',
			'selector'     => ".fl-node-$id .bbmodule-slider .slider .slick-list",
			'prop'         => 'padding-right',
		)
	);
}

if ( $settings->slide_peek_toggle == 'both' ) {
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'slide_peek',
			'selector'     => ".fl-node-$id .bbmodule-slider .slider .slick-list",
			'prop'         => 'padding-left',
		)
	);
}

if ( $settings->slide_peek_toggle != 'none' ) {
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'slide_margin',
			'selector'     => ".fl-node-$id .bbmodule-slider .slider .slick-slide",
			'prop'         => 'margin-right',
		)
	);
}

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'arrow_width',
		'selector'     => ".fl-node-$id .bbmodule-slider .slider .slick-arrow",
		'prop'         => 'width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'arrow_height',
		'selector'     => ".fl-node-$id .bbmodule-slider .slider .slick-arrow",
		'prop'         => 'height',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'arrow_font_size',
		'selector'     => ".fl-node-$id .bbmodule-slider .slider .slick-arrow::after",
		'prop'         => 'font-size',
	)
);

if ( ! empty( $settings->arrow_theme ) ) {
	$settings->arrow_theme = generate_theme( $settings->arrow_theme, 'button' );

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slick-arrow::after",
			'enable'   => ! empty( $settings->arrow_theme->text_colour ),
			'props'    => array(
				'color' => str_replace( '#', '', $settings->arrow_theme->text_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slick-arrow:hover::after",
			'enable'   => ! empty( $settings->arrow_theme->text_hover_colour ),
			'props'    => array(
				'color' => str_replace( '#', '', $settings->arrow_theme->text_hover_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slick-arrow",
			'enable'   => ! empty( $settings->arrow_theme->default_colour ),
			'props'    => array(
				'background-color' => str_replace( '#', '', $settings->arrow_theme->default_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slick-arrow:hover",
			'enable'   => ! empty( $settings->arrow_theme->hover_colour ),
			'props'    => array(
				'background-color' => str_replace( '#', '', $settings->arrow_theme->hover_colour ),
			),
		)
	);
}

if ( ! empty( $settings->dot_theme ) ) {
	$settings->dot_theme = generate_theme( $settings->dot_theme, 'button' );

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slick-dots li button",
			'enable'   => ! empty( $settings->dot_theme->default_colour ),
			'props'    => array(
				'background-color' => str_replace( '#', '', $settings->dot_theme->default_colour ),
				'border-color'     => str_replace( '#', '', $settings->dot_theme->default_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slick-dots li.slick-active button, .fl-node-$id .bbmodule-slider .slick-dots li button:hover",
			'enable'   => ! empty( $settings->dot_theme->hover_colour ),
			'props'    => array(
				'background-color' => str_replace( '#', '', $settings->dot_theme->hover_colour ),
				'border-color'     => str_replace( '#', '', $settings->dot_theme->hover_colour ),
			),
		)
	);
}

if ( ! empty( $settings->play_theme ) ) {
	$settings->play_theme = generate_theme( $settings->play_theme, 'button' );

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slick-nav.has-play .play",
			'enable'   => ! empty( $settings->play_theme->text_colour ),
			'props'    => array(
				'color' => str_replace( '#', '', $settings->play_theme->text_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slick-nav.has-play .play",
			'enable'   => ! empty( $settings->play_theme->default_colour ),
			'props'    => array(
				'background-color' => str_replace( '#', '', $settings->play_theme->default_colour ),
				'border-color'     => str_replace( '#', '', $settings->play_theme->default_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slick-nav.has-play .play:hover",
			'enable'   => ! empty( $settings->play_theme->text_hover_colour ),
			'props'    => array(
				'color' => str_replace( '#', '', $settings->play_theme->text_hover_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slick-nav.has-play .play:hover",
			'enable'   => ! empty( $settings->play_theme->hover_colour ),
			'props'    => array(
				'background-color' => str_replace( '#', '', $settings->play_theme->hover_colour ),
				'border-color'     => str_replace( '#', '', $settings->play_theme->hover_colour ),
			),
		)
	);
}

if ( ! empty( $settings->slides ) ) {
	if ( is_numeric( $settings->slides ) ) {
		$settings->slides = array( $settings->slides );
	} elseif ( is_string( $settings->slides ) ) {
		$settings->slides = explode( ',', $settings->slides );
	}

	if ( is_array( $settings->slides ) ) {
		foreach ( $settings->slides as $key => $slide_id ) {
			$meta = get_field( 'styling', $slide_id );

			if ( $meta ) {
				if ( $meta['transparent_text_box'] ) {
					FLBuilderCSS::rule(
						array(
							'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap.slide-$slide_id .slider_text_box",
							'props'    => array(
								'background' => 'none',
								'width'      => '100%',
							),
						)
					);
				} else {
					FLBuilderCSS::rule(
						array(
							'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap.slide-$slide_id .slider_text_box",
							'enable'   => ! empty( $meta['text_box_background_color'] ),
							'props'    => array(
								'background-color' => str_replace( '#', '', $meta['text_box_background_color'] ),
							),
						)
					);
				}

				FLBuilderCSS::rule(
					array(
						'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap.slide-$slide_id .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_heading",
						'enable'   => ! empty( $meta['heading_color'] ),
						'props'    => array(
							'color' => str_replace( '#', '', $meta['heading_color'] ),
						),
					)
				);

				FLBuilderCSS::rule(
					array(
						'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap.slide-$slide_id .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_description",
						'enable'   => ! empty( $meta['description_color'] ),
						'props'    => array(
							'color' => str_replace( '#', '', $meta['description_color'] ),
						),
					)
				);

				FLBuilderCSS::rule(
					array(
						'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap.slide-$slide_id .slider_text_box .slider_text_box_tbl .slider_text_box_cel a.slider_btn",
						'enable'   => ! empty( $meta['button_text_color'] ),
						'props'    => array(
							'color' => str_replace( '#', '', $meta['button_text_color'] ),
						),
					)
				);

				FLBuilderCSS::rule(
					array(
						'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap.slide-$slide_id .slider_text_box .slider_text_box_tbl .slider_text_box_cel a.slider_btn",
						'enable'   => ! empty( $meta['button_background_color'] ),
						'props'    => array(
							'background-color' => str_replace( '#', '', $meta['button_background_color'] ),
						),
					)
				);
			}
		}
	}
}

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'text_box_padding',
		'selector'     => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box",
		'props'        => array(
			'padding-top'    => 'text_box_padding_top',
			'padding-right'  => 'text_box_padding_right',
			'padding-bottom' => 'text_box_padding_bottom',
			'padding-left'   => 'text_box_padding_left',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'text_box_content_align',
		'selector'     => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box",
		'prop'         => 'text-align',
	)
);

if ( ! empty( $settings->text_box_theme ) ) {
	$settings->text_box_theme = generate_theme( $settings->text_box_theme, 'background' );

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box",
			'enable'   => ! empty( $settings->text_box_theme->text_colour ),
			'props'    => array(
				'color' => str_replace( '#', '', $settings->text_box_theme->text_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box",
			'enable'   => ! empty( $settings->text_box_theme->default_colour ),
			'props'    => array(
				'background-color' => str_replace( '#', '', $settings->text_box_theme->default_colour ),
			),
		)
	);
}

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'heading_typography',
		'selector'     => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_heading",
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'heading_margin',
		'selector'     => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_heading",
		'prop'         => 'margin-bottom',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_heading",
		'enable'   => ! empty( $settings->heading_color ),
		'props'    => array(
			'color' => $settings->heading_color,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'description_typography',
		'selector'     => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_description",
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'description_margin',
		'selector'     => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_description",
		'prop'         => 'margin-bottom',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel .slide_description",
		'enable'   => ! empty( $settings->description_color ),
		'props'    => array(
			'color' => $settings->description_color,
		),
	)
);

if ( ! empty( $settings->button_theme ) ) {
	$settings->button_theme = generate_theme( $settings->button_theme, 'button' );

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel a.slider_btn",
			'enable'   => ! empty( $settings->button_theme->text_colour ),
			'props'    => array(
				'color' => str_replace( '#', '', $settings->button_theme->text_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel a.slider_btn",
			'enable'   => ! empty( $settings->button_theme->default_colour ),
			'props'    => array(
				'background-color' => str_replace( '#', '', $settings->button_theme->default_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel a.slider_btn:hover",
			'enable'   => ! empty( $settings->button_theme->text_hover_colour ),
			'props'    => array(
				'color' => str_replace( '#', '', $settings->button_theme->text_hover_colour ),
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_text_box .slider_text_box_tbl .slider_text_box_cel a.slider_btn:hover",
			'enable'   => ! empty( $settings->button_theme->hover_colour ),
			'props'    => array(
				'background-color' => str_replace( '#', '', $settings->button_theme->hover_colour ),
			),
		)
	);
}

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_img .slider_video .video-defer .play-button-icon:before",
		'enable'   => ! empty( $settings->icon_color ),
		'props'    => array(
			'color' => $settings->icon_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .bbmodule-slider .slider .slider_box_wrap .slider_img .slider_video .video-defer .play-button-icon",
		'enable'   => ! empty( $settings->icon_bg_color ),
		'props'    => array(
			'background-color' => $settings->icon_bg_color,
		),
	)
);
