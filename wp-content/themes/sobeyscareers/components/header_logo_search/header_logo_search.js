var winWidth = jQuery(window).width();

jQuery('[data-js-mob_search_toggle]').on('click', function() {
	var component = jQuery(this).parents('.component_header_logo_search');
	jQuery(this).parents('.component_header_logo_search').find('.search-col').slideToggle(function() {

		//force focus on the search box if keyboard is in use
		if (jQuery('.focus-visible').length) {
			jQuery('#headerSearch_s', component).focus();
		}
	} );
} );

jQuery('form#search').on('submit', function(e) {
	jQuery(this).removeClass('invalid');
	var search_value = jQuery(e.currentTarget).find('[name="s"]').val();
	if ('' === search_value) {
		e.preventDefault();
		jQuery(this).addClass('invalid');
	} else {
		var $facted_search_keyword_param = jQuery(e.currentTarget).find('[name="fwp_keyword_search"]');
		if ('' === $facted_search_keyword_param.val() ) {
			e.preventDefault();
			$facted_search_keyword_param.val(search_value);
			jQuery(e.currentTarget).trigger('submit');
		}
	}
} );

jQuery('.component_header_logo_search .search-btn.close').on('click', function() {
	var component = jQuery(this).closest('.component_header_logo_search');
	jQuery(this).closest('.component_header_logo_search').find('.search-col').slideToggle(function() {
		if (jQuery('.focus-visible').length) {

			//force focus back on the search toggle if keyboard in use
			jQuery('[data-js-mob_search_toggle]', component).focus();
		}
	} );
} );

jQuery(window).on('resize', function() {
	if (winWidth != jQuery(window).width() ) {
		winWidth = jQuery(window).width();

		//remove display hidden if width resizing from small to large screens and the search toggle was used
		jQuery('.component_header_logo_search').each(function() {
			jQuery('.search-col', this).css( {
				display: ''
			} );
		} );
	}
} );

jQuery('#headerSearch_s').on('focus', function() {
	jQuery(this).siblings('.search-label').addClass('search-active');
} ).on('blur', function() {
	if (! jQuery(this).val() ) {
		jQuery(this).siblings('.search-label').removeClass('search-active');
		jQuery('form#search').removeClass('invalid');
	}
} );

jQuery(document).ready(function() {
	if (jQuery('#headerSearch_s').val() ) {
		jQuery('#headerSearch_s').siblings('.search-label').addClass('search-active');
	}
} );
