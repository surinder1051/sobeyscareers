jQuery(function($) {

	jQuery.validator.addMethod('equals', function(value, element, param) {
		return this.optional(element) || value === param;
	}, jQuery.validator.format('You must enter {0}') );

	jQuery.validator.addMethod('notequals', function(value, element, param) {
		return this.optional(element) || value !== param;
	}, jQuery.validator.format('You must enter {0}') );

	jQuery.validator.addMethod('required_json', function(value, element, required_params) {

		// console.log('required_json = %o', arguments);
		value = JSON.parse(value);

		// console.log('value = %o', value);
		for (var param in required_params) {
			if (! value[param] || '' == value[param] ) {
				return false;
			}
		}
		return true;
	}, function(required_params) {
		return jQuery.validator.format(Object.keys(required_params) + ' values are required.');
		return false;
	} );

	jQuery.validator.addMethod('mincount', function(value, element, required_params) {
		var inputCount = jQuery('input[name="' + jQuery(element).attr('name') + '"]').length;
		if (inputCount < required_params) {
			return false;
		}
		return true;
	}, jQuery.validator.format('Minimum {0} forms is required.') );

	jQuery.validator.addMethod('maxlengthhtml', function(value, element, param) {
		value = value.replace(/"/g, '`');

		// value = $(value).text();
		value = value.replace(/(<([^>]+)>)/ig, '');
		var length = $.isArray(value) ? value.length : this.getLength(value, element);
		return this.optional(element) || length <= param;
	}, jQuery.validator.format('Maximum {0} characters allowed.') );

} );

