(function($) {

	function handleButton(e) {
		var url = $(this).data('url');
		var target = $(this).data('target');

		if ('_blank' === target) {
			window.open(url, '_blank');
		} else {
			window.location = url;
		}
	}

	function initSlick() {
		$('#recipe-slider-carousel').slick( {
			slidesToShow: 3,
			slidesToScroll: 3,
			infinite: true,
			dots: true,
			responsive: [
				{
					breakpoint: 769,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						infinite: true,
						dots: true
					}
				}
			]
		} );
	}

	$(document).ready(function() {
		var $recipe_slider = $('[data-js-recipe_slider]');
		if (! $recipe_slider.length) {
			return; // Return if component isn't on the page
		}
		initSlick();
		$(document).on('click', '[data-js-recipe_slider] button.recipe-btn', handleButton);
	} );

}(jQuery) );
