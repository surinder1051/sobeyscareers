(function($) {
	FLBuilder._registerModuleHelper('timeline', {
		rules: {
			timeline_postid: {
				required: true
			}
		},
		init: function() {
			bb_ajax_select_field_posts();
		}
	} );
} (jQuery) );
