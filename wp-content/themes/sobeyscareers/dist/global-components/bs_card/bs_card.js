(function($) {

  var $bs_card = $('[data-js-bs_card]');
  if (! $bs_card.length) {
	return; // Return if component isn't on the page
  }

/**  Bs Card expand text **/
jQuery('.expand_text').on('click', function() {
	var elem = jQuery(this).closest('.card-text');
	elem.find('.hidden_text').show();
	elem.find('.collapse_text').show();
	elem.find('.eclipse_more').hide();
	jQuery(this).hide();
	equalheight('.card-text-equal-height .card-text');
} );

jQuery('.collapse_text').on('click', function() {
	var elem = jQuery(this).closest('.card-text');
	elem.find('.hidden_text').hide();
	elem.find('.expand_text').show();
	elem.find('.eclipse_more').show();
	jQuery(this).hide();
	equalheight('.card-text-equal-height .card-text');
} );
}(jQuery) );
