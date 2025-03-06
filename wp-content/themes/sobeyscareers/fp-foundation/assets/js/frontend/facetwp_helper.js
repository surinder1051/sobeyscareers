// Add support to hide the facet module if there is no facets within
(function($) {
	$(document).on('facetwp-loaded', function() {

		//add aria labels and tabbing index (through href) to pager links
		if ($('.facetwp-pager').length) {
			$('.facetwp-pager').attr('role', 'navigation');
			$('.facetwp-pager a').each(function() {

                var ariaLabel = (typeof fwp_ariaLabel == 'object') ? fwp_ariaLabel.label + ' ' : 'Go to page ';
                var $html = $(this).html();
				
                if (!$(this).hasClass('dots')) {
                    $(this).attr('role', 'button').attr('aria-label', ariaLabel + $(this).attr('data-page') );
                    if ($(this).hasClass('next') || $(this).hasClass('prev')) {
                        ariaLabel += $(this).attr('data-page');
                    }
                    $(this).html('<span class="screen-reader-text visuallyhidden">' + ariaLabel + '</span>' + $html);
                }
			} );
		}
	} );
}(jQuery) );

// Add support to hide the facet module if there is no facets within
(function ($) {
	$(document).on('facetwp-loaded', function () {
		$.each(FWP.settings.num_choices, function (key, val) {
			var $parent = $('.facetwp-facet-' + key).closest('.fl-module');
			(0 === val) ? $parent.hide() : $parent.show();
		});
	});

}(jQuery));


// Hide mobile menu when facet is changed
(function ($) {
	$(document).on('facetwp-loaded', function () {        
        $(document).on('change', '.facetwp-sort-select', function(event) {
            $('.mobile_category_menu').removeClass('active');
        } );
	});
}(jQuery));


