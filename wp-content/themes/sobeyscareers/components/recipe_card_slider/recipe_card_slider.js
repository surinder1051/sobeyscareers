(function($) {

	function initSlick(selector) {
		$(selector).slick( {
			slidesToShow: 4,
			slidesToScroll: 4,
			infinite: true,
			dots: true,
			responsive: [ {
					breakpoint: 992,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 3
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2
					}
				},
				{
					breakpoint: 580,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
			]
		} );
	}

	$(document).ready(function() {
		var $recipe_card_slider = $('[data-js-recipe_card_slider]');
		if (! $recipe_card_slider.length) {
			return; // Return if component isn't on the page
		}

		initSlick($recipe_card_slider.find('.recipe-card-slider-carousel') );
	} );

}(jQuery) );
