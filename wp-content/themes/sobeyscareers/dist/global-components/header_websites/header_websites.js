(function($) {

	var comp_debug = true;

	var $header_websites = $('[data-js-header_websites]');

	if (! $header_websites.length) {
		return; // Return if component isn't on the page
	}

	var $component_websites = $('.component_header_websites');
	var $accordions = $component_websites.find('.accordion button');
	var $menu_icon = $('#menu-icon');

	var gh_close_button = ('.gh-close-button');
	var gh_menu_item_class = ('.gh-menu-item');

	var open_class = ('-open');
	var starred_class = ('-starred');
	var websitesBoxNext = 0;
	var websitesBoxPrev = 0;
	var websitesBoxControls; //for modal keyboard tabbing

	$(document).ready(init);

	function init() {
		bindEvents();
		initCarousel();
	}
	var setWebsitesTabControls = function() {
		var controlWrapper;
		var p = 0;
		var panel;
		if ($($component_websites).hasClass('-open') ) {
			websitesBoxControls = new Array();
			websitesBoxControls.push($('.menu-global-container .gh-close-button a') );
			if ($('.desktop', $component_websites).is(':visible') ) {
				$('.component_header_websites.-open .tab-list li').each(function() {
					$(this).attr('tabindex', '0');
					websitesBoxControls.push($(this) );
				} );
				$('.component_header_websites.-open .tab-content.current .row').each(function() {
					if ($(this).hasClass('active') && $('a', this).length) {
						$('a', this).each(function() {
							websitesBoxControls.push($(this) );
							$(this).attr('tabindex', '0');
						} );
					}
				} );
				if ($('.component_header_websites.-open .tab-content.current .carousel-control-wrap').length) {
					controlWrapper = $('.component_header_websites.-open .tab-content.current .carousel-control-wrap');
					$('.carousel-control-prev', controlWrapper).attr('tabindex', '0');
					websitesBoxControls.push($('.carousel-control-prev', controlWrapper) );
					$('.carousel-control-next', controlWrapper).attr('tabindex', '0');
					websitesBoxControls.push($('.carousel-control-next', controlWrapper) );
				}
			} else if ($('.mobile', $component_websites).is(':visible') ) {
				$('.component_header_websites.-open [data-js-accordion]').each(function() {
					$(this).attr('tabindex', '0');
					websitesBoxControls.push($(this) );
					panel = $('.component_header_websites.-open .tab-container.mobile .panel:eq(' + p + ')');
					if (panel && $(panel).is(':visible') && $('a', panel).length) {
						$('a', panel).each(function() {
							$(this).attr('tabindex', '0');
							websitesBoxControls.push($(this) );
						} );
					}
					p++;
				} );
			}
		}
		for (var i = 0; i < websitesBoxControls.length; i++) {
			if ($(websitesBoxControls[i] ).hasClass('is-tabbing') ) {
				websitesBoxNext = ( (1 + i) == websitesBoxControls.length) ? 0 : (1 + i);
				websitesBoxPrev = (0 > (-1 + i) ) ? websitesBoxControls.length - 1 : (-1 + i);
			}
		}
	};

	var websitesBoxBackward = function() {
		var prev = websitesBoxPrev - 1;
		$('.component_header_websites.-open .is-tabbing').removeClass('is-tabbing');
		$(websitesBoxControls[websitesBoxPrev] ).focus().addClass('is-tabbing');
		if (0 == websitesBoxPrev) {
			prev = (websitesBoxControls.length - 1);
		}
		websitesBoxNext = prev;
	};
	var websitesBoxForward = function() {
		var next = 1 + websitesBoxNext;
		$('.component_header_websites.-open .is-tabbing').removeClass('is-tabbing');
		$(websitesBoxControls[websitesBoxNext] ).focus().addClass('is-tabbing');
		if (next == websitesBoxControls.length) {
			next = 0;
		}
		websitesBoxNext = next;
	};

	$(window).on('keydown', function(e) {
		if ($($component_websites).hasClass('-open') ) {
			var keyed = (e.which) ? e.which : e.keyCode;
			var regex = new RegExp(/carousel\-control/);
			switch (keyed) {
				case 9:
					e.preventDefault();
					setWebsitesTabControls();
					if (e.shiftKey) {
						websitesBoxBackward();
					} else {
						websitesBoxForward();
					}
					break;
				case 27:

					//on escape, close the modal
					closeModal();
					break;
				case 13:
					if ('carousel-control' == e.target.className.match(regex) ) {
						$(e).trigger('click').focus();
					} else if (e.target.hasAttribute('data-tab') ) {
						$(e).trigger('click');
					} else if (e.target.hasAttribute('data-js-header-close-button') ) {
						e.preventDefault();
						closeModal();
					} else if (e.target.hasAttribute('data-js-accordion') ) {
						$(e).trigger('click').focus().addClass('toggle');
					}
					break;
				default:
					break;
			}
		}
	} );

	function bindEvents() {
		$header_websites.on('click', openModal);
		$(document).on('click', gh_close_button, closeModal);
		$accordions.on('click', toggleAccordion);
		$(document).on('modal-close', closeModal);
	}

	function toggleAccordion() {
		$(this).parents('.accordion').toggleClass('active');
		$(this).parents('.accordion').next().slideToggle();
	}

	function quitModalKey(e) {
		if (27 === e.keyCode) //esc
		{
			closeModal();
		}
	}

	function openModal(e) {
		$(document).trigger('menu-close');
		$('body').addClass('no_scroll');
		e.preventDefault();
		$component_websites.show();
		$component_websites.focus();
		$component_websites.addClass(open_class);
		$(gh_menu_item_class).hide();
		$(gh_close_button).show();
		$(document).bind('keyup', quitModalKey);

		//set the tabbing controls
		setWebsitesTabControls();
	}

	function closeModal() {
		$('body').removeClass('no_scroll');
		$component_websites.removeClass(open_class);
		$component_websites.hide();
		$(gh_menu_item_class).show();
		$(gh_close_button).hide();
		$(document).unbind('keyup', quitModalKey);
		$menu_icon.focus();
	}

	/**
	 * Globalize the open/close menu function.
	 */
	window.opg_closeHWMenu = function() {
		closeModal();
	};

	function initCarousel() {

		/*Tab Menu*/

		$('.component_header_websites ul.tab-list li').click(function() {
			var tab_id = $(this).attr('data-tab');

			$('.component_header_websites ul.tab-list li').removeClass('current');
			$('.component_header_websites .tab-content').removeClass('current');

			$(this).addClass('current');
			$('#' + tab_id).addClass('current');
		} );

		var options = {
			wrap: false
		};

		/*Carousel*/
		$('.component_header_websites .carousel').carousel(options);

		$('.component_header_websites .carousel').on('slid', function() {
			var totalItems = $(this).find('.carousel-item').length;
			var currentIndex = $(this).find('.carousel-item.active').index() + 1;
			$(this).find('.current-slide').text(currentIndex);
			$(this).find('.total-slide').text(totalItems);
		} );

		$('.component_header_websites .carousel').trigger('slid');

		$('.component_header_websites .carousel-controls').on('slid.bs.carousel', function() {
			var currentIndex = $(this).find('.carousel-item.active').index() + 1;
			$(this).find('.current-slide').text(currentIndex);
		} );

		$('.component_header_websites .carousel-control-prev').click(function() {
			$(this).parent().closest('.carousel-controls').carousel('prev');
		} );

		$('.component_header_websites .carousel-control-next').click(function() {
			$(this).parent().closest('.carousel-controls').carousel('next');
		} );

	}

	function toggleFavorite() {
		$(this).toggleClass(starred_class);
	}

	function debug(msg) {
		if (comp_debug) {

			// console.log('header_websites: ' + msg);
		}
	}

}(jQuery) );
