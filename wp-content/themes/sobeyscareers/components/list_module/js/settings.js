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
			intro_content: {
				maxlengthhtml: 500
			},
			'list_items[]': {
				required: true,
				mincount: 1,
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

}(jQuery) );
