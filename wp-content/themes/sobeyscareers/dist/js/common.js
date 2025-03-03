jQuery(document).ready(function() {
	jQuery('img').each(function() {
		var img_src = jQuery(this).attr('src');
		if (typeof img_src !== typeof undefined && false !== img_src) {
			var img_alt = jQuery(this).attr('alt');
			var str = img_src;
			var pieces = str.split('/');
			var imgName = pieces[pieces.length - 1];
			var imgnameArray = imgName.split('.');
			var alt = imgnameArray[0];
			if ('' == img_alt || typeof img_alt === typeof undefined || false === img_alt) {
				jQuery(this).attr('alt', alt);
			}
		}

		//add select dropdown styling for formidable forms

		if (jQuery('.frm_form_field').length) {
			jQuery('.frm_form_field').each(function() {
				if (jQuery('select', this).length && ! jQuery(this).hasClass('frm-select-wrapper') ) {
					jQuery(this).append('<span class="icon-dropdown"></span>').addClass('frm-select-wrapper');
				}
			} );
		}
	} );

	//Reason: Script for custom slider in economic choice page
	if (0 < jQuery('.economic-choice-col-group').find('.economic-choice-slider').length) {
		slickSliderCustomSlides('.economic-choice-slider', 3);
	}

	if (0 < jQuery('.economic-choice-col-group').find('.economic-choice-tricks-slider').length) {
		slickSliderCustomSlides('.economic-choice-tricks-slider', 4);
	}

	/** add active class to filter button on search page **/
	jQuery('.filter_button').on('click', function() {
		jQuery('.mobile_category_menu').addClass('active');
		jQuery('body').addClass('overflow-hidden');
	} );

	/** remove active class to filter button on search page **/
	jQuery('.close_category_menu').on('click', function() {
		jQuery('.mobile_category_menu').removeClass('active');
		jQuery('body').removeClass('overflow-hidden');
	} );

	/** image src to background image for responsiveness fixes **/
	jQuery('.image-object-fit').each(function() {
		var imgSrc = jQuery(this).find('img').attr('src');
		jQuery(this).css('background-image', 'url(' + imgSrc + ')');
	} );

	jQuery('.replace_iframe_on_click').click(function(e) {
		e.preventDefault();
		var data_link = jQuery(this).attr('data-target');
		replace_iframe_on_click(e.target, data_link);
	} );

	function replace_iframe_on_click(ele, data_link) {
		video_frame = '<iframe class="app_vid_iframe" src="' + data_link + '" width="100%" height="304" frameBorder="0" allow="autoplay" ></iframe>';
		jQuery(ele).replaceWith(video_frame);
	}

	function check_if_in_view() {
		var window_height = jQuery(window).height();
		var window_top_position = jQuery(window).scrollTop();
		var window_bottom_position = (window_top_position + window_height / 1.5);

		jQuery.each(jQuery('.animation-element'), function() {
			var $element = jQuery(this);
			var element_height = $element.outerHeight();
			var element_top_position = $element.offset().top;
			var element_bottom_position = (element_top_position + element_height / 1.5);

			//check to see if this current container is within viewport
			if ( (element_bottom_position >= window_top_position) &&
				(element_top_position <= window_bottom_position) ) {
				$element.addClass('in-view');
				$element.removeClass('out-view');
			} else {
				$element.removeClass('in-view');
				$element.addClass('out-view');
			}
		} );
	}
	jQuery(window).on('scroll resize load', check_if_in_view);

	/**  Location page drop down **/
	jQuery('.select_options').on('change', function() {
		var selectVal = jQuery('select option:selected').attr('data-url');
		jQuery('.select_options_link').attr('href', selectVal);
	} );

	/****/
	mobileSlider();

} );


jQuery(window).on('load', function() {
	if (jQuery('body').hasClass('logged-in') ) {
		var dummyColour = 'd8d8d8';
		jQuery('img').each(function() {
			if ( ('undefined' != typeof this.naturalWidth && 0 == this.naturalWidth) || 'uninitialized' == this.readyState) {
				jQuery(this).attr('src', 'https://place-hold.it/' + jQuery(this).attr('width') + 'x' + jQuery(this).attr('height') + '/' + dummyColour + '/ffffff.jpg&text=img');
			}
			dummyColour = ('d8d8d8' == dummyColour) ? 'e8e8e8' : 'd8d8d8';
		} );
	}
} );


jQuery(window).on('load resize', function() {

	equalHeightPerRow('.card-body-equal-height', '.card-body');
	equalHeightPerRow('.history_main_wrapper', '.equal_height_history');
	equalHeightPerRow('.title_equal_height', '.fl-post-title');
	equalHeightPerRow('.card-text-equal-height', '.card-text');
	equalHeightPerRow('.bottom-col-wrap-equal-height', '.bottom-col-wrap');
	equalHeightPerRow('.equal-height-title', '.item-location_title');
	equalHeightPerRow('.equal-height-title', '.item-location_summary h4');
	equalHeightPerRow('.gift_card_main', '.gift_card_eq_height');
	equalHeightPerRow('.card-title-equal-height', '.card-title');
	equalHeightPerRow('.card-deck', '.card-title');
	equalHeightPerRow('.video-section', '.text-sec h3');
	equalHeightPerRow('.air_miles_collector', '.air_miles_tiles .fl-heading');
	equalHeightPerRow('.detail_tile_row', '.component_recipe_details  .tile_makes');
	equalheight('.mobile_carousel .card-title');
	equalheight('.mobile_carousel .card-text');
	equalheight('.mobile_carousel .bottom-col-wrap');
	equalheight('.fl-col-group-equal-height .two_col_list h4');
	equalheight('.article_singlur_count .fl-photo-img-svg img');
	equalheight('.article_singlur_count .fl-heading');
	equalheight('.clients_info_section .slick-slide .desp_style_sec h2');
	equalheight('.clients_info_section .slick-slide .desp_style_sec');
	equalheight('.clients_info_section .slick-slide .desp_style_sec p');
	equalHeightPerRow('.clients_info_section', '.image_info');
	equalheight('.single_article_count .singlur_count .article_count');
	equalHeightPerRow('#list-stores-wrap', '.equal_height');
	equalheight('#list-stores-wrap .equal_height > div:first-child .store-title');
	equalheight('#list-stores-wrap .equal_height > div:first-child .location_address');
	equalheight('.summer-infuenceurs .card');
	equalheight('.summer-infuenceurs .card-title');
	equalheight('.summer-infuenceurs .card-description');
	equalheight('.mobileColumnSlider .community_col_text');
	equalheight('.facetwp-template .component_bs_card .card-body');
	equalheight('.economic-choice-col-group .economic-choice-txt-col');
	equalheight('.economic-choice-col-group .economic-choice-txt-col h2');
	equalheight('.economic-choice-col-group .economic-choice-txt-col .text-content');
	equalheight('.offers_rows .card-image-wrap img');
	equalheight('.gc-col .gc-text');
	equalheight('.gc-idea .card-body');
	equalheight('.gc-program-col .gc-program-text');
	equalheight('.cookHealthSection .card .card-body .card-title');
	equalheight('.offers_rows h3');
	if (0 < jQuery('.mobile_carousel').length) {
		mobileSlider();
	}
} );
function equalHeightPerRow(parent_element, child_element) {
	var $list = jQuery(parent_element),
		$items = $list.find(child_element),
		setHeights = function() {
			$items.css('height', 'auto');

			var perRow = Math.floor($list.width() / $items.width() );
			if (null == perRow || 2 > perRow) {
				return true;
			}

			for (var i = 0, j = $items.length; i < j; i += perRow) {
				var maxHeight = 0,
					$row = $items.slice(i, i + perRow);

				$row.each(function() {
					var itemHeight = parseInt(jQuery(this).outerHeight() );
					if (itemHeight > maxHeight) {
						maxHeight = itemHeight;
					}
				} );
				$row.css('height', maxHeight);
			}
		};

	setHeights();
	jQuery(window).on('resize', setHeights);
}

jQuery(document).on('keydown', function(e) {
	checkKeys = [ 9, 37, 38, 39, 40, 16 ];
	keyPressed = (e.which) ? e.which : e.keyCode;

	//if (checkKeys.includes(keyPressed) ) {
	if (-1 < checkKeys.indexOf(keyPressed) ) {
		jQuery('li.nav-item, a, textarea, input, button, select').addClass('is-tabbing');
	} else {
		jQuery('li.nav-item, a, textarea, input, button, select').removeClass('is-tabbing');
	}
} ).on('click', function(e) {
	jQuery('li.nav-item, a, textarea, input, button, select').removeClass('is-tabbing');
} );

function singleRecipePrint() {
	window.print();
}

var slickSliderActive = false;

function mobileSlider() {
	if (jQuery(window).width() < 768 - getMobileSlider() ) {
		if (false == slickSliderActive) {
			jQuery('.mobile_carousel .fl-row-content > .fl-col-group').slick( {
				dots: true,
				arrows: true,
				slidesToShow: 1,
				slidesToScroll: 1
			} );
			jQuery('.mobile_carousel > .fl-col-content > .fl-col-group').slick( {
				dots: true,
				arrows: true,
				slidesToShow: 1,
				slidesToScroll: 1
			} );
			jQuery('.mobile_carousel .card-deck').slick( {
				dots: true,
				arrows: true,
				slidesToShow: 1,
				slidesToScroll: 1
			} );
			jQuery('.mobileColumnSlider > .fl-row-content-wrap > .fl-row-content').slick( {
				dots: true,
				arrows: true,
				slidesToShow: 1,
				slidesToScroll: 1
			} );

			slickSliderActive = true;
		}
	} else {
		if (slickSliderActive) {
			jQuery('.mobile_carousel .card-deck').slick('unslick');
			jQuery('.mobile_carousel .fl-row-content > .fl-col-group').slick('unslick');
			jQuery('.mobile_carousel > .fl-col-content > .fl-col-group').slick('unslick');
			jQuery('.mobileColumnSlider > .fl-row-content-wrap > .fl-row-content').slick('unslick');
			slickSliderActive = false;
		}
	}
};

function getMobileSlider() {
	var div = document.createElement('div');
	div.style.overflowY = 'scroll';
	div.style.width = '50px';
	div.style.height = '50px';
	div.style.visibility = 'hidden';
	document.body.appendChild(div);
	var scrollWidth = div.offsetWidth - div.clientWidth;
	document.body.removeChild(div);
	return scrollWidth;
}

function equalheight(container) {
	var currentTallest = 0,
		currentRowStart = 0,
		rowDivs = new Array(),
		$el,
		topPosition = 0;
	jQuery(container).each(function() {

		$el = jQuery(this);
		jQuery($el).height('auto');
		topPostion = $el.position().top;

		if (currentRowStart != topPostion) {
			for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}

			rowDivs.length = 0; // empty the array
			currentRowStart = topPostion;
			currentTallest = $el.height();
			rowDivs.push($el);
		} else {
			rowDivs.push($el);
			currentTallest = (currentTallest < $el.height() ) ? ($el.height() ) : (currentTallest);
		}
		for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
			rowDivs[currentDiv].height(currentTallest);
		}
	} );
};

(function($) {
	$(document).on('facetwp-refresh', function() {
		if (FWP.loaded && -1 == window.location.href.indexOf('fl_builder') ) { // after the initial pageload
			FWP.parseFacets(); // load the values
			FWP.setHash(); // set the new URL
			location.reload();
			return false;
		}
	} );
}(jQuery) );

// Add support to hide the facet module if there is no facets within
(function($) {
	$(document).on('facetwp-loaded', function() {
		$.each(FWP.settings.num_choices, function(key, val) {
			var $parent = $('.facetwp-facet-' + key).closest('.fl-module');
			(0 === val) ? $parent.hide() : $parent.show();
		} );

		//add aria labels and tabbing index (through href) to pager links
		if ($('.facetwp-pager').length) {
			$('.facetwp-pager').attr('role', 'navigation');
			$('.facetwp-pager a').each(function() {

				var url = window.location.href.split('?')[0];
				var param = '?fwp_paged=' + $(this).attr('data-page');

				$(this).on('click', function(e) {
					e.stopPropagation();
				} );

				$(this).attr('href', url + param).attr('role', 'button').attr('aria-label', 'Go to page ' + $(this).attr('data-page') );
			} );
		}

	} );
}(jQuery) );

function slickSliderCustomSlides(element, slides) {
	jQuery(element).slick( {
		slidesToShow: slides,
		slidesToScroll: slides,
		responsive: [
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: true
				}
			}
		]
	} );
}
