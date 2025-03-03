<?php if (!empty($articlePDF)) : ?>
<div <?php $this->component_class() ?> data-js-downloads>
	<div class="pdf-download-wrapper">
		<div class="pdf-content">
			<?php if (isset($thumbnail)) : ?>
				<div class="pdf-thumbnail">
					<img src="<?php echo $thumbnail; ?>"  alt="<?php _e('PDF Featured Image', FP_TD); ?>" />
				</div>
			<?php endif; ?>
			<?php if (!empty($articleTitle) ) : ?>
			<span><?php echo $articleTitle;?></span>
			<?php endif; ?>
			<a href="<?php echo $articlePDF;?>" class="button" download="<?php echo $articlePDF;?>"><?php echo $download_button_text;?></a>
		</div>
	</div>
</div>
<?php endif;