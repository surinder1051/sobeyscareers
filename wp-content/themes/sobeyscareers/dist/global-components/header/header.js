(function($) {

	var $header_video_bg = $('.fl-row-bg-video .component_header');
	if (! $header_video_bg.length) {
		return; // Return if component isn't on the page
	}

	var $video_row = $header_video_bg.parents('.fl-row-bg-video');

	$(window).resize(function() {
		size_header_video();
	} );

	$('.fl-bg-video').initialize(function() {
		var $header_video_bg = $('.fl-row-bg-video .component_header');
		var $video_row = $header_video_bg.parents('.fl-row-bg-video');
		$video_row.find('.fl-bg-video-player').css('opacity', 1);
		$video_row.addClass('video_bg_resize');
		setTimeout(function() {
			size_header_video();
		}, 500);
	} );

}(jQuery) );

function size_header_video() {
	$video_row = jQuery('.video_bg_resize');
	h = $video_row.find('img.dummy').height();
	w = $video_row.find('img.dummy').width();
	offset = $video_row.find('img.dummy').offset();
	content_offset = jQuery('.fl-row-content-wrap').offset();
	if (! offset || ! content_offset) {
		return;
	}
	$video_row.find('.fl-bg-video').css('left', offset.left - content_offset.left).css('top', 0).width(w).height(h);
}
