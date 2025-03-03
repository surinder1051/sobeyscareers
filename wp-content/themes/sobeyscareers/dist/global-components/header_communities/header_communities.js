(function($) {

	var comp_debug = true;

	var $header_communities = $('[data-js-header_communities]');

	if (! $header_communities.length) {
		return; // Return if component isn't on the page
	}

	var $component_communities = $('.component_header_communities');
	var $region_stars = $('.region-single-star');
	var $region_title = $('.region-title');
	var $region_single = $('.region-single');
	var $region_map = $('.region-map');
	var $region_map_images = $('.region-map img');

	var gh_close_button = ('.gh-close-button');
	var gh_menu_item_class = ('.gh-menu-item');

	var open_class = ('-open');
	var starred_class = ('-starred');
	var communityBoxNext = 0;
	var communityBoxControls; //for modal keyboard tabbing

	$(document).ready(init);

	var setCommunityTabControls = function() {
		if ($($component_communities).hasClass('-open') ) {
			communityBoxControls = new Array();
			communityBoxControls.push($('.menu-global-container .gh-close-button a') );
			$('.region-single a', $component_communities).each(function() {
				$(this).attr('tabindex', '0');
				communityBoxControls.push($(this) );
			} );
		}
	};

	var communityBoxBackward = function() {
		var next = communityBoxNext - 1;
		$('.region-single .bg, .region-single .region-title', $component_communities).removeClass('hover');
		$(communityBoxControls[communityBoxNext] ).focus();
		$('.bg, .region-title', communityBoxControls[communityBoxNext] ).addClass('hover');
		if (0 == communityBoxNext) {
			next = (communityBoxControls.length - 1);
		}
		communityBoxNext = next;
	};
	var communityBoxForward = function() {
		var next = 1 + communityBoxNext;
		$('.region-single .bg, .region-single .region-title', $component_communities).removeClass('hover');
		$(communityBoxControls[communityBoxNext] ).focus();
		$('.bg, .region-title', communityBoxControls[communityBoxNext] ).addClass('hover');
		if (next == communityBoxControls.length) {
			next = 0;
		}
		communityBoxNext = next;
	};

	$(window).on('keydown', function(e) {
		if ($($component_communities).hasClass('-open') ) {
			var keyed = (e.which) ? e.which : e.keyCode;
			switch (keyed) {
				case 9:
					e.preventDefault();
					if (e.shiftKey) {
						communityBoxBackward();
					} else {
						communityBoxForward();
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

	function init() {
		bindEvents();
	}

	function bindEvents() {
		$header_communities.on('click', openModal);
		$region_stars.on('click', toggleFavorite);
		$(document).on('click', gh_close_button, closeModal);
		$region_single.on('mouseenter', highlightRegion);
		$(document).on('modal-close', closeModal);
	}

	function quitModalKey(e) {
		if (27 === e.keyCode) //esc
		{
			closeModal();
		}
	}

	function highlightRegion(e) {
		var map_class_id = $(this).data('map-class');
		if ('undefined' === typeof map_class_id) {
			return;
		}

		$region_map_images.hide();
		$region_map.find('.' + map_class_id).show();
	}

	function openModal(e) {
		$(document).trigger('menu-close');
		$('body').addClass('no_scroll');
		e.preventDefault();
		$component_communities.show();
		$component_communities.addClass(open_class);
		$(gh_menu_item_class).hide();
		$(gh_close_button).show();
		$(document).bind('keyup', quitModalKey);

		//set up the keyboard tabbing
		setCommunityTabControls();
	}

	function closeModal() {
		$('body').removeClass('no_scroll');
		$component_communities.removeClass(open_class);
		$component_communities.hide();
		$(gh_menu_item_class).show();
		$(gh_close_button).hide();
		$(document).unbind('keyup', quitModalKey);
	}

	function toggleFavorite() {
		$(this).toggleClass(starred_class);
	}

	function debug(msg) {
		if (comp_debug) {

			// console.log('header_communities: ' + msg);
		}
	}

}(jQuery) );
