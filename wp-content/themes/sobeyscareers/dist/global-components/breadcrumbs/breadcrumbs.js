(function($) {

	var $breadcrubms = $('[data-js-breadcrumbs]');
	if (! $breadcrubms.length) {
		return; // Return if component isn't on the page
	}

	$breadcrubms.find('.breadcrumb-mobile-icon').on('click', function() {
		$(this).closest('.breadcrumb-intro').find('.mobile-breadrumb').slideToggle();
	} );

	$breadcrubms.find('.close_btn').on('click', function() {
		$(this).closest('.breadcrumb-intro').find('.mobile-breadrumb').slideUp();
	} );

}(jQuery) );

