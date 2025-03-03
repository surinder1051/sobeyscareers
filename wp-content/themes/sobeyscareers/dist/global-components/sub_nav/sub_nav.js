jQuery(document).ready(function(e) {
	var navDirection = function(direction, container, element) {
		var current, nextActive;
		var totalEl = jQuery(element, container).length;
		var prevDiv = jQuery(container).prev('div');
		if (0 < totalEl) {
			if (0 == jQuery(element + '.active', container).length) {
				jQuery(element + ':eq(0)', container).addClass('active');
				jQuery(element + ':eq(0) a', container).focus();
			} else {
				jQuery(element, container).each(function() {
					if (jQuery(this).hasClass('active') ) {
						current = jQuery(this).index();
						nextActive = ('up' == direction) ? current - 1 : 1 + current;
						if (nextActive == totalEl) {
							nextActive = 0;
						}
					}
				} );
				jQuery(element + '.active', container).removeClass('active');
				if (0 <= nextActive) {
					jQuery(element + ':eq(' + nextActive + ')', container).addClass('active');
					jQuery(element + ':eq(' + nextActive + ') a', container).focus();
					jQuery(container).attr('aria-activedescendant', jQuery(element + ':eq(' + nextActive + ')', container).attr('id') );
				} else {
					jQuery('button', prevDiv).focus();
				}
			}
		}
	};

	var exitDropdown = function(buttonContainer, ulContainer) {
		if (jQuery(ulContainer).is(':visible') ) {
			jQuery('button', buttonContainer).trigger('click');
		} else {
			jQuery('button', buttonContainer).blur();
		}
	};

	function triggeredSetup() {
		jQuery('.component_sub_nav .select-styled').each(function() {
			var $this = jQuery(this);
			var $next = jQuery($this).next('.select-options');
			var $active = jQuery('.active', $next).attr('id');

			jQuery('a', $next).on('click', function(e) {
				e.preventDefault();
				var $a = jQuery(this);

				// If value in dropdown is numeric treat it as anchor for row

				if (jQuery.isNumeric($a.attr('href') ) ) {
					jQuery( [ document.documentElement, document.body ] ).animate( {
						scrollTop: jQuery('.fl-row:nth-child(' + $a.attr('href') + ')').offset().top - 30
					}, 1000);
				} else if ('' !== $a.attr('href') ) {
					window.open($a.attr('href'), $a.attr('target') );
				}
			} );

			jQuery(document).on('keydown', function(e) {
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

			if (jQuery($this).hasClass('with-button') ) {
				jQuery('button', $this).on('click', function() {
					if (jQuery($next).is(':visible') ) {
						jQuery($next).hide().css( { 'display': '' } ).attr('aria-expanded', 'false').attr('aria-activedescendant', '').removeClass('active');
					} else {
						jQuery(this).blur();
						jQuery($next).show().attr('aria-expanded', 'true').attr('aria-activedescendant', $active).addClass('active');
						jQuery('a', '#' + $active).focus();
					}
				} );
			}
		} );

		jQuery(document).on('click', function(e) {
			if ('undefined' == typeof e.target.parentNode.className || 'select-styled' != e.target.parentNode.className.match(/select\-styled/) ) {
				jQuery('.component_sub_nav .select-options').each(function() {
					if (jQuery(this).hasClass('active') ) {
						exitDropdown(jQuery(this).closest('div'), jQuery(this) );
					}
				} );
			}
		} );

		/*jQuery(window).on('scroll', function() {
		   jQuery('.component_sub_nav .select-options').each(function() {
			  if (jQuery(this).hasClass('active') && ! jQuery(this).is(':focus')) {
				  exitDropdown(jQuery(this).closest('div'), jQuery(this));
			  }
		   } );
		} );*/
	}

	/**
		This code fires when the settings is fired.
	**/

	triggeredSetup();

	jQuery('.fl-builder-content').on('fl-builder.layout-rendered', function() {
		triggeredSetup();
	} );


} );
