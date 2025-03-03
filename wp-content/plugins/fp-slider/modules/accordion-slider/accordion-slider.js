(function ($) {

	var $fp_accordion_slider = $('[data-js-accordion_slider]');
	if (!$fp_accordion_slider.length) {
		return; // Return if component isn't on the page
	}

	var winWidth = $(window).width();
	var updateCurrentSlide = function (currentSlide) {
		var parentCard = $(currentSlide).closest('.card');
		activeColour = $(parentCard).attr('data-button-active');
		$(currentSlide).addClass('show');
		$(parentCard).attr('aria-expanded', true).removeClass('collapsed');
		$('.button-icon', parentCard).css({
			'color': '#' + activeColour.replace('#', '')
		});
		$(currentSlide).css({
			width: '',
			display: '',
			overflow: '',
			visibility: ''
		});
		$('.status', parentCard).attr('aria-live', 'assertive');
	};

	var updatePrevSlide = function (prevSlide) {
		var parentCard = $(prevSlide).closest('.card');
		$(prevSlide).removeClass('show');
		$(parentCard).attr('aria-expanded', false).addClass('collapsed');
		$('.button-icon', parentCard).css({
			'color': ''
		});
		$(prevSlide).css({
			width: '',
			display: '',
			overflow: '',
			visibility: ''
		});
		$('.status', parentCard).attr('aria-live', 'off');
	};

	var sliderSetup = function (component) {
		var $this = '#' + $(this).attr('id');
		var breakpoint = $('.slider', component).attr('data-breakpoint');

		breakpoint = ('String' == typeof breakpoint) ? parseInt(breakpoint, 10) : 976;

		if (winWidth >= breakpoint) {
			if (!$('.slider', component).hasClass('large-screen')) {
				$('.mb-0', component).css({
					visibility: 'hidden'
				});
				$('.slider', component).addClass('large-screen').css({
					opacity: '1'
				});
				setTimeout(function () {
					$('.mb-0', component).css({
						visibility: ''
					});
				}, 200);
				$('.slide-0', component).removeClass('collapsed');
				$('.slide-0 .collapse', component).addClass('show');
			}
		} else {
			if ($('.slider', component).hasClass('large-screen')) {
				$('.mb-0', component).css({
					visibility: 'hidden'
				});
				$('.card', component).removeClass('expand');
				$('.slider', component).removeClass('large-screen').css({
					opacity: '1'
				});
				setTimeout(function () {
					$('.mb-0', component).css({
						visibility: ''
					});
				}, 200);
			}
		}
		setTimeout(function () {
			$('.card', component).removeClass('resizing');
		}, 250);

		if ('collapse' != $('.card:eq(0)', component).attr('data-toggle')) {
			$('.card', component).attr('data-toggle', 'collapse');

			var currentSlide = parseInt($('.card[aria-expanded="true"]', component).attr('data-index'), 10);
			var nextSlide = ((currentSlide + 1) < $('.card', component).length) ? (currentSlide + 1) : 0;
			var activeColour = '';

			$('.card', component).on('shown.bs.collapse', function () {
				var activeColour = $('.card[aria-expanded="true"]', component).attr('data-button-active');
				$('.button-icon', component).css({
					'color': ''
				});
				$('.card[aria-expanded="true"] .button-icon', component).css({
					'color': '#' + activeColour.replace('#', '')
				});
				if (breakpoint <= winWidth) {

					e.preventDefault();
				}
			}).on('show.bs.collapse', function (e) {

				if (breakpoint <= winWidth) {
					var totalSlides = $('.card', component).length;
					var i = 1;
					var rowWidth = $(component).parents('.fl-row').width();

					//do large screen actions
					e.preventDefault();
					currentSlide = $(e.currentTarget).attr('data-index');

					prevSlide = $('.card[aria-expanded="true"]', component).attr('data-index');

					$('.card:eq(' + currentSlide + ') .collapse', component).css({
						visibility: 'visible'
					});
					if (0 == currentSlide) {
						if (1 < totalSlides) {
							i = 1;
							for (i; i < totalSlides; i++) {
								$('.card:eq(' + i + ')', component).removeClass('expand');
								setTimeout(function () {
									updateCurrentSlide($('.card:eq(' + currentSlide + ') .collapse', component));
									updatePrevSlide($('.card:eq(' + prevSlide + ') .collapse', component));
								}, 500);
							}
						}
					} else {
						if (1 < totalSlides) {
							if (prevSlide > currentSlide) {
								$('.card:eq(' + prevSlide + ')', component).removeClass('expand');
							}
							for (i; i <= currentSlide; i++) {
								$('.card:eq(' + i + ')', component).addClass('expand');
							}
							setTimeout(function () {
								updateCurrentSlide($('.card:eq(' + currentSlide + ') .collapse', component));
								updatePrevSlide($('.card:eq(' + prevSlide + ') .collapse', component));
							}, 500);
						}
					}

					return false;
				}
			}).on('hide.bs.collapse', function (e) {
				if (breakpoint > winWidth) {
					currentSlide = parseInt($('.card[aria-expanded="true"]', component).attr('data-index'), 10);
					nextSlide = ((currentSlide + 1) < $('.card', component).length) ? (currentSlide + 1) : 0;
				} else {
					e.preventDefault();

					//do large screen actions
					return false;
				}
			}).on('hidden.bs.collapse', function () {
				if (0 == $('.card[aria-expanded="true"]', component).length) {
					$('.card:eq(' + nextSlide + ') .mb-0', component).trigger('click');
				}
			});
		}
	};

	$($fp_accordion_slider).each(function () {
		var component = $(this);
		sliderSetup($(this));
	});
	$(window).on('resize', function () {

		//only on horizontal resize
		if (winWidth !== $(this).width()) {
			winWidth = $(this).width();
			$($fp_accordion_slider).each(function () {
				$('.card', this).addClass('resizing');
				sliderSetup($(this));
			});
		}
	});

}(jQuery));