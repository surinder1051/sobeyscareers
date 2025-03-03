/**
* This file should contain frontend styles that
* will be applied to individual module instances.
*
* You have access to three variables in this file:
*
* $module An instance of your module class.
* $id The module's ID.
* $settings The module's settings.
*
* Note: When used from beaver builder, a cached version of this file will be
* crated that's unique to the instance in the /uploads/bb-plugin/cache/
* ,however when used by a regular shortcode an inline style will in turn be
* generated and put on the page where it's been used, no cached file will be
* created.
*
* Example:
*/

<?php
// fp_apply_style($id, '.card-title', 'color', $settings->title_color);
FLBuilderCSS::dimension_field_rule( array(
	'settings'    => $settings,
	'setting_name'    => 'dd_menu_padding',
	'selector'    => ".fl-node-$id .component_menu_dropdown .menu-items .menu-item .menu-dropdown-content",
	'unit'        => 'px', // Omit if custom unit select is used.
	'props'       => array(
		'padding-top'    => 'dd_menu_padding_top', // As in $settings->padding_top
		'padding-right'  => 'dd_menu_padding_right',
		'padding-bottom' => 'dd_menu_padding_bottom',
		'padding-left'   => 'dd_menu_padding_left',
	),
) );

if (!empty($settings->menu_items) && count($settings->menu_items) > 0) :
	foreach($settings->menu_items as $index => $item) :
		foreach($item->dropdown_items as $dindex => $di) :
			$data = json_decode($di);
?>
		.fl-node-<?php echo $id;?> .component_menu_dropdown .menu-items .menu-item .dropdown-list .item#menuItem-<?php echo $id;?>-<?php echo ($index + $dindex);?> {
			<?php if (!empty($data->store_image_padding_bottom)) : ?>
				padding-bottom: <?php echo $data->store_image_padding_bottom; ?>px;
			<?php endif; ?>
			<?php if (!empty($data->store_image_padding_left)) : ?>
				padding-left: <?php echo $data->store_image_padding_left; ?>px;
			<?php endif; ?>
			<?php if (!empty($data->store_image_padding_right) ) : ?>
				padding-right: <?php echo $data->store_image_padding_right; ?>px;
			<?php endif; ?>
			<?php if (!empty($data->store_image_padding_top)) : ?>
				padding-top: <?php echo $data->store_image_padding_top; ?>px;
			<?php endif; ?>
		}
<?php 	if (!empty($data->store_image_width)) : ?>
		.fl-node-<?php echo $id;?> .component_menu_dropdown .menu-items .menu-item .dropdown-list .item#menuItem-<?php echo $id;?>-<?php echo ($index + $dindex);?> img {
			height: auto;
			width: <?php echo $data->store_image_width; ?>px;
		}
<?php endif; ?>
		@media screen and (max-width: 768px) {
			.fl-node-<?php echo $id;?> .component_menu_dropdown .menu-items .menu-item .dropdown-list .item#menuItem-<?php echo $id;?>-<?php echo ($index + $dindex);?> {
				<?php if (!empty($data->store_image_padding_bottom_medium)) : ?>
					padding-bottom: <?php echo $data->store_image_padding_bottom_medium; ?>px;
				<?php endif; ?>
				<?php if (!empty($data->store_image_padding_left_medium)) : ?>
					padding-left: <?php echo $data->store_image_padding_left_medium; ?>px;
				<?php endif; ?>
				<?php if (!empty($data->store_image_padding_right_medium) ) : ?>
					padding-right: <?php echo $data->store_image_padding_right_medium; ?>px;
				<?php endif; ?>
				<?php if (!empty($data->store_image_padding_top_medium)) : ?>
					padding-top: <?php echo $data->store_image_padding_top_medium; ?>px;
				<?php endif; ?>
			}
	<?php 	if (!empty($data->store_image_width_medium)) : ?>
				.fl-node-<?php echo $id;?> .component_menu_dropdown .menu-items .menu-item .dropdown-list .item#menuItem-<?php echo $id;?>-<?php echo ($index + $dindex);?> img {
					height: auto;
					width: <?php echo $data->store_image_width_medium; ?>px;
				}
	<?php endif; ?>
		}
		@media screen and (max-width: 576px) {
			.fl-node-<?php echo $id;?> .component_menu_dropdown .menu-items .menu-item .dropdown-list .item#menuItem-<?php echo $id;?>-<?php echo ($index + $dindex);?> {
				<?php if (!empty($data->store_image_padding_bottom_responsive)) : ?>
					padding-bottom: <?php echo $data->store_image_padding_bottom_responsive; ?>px;
				<?php endif; ?>
				<?php if (!empty($data->store_image_padding_left_responsive)) : ?>
					padding-left: <?php echo $data->store_image_padding_left_responsive; ?>px;
				<?php endif; ?>
				<?php if (!empty($data->store_image_padding_right_responsive) ) : ?>
					padding-right: <?php echo $data->store_image_padding_right_responsive; ?>px;
				<?php endif; ?>
				<?php if (!empty($data->store_image_padding_top_responsive)) : ?>
					padding-top: <?php echo $data->store_image_padding_top_responsive; ?>px;
				<?php endif; ?>
			}
		<?php 	if (!empty($data->store_image_width_responsive)) : ?>
				.fl-node-<?php echo $id;?> .component_menu_dropdown .menu-items .menu-item .dropdown-list .item#menuItem-<?php echo $id;?>-<?php echo ($index + $dindex);?> img {
					height: auto;
					width: <?php echo $data->store_image_width_responsive; ?>px;
				}
		<?php endif; ?>
		}
<?php

		endforeach;
	endforeach;
endif;
