<div <?php $this->component_class() ?> data-js-post_taxonomies_list>

	<?php if (!empty($title)) : ?>
		<<?php echo $title_tag ?> class="title"><?php esc_attr_e($title); ?></<?php echo $title_tag ?>>
	<?php endif; ?>

	<?php if (!empty($atts['terms']) && is_array($atts['terms'])) : ?>
		<ul>
			<?php foreach ($terms as $term) : ?>
				<li>
					<a href='<?php echo get_term_link($term) ?>'><?php echo $term->name ?></a>
				</li>
			<?php endforeach; ?>
		</ul>

	<?php endif; ?>

</div>