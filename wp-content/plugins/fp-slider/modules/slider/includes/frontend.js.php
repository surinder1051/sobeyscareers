(function ($) {
	var $slider = $('.fl-node-<?php echo $id; ?> .bbmodule-slider .slider');
	var $slickNav = (<?php echo $settings->show_dots; ?> && '<?php echo $settings->dot_position; ?>' == 'below') ? $slider.next('.slick-nav') : $slider;

	$slider.on('init', function () {
		$('.slick-dots button').each(function () {
			$(this).html('<span class="screen-reader-text">' + $(this).html() + '</span>');
		});

		if (<?php echo $settings->show_play; ?> && '<?php echo $settings->dot_position; ?>' == 'below') {
			$slickNav.addClass('has-play').prepend('<button class="play" aria-label="<?php echo __( 'Play', FP_TD ); ?>"></button>');
			var $playButton = $slickNav.find('.play');

			if (<?php echo $settings->autoplay; ?>) {
				$playButton.addClass('paused').attr('aria-label', '<?php echo __( 'Pause', FP_TD ); ?>');
			}

			$playButton.on('click', function() {
				if($(this).hasClass('paused')) {
					$(this).removeClass('paused').attr('aria-label', '<?php echo __( 'Play', FP_TD ); ?>');
					$slider.slick('slickPause');
				} else {
					$(this).addClass('paused').attr('aria-label', '<?php echo __( 'Pause', FP_TD ); ?>');
					$slider.slick('slickPlay');
				}
			})
		}

		$(this).on('focusin', function() {
			$slider.slick('slickSetOption', 'focusOnChange', true);
		}).on('focusout', function() {
			$slider.slick('slickSetOption', 'focusOnChange', false);
		});

	}).on('afterChange init', function () {
		if ($('.slick-dots .slick-active button').hasClass('is-tabbing')) {
			$('.slick-dots button').attr('tabindex', 0);
		}
	}).slick({
		dots: <?php echo $settings->show_dots; ?>,
		appendDots: $slickNav,
		arrows: <?php echo $settings->show_arrows; ?>,
		infinite: true,
		speed: 600,
		accessibility: true,
		focusOnChange: false,
		autoplay: <?php echo $settings->autoplay; ?>,
		autoplaySpeed: 3500,
		slidesToShow: 1,
		slidesToScroll: 1,
		responsive: [
			{
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
	});

}(jQuery));
