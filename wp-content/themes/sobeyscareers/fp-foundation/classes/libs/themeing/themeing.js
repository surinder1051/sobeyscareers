jQuery(document).ready(function ($) {

    $global_font_select_field = $('[data-name="font_family"] select');
    $global_variant_field = $('[data-name="font_variant"]');

    $global_font_select_field.on('change', function () {
        get_font_variants($(this), $global_variant_field);
    });

    $main_menu_font_select_field = $('[data-name="font_family_main_menu"] select');
    $main_menu_variants_select_field = $('[data-name="font_variant_main_menu"] select');

    $main_menu_font_select_field.on('change', function () {
        get_font_variants($(this), $main_menu_variants_select_field);
    });

    //ACF Icon picker functions to override default radio button function (use as select field)
    if ($('[data-name="taxonomy_icon"]').length ) {
        $('[data-name="taxonomy_icon"] .acf-input').on('click', function(){
            $(this).toggleClass('hover');
        }).on('blur', function() {
            $(this).removeClass('hover');
        });
        $('[data-name="taxonomy_icon"] .acf-input label').each(function() {
            var iconClass = $('input', this).val();
            $(this).append('<span class="taxonomy-icon-preview"><span class="' + iconClass + '" style="display:inline-block"></span></span>');
            if ($(this).hasClass('selected')) {
                $(this).parents('li').addClass('checked');
            }
        });
        $('[data-name="taxonomy_icon"] input[type="radio"]').on('change', function() {
            var parEl = $(this).parents('[data-name="taxonomy_icon"]');
            $('li', parEl).removeClass('checked');
            $(this).parents('li').addClass('checked');
            $('.acf-input', parEl).trigger('click');
        });
        $('.menu-item-settings').on('click', function(e) {
            if (e.target.className.match(/menu\-item\-settings/) == 'menu-item-settings') {
                $('[data-name="taxonomy_icon"] .acf-input', this).removeClass('hover');
            }
        });
    }

});

// fetch variants from rest api and assign them to variant checkbox option list

function get_font_variants($font_select_field, $variant_field) {
    name = $font_select_field.val();
    if (name.length) {
        $variant_field.find('.acf-label').find('label').append('<span>&nbsp;[ Loading ] </span>');
        jQuery.getJSON(wpApiSettings.root + 'wp/v1/fp-get-font-data/?name=' + name, function (response) {
            variants = response.variants;
            if (variants.length) {
                var variant_field_name = jQuery('.acf-input > input', $variant_field).attr('name');
                $variant_field.find('.acf-checkbox-list').html('');
                jQuery(variants).each(function (i, slug) {
                    title = slug.substr(0, 1).toUpperCase() + slug.substr(1);
                    choice = '<li><label><input type="checkbox" id="' + variant_field_name + '-' + title + '" name="' + variant_field_name + '[]" value="' + title + '">' + title + '</label></li>';
                    $variant_field.find('.acf-checkbox-list').append(choice);
                })
            }
            $variant_field.find('.acf-label').find('span').remove();
        });
    } else {
        $variants_select_field.html('<option>No variants found</option>');
    }
}
