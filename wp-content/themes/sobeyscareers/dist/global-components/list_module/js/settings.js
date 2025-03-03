(function($) {


	FLBuilder._registerModuleHelper('list_items', {
		rules: {
			item_title: {
				required: true
			}
		}
	} );

	FLBuilder._registerModuleHelper('list_module', {
		rules: {
			h2: {
				required: true
			},
			intro_content: {
				required: true,
				maxlengthhtml: 200
			},
			'list_items[]': {
				required: true,
				mincount: 2,
				maxcount: 30,
				required_json: {
					item_title: true
				}
			}
		},
		init: function() {
			restrict_editor_height();
			monitor_repeater_length();
		}
	} );

} (jQuery) );
