(function($) {

	var $full_width_photo = $('[data-js-full_width_photo]');
	if (! $full_width_photo.length) {
		return; // Return if component isn't on the page
	} else {
		$full_width_photo.each(function() {
			if ($(this).hasClass('margins_off') ) {
				$(this).parents('.fl-row, .fl-module-content-row').css( {'margin': '0'} );
				$(this).parents('.fl-row-content-wrap').css( {'padding-left': '0', 'padding-right': '0'} );
			}
		} );
	}

} (jQuery) );
