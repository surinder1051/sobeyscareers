(function($) {

	FLBuilder._registerModuleHelper('form', {
		rules: {
			ninja_form: {
				required: true
			}
		},
		init: function() {
			jQuery(document).ready(function() {
				jQuery('[name="ninja_form"]').change(function() {

					// loadForm(jQuery(this).val());
					jQuery('.fl-builder-settings-save').trigger('click');
					location.reload();
				} );
			} );
		}

	} );

} (jQuery) );
