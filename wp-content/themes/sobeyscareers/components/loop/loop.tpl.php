<div <?php $this->component_class('d-flex flex-column') ?> data-js-loop>
	<div class='card-deck flex-row d-flex columns_<?php echo count($atts['posts']) ?>'>
		<?php foreach ($atts['posts'] as $post) : ?>
			<?php echo do_shortcode("[" . $atts['shortcode'] . " id='" .  $post->ID .  "' no_container='" . $atts['no_container'] . "']") ?>
		<?php endforeach; ?>
	</div>
</div>