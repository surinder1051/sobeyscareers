<?php if (!empty($settings->title_font_size)) : ?>
    .fl-node-<?php echo $id; ?> .title {
        font-size: <?php echo $settings->title_font_size; ?>px;
    }

    <?php
        FLBuilderCSS::responsive_rule( array(
            'settings'	=> $settings,
            'setting_name'	=> 'title_font_size', // As in $settings->align.
            'selector'	=> ".fl-node-$id .title",
            'prop'		=> 'font-size',
            'unit'		=> 'px',
        ) );
    endif;
?>

<?php if (isset($settings->content_background)) : ?>
    .fl-node-<?php echo $id;?> .component_fp_downloads {
        background: #<?php echo $settings->content_background;?>;
    }
<?php endif;
