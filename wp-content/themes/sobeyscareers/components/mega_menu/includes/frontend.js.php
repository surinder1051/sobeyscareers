var parCol = jQuery('.fl-node-<?php echo $id;?>').closest('.fl-col');
<?php
//screen display options
if (isset($settings->mm_display) && !empty($settings->mm_display) && is_countable($settings->mm_display)) :
?>
	jQuery(parCol).addClass('mm-<?php echo $id; ?>');
<?php
	if (in_array('-show-mobile', $settings->mm_display)) :
?>
	jQuery(parCol).addClass('-show-mobile');

<?php
	endif;
	if (in_array('-show-tablet', $settings->mm_display)) :
?>
	jQuery(parCol).addClass('-show-tablet');
<?php
	endif;
	if (in_array('-show-desktop', $settings->mm_display)) :
?>
	jQuery(parCol).addClass('-show-desktop');
<?php
	endif;

endif;