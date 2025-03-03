<?php

if (isset($settings->search_icon_colour) && !empty($settings->search_icon_colour)) :

?>
	.fl-node-<?php echo $id; ?> .component_header_logo_search.-expand-above .search-col .search-btn .icon-search,
	.fl-node-<?php echo $id; ?> .component_header_logo_search.-expand-above .search-col .search-btn .icon-search::before,
	.fl-node-<?php echo $id; ?> .component_header_logo_search .search-col .header-search .search-btn span:before,
	.fl-node-<?php echo $id; ?> .component_header_logo_search button.mob_search_toggle {
		color: #<?php echo $settings->search_icon_colour; ?>;
	}
	.fl-node-<?php echo $id; ?> .component_header_logo_search button.mob_menu_toggle span,
	.fl-node-<?php echo $id; ?> .component_header_logo_search button.mob_menu_toggle span::before,
	.fl-node-<?php echo $id; ?> .component_header_logo_search button.mob_menu_toggle span::after {
		background: #<?php echo $settings->search_icon_colour; ?>;
	}
	.fl-node-<?php echo $id; ?> .component_header_logo_search button.mob_search_toggle:hover,
	.fl-node-<?php echo $id; ?> .component_header_logo_search button.mob_menu_toggle:hover {
		border: none;
		background-color: transparent;
		color: #<?php echo $settings->search_icon_colour; ?>;
	}
<?php
endif;
