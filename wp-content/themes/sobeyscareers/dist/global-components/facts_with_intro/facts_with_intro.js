(function($) {

	var $facts_with_intro = $('[data-js-facts_with_intro]');

	var statsCounter = function() {
		var calloutRow = document.querySelectorAll('.component_facts_with_intro');
		var i = 0;

		//calloutRow.forEach(function(callout) {
		for (i; i < calloutRow.length; i++) {
			var callout = calloutRow[i];
			var statsItems = $('[data-end]', callout);
			var yPosition = callout.getBoundingClientRect();

			if ($(statsItems).length && yPosition.top <= window.innerHeight && 0 <= yPosition.bottom) {
				$('.stat-counter', callout).each(function() {
					var dataEnd = $(this).attr('data-end');
					var $this = $(this);
					$( { countNum: $this.text()} ).animate( {
						countNum: dataEnd
					}, {
						duration: 4000,
						easing: 'linear',
						step: function() {
							$this.text(Math.floor(this.countNum) );
						},
						complete: function() {
							$this.text(this.countNum);

						}
					} );
				} );
			}
		}
	};

	if (! $facts_with_intro.length) {
		return; // Return if component isn't on the page
	}

	setTimeout(function() {
		statsCounter();
	}, 250);

	$(window).on('scroll', function() {
		setTimeout(function() {
			statsCounter();
		}, 250);
	} );
	$(window).on('orientationchange', function() {
		setTimeout(function() {
			statsCounter();
		}, 250);
	} );

} (jQuery) );


