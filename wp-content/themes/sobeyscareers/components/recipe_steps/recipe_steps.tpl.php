<?php global $post; ?>

<div <?php $this->component_class() ?> data-js-recipe_steps>
	<?php if (!empty($atts['title'])) : ?>
		<<?php echo $title_tag ?> class="title text-<?php echo $title_align ?>"><?php esc_attr_e($atts['title']); ?></<?php echo $title_tag ?>>
	<?php endif; ?>
	<div role="document" class="component-content-wrapper">

		<?php if ($steps = get_post_meta($post->ID, '_steps_step', true)) : ?>
			<?php foreach ($steps as $key => $step) : ?>
				<dl class='d-flex' tabindex='0'>
					<dt class='col-2'><?php echo __('Step', FP_TD ); ?> <?php echo $key + 1 ?></dt>
					<?php if (isset($step)) : ?>
						<dd class='col'><?php echo $step ?></dd>
					<?php endif; ?>
				</dl>
			<?php endforeach ?>

		<?php elseif ($steps = get_field('steps', $post->ID)) : ?>
			<?php foreach ($steps as $key => $step) : ?>
				<dl class='d-flex' tabindex='0'>
					<dt class='col-2'><?php echo __('Step', FP_TD ); ?> <?php echo $key + 1 ?></dt>
					<?php if (isset($step['step'])) : ?>
						<dd class='col'><?php echo $step['step'] ?></dd>
					<?php endif; ?>
				</dl>
			<?php endforeach; ?>
		<?php elseif (is_user_logged_in()) : ?>
				  
			<b><i>Data is missing for recipe_steps.</i></b>

		<?php endif; ?>
	</div>
</div>