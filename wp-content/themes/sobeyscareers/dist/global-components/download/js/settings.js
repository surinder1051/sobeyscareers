(function($) {

	FLBuilder._registerModuleHelper('download', {
		rules: {
			title: {
				required: true
			},
			publish_date: {
				required: true
			},
			pages: {
				required: true
			},
			publisher: {
				required: true
			},
			link_english: {
				required: false
			}
		},
		init: function() {
			restrict_editor_height();
			bb_ajax_select_field_posts();
		}
	} );

} (jQuery) );
