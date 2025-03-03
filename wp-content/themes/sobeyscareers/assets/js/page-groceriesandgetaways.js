// code added for custom accordion
jQuery(document).ready(function() {
	jQuery('.accordion .group a').click(function(e) {
	if (jQuery(this).closest('.accordion').hasClass('active') ) {
	  jQuery(this).closest('.accordion').removeClass('active');
	  jQuery(this).closest('.accordion').find('.body-part').slideUp();
	} else {
	  jQuery('.accordion').removeClass('active');
	  jQuery('.accordion').find('.body-part').slideUp();
	  jQuery(this).closest('.accordion').addClass('active');
	  jQuery(this).closest('.accordion').find('.body-part').slideDown();
	}
  } );
} );
