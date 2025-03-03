<?php
$listLink = generate_theme($settings->list_link_colour, 'a');

if (isset($settings->ol_list_colour)) :
	if (!empty($settings->ol_list_colour)) :
		$olBG = generate_theme($settings->ol_list_colour, 'background');
		if (isset($olBG->default_colour) && !empty($olBG->default_colour)) : ?>

			.fl-node-<?php echo $id; ?> .component_list_module.-ordered ol.style-background li:before {
				background-color: <?php echo $olBG->default_colour; ?>;
				color: <?php echo $olBG->text_colour; ?>;
			}
			.fl-node-<?php echo $id; ?> .component_list_module.-ordered ol.style-checkmark li span {
				background-color: <?php echo $olBG->default_colour; ?>;
			}
			.fl-node-<?php echo $id; ?> .component_list_module.-ordered ol.style-checkmark li span:before {
				border-color: <?php echo $olBG->text_colour; ?>;
			}
			.fl-node-<?php echo $id; ?> .component_list_module.-unordered ul.style-bullet li:before {
				background-color: <?php echo $olBG->default_colour; ?>;
			}
<?php
		endif;
	endif;

endif;

if (isset($settings->list_default_colour) && !empty($settings->list_default_colour)) :

?>
.fl-node-<?php echo $id; ?> .component_list_module.-<?php echo $settings->list_toggle;?> ul li {
	color: #<?php echo $settings->list_default_colour;?>;
}
<?php

endif;

if (isset($listLink->default_colour) && !empty($listLink->default_colour)) :

?>
	.fl-node-<?php echo $id; ?> .component_list_module.-unordered ul li a,
	.fl-node-<?php echo $id; ?> .component_list_module.-unordered ul li a:visited,
	.fl-node-<?php echo $id; ?> .component_list_module.-ordered ol li a,
	.fl-node-<?php echo $id; ?> .component_list_module.-ordered ol li a:visited {
		color: <?php echo $listLink->default_colour; ?>;
	}
	.fl-node-<?php echo $id; ?> .component_list_module.-unordered ul li a:hover,
	.fl-node-<?php echo $id; ?> .component_list_module.-unordered ul li a:visited:hover,
	.fl-node-<?php echo $id; ?> .component_list_module.-ordered ol li a:hover,
	.fl-node-<?php echo $id; ?> .component_list_module.-ordered ol li a:visited:hover {
		color: <?php echo $listLink->hover_colour; ?>;
	}
	<?php

endif;

FLBuilderCSS::dimension_field_rule( array (
	'settings'    => $settings,
	'setting_name'    => 'list_padding',
	'selector'    => ".fl-node-$id .component_list_module.-ordered ol." . $settings->ol_list_style . " li",
	'unit'        => 'px',
	'props'       => array (
		'margin-top'    => 'list_item_margin_top',
		'margin-right'  => 'list_item_margin_right',
		'margin-bottom' => 'list_item_margin_bottom',
		'margin-left'   => 'list_item_margin_left',
	),
) );

/**
 * Renders the rule/properties for a dimension field.
 */
FLBuilderCSS::dimension_field_rule( array (
	'settings'    => $settings,
	'setting_name'    => 'list_padding',
	'selector'    => ".fl-node-$id .component_list_module.-unordered ul.style-bullet li",
	'unit'        => 'px',
	'props'       => array (
		'margin-top'    => 'list_item_margin_top',
		'margin-right'  => 'list_item_margin_right',
		'margin-bottom' => 'list_item_margin_bottom',
		'margin-left'   => 'list_item_margin_left',
	),
) );
