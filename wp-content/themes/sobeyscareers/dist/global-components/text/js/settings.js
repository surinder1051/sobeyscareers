(function($) {

	FLBuilder._registerModuleHelper('text', {
		rules: {
			fp_text_content: {
				required: true
			}
		},
		init: function() {
			try {
				bb_rest_video_upload();
			} catch (e) { }
			$('select[name=\'insert_video\']').show();
		}
	} );

}(jQuery) );
