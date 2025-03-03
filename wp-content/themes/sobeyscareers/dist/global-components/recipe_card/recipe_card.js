(function($) {

	var $recipe_card = $('[data-js-recipe_card]');
	if (! $recipe_card.length) {
		return; // Return if component isn't on the page
	}

}(jQuery) );

jQuery(document).ready(function(e) {
	jQuery('.recipe-card a.recipe_card_link').on('focus', function() {
		jQuery(this).parent().addClass('hover');
		jQuery('span.button', this).addClass('hover');
	} ).on('blur', function() {
		jQuery(this).parent().removeClass('hover');
		jQuery('span.button', this).removeClass('hover');
	} );
} );
