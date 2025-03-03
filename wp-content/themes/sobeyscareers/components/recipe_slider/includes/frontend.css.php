<?php
FLBuilderCSS::typography_field_rule(array(
    'settings'    => $settings,
    'setting_name'     => 'title_tag_typography',
    'selector'     => 'body .fl-module-recipe_slider.fl-node-' . $id . ' .component_recipe_slider .title',
));
FLBuilderCSS::typography_field_rule(array(
    'settings'    => $settings,
    'setting_name'     => 'slides_typography',
    'selector'     => 'body .fl-module-recipe_slider.fl-node-' . $id . ' .component_recipe_slider .carousel-item .card-body .card-title',
));