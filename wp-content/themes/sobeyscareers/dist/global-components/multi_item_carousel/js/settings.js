(function($) {

	FLBuilder._registerModuleHelper('slides', {
		rules: {
			background: {
				required: true
			},
			content: {
				required: {
					depends: function(element) {
						return ('' == $('.fl-field [name=\'link_url\']').val() );
					}
				}
			},
			link_title: {
				required: {
					depends: function(element) {
						return ('' == $('.fl-field [name=\'content\']').val() );
					}
				}
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
		},
		submit: function() {
			return true;
		}
	} );

	FLBuilder._registerModuleHelper('multi_item_carousel', {
		rules: {
			'slides[]': {
				mincount: 2,
				maxcount: 10,
				required_json: {
					background: true
				}
			}
		},
		init: function() {
			monitor_repeater_length();
			bb_ajax_select_field_posts();
		},
		submit: function() {
			return true;
		}
	} );

} (jQuery) );
