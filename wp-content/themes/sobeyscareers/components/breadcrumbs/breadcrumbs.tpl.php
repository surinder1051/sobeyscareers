<?php
/**
 * Component template file
 *
 * @package fp-foundation
 */

?>
<?php if ( ( isset( $atts ) && ! empty( $atts ) ) || isset( $_GET['fl_builder'] ) ) : ?>
	<div <?php $this->component_class(); ?> data-js-breadcrumbs>
		<nav aria-label="breadcrumb">
			<?php if ( ! empty( $items ) ) : ?>
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="<?php echo esc_attr( defined( 'ICL_LANGUAGE_CODE' ) ? '/' . ICL_LANGUAGE_CODE : '/' ); ?>"><?php esc_html_e( 'Home', FP_TD ); ?></a>
					</li>
					<li class="breadcrumb-item">
						<span class="separator">
							<i class="<?php echo esc_attr( $separator_icon ); ?>" aria-hidden="true"></i>
						</span>
						<a href="<?php echo esc_attr( apply_filters( 'breadcrumbs_items_url', $items['url'] ) ); ?>">
							<?php echo esc_html( apply_filters( 'breadcrumbs_items_title', $items['title'] ) ); ?>
						</a>
					</li>
				</ol>
			<?php else : ?>
				<div class="breadcrumb">
					<div class="breadcrumb-item">
						<a href="<?php echo esc_attr( defined( 'ICL_LANGUAGE_CODE' ) ? '/' . ICL_LANGUAGE_CODE : '/' ); ?>"><?php esc_html_e( 'Home', FP_TD ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</nav>
	</div>
<?php endif; ?>
