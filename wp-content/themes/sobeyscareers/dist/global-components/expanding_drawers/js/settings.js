(function($) {

	FLBuilder._registerModuleHelper('expanding_drawer', {
		rules: {
			expandable_title: {
				required: true
			},
			expandable_text: {
				required: true
			}
		}
	} );

	FLBuilder._registerModuleHelper('expanding_drawers', {
		rules: {
			'expanding_items[]': {
				mincount: 1,
				maxcount: 8,
				required_json: {
					expandable_title: true,
					expandable_text: true
				}
			}
		},
		init: function() {
			restrict_editor_height();
			monitor_repeater_length();
		}

	} );

} (jQuery) );
