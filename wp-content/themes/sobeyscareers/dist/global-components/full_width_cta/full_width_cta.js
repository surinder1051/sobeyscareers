(function($) {

	var $full_width_cta = $('[data-js-full_width_cta]');
	if (! $full_width_cta.length) {
		return; // Return if component isn't on the page
	}

	$(window).on('load', function() {
		setTimeout(function() {
			$('[data-js=nudge]').addClass('bounce-animate');
		}, 3000);
	} );

	$('.component_full_width_cta .scroll-box').click(function() {
		var offset = $full_width_cta.offset().top;
		var height = $full_width_cta.outerHeight();
		scroll_top = offset + height;
		$('html, body').animate( {
			scrollTop: scroll_top
		}, 600);
		return false;
	} );

}(jQuery) );
