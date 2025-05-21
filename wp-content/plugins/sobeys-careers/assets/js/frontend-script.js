// change the distance input field value
jQuery(document).on('change', '#distance_km', function(){
    var current = jQuery(this).val();
    jQuery('.distance_current').html(current);
})
// search form on click event
jQuery(document).on('click', '#searching-form', function(){
    var id = jQuery('.search-form-id').val();
    var keyword = jQuery('#keywordSearch').val();
    var category = jQuery('#categorySearch').val();
    var location = jQuery('#locationSearch').val();
    var language_code = jQuery('#language_code').val();
    var page_slug = jQuery('#page-slug').val();
    var current = 1;
    sobeys_search_filter(current, id, keyword, category, location, language_code, page_slug );
});
// trigger search form ajax
function sobeys_search_filter( current, id, keyword, category, location, language, page_slug ){
    jQuery('.loader-wrapper').show();
    jQuery.ajax({
        url: Ajax.url,
        type: 'post',
        dataType: 'json',
        data: { action: 'Sobeys_Search_Filter', nonce: Ajax.nonce, current: current, keyword: keyword, category: category, location: location, language: language, page_slug: page_slug },
        success: function (response) {   
            jQuery('.loader-wrapper').hide();
            var form = wp.template('job_list_rows'); 
            jQuery('#carrers-data').html(form({
                result : response.data.result,
                lang: language
            }));                        
            jQuery('.total-item-count').html(response.data.count);   
            if(response.data.pages > 1){
                jQuery('.jobs_pagination').removeClass('d-none');   
            } else {
                jQuery('.jobs_pagination').addClass('d-none');   
            }
            var searchTerms = [];
            if (keyword) searchTerms.push(keyword);
            if (category) searchTerms.push(category);
            if (location) searchTerms.push(location);
            var searchString = searchTerms.join(', ');
            if (response.data.total < 1 ) {
                jQuery('.no-search-results .searched-keyword').text(searchString);
                jQuery('.no-search-results').removeClass('d-none');
                jQuery('.search_alert_section, .filter_form_section').addClass('d-none');
                var row = jQuery(".no-search-results");
            } else {
                jQuery('.no-search-results').addClass('d-none');
                jQuery('.search_alert_section, .filter_form_section').removeClass('d-none');
                var row = jQuery("#carrers-results");
            }
            jQuery('.jobs_pagination').html(response.data.pagination);   
            jQuery('.career_table_section').attr('data-form', id);   
            var w = jQuery(window);
            jQuery('html,body').animate({ scrollTop: row.offset().top - (w.height() / 2) }, 1000);                                  
        }
    });
}
// filter form on click event
jQuery(document).on('click', '.apply-filter-form', function(){
    let selectedFilters = [];
    jQuery('.filter-mobile-form').removeClass('show');
    var page_slug = jQuery('#page-slug').val();
    var id = jQuery('.filter-form-id').val();
    var distance = jQuery('#distance_km').val();
    var location = jQuery('#locationValue').val();
    var title = jQuery('#titleSearch').val();
    var datePosted = jQuery('#datePosted').val();
    var banner = jQuery('#bannerValue').val();
    var langValue = jQuery('#languageValue').val();
    var businessUnit = jQuery('#businessUnit').val();
    var jobType = jQuery('#jobType').val();
    var language = jQuery('#lang_code').val();
    var current = 1;
    jQuery('.loader-wrapper').show();
    if (title) selectedFilters.push({ name: 'titleSearch', type: 'input', value: title });
    if (langValue) selectedFilters.push({ name: 'languageDropdown', type: 'select', value: langValue });
    if (banner) selectedFilters.push({ name: 'bannerDropdown', type: 'select', value: banner });
    if (datePosted) selectedFilters.push({ name: 'dateDropdown', type: 'select', value: datePosted });
    if (jobType) selectedFilters.push({ name: 'jobDropdown', type: 'select', value: jobType });
    if (location) selectedFilters.push({ name: 'locationValue', type: 'input', value: location });
    if (businessUnit) selectedFilters.push({ name: 'businessUnit', type: 'input', value: businessUnit });
    if (distance) selectedFilters.push({ name: 'distance_km', type: 'range', value: 'Distance ' + distance + ' KM' });

    if (selectedFilters.length > 0) {
        let html = '<h3>Your Selected</h3><ul>';
        selectedFilters.forEach(f => {
            html += `<li class="selected-val">${f.value} <span class="remove-entry" data-type="${f.type}" data-filter="${f.name}">×</span></li>`;
        });
        html += '</ul>';
        jQuery('#selectedFilters').html(html);
        jQuery('#clearAllFilters').show();
    } else {
        jQuery('#selectedFilters').empty();
        jQuery('#clearAllFilters').hide();
    }
    sobeys_data_filter(current, id, title, langValue, banner, datePosted, location, distance, businessUnit, jobType, language, page_slug);
});
// pagination on click event
jQuery(document).on('click', '.jobs_pagination li a', function(e){
    e.preventDefault();           
    var current = jQuery(this).attr('data-number');
    var id = jQuery('.career_table_section').attr('data-form');
    var page_slug = jQuery('#page-slug').val();
    if( id == '2'){
        var title = jQuery('#titleSearch').val();
        var location = jQuery('#locationValue').val();
        var distance = jQuery('#distance_km').val();
        var datePosted = jQuery('#datePosted').val();
        var banner = jQuery('#bannerValue').val();
        var langValue = jQuery('#languageValue').val();
        var businessUnit = jQuery('#businessUnit').val();
        var jobType = jQuery('#jobType').val();
        var language = jQuery('#lang_code').val();
        sobeys_data_filter(current, id, title, langValue, banner, datePosted, location, distance, businessUnit, jobType,language, page_slug );
    } else {
        var keyword = jQuery('#keywordSearch').val();
        var category = jQuery('#categorySearch').val();
        var location = jQuery('#locationSearch').val();
        var language_code = jQuery('#language_code').val();
        sobeys_search_filter(current, id, keyword, category, location, language_code, page_slug );
    }
});
// trigger job view detail ajax
jQuery(document).on('click', '.job_view_detail', function(e){
    e.preventDefault();
    jQuery('.loader-wrapper').show();
    var id = jQuery(this).data('id');
    var language = jQuery('#lang_code').val();
    jQuery.ajax({
        url: Ajax.url,
        type: 'post',
        dataType: 'json',
        data: { action: 'Sobeys_Job_Details', id: id },
        success: function (response) { 
            jQuery('.loader-wrapper').hide(); 
            var template = wp.template('job_list_text');
            var html = template({
                result: response.data.result,
                lang: language
            });
            jQuery('.job_modal_body').html(html); 
            jQuery('.single_modal_content').show();
            jQuery('body').addClass('scroll-hide');                                                     
        }
    });
});
// clear & reset button on click event
jQuery(document).on('click', '#clearAllFilters, .reset_button', function(e){
    e.preventDefault();
    jQuery('.filter-mobile-form').removeClass('show');
    var id = jQuery('.filter-form-id').val();
    var page_slug = jQuery('#page-slug').val();
    jQuery('#distance_km').val('50');
    jQuery('.distance_current').html('50');
    jQuery('#locationValue').val('');
    jQuery('#titleSearch').val('');
    jQuery('#businessUnit').val('');
    resetCustomDropdown('dateDropdown');
    resetCustomDropdown('bannerDropdown');
    resetCustomDropdown('languageDropdown');
    resetCustomDropdown('jobDropdown');
    jQuery('#selectedFilters').empty();
    jQuery('#clearAllFilters').hide();
    var language = jQuery('#lang_code').val();
    sobeys_data_filter(1, id, '', '', '', '', '', '', '', '', language, page_slug );
});
// remove entry on click event
jQuery(document).on('click', '.remove-entry', function(){
    var id = jQuery('.filter-form-id').val();
    var $filter = jQuery(this).closest('.selected-val');
    var filterType = jQuery(this).attr('data-type'); 
    var filterId = jQuery(this).attr('data-filter');
    $filter.remove(); 
    var distance = jQuery('#distance_km').val();
    if(filterType === 'select'){
        resetCustomDropdown(filterId);
    }
    if(filterType === 'input'){
        jQuery(`#${filterId}`).val('');
    }
    if(filterType === 'range'){
        jQuery('#'+id).val('50');
        distance = '';
    }
    if (jQuery('#selectedFilters').children().length === 0) {
        jQuery('#clearAllFilters').hide();
    }
    jQuery('.distance_current').html('50');
    var location = jQuery('#locationValue').val();
    var title = jQuery('#titleSearch').val();
    var datePosted = jQuery('#datePosted').val();
    var banner = jQuery('#bannerValue').val();
    var langValue = jQuery('#languageValue').val();
    var businessUnit = jQuery('#businessUnit').val();
    var jobType = jQuery('#jobType').val();
    var language = jQuery('#lang_code').val();
    var page_slug = jQuery('#page-slug').val();
    sobeys_data_filter( 1, id, title, langValue, banner, datePosted, location, distance, businessUnit, jobType, language, page_slug );
});
// trigger filter form ajax function
function sobeys_data_filter( current, id, title, langValue, banner, datePosted, location, distance, businessUnit, jobType, language, page_slug){
    jQuery('.loader-wrapper').show();
    jQuery.ajax({
        url: Ajax.url,
        type: 'post',
        dataType: 'json',
        data: { action: 'Sobeys_Apply_Filter', nonce: Ajax.nonce, current: current, title: title, distance: distance, location: location, datePosted: datePosted, banner: banner, langValue: langValue, businessUnit: businessUnit, jobType: jobType, language: language, page_slug: page_slug  },
        success: function (response) {    
            jQuery('.loader-wrapper').hide();
            var form = wp.template('job_list_rows'); 
            jQuery('#carrers-data').html(form({
                result: response.data.result,
                lang: language
            }));  
            jQuery('.total-item-count').html(response.data.count);  
            if(response.data.pages > 1){
                jQuery('.jobs_pagination').removeClass('d-none');   
            } else {
                jQuery('.jobs_pagination').addClass('d-none');   
            } 
            var searchTerms = [];
            if (title) searchTerms.push(title);
            if (langValue) searchTerms.push(langValue);
            if (banner) searchTerms.push(banner);
            if (businessUnit) searchTerms.push(businessUnit);
            if (datePosted) searchTerms.push(datePosted);
            if (location) searchTerms.push(location);
            if (jobType) searchTerms.push(jobType);
            if (distance) searchTerms.push('Distance ' + distance + ' KM');
            var searchString = searchTerms.join(', ');

            if (response.data.total < 1 ) {
                jQuery('.no-filter-results .filtered-keyword').text(searchString);
                jQuery('.no-filter-results').removeClass('d-none');
                jQuery('#carrers-results').addClass('d-none');
                var row = jQuery(".no-filter-results");
            } else {
                jQuery('.no-filter-results').addClass('d-none');
                jQuery('#carrers-results').removeClass('d-none');
                var row = jQuery("#carrers-results");
            }

            jQuery('.jobs_pagination').html(response.data.pagination);   
            jQuery('.career_table_section').attr('data-form', id);   
            var w = jQuery(window);  
            jQuery('html,body').animate({ scrollTop: row.offset().top - (w.height() / 2) }, 1000);                                  
        }
    });
}
// search form on click event
function resetCustomDropdown(dropdownId) {
    const $dropdown = jQuery(`#${dropdownId}`);
    const $firstOption = $dropdown.find('.dropdown-options li').first();
    $dropdown.find('input[type="hidden"]').val('');
    $dropdown.find('.dropdown-selected').text($firstOption.text());
    $dropdown.find('.dropdown-options li').removeClass('selected');
    $firstOption.addClass('selected');
}
// view details popup close icon on click event
jQuery(document).on('click', '.single_modal_content .btn-close', function(e){
    e.preventDefault();
    jQuery('.single_modal_content').hide();
    jQuery('body').removeClass('scroll-hide'); 
});
// mobile form close icon on click event
jQuery(document).on('click', '.filter-mobile-form .btn-close', function(e){
    e.preventDefault();
    jQuery('.filter-mobile-form').removeClass('show');
    jQuery('body').removeClass('scroll-hide');
});
// custom dropdown on click event
jQuery(document).on('click', '.dropdown-selected', function(e) {
    e.stopPropagation();
    var $dropdown = jQuery(this).closest('.custom-dropdown');
    jQuery('.dropdown-options').not($dropdown.find('.dropdown-options')).slideUp();
    $dropdown.find('.dropdown-options').slideToggle();
});
// custom dropdown selected value on click event
jQuery(document).on('click', '.dropdown-options li', function(e) {
    var $li = jQuery(this);
    jQuery('.dropdown-options li').removeClass('selected');
    $li.addClass('selected');
    var $dropdown = $li.closest('.custom-dropdown');
    var value = $li.data('value');
    var text = $li.text();

    $dropdown.find('.dropdown-selected').text(text);
    $dropdown.find('input[type="hidden"]').val(value);
    $dropdown.find('.dropdown-options').slideUp();
});

// Close dropdown & popup if clicked outside
jQuery(document).on('click', function() {
    jQuery('.dropdown-options').slideUp();
    jQuery('body').removeClass('scroll-hide');
    jQuery('.single_modal_content').hide();
    jQuery('.filter-mobile-form').removeClass('show');
});
// show mobile filter form on click event
jQuery(document).on('click', '.mobile-filter-btn', function(){
    jQuery('.filter-mobile-form').addClass('show');
    jQuery('body').addClass('scroll-hide'); 
})