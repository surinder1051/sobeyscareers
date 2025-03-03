(function($) {

	var $timeline_header = $('[data-js-timeline_header]');
	if (! $timeline_header.length) {
		return; // Return if component isn't on the page
	}

	var set_timeline_header_bg = function(component) {
		var winWidth = $(window).width();

		var lgBG = $(component).attr('data-bg-default');
		var medBG = $(component).attr('data-bg-medium');
		var smBG = $(component).attr('data-bg-small');

		if (768 > winWidth && 'undefined' != smBG) {
			$(component).css( {'background-image': 'url(' + smBG + ' )'} );
		} else if (993 > winWidth && 'undefined' != medBG) {
			$(component).css( {'background-image': 'url(' + medBG + ' )'} );
		} else {
			$(component).css( {'background-image': 'url(' + lgBG + ' )'} );
		}
	};

	$timeline_header.each(function() {
		set_timeline_header_bg($(this) );

		$('.timeline-scoll-container button', this).on('click', function() {
			var dataRow = $(this).attr('data-scroll');
			var flRow = $('.fl-row:eq(' + dataRow + ')');
			if ('udefined' !== dataRow && flRow.length) {

				$('html, body').animate( {
					scrollTop: $(flRow).offset().top
				}, 1000);
			}
		} );
	} );

	$(window).on('resize', function() {
		$timeline_header.each(function() {
			var $this = $(this);
			setTimeout(function() {
				set_timeline_header_bg($this);
			}, 500);
		} ).on('orientationChange', function() {
			$timeline_header.each(function() {
				var $this = $(this);
				setTimeout(function() {
					set_timeline_header_bg($this);
			}, 500);
			} );
		} );
	} );

}(jQuery) );
