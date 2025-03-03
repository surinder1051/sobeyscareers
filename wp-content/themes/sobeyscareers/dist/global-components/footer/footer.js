(function($) {

	var $footer = $('[data-js-footer]');
	var $width_setting = 'fixed-width';
	var $content_height = $('.site').innerHeight();
	var $footer_height = $('.component_footer').outerHeight();
	var $combined_footer_content_height = parseInt($content_height) + parseInt($footer_height);
	var $document_height = parseInt($('body').outerHeight() );
	var $margin_bottom = ($(window).height() - $('.site').height() - $footer_height);
	var $row_content_container = $footer.closest('.fl-row-content');
	var $html_margin = parseInt($('html').css('margin-top') );
	var $additional_margin_to_subtract = 0;

	/* Some components are not taken into account, such as the notice component as it's outside of the .site element
	 */
	if (0 < $('body > .fl-module-notice').find('.-important-notice').length) {
		$additional_margin_to_subtract = parseInt($('body').find('.-important-notice').css('height') );
	}

	if (! $footer.length) {
		return; // Return if component isn't on the page
	}

	$('html, body').css('height', 'calc(100% - 50px)');

	if ($row_content_container.hasClass('fl-row-full-width') ) {
		$width_setting = 'full-width';
	} else {
		$width_setting = 'fixed-width';
	}

	if ('undefined' === typeof (FLBuilder) ) {

		/*
			This is executed on the page load. The footer is removed from the current Beaver Builder Row container and moved to the body tag. We're adding the class to the footer so we can either have it set to full width or fixed width. Next we're deleting the empty container after the footer is moved, and the margin bottom is calculated then applied to the site container.
		*/
		$('body').append($('.component_footer') );
		$('.component_footer').addClass($width_setting);
		$footer_height = $('.component_footer').innerHeight();
		$row_content_container.closest('.fl-row').remove();

		$margin_bottom = ($(document).height() - $('.site').innerHeight() - $footer_height - $html_margin - $additional_margin_to_subtract);

		if (0 < $margin_bottom) {
			$('.site').css('margin-bottom', $margin_bottom);
		}
	}

	/*
		When the window resizes, we're recalculating the window height. The html tag has a margin top added when we're logged in as admin, so when the html tag has a margin top, it's taken into account. We're calculating the height of the window here in the document height because it's not the same thing as the document height that I am calculating upon refresh.
	*/
	$(window).on('resize', function() {
		$content_height = $('.site').outerHeight();
		$footer_height = $('.component_footer').outerHeight();
		$combined_footer_content_height = parseInt($content_height) + parseInt($footer_height);
		$document_height = parseInt($(window).outerHeight() );
		$margin_bottom = ($document_height - $('.site').innerHeight() - $footer_height - $html_margin - $additional_margin_to_subtract);

		if (0 < $margin_bottom) {
			$('.site').css('margin-bottom', $margin_bottom);
		}
	} );


}(jQuery) );
