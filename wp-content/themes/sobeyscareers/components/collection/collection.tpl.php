<div <?php $this->component_class() ?> data-js-collection>
	<?php if (isset($related_content) && is_array($related_content)) : ?>
		<div class='card-deck flex-row d-flex'>
			<?php foreach ($related_content as $key => $content) : ?>
				<?php $key = $key + 1; ?>
				<?php echo do_shortcode("[recipe_card id='$content->ID' index='$key / $related_count']") ?>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		Missing collection content.
	<?php endif; ?>
</div>