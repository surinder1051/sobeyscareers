<?php
$themeOpts = generate_theme($settings->style_background, 'background');
$secondaryColour = generate_theme('secondary_colour');

if (isset($themeOpts->default_colour) && !empty($themeOpts->default_colour)) :
?>
.fl-row.fl-node-<?php echo $id; ?> {
    background: <?php echo $themeOpts->default_colour; ?>;
}
<?php endif; ?>

<?php if (isset($themeOpts->text_colour) && !empty($themeOpts->text_colour)) : ?>
.fl-node-<?php echo $id; ?> h1,
.fl-node-<?php echo $id; ?> h2,
.fl-node-<?php echo $id; ?> h3,
.fl-node-<?php echo $id; ?> h4,
.fl-node-<?php echo $id; ?> h3,
.fl-node-<?php echo $id; ?> h5,
.fl-node-<?php echo $id; ?> h6,
.entry-content .fl-node-<?php echo $id; ?> p {
    color: <?php echo $themeOpts->text_colour; ?>;
}
<?php
endif;

if (!empty($secondaryColour)) :
?>
#main .fl-node-<?php echo $id; ?> .accent-bar-center:after {
    border-left-color: <?php echo $secondaryColour;?>;
}
#main .fl-node-<?php echo $id; ?> .accent-bar-center:before {
    background-color: <?php echo $secondaryColour;?>;
}

<?php endif;

fp_apply_style($id, ['.heading'], 'color', $settings->fact_heading_colour);

FLBuilderCSS::typography_field_rule( array(
	'settings'    => $settings,
	'setting_name'    => 'fact_heading_style', // As in $settings->typography
	'selector'    => ".fl-node-$id .heading",
) );