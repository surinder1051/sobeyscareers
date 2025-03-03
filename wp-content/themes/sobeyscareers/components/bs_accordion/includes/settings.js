(function($) {

	FLBuilder._registerModuleHelper('bs_accordion', {
		rules: {

			// h2: {
			// 	required: true
			// },
			// intro_content: {
			// 	maxlengthhtml: 500
			// },
			// 'list_items[]': {
			// 	required: true,
			// 	mincount: 2,
			// 	maxcount: 30,
			// 	required_json: {
			// 		item_title: true
			// 	}
			// }
		},
		init: function() {
			restrict_editor_height();
			monitor_repeater_length();
		}
	} );

}(jQuery) );
