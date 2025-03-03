(function($) {

	FLBuilder._registerModuleHelper('slides', {
		rules: {
			background: {
				required: true
			},
			content: {
				required: true
			},
			link_url: {
				required: {
					depends: function(element) {
						return ('' != $('.fl-field [name=\'link_title\']').val() );
					}
				}
			}
		},
		init: function() {
			restrict_editor_height();
		}
	} );

	FLBuilder._registerModuleHelper('full_width_carousel', {
		rules: {
			'slides[]': {
				mincount: 1,
				required_json: {
					background: true,
					content: true
				}
			}
		},
		init: function() {
			monitor_repeater_length();
		}
	} );

} (jQuery) );
