(function($) {

	var columns = 1;

	FLBuilder._registerModuleHelper('table_rows_editor', {
		rules: {
			'row_columns[]': {
				maxcount: columns
			}
		},
		init: function() {
			monitor_repeater_length();
			restrict_editor_height();
		}
	} );

	FLBuilder._registerModuleHelper('table_columns_editor', {
		init: function() {
			restrict_editor_height();
		}
	} );

	FLBuilder._registerModuleHelper('table_row_editor', {
		init: function() {
			restrict_editor_height();
		}
	} );


	FLBuilder._registerModuleHelper('table', {
		init: function() {
			$('[data-type="table_rows_editor"]').click(function() {
				columns = $('[name="table_columns[]"]').length;

				// update table_rows_editor maxcount based on number of columns
				var module = FLBuilder._moduleHelpers['table_rows_editor'];
				for (var field in module.rules) {
						var rule = module.rules[field];
						if (0 < rule.maxcount) {
							rule.maxcount = columns;
						}
				}
			} );
		}
	} );

} (jQuery) );
