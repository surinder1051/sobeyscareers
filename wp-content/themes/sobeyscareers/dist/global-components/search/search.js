(function($) {

	var $search = $('[data-js-header_search]');

	if (! $search.length) {
		return; // Return if component isn't on the page
	}

	var $site_nav_header = $('#site-nav-header');
	var $component = $('.component_search');
	var $search_srouce = $component.data('search-source');
	var $more = $('[data-js-search-load-more]');

	var AutoFillWords = false;
	var description_length = 250;
	var selected_live_search = 0;
	var gh_close_button = ('.gh-close-button');
	var gh_menu_item = ('li.gh-menu-item');
	var submit_icon = ('.component_search .field .icon');
	var live_result = ('.component_search .live-result');
	var open_class = ('-open');
	var last_search_term = '';
	var searchBoxNext = 0;
	var searchBoxControls; //for modal keyboard tabbing

	var setKeyBoardControls = function() {
		if ($($component).hasClass('-open') ) {
			searchBoxControls = new Array();
			searchBoxControls.push($('.menu-global-container .gh-close-button a') );
			searchBoxControls.push($('#siteSearch', $component) );
			$('.results a', $component).each(function() {
				$(this).attr('tabindex', '0');
				searchBoxControls.push($(this) );
			} );
			if ($('.load-more button', $component).length && $('.load-more button', $component).is(':visible') ) {
				searchBoxControls.push($('.load-more button', $component) );
			}
		}
	};

	var searchBoxBackward = function() {
		var next = searchBoxNext - 1;
		$(searchBoxControls[searchBoxNext] ).focus();
		if (0 == searchBoxNext) {
			next = (searchBoxControls.length - 1);
		}
		searchBoxNext = next;
	};
	var searchBoxForward = function() {
		var next = 1 + searchBoxNext;
		$(searchBoxControls[searchBoxNext] ).focus();
		if (next == searchBoxControls.length) {
			next = 0;
		}
		searchBoxNext = next;
	};

	$(document).ready(init);

	// hide live suggestions when leaving the search box

	$('.component_search .field input').focusout(function() {
		$('.live-search-results').fadeOut();
	} );

	$(window).on('keydown', function(e) {
		if ($($component).hasClass('-open') ) {
			var keyed = (e.which) ? e.which : e.keyCode;
			switch (keyed) {
				case 9:
					e.preventDefault();
					if (e.shiftKey) {
						searchBoxBackward();
					} else {
						searchBoxForward();
					}
					break;
				case 27:

					//on escape, close the modal
					closeModal();
					break;
				default:
					break;
			}
		}
	} );

	function searchInput(e) {

		suggestion_count = $('.live-search-results .live-result:visible').length;
		var keyed = (e.which) ? e.which : e.keyCode;
		if (13 == keyed) {
			if ($component.find('.live-search-results .selected').length) {
				val = $('.live-search-results .selected .title').text();
				$component.find('.field input').val(val);
				submitForm();
			} else {
				submitForm();
			}

		} else if (40 == keyed) {

			if (! suggestion_count) {
				return;
			}

			if (1 > $component.find('.live-search-results .selected').length) {
				selected_live_search = 1;
			} else {
				selected_live_search = parseInt(selected_live_search) + 1;
				if (selected_live_search >= suggestion_count) {
					selected_live_search = suggestion_count;
				}
			}

			$component.find('.live-search-results .selected').removeClass('selected');
			$component.find('.live-search-results .live-result:nth-child(' + selected_live_search + ')').addClass('selected');

		} else if (38 == keyed) {

			if (! suggestion_count) {
				return;
			}

			if (1 > $component.find('.live-search-results .selected').length) {
				selected_live_search = 1;
			} else {
				selected_live_search = parseInt(selected_live_search) - 1;
				if (0 >= selected_live_search) {
					selected_live_search = 1;
				}
			}

			$component.find('.live-search-results .selected').removeClass('selected');
			$component.find('.live-search-results .live-result:nth-child(' + selected_live_search + ')').addClass('selected');

		} else {

			selected_live_search = 0;
			val = $(this).val();
			setupAutoFillWords(val);
			$component.find('.live-search-results .selected').removeClass('selected');
			$component.find('.load-more button').data('paged', 0);
			$component.find('.results').html('');
			$component.find('.pagination, .load-more, .summary').hide();
			$component.find('.results-count .term').text('0');

		}

		return;

	}

	// setup autofill for current value of search

	function setupAutoFillWords(val) {
		var list = [];
		var count = 0;
		for (var i = 0; i < AutoFillWords.length; i++) {
			if (0 == AutoFillWords[i].indexOf(val) ) {
				list.push(AutoFillWords[i] );
				count++;
			}
			if (2 < count) {
				break;
			}
		}

		$('.live-search-results').hide();
		if (list.length && val) {
			$('.live-search-results .live-result').hide();
			for (var i = 0; i < list.length; i++) {
				$('.live-search-results .live-result:nth-child(' + (i + 1) + ') .title').text(list[i] ).parent().show();
			}
			$('.live-search-results').show();
		}
	}

	// pull live search list

	function pullSearchAutofillWords() {
		if (AutoFillWords) {
			return;
		}
		$.ajax( {
			url: endpoint_search,
			data: {
				autofill_list: true,
				search_source: $search_srouce
			},
			type: 'GET'
		} ).done(function(data) {
			AutoFillWords = data.sort();
		} );
	}

	// click a live suggestion

	function searchLiveResult() {
		val = $(this).find('.title').text();
		$('.component_search .field input').val(val);
		$('.live-search-results').hide();
		submitForm();
	}

	// load more results

	function loadMore() {
		$component.find('.load-more button').text('Loading...').attr('disabled', 'disabled');
		submitForm();
	}

	function quitModalKey(e) {
		var keyed = (e.which) ? e.which : e.keyCode;
		if (27 === keyed) //esc
		{
			closeModal();
		}
	}

	// submit search function

	function submitForm() {

		if (jQuery('.component_search').hasClass('searching') ) {
			return;
		}

		jQuery('.component_search').addClass('searching');

		var search_term = $('.component_search .field input').val();
		if (0 === search_term.length) {
			return;
		}

		if (last_search_term !== search_term) {
			clear_results();
		}

		last_search_term = search_term;


		$('.component_search .field input').prop('disabled', true);
		$('.component_search .field input').val('Searching for "' + search_term + '" ...');

		if ($('body').hasClass('admin-bar') ) {
			$('html').animate( {
				scrollTop: 0
			}, 30);
		}


		if (! validate(search_term) ) {
			return;
		}

		if (undefined == $component.find('.load-more button').data('paged') ) {
			paged = 0;
		} else {
			paged = $component.find('.load-more button').data('paged');
		}

		$.ajax( {
			url: endpoint_search,
			data: {
				s: search_term,
				search_source: $search_srouce,
				paged: paged
			},
			type: 'GET'
		} ).done(function(data) {

			// $component.find('.results').html('');
			$component.find('.summary').show();
			if (1 > data.results) {
				no_results();
			} else {
				parse_results(data);
			}
			$('.component_search .field input').prop('disabled', false).val(search_term);
			setKeyBoardControls(); //reset the tabbing when new resulst are loaded
			searchBoxNext = 2; //set to the start of the results section
			jQuery('.component_search').removeClass('searching');

			return;
		} );
	}

	// when results are found

	function parse_results(data) {

		$component.find('.results-count .term').text(data.found_posts);
		$component.find('.summary').show();

		start = 1 + (data.max_results * (data.paged - 1) );
		scroll_height = 0;
		finish = start + data.max_results - 1;

		if (finish >= data.found_posts) {
			finish = data.found_posts;
			$component.find('.load-more').hide().find('button').data('paged', 0);
			pagination_text = 'Results 1-' + finish;
		} else {
			$component.find('.load-more button').text('Load more').attr('disabled', false);
			$component.find('.pagination, .load-more').show().find('button').data('paged', data.paged);
			pagination_text = 'Results 1-' + finish + ' of ' + data.found_posts;
		}

		$component.find('.pagination h6').text(pagination_text);
		$component.find('h5').text('Search Results: ' + data.search_term);

		for (var i = 0; i < data.results.length; i++) {

			link = data.results[i].link;
			title = data.results[i].title;
			tag = null;
			description = null;
			if (data.results[i].tag) {
				tag = data.results[i].tag;
			}
			if (data.results[i].description) {
				description = data.results[i].description;
				if (description.length > description_length) {
					description = description.substring(1, description_length) + ' ... ';
				}
			}

			if (title) {
				if (! i) {
					result_class = 'scroll_marker';
				} else {
					result_class = '';
				}
				html = '<div class="result ' + result_class + '">';
				if (tag) {
					html += '<div class="tag">' + tag + '</div>';
				}
				html += '<a target="_blank" title="' + title + '" href="' + link + '">' + title + '</a>';
				if (description) {
					html += '<p class="description">' + description + '</p>';
				}
				html += '</div>';
				$component.find('.results').append(html);
				scroll_height += $component.find('.results .result:last-child').height();
			}
		}

		if (1 == data.paged) {
			scrollPoint = $('.component_search .search-field').height();
			$('.component_search').animate( {
				scrollTop: scrollPoint + 80
			}, 500);
		} else {
			scrollPoint = $('.component_search').scrollTop() + scroll_height;
			$('.component_search').animate( {
				scrollTop: scrollPoint + 135
			}, 500);
		}

	}

	// when no results are found

	function no_results() {
		var search_term = $('.component_search .field input').val();
		$component.find('h5').text('No results for: ' + search_term);
		clear_results();
	}

	// reset search to default

	function clear_results() {
		$component.find('h5').html('Search Results:');
		$component.find('.results').html('');
		$component.find('.results-count .term').text('0');

		//$component.find('.pagination, .load-more').hide();
		$component.find('.pagination').hide();
		$component.find('.field input').val('');
	}

	// validate search term

	function validate(search_term) {
		if (search_term.length) {
			return true;
		}
	}

	// init

	function init() {
		bindEvents();
	}

	// bind events

	function bindEvents() {
		$search.on('click', openModal);
		$more.on('click', loadMore);
		$(document).on('click', gh_close_button, closeModal);
		$(document).on('click', submit_icon, submitForm);
		$(document).on('click', live_result, searchLiveResult);
		$(document).on('modal-close', closeModal);
	}

	// open search modal

	function openModal(e) {
		$(document).trigger('menu-close');
		$(document).bind('keyup', quitModalKey);
		pullSearchAutofillWords();
		$('body').addClass('no_scroll');
		e.preventDefault();
		$component.show();
		$component.addClass(open_class);
		$component.find('.summary').hide();
		$(gh_menu_item).hide();
		$(gh_close_button).show();

		$('html, .component_search').animate( {
			scrollTop: 0
		}, 0);
		$('.component_search .search-field input[type="text"]').focus();

		// events for the search input that run on every key stroke
		$('.component_search .field input').bind('keyup', searchInput);

		//here we have to load the keyboard tab elements
		setKeyBoardControls();
		searchBoxNext = 0; //set the initial tab element to 0
	}

	// close search modal

	function closeModal() {
		$(document).unbind('keyup', quitModalKey);
		clear_results();
		$('body').removeClass('no_scroll');
		$component.removeClass(open_class);
		$component.hide();
		$(gh_menu_item).show();
		$(gh_close_button).hide();
		$('.component_search .field input').unbind('keyup', searchInput);
	}

	/**
	 * Globalize the open/close menu function.
	 */
	window.opg_closeHSMenu = function() {
		closeModal();
	};

}(jQuery) );
