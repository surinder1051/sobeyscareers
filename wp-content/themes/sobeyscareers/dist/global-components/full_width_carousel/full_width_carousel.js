(function($) {

	var $component_full_width_carousel = $('[data-js-full_width_carousel]');
	if (! $component_full_width_carousel.length) {
		return; // Return if component isn't on the page
	}

	jQuery(document).ready(function() {

		jQuery('body').on('click', '.component_full_width_carousel .carousel-control-prev', function() {
			jQuery(this).parent().carousel('prev');
		} );

		jQuery('body').on('click', '.component_full_width_carousel .carousel-control-next', function() {
			jQuery(this).parent().carousel('next');
		} );

		jQuery('.carousel', $component_full_width_carousel).on('slid.bs.carousel', function() {
			var currentIndex = jQuery(this).find('.carousel-item.active').index() + 1;
			var onSlide = jQuery(this).parent().find('.current').attr('aria-label').match(/(\d+)\sof/);
			var currentEl = jQuery(this).find('.current');
			jQuery(this).find('.current').text(currentIndex);
			if (onSlide[1] ) {
				jQuery(currentEl).attr('aria-label', jQuery(currentEl).attr('aria-label').replace(onSlide[1], jQuery(currentEl).text() ) );
			}
		} );

	} );


} (jQuery) );
