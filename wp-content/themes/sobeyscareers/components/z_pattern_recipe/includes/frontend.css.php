<?php
if (!empty($settings->content_theme)) :
	$contentTheme = generate_theme($settings->content_theme, 'background');
	if (isset($contentTheme->text_colour)) :
?>
	.fl-node-<?php echo $id; ?> .component_z_pattern_recipe h1,
	.fl-node-<?php echo $id; ?> .component_z_pattern_recipe h2,
	.fl-node-<?php echo $id; ?> .component_z_pattern_recipe h3 {
		color: <?php echo $contentTheme->text_colour;?>;
	}
	<?php if ($contentTheme->default_colour != '#ffffff') : ?>
	.fl-node-<?php echo $id; ?> .component_z_pattern_recipe .field_editor {
		border-bottom-color: #<?php echo FLBuilderColor::adjust_brightness( str_replace('#','', $contentTheme->default_colour), 12, 'lighten' ); ?>;
		border-top-color: #<?php echo FLBuilderColor::adjust_brightness( str_replace('#','', $contentTheme->default_colour), 12, 'lighten' ); ?>;
	}
	<?php endif; ?>
	@media screen and (min-width: 768px) {
		.fl-module-z_pattern_recipe.fl-node-<?php echo $id; ?> {
			background-color: <?php echo $contentTheme->default_colour;?>;
		}
	}
<?php endif;
endif;
?>

<?php
FLBuilderCSS::typography_field_rule( array(
	'selector'     => ".fl-node-$id .component_z_pattern_recipe .zpattern-heading",
	'setting_name' => 'heading_typography',
	'settings'     => $settings,
) );