(function($) {

	FLBuilder._registerModuleHelper('event_list', {
		rules: {
			h2: {
				required: true
			}
		},
		init: function() {
			restrict_editor_height();
		}
	} );

} (jQuery) );
