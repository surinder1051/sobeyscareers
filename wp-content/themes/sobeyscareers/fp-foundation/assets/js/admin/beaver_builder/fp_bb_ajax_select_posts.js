// module helper to allow population of posts for post select fields

function bb_ajax_select_field_posts() {

	// console.log('bb_ajax_select_field_posts %o', jQuery('[data-ajax-bb-field-posts]').length);

	if (jQuery('[data-ajax-bb-field-posts]').length < 1) {
		console.log('no data-ajax-bb-field-posts');
		return;
	}

	$ajax_bb_field = jQuery('[data-ajax-bb-field-posts]');

	var queue = [];
	var running = false;
	var stored_data = [];

	jQuery('[data-ajax-bb-field-posts]').each( function () {
		current = jQuery(this);
		current.prop('disabled', 'disabled');
		current.append('<option selected class="loading">Loading Posts..</option>')
		current.addClass('queue');
		fp_as_make_request();
	})

	function fp_as_make_request() {

		if ( running ) return;

		running = true;

		if ( jQuery('[data-ajax-bb-field-posts].queue').length < 1 ) return;

		current_field 	= jQuery('[data-ajax-bb-field-posts].queue').first();
		dataurl 		= fp_as_prep_call_parameters(current_field);

		if ( stored_data[dataurl] ) {
			response = stored_data[dataurl];
			fp_as_parse_results(response, current_field);
			val = current_field.data('value');
			current_field.prop('disabled', false);
			current_field.val(val);
			current_field.removeClass('started');
			current_field.removeClass('queue');
			current_field.find('.loading').remove();
			running = false;
			fp_as_make_request();
		} else {
		jQuery.ajax({
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
			},
			url: wpApiSettings.root + 'wp/v1/get_select_field_posts/?' + dataurl,
			method: 'GET',
			success: function (response) {
				fp_as_parse_results(response, current_field);
					stored_data[dataurl] = response;
			},
			error: function (xhr) {
				console.log('Error no results found for select field popuplation.');
			}
		}).done(function (response) {
				current_field.prop('disabled', false);
				val = current_field.data('value');
				current_field.val(val);
				current_field.removeClass('started');
				current_field.removeClass('queue');
				current_field.find('.loading').remove();
				running = false;
				fp_as_make_request();
		});
	}
	}

	function fp_as_parse_results(results, current_field) {
		for (var key in results) {
			current_field.each(function () {
				value = results[key].id;
				title = results[key].title;
				if (title.length > 0) {
					jQuery(this).append('<option value=\'' + value + '\'>' + title + '</option>');
				}
			})
		}
	}

	function fp_as_prep_call_parameters($target) {

		data = $target.data('ajax-bb-field-posts');
		if (typeof null == data) {
			return;
		}

		parameters = {};

		if (typeof data.posts_per_page !== 'undefined') {
			if (0 > data.posts_per_page) {
				data.posts_per_page = 99999;
			}
			parameters['posts_per_page'] = data.posts_per_page;
		}
		if (typeof data.order !== 'undefined') {
			parameters.order = data.order;
		}
		if (typeof data.orderby !== 'undefined') {
			parameters.orderby = data.orderby;
		}
		if (typeof data.post_type !== 'undefined') {
			parameters['post_type'] = data.post_type;
		}
		if (typeof data.show_post_type !== 'undefined') {
			parameters['show_post_type'] = data.show_post_type;
		}

		dataurl = '';
		// console.log('parameters = %o', parameters);

		for (var property in parameters) {
			if (parameters.hasOwnProperty(property)) {
				if (parameters[property] instanceof Array) {
					for (i = 0; i < parameters[property].length; i++) {
						dataurl += '&' + property + '[]=' + parameters[property][i];
					}
				} else {
					dataurl += '&' + property + '=' + parameters[property];
				}
			}
		}

		return dataurl;

	}

}
