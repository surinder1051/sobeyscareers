<?php
$lang_code = 'en';
if( function_exists('pll_current_language' )) {
    $lang_code = pll_current_language(); 
}
use TRU\SOBYES_CAREERS\Classes\TRU_SOBYES_CAREERS_FRONTEND;
$category = TRU_SOBYES_CAREERS_FRONTEND::get_Unique_Entry_List('Category', 'en' );
global $post;
?>
<div class="container">
    <div class="searching row">
        <div class="column col-xl-3 search-locations">
            <div class="column_text">
                <span class="icon"></span>
                <div class="search_content search_content_border">
                    <img src="<?php echo TRU_PLUGIN_URL.'/assets/image/searching_icon.svg' ?>" alt="">
                    <input type="text" id="keywordSearch" name="keyword" placeholder="<?php _e('Search by Keyword', 'sobeys-careers'); ?>">
                </div>
            </div>
        </div>
        <div class="column col-xl-3">
            <div class="column_text">
                <span class="icon"></span>
                <div class="search_content search_content_border search_dropdown">
                    <img src="<?php echo TRU_PLUGIN_URL.'/assets/image/cart_icon.svg' ?>" alt="">
                    <div class="custom-dropdown" id="categoryDropdown">
                        <div class="dropdown-selected"><?php _e('Search by Category', 'sobeys-careers'); ?></div>
                        <ul class="dropdown-options">
                            <li data-value="" class="selected"><?php _e('Search by Category', 'sobeys-careers'); ?></li>
                            <?php foreach($category as $value ){ ?>
                            <li data-value="<?php echo $value; ?>"><?php echo $value; ?></li>
                            <?php } ?>
                        </ul>
                        <input type="hidden" id="categorySearch" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="column col-xl-3 search-location">
            <div class="column_text">
                <span class="icon"></span>
                <div class="search_content">
                    <img src="<?php echo TRU_PLUGIN_URL.'/assets/image/location_icon.svg' ?>" alt="">
                    <input type="text" id="locationSearch" name="location" placeholder="<?php _e('Search by Location', 'sobeys-careers'); ?>">
                </div>
            </div>
        </div>
        <div class="column col-xl-3 search-button-column">
            <div class="column_text">
                <div class="search_button">
                    <input type="hidden" class="search-form-id" value="1">
                    <input type="hidden" id="language_code" value="<?php echo $lang_code; ?>">
                    <input type="hidden" id="page-slug" value="<?php echo $post->post_name; ?>">
                    <button id="searching-form" class="searchJob career-button"><span><?php _e('Search Jobs', 'sobeys-careers'); ?></span></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-none no-search-results">
    <h3 class="search-results-heading">
        <?php printf( __('Search results for "%s".', 'sobeys-careers'), '<span class="searched-keyword"></span>'); ?>
    </h3>
    <div class="search-results-message">
    <p><?php printf( __('There are currently no open positions matching "%s".', 'job search message', 'your-text-domain'),
    '<span class="searched-keyword"></span>' ); ?></p>
        <p><?php _e('The most recent jobs posted by Sobeys are listed below for your convenience.', 'sobeys-careers'); ?></p>
    </div>
</div>
<!-- <div class="search_alert_section">
    <div class="alert_section">
        <h4><?php// _e('Select how often (in days) to receive an alert:', 'sobeys-careers'); ?></h4>
    </div>
    <div class="alert2">
        <div class="alert_digit">0</div>
        <div class="alert_button">
            <button id="searching-form" class="searchJob career-button"><span><?php //_e('Create Alert', 'sobeys-careers'); ?></span></button>
        </div>
    </div>
</div> -->


