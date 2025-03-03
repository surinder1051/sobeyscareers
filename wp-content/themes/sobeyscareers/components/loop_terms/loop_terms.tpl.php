<div <?php $this->component_class('d-flex flex-column') ?> data-js-loop>
	<div class='card-deck flex-row d-flex'>
		<?php foreach ($atts['terms'] as $term) : ?>
			<?php $link = array(
					array(
						'link_url' => '#',
						'link_url_target' => '_self',
						'link_title' => 'Learn More',
					)
					); ?>
			<?php echo do_shortcode("[" . $atts['shortcode'] . "
			term_id='" .  $term->term_id .  "'
			no_container='" . $atts['no_container'] . "'
			]"); ?>
		<?php endforeach; ?>
	</div>
</div>