jQuery(function($) {
	$fp_downloads = $('[data-js-downloads]');

	if (! $fp_downloads.length) {
		return;
	} else {

		var downloadsResize = function(component) {
			var winWidth = $(window).width();
			var thumbnailHeight = $('.pdf-content', component).height() + 80;
			var buttonWidth = $('.pdf-content a', this).width();
			var contentWidth = $(this).width() - buttonWidth;


			if (577 < winWidth) {
				$('.pdf-content span', component).css( {'width': contentWidth + 'px' } );
				if ($(component).hasClass('-with-thumbnail') ) {
					thumbnailHeight = $('.pdf-content', component).height() + 80;
					$('.pdf-thumbnail', component).css( {'height': thumbnailHeight + 'px'} );
				}
			} else {
				$('.pdf-content span', component).css( {'width': '' } );
				if ($(component).hasClass('-with-thumbnail') ) {
					$('.pdf-thumbnail', component).css( {'height': ''} );
				}
			}

		};

		$fp_downloads.each(function() {

			downloadsResize($(this) );


		} );

		$(window).on('resize', function() {
			$fp_downloads.each(function() {
				downloadsResize($(this) );
			} );
		} );
	}
} );
