(function($) {

	var fp_watermark = $('[data-js-fp_watermark]');
	if (! fp_watermark.length) {
		return; // Return if component isn't on the page
	}
	fp_watermark.each(function() {
		$(this).parents('.fl-row').css( {'position': 'relative'} );
		$(this).parents('.fl-col-group').siblings('.fl-col-group').css( {'position': 'relative'} );
	} );


}(jQuery) );
