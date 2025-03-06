<?php
/**
 * Component template file
 *
 * @package fp-foundation
 */

?>
<div <?php esc_attr( $this->component_class() ); ?> data-js-hiroy>
	<?php if ( ! empty( $title ) ) : ?>
		<<?php echo esc_attr( $title_tag ); ?> class="title"><?php echo esc_attr( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>
	<?php endif; ?>
</div>
