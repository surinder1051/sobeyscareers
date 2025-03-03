(function ($) {

    /**
     * Add region filter to the BB menu
     */
    function fp_add_region_filter_to_bb_menu() {
        console.log('add_region_filter_to_bb_menu');

        if (typeof fp_bb_regionalization == "undefined") {
            console.warn('FP_BB_Module_Regionalization: Missing localized variable - fp_bb_regionalization, breaking...');
            return;
        }

        if (jQuery('.bb_region_filter').length > 0) {
            return;
        }

        var select = $('<select class="fp-region-dropdown" style="flex-basis: 100%"></select>');
        for (fp_region_i in fp_bb_regionalization['regions']) {
            var opt = $("<option></option>");
            opt.val(fp_region_i);
            opt.html(fp_bb_regionalization['regions'][fp_region_i]);
            select.append(opt);
        }

        $bb_region_filter = jQuery('<span class="fl-builder-button-group bb_region_filter"><label>Region:</label><select class="fp-region-dropdown" style="flex-basis: 100%"></select></span>').insertAfter('.fl-builder-content-panel-button');
        $update_button = jQuery('.fp-update-region-button');
        $region_dropdown = jQuery('.fp-region-dropdown');
        $('.fp-region-dropdown').replaceWith(select);

        if (jQuery('.fl-module').length > 1) {
            filter_modules_by_region();
        }

        $('.fp-region-dropdown').on('change', filter_modules_by_region);
    }

    /**
     * Filter the current selected region.
     */
    function filter_modules_by_region(e) {
        if (typeof e !== "undefined") {
            console.log("filter_modules_by_region: ", e);
            if (e.type == 'fl-builder' && e.namespace == "didAddModule") {
                pre_select_newModule_region();
            }
        }
        else {
            console.log("filter_modules_by_region: none ");
        }

        var selected_region = $('.fp-region-dropdown').val();

        // Show all.
        if (selected_region == 'all') {
            $('.fp-region-module').hide();
            $('.fp-region-module').show();
        } // Show with no selection.
        else if (selected_region == 'none') {
            $('.fp-region-module').hide();
            // show all the visible ones and 
            $('.fp-region-visible-no-selection').show();
        } else {
            $('.fp-region-module').hide();
            $('.fp-region-visible-no-selection').show();
            console.log("selected_region:", selected_region);
            if (typeof fp_bb_regionalization.child_regions[selected_region] !== 'undefined') {
                //var region_in = fp_bb_regionalization.child_regions[ selected_region ][i].join(',');
                for (i in fp_bb_regionalization.child_regions[selected_region]) {
                    var current_region = fp_bb_regionalization.child_regions[selected_region][i];
                    $('.fp-region-visible-' + current_region).show();
                    $('.fp-region-hidden-' + current_region).hide();
                }
            } else {
                $('.fp-region-visible-' + selected_region).show();
                $('.fp-region-hidden-' + selected_region).hide();
            }
        }
    }

    /**
     * Hook to pre-select region when a new module is added.
     */
    function pre_select_newModule_region() {
        var region = $('.fp-region-dropdown').val();
        if (region == 'all')
            return;
            
        $('#fl-builder-settings-tab-regions select[name*="visible_regions"] option[value*="' + region + '"]').prop('selected', true);
    }

    /**
     * Handler to check if the BB lightbox has been open, if so, we want to select all the children of the current selections.
     * @param {*} e 
     */
    function module_open_preselect_childen(e) {
        if (typeof e !== "undefined") {
            console.log("module_open_preselect_childen: ", e);
            if (e.type == 'fl-builder' && e.namespace == "didShowLightbox") {
                if ( $('#fl-builder-settings-tab-regions').length == 0 ) {
                    console.log("No module lightbox found, cannot check children region selections.");
                    return;
                }

                // Let's select all the children of the currently selected options
                
                $('#fl-builder-settings-tab-regions select option:selected').each(function(index){
                    //console.log("Selecting children of ", selected_option);
                    pre_select_children_regions( false, $(this) );
                });
            }
        }
        else {
            console.log("module_open_preselect_childen: none ");
        }
    } 

    /**
     * Captures the current or provided `option`. Then pre-selects all it's child regions.
     * @param {*} e 
     * @param {*} el 
     */
    function pre_select_children_regions( e, el ) {
        // Ensure the box is open and regions tab exists
        if ( $('#fl-builder-settings-tab-regions').length == 0 ) {
            console.log("No module lightbox found, cannot check children region selections.");
            return;
        }

        // Let get the region name, if it's selected or not and the select area.
        var selected_region = typeof el !== "undefined" ? $(el).val() : $(this).val();
        var is_selected = typeof el !== "undefined" ? $(el).prop('selected') : $(this).prop('selected');
        var select_attr_name = typeof el !== "undefined" ? $(el).parent().attr('name') : $(this).parent().attr('name');

        // Loop through all the child regions, and ensure they are also selected/unselected
        if (typeof fp_bb_regionalization.child_regions[selected_region] !== 'undefined') {
            var child_regions = fp_bb_regionalization.child_regions[selected_region];
            // If there's multiple children, select/unselect them all
            if (child_regions.length > 1) {
                for (i in fp_bb_regionalization.child_regions[selected_region]) {
                    var current_region = fp_bb_regionalization.child_regions[selected_region][i];
                    $('#fl-builder-settings-tab-regions select[name*="'+select_attr_name+'"] option[value="' + current_region + '"]').prop('selected', is_selected);
                }
            } // This is a bottom-child region, there could be parents but there are no children. We want to check if a child region is being unselected while a parent is selected, prevent this.
            else if ((child_regions.length === 1) && ( ! is_selected ) ) {
                // get the parent, if any
                var parents = get_parent_of_child( child_regions[0] );
                for (l in parents) {
                    var parent = parents[l];
                    var is_parent_selected = $('#fl-builder-settings-tab-regions select[name*="'+select_attr_name+'"] option[value="' + parent + '"]').prop('selected');
                    // If the parent is indeed selected, then we cannot allow the single item to be de-selected
                    if (is_parent_selected) {
                        $('#fl-builder-settings-tab-regions select[name*="'+select_attr_name+'"] option[value="' + child_regions[0] + '"]').prop('selected', true);
                    }
                }
            }
        }
    }

    /**
     * Helper function to get all the parents of a provided region, this help detemine if we should allow a de-select or not if the parent is selected.
     * @param {string} region_name 
     */
    function get_parent_of_child( region_name ) {
        if (typeof fp_bb_regionalization.child_regions === 'undefined') {
            console.warn("Missing child region localized JS var");
            return;
        }
        var parent_regions = [];
        for (parent_name in fp_bb_regionalization.child_regions) {
            if (parent_name === region_name) {
                continue;
            }
            var child_regions = fp_bb_regionalization.child_regions[parent_name];
            if (child_regions.includes(region_name)) {
                parent_regions.push( parent_name )
            }
        }
        return parent_regions;
    }

    /**
     * Initialize region filter and hooks.
     */
    function init_region_filter() {
        setTimeout(function () {
            fp_add_region_filter_to_bb_menu();
        }, 1 * 2000);

        FLBuilder.addHook('didAddModule', filter_modules_by_region);
        FLBuilder.addHook('didSaveNodeSettingsComplete', filter_modules_by_region);
        FLBuilder.addHook('didSaveNodeSettings', filter_modules_by_region);
        FLBuilder.addHook('didDuplicateModule', filter_modules_by_region);
        FLBuilder.addHook('didDeleteModule', filter_modules_by_region);
        FLBuilder.addHook('contentItemsChanged', filter_modules_by_region);
        FLBuilder.addHook('publishButtonClicked', FLBuilder._updateLayout );
        
        
        // When a region is selected/unselected be sure to highlight all the boxes
        // We should only do this if the this feature is enabled
        // And we need to do this every time a box is opened to verify all regions are enabled
        if (typeof fp_bb_regionalization.include_children !== 'undefined') {
            if (fp_bb_regionalization.include_children == "true") {
                $(document).on('click', '#fl-builder-settings-tab-regions select option', pre_select_children_regions);
                FLBuilder.addHook('didShowLightbox', module_open_preselect_childen );
            } 
        }
        
    }

    $(document).ready(function ($) {
        init_region_filter();
        return;
    });

})(jQuery);