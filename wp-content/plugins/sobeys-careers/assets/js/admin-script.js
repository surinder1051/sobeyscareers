jQuery(document).on('click', '.updateJsonData', function () {
    jQuery('.updateJsonData').attr('disabled', 'disabled');
    jQuery.ajax({
        url: AjaxUrl,
        type: 'post',
        dataType: 'json',
        data: { action: 'Sobeys_Api_Json_Filter'},
        success: function (response) {
            if (response.data.result === 'success') {
                jQuery('.updated').slideToggle();
                jQuery('.updateJsonData').attr('disabled', false);
                setTimeout(function () {
                    jQuery('.updated').slideToggle();
                }, 4000);
            }
        }
    });
});