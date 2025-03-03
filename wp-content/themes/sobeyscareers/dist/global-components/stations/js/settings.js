(function($) {

	FLBuilder._registerModuleHelper('stations', {
		rules: {
			heading: {
				required: true
			},
			map_top_content: {
				maxlengthhtml: 120
			},
			map_bottom_content: {
				maxlengthhtml: 120
			}
		},
		init: function() {
			restrict_editor_height();
			$('#fl-field-station_posts_overwrite input').click(function() {
				$('[name="station_type_overwrite[]"]').val('');
			} );
		}
	} );

} (jQuery) );
