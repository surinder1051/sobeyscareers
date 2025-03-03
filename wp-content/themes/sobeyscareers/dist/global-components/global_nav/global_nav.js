(function($) {

	$global_nav = $('[data-js-global-nav]');
	if (! $global_nav.length) {
		return; // Return if component isn't on the page.
	}

	var gn_debug = true;
	var nav_trail = [];
	var $site_nav_header = $('#site-nav-header');
	var site_nav_header_height = $site_nav_header.outerHeight();
	var hamburger_icon_id = '#site-nav-header #menu-icon';
	var $hamburger_icon = $(hamburger_icon_id);
	var main_menu = ('ul#primary-menu');
	var menu_item = ('li.menu-item');
	var menu_item_parent = ('li.menu-item.menu-item-has-children');
	var menu_item_link = ('li.menu-item a');
	var primary_menu_item = ('li.primary-menu-item');
	var gn_panel_class = ('.gn-panel');
	var gn_panel_id_prefix = ('#gn-panel-');
	var sub_menu = ('ul.sub-menu');
	var first_level_li = ('[data-js-menu-nav-level=\'0\']');
	var current_menu_ancestor = ('.current-menu-ancestor');
	var current_menu_item = ('.current-menu-item');
	var data_id_label = ('js-menu-nav-id');
	var data_level_label = ('js-menu-nav-level');
	var open_state_class = ('-open'); // Using combinators, shouldn't need for multiple classes.
	var close_state_class = ('-close'); // Using combinators, shouldn't need for multiple classes.
	var hidden_state_class = ('-hidden');
	var body_open_state_class = ('-menu-open');
	var breadcrumb_tpl = '<li class="menu-item bc-menu-item"><a>%s</a></li>';
	var navMenuItems;
	var navMenuNext = 2;
	var navMenuPrev = 0;

	$(document).ready(function() {
		init();
	} );

	function init() {
		$global_nav.show();
		labelSubMenus();
		setupBreadcrumbs();
		setupPanels();

		// We bind events last because we shifted elements around.
		bindEvents();
		navigateToCurrent();
		removePanelTabIndex();
		addAriaExpandedStates();

		// Beaver Builder preview helper

		$('body.fl-builder-edit').on('click', '#bb_expand_main_menu', function() {
			closeMenu();
			jQuery('#global-nav').height(0);
			height = jQuery('#site-nav-header').height() + 1;
			menu_height = jQuery(document).height() - height;
			jQuery('#global-nav, .component_search, .component_header_communities, .component_header_websites').css('top', height);
			jQuery('#global-nav').height(menu_height).addClass('-open').show();
			toggleMenu();
		} );

		try {
			fpSetNavOffset();
		} catch (e) {
		}
		$(window).resize(function() {
			try {
				fp_setNavOffset();
			} catch (e) {
			}
		} );

		$(document).keydown(function(e) {
			var keyCode = (e.which) ? e.which : e.KeyCode;
			var navPanels = $('.gn-panels .-open', '#global-nav.-open');
			if ($('#global-nav').hasClass('-open') ) {
				switch (keyCode) {
					case 9:
						e.preventDefault();
						resetNavTabbing();
						if (0 == navPanels.length) {
							$(e).focus();
						} else {
							$(navMenuItems[1], '#global-nav.-open').focus().addClass('is-tabbing');
						}
						if (e.shiftKey) {
							navMenuUp();
						} else {
							navMenuDown();
						}
						break;
					case 40:
						e.preventDefault();
						resetNavTabbing();
						if (0 == navPanels.length) {
							$(e).focus();
						} else {
							$(navMenuItems[1], '#global-nav.-open').focus().addClass('is-tabbing');
						}
						navMenuDown();
						break;
					case 38:
						e.preventDefault();
						resetNavTabbing();
						if (0 == navPanels.length) {
							$(e).focus();
						} else {
							$(navMenuItems[1], '#global-nav.-open').focus().addClass('is-tabbing');
						}
						navMenuUp();
						break;

					/*case 27:
						break;*/
					case 13:

						//if a user enters a new panel and users the arrow keys, the tab items need to be reset
						if ('menu-icon' == e.target.id) {
							$(e).removeClass('is-tabbing').trigger('click');
							resetNavTabbing();
						} else {
							resetNavTabbing();
						}
						break;
					default:
						break;
				}
			}
		} );
	}

	function resetNavTabbing() {
		var panelLevel;
		if (typeof navMenuNext == undefined || null == navMenuNext) {
			navMenuNext = 2;
			navMenuPrev = 0;
		}
		navMenuItems = new Array();

		//add the navicon so users can get out
		navMenuItems.push($('#menu-icon.-open') );
		if ($('#global-nav.-open .gn-panel.-open').length) {
			$('#global-nav.-open .gn-panel.-open').each(function() {
				panelLevel = $(this);
			} );
			$('a', panelLevel).each(function() {
				navMenuItems.push($(this) );
			} );
		} else if ($('#primary-menu', '#global-nav.-open').is(':visible') ) {
			$('#primary-menu a', '#global-nav.-open').each(function() {
				if ($(this).is(':visible') ) {
					navMenuItems.push($(this) );
				}
			} );
		}
		for (var i = 0; i < navMenuItems.length; i++) {
			if ($(navMenuItems[i] ).hasClass('is-tabbing') ) {
				navMenuNext = ( (1 + i) == navMenuItems.length) ? 0 : (1 + i);
				navMenuPrev = (0 > (-1 + i) ) ? navMenuItems.length - 1 : (-1 + i);
			}
		}
	}

	function navMenuUp() {
		var prev = navMenuPrev - 1;
		$('a.is-tabbing', '#global-nav.-open').removeClass('is-tabbing');
		$(navMenuItems[navMenuPrev], '#global-nav.-open').attr('tabindex', '0').focus().addClass('is-tabbing');
		if (0 == navMenuPrev) {
			prev = (navMenuPrev.length - 1);
		}
		navMenuPrev = prev;
	}
	function navMenuDown() {
		var next = 1 + navMenuNext;
		$('a.is-tabbing', '#global-nav.-open').removeClass('is-tabbing');
		$(navMenuItems[navMenuNext], '#global-nav.-open').attr('tabindex', '0').focus().addClass('is-tabbing');
		if (next == navMenuItems.length) {
			next = 0;
		}
		navMenuNext = next;
	}


	/**
	 * Get nav element by unique ID.
	 * @param  {int} nav_id
	 * @return {$}
	 */
	function getNavEl(nav_id) {
		return $('[data-' + data_id_label + '="' + nav_id + '"]').eq(0);
	}

	/**
	 * Navigate to the current menu item.
	 * @return {void}
	 */
	function navigateToCurrent() {
		var $current_panel_el = $global_nav.find(gn_panel_class + ' ' + current_menu_item).last();
		if (0 === $current_panel_el.length) {

			// It's a top level item
			var $top_level_current = $global_nav.find(main_menu + ' ' + current_menu_ancestor).last();
			if ($top_level_current.length) {
				$top_level_current.addClass(open_state_class);
			}
			return;
		}

		$global_nav.find(current_menu_ancestor).closest(gn_panel_class).each(function(index) {
			var panel_id = $(this).attr('id').replace('gn-panel-', '');
			nav_trail.push(panel_id);
			$global_nav.find(gn_panel_id_prefix + panel_id).addClass(open_state_class);
		} );

		var current_panel_id = $current_panel_el.closest(gn_panel_class).attr('id').replace('gn-panel-', '');
		nav_trail.push(current_panel_id);
		$global_nav.find(gn_panel_id_prefix + current_panel_id).addClass(open_state_class);
		debug('navigateToCurrent: ' + nav_trail.toString() );
	}

	/**
	 * Get item level depth
	 * @param  {int} nav_id uniqueID.
	 * @return {int} level item level.
	 */
	function getItemDepth(nav_id) {
		$el = getNavEl(nav_id);
		var $parent_menu_el = $el.closest('ul');
		var matches = $parent_menu_el.attr('class').match('gn-level-([0-9]+)');
		if (matches) {
			var level = matches[1];
		}
		return level;
	}

	/**
	 * Traverse through all the menu items and label accordingly to level actions.
	 */
	function labelSubMenus() {

		// Label last primary menu item
		$(primary_menu_item).eq($(primary_menu_item).length - 1).addClass('primary-menu-item-last');

		// Label all submenu item with unique ID's and levels.
		$global_nav.find(main_menu + ' ' + menu_item).each(function(index) {
			$(this).attr('data-' + data_id_label, index);
			var level = getItemDepth(index);
			$(this).attr('data-' + data_level_label, level);
		} );

		// Label all top level menu items.
		$global_nav.find(main_menu + ' > ' + menu_item).each(function(index) {
			$(this).attr('data-' + data_level_label, 0);
		} );

		$global_nav.find(gn_panel_class).each(function(e) {
			var $top_level_menu_item = $(this).closest(menu_item);
			$(this).attr('id', 'gn-panel-' + $top_level_menu_item.data(data_id_label) );
		} );
	}

	/**
	 * Insert our navigation level breadcrumbs to navigate back up.
	 * @return {[type]} [description]
	 */
	function setupBreadcrumbs() {
		$global_nav.find(gn_panel_class).each(function(e) {

			// non-clickable label
			var $thirdlevel_menu_item_el = $(this).closest(menu_item);
			var third_level_bc = $thirdlevel_menu_item_el.find('> a').text();
			var third_level_html = breadcrumb_tpl.replace('%s', third_level_bc);
			var $third_level_el = $(third_level_html);
			$third_level_el.addClass('bc-menu-item-label');
			$third_level_el.find('> a').attr('tabindex', '-1');

			//$third_level_el.attr( 'data-' + data_id_label, ($thirdlevel_menu_item_el.data( data_id_label ) - 1) );

			// Up one
			var $secondlevel_menu_item_el = $thirdlevel_menu_item_el.closest(sub_menu).closest(menu_item);
			var second_level_bc = $secondlevel_menu_item_el.find('> a').text();
			var second_level_html = breadcrumb_tpl.replace('%s', second_level_bc);
			var $second_level_el = $(second_level_html);
			$second_level_el.find('> a').attr('tabindex', '-1');
			$second_level_el.attr('data-' + data_id_label, ($thirdlevel_menu_item_el.data(data_id_label) - 1) );

			// Main Menu
			var $firstlevel_menu_item_el = $secondlevel_menu_item_el.closest('ul').closest(menu_item);
			var first_level_bc = $firstlevel_menu_item_el.find('> a').text();
			first_level_bc = 'Main menu';
			var first_level_html = breadcrumb_tpl.replace('%s', first_level_bc);
			var $first_level_el = $(first_level_html);
			$first_level_el.find('> a').attr('tabindex', '-1');
			$first_level_el.attr('data-' + data_id_label, -1);

			//first_level_el						= first_level_el.replace( '%c', '3' );

			$(this).prepend($third_level_el);
			$(this).prepend($second_level_el);
			$(this).prepend($first_level_el);
		} );
	}


	function setupPanels() {
		var newNode = document.createElement('div');
		newNode.className = 'gn-panels';
		$(main_menu).after(newNode);
		$('.gn-panels').append($(gn_panel_class) );
	}

	function bindEvents() {
		$(document).on('click', hamburger_icon_id, toggleMenu);

		//$hamburger_icon.on('click', toggleMenu );
		$global_nav.find(menu_item_link).on('click', menuClick); // Listen to all menu-items, we will distinguish the source on the event callback.
		mimicClick();
		$(document).on('menu-close', closeMenu);
	}

	/**
	 * Some `a` have no URL and are navigated over with JS, to ensure proper accessibilty and the user can press enter for all links,
	 * we catch when a link has a key press and is in focus and mimic that same click.
	 */
	function mimicClick() {
		$('a').keydown(function(e) {
			if ($(this).is(':focus') && ('undefined' === typeof $(this).attr('href') ) && (13 === e.which) ) { // enter key
				$(this).click();
			}
		} );
	}

	function toggleMenu() {
		if (! $('body').hasClass(body_open_state_class) ) {
			console.log('open');
			openMenu();
		} else {
			console.log('close');

			closeMenu();
		}
	}

	/**
	 * Globalize the open/close menu function so we can listen for keydown attr.
	 */
	window.opg_toggleNavMenu = function() {
		toggleMenu();
	};

	function openMenu() {
		$('.gn-panels').css('top', 0);
		$(document).trigger('modal-close');
		$('body').addClass(body_open_state_class); // Fix global_header when the menu is open
		$hamburger_icon.addClass(open_state_class);
		$global_nav.addClass(open_state_class);
		reIndexTopLevelLinks();
		focusMainMenu();
		$(document).bind('keyup', quitModalKey);

		//reset keyboard tabbing
		resetNavTabbing();
	}

	function closeMenu() {
		$('.gn-panels').css('top', -100000);
		$('body').removeClass(body_open_state_class); // Fix global_header when the menu is open
		$hamburger_icon.removeClass(open_state_class);
		$global_nav.removeClass(open_state_class);
		unIndexTopLevelLinks();
		$(document).unbind('keyup', quitModalKey);
	}

	function hasSubmenu($el) {
		return ($el.closest(menu_item).hasClass('menu-item-has-children') );
	}

	function hidePrimaryNav() {
		$global_nav.find(main_menu).addClass(close_state_class);
		$global_nav.find(main_menu).delay(500).slideUp(0);
	}

	function showPrimaryNav() {
		$global_nav.find(main_menu).removeClass(close_state_class);
		$global_nav.find(main_menu).slideDown(0);
		focusMainMenu();
	}

	/**
	 * Add `aria-expanded` attributes to all menu items that can expand as a default.
	 */
	function addAriaExpandedStates() {
		$global_nav.find(menu_item_parent).attr('aria-expanded', 'false');
	}

	function unIndexTopLevelLinks() {
		$global_nav.find(main_menu + ' > ' + menu_item + ' > a').attr('tabindex', '-1');
	}

	function reIndexTopLevelLinks() {
		$global_nav.find(main_menu + ' > ' + menu_item + ' > a').attr('tabindex', '0');
	}

	function focusMainMenu() {
		$global_nav.find(main_menu + ' > ' + menu_item + ' > a').eq(0).focus();
	}

	/**
	 * For accessibilty, we want to remove the hidden panels from the tabindex and only put them back in natural order
	 * once they are in view.
	 */
	function removePanelTabIndex() {
		$global_nav.find(gn_panel_class + ' a').attr('tabindex', '-1');
		unIndexTopLevelLinks();
	}

	function slidePanel(panel_id, prev, trail_length) {
		debug('slidePanel: panel_id: ' + panel_id);
		debug('slidePanel: trail_length: ' + trail_length);

		removePanelTabIndex();

		// If we are navigating a level back
		if (0 === panel_id) {
			$global_nav.find(gn_panel_id_prefix + prev).removeClass(open_state_class);

			// if panel_id is 0 && trail_length is 0 then we are back at main level, reset the tabindex for all direct links at main level
			// if panel_id is 0 && trail_length is not 0, we are just going back up one level
			if (trail_length) {
				var lastEl = nav_trail.pop();
				nav_trail.push(lastEl);
				$global_nav.find(gn_panel_id_prefix + lastEl + ' a').attr('tabindex', '0');
				$global_nav.find(gn_panel_id_prefix + lastEl + ' a').eq(0).focus();
			} else {
				reIndexTopLevelLinks();
			}

			//return;
		} else {

			// navigating forward
			scrollTo();
			$global_nav.find(gn_panel_id_prefix + panel_id).addClass(open_state_class);
			$global_nav.find(gn_panel_id_prefix + panel_id + ' a').attr('tabindex', '0');

			//$global_nav.find( gn_panel_id_prefix + prev ).addClass( close_state_class );
		}

		// If we are not on the top-level.
		if (0 < trail_length) {
			hidePrimaryNav();
		} else { // if we are navigating back to the top level.
			showPrimaryNav();
		}
	}

	function slideHome() {
		reIndexTopLevelLinks();
		showPrimaryNav();
		$global_nav.find(gn_panel_class).removeClass(open_state_class);
		$global_nav.find(main_menu + ' ' + menu_item).removeClass(open_state_class);
	}

	function dropDown($el) {
		debug('dropdown clicked');
		if ($el.hasClass(open_state_class) ) { // closing an existing opening one
			$el.removeClass(open_state_class);
			$el.attr('aria-expanded', 'false');
		} else { // close all other before you open.
			$(first_level_li).removeClass(open_state_class);
			$el.addClass(open_state_class);
			$el.attr('aria-expanded', 'true');

			debug('dropdown: scrollTop el:' + $el.offset().top);
			scrollTo($el.offset().top - site_nav_header_height, 400);
		}
	}

	function navigate(nav_id) {
		debug('navigate');
		debug('navigate: selected panel/nav_id: ' + nav_id);

		//function navigate( nav_id ) {
		var $nav_el = getNavEl(nav_id);
		var item_level = $nav_el.data(data_level_label);
		debug('navigate: : item_level: ' + item_level);
		debug('navigate: : nav_trail: ' + nav_trail.toString() );

		// If the breadcrumb is -1 we can top level but all menus closed
		if (-1 == nav_id) {
			debug('navigate: slideHome, resetting nav_trail');
			slideHome();
			nav_trail = [];
			return;
		}

		// If this is a breadcrumb link, let's find our prev id
		var prev = 0;
		if (nav_trail.length) {
			prev = nav_trail[nav_trail.length - 1];
		}

		debug('navigate: prev: ' + prev);

		// If it is indeed before this then let's loop back to top
		if (nav_id < prev) {
			for (; prev > nav_id; prev--) {
				nav_trail.pop();

				// First we find out el, then it's top-level panel-id
				slidePanel(0, prev, nav_trail.length);

				//$global_nav.find( gn_panel_id_prefix + prev ).removeClass( open_state_class );

				debug('navigate: : nav_trail pop: ' + nav_trail.toString() );
			}
			return;
		}

		// Or we move forward. Either we dropdown or slide...
		if (0 == item_level) {

			// Open corresponding menu
			dropDown($nav_el);

			// or for transitions @todo
			// var $sub_menu = $parent_menu_item_el.find( " > " + sub_menu );
			// $sub_menu.slideDown();
		} else {
			nav_trail.push(nav_id);

			// Open slide
			slidePanel(nav_id, prev, nav_trail.length);
			debug('navigate: : nav_trail push: ' + nav_trail.toString() );
		}
	}

	function scrollTo(offset, transition) {
		var offset = 'undefined' === typeof offset ? 0 : offset;
		var transition = 'undefined' === typeof transition ? 0 : transition;
		$global_nav.animate( {
			scrollTop: offset
		}, transition);
	}

	function quitModalKey(e) {
		if (27 === e.keyCode) //esc
		{
			closeMenu();
		}
	}

	function menuClick(e) {
		var $parent_menu_item_el = $(this).closest(menu_item);

		// if the item we clicked on has childen/submenu we don't want to navigate away from this page.
		if (hasSubmenu($parent_menu_item_el) || $parent_menu_item_el.hasClass('bc-menu-item') ) {
			e.preventDefault();
		} else {
			return true;
		}
		var nav_id = $parent_menu_item_el.data(data_id_label);
		navigate(nav_id);
	}

	function debug(msg) {
		if (gn_debug) {

			// console.log('global-nav: ' + msg);
		}
	}

}(jQuery) );
