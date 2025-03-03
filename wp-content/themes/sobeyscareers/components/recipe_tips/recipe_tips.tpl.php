<?php global $post; ?>

<div <?php $this->component_class() ?> data-js-recipe_tips>
	<?php if ($tips = get_field('tips', $post->ID)) : ?>
		<?php foreach ($tips as $key => $tip) : ?>
			<?php if (isset($tip['tip']) && !empty($tip['tip'])) : ?>
				<?php if (!empty($title) && $key == 0) : ?>
					<<?php echo $title_tag ?> class="title"><?php esc_attr_e($title); ?></<?php echo $title_tag ?>>
				<?php endif; ?>
				<div>
					<?php echo $tip['tip'] ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>