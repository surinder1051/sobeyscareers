(function($) {

	FLBuilder._registerModuleHelper('global_nav', {
		rules: {
		},
		init: function() {
			if (jQuery('#bb_expand_main_menu').length) {
				return;
			}
			jQuery('#fl-field-logo_supporting_text').initialize(function() {
				html = '<tr id="bb_expand_main_menu" data-preview="{"type":"none"}" class="fl-field"><td><button>Open Main Menu</button></td></tr>';
				jQuery('#fl-field-fp_global_main_menu_theme').before(html);
			} );
		}
	} );

}(jQuery) );
