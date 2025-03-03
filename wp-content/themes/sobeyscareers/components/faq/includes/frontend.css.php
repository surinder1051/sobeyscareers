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
if (!empty($settings->active_color)) : ?>
.fl-node-<?php echo $id; ?> .category-list li.-active, .fl-node-<?php echo $id; ?> .category-list li:hover
{
	color: #<?php echo $settings->active_color; ?>;
}
<?php endif; ?>

<?php if (!empty($settings->icon_color)) : ?>
.fl-node-<?php echo $id; ?> .card-header span.-opened, .fl-node-<?php echo $id; ?> .card-header span.-closed
{
	color: #<?php echo $settings->icon_color; ?>;
}
<?php endif; ?>

