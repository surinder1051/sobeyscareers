(function($) {
	var $bs_nav = $('[data-js-bs_nav]');

	if (! $bs_nav.length) {
		return; // Return if component isn't on the page
	} else {
		$bs_nav.each(function() {
			var component = $(this);

			$(component).find('.title').click(function() {
				if (component.hasClass('expand-collapse') ) {
					$(this).toggleClass('navCollapsed');
					$(this).next('.navbar-nav').slideToggle();
				}
			} );

			$('.navbar-nav a', component).each(function() {
				$(this).attr('aria-label', $(this).text() );
			} );

			if ($('button.bs-nav-expander', this).length) {

				//setup any second and third level expand display
				$('li.current-menu-ancestor', this).addClass('expanded').attr('aria-expanded', true).each(function() {
					var expander = $('button.bs-nav-expander', this);
					$('span', expander).removeClass().addClass($(expander).attr('data-close-icon') );
				} );
				$('li', this).each(function() {
					if (! $(this).hasClass('current-menu-ancestor') ) {
						var expander = $('button.bs-nav-expander', this);
						$('span', expander).removeClass().addClass($(expander).attr('data-open-icon') );
					}
				} );

			}

			$('button.bs-nav-expander', component).on('click', function() {
				$(this).closest('li').toggleClass('expanded');
				if ($(this).closest('li').hasClass('expanded') ) {
					$(this).closest('li').attr('aria-expanded', true);
				} else {
					$(this).closest('li').attr('aria-expanded', true);
				}
				if ($('span', this).hasClass($(this).attr('data-close-icon') ) ) {
					$('span', this).removeClass().addClass($(this).attr('data-open-icon') );
				} else {
					$('span', this).removeClass().addClass($(this).attr('data-close-icon') );
				}
			} );
		} );
	}

}(jQuery) );
