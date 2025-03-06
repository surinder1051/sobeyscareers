// Name: 			Color Preview hook for BB modules
// Author: 			Flowpress (MD)
// Compaibility: 	Works with drowdown fields in your beaver builder module
// Description:		Allows you to preview #hexcolors when using select field types
//
// setup
//
// configure your BB field in {component_name}
//
//		{component_name} => array(
//		 	'type'        => 'select',
//		 	'label'       => __( 'Color', 'fl-builder' ),
//		 	'options'     => array(
//		 		'#00bfff'      => __( 'Pre-defined Color #1', 'fl-builder' ),
// 				'#004400'      => __( 'Pre-defined Color #2', 'fl-builder' ),
// 			),
// 		),
//
// add your module's backend javascript by creating a settings.js file under {component}/js/
//
// setup your component helper in settings.js
//
// FLBuilder._registerModuleHelper('{component_name}', {
// 	init: function() {
// 		color_fields = [
// 			'{component_field_name}',
// 			'{component_field_name}',
// 		];
// 		fp_bb_js_colors_selection( color_fields );
// 	},
// });


// call set_color function to process initial uploads
// add change events listeners for each field

function fp_bb_js_colors_selection(color_fields) {
	fp_bb_js_colors_selection_set_color(color_fields);
	for (var i = 0; i < color_fields.length; i++) {
		element = $('[name="' + color_fields[i] + '"]');
		if (element.length) {
			$('[name="' + color_fields[i] + '"]').change(function() {
				id = $(this).attr('name');
				fp_bb_js_colors_selection_create_preview(id);
			} );
		}
	}
}

// filter out non existent fields

function fp_bb_js_colors_selection_set_color(name_array) {
	for (var i = 0; i < name_array.length; i++) {
		element = $('[name="' + name_array[i] + '"]');
		if (element.length) {
			fp_bb_js_colors_selection_create_preview(name_array[i] );
		}
	}
}

// setup preview boxes for each field.

function fp_bb_js_colors_selection_create_preview(id) {
	color = $('[name="' + id + '"]').val();
	element = $('[name="' + id + '"]');
	if (! $(element).siblings('.preview').length) {
		preview = '<i class="preview" style="background:' + color + '"></i>';
		$(element).before(preview);
	} else {
		$(element).siblings('.preview').css('background', color);
	}
}
