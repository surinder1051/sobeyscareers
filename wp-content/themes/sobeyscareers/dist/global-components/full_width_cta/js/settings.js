(function($) {

	FLBuilder._registerModuleHelper('full_width_cta', {
		rules: {
			background_image: {
				required: true
			},
			heading: {
				required: {
					depends: function(element) {
						return ('' == $('.fl-field [name=\'text\']').val() );
					}
				}
			},
			text: {
				required: {
					depends: function(element) {
						return ('' == $('.fl-field [name=\'heading\']').val() );
					}
				}
			},
			link_url: {
				required: {
					depends: function(element) {
						return ('' != $('.fl-field [name=\'link_text\']').val() );
					}
				}
			}
		}
	} );

} (jQuery) );
