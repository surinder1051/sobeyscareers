(function($) {

	FLBuilder._registerModuleHelper('my_links', {
		rules: {
			nav_items_title: {
				required: true
			},
			nav_items_link: {
				required: true
			}
		}
	} );

	FLBuilder._registerModuleHelper('header', {
		rules: {
			masterhead_image: {
				required: true
			},
			heading: {
				required: false
			},
			intro_content: {
				required: false,
				maxlengthhtml: 500
			},
			nav_heading: {
				required: {
					depends: function(element) {
						return ('1' == $('.fl-field [name=\'header_nav\']').val() );
					}
				}
			},
			'my_links[]': {
				mincount: 1,
				required_json: {
					nav_items_title: true,
					nav_items_link: true
				}
			}
		},
		messages: {  // sibling of 'rules' object
			masterhead_image: {
				required: 'Missing Masthead Image'
			}
		},
		init: function() {
			restrict_editor_height();
		}
	} );

} (jQuery) );
