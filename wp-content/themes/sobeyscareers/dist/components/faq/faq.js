(function($) {

	var winWidth = $(window).width();
	var $faq = $('[data-js-faq]');
	if (! $faq.length) {
		return; // Return if component isn't on the page
	}

	var itemHeight = function(component) {
		if (752 < winWidth) {
			var itemHeight = 0;
			var index = 0;
			$('span.category-item', component).each(function() {

				$(this).css( {'margin-top': itemHeight + 'px'} );
				itemHeight += $(this).outerHeight();
				index++;
			} );

		} else {

			$('span.category-item', component).css( {'margin-top': ''} );
		}
	};

	$($faq).each(function() {
		var component = $(this);
		$faq.find('span.category-item').on('click', function() {
			if (! $(this).hasClass('-active') ) {
				var target = $(this).attr('data-target');
				$('span.category-item', component).removeClass('-active');
				$(this).addClass('-active');
				$('.question-section', component).removeClass('-active');
				$('#' + target).addClass('-active');

				if (753 > winWidth) {
					$('html, body').animate( {
						scrollTop: $(this).offset().top
					}, 300);
				}
			}
		} ).on('keypress', function(e) {
			var keyed = (e.which) ? e.which : e.keyCode;
			if (13 == keyed) {
				$(this).trigger('click');
			}
		} ).on('keydown', function(e) {
			var keyed = (e.which) ? e.which : e.keyCode;
			var faqCount = $('span.category-item', component).length;
			var target = $(this).attr('data-target').split('-');
			var index = parseInt(target[2], 10);
			if (752 < winWidth) {
				if (40 == keyed) {
					if ( (1 + index) < faqCount) {
						$('span.category-item:eq(' + (index + 1) + ')', component).focus();
					}
				} else if (38 == keyed) {
					if (0 < index) {
						$('span.category-item:eq(' + (index - 1) + ')', component).focus();
					}
				}
			}
		} );
		itemHeight($(this) );
	} );


	$(window).on('resize', function() {
		if (winWidth != $(window).width() ) {
			winWidth = $(window).width();
			$($faq).each(function() {
				itemHeight($(this) );
			} ) ;
		}
	} );

}(jQuery) );
