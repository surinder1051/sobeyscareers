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
/**
 * To use a active theme that can be updated via Options page for a XXX field you need to generate it at runtime.
 * element can be ('element'     => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',)
 * $settings->field_key = generate_theme($settings->field_key, element);

 * $settings->field_key->default_colour
 * $settings->field_key->hover_colour
 * $settings->field_key->text_colour
 * $settings->field_key->text_hover_colour


 * FLBuilderCSS::typography_field_rule(array(
 * 'settings'  => $settings,
 * 'setting_name'  => 'title_typography',
 * 'selector'  => 'body .fl-node-' . $id . ' .title',
 * ));

 * fp_apply_style($id, '.card-title', 'color', $settings->title_color);
 */

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .component_bs_accordion .card .card-header .accordion-header::after",
		'enabled'  => ! empty( $settings->card_icon_color ),
		'props'    => array(
			'color' => $settings->card_icon_color,
		),
	)
);

if ( is_array( $settings->items ) ) {
	foreach ( $settings->items as $i => $item ) {
		FLBuilderCSS::typography_field_rule(
			array(
				'settings'     => $item,
				'setting_name' => 'subitem_heading_typography',
				'selector'     => ".fl-node-$id .component_bs_accordion .card.card-$i .card-body .card-content-grid .grid-heading",
			)
		);

		FLBuilderCSS::rule(
			array(
				'selector' => ".fl-node-$id .component_bs_accordion .card.card-$i .card-body .card-content-grid",
				'media'    => 'default',
				'enabled'  => ! empty( $item->grid_columns ),
				'props'    => array(
					'grid-template-columns' => "repeat($item->grid_columns, 1fr)",
				),
			)
		);

		FLBuilderCSS::rule(
			array(
				'selector' => ".fl-node-$id .component_bs_accordion .card.card-$i .card-body .card-content-grid",
				'media'    => 'medium',
				'enabled'  => ! empty( $item->grid_columns_medium ),
				'props'    => array(
					'grid-template-columns' => "repeat($item->grid_columns_medium, 1fr)",
				),
			)
		);

		FLBuilderCSS::rule(
			array(
				'selector' => ".fl-node-$id .component_bs_accordion .card.card-$i .card-body .card-content-grid",
				'media'    => 'responsive',
				'enabled'  => ! empty( $item->grid_columns_responsive ),
				'props'    => array(
					'grid-template-columns' => "repeat($item->grid_columns_responsive, 1fr)",
				),
			)
		);
	}
}
