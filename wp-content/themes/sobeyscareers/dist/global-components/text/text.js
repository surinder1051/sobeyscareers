(function($) {

	var $text = $('[data-js-text]');
	if (! $text.length) {
		return; // Return if component isn't on the page
	}

	$(document).on('click', '.fl-builder-video-cancel', function() {
		$('#insert_video_media').modal('hide');
	} );

} (jQuery) );
