(function($) {

	var fp_pullquote = $('[data-js-fp_pullquote]');
	if (! fp_pullquote.length) {
		return; // Return if component isn't on the page
	}
	fp_pullquote.each(function() {

		if ($(this).hasClass('pq-right') ) {
			$(this).parents('.fl-module.fl-module-pullquote').addClass('pq-right');
			$('.component_text, .fl-rich-text', $(this).parents('.fl-row') ).css( {'margin-top': '0', 'margin-bottom': '0'} );
		} else if ($(this).hasClass('pq-left') ) {
			$(this).parents('.fl-module.fl-module-pullquote').addClass('pq-left');
			$('.component_text, .fl-rich-text', $(this).parents('.fl-row') ).css( {'margin-top': '0', 'margin-bottom': '0'} );
		} else {
			$(this).parents('.fl-module.fl-module-pullquote').addClass('pq-center');
		}
	} );


}(jQuery) );
