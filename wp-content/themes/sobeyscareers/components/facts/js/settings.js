(function($) {

	FLBuilder._registerModuleHelper('fact', {
		rules: {
			fact_heading: {
				required: false
			},
			fact_sub_heading: {
				required: false
			},
			fact_content: {
				maxlengthhtml: 90
			}
		},
		init: function() {
			restrict_editor_height();
		}
	} );

	if ('function' === typeof mincount) {
		FLBuilder._registerModuleHelper('facts', {
			rules: {
				'facts[]': {
					mincount: 4,
					maxcount: 4,
					required_json: {
						fact_heading: true
					}
				}
			},
			init: function() {
				if ('function' === typeof monitor_repeater_length) {
					monitor_repeater_length();
				}
			}

		} );
	}

}(jQuery) );
