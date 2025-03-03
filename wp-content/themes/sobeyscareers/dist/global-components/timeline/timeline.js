var index;
var height = 0;
var totalHeight = 0;
var secHeight = 0;
var current_elment_height;
var downHeight = 0;
var totalIndex = 0;
var currentIndex;
var $timeElm;
var $timeElmActive;
var $timeElmboxes;
var total_h;
var scrollHeight = 0;
var mouseWheel;
var total_height = 0;
var sized;
var ua = navigator.userAgent.toLowerCase();
var isAndroid = -1 < ua.indexOf('android');
var isChrome = -1 < ua.indexOf('chrome');

function initTimeline_on_rotate() {
	initTimeline();
}

(function($) {

	var $timeline = jQuery('[data-js-timeline]');

	if (! $timeline.length) {
		return; // Return if component isn't on the page
	}

	window.addEventListener('orientationchange', initTimeline_on_rotate, false);

	jQuery(window).resize(function() {
		if (is_mobile() ) {
return;
}
		clearTimeout(sized);
		sized = setTimeout(initTimeline, 200);
	} );

	jQuery(document).ready(function(e) {

		// dont run in BB

		if (jQuery('html').hasClass('fl-builder-edit') ) {
return;
}

		initTimeline();

		// click title to open first icons

		jQuery('.component_timeline .icons h6, .component_timeline .icons .heading-6').on('click', function() {
			jQuery(this).siblings('.icons').find('button:first').trigger('click');
		} );

		jQuery('.component_timeline .arrow-down').on('click', function() {
			$timeElm = jQuery(this).parents('.component_timeline');
			current_scroll_position = $timeElm.find('.segments-main.active').scrollTop();
			total_height = 0;
			$timeElm.find('.segments-main.active .segment').each(function() {
				total_height += parseInt(jQuery(this).height() );
			} );
			scrollHeight = current_scroll_position + parseInt(300);
			if (scrollHeight < total_height) {

				// if ( isAndroid && isChrome ) {
				// 	$timeElm.find('.segments-main.active').scrollTop(scrollHeight);
				// } else {
					$timeElm.find('.segments-main.active').animate( {
						scrollTop: parseInt(scrollHeight)
					}, {
						duration: 1000
					} );

				// }
			}

		} );

		jQuery('.component_timeline .arrow-up').on('click', function() {
			$timeElm = jQuery(this).closest('.component_timeline');
			current_scroll_position = $timeElm.find('.segments-main.active').scrollTop();
			scrollHeight = current_scroll_position - parseInt(300);
			if (0 > scrollHeight) {
				scrollHeight = 0;
			}

			// if ( isAndroid && isChrome ) {
			// 	$timeElm.find('.segments-main.active').scrollTop(scrollHeight);
			// } else {
				$timeElm.find('.segments-main.active').animate( {
					scrollTop: parseInt(scrollHeight)
				}, {
						duration: 1000
				} );

			// }
		} );

		jQuery('.component_timeline .segments-main').on('scroll', function() {
			timelineArrows();
		} );

		/**  Timeline Tabs**/
		jQuery('.component_timeline .timelinetabs li').on('click', function() {
			var datashow = jQuery(this).attr('data-show');
			var slideSize = 0;
			var currentWidth = 0;
			var totalWidth = 0;
			var $this = jQuery(this).closest('.component_timeline');
			$this.find('.timelinetabs li').removeClass('active');
			$this.find('.segments-main').removeClass('active');
			$this.find('#' + datashow).addClass('active');
			$this.find('.timline-unit-status, .intro_copy').removeClass('active');
			$this.find('.timline-unit-status[data-status = "' + datashow + '"]').addClass('active');
			$this.find('.intro_copy[data-show = "' + datashow + '"]').addClass('active');
			jQuery(this).addClass('active');
			$this.find('.segments-main.active').prevAll().each(function() {
				slideSize += jQuery(this).outerWidth(true);
			} );

			// if ( isAndroid && isChrome ) {
				// duration = 1;
			// } else {
				duration = 1000;

			// }
			$this.find('.segment-main-inner').animate(
				{'left': '-' + slideSize + 'px'},
				{
					duration: duration,
					easing: 'easeInOutBack'
				} );

			// reset_scroll($this);
			timelineArrows($this);

			setTimeout(function() {
				scroll_to_active_task($this);
			}, 700);

			//aria

			$this.find('.icons, .arrow-bottom, .arrow-top').children('span').attr('taonex', -1);
			$this.find('#' + datashow).find('.icons, .arrow-bottom, .arrow-top').children('span').attr('taonex', 0);
			$(this).attr('aria-selected', 'true').siblings('li').attr('aria-selected', 'false');

			//here we want to disable all buttons within inactive segments to ease keyboard navigation
			if ($(this).parent('ul').hasClass('desktop-tabs') ) {
				$('.segments-main .icons button', $this).attr('disabled', 'disabled');
				$('.segments-main.active .icons button', $this).attr('disabled', false);
			}

		} );

		$('.component_timeline .timelinetabs li').on('keydown', function(event) {
			code = (event.which) ? event.which : event.keyCode;
			if ( (13 === code) || (32 === code) ) {
				$(this).trigger('click');
			}
		 } );

		 $('.component_timeline .segmentbox .icons span').on('keydown', function(event) {
			code = (event.which) ? event.which : event.keyCode;
			if ( (13 === code) || (32 === code) ) {
				$(this).trigger('click');
			}
		 } );

		 $('.component_timeline .timeline-detail-section .icon-box span').on('keydown', function(event) {
			code = (event.which) ? event.which : event.keyCode;
			if ( (13 === code) || (32 === code) ) {
				$(this).trigger('click');
			}
		 } );

		 $('.component_timeline .timeline-detail-section .action-button img').on('keydown', function(event) {
			code = (event.which) ? event.which : event.keyCode;
			if ( (13 === code) || (32 === code) ) {
				$(this).trigger('click');
			}
		 } );

		/**  Mobile timeline dropdown ***/
		$('.component_timeline .navigation-select').each(function() {
			var $this = $(this),
			numberOfOptions = $(this).children('option').length;
			$this.addClass('select-hidden');
			$this.wrap('<div class="select"></div>');
			$this.after('<div class="select-styled"></div>');
			var $elemTimeline = jQuery(this).closest('.component_timeline');
			var $styledSelect = $this.next('div.select-styled');
			$styledSelect.text($this.children('option').eq(0).text() );

			var $list = $('<ul />', {
				'class': 'select-options'
			} ).insertAfter($styledSelect);

			for (var i = 0; i < numberOfOptions; i++) {
				$('<li />', {
					text: $this.children('option').eq(i).text(),
					rel: $this.children('option').eq(i).val(),
					datashow: $this.children('option').eq(i).attr('data-show')
				} ).appendTo($list);
			}

			var $listItems = $list.children('li');
			$styledSelect.click(function(e) {
				e.stopPropagation();
				$('div.select-styled.active').not(this).each(function() {
					$(this).removeClass('active').next('ul.select-options').hide();
				} );
				$(this).toggleClass('active').next('ul.select-options').toggle();
			} );
			$listItems.click(function(e) {
				e.stopPropagation();
				$styledSelect.text($(this).text() ).removeClass('active');
				$this.val($(this).attr('rel') );
				var datashow = $(this).attr('datashow');
				var selected_swipesize = 0;
				$elemTimeline.find('.segments-main').removeClass('active');
				$elemTimeline.find('#' + datashow).addClass('active');
				$elemTimeline.find('.segments-main.active').prevAll().each(function() {
					selected_swipesize += jQuery(this).outerWidth(true);
				} );

				// if ( isAndroid && isChrome ) {
					// duration = 1;
				// } else {
					duration = 1000;

				// }
				$elemTimeline.find('.segment-main-inner').animate(
					{left: '-' + selected_swipesize + 'px'},
					{
						duration: duration,
						easing: 'easeInOutBack'
				} );
				$elemTimeline.find('.desktop-tabs li').removeClass('active');
				$elemTimeline.find('.desktop-tabs li[data-show = \'' + datashow + '\']').addClass('active');
				$elemTimeline.find('.timline-unit-status').removeClass('active');
				$elemTimeline.find('.timline-unit-status[data-status = "' + datashow + '"]').addClass('active');

				// reset_scroll($this);
				$list.hide();
				setTimeout(function() {
					scroll_to_active_task($elemTimeline);
				}, 100);


				//here we want to disable all buttons within inactive segments to ease keyboard navigation
				$('.segments-main .icons button').attr('disabled', 'disabled');
				$('.segments-main.active .icons button').attr('disabled', false);

			} );

			$(document).click(function() {
				$styledSelect.removeClass('active');
				$list.hide();
			} );
		} );


		$timeline.KLightBoxTimeline( {} );

		slickSliderTimeline();

		jQuery('.component_timeline [data-js-return-timeline]').on('click', function() {
			jQuery('body').removeClass('no_scroll');
			jQuery(this).closest('.component_timeline').find('.timeline-detail-section-main').hide().removeClass('is-open');
			jQuery(this).closest('.segments-main').find('[taonex="-1"]').attr('taonex', 0);
			jQuery(this).closest('.segments-main').find('.timeline-detail-section-main').find('[taonex="0"]').attr('taonex', -1);
			jQuery(this).closest('.component_timeline').find('.desktop-tabs').find('[taonex="-1"]').attr('taonex', 0);

			//focus the user back on the button that was clicked so they don't leave the timeline section and if tabbing, keep position
			jQuery(this).closest('.segments-main.active').find('button.active').focus().removeClass('active');
			$( [ document.documentElement, document.body ] ).animate( {
				scrollTop: jQuery(this).closest('.fl-module-timeline').offset().top
			}, 500);
		} );

		jQuery('.component_timeline .icon-box  span').on('click', function() {
			jQuery(this).closest('.component_timeline').find('.icon-box  span').removeClass('active');
			jQuery(this).addClass('active');
		} );

		if (! is_mobile() ) {
			jQuery('.segments-main').swipe( {
				swipeLeft: function(event, direction, distance, duration, fingerCount, fingerData) {
					timeLineSwipeHandler(direction, this);
				},
				swipeRight: function(event, direction, distance, duration, fingerCount, fingerData) {
					timeLineSwipeHandler(direction, this);
				},
				allowPageScroll: 'vertical',
				threshold: 50
			} );
		}

	} );

}(jQuery) );

jQuery.fn.KLightBoxTimeline = function(options) {
	var $$ = jQuery.noConflict();

	/* defaults */
	var defaults = {
		effectTime: 500,
		roundK: true
	};

	/* Variables */
	var settings = $$.extend( {}, defaults, options);
	var itemSelector = '.light-box-item';
	var index = 0;
	var items = $$(this).find(itemSelector);
	var totalItems = items.length;
	var lightBoxControls, lightBoxNext;

	function Open(obj) {
		$$('.component_timeline .desktop-tabs li').attr('taonex', '-1');
		index = $$(itemSelector).index(obj);
		Update();
		$$('.module-timeline-lightbox-wrap').fadeIn(settings.effectTime);
		$$('.current-gallery-item').html(index + 1);
		$$('.component_timeline .segments-main.active [taonex="0"]').addClass('restore0tabs').attr('taonex', -1);
		$$(obj).find('[role="button"]').addClass('restore_focus');
		lightBoxControls = $$('.module-timeline-lightbox-wrap a');
		lightBoxNext = (1 < lightBoxControls.length) ? 1 : 0;
		$$('.module-timeline-lightbox-wrap .lightbox-close').focus();
	}

	function Update() {
		LoadContent($$(items[index] ) );
		if ($$(items[index] ) ) {
			$$('.current-gallery-item').html(index + 1);
			$$('.total-gallery-item').html(totalItems);
		} else {
			$$('.current-gallery-item').html(index);
			$$('.total-gallery-item').html(totalItems);
		}
		if (2 > totalItems) {
			$$('.component_timeline .lightbox-content-box .nav-control').css('opacity', 0);
		} else {
			$$('.component_timeline .lightbox-content-box .nav-control').css('opacity', 1);
		}
	}

	function Close() {
		$$('.component_timeline .segments-main.active .restore0tabs').attr('taonex', 0).removeClass('restore0tabs');
		$$('.component_timeline .segments-main.active .restore_focus').focus().removeClass('restore_focus');
		$$('.component_timeline .desktop-tabs li').attr('taonex', '0');
		$$('.module-timeline-lightbox-wrap').fadeOut(settings.effectTime);
		$$('.module-timeline-lightbox-wrap a').removeClass('is-tabbing');
		lightBoxControls = null;
		lightBoxNext = null;
		setTimeout(function() {
			$$('.lightbox-append').html('');
			$$('.lightbox-intro').html('');
		}, 500);
	}

	function timelineModalBackward(container, axnSelector) {
		var next = lightBoxNext - 1;
		$$(axnSelector, container).removeClass('is-tabbing');
		$$(lightBoxControls[lightBoxNext],  container).focus().addClass('is-tabbing');
		if (0 == lightBoxNext) {
			next = (lightBoxControls.length - 1);
		}
		lightBoxNext = next;
	}
	function timelineModalForward(container, axnSelector) {
		var next = 1 + lightBoxNext;
		$$(axnSelector, container).removeClass('is-tabbing');
		$$(lightBoxControls[lightBoxNext], container).focus().addClass('is-tabbing');
		if (next == lightBoxControls.length) {
			next = 0;
		}
		lightBoxNext = next;
	}

	function Next() {
		if (index < totalItems - 1) {
			index++;
			Update();
		} else if (settings.roundK) {
			index = 0;
			Update();
		}
	}

	function Previous() {
		if (0 < index) {
			index--;
			Update();
		} else if (settings.roundK) {
			index = totalItems - 1;
			Update();
		}
	}

	function LoadContent(obj) {
		var $current = jQuery(obj).parents('[data-js-timeline]').find('.lightbox-append');
		var tag = $$(obj).attr('data-show');
		var dataintro = $$(obj).attr('data-intro');
		if ('lightbox-video' == tag) {
			var videoURL = 'https://www.youtube.com/embed/' + $$(obj).attr('data-lightbox');
			var videoframe = '<iframe src="' + videoURL + '?autoplay=true" frameborder="0" allowfullscreen allow="accelerometer; autoplay; encrypted-media; gyroscope"></iframe>';
			$current.html(videoframe);
			jQuery(obj).parents('[data-js-timeline]').find('.lightbox-intro').hide();

		} else if ('lightbox-image' == tag) {
			var imgsrc = $$(obj).attr('data-lightbox');
			var image = '<img src="' + imgsrc + '"/>';
			jQuery(obj).parents('[data-js-timeline]').find('.lightbox-intro').show().html(dataintro);
			$current.html(image);
		}
	}

	$$('.component_timeline').find(itemSelector).stop().click(function(e) {
		var selected = $$(this).attr('data-gallery');
		itemSelector = '.' + selected;
		items = $$('body').find(itemSelector);
		totalItems = items.length;
		e.preventDefault();
		Open($$(this) );
	} );

	$$('.component_timeline .lightbox-close').stop().on('click', function() {
		Close();
	} );
	$$('.component_timeline .nav-next').stop().on('click', function() {
		Next();
	} );
	$$('.component_timeline .nav-prev').stop().on('click', function() {
		Previous();
	} );

	$$(document).keydown(function(e) {
		var keyCode = (e.which) ? e.which : e.KeyCode;
		var $target = e.target.className;
		switch (keyCode) {
			case 9:
				if ($$('.component_timeline .module-timeline-lightbox-wrap').is(':visible') ) {
					e.preventDefault();
					if (e.shiftKey) {
						timelineModalBackward($$('.component_timeline .module-timeline-lightbox-wrap'), 'a');
					} else {
						timelineModalForward($$('.component_timeline .module-timeline-lightbox-wrap'), 'a');
					}
				} else if ($$('.component_timeline .timeline-detail-section-main.is-open').length) {
					e.preventDefault();
					if (typeof lightBoxNext == undefined || null == lightBoxNext) {
						lightBoxNext = 1;
					}
					lightBoxControls = new Array();

					//these need to be reset everytime in case the view switches
					$$('.component_timeline .timeline-detail-section-main.is-open .modal-action').each(function() {
						if ($$(this).is(':visible') ) {
							lightBoxControls.push($$(this) );
						}
					} );
					lightBoxNext = (lightBoxNext < lightBoxControls.length) ? lightBoxNext : 0;
					if (e.shiftKey) {
						timelineModalBackward($$('.component_timeline .timeline-detail-section-main.is-open'), '.modal-action');
					} else {
						timelineModalForward($$('.component_timeline .timeline-detail-section-main.is-open'), '.modal-action');
					}
				}
				break;
			case 39:
				if ($$('.component_timeline .module-timeline-lightbox-wrap').is(':visible') ) {
					Next();
				}
				break;
			case 37:
				if ($$('.component_timeline .module-timeline-lightbox-wrap').is(':visible') ) {
					Previous();
				}
				break;
			case 27:
				if ($$('.component_timeline .module-timeline-lightbox-wrap').is(':visible') ) {
					Close();
				}
				break;
			case 13:
				if ($$('.module-timeline-lightbox-wrap').is(':visible') ) {
					if ('nav-control' != $target.match(/nav\-control/) ) {
						$$('.module-timeline-lightbox-wrap .lightbox-close').focus();
					} else {
						$$(e).focus();
					}
				} else if ($$('.component_timeline .timeline-detail-section-main').is(':visible') ) {
					if ('carousel-control' == $target.match(/carousel\-control/) ) {
						$$(e).focus();
					} else {
						$$(e).trigger('click');
					}
				}
				break;
			default:
				break;
		}
	} );
};

function initTimeline() {
	setTimeout(function() {
		var $this = jQuery('.component_timeline');
		$this.find('.segments-main.active').attr('data-active_scroll_position', '');
		set_timeline_width();
		set_timeline_height();
		$this.find('.desktop-tabs li.active:first').click();
		$this.css('opacity', 100);
		text = $this.find('.desktop-tabs li.active:first').text();
		$this.find('.menu-dropdown-mobile .select-styled').text(text);
		setTimeout(function() {
			scroll_to_active_task($this);
		}, 1000);
	}, 100);
}

function scroll_to_active_task(component) {

	$timeElm = component;
	if (! $timeElm.find('.segments-main.active .active-task').length) {
return;
}
	scroll_position = $timeElm.find('.segments-main.active').attr('data-active_scroll_position');

	if (! scroll_position) {
		calc1 = parseInt($timeElm.find('.segments-main.active').offset().top);
		calc2 = parseInt($timeElm.find('.segments-main.active .active-task').offset().top);
		calc3 =  parseInt($timeElm.find('.segments-main.active').height() ) / 2;
		scroll_position = calc2 - calc1 - calc3;
		$timeElm.find('.segments-main.active').attr('data-active_scroll_position', scroll_position);
	}


	// if ( isAndroid && isChrome ) {
		// $timeElm.find('.segments-main.active').scrollTop(scroll_position);
	// } else {
		$timeElm.find('.segments-main.active').stop().animate( {
			scrollTop: scroll_position
		}, {
			duration: 1000,
			easing: 'easeInOutBack',
			complete: function() {
				console.log('complete. (' + scroll_position + ')');
				jQuery('.segments-main.active').scrollTop(scroll_position);
			}
		} );

	// }
	setTimeout(function() {
		timelineArrows();
	}, 1100);

}

function set_timeline_width() {
	var $this = jQuery('.component_timeline');
	w = $this.find('.timeline-container').width();
	l = $this.find('.segments-main').length;
	totalTimelineWidth = w * l;
	$this.find('.segment-main-inner').css('width', totalTimelineWidth);
	$this.find('.segments-main').css('display', 'block');
}

function set_timeline_height() {

	setTimeout(function() {

		var $this = jQuery('.component_timeline');
		n = $this.find('.segments-main.active:first .timeline-detail-container-box').length - 1;

		if (0 > n) {
return;
}
		if (3 < n) {
			n = 3;
		}

		$this.find('.segments-main').height('10000px');

		parent_offset = $this.find('.segments-main.active').offset().top;
		child_offset = $this.find('.segments-main.active .timeline-detail-container-box:eq(' + n + ')').offset().top;
		child_height = $this.find('.segments-main.active .timeline-detail-container-box:eq(' + n + ')').outerHeight();

		view_height = child_offset - parent_offset + child_height + 30;

		device_height = jQuery(window).height();
		max_height = device_height * 0.6;

		if (view_height > max_height) {
			view_height = max_height;
		}

		$this.find('.segments-main').height(view_height);

	}, 100);

}

function intractsection(current, sectionID) {
	jQuery(this).addClass('active');
	var elem = jQuery(current).closest('.component_timeline').find('.timeline-detail-section-main');
	elem.removeClass('active');
	elem.find('.timeline-detail').addClass('no-show');
	elem.find('.' + sectionID).removeClass('no-show');
	jQuery('html, body').animate( {
		scrollTop: jQuery('.timelines').offset().top
	}, {
			duration: 800,
			easing: 'easeInOutBack'
	} );
}

function slickSliderTimeline() {
	jQuery('.timelinecarousalvideo .carousel-control-prev').on('click', function() {
		jQuery(this).parent().carousel('prev');
	} );

	jQuery('.timelinecarousalvideo .carousel-control-next').on('click', function() {
		jQuery(this).parent().carousel('next');
	} );
	jQuery('.timelinecarousalphoto .carousel-control-prev').on('click', function() {
		jQuery(this).parent().carousel('prev');
	} );

	jQuery('.timelinecarousalphoto .carousel-control-next').on('click', function() {
		jQuery(this).parent().carousel('next');
	} );

}

function openTimeline(current, id) {
	jQuery('body').addClass('no_scroll');
	var elem = jQuery(current).closest('.component_timeline');
	var elemDetail = jQuery(current).closest('.timeline-detail-container-box').find('.timeline-detail-section-main');
	elemDetail.show().addClass('is-open');
	elemDetail.find('.timeline-detail').addClass('no-show');
	elemDetail.find('.' + id).removeClass('no-show');

	// tabbing

	jQuery(current).closest('.segments-main').find('[taonex="0"]').attr('taonex', -1);
	elem.find('[taonex="0"]').attr('taonex', -1);
	elemDetail.find('[role="button"], .action-button img').attr('taonex', '0');

	if ('timelineinfo' === id) {
		elemDetail.find('button').removeClass('active');
		elemDetail.find('.info-icon').addClass('active');
	} else if ('timelinevideo' === id) {
		elemDetail.find('button').removeClass('active');
		elemDetail.find('.play-icon').addClass('active');
	} else {
		elemDetail.find('button').removeClass('active');
		elemDetail.find('.photo-icon').addClass('active');
	}
	jQuery(current).addClass('active');
}

function timeLineSwipeHandler(direction, elem) {
	var totatTabs = jQuery(elem).closest('.component_timeline').find('.timelinetabs .desktop-tabs li').length - 1;
	var activetab = jQuery(elem).closest('.component_timeline').find('.timelinetabs li.active').index();
	var $elemList = jQuery(elem).closest('.component_timeline').find('.timelinetabs li');
	var $elemMain = jQuery(elem).closest('.component_timeline').find('.segments-main');
	var $elm = jQuery(elem).closest('.component_timeline');
	var swipesize = 0;
	var activeMenuID;
	var selectedText;
	if ('left' === direction) {
		if (activetab < totatTabs) {
			$elemList.removeClass('active');
			$elemMain.removeClass('active');
			$elemList.eq(activetab + 1).addClass('active');
			$elemMain.eq(activetab + 1).addClass('active');
			$elm.find('.timline-unit-status').removeClass('active');
			$elm.find('.timline-unit-status').eq(activetab + 1).addClass('active');
			$elm.find('.segments-main.active').prevAll().each(function() {
				swipesize += jQuery(this).outerWidth(true);
			} );
			$elm.find('.segment-main-inner').animate( {left: '-' + swipesize + 'px'},
				{
					duration: 1000,
					easing: 'easeInOutBack'
				} );
			activeMenuID = $elm.find('.segments-main.active').attr('id');
			selectedText = $elm.find('.select-options li[datashow = "' + activeMenuID + '"]').text();
			$elm.find('.menu-dropdown-mobile .select-styled').text(selectedText);

			// reset_scroll(elem);
		}
	}
	if ('right' === direction) {
		if (0 < activetab) {
			$elemList.removeClass('active');
			$elemMain.removeClass('active');
			$elemList.eq(activetab - 1).addClass('active');
			$elemMain.eq(activetab - 1).addClass('active');
			$elm.find('.timline-unit-status').removeClass('active');
			$elm.find('.timline-unit-status').eq(activetab - 1).addClass('active');
			$elm.find('.segments-main.active').prevAll().each(function() {
				swipesize += jQuery(this).outerWidth(true);
			} );
			$elm.find('.segment-main-inner').animate( { left: '-' + swipesize + 'px' }, {
				duration: 1000,
				easing: 'easeInOutBack'
			} );
			activeMenuID = $elm.find('.segments-main.active').attr('id');
			selectedText = $elm.find('.select-options li[datashow = "' + activeMenuID + '"]').text();
			$elm.find('.menu-dropdown-mobile .select-styled').text(selectedText);

			// reset_scroll(elem);
		}
	}
	var $this = jQuery('.component_timeline');
	setTimeout(function() {
		scroll_to_active_task($this);
	}, 700);

}

function timelineArrows(elem) {
	if ('undefined' == typeof elem) {
		$timeElm = jQuery('body').find('.component_timeline');
	} else {
		$timeElm = jQuery(elem).closest('.component_timeline');
	}

	setTimeout(function() {
		total_height = 0;
		$parent_element = $timeElm.find('.segments-main.active');
		$timeElm.find('.segments-main.active .segment').each(function() {
			total_height += parseInt(jQuery(this).outerHeight(true) );
		} );
		var scroll = $parent_element.scrollTop();
		 var view_height = $parent_element.outerHeight();

		total_height -= 20;

		if (scroll + view_height > total_height) {
			$timeElm.find('.arrow-down').addClass('no-timeline');
			$timeElm.find('.arrow-up').removeClass('no-timeline');
			if (view_height > total_height) {
				$timeElm.find('.arrow-up').addClass('no-timeline');
			}
		} else if (0 >= scroll) {
			$timeElm.find('.arrow-up').addClass('no-timeline');
			$timeElm.find('.arrow-down').removeClass('no-timeline');
		} else {
			$timeElm.find('.arrow-down').removeClass('no-timeline');
			$timeElm.find('.arrow-up').removeClass('no-timeline');
		}
	}, 200);
}

function reset_scroll(elem) {
	$elemTime = jQuery(elem).closest('.component_timeline');
	$elemTime.find('.segments-main').animate( {
		scrollTop: 0
	}, {
			duration: 1,
			easing: 'easeInOutBack'
		} );
}
