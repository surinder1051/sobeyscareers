jQuery(document).ready(function($) {

    var hideHeadings = new Array();
    var visibleP = 0;
    var headingInit = false;

    if ($('.settings_page_fl-builder-settings').length && $('#modules-form').length ) {
        if (typeof excludeModules.list != 'undefined' && excludeModules.list.length) {
            $('#modules-form input[type="checkbox"]').each(function() {
                if (excludeModules.list.indexOf($(this).val()) != -1) {
                    $(this).attr('checked', false);
                    if ($(this).parents('p').length) {
                        $(this).parents('p').hide();
                    } else {
                        $(this).parents('label').hide();
                    }
                }
            });
            $('#modules-form .fl-settings-form-content').children().each(function(index) {
                if ($(this).prop('tagName').toLowerCase() == 'h3') {
                    if (headingInit !== false && visibleP == 0) {
                        hideHeadings.push(headingInit);
                    }
                    headingInit = $(this);
                    visibleP = 0;
                } else if ($(this).prop('tagName').toLowerCase() == 'p' && $(this).css('display') != 'none' ) {
                    visibleP++;
                }
            });

            if (hideHeadings.length) {
                for (var h = 0; h < hideHeadings.length; h++) {
                    hideHeadings[h].hide();
                }
            }
        }
    }
});
