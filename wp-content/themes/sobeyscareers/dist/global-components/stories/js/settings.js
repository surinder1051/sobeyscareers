(function($) {

	FLBuilder._registerModuleHelper('stories', {
		rules: {
			top_tag: {
				required: true
			}
		},
		init: function() {
			bb_ajax_select_field_posts();
		}
	} );

} (jQuery) );
