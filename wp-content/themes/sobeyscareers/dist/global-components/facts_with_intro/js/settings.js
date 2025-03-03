(function($) {

	FLBuilder._registerModuleHelper('fact_field', {
		rules: {
			heading: {
				required: true
			},
			icon: {
				required: true
			},
			text: {
				required: true
			}
		},
		submit: function() {
			return monitor_fact_fields();
		}
	} );

	FLBuilder._registerModuleHelper('facts_with_intro', {
		rules: {
			intro_title: {
				required: {
					depends: function(element) {
						return ('show' == $('.fl-field [name=\'intro\']').val() );
					}
				}
			},
			intro_content: {
				required: {
					depends: function(element) {
						return ('show' == $('.fl-field [name=\'intro\']').val() );
					}
				},
				maxlengthhtml: 125
			},
			'fact_field[]': {
				mincount: 2,
				maxcount: 8,
				required_json: {
					heading: true,
					text: true,
					icon: true
				}
			}
		},
		init: function() {
			monitor_repeater_length();
			restrict_editor_height();
			monitor_view_options();
			$('body').on('click touchend', '.fl-builder-field-add, .fl-builder-field-copy, .fl-builder-field-delete', function() {
				monitor_view_options();
			} );
			$('.wp-editor-tools').hide();
			$('.mce-txt:contains(\'Select Video\')').parent().parent().hide();
		}

	} );

} (jQuery) );

function monitor_view_options() {
	facts = $('[data-field="fact_field"]').length - 1;
	if (4 > facts) {
		$('[name="fact_style"] [value="row3"]').hide();
		$('[name="fact_style"] [value="row4"]').hide();
		$('[name="fact_style"] [value="fixed"]').show();
		$('[name="fact_style"] [value="justified"]').show();
		$('[name="fact_style"]').val('justified');
	} else {
		$('[name="fact_style"] [value="row3"]').show();
		$('[name="fact_style"] [value="row4"]').show();
		$('[name="fact_style"] [value="fixed"]').hide();
		$('[name="fact_style"] [value="justified"]').hide();
		$('[name="fact_style"]').val('row3');
	}
}

function monitor_fact_fields() {

	heading = jQuery('[data-form-id="fact_field"] [name="heading"]').val();
	sub_heading = jQuery('[data-form-id="fact_field"] [name="sub_heading"]').val();
	total_length = heading.length + sub_heading.length;

	if (15 < total_length) {
		alert('The heading and sub heading together can only be 15 character long, please adjust your values.');
	} else {
		return true;
	}

}
