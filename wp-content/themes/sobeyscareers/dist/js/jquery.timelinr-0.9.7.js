/* ----------------------------------
jQuery Timelinr 0.9.7
tested with jQuery v1.6+

Copyright 2011, CSSLab.cl
Free under the MIT license.
http://www.opensource.org/licenses/mit-license.php

instructions: http://www.csslab.cl/2011/08/18/jquery-timelinr/
---------------------------------- */

jQuery.fn.timelinr = function(options) {

	// default plugin settings
	settings = jQuery.extend( {
		orientation: 'horizontal', // value: horizontal | vertical, default to horizontal
		containerDiv: '#timeline', // value: any HTML tag or #id, default to #timeline
		datesDiv: '#dates', // value: any HTML tag or #id, default to #dates
		datesSelectedClass: 'selected', // value: any class, default to selected
		datesSpeed: 'normal', // value: integer between 100 and 1000 (recommended) or 'slow', 'normal' or 'fast'; default to normal
		issuesDiv: '#issues', // value: any HTML tag or #id, default to #issues
		issuesSelectedClass: 'selected', // value: any class, default to selected
		issuesSpeed: 'fast', // value: integer between 100 and 1000 (recommended) or 'slow', 'normal' or 'fast'; default to fast
		issuesTransparency: 0.2, // value: integer between 0 and 1 (recommended), default to 0.2
		issuesTransparencySpeed: 500, // value: integer between 100 and 1000 (recommended), default to 500 (normal)
		prevButton: '#prev', // value: any HTML tag or #id, default to #prev
		nextButton: '#next', // value: any HTML tag or #id, default to #next
		arrowKeys: 'false', // value: true | false, default to false
		startAt: 1, // value: integer, default to 1 (first)
		autoPlay: 'false', // value: true | false, default to false
		autoPlayDirection: 'forward', // value: forward | backward, default to forward
		autoPlayPause: 2000 // value: integer (1000 = 1 seg), default to 2000 (2segs)
	}, options);

	(function($) {

		// Checks if required elements exist on page before initializing timelinr | improvement since 0.9.55
		if (0 < $(settings.datesDiv).length && 0 < $(settings.issuesDiv).length) {

			// setting variables... many of them
			var howManyDates = $(settings.datesDiv + ' li').length;
			var howManyIssues = $(settings.issuesDiv + ' li').length;
			var currentDate = $(settings.datesDiv).find('a.' + settings.datesSelectedClass);
			var currentIssue = $(settings.issuesDiv).find('li.' + settings.issuesSelectedClass);
			var widthContainer = $(settings.containerDiv).width();
			var heightContainer = $(settings.containerDiv).height();
			var widthIssues = $(settings.issuesDiv).width();
			var heightIssues = $(settings.issuesDiv).height();
			var widthIssue = $(settings.issuesDiv + ' li').width();
			var heightIssue = $(settings.issuesDiv + ' li').height();
			var widthDates = $(settings.datesDiv).width();
			var heightDates = $(settings.datesDiv).height();
			var widthDate = $(settings.datesDiv + ' li').width();
			var heightDate = $(settings.datesDiv + ' li').height();

			// set positions!
			if ('horizontal' == settings.orientation) {
				$(settings.issuesDiv).width(widthIssue * howManyIssues);
				$(settings.datesDiv).width(widthDate * howManyDates).css('marginLeft', widthContainer / 2 - widthDate / 2);
				var defaultPositionDates = parseInt($(settings.datesDiv).css('marginLeft').substring(0, $(settings.datesDiv).css('marginLeft').indexOf('px') ) );
			} else if ('vertical' == settings.orientation) {
				$(settings.issuesDiv).height(heightIssue * howManyIssues);
				$(settings.datesDiv).height(heightDate * howManyDates).css('marginTop', heightContainer / 2 - heightDate / 2);
				var defaultPositionDates = parseInt($(settings.datesDiv).css('marginTop').substring(0, $(settings.datesDiv).css('marginTop').indexOf('px') ) );
			}

			$(settings.datesDiv + ' a').click(function(event) {
				event.preventDefault();

				// first vars
				var whichIssue = $(this).text();
				var currentIndex = $(this).parent().prevAll().length;

				// moving the elements
				if ('horizontal' == settings.orientation) {
					$(settings.issuesDiv).animate( { 'marginLeft': -widthIssue * currentIndex }, { queue: false, duration: settings.issuesSpeed } );
				} else if ('vertical' == settings.orientation) {
					$(settings.issuesDiv).animate( { 'marginTop': -heightIssue * currentIndex }, { queue: false, duration: settings.issuesSpeed } );
				}
				$(settings.issuesDiv + ' li').animate( { 'opacity': settings.issuesTransparency }, { queue: false, duration: settings.issuesSpeed } ).removeClass(settings.issuesSelectedClass).eq(currentIndex).addClass(settings.issuesSelectedClass).fadeTo(settings.issuesTransparencySpeed, 1);

				// prev/next buttons now disappears on first/last issue | bugfix from 0.9.51: lower than 1 issue hide the arrows | bugfixed: arrows not showing when jumping from first to last date
				if (1 == howManyDates) {
					$(settings.prevButton + ',' + settings.nextButton).fadeOut('fast');
				} else if (2 == howManyDates) {
					if ($(settings.issuesDiv + ' li:first-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.prevButton).fadeOut('fast');
						$(settings.nextButton).fadeIn('fast');
					} else if ($(settings.issuesDiv + ' li:last-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.nextButton).fadeOut('fast');
						$(settings.prevButton).fadeIn('fast');
					}
				} else {
					if ($(settings.issuesDiv + ' li:first-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.nextButton).fadeIn('fast');
						$(settings.prevButton).fadeOut('fast');
					} else if ($(settings.issuesDiv + ' li:last-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.prevButton).fadeIn('fast');
						$(settings.nextButton).fadeOut('fast');
					} else {
						$(settings.nextButton + ',' + settings.prevButton).fadeIn('slow');
					}
				}

				// now moving the dates
				$(settings.datesDiv + ' a').removeClass(settings.datesSelectedClass);
				$(this).addClass(settings.datesSelectedClass);
				if ('horizontal' == settings.orientation) {
					$(settings.datesDiv).animate( { 'marginLeft': defaultPositionDates - (widthDate * currentIndex) }, { queue: false, duration: 'settings.datesSpeed' } );
				} else if ('vertical' == settings.orientation) {
					$(settings.datesDiv).animate( { 'marginTop': defaultPositionDates - (heightDate * currentIndex) }, { queue: false, duration: 'settings.datesSpeed' } );
				}
			} );

			$(settings.nextButton).bind('click', function(event) {
				event.preventDefault();

				// bugixed from 0.9.54: now the dates gets centered when there's too much dates.
				var currentIndex = $(settings.issuesDiv).find('li.' + settings.issuesSelectedClass).index();
				if ('horizontal' == settings.orientation) {
					var currentPositionIssues = parseInt($(settings.issuesDiv).css('marginLeft').substring(0, $(settings.issuesDiv).css('marginLeft').indexOf('px') ) );
					var currentIssueIndex = currentPositionIssues / widthIssue;
					var currentPositionDates = parseInt($(settings.datesDiv).css('marginLeft').substring(0, $(settings.datesDiv).css('marginLeft').indexOf('px') ) );
					var currentIssueDate = currentPositionDates - widthDate;
					if (currentPositionIssues <= -(widthIssue * howManyIssues - (widthIssue) ) ) {
						$(settings.issuesDiv).stop();
						$(settings.datesDiv + ' li:last-child a').click();
					} else {
						if (! $(settings.issuesDiv).is(':animated') ) {

							// bugixed from 0.9.52: now the dates gets centered when there's too much dates.
							$(settings.datesDiv + ' li').eq(currentIndex + 1).find('a').trigger('click');
						}
					}
				} else if ('vertical' == settings.orientation) {
					var currentPositionIssues = parseInt($(settings.issuesDiv).css('marginTop').substring(0, $(settings.issuesDiv).css('marginTop').indexOf('px') ) );
					var currentIssueIndex = currentPositionIssues / heightIssue;
					var currentPositionDates = parseInt($(settings.datesDiv).css('marginTop').substring(0, $(settings.datesDiv).css('marginTop').indexOf('px') ) );
					var currentIssueDate = currentPositionDates - heightDate;
					if (currentPositionIssues <= -(heightIssue * howManyIssues - (heightIssue) ) ) {
						$(settings.issuesDiv).stop();
						$(settings.datesDiv + ' li:last-child a').click();
					} else {
						if (! $(settings.issuesDiv).is(':animated') ) {

							// bugixed from 0.9.54: now the dates gets centered when there's too much dates.
							$(settings.datesDiv + ' li').eq(currentIndex + 1).find('a').trigger('click');
						}
					}
				}

				// prev/next buttons now disappears on first/last issue | bugfix from 0.9.51: lower than 1 issue hide the arrows
				if (1 == howManyDates) {
					$(settings.prevButton + ',' + settings.nextButton).fadeOut('fast');
				} else if (2 == howManyDates) {
					if ($(settings.issuesDiv + ' li:first-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.prevButton).fadeOut('fast');
						$(settings.nextButton).fadeIn('fast');
					} else if ($(settings.issuesDiv + ' li:last-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.nextButton).fadeOut('fast');
						$(settings.prevButton).fadeIn('fast');
					}
				} else {
					if ($(settings.issuesDiv + ' li:first-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.prevButton).fadeOut('fast');
					} else if ($(settings.issuesDiv + ' li:last-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.nextButton).fadeOut('fast');
					} else {
						$(settings.nextButton + ',' + settings.prevButton).fadeIn('slow');
					}
				}
			} );

			$(settings.prevButton).click(function(event) {
				event.preventDefault();

				// bugixed from 0.9.54: now the dates gets centered when there's too much dates.
				var currentIndex = $(settings.issuesDiv).find('li.' + settings.issuesSelectedClass).index();
				if ('horizontal' == settings.orientation) {
					var currentPositionIssues = parseInt($(settings.issuesDiv).css('marginLeft').substring(0, $(settings.issuesDiv).css('marginLeft').indexOf('px') ) );
					var currentIssueIndex = currentPositionIssues / widthIssue;
					var currentPositionDates = parseInt($(settings.datesDiv).css('marginLeft').substring(0, $(settings.datesDiv).css('marginLeft').indexOf('px') ) );
					var currentIssueDate = currentPositionDates + widthDate;
					if (0 <= currentPositionIssues) {
						$(settings.issuesDiv).stop();
						$(settings.datesDiv + ' li:first-child a').click();
					} else {
						if (! $(settings.issuesDiv).is(':animated') ) {

							// bugixed from 0.9.54: now the dates gets centered when there's too much dates.
							$(settings.datesDiv + ' li').eq(currentIndex - 1).find('a').trigger('click');
						}
					}
				} else if ('vertical' == settings.orientation) {
					var currentPositionIssues = parseInt($(settings.issuesDiv).css('marginTop').substring(0, $(settings.issuesDiv).css('marginTop').indexOf('px') ) );
					var currentIssueIndex = currentPositionIssues / heightIssue;
					var currentPositionDates = parseInt($(settings.datesDiv).css('marginTop').substring(0, $(settings.datesDiv).css('marginTop').indexOf('px') ) );
					var currentIssueDate = currentPositionDates + heightDate;
					if (0 <= currentPositionIssues) {
						$(settings.issuesDiv).stop();
						$(settings.datesDiv + ' li:first-child a').click();
					} else {
						if (! $(settings.issuesDiv).is(':animated') ) {

							// bugixed from 0.9.54: now the dates gets centered when there's too much dates.
							$(settings.datesDiv + ' li').eq(currentIndex - 1).find('a').trigger('click');
						}
					}
				}

				// prev/next buttons now disappears on first/last issue | bugfix from 0.9.51: lower than 1 issue hide the arrows
				if (1 == howManyDates) {
					$(settings.prevButton + ',' + settings.nextButton).fadeOut('fast');
				} else if (2 == howManyDates) {
					if ($(settings.issuesDiv + ' li:first-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.prevButton).fadeOut('fast');
						$(settings.nextButton).fadeIn('fast');
					} else if ($(settings.issuesDiv + ' li:last-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.nextButton).fadeOut('fast');
						$(settings.prevButton).fadeIn('fast');
					}
				} else {
					if ($(settings.issuesDiv + ' li:first-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.prevButton).fadeOut('fast');
					} else if ($(settings.issuesDiv + ' li:last-child').hasClass(settings.issuesSelectedClass) ) {
						$(settings.nextButton).fadeOut('fast');
					} else {
						$(settings.nextButton + ',' + settings.prevButton).fadeIn('slow');
					}
				}
			} );

			// keyboard navigation, added since 0.9.1
			if ('true' == settings.arrowKeys) {
				if ('horizontal' == settings.orientation) {
					$(document).keydown(function(event) {
						if (39 == event.keyCode) {
							$(settings.nextButton).click();
						}
						if (37 == event.keyCode) {
							$(settings.prevButton).click();
						}
					} );
				} else if ('vertical' == settings.orientation) {
					$(document).keydown(function(event) {
						if (40 == event.keyCode) {
							$(settings.nextButton).click();
						}
						if (38 == event.keyCode) {
							$(settings.prevButton).click();
						}
					} );
				}
			}

			// default position startAt, added since 0.9.3
			$(settings.datesDiv + ' li').eq(settings.startAt - 1).find('a').trigger('click');

			// autoPlay, added since 0.9.4
			if ('true' == settings.autoPlay) {

				// set default timer
				var timer = setInterval(autoPlay, settings.autoPlayPause);

				// pause autoplay on hover
				$(settings.containerDiv).hover(function(ev) {
					clearInterval(timer);
				}, function(ev) {

					// start again timer on mouse out
					timer = setInterval(autoPlay, settings.autoPlayPause);
				} );

			}
		}
	}(jQuery) );
};

// autoPlay, added since 0.9.4
