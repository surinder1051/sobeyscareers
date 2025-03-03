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

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'scard_heading_typography',
		'selector'     => ".fl-node-$id .component_bs_card_simple .card-grid-row .card .card-img-overlay .card-content .card-title",
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'scard_text_typography',
		'selector'     => ".fl-node-$id .component_bs_card_simple .card-grid-row .card .card-img-overlay .card-content .card-text",
	)
);

if ( ! empty( $settings->overlay_padding_top || $settings->overlay_padding_right || $settings->overlay_padding_bottom || $settings->overlay_padding_left ) ) {
	FLBuilderCSS::dimension_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'overlay_padding',
			'selector'     => ".fl-node-$id .component_bs_card_simple .card-grid-row .card .card-img-overlay",
			'props'        => array(
				'padding-top'    => 'overlay_padding_top',
				'padding-right'  => 'overlay_padding_right',
				'padding-bottom' => 'overlay_padding_bottom',
				'padding-left'   => 'overlay_padding_left',
			),
		)
	);
}

if ( ! empty( $settings->sc_callout_options ) ) {
	foreach ( $settings->sc_callout_options as $index => $calloutItem ) {
		$theme = ! empty( $calloutItem->callout_button_theme ) ? generate_theme( $calloutItem->callout_button_theme, 'button' ) : '';

		if ( ! empty( $calloutItem->callout_button_theme ) ) {
			$default_colour = str_replace( '#', '', $theme->default_colour );

			FLBuilderCSS::rule(
				array(
					'selector' => ".fl-node-$id .component_bs_card_simple .card-grid-row #card-$id-$index .card-img-overlay .card-button a.button",
					'props'    => array(
						'color'            => str_replace( '#', '', $theme->text_colour ),
						'background-color' => $default_colour,
						'border-color'     => $default_colour,
					),
				)
			);

			FLBuilderCSS::rule(
				array(
					'selector' => ".fl-node-$id .component_bs_card_simple .card-grid-row #card-$id-$index .card-img-overlay .card-button a.button:hover",
					'props'    => array(
						'color'            => str_replace( '#', '', $theme->text_hover_colour ),
						'background-color' => str_replace( '#', '', $theme->hover_colour ),
					),
				)
			);
		}

		FLBuilderCSS::rule(
			array(
				'selector' => ".fl-node-$id .component_bs_card_simple .card-grid-row #card-$id-$index .card-img-overlay .card-content .card-title",
				'enabled'  => ! empty( $calloutItem->callout_heading_color ),
				'props'    => array(
					'color' => $calloutItem->callout_heading_color,
				),
			)
		);

		FLBuilderCSS::rule(
			array(
				'selector' => ".fl-node-$id .component_bs_card_simple .card-grid-row #card-$id-$index .card-img-overlay",
				'enabled'  => ! empty( $calloutItem->callout_bg_color ),
				'props'    => array(
					'top'              => 'auto',
					'background-color' => $calloutItem->callout_bg_color,
				),
			)
		);
	}
}
