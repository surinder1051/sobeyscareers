(function($) {

	var $multiCarousel = $('[data-js-multi_item_carousel]');
	if (! $multiCarousel.length) {
		return; // Return if component isn't on the page
	}

	var setCardHeight = function(component) {
		$('.card-body', component).css( { 'height': '' } );
		$('.card-image-wrap', component).css( { 'height': '' } );

		var cardHeight = $('.card:eq(0) .card-body', component).height();
		$('.card', component).each(function() {
			if ($('.card-body', this).height > cardHeight) {
				cardHeight = $('.card-body', this).height();
			}
			if ($('.card-body:eq(0)', this).hasClass('-with-description') ) {
				cardHeight += 10;
			}
		} );

		$('.card-body', component).css( { 'height': cardHeight + 'px' } );
	};

	$multiCarousel.each(function() {
		$('.carousel-wrapper', this).slick();
		setCardHeight(this);
	} );

	$(window).on('load resize', function() {
		$multiCarousel.each(function() {
			setTimeout(function() {
				setCardHeight(this);
			}, 500);
		} );
	} );

}(jQuery) );
