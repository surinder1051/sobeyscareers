<?php
/**
 * This file should contain frontend styles that
 * will be applied to individual module instances.
 *
 * @package fp-foundation
 *
 * You have access to three variables in this file:
 *
 * $module An instance of your module class.
 * $id The module's ID.
 * $settings The module's settings.
 *
 * Note: When used from beaver builder, a cached version of this file will be
 * created that's unique to the instance in the /uploads/bb-plugin/cache/
 * ,however when used by a regular shortcode an inline style will in turn be
 * generated and put on the page where it's been used, no cached file will be
 * created.
 *
 * ** Examples: **

 * To use a active theme that can be updated via Options page for a XXX field you need to generate it at runtime.
 * element can be ('element'     => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',)
 * $settings->field_key = generate_theme($settings->field_key, element);
 * $settings->field_key->default_colour
 * $settings->field_key->hover_colour
 * $settings->field_key->text_colour
 * $settings->field_key->text_hover_colour
 *
 * FLBuilderCSS::typography_field_rule(array(
 * 'settings'     => $settings,
 * 'setting_name' => 'title_typography',
 * 'selector'     => 'body .fl-node-' . $id . ' .title',
 * ));
 * fp_apply_style($id, '.card-title', 'color', $settings->title_color);
 */

if ( ! empty( $settings->menu_padding_top || $settings->menu_padding_right || $settings->menu_padding_bottom || $settings->menu_padding_left ) ) {
	FLBuilderCSS::dimension_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'menu_padding',
			'selector'     => ".fl-node-$id .component_breadcrumbs .breadcrumb",
			'props'        => array(
				'padding-top'    => 'menu_padding_top',
				'padding-right'  => 'menu_padding_right',
				'padding-bottom' => 'menu_padding_bottom',
				'padding-left'   => 'menu_padding_left',
			),
		)
	);
}

$settings->menu_theme = generate_theme( $settings->menu_theme, 'a' );
if ( ! empty( $settings->menu_theme ) ) {
	if ( ! empty( $settings->menu_theme->default_colour ) ) {
		FLBuilderCSS::rule(
			array(
				'selector' => ".fl-node-$id .component_breadcrumbs nav",
				'props'    => array(
					'background-color' => str_replace( '#', '', $settings->menu_theme->default_colour ),
				),
			)
		);
	}

	if ( ! empty( $settings->menu_theme->text_colour ) ) {
		FLBuilderCSS::rule(
			array(
				'selector' => ".fl-node-$id .component_breadcrumbs .breadcrumb *",
				'props'    => array(
					'color' => str_replace( '#', '', $settings->menu_theme->text_colour ),
				),
			)
		);
	}

	if ( ! empty( $settings->menu_theme->text_hover_colour ) ) {
		FLBuilderCSS::rule(
			array(
				'selector' => ".fl-node-$id .component_breadcrumbs .breadcrumb .breadcrumb-item a:hover, .fl-node-$id .component_breadcrumbs .breadcrumb .breadcrumb-item a:focus",
				'props'    => array(
					'color' => str_replace( '#', '', $settings->menu_theme->text_hover_colour ),
				),
			)
		);
	}
}

if ( ! empty( $settings->menu_border ) ) {
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'menu_border',
			'selector'     => ".fl-node-$id .component_breadcrumbs",
		)
	);
}

if ( ! empty( $settings->link_typography ) ) {
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'link_typography',
			'selector'     => ".fl-node-$id .component_breadcrumbs .breadcrumb .breadcrumb-item a",
		)
	);
}

if ( ! empty( $settings->link_underline ) ) {
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .component_breadcrumbs .breadcrumb .breadcrumb-item a:hover",
			'props'    => array(
				'text-decoration' => $settings->link_underline,
			),
		)
	);
}

if ( ! empty( $settings->separator_color ) ) {
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .component_breadcrumbs .breadcrumb .breadcrumb-item .separator i",
			'props'    => array(
				'color' => $settings->separator_color . ' !important',
			),
		)
	);
}

if ( ! empty( $settings->separator_font_size ) ) {
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'separator_font_size',
			'selector'     => ".fl-node-$id .component_breadcrumbs .breadcrumb .breadcrumb-item .separator i",
			'prop'         => 'font-size',
		)
	);
}

if ( ! empty( $settings->separator_margin ) ) {
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'separator_margin',
			'selector'     => ".fl-node-$id .component_breadcrumbs .breadcrumb .breadcrumb-item .separator",
			'prop'         => 'margin-right',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'separator_margin',
			'selector'     => ".fl-node-$id .component_breadcrumbs .breadcrumb .breadcrumb-item .separator",
			'prop'         => 'margin-left',
		)
	);
}
