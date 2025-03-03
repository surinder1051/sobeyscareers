(function($) {

	var $multi_item_carousel = $('[data-js-multi_item_carousel]');
	if (! $multi_item_carousel.length) {
		return; // Return if component isn't on the page
	}

	jQuery(document).ready(function() {

		jQuery('.box-slider').slick( {
			lazyLoad: 'ondemand',
			dots: false,
			infinite: true,
			slidesToShow: 1,
			centerMode: true,
			speed: 300,
			centerPadding: '50px',
			variableWidth: true
		}, 1000);

		jQuery('.box-slider').on('init', function(event, slick, currentSlide, nextSlide) {
			jQuery(this).parent().find('.current').text(1);
			jQuery(this).parent().find('.total').text(slick.slideCount);
		} );
		jQuery('.box-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
			jQuery(this).parent().find('.current').text(currentSlide + 1);
			jQuery(this).parent().find('.total').text(slick.slideCount);
		} );
		jQuery('.box-slider').on('afterChange', function(event, slick, currentSlide) {
			var regEx = new RegExp('(\d+)\sof');
			var onSlide = jQuery(this).parent().find('.current').attr('aria-label').match(/(\d+)\sof/);
			jQuery(this).parent().find('.current').text(currentSlide + 1);
			if (onSlide[1] ) {
				jQuery(this).parent().find('.current').attr('aria-label', jQuery(this).parent().find('.current').attr('aria-label').replace(onSlide[1], jQuery(this).parent().find('.current').text() ) );
			}
			jQuery(this).parent().find('.total').text(slick.slideCount);
		} );
		jQuery('.carousel-control-prev button').click(function() {
			jQuery(this).parents('.component_multi_item_carousel').find('.slick-prev').trigger('click');
		} );
		jQuery('.carousel-control-next button').click(function() {
			jQuery(this).parents('.component_multi_item_carousel').find('.slick-next').trigger('click');
		} );
	} );


} (jQuery) );
