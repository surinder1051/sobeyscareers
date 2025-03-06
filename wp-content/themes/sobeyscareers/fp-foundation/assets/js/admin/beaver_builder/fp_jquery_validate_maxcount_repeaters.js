// Checks if a repeater form field has reached maxium lenght, if so add button is removed

function hide_show_add_repeater_field() {
	for (var module in FLBuilder._moduleHelpers) {
		var module = FLBuilder._moduleHelpers[module];
		if (module.rules) {
			for (var field in module.rules) {
				if (-1 < field.indexOf('[]')) {
					var rule = module.rules[field];
					field = field.replace('[]', '');
					if (rule.maxcount <= jQuery('tr[data-field="' + field + '"]').length) {
						jQuery('a[data-field="' + field + '"]').hide();
						jQuery('tr[data-field="' + field + '"] .fl-builder-field-copy').hide();
					} else {
						jQuery('a[data-field="' + field + '"]').show();
						jQuery('tr[data-field="' + field + '"] .fl-builder-field-copy').show();
					}
				}
			}
		}
	}
}

function monitor_repeater_length() {
	jQuery('body').on('.fl-builder-field-add', 'click', window.hide_show_add_repeater_field);
	jQuery('body').on('.fl-builder-field-copy', 'click', window.hide_show_add_repeater_field);
	jQuery('body').on('.fl-builder-field-delete', 'click', window.hide_show_add_repeater_field);
	window.hide_show_add_repeater_field();
}

jQuery(function ($) {

	jQuery.validator.addMethod('maxcount', function (value, element, param) {
		return true;
	}, jQuery.validator.format('You must enter {0}'));

});

