<div <?php $this->component_class() ?> data-js-social_links>
	<?php if (!empty($title)) : ?>
		<<?php echo $title_tag ?> class="title"><?php esc_attr_e($title); ?></<?php echo $title_tag ?>>
	<?php endif; ?>
	<?php if (!empty($social_links)) : ?>
		<?php foreach ($social_links as $link) : ?>
			<a class="social-link <?php echo $link->icon ?>" target="<?php echo $link->url_target ?>" rel="<?php echo $link->rel ?>" href="<?php echo $link->url ?>" aria-label="<?php echo __($link->accessibility_label); ?>">
				<span class="social-icon <?php echo $link->icon ?>" role="presentation"></span>
			</a>
		<?php endforeach; ?>
	<?php endif; ?>
</div>