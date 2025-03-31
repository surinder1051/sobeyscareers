jQuery(document).on('click', '.jobs_pagination li a', function(e){
    e.preventDefault();           
    var current = jQuery(this).attr('data-number');
    console.log(current, AjaxUrl);
    jQuery.ajax({
        url: AjaxUrl,
        type: 'post',
        dataType: 'json',
        data: { action: 'Sobeys_Form_Filter', current: current },
        success: function (response) {    
            jQuery('#carrers-data').html(response.data.result);                        
            jQuery('.show_count_label').html(response.data.count);   
            jQuery('.pagination_label ul').html(response.data.pagination);   
            var w = jQuery(window);
            var row = jQuery("#carrers-results");
            jQuery('html,body').animate({ scrollTop: row.offset().top - (w.height() / 2) }, 1000);                                  
        }
    });
});