(function($) {

	var $animated_images = $('[data-js-animated_images]');

	//var has_loaded_images = false;
	if (! $animated_images.length) {
		return; // Return if component isn't on the page
	}

	$(window).on('scroll resize load', check_if_in_view);

	$(document).ready(function(e) {

		//lazyLoadImages();
	} );

	function check_if_in_view() {
		var window_height = $(window).height();
		var window_top_position = $(window).scrollTop();
		var window_bottom_position = (window_top_position + window_height / 1.5);

		$animated_images.find('.animation-element').each($, function() {
			var $element = $(this);
			var element_height = $element.outerHeight();
			var element_top_position = $element.offset().top;
			var element_bottom_position = (element_top_position + element_height / 1.5);

			//check to see if this current container is within viewport
			if ( (element_bottom_position >= window_top_position) &&
				(element_top_position <= window_bottom_position) ) {
				$element.addClass('in-view');
				$element.removeClass('out-view');

				// if (true !== has_loaded_images) {
				// 	lazyLoadImages();
				// 	has_loaded_images = true;
				// }
			} else {
				$element.removeClass('in-view');
				$element.addClass('out-view');
			}
		} );
	}

	function lazyLoadImages() {
		$animated_images.find('img').each(function() {
			var img_src = $(this).attr('src');
			var data_img_src = $(this).data('src');
			if ('' == (img_src) && ('' != data_img_src) ) {
				$(this).attr('src', data_img_src);
			}
		} );
	}

}(jQuery) );
