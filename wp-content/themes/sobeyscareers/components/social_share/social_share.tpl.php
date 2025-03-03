<div <?php $this->component_class() ?> data-js-social_share>
	<?php if (!empty($title)) : ?>
		<<?php echo $title_tag ?> class="title"><?php esc_attr_e($title); ?></<?php echo $title_tag ?>>
	<?php endif; ?>

	<?php if (in_array('facebook', $atts['services'])) : ?>
		<a class="social-share facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $email_url ?>" aria-label="<?php echo __('Share this recipe on Facebook (Opens in a new window)', FP_TD); ?>" title="<?php echo __('Share on Facebook', FP_TD); ?>">
			<span class="fab fa-facebook-f social-icon" role="presentation"></span>
		</a>
	<?php endif; ?>

	<?php if (in_array('twitter', $atts['services'])) : ?>
		<a class="social-share twitter" target="_blank" href="https://twitter.com/intent/tweet?source=<?php echo $email_url ?>&text=<?php echo $email_title ?>:%20<?php echo $email_url ?>" aria-label="<?php echo __('Share this recipe on Twitter (Opens in a new window)', FP_TD); ?>" title="<?php echo __('Share on Twitter', FP_TD); ?>">
			<span class="fab fa-twitter social-icon" role="presentation"></span>
		</a>
	<?php endif; ?>

	<?php if (in_array('linkedin', $atts['services'])) : ?>
		<a class="social-share linkedin" target="_blank" href="https://www.linkedin.com/shareArticle?url=<?php echo $email_url ?>&title=<?php echo $email_title ?>" aria-label="<?php echo __('Share this recipe on LinkedIn (Opens in a new window)', FP_TD); ?>" title="<?php echo __('Share on LinkedIn', FP_TD); ?>">
			<span class="fab fa-linkedin social-icon" role="presentation"></span>
		</a>
	<?php endif; ?>

	<?php if (in_array('pinterest', $atts['services'])) : ?>
		<a class="social-share pinterest" target="_blank" href="https://pinterest.com/pin/create/bookmarklet/?url=<?php echo $email_url ?>description=<?php echo $email_title ?>" aria-label="<?php echo __('Share this recipe on Pinterest (Opens in a new window)', FP_TD); ?>" title="<?php echo __('Share on Pinterest', FP_TD); ?>">
			<span class="fab fa-pinterest social-icon" role="presentation"></span>
		</a>
	<?php endif; ?>

	<?php if (in_array('email', $atts['services'])) : ?>
		<a class="social-share email" target="_blank" href="mailto:?subject=<?php echo $email_title ?>&body=<?php echo $email_body ?>" arial-label="<?php echo __('Share this recipe by email (Opens in a new window)', FP_TD); ?>" title="<?php echo __('Share by Email', FP_TD); ?>">
			<span class="dashicons dashicons-before dashicons-email-alt social-icon" role="presentation"></span>
		</a>
	<?php endif; ?>

	<?php if (in_array('print', $atts['services'])) : ?>
		<a class="social-share print" onclick="singleRecipePrint()" href="#" arial-label="<?php echo __('Print Recipe', FP_TD); ?>" title="<?php echo __('Print Recipe', FP_TD); ?>">
			<span class="icon-recipe-print social-icon" role="presentation"></span>
		</a>
	<?php endif; ?>
</div>