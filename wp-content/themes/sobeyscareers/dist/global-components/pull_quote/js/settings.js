(function($) {

	FLBuilder._registerModuleHelper('pull_quote', {
		rules: {
			title: {
				required: false
			},
			content: {
				required: true,
				maxlengthhtml: 325
			}
		},
		init: function() {
			restrict_editor_height();
			hide_video_and_media_buttons();
		}
	} );

} (jQuery) );
