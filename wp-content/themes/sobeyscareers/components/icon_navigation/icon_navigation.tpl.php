<?php if (isset($atts['fp_icon_nav_items']) && !empty($atts['fp_icon_nav_items'])) : ?>
	<div id="node-<?php echo $node_id; ?>" <?php $this->component_class(); ?> data-js-icon-navigation>
		<div class="icon-navigation-row display-table <?php echo $atts['grid_class']; ?>" data-breakpoint="<?php echo $atts['fp_icon_nav_breakpoint']; ?>" role="navigation">
			<?php foreach ($atts['fp_icon_nav_items'] as $index => $navItem) : ?>
				<div class="icon-nav-item item-<?php echo (1 + $index); ?>" id="iconNavItem-<?php echo $node_id; ?>-<?php echo $index; ?>">
					<a href="<?php echo $navItem->nav_item_link; ?>" <?php echo (str_replace(site_url(), '', $_SERVER['REQUEST_URI']) == str_replace(site_url(), '', $navItem->nav_item_link)) ? 'class="active"' : "" ?>>
						<span class="navicon <?php echo $navItem->nav_item_icon; ?>"></span>
						<span class="nav-label"><?php _e($navItem->nav_item_title); ?></span>
					</a>
				</div>
			<?php endforeach ?>
		</div>
	</div>
<?php elseif (isset($_GET['fl-builder'])) : ?>
	Add icon navigation items
<?php endif ?>