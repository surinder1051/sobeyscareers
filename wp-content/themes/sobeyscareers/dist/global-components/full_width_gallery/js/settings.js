(function($) {

	FLBuilder._registerModuleHelper('gallery_item', {
		rules: {
			preview_field: {
				required: true
			}
		},
		init: function() {

			bb_ajax_select_field_posts();
			bb_rest_video_upload();
			monitor_preview_field();
			check_form_validation();

			$('body').on('click', '.media-toolbar-primary', function() {
				check_form_validation();
			} );

			$('#fl-field-type select, #fl-field-youtube_post_id select').change(function() {
				check_form_validation();
			} );

		}
	} );

	FLBuilder._registerModuleHelper('full_width_gallery', {
		rules: {
			'gallery_items[]': {
				mincount: 3,
				maxcount: 6,
				required_json: {
					preview_field: true
				}
			},
			intro_content: {
				maxlengthhtml: 60
			}
		},
		init: function() {
			monitor_repeater_length();
			restrict_editor_height();
			bb_render_visual_preview('preview_field', 'type');
			$('body').on('click', '.fl-builder-field-add', function(e) {
				e.preventDefault();
				bb_render_visual_preview('preview_field', 'type');
			} );
		}
	} );

}(jQuery) );

function check_form_validation() {

	$('.fl-builder-settings-save').hide();

	image_id = $('#fl-field-image input').val();
	video_id = $('#fl-field-youtube_post_id select').val();
	type = $('#fl-field-type select').val();

	if (image_id.indexOf('dummyimage') ) {
		$('.fl-builder-settings-save').show();
	}

	if ( ('image' == type) && (0 < parseInt(image_id) ) ) {
		$('.fl-builder-settings-save').show();
	}

	if ( ('video' == type) && (0 < parseInt(video_id) ) ) {
		$('.fl-builder-settings-save').show();
	}

}

function bb_render_visual_preview(selector, type) {

	$('.fl-builder-field-multiple input').change(function() {
		fetch_field_data(this, selector, type);
	} );

	$('.fl-builder-field-multiple input').each(function() {
		fetch_field_data(this, selector, type);
	} );

}

function fetch_field_data(current_object, selector, type) {

	$(current_object).siblings('.fl-form-field-preview-text').html('');

	val = $(current_object).val();
	object = jQuery.parseJSON(val);

	if (! object || ! val) {
		return;
	}

	lookup_id = object[selector];
	lookup_type = object[type];


	var id = 'rendering_' + Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);
	$(current_object).siblings('.fl-form-field-preview-text').addClass(id);

	if (! lookup_id) {
		return;
	}

	if ('image' == lookup_type) {

		if (0 < lookup_id.indexOf('dummyimage') ) {
			add_preview_image(lookup_id, '', id);
			return;
		}

		$.ajax( {
			url: wpApiSettings.root + 'wp/v2/media/' + lookup_id,
			method: 'GET'
		} ).done(function(response) {
			add_preview_image(response.source_url, '', id);
		} );

	} else if ('video' == lookup_type) {

		$.ajax( {
			url: wpApiSettings.root + 'wp/v2/video/' + lookup_id + '?embed',
			method: 'GET'
		} ).done(function(response) {
			if (response._links['wp:featuredmedia'] ) {
				lookup = response._links['wp:featuredmedia'][0].href;
				if (lookup) {
					$.getJSON(lookup, function(data) {
						add_preview_image(data.source_url, 'video', id);
					} );
				}
			}
		} );

	}

}

function add_preview_image(image_source, type_class, id) {

	if (! image_source) {
		return;
	}

	html = '<b class="overlay"></b><img src="' + image_source + '">';

	$('.fl-form-field-preview-text.' + id).html(html).removeClass(id).removeClass('video').addClass(type_class);

}

function monitor_preview_field() {

	$('#fl-field-preview_field').hide();

	jQuery('body').on('click', '.media-toolbar-primary', function() {
		val = $('[name="image"]').val();
		$('[name="preview_field"]').val(val).attr('value', val).trigger('change');;
	} );

	$('[name="youtube_post_id"], [name="image"]').change(function() {
		val = $(this).val();
		if ( ('add' != val) && ('' != val) ) {
			$('[name="preview_field"]').val(val).attr('value', val).trigger('change');
		}
	} );

}
