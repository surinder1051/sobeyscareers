(function($) {
	var iconNavCallout = $('[data-js-icon-navigation]');
	var winWidth = $(window).width();


	if (! iconNavCallout.length) {
		return; // Return if component isn't on the page
	} else {

		var iconNavLayout = function(component) {
			var iconRow = $('.icon-navigation-row', component);
			var breakpoint = $(iconRow).attr('data-breakpoint');
			winWidth = $(window).width();


			if (breakpoint <= winWidth) {
				if ($(iconRow).hasClass('display-grid') ) {
					$(iconRow).removeClass('display-grid').addClass('display-table');
				}
			} else {
				if ($(iconRow).hasClass('display-table') ) {
					$(iconRow).removeClass('display-table').addClass('display-grid');
				}
			}

		};

		iconNavCallout.each(function() {
			iconNavLayout($(this) );
		} );


		$(window).on('orientationchange', function() {
			setTimeout(function() {
				iconNavCallout.each(function() {
					iconNavLayout($(this) );
				} );
			}, 250);
		} ).on('resize', function() {
			setTimeout(function() {
				iconNavCallout.each(function() {
					iconNavLayout($(this) );
				} );;
			}, 250);
		} );
	}

}(jQuery) );
