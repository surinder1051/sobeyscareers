(function($) {

	var $menu_dropdown = $('[data-js-menu_dropdown]');
	if (! $menu_dropdown.length) {
		return; // Return if component isn't on the page
	}

	$('.menu-item', $menu_dropdown).on('focus', function() {
		$('.menu-item', $menu_dropdown).removeClass('hover');
		if ($('body .is-tabbing').length) {
			$(this).addClass('focus-visible');
		}
	} ).on('keypress', function(e) {
		var keyed = (e.which) ? e.which : e.keyCode;
		if (13 == keyed) {
			$(this).toggleClass('hover');
			$(this).next('.menu-item').removeClass('hover');
			$('.menu-dropdown-content', this).attr('aria-hidden', false).attr('aria-live', 'polite');
		}
	} );

	$('.menu-item .dropdown-list a:last-child').on('blur', function() {
		$(this).closest('.menu-item').removeClass('hover');
		$(this).closest('.menu-dropdown-content').attr('aria-hidden', true).attr('aria-live', 'off');
	} );

}(jQuery) );
