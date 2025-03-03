<div <?php $this->component_class('d-flex justify-content-between'); ?> data-js-recipe_details>
	
	<?php foreach ($field_data as $single_data) : ?>
		<div class="tile tile_makes">
			<span class="icon <?php echo $single_data['icon']; ?>" role="presentation"></span>
			<dl>
				<dt>
				<?php if (isset($single_data['label'])): ?>
					<?php echo (!empty($total_time_text) && $single_data['label'] == 'Total Time' ?  $total_time_text : $single_data['label']); ?>
				<?php endif; ?>
				</dt>
				<dd>
				<?php if (isset($single_data['value'])): ?>
					<?php pll_e($single_data['value']); ?>
				<?php endif; ?>
				</dd>
			</dl>
		</div>
	<?php endforeach; ?>

</div>