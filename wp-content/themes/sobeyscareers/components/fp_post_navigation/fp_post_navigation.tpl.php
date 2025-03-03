<?php
/**
 * FP Post Navigation template file
 *
 * @package fp-foundation
 */

if ( ! empty( $links ) ) : ?>
<div <?php $this->component_class(); //phpcs:ignore ?> data-js-fp_post_navigation>
	<div class="post-nav-wrapper">
		<?php if ( ! empty( $links['prev'] ) ) : ?>
			<div class="fp-nav-link <?php echo esc_attr( $links['prev']['class'] ); ?>">
				<?php if ( ! empty( $links['prev']['thumbnail'] ) ) : ?>
					<img class="nav-link-image" src="<?php echo esc_url( $links['prev']['thumbnail'] ); ?>" alt="<?php esc_attr_e( 'Featured Image - Previous Post', 'fp' ); ?>" />
				<?php endif; ?>
				<div class="nav-link-text">
					<small class="nav-text"><?php esc_attr_e( 'Previous Post', FP_TD ); //phpcs:ignore ?></small>
					<a  class="nav-link"href="<?php echo esc_url( $links['prev']['url'] ); ?>">
						<span class="link-title"><?php echo esc_attr( $links['prev']['title'] ); ?></span>
						<br />
						<span class="icon-arrow-left"></span>
					</a>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $links['next'] ) ) : ?>
			<div class="fp-nav-link <?php echo esc_attr( $links['next']['class'] ); ?>">
				<?php if ( ! empty( $links['next']['thumbnail'] ) ) : ?>
					<img class="nav-link-image" src="<?php echo esc_url( $links['next']['thumbnail'] ); ?>" alt="<?php esc_attr_e( 'Featured Image - Next Post', FP_TD ); //phpcs:ignore ?>" />
				<?php endif; ?>
				<div class="nav-link-text">
					<small class="nav-text"><?php esc_attr_e( 'Next Post', FP_TD ); //phpcs:ignore ?></small>
					<a class="nav-link" href="<?php echo esc_url( $links['next']['url'] ); ?>">
						<span class="link-title"><?php echo esc_attr( $links['next']['title'] ); ?></span>
						<br />
						<span class="icon-arrow-right"></span>
					</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
	<?php
endif;
