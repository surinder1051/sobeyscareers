<?php

fp_apply_style($id, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], 'color', $settings->heading_color);

FLBuilderCSS::typography_field_rule(array(
    'settings'    => $settings,
    'setting_name'     => 'heading_typography',
    'selector'     => 'body .fl-node-' . $id . ' .heading',
));

$textboxTheme = generate_theme($settings->theme, 'background');

FLBuilderCSS::responsive_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'heading_font_size', // As in $settings->align.
	'selector'	=> ".fl-node-$id h3",
	'prop'		=> 'font-size',
	'unit'		=> 'px',
) );

FLBuilderCSS::responsive_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'image_height', // As in $settings->align.
	'selector'	=> ".fl-node-$id .component_z_pattern_v2 .safety-container .image-container img",
	'prop'		=> 'height',
	'unit'		=> 'px',
) );

// Text Padding
FLBuilderCSS::dimension_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'text_padding',
	'selector'     => ".fl-node-$id .component_z_pattern_v2 .safety-container .text-container",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'text_padding_top',
		'padding-right'  => 'text_padding_right',
		'padding-bottom' => 'text_padding_bottom',
		'padding-left'   => 'text_padding_left',
	),
));
// var_name, var_name_medium, var_name_responsive - 

// Link Padding
FLBuilderCSS::dimension_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'link_padding',
	'selector'     => ".fl-node-$id .component_z_pattern_v2 .safety-container .text-container .icon-link",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'link_padding_top',
		'padding-right'  => 'link_padding_right',
		'padding-bottom' => 'link_padding_bottom',
		'padding-left'   => 'link_padding_left',
	),
));

?>


.fl-node-<?php echo $id; ?> h3 {

<?php if (!empty($settings->heading_text_align)) : ?>
    text-align: <?php echo $settings->heading_text_align ?>;
<?php endif ?>

<?php if (!empty($settings->heading_font_style)) : ?>
    text-transform: <?php echo $settings->heading_font_style ?>;
<?php endif ?>

}

<?php if (!empty($settings->heading_font_size_medium)) : ?>
    @media (min-width: 800px) and (max-width: 1024px) {
    .fl-node-<?php echo $id ?> h3 {
    font-size: <?php echo $settings->heading_font_size_medium ?>px;
    }
    }
<?php endif    ?>

<?php if (!empty($settings->heading_font_size_responsive)) : ?>
    @media (max-width: 800px) {
    .fl-node-<?php echo $id ?> h3 {
    font-size: <?php echo $settings->heading_font_size_responsive ?>px;
    }
    }
<?php endif    ?>

<?php
// background_width refers to the width of the text-container, will convert to block layout if it's 100%
fp\components\z_pattern_v2::fp_responsive_width_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'background_width', // As in $settings->align.
	'selector'	=> [".fl-node-$id .component_z_pattern_v2 .safety-container .text-container", ".fl-node-$id .component_z_pattern_v2 .safety-container .image-container"],
    'container' => ".fl-node-$id .component_z_pattern_v2 .safety-container",
	'prop'		=> 'width',
	'unit'		=> '%',
) );

?>

<?php if (!empty($settings->link_color)) : ?>
.fl-node-<?php echo $id ?> .component_z_pattern_v2 .safety-container .text-container .icon-link {
    color: #<?php echo $settings->link_color ?>;
}    
<?php endif; ?>

.fl-node-<?php echo $id ?> .component_z_pattern_v2 .safety-container .text-container {
    <?php if (!empty($settings->bg_colour)) : ?>
        background-color: <?php echo $settings->bg_colour ?>;
    <?php endif; ?>
    <?php if (!empty($settings->text_padding)) : ?>
        padding: <?php echo $settings->text_padding ?>px;
    <?php endif; ?>
}

<?php if (isset($textboxTheme->default_colour)) : ?>
	.fl-node-<?php echo $id ?> .component_z_pattern_v2 .safety-container .text-container {
		background: <?php echo $textboxTheme->default_colour; ?>;
	}
	.fl-node-<?php echo $id ?> .component_z_pattern_v2 .safety-container .text-container .display-table .display-cell  p{
		color: <?php echo $textboxTheme->text_colour; ?>;
	}

<?php endif; ?>

<?php
FLBuilderCSS::typography_field_rule(array(
    'settings'    => $settings,
    'setting_name'     => 'content_typography',
    'selector'     => 'body .fl-node-' . $id . ' .safety-container .text-container p',
));

?>

