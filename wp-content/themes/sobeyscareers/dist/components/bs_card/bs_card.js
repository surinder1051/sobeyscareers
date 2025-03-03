(function($) {

	var $bs_card = $('[data-js-bs_card]');
	if (! $bs_card.length) {
		return; // Return if component isn't on the page
	}

	var winWidth = $(window).width();

	var resize_image_top = function(element) {
		if ($(element).attr('data-height') ) {
			var vw = $(element).width();
			var dh = $(element).attr('data-height');
			var	dw = $(element).attr('data-width');
			var calcH = vw / (dw / dh);
			$(element).css( {height: Math.ceil(calcH) + 'px'} );
		}
	};

	/**  Bs Card expand text **/
	//Bart - this is the original tru code
	jQuery('.expand_text').on('click', function() {
		var elem = jQuery(this).closest('.card-text');
		elem.find('.hidden_text').show();
		elem.find('.collapse_text').show();
		elem.find('.eclipse_more').hide();
		jQuery(this).hide();
		equalheight('.card-text-equal-height .card-text');
	} );

	jQuery('.collapse_text').on('click', function() {
		var elem = jQuery(this).closest('.card-text');
		elem.find('.hidden_text').hide();
		elem.find('.expand_text').show();
		elem.find('.eclipse_more').show();
		jQuery(this).hide();
		equalheight('.card-text-equal-height .card-text');
	} );

	//newer version of description text for accessibility
	$($bs_card).each(function() {
		if ($('.expand-description', this).length) {
			$('.expand-description', this).on('click', function() {
				var parText = $(this).closest('.card-text');
				$('.bs-more-description', parText).addClass('expanded').attr('aria-expanded', true).attr('aria-hidden', false).attr('aria-live', 'assertive');
				$('.collapse-description', parText).removeClass('hidden');
				$(this).addClass('hidden');
				$('.hellip', parText).addClass('hidden');
			} );
		}
		if ($('.collapse-description', this).length) {
			$('.collapse-description', this).on('click', function() {
				var parText = $(this).closest('.card-text');
				$('.bs-more-description', parText).removeClass('expanded').attr('aria-expanded', false).attr('aria-hidden', true).attr('aria-live', 'off');
				$('.expand-description', parText).removeClass('hidden');
				$(this).addClass('hidden');
				$('.hellip', parText).removeClass('hidden');
			} );
		}

		if ($('.card-img-top', this).length) {
			resize_image_top($('.card-img-top', this) );
		}
	} );

	$(window).on('resize', function() {
		if ($(this).width() != winWidth) {
			winWidth = $(this).width();
			$($bs_card).each(function() {
				if ($('.card-img-top', this).length) {
					resize_image_top($('.card-img-top', this) );
				}
			} );
		}
	} );

}(jQuery) );
