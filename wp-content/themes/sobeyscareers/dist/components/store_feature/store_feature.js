(function($) {

	/**	Helper */
	$.fn.resized = function(callback, timeout) {
		$(this).resize(function() {
			var $this = $(this);
			if ($this.data('resizeTimeout') ) {
				clearTimeout($this.data('resizeTimeout') );
			}
			$this.data('resizeTimeout', setTimeout(callback, timeout) );
		} );
	};

	var $store_feature = $('[data-js-store_feature]');
	var store_feature_ratio = 0.36;

	if (! $store_feature.length) {
		return; // Return if component isn't on the page
	}

	$(document).ready(function() {
		resizeFeatures();
	} );
	$(window).resized(resizeFeatures, 400);

	function resizeDefaultFeatures() {
		$store_feature.find('.-theme-default').each(function() {
			var width = $(this).outerWidth(); // 717
			var height = $(this).outerHeight(); // 240
			var innerHeight = $(this).innerHeight(); // 240
			console.log('inner', innerHeight);
			var desiredHeight = width * store_feature_ratio; // 258
			var remainder = desiredHeight - height; // 18
			console.log('remainder', remainder);
			if (0 < remainder) {
				var padding_y = Math.ceil(remainder); // 18
				var current_padding = $(this).css('padding-top'); // 18px
				padding_y = padding_y + parseInt(current_padding); // 36
				var padding_y_top = Math.ceil(padding_y * 0.3); // 12
				var padding_y_bottom = Math.ceil(padding_y * 0.7); // 26
				$(this).css('padding-top', padding_y_top + 'px');
				$(this).css('padding-bottom', padding_y_bottom + 'px');
			}
		} );
	}

	function resizeImageFeatures() {
		$store_feature.each(function() {
			var width = $(this).outerWidth();
			var height = $(this).outerHeight();
			var desiredHeight = width * store_feature_ratio;
			desiredHeight = Math.ceil(desiredHeight);
			$(this).css('height', desiredHeight + 'px');
		} );

		if (400 > $(window).width() ) {
			$('.-theme-default .title').fitText(1.0, { minFontSize: '11px', maxFontSize: '15px' } );
			$('.-theme-default .subtitle').fitText(1.0, { minFontSize: '10px', maxFontSize: '13px' } );
			$('.-theme-default .feature-list').fitText(1.0, { minFontSize: '9px', maxFontSize: '13px' } );

			$('.-theme-image .title').fitText(1.0, { minFontSize: '11px', maxFontSize: '16px' } );
			$('.-theme-image .feature-cta-button').fitText(1.0, { minFontSize: '11px', maxFontSize: '13px' } );
		}
	}

	function resizeFeatures() {

		//resizeDefaultFeatures();
		resizeImageFeatures();
	}

}(jQuery) );
