(function($) {

	FLBuilder._registerModuleHelper('full_width_photo', {
		rules: {
			image: {
				required: true
			},
			intro_content: {
				maxlengthhtml: 60
			}
		},
		init: function() {
			restrict_editor_height();
		}
	} );

	$(document).ready(function($) {
		if (wp.media) {
			wp.media.view.Modal.prototype.on('open', function() {
				var selection = wp.media.frame.state().get('selection');
				selection.on('selection:single', function(event) {
					localStorage.setItem('last_photo_id', $('input[name="image"]').val() );
					localStorage.setItem('temp_photo_caption', $('[data-setting="caption"] textarea').text() );
				} );
				jQuery('body').on('click', '.media-toolbar-primary', function() {
					$('#fl-field-caption input[name="caption"]').val(localStorage.getItem('temp_photo_caption') );
				} );
			} );
		}
	} );

}(jQuery) );
