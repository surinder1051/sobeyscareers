(function($) {

	var $slider = $('[data-js-slider]');
	if (! $slider.length) {
		return; // Return if component isn't on the page
	}

	// return;
	$('[data-js-slider] .slider').on('init', function(event, slick) {
		$('.slick-dots button').each(function() {
			$(this).html('<span class="screen-reader-text">' + $(this).html() + '</span>');
		} );
	} );
	jQuery('[data-js-slider] .slider').slick( {
		dots: true,
		infinite: true,
		speed: 600,
		accessibility: true,
		autoplay: true,
		autoplaySpeed: 3500,
		slidesToShow: 1,
		slidesToScroll: 1,
		responsive: [ {
			breakpoint: 991,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				infinite: true,
				dots: true
			}
		},
		{
			breakpoint: 600,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}
		]
	} );

}(jQuery) );

jQuery(document).ready(function($) {
	$('.slick-dots .slick-active button').on('focus', function() {
		if ($(this).hasClass('is-tabbing') ) {
			$('.slick-dots button').attr('tabindex', 0);
		}
	} );
} );
jQuery(window).on('load', function() {
	jQuery('.component_slider').css( {'opacity': '1', 'min-height': 'auto'} );
} );
