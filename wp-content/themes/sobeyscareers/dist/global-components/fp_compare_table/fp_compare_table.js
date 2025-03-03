(function($) {
	var fpCompareTable = $('.component_fp_compare_table');
	var winWidth = $(window).width();

	var setSticky = function(component) {
		var elementTop = $('.compare-table-wrapper', component).offset().top;
		var viewportTop = $(window).scrollTop();
		var colTop = elementTop - viewportTop;

		if (992 < winWidth || 576 > winWidth) {
			$('.sticky', component).css( {'top': ''} );
		} else {
			$('.sticky', component).css( {'top': colTop + 'px'} );
		}

	};

	var responsiveTable = function(component) {
		var wrapper = $('.compare-table-wrapper div', component);
		var scrollMargin = 0;
		var i = 0;
		var stickyMargin = Math.floor( (winWidth - $(component).width() ) / 2);
		var stickyLeft = new Array();
		var liHeight = new Array($('ul:eq(0) li', component).length);

		if ($('.sticky', component).length) {
			stickyLeft.push(stickyMargin);
			$('.sticky', component).each(function() {
				scrollMargin += $(this, component).width();
				stickyLeft.push(stickyMargin + $(this, component).width() );
			} );
			for (i; i < $('.sticky', component).length; i++) {
				if (stickyLeft[i] ) {
					$('.sticky:eq(' + i + ')', component).attr('data-left', stickyLeft[i] );
				}
			}
			$('.compare-table-wrapper div').attr('data-scroll-margin', scrollMargin);
		}
		if (992 < winWidth || 576 > winWidth) {
			$(wrapper).css( {'margin-left': ''} );
			$('.sticky', component).each(function() {
				$(this).css( {'left': ''} );
			} );
		} else {
			$(wrapper).css( {'margin-left': $(wrapper).attr('data-scroll-margin') + 'px'} );
			$('.sticky', component).each(function() {
					$(this).css( {'left': $(this).attr('data-left') + 'px'} );
			} );
		}

		if (576 < winWidth) {
			$('li', component).css( {'height': ''} );
			$('ul', component).each(function() {
				i = 0;
				for (i; i < liHeight.length; i++) {

					if (undefined == liHeight[i] || (parseInt($('li:eq(' + i + ')', this).height(), 10) + 20) > liHeight[i] ) {
						liHeight[i] = parseInt($('li:eq(' + i + ')', this).height(), 10) + 20;
					}
				}
			} );
			$('ul', component).each(function() {
				i = 0;
				for (i; i < liHeight.length; i++) {
					$('li:eq(' + i + ')', this).css( {'height': liHeight[i] + 'px'} );
				}
			} );
		}
	};

	var bindFilterToggle = function(button) {
		var parUL = $(button).parents('ul');
		$('.td.filter', parUL).not('.active').toggle('500', function() {
			$(button).blur();
		} );
	};

	var bindTableFilter = function(button, component) {
		var showCol = $(button).parents('li').attr('data-cell');

		$('.filter.active button', component).off('click').removeClass('outline');
		$('.filter.active', component).removeClass('active').css( {'display': 'list-item'} );

		$(button).parents('li').addClass('active');
		$(button).addClass('outline');
		$('.td.mobile-visible', component).not('.filter').hide().removeClass('mobile-visible').addClass('mobile-hidden').css( {'display': ''} );
		$('.mobile-hidden.data-column-' + showCol, component).show().removeClass('mobile-hidden').addClass('mobile-visible');
		if ($('.columns', component).length) {
			$('.mobile-visible.data-column-' + showCol, component).css( {'display': 'table-cell'} );
		}
		$('.filter.active', component).css( {'display': 'list-item'} );
		$(button).off('click');
		bindFilterButtons(component, true);
	};

	var bindFilterButtons = function(component, triggerClick) {
		$('.td.filter', component).not('.active').each(function() {
			$('button', this).off('click').on('click', function() {
				bindTableFilter($(this), component);
			} ).attr('aria-label', 'Click to view content for this filter');;
		} );
		$('.td.filter.active button', component).on('click', function() {
			bindFilterToggle($(this) );
			$(this).attr('aria-label', 'Click to view more filters');
		} );
		if (triggerClick) {
			$('.td.filter.active button', component).trigger('click');
		}
	};

	if (! fpCompareTable.length) {
		return; // Return if component isn't on the page
	} else {
		$(fpCompareTable).each(function() {
			var $this = $(this);
			setSticky($this);
			responsiveTable(this);
			if (576 > winWidth) {
				$('.td.filter', this).each(function() {
					var buttonText;
					if (! $('button', this).length) {
						buttonText = $('span', this).html();
						$(this).html('<button class="button no-style">' + buttonText + '</button>');
					}
					if (0 == $(this).attr('data-cell') ) {
						$(this).addClass('active');
						$('button', this).addClass('outline');

						$('button', this).attr('aria-label', 'Click to view filters');
					}
				} );
				bindFilterButtons($this, false);

			}
		} );
		$(window).on('resize', function() {
			winWidth = $(window).width();
			$(fpCompareTable).each(function() {
				var $this = $(this);
				responsiveTable(this);
				if (576 > winWidth && 0 == $('.td.filter.active', this).length) {
					$('.td.filter', this).each(function() {
						var buttonText;
						if (! $('button', this).length) {
							buttonText = $('span', this).html();
							$(this).html('<button class="button no-style">' + buttonText + '</button>');
						}
						if (0 == $(this).attr('data-cell') ) {
							$(this).addClass('active');
							$('button', this).addClass('outline');

							$('.mobile-visible', $this).removeClass('mobile-visible').addClass('mobile-hidden');
							$('.data-column-0.mobile-hidden', $this).removeClass('mobile-hidden').addClass('mobile-visible');
						}
					} );
					bindFilterButtons($this, false);

				} else if (576 <= winWidth) {
					$('.td').css( {'display': ''} );
					$('.td.filter', this).each(function() {
						var spanText ;
						if ($('button', this).length) {
							spanText = $('button', this).html();
							$('button', this).off('click').attr('aria-label', '');
							$(this).removeClass('active');
							$('button', this).removeClass('outline');
							$(this).html('<span>' + spanText + '</span>');
						}
						if (0 !== $(this).attr('data-cell') ) {
							$(this).removeClass('mobile-visible').addClass('mobile-hidden');
						} else {
							$(this).removeClass('mobile-hidden').addClass('mobile-visible');
						}
					} );
				}
			} );
		} ).on('scroll', function() {
			$(fpCompareTable).each(function() {
				setSticky($(this) );
			} );
		} );
	}

}(jQuery) );
