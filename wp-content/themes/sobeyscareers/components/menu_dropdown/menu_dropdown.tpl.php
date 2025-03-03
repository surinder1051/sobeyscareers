<div <?php $this->component_class() ?> data-js-menu_dropdown>
	<div class="menu-items" role="navigation" aria-label="<?php echo $menu_name; ?>">
	<?php foreach($menu_items as $index => $menu_item) : ?>
		<div class="menu-item" role="button" tabindex="0" aria-controls="<?php echo "{$node_id}-{$index}";?>" aria-label="<?php _e("Click to view {$menu_item->menu_text} links", FP_TD);?>">
			<span class="menu-item-text"><?php echo $menu_item->menu_text ?><span class="dropdown-icon button-icon-right fas-icon <?php echo $dropdown_icon ?>"></span></span>

			<div class="menu-dropdown-content" id="<?php echo "{$node_id}-{$index}";?>" aria-hidden="true">
				<?php if (!empty($menu_item->title)) : ?>
				<p class="dropdown-text"><?php echo $menu_item->title; ?></p>
				<?php endif; ?>
				<div class="dropdown-list">
				<?php foreach($menu_item->dropdown_items as $dindex => $dropdown_item) : ?>
                    <?php
                        $dropdown_item = is_object($dropdown_item) ? $dropdown_item : json_decode($dropdown_item);
                        $dropdown_aria = (isset($dropdown_item->link_title) && !empty($dropdown_item->link_title) ) ? $dropdown_item->link_title : $alt_text = get_post_meta($dropdown_item->store_image, '_wp_attachment_image_alt', true);;
                        if ( $dropdown_item->link_target == '_blank') :
                            $dropdown_aria .= ' ' . __('Opens in a new window', FP_TD);
                        endif;
                    ?>
					<a class="item" target="<?php echo $dropdown_item->link_target; ?>" href="<?php echo $dropdown_item->link; ?>" aria-label="<?php echo $dropdown_aria;?>" id="menuItem-<?php echo $node_id; ?>-<?php echo ($index + $dindex); ?>">
					    <img src="<?php echo $dropdown_item->store_image_src; ?>" alt="" />
					</a>
				<?php endforeach; ?>
				</div>
			</div>

		</div>

	<?php endforeach; ?>
	</div>
</div>