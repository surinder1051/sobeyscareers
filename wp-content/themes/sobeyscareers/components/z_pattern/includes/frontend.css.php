<?php

fp_apply_style($id, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], 'color', $settings->heading_color);

FLBuilderCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'     => 'heading_typography',
	'selector'     => 'body .fl-node-' . $id . ' .heading',
));

?>

.fl-node-<?php echo $id; ?> h3 {

<?php if (!empty($settings->heading_text_align)) : ?>
	text-align: <?php echo $settings->heading_text_align ?>;
<?php endif ?>

<?php if (!empty($settings->heading_font_style)) : ?>
	text-transform: <?php echo $settings->heading_font_style ?>;
<?php endif ?>

<?php if (!empty($settings->heading_font_size)) : ?>
	font-size: <?php echo $settings->heading_font_size ?>px;
<?php endif ?>

}

<?php if (!empty($settings->heading_font_size_medium)) : ?>
	@media (min-width: 800px) and (max-width: 1024px) {
	.fl-node-<?php echo $id ?> h3 {
	font-size: <?php echo $settings->heading_font_size_medium ?>px;
	}
	}
<?php endif    ?>

<?php if (!empty($settings->heading_font_size_responsive)) : ?>
	@media (max-width: 800px) {
	.fl-node-<?php echo $id ?> h3 {
	font-size: <?php echo $settings->heading_font_size_responsive ?>px;
	}
	}
<?php endif    ?>

@media (min-width: 768px) {
	.fl-node-<?php echo $id ?> .component_z_pattern .safety-container .image-container {
		width: <?php echo (100 - intval($settings->background_width)) ?>%;
	}
}

.fl-node-<?php echo $id ?> .component_z_pattern .safety-container .text-container {
<?php if (!empty($settings->bg_colour)) : ?>
	background-color: <?php echo $settings->bg_colour ?>;
<?php endif; ?>

<?php if (!empty($settings->text_padding)) : ?>
	padding-left: <?php echo $settings->text_padding ?>px;
	padding-right: <?php echo $settings->text_padding ?>px;
<?php endif; ?>
}


<?php if (!empty($settings->background_width)) : ?>
@media (min-width: 768px) {
	.fl-node-<?php echo $id ?> .component_z_pattern .safety-container .text-container {
		width: <?php echo $settings->background_width ?>%;
	}
}
<?php endif;