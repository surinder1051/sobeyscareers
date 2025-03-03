(function($) {

	FLBuilder._registerModuleHelper('notice', {
		rules: {
			notice_id: {
				required: true
			}
		},
		init: function() {
			bb_ajax_select_field_posts();
			check_notice_status();
			$('[name="notice_id"]').change(function() {
				if ('' == $('[name="notice_id"]').val() ) {
					$('#fl-field-text label').html('');
				} else {
					check_notice_status();
				}
			} );
		}
	} );

}(jQuery) );

function check_notice_status() {

	lookup_id = $('[name="notice_id"]').val();
	if ('' == lookup_id) {
		return;
	}

	$.ajax( {
		url: wpApiSettings.root + 'wp/v2/notice/' + lookup_id,
		method: 'GET'
	} ).done(function(response) {
		if (1 > response.acf.appear_on.length) {
			$('#fl-field-text label').html('This notice is not setup to appear on this page, click <a target="_blank" href="/wp-admin/post.php?post=' + lookup_id + '&action=edit">here</a> to edit notice settings.');
		} else {
			for (var i = 0; i < response.acf.appear_on.length; i++) {
				if (FLBuilderConfig.postId == response.acf.appear_on[i].ID) {
					$('#fl-field-text label').html('This notice has permission to show on this page.');
					return;
				} else {
					$('#fl-field-text label').html('This notice is not setup to appear on this page, click <a target="_blank" href="/wp-admin/post.php?post=' + lookup_id + '&action=edit">here</a> to edit notice settings.');
				}
			}

		}
	} );

}
