(function($) {
	FLBuilder._registerModuleHelper('chart', {
		rules: {
			ninja_form: {
				required: true
			}
		},
		init: function() {
			bb_ajax_select_field_posts();
		}

	} );

}(jQuery) );

