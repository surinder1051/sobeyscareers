<div <?php $this->component_class() ?> data-js-recipe_characteristics>

	<?php if (!empty($terms) && is_array($terms)) : ?>

		<?php if (!empty($atts['title'])) : ?>
			<<?php echo $title_tag ?> class="title text-<?php echo $title_align ?>"><?php esc_attr_e($atts['title']); ?></<?php echo $title_tag ?>>
		<?php endif; ?>

		<?php foreach ($terms as $term) : ?>
			<span class='term_<?php echo $term->slug ?>'><a href='<?php echo get_term_link($term->term_id); ?>' aria-label="<?php echo __('View', FP_TD) . ' ' . $term->name . ' ' . __('recipe tag', FP_TD); ?>"><?php echo $term->name ?></a></span>
		<?php endforeach; ?>

	<?php elseif (is_user_logged_in()) : ?>
		
		<b><i>Data is missing for recipe_characteristics.</i></b>

	<?php endif; ?>

</div>