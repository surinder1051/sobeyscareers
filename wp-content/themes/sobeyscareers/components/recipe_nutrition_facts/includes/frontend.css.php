<?php

FLBuilderCSS::typography_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'title_typography',
	'selector'     => '.fl-node-' . $id . ' .title',
));

?>
<?php if (!empty($settings->background_color)) : ?>
	.fl-node-<?php echo $id; ?> .component_recipe_nutrition_facts {
	background-color: #<?php echo $settings->background_color ?>;
	}
<?php endif; ?>

<?php if (!empty($settings->heading_color)) : ?>
	.fl-node-<?php echo $id; ?> .title {
	color: #<?php echo $settings->heading_color ?>;
	}
<?php endif; ?>