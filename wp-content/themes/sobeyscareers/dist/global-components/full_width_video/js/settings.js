(function($) {

	FLBuilder._registerModuleHelper('full_width_video', {
		rules: {
			youtube_id: {
				required: true
			}
		},
		init: function() {

			// toggle showing new video fields when video drop down is set to "Add"
			bb_rest_video_upload();
			restrict_editor_height();
			bb_ajax_select_field_posts();
		}
	} );

} (jQuery) );
