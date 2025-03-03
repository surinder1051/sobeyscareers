<?php
if (!empty($settings->map_theme)) :
$mapTheme = generate_theme($settings->map_theme, 'button');
if (isset($mapTheme->default_colour) ): ?>

.fl-builder-content .fl-node-<?php echo $id;?> .fl-module-custom button.location-description {
    background-color: <?php echo $mapTheme->default_colour;?>;
    border-color: <?php echo $mapTheme->default_colour;?>;
    color:  <?php echo $mapTheme->text_colour;?>;
}
.fl-builder-content .fl-node-<?php echo $id;?> .fl-module-custom button.location-description:hover {
    color: <?php echo $mapTheme->text_hover_colour;?> !important;
}

<?php
endif;
endif;

FLBuilderCSS::typography_field_rule( array(
    'settings'	=> $settings,
    'setting_name'	=> 'heading_typography', // As in $settings->align.
    'selector'	=> ".fl-node-$id .component_custom_map .heading .map-title",
) );