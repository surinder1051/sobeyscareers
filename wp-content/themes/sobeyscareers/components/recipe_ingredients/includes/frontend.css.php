<?php

FLBuilderCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'     => 'text_typography',
	'selector'     => 'body .fl-node-' . $id . ' p',
));

?>

<?php if (!empty($settings->text_color)) : ?>
	.fl-node-<?php echo $id; ?> .component_recipe_ingredients p {
	color: #<?php echo $settings->text_color ?>;
	}
<?php endif; ?>

<?php if (!empty($settings->heading_color)) : ?>
	.fl-node-<?php echo $id; ?> .component_recipe_ingredients .title {
	color: #<?php echo $settings->heading_color ?>;
	}
<?php endif; ?>
