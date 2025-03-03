function is_touch_device() {
	var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
	var mq = function(query) {
		return window.matchMedia(query).matches;
	};

	if ( ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
		return true;
	}

	// include the 'heartz' as a way to have a non matching MQ to help terminate the join
	// https://git.io/vznFH
	var query = [ '(', prefixes.join('touch-enabled),('), 'heartz', ')' ].join('');
	return mq(query);
}

(function($) {

	var $mega_menu = $('[data-js-mega_menu]');
	if (! $mega_menu.length) {
		return; // Return if component isn't on the page
	}

	$dropdown_toggle = $('.dropdown-toggle', $mega_menu);
	$('ul.dropdown-menu > li', $mega_menu).on('mouseover', function(e) {
		$target = $(e.currentTarget);
		if (! $(this).hasClass('is-tabbing') ) {
			if ($target.find('>a').data('image') ) {
				show_side_menu($target);
			} else {
				delete_side_menu(e);
			}
		}
	} );

	$('ul.dropdown-menu a.dropdown-item', $mega_menu).on('focus', function(e) {
		$target = $(e.currentTarget);
		if ($(this).hasClass('is-tabbing') ) {
			$dropdown_menu = $target.parent();
			if ($(this).data('image') ) {
				show_side_menu($dropdown_menu);
			}
		}
	} ).on('blur', function(e) {
		$target = $(e.currentTarget);
		$mega_menu.trigger('hide.bs.dropdown');
	} );

	$dropdown_toggle.on('hover', function(e) {
		$target = $(e.currentTarget);
		if (! $target.hasClass('is-tabbing') ) {
			if (! $target.parent().hasClass('show') ) {
				$dropdown_menu = $target.parent().find('.dropdown-menu');
				$first_item = $dropdown_menu.find('li:first-child');
				if ($first_item.find('>a').data('image') ) {
					show_side_menu($first_item);
				}
			} else {
				delete_side_menu(e);
			}
		}
	} );

	$dropdown_toggle.on('click', function(e) {

		$target = $(e.currentTarget);
		if (! is_touch_device() && 767 < jQuery(window).width() ) { // If we're using a mouse and are seeing desktop size menu open url on click
			let url = $target.data('url');
			window.open(url, '_self');
		}
	} );

	$mega_menu.on('hide.bs.dropdown', function(e) {
		$('.sidemenu').remove();
	} );

	$('.fl-module-mega_menu').on('mouseleave', function() {
		$mega_menu.trigger('hide.bs.dropdown');
	} );

	function show_side_menu($item) {
		$ul = $item.closest('ul');
		$primary_item_li = $ul.closest('li');
		$a = $item.find('>a');
		$menu_child_class = $a.text();
		$menu_child_class = $menu_child_class.replace(/[^\w\s]/gi, '');
		$menu_child_class = $menu_child_class.replace(/\s+/g, '-').toLowerCase();;
		if (! $primary_item_li.find('.sidemenu').length) {
			$sidemenu = $('<div class="sidemenu menu_item_' + $menu_child_class + '"><footer><div class="text"></div></footer></div>').appendTo($primary_item_li);

			$sidemenu.css( {
				top: $ul.css('top')
			} );

			$footer = $sidemenu.find('footer');
			$footer_text = $sidemenu.find('footer .text');
			$('<img/>').attr('src', $a.data('image') )
				.prependTo($sidemenu.find('footer') );
			$('<h2/>').text($a.data('title') )
				.appendTo($footer_text).addClass('heading');
			$('<p/>').text($a.data('description') )
				.appendTo($footer_text);
			$('<a/>').text($a.data('link_title') )
				.appendTo($footer_text).attr($a.data('url') );
		} else {
			$sidemenu = $primary_item_li.find('.sidemenu');
			$footer = $sidemenu.find('footer');
			$sidemenu.attr('class', 'sidemenu ' + 'menu_item_' + $menu_child_class);
			$footer.find('img').attr('src', $a.data('image') );
			$footer.find('h2').text($a.data('title') );
			$footer.find('p').text($a.data('description') );
			$footer.find('a').text($a.data('link_title') ).attr('href', $a.data('url') );
		}

		$sidemenu.css( {
			color: $a.data('textColor')
		} );

		if ('Cover Background' == $a.data('image_cover') ) {
			$sidemenu.css( {
				backgroundImage: 'url("' + $a.data('image') + '")',
				backgroundColor: 'transparent'
			} );
			$sidemenu.addClass('image_cover');
		} else {
			$sidemenu.css( {
				backgroundImage: 'none',
				backgroundColor: $a.data('backgroundColor')
			} );
			$sidemenu.removeClass('image_cover');
		}


	}

	jQuery('.nav-link', $mega_menu).on('mouseenter', function() {
		$('.is-tabbing .show', $mega_menu).removeClass('show');
		$('.is-tabbing', $mega_menu).removeClass('is-tabbing').removeClass('has-focus').removeClass('show').removeClass('hover');
	} );

	jQuery('.component_mega_menu, .dropdown').on('mouseleave', '.sidemenu', function(e) {
		delete_side_menu(e);
	} );

	function delete_side_menu(e) {
		$(e.currentTarget).closest('ul.nav').find('.sidemenu').remove();
	}


}(jQuery) );

jQuery(document).ready(function(e) {

	jQuery('.dropdown').hover(function() {
		jQuery('a', this).addClass('hover');
		jQuery('a', this).attr('aria-expanded', true);
		var dropdownMenu = jQuery(this).children('.dropdown-menu');
		if (dropdownMenu.is(':visible') ) {
			dropdownMenu.parent().addClass('show');
		} else {
			dropdownMenu.parent().removeClass('show');
			jQuery('.sidemenu').remove();
		}
	}, function() {
		jQuery('a', this).removeClass('hover');
		jQuery('a', this).attr('aria-expanded', false);
	} );

	//jQuery('.mob_menu_toggle').click(function() {
	jQuery('.mob_menu_toggle').on('mouseup touchend', function(e) {
		jQuery('.fl-module-mega_menu .fl-module-mega_menu').addClass('expand');
		jQuery('body').addClass('scroll_hidden');
	} );

	jQuery('.component_mega_menu .navbar-toggler').click(function() {
		jQuery('.fl-module-mega_menu .fl-module-mega_menu').removeClass('expand');
		jQuery('body').removeClass('scroll_hidden');
	} );

	jQuery('.component_mega_menu .nav-item a.nav-link').focus(function(e) {
		var parentContainer;
		if (jQuery(this).hasClass('is-tabbing') && ! jQuery(this).hasClass('dropdown-toggle') ) {
			parentContainer = jQuery(this).parents('.component_mega_menu');
			jQuery('.nav-item', parentContainer).removeClass('show').removeClass('hover');
			jQuery('.dropdown-toggle', parentContainer).removeClass('hover').removeClass('has-focus');
			jQuery('.dropdown-menu', parentContainer).removeClass('show');
			jQuery(this).trigger('hover').addClass('hover').addClass('has-focus');
		}
	} ).blur(function(e) {
		if (jQuery(this).hasClass('is-tabbing') && ! jQuery(this).hasClass('dropdown-toggle') ) {
			jQuery(this).removeClass('has-focus').removeClass('hover');
		}
	} );

	jQuery('.component_mega_menu .dropdown a.dropdown-toggle').focus(function(e) {
		if (jQuery(this).hasClass('is-tabbing') ) {
			var closest = jQuery('.dropdown a.dropdown-toggle').closest('.has-focus');
			jQuery(closest).removeClass('has-focus').removeClass('hover');
			jQuery(closest).next('.dropdown-menu').removeClass('show');
			jQuery(this).trigger('hover').addClass('hover').addClass('has-focus');
			jQuery(this).next('.dropdown-menu').addClass('show');
		}
	} );

} );


/***sticky header***/
jQuery(window).on('load', function(e) {

	var sticky_top = jQuery('.fl-module-mega_menu').offset().top;

	jQuery(window).on('scroll', function(e) {
		var window_top = jQuery(window).scrollTop();

		if (window_top > sticky_top) {
			jQuery('.fl-module-mega_menu').addClass('sticky_header');
		} else {
			jQuery('.fl-module-mega_menu').removeClass('sticky_header');
		}
	} );
} );
