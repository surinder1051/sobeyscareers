<?php //phpcs:ignore
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
 *
 *
 *
 * ** TO EXTEND THIS FILE: **
 *
 * if ( trailingslashit( file_exists( __DIR__ ) ) . '/extend-frontend.css.php' ) :
 * include_once trailingslashit( __DIR__ ) . '/extend-frontend.css.php';
 * endif;
 */

if ( ! empty( $settings->nav_color ) ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .component_fp_post_navigation .fp-nav-link .nav-link-text .nav-text",
			'props'    => array(
				'color' => $settings->nav_color,
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .component_fp_post_navigation .fp-nav-link .nav-link-text .nav-link [class^='icon-arrow-']:before",
			'props'    => array(
				'color' => $settings->nav_color,
			),
		)
	);
endif;

if ( ! empty( $settings->nav_typography ) ) :
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'nav_typography',
			'selector'     => ".fl-node-$id .component_fp_post_navigation .fp-nav-link .nav-link-text .nav-text",
		)
	);
endif;

if ( ! empty( $settings->arrow_size ) ) :
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'arrow_size',
			'selector'     => ".fl-node-$id .component_fp_post_navigation .fp-nav-link .nav-link-text .nav-link [class^='icon-arrow-']:before",
			'prop'         => 'font-size',
		)
	);
endif;

if ( ! empty( $settings->link_color ) && false === stristr( $settings->link_color, 'default' ) ) :
	$settings->link_color = generate_theme( $settings->link_color, 'a' );
	$text_colour          = str_replace( '#', '', $settings->link_color->default_colour );
	$text_hover_colour    = str_replace( '#', '', $settings->link_color->hover_colour );

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .component_fp_post_navigation .fp-nav-link .nav-link-text .nav-link .link-title",
			'props'    => array(
				'color' => $text_colour,
			),
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .component_fp_post_navigation .fp-nav-link .nav-link-text .nav-link:hover .link-title",
			'props'    => array(
				'color' => $text_hover_colour,
			),
		)
	);
endif;

if ( ! empty( $settings->link_typography ) ) :
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'link_typography',
			'selector'     => ".fl-node-$id .component_fp_post_navigation .fp-nav-link .nav-link-text .nav-link .link-title",
		)
	);
endif;

if ( ! empty( $settings->padding_top ) || ! empty( $settings->padding_right ) || ! empty( $settings->padding_bottom ) || ! empty( $settings->padding_left ) ) :
	FLBuilderCSS::dimension_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'padding',
			'selector'     => ".fl-node-$id .component_fp_post_navigation .post-nav-wrapper",
			'props'        => array(
				'padding-top'    => 'padding_top',
				'padding-right'  => 'padding_right',
				'padding-bottom' => 'padding_bottom',
				'padding-left'   => 'padding_left',
			),
		)
	);
endif;

if ( ! empty( $settings->border ) ) :
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'border',
			'selector'     => ".fl-node-$id .component_fp_post_navigation .post-nav-wrapper",
		)
	);
endif;
