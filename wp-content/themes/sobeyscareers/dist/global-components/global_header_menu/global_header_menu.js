(function($) {

	var $global_header_menu = $('#global-menu');
	if (! $global_header_menu.length) {
		return; // Return if component isn't on the page
	}

	var close_button_html = '<li class="menu-item gh-close-button"><a tabindex="1" role="button" data-js-header-close-button="button">Close</a></li>';
	var gh_menu_item = 'gh-menu-item';
	var button_item = '[role="button"]';

	$(document).ready(init);

	function init() {

		$global_header_menu.find('li.menu-item').addClass(gh_menu_item);
		$global_header_menu.addClass('count_' + $global_header_menu.find('li.menu-item').length);
		$global_header_menu.append(close_button_html);
		$(button_item).bind('keydown', mimicClick);
	}

	/**
	 * Some `a` have no URL and are navigated over with JS, to ensure proper accessibilty and the user can press enter for all links,
	 * we catch when a link has a key press and is in focus and mimic that same click.
	 */
	function mimicClick(e) {

		// Testing purposes
		if (9 === e.which) {

			//console.log( document.activeElement );
		}

		if ($(this).is(':focus') && (13 === e.which) ) { // enter key
			$(this).click();
		}
	}

}(jQuery) );
