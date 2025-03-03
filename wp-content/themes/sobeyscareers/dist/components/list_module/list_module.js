(function($) {
	var $list = $('.component_list_module');
	var winWidth;
	var keyedAccess = false;

	var navDirection = function(direction, container, element) {
		var current, nextActive;
		var totalEl = $(element, container).length;
		var prevDiv = $(container).prev('div');
		if (0 < totalEl) {
			if (0 == $(element + '.active', container).length) {
				$(element + ':eq(0)', container).addClass('active');
				$(element + ':eq(0) a', container).focus();
			} else {
				$(element, container).each(function() {
					if ($(this).hasClass('active') ) {
						current = $(this).index();
						nextActive = ('up' == direction) ? current - 1 : 1 + current;
						if (nextActive == totalEl) {
							nextActive = 0;
						}
					}
				} );
				$(element + '.active', container).removeClass('active');
				if (0 <= nextActive) {
					$(element + ':eq(' + nextActive + ')', container).addClass('active');
					$(element + ':eq(' + nextActive + ') a', container).focus();
					$(container).attr('aria-activedescendant', $(element + ':eq(' + nextActive + ')', container).attr('id') );
				} else {
					$('button', prevDiv).focus();
				}
			}
		}
	};

	var exitDropdown = function(buttonContainer, ulContainer) {
		if ($(ulContainer).is(':visible') ) {
			$('button', buttonContainer).trigger('click');
		} else {
			$('button', buttonContainer).blur();
		}
	};

	function keyAccessSetup() {
		$('.select-styled', $list).each(function() {
			var $this = $(this);
			var $next = $('.select-options', $this);
			var $active = $('.active', $next).attr('id');
			$(document).on('keydown', function(e) {
				var theKey = (e.which) ? e.which : e.keyCode;
				switch (theKey) {
					case 38:
						navDirection('up', $next, 'li');
						break;
					case 40:
						navDirection('down', $next, 'li');
						break;
					case 27:
						exitDropdown($this, $next);
						break;
					default:
						break;
				}
			} );

		} );
	}

	if (! $list.length) {
		return; // Return if component isn't on the page
	} else {
		var formatMobileView = function(container) {
			winWidth = $(window).width();
			if (748 < winWidth && $(container).hasClass('select') ) {
				$(container).removeClass('select');
				$('.list-wrapper', container).removeClass('select-styled');
				$('.list-wrapper button', container).attr('disabled', 'disabled');
				$('.list-wrapper li', container).removeClass('select-hidden').attr('role', '').attr('tabindex', '');
				$('.list-wrapper ul, .list-wrapper ol', container).removeClass('select-options').attr('role', '').attr('aria-labelledby', '');
				$('.select-styled button', container).off('click');
			} else if (748 >= winWidth) {
				$(container).addClass('select');
				$('.list-wrapper', container).addClass('select-styled');
				$('.list-wrapper button', container).attr('disabled', false);
				$('.list_content', container).addClass('select-options').attr('tabindex', '-1').attr('role', 'listbox').attr('aria-labelledby', $('.list_content', container).attr('id') );
				$('.list_content li, .list-wrapper ol', container).each(function() {
					if ($('a', this).length) {
						$(this).attr('role', 'option');
					} else {
						$(this).addClass('select-hidden');
					}
				} );
				if (false === keyedAccess) {
					keyAccessSetup();
					keyedAccess = true;
				}
			}
		};

		function setupClickEvents(component) {
			$('.select-styled button', component).on('click', function() {
				if ($('.list-wrapper ul, .list-wrapper ol', component).is(':visible') ) {
					$('.list-wrapper ul, .list-wrapper ol', component).hide().css( { 'display': '' } );
				} else {
					$('.list-wrapper ul, .list-wrapper ol', component).show();
				}
			} );
		}

		$('.component_list_module').each(function() {
			if ('string' == typeof $('.list-content-container', this).attr('data-mobile-select') && '1' == $('.list-content-container', this).attr('data-mobile-select') ) {
				formatMobileView($('.list-content-container', this) );
				setupClickEvents($(this) );
			}
		} );

		$(window).on('resize', function() {
			$('.component_list_module').each(function() {
				if ('string' == typeof $('.list-content-container', this).attr('data-mobile-select') && '1' == $('.list-content-container', this).attr('data-mobile-select') ) {
					formatMobileView($('.list-content-container', this) );
					setupClickEvents($(this) );
				}
			} );
		} );

		$(document).on('click', function(e) {
			if ('undefined' == typeof e.target.parentNode.className || 'select-styled' != e.target.parentNode.className.match(/select\-styled/) ) {
				$('.component_list_module .select-options').each(function() {
					if ($(this).is(':visible') ) {
						exitDropdown($(this).closest('div'), $(this) );
					}
				} );
			}
		} );
	}

}(jQuery) );
