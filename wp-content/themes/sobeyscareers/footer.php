<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fp
 */

?>

</div><!-- #content -->

<footer class="site-footer">
	<?php if (!isset($post) || get_post_meta($post->ID, 'disable_default_header__footer', true) !== '1') : ?>
		<?php echo do_shortcode('[fl_builder_insert_layout slug="footer"]') ?>
	<?php endif ?>
</footer><!-- .site-footer container-->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>

</html>