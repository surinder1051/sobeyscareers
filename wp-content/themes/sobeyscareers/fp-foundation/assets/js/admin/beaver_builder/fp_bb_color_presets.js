// Prevent color picker from appearing and only show color presets

jQuery(document).ready(function ($) {

	$(document).off('click', '.fl-color-picker-color, .fl-color-picker-preset');
	$(document).on('click', '.fl-color-picker-color, .fl-color-picker-preset', function (e) {

		$('.fl-color-picker-ui .iris-picker').css( { 'visibility' : 'hidden' });
		$('.fl-color-picker-presets-toggle, .fl-color-picker-preset-remove').hide();
		$('.fl-color-picker-active').trigger('click').removeClass('fl-color-picker-active');
		$('.fl-color-picker-presets-list').show();
		if ( $('.fl-alpha-wrap').length ) {
			$('.fl-alpha-wrap').css({'z-index' : '500', 'right' : '15px' } );
		}

	});

	$(document).on('click', '.fl-color-picker-preset', function (e) {

		e.preventDefault();

	});

});
