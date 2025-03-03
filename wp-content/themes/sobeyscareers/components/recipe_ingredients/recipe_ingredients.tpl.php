<div <?php $this->component_class() ?> data-js-recipe_ingredients>
<?php if ( $atts['has_ingredients'] ) : ?>
	<?php if (!empty($atts['title'])) : ?>
		<<?php echo $title_tag ?> id="ingredientsHeading-<?php echo $module->node; ?>" class="title text-<?php echo $title_align ?>"><?php esc_attr_e($atts['title']); ?></<?php echo $title_tag ?>>
	<?php endif; ?>

	<?php if ( !empty( $atts['legacy_ingredients'] ) ) : ?>
		<?php foreach ($atts['legacy_ingredients'] as $ingredient) : ?>
			<dl class='d-flex' tabindex="0">
				<dt class='col-4 lb' aria-label="<?php echo __('Imperial Quantity', FP_TD);?>: <?php echo $ingredient['_quantity'].' '. $ingredient['_unit_imp'] ?>"><?php echo $ingredient['_quantity'].' '. $ingredient['_unit_imp'] ?></dt>
				<dd class='col-4 desc' aria-label="<?php echo __('Ingredient', FP_TD);?> <?php echo $ingredient['_ingredient'] ?>"><?php echo $ingredient['_ingredient'] ?></dd>
				<dd class='col-4 weight' aria-label="<?php echo __('Metric Quantity', FP_TD);?>: <?php echo $ingredient['_quantity_met'].' '.$ingredient['_unit_met'] ?>"><?php echo $ingredient['_quantity_met'].' '.$ingredient['_unit_met'] ?></dd>
			</dl>
		<?php endforeach ?>

	<?php elseif ( !empty( $atts['ingredients'] ) ) : ?>
		<?php foreach ($atts['ingredients'] as $ingredient) : ?>
			<dl class='d-flex' tabindex="0">
				<?php if (isset($ingredient['quantity'])) : ?>
					<dt class='col-4 lb' aria-label="<?php echo __('Imperial Quantity', FP_TD); ?>: <?php echo $ingredient['quantity']; ?><?php echo $ingredient['unit_imp'] ? ' ' . $ingredient['unit_imp'] : ''; ?>">
						<?php echo $ingredient['quantity']; ?><?php echo $ingredient['unit_imp'] ? ' ' . $ingredient['unit_imp'] : ''; ?>
					</dt>
				<?php endif; ?>

				<?php if (isset($ingredient['ingredient'])) : ?>
					<dd class='col-4 desc' aria-label="<?php echo __('Ingredient', FP_TD); ?>: <?php echo $ingredient['ingredient']; ?>">
						<?php echo $ingredient['ingredient']; ?>
					</dd>
				<?php endif; ?>

				<?php if (isset($ingredient['quantity_met'])) : ?>
					<dd class='col-4 weight' aria-label="<?php echo __('Metric Quantity', FP_TD); ?>: <?php echo $ingredient['quantity_met'] ?><?php echo $ingredient['unit_met'] ? ' ' . $ingredient['unit_met'] : ''; ?>">
						<?php echo $ingredient['quantity_met']; ?><?php echo $ingredient['unit_met'] ? ' ' . $ingredient['unit_met'] : ''; ?>
					</dd>
				<?php endif; ?>

			</dl>
		<?php endforeach; ?>

	<?php elseif ( !empty( $atts['general_ingredients'] ) ) : ?>
		
		<p><?php echo $atts['general_ingredients']; ?></p>

	<?php endif; ?>

<?php elseif (is_user_logged_in()) : ?>
	<b><i>Data is missing for recipe ingredients.</i></b>
<?php endif; ?>
</div>