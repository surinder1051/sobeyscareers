<?php global $post; ?>

<div <?php $this->component_class(); ?> data-js-recipe_nutrition_facts>

	<?php if (isset($atts['meta']['nutrition_info_calories'][0]) && !empty($atts['meta']['nutrition_info_calories'][0])) : ?>
		<?php if (!empty($atts['title'])) : ?>
			<<?php echo $title_tag; ?> class="title text-<?php echo $title_align; ?>" id="nutritionFactsHeader-<?php echo $node_id; ?>"><?php echo $atts['title']; ?></<?php echo $title_tag; ?>>
		<?php endif; ?>
		<div role="document" tabindex="0" class="component-content-wrapper">
			<?php foreach ($atts['show_these_fields'] as $title => $field_data) :
				$val = get_post_meta($post->ID, $field_data['key'], true);
				$val = !empty($val) ? trim($val) : "";
			?>
				<?php if (isset($atts['show_daily_value']) && $atts['show_daily_value'] == 'true' &&  $field_data['key'] == 'nutrition_info_calories') : ?>
					<dl class="row">
						<dt></dt>
						<dd class="col bold_text"><strong><?php echo __('Amount', FP_TD) ?></strong></dd>
						<dd class="col text-right bold_text"><strong><?php echo __('% of Daily Value', FP_TD) ?></strong></dd>
					</dl>
				<?php endif; ?>

				<?php
				$dailyvalue = get_post_meta($post->ID, $field_data['key'] . '_daily_value', true);
				$dailyvalue = !empty($dailyvalue) ? trim($dailyvalue) : "";
				?>

				<?php if (!empty($val)) : ?>
					<dl class="row <?php echo $field_data['key']; ?>">

						<?php $value = apply_filters('nutrition_facts_unit', $val, $field_data['key'], 10, 4); ?>

						<?php if (isset($atts['show_daily_value']) && $atts['show_daily_value'] == 'true' && !in_array($field_data['key'], ['general_serving_Size_amount'])) : ?>

							<?php $dailyvalue = get_post_meta($post->ID, $field_data['key'] . '_daily_value', true); ?>

							<dt class="col"><strong><?php echo $field_data['title']; ?></strong>: <?php echo $value; ?></dt>
							<?php if (!empty($dailyvalue)) : ?>
								<dd class="col text-right"><?php echo $dailyvalue; ?>%</dd>
							<?php endif; ?>

						<?php else : ?>

							<dt class="col"><strong><?php echo $field_data['title']; ?></strong>:</dt>
							<dd class="col text-right"><?php echo $value; ?></dd>

						<?php endif; ?>

					</dl>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php elseif (is_user_logged_in()) : ?>
		<b><i>Data is missing for recipe nutrition facts module.</i></b>
	<?php endif; ?>

</div>