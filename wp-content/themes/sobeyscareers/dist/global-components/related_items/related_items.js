(function($) {

	var $related_items = $('[data-js-related_items]');
	if (! $related_items.length) {
		return; // Return if component isn't on the page
	}

	jQuery(document).ready(function($) {
		customCollapse();

		$(window).resize(function() {
			customCollapse();
		} );

		$('.component_related_items').each(function() {
			var bgClass = $(this).attr('class');
			var bgRegex = bgClass.match(/[A-Za-z0-9\_\-]+\_background/);
			if (null != bgRegex && bgRegex != undefined) {
				console.log(bgRegex[0] );
				$(this).parents('.fl-row').addClass(bgRegex[0] );
			}
		} );
	} );

	function customCollapse() {
		var width = $(window).width();
		if (768 > width) {
			$('.component_related_items').each(function() {
				$('.item-box', this).slice(0, 1).show();
			} );
			$('.component_related_items .load-more').on('click', function(e) {
				e.preventDefault();
				$(this).parents('.component_related_items').find('.item-box').removeClass('mob-faded');
				$(this).parents('.component_related_items').find('.item-box:hidden').slideDown();
				$(this).fadeOut('slow');
				var scrollto = jQuery(this).parents('.component_related_items').find('.item-box').offset().top;
				$('html,body').animate( {
					scrollTop: scrollto - 50
				}, 500);

			} );
		}
	}

}(jQuery) );
