<?php
// var_dump($header_logo);
// $logo = (isset($header_logo) && !empty($header_logo)) ? $header_logo : get_field('logo', 'options');
?>
<div <?php $this->component_class("header_section") ?> data-js-search_bar>
	<button class="mob_search_toggle" data-js-mob_search_toggle role="button" aria-label='<?php _e('Click to access site search form', FP_TD); ?>'>
		<span class='<?php echo $search_button_icon; ?>'></span>
	</button>
	<div class="search-col">
		<form id='search' class="header-search" action='/<?php echo (function_exists('pll_current_language') ? pll_current_language() : '' ) ?>/'>
			<label for="headerSearch_s" class="screen-reader-text">
				<?php echo __('Enter search keywords or phrase', FP_TD); ?>
			</label>
			<input type="hidden" name='s' aria-label="<?php echo __('Search', FP_TD); ?>" <?php echo (isset($_GET['s']) ? "value='" . $_GET['s'] . "'" : '') ?>>
			<input class="form-control mr-sm-2 search-input" id="headerSearch_s" type="search" name='fwp_keyword_search' <?php echo (isset($_GET['s']) ? "value='" . $_GET['s'] . "'" : '') ?> placeholder="<?php echo __('Search', FP_TD); ?>" aria-label="<?php echo __('Enter search text', FP_TD); ?>">
			<button class="search-btn" type="submit" aria-label="<?php echo __('Submit Search', FP_TD); ?>">
				<span class='<?php echo $search_button_icon; ?>'></span>
				<span class="screen-reader-text">
					<?php echo __('Submit'); ?>
				</span>
			</button>
		</form>
	</div>
</div>