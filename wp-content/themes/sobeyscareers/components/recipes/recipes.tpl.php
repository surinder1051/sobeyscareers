<div <?php $this->component_class() ?> data-js-recipies>
	<div class="container">
		<?php if (!empty($atts['title'])) : ?>
			<<?php echo $title_tag ?> class='text-<?php echo $title_align ?>'><?php echo $atts['title'] ?></<?php echo $title_tag ?>>
		<?php endif; ?>
		<?php if (!empty($atts['posts'])) : ?>
			<div class='row'>
				<?php foreach ($atts['posts'] as $post) : ?>
					<div class="col">
						<?php echo do_shortcode("[recipe_card]") ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
</div>