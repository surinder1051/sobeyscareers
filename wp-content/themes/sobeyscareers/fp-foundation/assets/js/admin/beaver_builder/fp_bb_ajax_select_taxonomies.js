// module helper to allow population of posts for post select fields

function bb_ajax_select_field_taxonomies() {

    // console.log('bb_ajax_select_field_taxonomies %o', jQuery('[data-ajax-bb-field-taxonomies]').length);

    if (jQuery('[data-ajax-bb-field-taxonomies]').length < 1) {
        console.log('no data-ajax-bb-field-taxonomies');
        return;
    }

    $ajax_bb_field = jQuery('[data-ajax-bb-field-taxonomies]');

    if (jQuery($ajax_bb_field).hasClass('started')) {
        return;
    }
    jQuery($ajax_bb_field).addClass('started');
    jQuery($ajax_bb_field).prop('disabled', 'disabled');
    current_field = jQuery($ajax_bb_field);


    jQuery.ajax({
        url: wpApiSettings.root + 'wp/v1/get_select_field_taxonomies/',
        method: 'GET',
        success: function (response) {
            fp_as_parse_results(response, current_field);
        },
        error: function (xhr) {
            console.log('Error no results found for select field popuplation.');
        }
    }).done(function (response) {
        jQuery(current_field[0]).prop('disabled', false);
        val = jQuery(current_field[0]).data('value');
        jQuery(current_field[0]).val(val);
        jQuery(current_field[0]).removeClass('started');
    });


    function fp_as_parse_results(results, current_field) {

        value     = jQuery(current_field[0]).data('value');

        for (var key in results) {
            if ( jQuery.inArray( key, value ) > -1 ) {
                selected = 'selected';
            } else {
                selected = '';
            }
            jQuery(current_field[0]).append('<option ' + selected + ' value=\'' + key + '\'>' + results[key] + '</option>');
        }

        jQuery(current_field[0]).val(value);

    }



}
