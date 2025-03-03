<?php
if (!function_exists('site_name')) {
    function display_site_name($atts, $content = '') {
        return get_bloginfo('name');
    }
    add_shortcode('sitename', 'display_site_name');
}