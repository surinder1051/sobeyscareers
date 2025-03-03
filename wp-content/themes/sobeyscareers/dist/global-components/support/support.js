(function($) {

	var $support = $('[data-js-support]');
	var isTabFocus = function() {
		var tabID;
		$('.component_support .accordion').removeClass('is-tabbing');
		$('.component_support .accordion').each(function() {
			if ($(this).is(':focus') || $(this).is(':active') ) {
				$(this).addClass('is-tabbing');
			}
		} );
	};

	if (! $support.length) {
		return; // Return if component isn't on the page
	}

	$(document).ready(function() {
		$('.component_support .accordion').on('click', function() {
			$(this).toggleClass('active');
			$(this).next().slideToggle();
			if ($(this).hasClass('active') ) {
				$(this).attr('aria-expanded', true);
			} else {
				$(this).attr('aria-expanded', false);
			}
		} ).on('mouseover', function() {
			$(this).addClass('hover');
		} ).on('mouseout', function() {
			$(this).removeClass('hover');
		} );

		$(window).on('keyup', function(e) {
			var keyed = (e.which) ? e.which : e.keyCode;
			switch (keyed) {
				case 9:
					isTabFocus();
					break;
				case 38:
					isTabFocus();
					break;
				case 40:
					isTabFocus();
					break;
				case 27:
					$('.component_support .accordion.active.is-tabbing').trigger('click').blur().removeClass('is-tabbing');
					break;
				default:
					break;
			}

			//Shift + tab
			if (e.shiftKey && 9 == keyed && $('.component_support .accordion.is-tabbing').is(':focus') ) {
				$('.component_support .accordion.is-tabbing').trigger('click').blur().removeClass('is-tabbing');
			}
		} );
	} );


}(jQuery) );
