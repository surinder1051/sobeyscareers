(function($) {

	FLBuilder._registerModuleHelper('drawer', {
		rules: {
			expandable_title: {
				required: true
			},
			expandable_text: {
				required: true,
				maxlengthhtml: 200
			}
		},
		init: function() {
			restrict_editor_height();
		}
	} );

	FLBuilder._registerModuleHelper('support', {
		rules: {
			heading: {
				required: true
			},
			intro_content: {
				required: true,
				maxlengthhtml: 60
			},
			right_content: {
				maxlengthhtml: 200
			},
			'drawer[]': {
				mincount: 1,
				required_json: {
					expandable_title: true,
					expandable_text: true
				}
			},
			left_heading: {
				required: true
			},
			right_heading: {
				required: true
			}
		},
		init: function() {
			monitor_repeater_length();
			restrict_editor_height();
			$('#fl-builder-settings-section-left_side .fl-builder-settings-section-header').click();
			$('#fl-builder-settings-section-right_side .fl-builder-settings-section-header').click();
		}

	} );

}(jQuery) );
