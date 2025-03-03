<<?php echo esc_attr( $settings->tag ); ?> class="fl-heading">
	<?php if ( ! empty( $settings->link ) ) : ?>
	<a href="<?php echo esc_url( do_shortcode( $settings->link ) ); ?>" title="<?php echo esc_attr( wp_strip_all_tags( $settings->heading ) ); ?>" <?php echo ( isset( $settings->link_download ) && 'yes' === $settings->link_download ) ? ' download' : ''; ?> target="<?php echo esc_attr( $settings->link_target ); ?>" <?php echo $module->get_rel(); ?>>
	<?php endif; ?>
	<span class="fl-heading-text"><?php echo $settings->heading; ?></span>
	<?php if ( ! empty( $settings->link ) ) : ?>
	</a>
	<?php endif; ?>
</<?php echo esc_attr( $settings->tag ); ?>>
