(function($) {

	FLBuilder._registerModuleHelper('sub_nav', {
		rules: {
			nav_title: {
				required: true
			},
			'sub_nav_items[]': {
				mincount: 2,
				required_json: {
					nav_title: true
				}
			}
		},
		init: function() {

			// $(document).trigger('fp_setup_subnav_dropdown')
		}
	} );

	FLBuilder._registerModuleHelper('navigation_option_editor', {
		rules: {
			nav_title: {
				required: true
			}
		},
		init: function() {
			monitor_repeater_length();
			populate_anochor_dropdown();
		}

	} );

	function populate_anochor_dropdown() {

		// Populate select with number of rows on the page for user to choose a row to scroll to
		rows = jQuery('.fl-row').length;
		val = $('[name="navigation_anchor"]').val();
		jQuery('select[name="navigation_anchor"]').html('');

		jQuery('.fl-row').each(function(key, item) {
			key++;
			selected = false;
			if (key == val) {
				selected = ' selected ';
			}
			jQuery('select[name="navigation_anchor"]').append('<option ' + selected + ' value="' + key + '">Scroll to Row #' + key + '</option>');
		} );

		// Scroll to row on page to preview where user will be scrolled to
		jQuery('select[name="navigation_anchor"]').on('change', function(e) {
			jQuery( [ document.documentElement, document.body ] ).animate( {
				scrollTop: jQuery('.fl-row:nth-child(' + jQuery(this).val() + ')').offset().top - 30
			}, 1000);
		} );
	}

}(jQuery) );
