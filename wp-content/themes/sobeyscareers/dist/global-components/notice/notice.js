(function($) {

	var $notice = $('[data-js-notice]');
	if (! $notice.length) {
		return; // Return if component isn't on the page
	}

	$(document).ready(function(e) {

		if ($('.alert-notice').length) {
			$('body').addClass('has-notice');
		}

		$('.component_notice .dismiss').click(function(e) {
			e.preventDefault();
			$('body').removeClass('has-notice');
			jQuery(this).parent().fadeOut(function() {
				try {
					fpSetNavOffset();
				} catch (e) {
				}
			} );
		} );
	} );

}(jQuery) );
