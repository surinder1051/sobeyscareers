(function($) {
	var $full_width_gallery = $('[data-js-full_width_gallery]');
	var $lightBoxClicked, $lightBoxControls, $lightBoxNext;
	var lightboxBackward = function() {
		var next = $lightBoxNext - 1;
		$($lightBoxControls[$lightBoxNext], '.module-gallery-lightbox-wrap').focus();
		if (0 == $lightBoxNext) {
			next = ($lightBoxControls.length - 1);
		}
		$lightBoxNext = next;
	};
	var lightboxForward = function() {
		var next = 1 + $lightBoxNext;
		$($lightBoxControls[$lightBoxNext], '.module-gallery-lightbox-wrap').focus();
		if (next == $lightBoxControls.length) {
			next = 0;
		}
		$lightBoxNext = next;
	};
	if (! $full_width_gallery.length) {
		return; // Return if component isn't on the page
	} else {

		$('.component_full_width_gallery .module-gallery-thumb a').bind('keydown', function(event) {
			code = event.keyCode;
			if ( (13 === code) || (32 === code) ) {
				$(this).find('.light-box-item').click();
			}
		} );

		if ($('.module-gallery-lightbox-wrap'.length) ) {
			$('.light-box-item, .action-button', $full_width_gallery).on('click', function() {
				$lightBoxClicked = $(this);
				$lightBoxControls = $('.module-gallery-lightbox-wrap a');
				$lightBoxNext = (1 < $lightBoxControls.length) ? 1 : 0;
				$('.module-gallery-lightbox-wrap .lightbox-close').focus();
			} );
			$('.module-gallery-lightbox-wrap .lightbox-close').on('click', function() {
				$($lightBoxClicked).focus();
			} );
			$(window).on('keyup', function(e) {
				var keyed = (e.which) ? e.which : e.keyCode;
				var $target = e.target.className;
				switch (keyed) {
					case 9:
						if ($('.module-gallery-lightbox-wrap').is(':visible') ) {
							e.preventDefault();
							if (e.shiftKey) {
								lightboxBackward();
							} else {
								lightboxForward();
							}
						}
						break;
					case 27:

						//on escape, close the modal
						if ($('.module-gallery-lightbox-wrap').is(':visible') ) {
							$('.module-gallery-lightbox-wrap .lightbox-close').trigger('click');
						}
						break;
					case 13:
						if ($('.module-gallery-lightbox-wrap').is(':visible') ) {
							if ('nav-control' != $target.match(/nav\-control/) ) {
								$('.module-gallery-lightbox-wrap .lightbox-close').focus();
							} else {
								$(e).focus();
							}
						}
						break;
					default:
						break;
				}
			} );
		}
	}


}(jQuery) );
