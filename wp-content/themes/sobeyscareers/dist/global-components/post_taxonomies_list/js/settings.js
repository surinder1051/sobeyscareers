(function($) {
	FLBuilder._registerModuleHelper('post_taxonomies_list', {
		rules: {
			title: {
				required: false
			}
		},
		init: function() {
			bb_ajax_select_field_posts();
			bb_ajax_select_field_taxonomies();
		}
	} );

}(jQuery) );
