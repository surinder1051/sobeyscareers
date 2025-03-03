<?php if ( is_array( $atts['items'] ) && count( $atts['items'] ) > 0 ) : ?>
	<div id='node-<?php echo esc_attr( $node_id ); ?>' <?php $this->component_class(); ?> data-js-bs_accordion>
		<?php if ( $title ) : ?>
			<<?php echo isset( $title_tag ) ? esc_attr( $title_tag ) : 'h2'; ?>>
				<?php echo esc_html( $title ); ?>
			</<?php echo isset( $title_tag ) ? esc_attr( $title_tag ) : 'h2'; ?>>
		<?php endif; ?>

		<?php foreach ( $atts['items'] as $key => $item ) : ?>
			<?php if ( isset( $atts['posts'] ) && 0 == $key ) : ?>
				<?php $item->state = 'show'; ?>
			<?php endif; ?>
			<div class="card card-<?php echo esc_attr( $key ); ?> <?php echo ( isset( $item->state ) && 'show' !== $item->state ) ? 'collapsed' : ''; ?>" data-toggle="collapse" data-target="#collapse-<?php echo esc_attr( $node_id . '_' . $key ); ?>" aria-expanded="<?php echo ( isset( $item->state ) && 'show' !== $item->state ) ? 'false' : 'true'; ?>" aria-controls="collapse-<?php echo esc_attr( $node_id . '_' . $key ); ?>">
				<div class="card-header" id="headingOne-<?php echo esc_attr( $node_id . '-' . $key ); ?>">
					<<?php echo isset( $card_header_tag ) ? esc_attr( $card_header_tag ) : 'div'; ?> class="mb-0 accordion-header" tabindex="0" role="button">
						<?php echo esc_html( $item->title ); ?>
					</<?php echo isset( $card_header_tag ) ? esc_attr( $card_header_tag ) : 'div'; ?>>
				</div>

				<div id="collapse-<?php echo esc_attr( $node_id . '_' . $key ); ?>" class="collapse <?php echo ( isset( $item->state ) && 'show' === $item->state ) ? 'show' : ''; ?>" aria-labelledby="headingOne-<?php echo esc_attr( $node_id . '-' . $key ); ?>" data-parent="#node-<?php echo esc_attr( $node_id ); ?>">
					<div class="card-body" tabindex="0">
						<?php echo wp_kses_post( $item->text ); ?>
						<?php if ( isset( $item->custom_content ) && ! empty( $item->custom_content ) ) : ?>
							<div class="card-content-grid">
								<?php foreach ( $item->custom_content as $cc ) : ?>
									<div tabindex="0" class="grid-item<?php echo ! empty( $cc['image'] ) ? ' with-image' : ''; ?>">
										<?php if ( ! empty( $cc['image'] ) ) : ?>
											<div class="grid-image" style="width:<?php echo esc_attr( $cc['image_width'] ); ?>px">
												<?php echo wp_kses_post( $cc['image'] ); ?>
											</div>
										<?php endif; ?>
										<div class="grid-text">
											<div class="grid-heading">
												<?php echo esc_html( $cc['heading'] ); ?>
											</div>
											<div class="grid-subtext">
												<?php echo wp_kses_post( $cc['subtext'] ); ?>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php elseif ( isset( $_GET['fl_builder'] ) ) : ?>
	<strong><em>Data is missing for bs_accordion.</em></strong>
<?php endif; ?>