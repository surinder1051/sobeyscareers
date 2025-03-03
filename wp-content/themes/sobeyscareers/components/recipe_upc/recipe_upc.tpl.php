<?php global $post; ?>

<?php if (!empty($atts['upc'])) : ?>
	<div <?php $this->component_class() ?> data-js-recipe_upc>
		<p><?php echo __('UPC CODE:', FP_TD); ?> <?php echo preg_replace("/[^A-Za-z0-9 ]/", '', $atts['upc']) ?></p>    
	</div>
<?php endif; ?>