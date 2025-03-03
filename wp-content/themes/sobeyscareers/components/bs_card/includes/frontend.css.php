<?php
if (isset($settings->overlay_cta_theme) && !empty($settings->overlay_cta_theme)) :
	$buttonTheme = generate_theme($settings->overlay_cta_theme, 'button');
endif;

if (isset($settings->description_expand) && $settings->description_expand == 'yes') :
	$text_links = generate_theme('text_links', '');
endif;

$textLinkButtons = array();
if (!empty($settings->text_links)) :

	foreach($settings->text_links as $link) :
		if (isset($link->link_style) && $link->link_style == 'button' ) :
			if (!empty($link->link_button_theme)) :
				if (!isset($textLinkButtons[$link->link_button_theme])) :
					$textLinkButtons[$link->link_button_theme] = generate_theme($link->link_button_theme, 'button');
				endif;
			endif;
		endif;
	endforeach;
endif;

if (isset($text_links->default_colour) && !empty($text_links->default_colour) ) : ?>
.fl-node-<?php echo $id; ?>.fl-module-bs_card .component_bs_card .card .card-text button.no-style .icon {
	color: <?php echo $text_links->default_colour; ?>;
}
.fl-node-<?php echo $id; ?>.fl-module-bs_card .component_bs_card .card .card-text button.no-style:hover .icon {
	color: <?php echo $text_links->hover_colour; ?>;
}
<?php endif; ?>

<?php

FLBuilderCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'     => 'title_typography',
	'selector'     => 'body .fl-node-' . $id . ' .card-title',
));
fp_apply_style($id, '.card-title', 'color', $settings->title_color);

?>

<?php if (isset($settings->image_min_height) && !empty($settings->image_min_height)) : ?>
.fl-node-<?php echo $id; ?> .card-img-top {
	min-height: <?php echo $settings->image_min_height ?>px;
}
<?php endif; ?>

<?php if (isset($settings->image_max_height) && !empty($settings->image_max_height)) : ?>
.fl-node-<?php echo $id; ?> .card-img-top {
	max-height: <?php echo $settings->image_max_height; ?>px;
}
<?php endif; ?>

<?php if (isset($settings->overlay_cta_colour) && !empty($settings->overlay_cta_colour)) :
	$overlayRGB = FLBuilderColor::hex_to_rgb($settings->overlay_cta_colour);
	$overlayOpacity = (isset($settings->overlay_cta_opacity) && !empty($settings->overlay_cta_opacity) ? $settings->overlay_cta_opacity : 0.8);
?>

.fl-node-<?php echo $id; ?> .component_bs_card .overlay::before {
	background-color: rgba(<?php echo $overlayRGB['r'];?>, <?php echo $overlayRGB['g'];?>, <?php echo $overlayRGB['b'];?>, <?php echo $overlayOpacity;?>);
}

<?php endif; ?>

<?php if (isset($buttonTheme->default_colour)) : ?>
	.fl-node-<?php echo $id; ?> .component_bs_card .overlay .align-self-center {
		background-color: <?php echo $buttonTheme->default_colour; ?>;
		border-color: <?php echo $buttonTheme->default_colour; ?>;
		color: <?php echo $buttonTheme->text_colour; ?>;
	}
	.fl-node-<?php echo $id; ?> .component_bs_card .overlay .align-self-center:hover {
		color: <?php echo $buttonTheme->default_colour; ?>;
	}
<?php endif ?>

<?php if (!empty($textLinkButtons) ) :
	foreach($textLinkButtons as $theme => $btn_style) :
?>
	.fl-node-<?php echo $id; ?> .component_bs_card .card .text-links a.button.<?php echo str_replace(' ', '.', $theme);?> {
		background-color: <?php echo $btn_style->default_colour; ?>;
		border-color: <?php echo $btn_style->default_colour; ?>;
		color: <?php echo $btn_style->text_colour; ?>;
	}
	.fl-node-<?php echo $id; ?> .component_bs_card .card .text-links a.button.<?php echo str_replace(' ', '.', $theme);?>:hover {
		color: <?php echo $btn_style->default_colour; ?>;
	}
<?php
	endforeach;

endif;


FLBuilderCSS::dimension_field_rule( array(
	'settings'    => $settings,
	'setting_name'    => 'description_padding',
	'selector'    => ".fl-node-$id .component_bs_card .card-body",
	'unit'        => 'px',
	'props'       => array(
	  'padding-top'    => 'description_padding_top',
	  'padding-right'  => 'description_padding_right',
	  'padding-bottom' => 'description_padding_bottom',
	  'padding-left'   => 'description_padding_left',
	),
  ) );
