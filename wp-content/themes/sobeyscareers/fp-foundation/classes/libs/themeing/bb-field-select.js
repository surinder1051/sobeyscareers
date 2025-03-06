//Add a class to the selected option label to distinguish which one is selected
var bbCustomSelectField = function(element, parentEl, clearID) {
	if ( jQuery('#' + parentEl).length ) {
		var container = jQuery('#' + parentEl);
		var parLabel = jQuery(element).closest('label');
		jQuery( 'label', container ).each(function() {
			jQuery(this).attr('class', '');
		});
		jQuery(parLabel).addClass('is-checked');
		jQuery('#' + clearID).show();
	}
}

//this function allows an admin to clear/remove a selected item
var bbCustomSelectClear = function(element, parentEl, clearID) {
	if ( jQuery('#' + parentEl).length ) {
		var container = jQuery('#' + parentEl);
		jQuery( 'label', container ).each(function() {
			if ( jQuery(this).hasClass('is-checked')) {
				jQuery(this).removeClass('is-checked');
				jQuery('input', this).attr('checked', false);
			}
		});
		jQuery('#bb-select-transparent').attr('checked', true);
		jQuery('#' + clearID).hide();
	}
}
//show the theme select options in the custom colour picker
var bbCustomThemeShow = function(parentEl) {
	if ( jQuery('#' + parentEl).length ) {
		jQuery( '#' + parentEl + ' ul:eq(0)').removeClass('hidden');
	}
}
//clear the selected theme option in the custom colour picker
var bbCustomColourClear = function(element, parentEl) {
	if ( jQuery('#' + parentEl).length ) {
		jQuery('#bb-colour-saved-' + parentEl).val('');
		jQuery( '#' + parentEl + ' button:eq(0)').css({'background' : 'transparent'});
	}
}
