function is_touch_device() {
	var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
	var mq = function(query) {
		return window.matchMedia(query).matches;
	};

	// noinspection JSUnresolvedVariable
	if ( ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
		return true;
	}

	// include the 'heartz' as a way to have a non matching MQ to help terminate the join
	// https://git.io/vznFH
	var query = [ '(', prefixes.join('touch-enabled),('), 'heartz', ')' ].join('');
	return mq(query);
}

(function($) {

	var winWidth = $(window).width();

	var $mega_menu = $('[data-js-mega_menu]');
	if (! $mega_menu.length) {
		return; // Return if component isn't on the page
	}

	//to make sure the flyouts fit in the window
	var setDropDownMenuPos = function(item) {
		var menuLeft = 0;
		var leftPos = 0;
		var dropWidth = 0;
		var maxOffset = 0;
		var menuPos = 0;
		var $nav = $(item).closest('.nav');
		if ($('.nav-grid', item).length) {
			leftPos = parseInt($(item).offset().left, 10);
			dropWidth = parseInt($('> div', item).width(), 10);
			maxOffset = (parseInt($($nav).attr('data-flyout-card'), 10) > parseInt($($nav).attr('data-flyout-standard'), 10) ) ? parseInt($($nav).attr('data-flyout-card'), 10) : parseInt($($nav).attr('data-flyout-standard'), 10);
			menuPos = leftPos + dropWidth + maxOffset;

			if (menuPos > winWidth) {
				menuLeft = ($('.nav-grid', item).length) ? 0 - $('> div', item).width() / 2 : 0 - $('> div', item).width();
			}
		}
		return menuLeft;
	};

	var mobileViewSwitch = function(component) {
		if ($(component).is(':visible') ) {
			$(this).attr('aria-hidden', '');
			$('.mm-component-link', component).attr('aria-hidden', '').attr('tabindex', '');

			if (! $('.navbar-toggler', component).is(':visible') ) {
				if ($('.level-2', component).length) {
					$('.level-2 .dropdown-menu', component).removeClass('show').css( {
						'display': 'none'
					} ).attr('aria-expanded', '');
					$('.level-1 button.multi-level-expand', component).remove();
				}

				//large screens - check to see of dropdown menu needs a left margin adjustment
				$('.level-0.menu-item-has-children', component).each(function() {
					var menuLeft = setDropDownMenuPos($(this) );
					$('> div', this).css( {
						'left': ''
					} );
					if (0 > menuLeft) {
						$('> div', this).css( {
							'left': menuLeft + 'px'
						} );
					}

				} );

			} else {
				if ($('.level-2', component).length) {
					$('.level-0 > div').css( {
						'left': ''
					} );
					$('.level-0 .dropdown-toggle', component).each(function(e) {
						$(this).on('keydown', function(e) {
							var $target = $(e.currentTarget);
							var $parent = $target.closest('.level-0');
							if ('enter' === e.key.toLowerCase() || ' ' === e.key.toLowerCase() ) {
								if ('string' !== typeof ($(this).attr('href') ) ) {
									e.preventDefault();
									mmShowLevel1($parent);
								}

							} else if ('escape' === e.key.toLowerCase() ) {
								e.preventDefault();
								mmHideLevel1($parent);
							}
						} ).on('click', function() {
							if ($(this).closest('.dropdown-menu').hasClass('show') ) {
								mmHideLevel1($parent);
							}
						} );
					} );
					$('.level-1.menu-item-has-children', component).each(function() {
						var expandTarget = $(this).attr('data-mobile-target');

						if (! $('button', this).length && 'string' == typeof expandTarget) {
							var buttonText = 'Expand' + $('> a.dropdown-item', this).text() + ' Sub Menu';
							$(this).prepend('<button class="multi-level-expand" aria-label="View ' + buttonText + '" data-target="' + expandTarget + '" aria-controls="' + expandTarget + '"></button>');

							$('button', this).on('click', function(e) {
								e.stopPropagation();
								var ulTarget = $(this).attr('data-target');
								if ($('#' + ulTarget).length) {
									if ($(this).hasClass('ul-visible') ) {
										$('#' + ulTarget).removeClass('show').attr('aria-expanded', 'false');
										$(this).removeClass('ul-visible');
									} else {
										$(this).addClass('ul-visible');
										$('#' + ulTarget).addClass('show').attr('aria-expanded', 'true');
									}
								}
							} );

							$('> a', this).on('click', function(e) {
								e.stopPropagation();
								if ('#' !== $(this).attr('href') && '' != $(this).attr('href') ) {
									if ($(this).closest('.dropdown-menu').hasClass('show') ) {
										window.location.href = $(this).attr('href');
									}
								}
							} );
						}
					} );
				}
			}
		} else {
			$(component).attr('aria-hidden', 'true');
			$('.mm-component-link', component).attr('aria-hidden', 'true').attr('tabindex', '-1');
		}
	};

	//common function to hide/show sidemenu when top level nav is focussed
	var topLevelSelect = function(el) {

		$(el).addClass('current');
		if ($(el).hasClass('is-tabbing') ) {
			$('li.level-1:eq(0)', el).addClass('current');
		}
		if ($('a.dropdown-item:eq(0)', el).data('flyout-type') ) {

			show_side_menu($('a.dropdown-item:eq(0)', el).closest('li') );
		} else {

			$('.sidemenu').remove();
		}
	};

	/**
	 * Show level 1 dropdown from the button click event
	 *
	 * @param object level0 is the parent li element
	 */
	var mmShowLevel1 = function(level0) {
		var $first = $(level0).find('li.level-1:eq(0)');
		var dropdownMenu = $('.dropdown-menu:eq(0)', level0);
		$('> div', $(level0).siblings('.level-0') ).css( {
			'height': ''
		} );

		$(level0).addClass('hover');
		if ($first.length) {
			$(level0).attr('aria-expanded', true);

			// Add current class on first li element
			$first.siblings('li').removeClass('current');
			$first.addClass('hover').addClass('current');
			$('a:eq(0)', $first).addClass('hover').addClass('current').focus().addClass('focus-visible');
		}

		// If the browser is resized we need to add show to the dropdown-menu
		if (752 < winWidth) {
			dropdownMenu.parent().addClass('show');
		} else {
			dropdownMenu.addClass('show');
		}
		setupThreeLevelNav($(level0) );
	};

	/**
	 * Hide level 1 dropdown from the button click event
	 *
	 * @param object level0 is the parent li element
	 */
	var mmHideLevel1 = function(level0) {
		var dropdownMenu = $('.dropdown-menu:eq(0)', level0);
		$(level0).removeClass('hover');
		if ($(level0).hasClass('menu-item-has-children') ) {
			$(level0).attr('aria-expanded', false);
		}
		$('a', level0).removeClass('hover');

		// If the browser is resized.
		if (752 < winWidth) {
			dropdownMenu.parent().removeClass('show');
		} else {
			dropdownMenu.removeClass('show');
		}
		if ($('> div', level0).length) {
			$('> div', level0).css( {
				height: ''
			} );
		}

		$('.sidemenu').remove();
	};

	var setupThreeLevelNav = function(el) {
		if ($('.nav-grid', el).length) {
			var navGrid = $('.nav-grid', el);

			if (752 < winWidth) {
				var navHeight = 0;
				var liHeight = $(el).outerHeight() + 8;
				var index = 0;
				$('li.level-1 ul', navGrid).each(function() {

					//set the parent div height
					if ($(this).outerHeight() > navHeight) {
						navHeight = $(this).outerHeight();
					}

					//set the margin top for the second level nav
					if (0 < index) {
						$(this).css( {
							'top': '-' + (index * liHeight) + 'px'
						} );
					}
					index++;
				} );
				if (0 < navHeight) {
					$(navGrid).css( {
						height: navHeight + 'px'
					} );
				} else {
					$(navGrid).css( {
						height: ''
					} );
				}
			} else {
				$(navGrid).css( {
					height: ''
				} );
				$('li.level-1 ul', navGrid).css( {
					marginTop: ''
				} );
			}
		}
	};

	/**
	 * Make sure that when screen size is changed that keyboard/mouse functions work on large/small screens
	 * @param object component
	 */
	var largeScreenActions = function(component) {
		var $dropdown_toggle = $('.nav-link', component);
		var $level_1 = $('.level-1', component);

		if (752 < winWidth) {

			// level-0 a.nav-link
			$dropdown_toggle.on('mouseenter', function(e) {
				var $target = $(e.currentTarget);
				var $parent = $target.closest('.level-0');

				$('.is-tabbing .show', $mega_menu).removeClass('show');
				$('.is-tabbing', $mega_menu).removeClass('is-tabbing').removeClass('has-focus').removeClass('show').removeClass('hover').removeClass('current');
				$parent.siblings('.current').removeClass('current');

				if (! $target.hasClass('is-tabbing') ) {
					if ($(this).hasClass('dropdown-toggle') ) {
						if (! $parent.hasClass('show') ) {
							topLevelSelect($parent);

						} else {

							delete_side_menu(e);
						}
					} else {

						$('.sidemenu').remove();
						$('.level-0 > div').css( {
							'height': ''
						} );
					}
				}
			} ).on('focus', function(e) {
				e.stopPropagation();
				var $target = $(e.currentTarget);
				var $parent = $($target).parents('.level-0');
				var $dropdown_menu;
				$parent.addClass('current');

				$('.sidemenu').remove();

				$parent.siblings('.level-0').each(function() {
					mmHideLevel1($(this) );
					$(this).removeClass('current');
					if ($('> div', this).length) {
						$('> div', this).css( {
							height: ''
						} );
					}
				} );
				if (! $(this).hasClass('is-tabbing') && $(this).hasClass('dropdown-toggle') ) {
					$dropdown_menu = $parent.find('.dropdown-menu:eq(0)');
					topLevelSelect($dropdown_menu);
				}

			} ).on('blur', function(e) {

				var $target = $(e.currentTarget);
				var $parent = $($target).parents('.level-0');
				if (! $('> div .current', $parent).length) {
					$parent.removeClass('hover').removeClass('current');
					$('a', $parent).removeClass('hover');

					if ($('> div', $parent).length) {
						$('> div', $parent).css( {
							height: ''
						} );
					}
				}

			} ).on('click', function(e) {
				var $target = $(e.currentTarget);
				if ($(this).hasClass('dropdown-toggle') ) {

					if (! is_touch_device() && 752 < winWidth) { // If we're using a mouse and are seeing desktop size menu open url on click
						var url = $target.data('url');
						window.open(url, '_self');
					}
					if (753 > winWidth) {
						$('button.ul-visible', component).trigger('click');
					}
				}
			} ).on('keydown', function(e) {
				var $target = $(e.currentTarget);
				var $parent = $target.closest('.level-0');
				if ('enter' === e.key.toLowerCase() || ' ' === e.key.toLowerCase() ) {
					if ('string' !== typeof ($(this).attr('href') ) ) {
						e.preventDefault();
						mmShowLevel1($parent);
					}
				} else if ('escape' === e.key.toLowerCase() ) {
					e.preventDefault();
					mmHideLevel1($parent);
				} else if ('arrowdown' === e.key.toLowerCase() || 'arrowup' === e.key.toLowerCase) {
					e.preventDefault();
				}
			} );
			$('ul.dropdown-menu a.dropdown-item', component).on('focus', function(e) {
				var $dropdown_menu = $(this).parent('li');
				topLevelSelect($dropdown_menu);

				if ($dropdown_menu.hasClass('level-1') || $dropdown_menu.hasClass('level-2') ) {
					var sibs = $dropdown_menu.siblings('li');
					$(sibs).each(function() {
						$(this).removeClass('current').removeClass('hover');
						$('a', this).removeClass('current').removeClass('hover');
					} );
					$dropdown_menu.addClass('current').addClass('hover');

				}
			} ).on('mouseenter', function(e) {
				var $target = $(e.currentTarget);
				var $parent = $target.closest('li');
				var $dropdown_menu = $target.closest('ul');
				$('li, a', $dropdown_menu).removeClass('hover').removeClass('current');
				$(this).addClass('hover').addClass('current');
				$parent.addClass('hover').addClass('current');
				show_side_menu($parent);

			} ).on('keydown', function(e) {
				if ('arrowdown' === e.key.toLowerCase() || 'arrowup' === e.key.toLowerCase() ) {
					e.preventDefault();
				}
			} );

			if ($level_1.length) {
				$('a', $level_1).each(function() {
					$(this).on('keydown', function(e) {
						var $target = $(e.currentTarget);
						var $parent = $target.closest('.level-0');
						if ('escape' === e.key.toLowerCase() ) {
							e.preventDefault();
							mmHideLevel1($parent);
							$('> li > .dropdown-toggle', $parent).focus();
						}
					} );
				} );
			}
		}
	};

	var megaMenuSetup = function(component) {

		$megaA = $('a.dropdown-item', component).length - 1;

		$('ul.dropdown-menu a:eq(' + $megaA + ')', component).on('blur', function() {
			$(this).parents('.dropdown-menu').removeClass('show');
			$('ul, li, a, button, div', component).removeClass('show').removeClass('current').removeClass('hover');
			$('.sidemenu').remove();
		} );

		// Wrap sublevel menu items.
		$('li.level-0', component).each(function() {
			if (! ($(this).hasClass('mm-init') ) ) {
				if (1 < $('.dropdown-menu', this).length) {
					$('.dropdown-menu:eq(0)', this).wrap('<div class="nav-grid"></div>');

					$('li.level-1.menu-item-has-children', this).each(function() {
						var $id = $(this).attr('id');
						if ('string' == typeof $id) {
							var subMenuID = 'mm-sub-' + $id.replace('-item', '');
							$(this).attr('data-mobile-target', subMenuID);
							$('> ul.dropdown-menu', this).attr('id', subMenuID);
						}
					} );
				} else {
					$('.dropdown-menu:eq(0)', this).wrap('<div class="nav-standard"></div>');
				}
				if (0 == $('a[aria-haspopup="true"]', this).length) {
					$(this).addClass('no-side');
				}

				if ('string' !== typeof $('> a.nav-link', this).attr('href') ) {
					$('> a.nav-link', this).attr('tabindex', '0');
				}
				$(this).addClass('mm-init');
			}
			if (752 < winWidth) {
				$(this, '[data-whatinput="mouse"]').on('focus', function() {
					mmShowLevel1($(this) );
				}, function() {
					mmHideLevel1($(this) );
				} );
				$(this, '[data-whatinput="keyboard"]').on('focus', function(e) {
					e.stopPropagation();
				} );
			}
		} );

		// Setup click, hover and accessibility actions for large screens.
		largeScreenActions(component);

		// Need to change this bc not using BS
		component.on('hide.bs.dropdown', function() {

			$('.sidemenu').remove();
			$('.level-0 > div').css( {
				'height': ''
			} );
		} );
	};

	//set the sidemenu left based on settings width and document position. May have to fly right
	function sidemenu_position(topLi, flyoutType) {
		var $nav = $(topLi).closest('.nav');
		var dropWidth = parseInt($('> div', topLi).width(), 10) - 4;
		var sideMenuWidth = ('recipe' == flyoutType) ? parseInt($nav.attr('data-flyout-card'), 10) : parseInt($nav.attr('data-flyout-standard'), 10);
		var liOffset = parseInt($('> div', topLi).css('left'), 10);
		var menuPos = parseInt($(topLi).offset().left, 10) + sideMenuWidth + dropWidth;

		if ($('.nav-standard', topLi).length) {
			if (menuPos > winWidth) {
				return 0 - sideMenuWidth;
			}
		}
		return dropWidth - Math.abs(liOffset);
	}

	function show_side_menu($item) {
		if (752 < winWidth) {
			var $ul = $item.closest('ul');
			var $primary_item_li = $item.closest('li.level-0');
			var $a = $item.find('a:first-child');
			var $menu_child_class = $a.text();
			var $sidemenuHeight = $('> div', $primary_item_li).outerHeight();

			$menu_child_class = $menu_child_class.replace(/[^\w\s]/gi, '');
			$menu_child_class = $menu_child_class.replace(/\s+/g, '-').toLowerCase();

			$('.sidemenu').remove();

			if (! $primary_item_li.find('.sidemenu').length) {
				if (undefined != $a.data('flyoutShortcode') ) {

					var sideMenuleft = sidemenu_position($primary_item_li, $a.data('flyout-type') ); //may need to flyout right. Default is left
					var $sidemenu = $('<div>' + b64_to_utf8($a.data('flyoutShortcode') ) + '</div>').appendTo($primary_item_li);
					var topCss = ($item.hasClass('level-2') ) ? '100%' : $ul.css('top');
					var sideClass = 'sidemenu ' + $menu_child_class;
					if (undefined != $a.data('class') ) {
						sideClass += ' ' + $a.data('class');
					}

					$('.overlay', $sidemenu).remove();
					$sidemenu.css( {
						top: topCss,
						marginLeft: sideMenuleft
					} ).on('mouseenter', function() {
						$item.addClass('hover').addClass('current');
					} ).on('mouseleave', function() {
						$item.removeClass('hover');
					} );
					$sidemenu.attr('class', sideClass);

					$sidemenu.attr('data-parent', $item.attr('id') );
					if ($sidemenu.outerHeight() < $sidemenuHeight) {
						$sidemenu.css( {
							'height': $sidemenuHeight + 'px'
						} );
					} else if ($sidemenu.outerHeight() > $sidemenuHeight) {
						$('> div', $primary_item_li).css( {
							'height': $sidemenu.outerHeight() + 'px'
						} );
					}

					if ('standard' == $a.data('flyout-type') ) {
						if ($('.card-img-top', $sidemenu).length) {
							$('.card-img-top', $sidemenu).on('click', function() {
								handle_click();
							} );
						}
						if ($('.heading', $sidemenu).length) {
							$('.heading', $sidemenu).on('click', function() {
								handle_click();
							} );
						}
					}
					if ($('.is-tabbing').length) {
						$('a', $sidemenu).addClass('is-tabbing');
					}
				}
			}
		}
	}

	function delete_side_menu(e) {
		var closestNav = $(e.currentTarget).closest('ul.nav');
		if (closestNav.find('.sidemenu') ) {
			if (! $('#' + closestNav.find('.sidemenu').attr('data-parent') ).hasClass('current') ) {

				closestNav.find('.sidemenu').remove();
			}
		}
	}

	$mega_menu.each(function() {
		megaMenuSetup($(this) );
		mobileViewSwitch($(this) );
	} );

	$('.fl-module-mega_menu').on('mouseleave', function(e) {
		$('li, a', $mega_menu, this).removeClass('current').removeClass('hover').removeClass('show');
		$mega_menu.trigger('hide.bs.dropdown');

	} ).on('blur', function(e) {
		if ('dropdown-item' != e.target.className.match(/dropdown\-item/) ) {
			$('li, a', this).removeClass('current').removeClass('hover').removeClass('show');
			$mega_menu.trigger('hide.bs.dropdown');
		}
	} );

	function handle_click() {
		var $a = jQuery(this).closest('.sidemenu').find('footer a');
		var $url = $a.attr('href');
		window.open($url, '_self');
	}

	function enable_disable_mobile_links(component, enable_links) {
		if (component.length) {
			if (enable_links) {
				jQuery('input,texarea,a,button,select,iframe, [role="button"]').each(function() {
					if (! jQuery(this).hasClass('mm-aria-enabled') && ! jQuery(this).hasClass('mm-component-links') ) {
						if ('true' !== jQuery(this).attr('aria-hidden') ) {
							jQuery(this).addClass('mm-aria-enabled').attr('aria-hidden', 'true').attr('tabindex', '-1');
						}
					} else if (jQuery(this).hasClass('mm-aria-enabled') ) {
						jQuery(this).attr('aria-hidden', 'true').attr('tabindex', '-1');
					}
				} );
				$('.mm-component-link', component).attr('aria-hidden', 'false').attr('tabindex', '');
			} else {
				$('.mm-aria-enabled').attr('aria-hidden', '').attr('tabindex', '');
				$('.mm-component-link', component).attr('aria-hidden', 'true').attr('tabindex', '-1');
			}
		}
	}

	$(window).on('resize', function() {
		if ($(window).width() !== winWidth) {
			var small_to_large = false;
			if (752 >= winWidth && 752 < $(window).width() ) {
				small_to_large = true;
			}
			winWidth = $(window).width();
			$mega_menu.each(function() {
				$('.dropdown-menu', this).hide().css( {
					display: ''
				} );
				if (small_to_large) {
					largeScreenActions($(this) );
				}
				mobileViewSwitch($(this) );
			} );
		}
	} ).on('scroll', function() {
		if (752 < $(window).width() ) {
			$('li, a', $mega_menu, this).removeClass('current').removeClass('hover').removeClass('show');
			$mega_menu.trigger('hide.bs.dropdown');
		}
	} );

	// Mobile menu toggle button
	jQuery('.mob_menu_toggle').on('click', function() {
		jQuery('.fl-module-mega_menu .fl-module-mega_menu').addClass('expand');
		jQuery('body').addClass('scroll_hidden');
		var parent_row = jQuery(this).closest('.fl-row');
		var component = jQuery('.component_mega_menu', parent_row);
		if (component.length) {
			enable_disable_mobile_links(component, true);
			$('.level-0:eq(0) .mm-component-link:eq(0)', component).focus();
		}
	} );

	// Mobile menu drop down close
	jQuery('.component_mega_menu .navbar-toggler').on('click', function() {
		jQuery('.fl-module-mega_menu .fl-module-mega_menu').removeClass('expand');
		jQuery('body').removeClass('scroll_hidden');
		var parent_row = jQuery(this).closest('.fl-row');
		var component = jQuery('.component_mega_menu', parent_row);
		if (component.length) {
			enable_disable_mobile_links(component, false);
		}
		jQuery('.mob_menu_toggle').focus();
	} );

	$(document).ready(function() {

		// Add aria-expanded to top level menus items that have sub menus.
		$mega_menu.find('.level-0.menu-item-has-children').each(function() {
			var $menuItemWithChildren = $(this);
			$menuItemWithChildren.attr('aria-expanded', 'false').on('mouseenter focus', '*', function() {
				$menuItemWithChildren.attr('aria-expanded', 'true');
			} ).on('mouseleave blur', '*', function() {
				$menuItemWithChildren.attr('aria-expanded', 'false');
			} );
		} );

	} );

}(jQuery) );

/***sticky header***/
jQuery(window).on('load', function() {

	if (jQuery('.component_mega_menu.mm-sticky').length) {
		var sticky_top = jQuery('.fl-module-mega_menu').offset().top;

		jQuery(window).on('scroll', function() {
			var window_top = jQuery(window).scrollTop();

			if (window_top > sticky_top) {
				jQuery('.fl-module-mega_menu').addClass('sticky-header');
			} else {
				jQuery('.fl-module-mega_menu').removeClass('sticky-header');
			}
		} );
	}
} );

/**  escape the string before encoding it **/
function b64_to_utf8(str) {
	return decodeURIComponent(escape(window.atob(str) ) );
}
