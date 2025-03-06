// Force alt tags to be included when using BB Modules and attaching photos to those modules.

jQuery(document).on('mousedown', '.media-button-select', function () {
	var $alt_text = jQuery(document).find('[data-setting=\'alt\']').find('input');
	var $required_text = '<span style=\'display: block; float: left; width: 100%; text-align: center;\'>Required fields are marked *</span>';
	if (0 < $alt_text.length && 0 === $alt_text.val().length) {
		jQuery(this).attr('disabled', 'disabled');
		$alt_text.attr('placeholder', 'This is a required field!');
		$alt_text.attr('style', 'border: 1px solid red');
		alert('Please enter an alt text before you can select this photo.');

		jQuery($required_text).insertBefore('.compat-item');

		/*
			This timeout is here because it doesn't focus on the input field if you attempt to focus immediately.
		*/
		setTimeout(function () {
			$alt_text.focus();
		}, 300);
	}
});

jQuery(document).on('click', '.attachments-browser .thumbnail', function () {
	jQuery('label[data-setting=\'alt\']').find('.name').html('Alt Text *');
	jQuery(document).find('[data-setting=\'alt\']').find('input').addClass('placeholder-required');
});

jQuery(document).on('keyup', '.attachment-details > label.setting > input', function (e) {
	if ('alt' == jQuery(this).parent().attr('data-setting') && 0 < jQuery(this).val().length) {
		jQuery(document).find('.media-button-select').removeAttr('disabled');
	} else if (0 === jQuery(this).val().length) {
		jQuery(this).attr('placeholder', 'This is a required field!');
		jQuery(document).find('.media-button-select').attr('disabled', 'disabled');
	}
});