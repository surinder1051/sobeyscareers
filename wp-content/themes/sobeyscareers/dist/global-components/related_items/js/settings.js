(function($) {

	FLBuilder._registerModuleHelper('related_items', {
		rules: {
			intro_content: {
				maxlengthhtml: 110
			}
		},
		init: function() {
			bb_ajax_select_field_posts();
			monitor_repeater_length();
			restrict_editor_height();
		}

	} );

} (jQuery) );
