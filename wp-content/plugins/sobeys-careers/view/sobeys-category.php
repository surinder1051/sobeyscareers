<?php $entries = get_option('sobeys_slider_entries', []); 
if(!empty($entries)){ 
    wp_enqueue_style( 'sobeys-slick-css'); ?>
<div class="sobeys-category-slider">
    <?php foreach ($entries as $entry) { 
            if (!empty($entry['image'])) { ?>
    <div class="category-slider-row">
        <div class="category-main">
            <div class="category-image-thumb">
                <img src="<?php echo esc_url($entry['image']); ?>" alt="<?php echo esc_html($entry['title']); ?>">
                <h3><?php echo esc_html($entry['title']); ?></h3>
            </div>
            <div class="category-slider-content">
                <?php echo wp_kses_post($entry['description']); ?>
                <div class="category-slider-button">
                    <div class ="category-link">
                    <?php if(!empty($entry['link'])){ ?>
                    <a href="<?php echo esc_url($entry['link']); ?>"
                    class="sobeys_learn_more career-button"><span>Learn More</span></a>
                    
                    <?php } ?>
                    </div>
                   <!-- <div class="category-button">
                   <button class="search_category career-button"><span>Search</span></button>
                   </div> -->
                </div>
            </div>
        </div>
    </div>
    <?php } 
        } ?>
</div>
<?php wp_enqueue_script( 'sobeys-slick-js' ); 
    wp_add_inline_script('sobeys-slick-js', 'jQuery(document).ready(function(){
        jQuery(".sobeys-category-slider").slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: false,
            dots: false,
            autoplaySpeed: 2000,
            prevArrow : \'<button type="button" class="slick-prev slick-arrow" aria-label="Previous" role="button"><img src="'.TRU_PLUGIN_URL.'/assets/image/chevron_left.svg"></button>\',
            nextArrow : \'<button type="button" class="slick-next slick-arrow" aria-label="Next" role="button" style=""><img src="'.TRU_PLUGIN_URL.'assets/image/chevron_right.svg"></button>\',
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });

    });');
} ?>