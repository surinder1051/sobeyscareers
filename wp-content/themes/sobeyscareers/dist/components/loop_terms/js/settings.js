(function($) {
	FLBuilder._registerModuleHelper('loop_terms', {
		rules: {
		},
		init: function() {
			bb_ajax_select_field_posts();
			bb_ajax_select_field_taxonomies();
		}
	} );

}(jQuery) );
