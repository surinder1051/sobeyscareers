<?php
$lang_code = 'en';
if( function_exists('pll_current_language' )) {
    $lang_code = pll_current_language(); 
}
global $post;
use TRU\SOBYES_CAREERS\Classes\TRU_SOBYES_CAREERS_FRONTEND;
$per_page = defined('PER_PAGE') ? PER_PAGE : 30;
$Api_Data = TRU_SOBYES_CAREERS_FRONTEND::Get_Sobeys_API_Json_Data($post->post_name);
$All_Data = array_slice( $Api_Data, 0, $per_page);
$show_count_label = TRU_SOBYES_CAREERS_FRONTEND::Api_Data_Result_Count(count($Api_Data), 1 );
$total_pages = ( count($Api_Data) > $per_page ) ? ceil( count($Api_Data) / $per_page ) : '1';
$pagination_label = TRU_SOBYES_CAREERS_FRONTEND::Sobeys_Filter_Pagination(array('total' => $total_pages, 'current' => 1 ));
$banners = TRU_SOBYES_CAREERS_FRONTEND::get_Unique_Entry_List('Banner', $lang_code );
$job_type = TRU_SOBYES_CAREERS_FRONTEND::get_Unique_Entry_List('JobType', $lang_code );
$result .= '<ul class="jobs_pagination">'.$pagination_label.'</ul>';

?>

<section class="filter_form_section">
    <div class="container">
        <main> 
            <div class="mobile-filter-wrap">
                <div class="filter-mb">
                    <button id="filter-form" class="filter-form career-button mobile-filter-btn"><span><?php _e('Filter', 'sobeys-careers'); ?></span></button>
                </div>
            </div>
            <section class="career_sec">
                <div class="row">
                
                    <div class="col-xl-3 filter-mobile-form">
                        <div class="career_sidebar">
                        <a href="#" class="btn-close" aria-label="Close"><img src="<?php echo TRU_PLUGIN_URL.'/assets/image/close.svg' ?>"></a>
                            <h2><?php _e('Filter', 'sobeys-careers'); ?></h2>
                            <div class="career_sidebar_data">
                                <input type="text" id="titleSearch" placeholder="Title" class="is-tabbing">

                                <div class="custom-dropdown" id="languageDropdown">
                                    <div class="dropdown-selected"><?php _e('Select Language', 'sobeys-careers'); ?></div>
                                    <ul class="dropdown-options">
                                        <li data-value="" class="selected"><?php _e('Select Language', 'sobeys-careers'); ?></li>
                                        <li data-value="<?php _e('English', 'sobeys-careers'); ?>"><?php _e('English', 'sobeys-careers'); ?></li>
                                        <li data-value="<?php _e('French', 'sobeys-careers'); ?>"><?php _e('French', 'sobeys-careers'); ?></li>
                                    </ul>
                                    <input type="hidden" id="languageValue" value="">
                                </div>
                                <?php if($banners){ ?>
                                <div class="custom-dropdown" id="bannerDropdown">
                                    <div class="dropdown-selected"><?php _e('Select Banner', 'sobeys-careers'); ?></div>
                                    <ul class="dropdown-options">
                                        <li data-value="" class="selected"><?php _e('Select Banner', 'sobeys-careers'); ?></li>
                                        <?php foreach($banners as $value ){ ?>
                                        <li data-value="<?php echo $value; ?>"><?php echo $value; ?></li>
                                        <?php } ?>
                                    </ul>
                                    <input type="hidden" id="bannerValue" value="">
                                </div>
                                <?php } ?>

                                <!-- Business Unit Input -->
                                <input type="text" id="businessUnit" placeholder="Enter Business Unit" class="is-tabbing">

                                <!-- Date Posted Dropdown -->
                                <div class="custom-dropdown" id="dateDropdown">
                                    <div class="dropdown-selected"><?php _e('Date Posted', 'sobeys-careers'); ?></div>
                                    <ul class="dropdown-options">
                                        <li data-value="" class="selected"><?php _e('Date Posted', 'sobeys-careers'); ?></li>
                                        <li data-value="<?php _e('7 Days ago', 'sobeys-careers'); ?>"><?php _e('7 Days ago', 'sobeys-careers'); ?></li>
                                        <li data-value="<?php _e('15 Days ago', 'sobeys-careers'); ?>"><?php _e('15 Days ago', 'sobeys-careers'); ?></li>
                                        <li data-value="<?php _e('30 Days ago', 'sobeys-careers'); ?>"><?php _e('30 Days ago', 'sobeys-careers'); ?></li>
                                    </ul>
                                    <input type="hidden" id="datePosted" value="">
                                </div>
                                <!-- Job Type Dropdown -->
                                <?php if($job_type){ ?>
                                <div class="custom-dropdown" id="jobDropdown">
                                    <div class="dropdown-selected"><?php _e('Job Type', 'sobeys-careers'); ?></div>
                                    <ul class="dropdown-options">
                                        <li data-value="" class="selected"><?php _e('Job Type', 'sobeys-careers'); ?></li>
                                        <?php foreach($job_type as $value ){ ?>
                                        <li data-value="<?php echo $value; ?>"><?php echo $value; ?></li>
                                        <?php } ?>
                                    </ul>
                                    <input type="hidden" id="jobType" value="">
                                </div>
                                <?php } ?>
                                <!-- Location Input -->
                                <input type="text" id="locationValue" placeholder="Enter Location" class="is-tabbing">
                                <label class="distance-range"> <strong><?php _e('Distance:', 'sobeys-careers'); ?></strong> <span class="distance_current">50</span> <?php _e('Kilometers', 'sobeys-careers'); ?></label>
                                <input type="range" id="distance_km" name="volume" min="1" max="100" value="50" class="is-tabbing">
                                
                                <!-- Apply Button -->
                                <div class="applyButton">
                                    <input type="hidden" class="filter-form-id" value="2">
                                    <input type="hidden" id="lang_code" value="<?php echo $lang_code; ?>">
                                    <button id="applyFilters" class="is-tabbing apply-filter-form"><?php _e('Apply', 'sobeys-careers'); ?></button>
                                    <button class="reset_button is-tabbing underline_text"><?php _e('Reset', 'sobeys-careers'); ?></button>
                                </div>
                            </div>`
                        </div>
                    </div>
                    
                    <div class="col-xl-9 mb_career_table_section">
                        <div class="career_table_section" data-form="1">
                            <!-- Loader Container -->
                            <div class="loader-wrapper" style="display: none;">
                                <span class="filter-overlay"></span>
                            </div>
                            <div class="selected-filters-wrapper">
                                <div class="selected-filters" id="selectedFilters"></div>
                                <a href="#" class="clear-all" id="clearAllFilters" style="display: none;"><?php _e('Clear All', 'sobeys-careers'); ?></a>
                            </div>

                            <div class="d-none no-filter-results">
                                <h3 class="filter-results-heading">
                                    <?php printf( __('Search results for "%s".', 'sobeys-careers'), '<span class="filtered-keyword"></span>'); ?>
                                </h3>
                                <div class="filter-results-message">
                                    <p><?php printf( __('There are currently no open positions matching "%s".', 'sobeys-careers'), '<span class="filtered-keyword"></span>'); ?></p>
                                    <p><?php _e('The most recent jobs posted by Sobeys are listed below for your convenience.', 'sobeys-careers'); ?></p>
                                </div>
                            </div>

                            <div id="carrers-results">

                                <div class="show_count_label">
                                    <div class="result-counter" role="status">
                                        <span class="total-item-count" tabindex="0"><?php echo $show_count_label; ?></span>
                                    </div>
                                    <div class="career_table_pagination">
                                        <ul class="jobs_pagination">
                                            <?php echo $pagination_label; ?>
                                        </ul>
                                    </div>
                                </div>
                        
                                <table>
                                    <thead class="career_table_heading">
                                        <tr>
                                            <th><?php _e('Title', 'sobeys-careers'); ?></th>
                                            <th><?php _e('Location', 'sobeys-careers'); ?></th>
                                            <th><?php _e('Category', 'sobeys-careers'); ?></th>
                                            <th><?php _e('Banner', 'sobeys-careers'); ?></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="carrers-data" class="career_table_body">
                                        <?php foreach ($All_Data as $value){ 
                                            $location_array = [$value['City'], $value['State'], $value['Country'], $value['PostalCode']]; ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($value['Title']); ?></td>
                                            <td><?php echo htmlspecialchars(implode(', ', $location_array)); ?></td>
                                            <td><?php echo htmlspecialchars($value['Category'] ?? ''); ?></td>
                                            <td><?php echo ( $lang_code == 'fr') ? $value['Banner_FR'] : $value['Banner']; ?></td>
                                            <td><a href="#" data-id="<?php echo $value['ID'] ?? ''; ?>" class="job_view_detail"><?php _e('View Details', 'sobeys-careers'); ?></a></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="show_count_label show_count_label_mb">

                                    <div class="result-counter " role="status">
                                        <span class="total-item-count" tabindex="0"><?php echo $show_count_label; ?></span>
                                    </div>
                                    <div class="career_table_pagination">
                                        <ul class="jobs_pagination">
                                            <?php echo $pagination_label; ?>
                                        </ul>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>
</section>
<section class="single_modal_content" style="display:none;">
    <div class="job_modal_body"></div>
</section>


