(function($) {

	var $bs_accordion = $('[data-js-bs_accordion]');
	if (! $bs_accordion.length) {
		return; // Return if component isn't on the page
	}

	// Accessibility add open on enter.
	// https://www.w3.org/TR/wai-aria-practices-1.1/#accordion
	$('.component_bs_accordion .accordion-header').keypress(function(event) {
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if ('13' == keycode) {
			$(this).click();
		}
	} );

}(jQuery) );
