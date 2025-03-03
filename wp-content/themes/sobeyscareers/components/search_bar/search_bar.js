jQuery(document).ready(function($) {
	jQuery('[data-js-mob_search_toggle]').click(function(e) {
		jQuery(this).parents('.component_search_bar').find('.search-col').slideToggle();
	} );

	jQuery('form#search').on('submit', function(e) {

		var $facted_search_keyword_param = jQuery(e.currentTarget).find('[name="fwp_keyword_search"]');
		if ('' == $facted_search_keyword_param.val() ) {
			e.preventDefault();
			$facted_search_keyword_param.val(jQuery(e.currentTarget).find('[name="s"]').val() );
			jQuery(e.currentTarget).submit();
		}

		jQuery(e.currentTarget).find('[name="s"]').val($facted_search_keyword_param.val() );

	} );

} );
