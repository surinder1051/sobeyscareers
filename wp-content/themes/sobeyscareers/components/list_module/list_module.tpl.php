<div class="component_<?php esc_attr_e($this->component); ?> <?php echo $atts['classes'] ?>">
	<div class="safety-container">
		<div class="list-main">
		<?php if (!empty($atts['h2']) || !empty($atts['intro_content'] )) : ?>
			<div class="heading accent-bar-center">
				<?php if (!empty($atts['h2'])) : ?>
					<<?php echo $title_tag ?> class='text-<?php echo $atts['heading_alignment'] ?>'>
						<?php esc_attr_e($atts['h2']); ?>
					</<?php echo $title_tag ?>>
				<?php endif; ?>
				<?php if (!empty($atts['intro_content'])) : ?>
					<div class='field_editor'>
						<?php echo $atts['intro_content']; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
			<div class="list-content-container" <?php echo (isset($atts['mobile_format']) && $atts['mobile_format'] == 'dropdown') ? 'data-mobile-select="1"' : '' ?>>
				<div class="list-wrapper">
					<?php if (isset($atts['mobile_format']) && $atts['mobile_format'] == 'dropdown') : ?>
						<button type="button" role="select" aria-haspopup="listbox" aria-labelledby="button_<?php echo $node_id; ?> select_<?php echo $node_id; ?>" id="button_<?php echo $node_id; ?>" disabled="disabled"><?php echo $atts["list_items"][0]->item_title; ?></button>
					<?php endif	?>
					<<?php echo $atts['list_tag'] ?> class="list_content<?php if(isset($list_alignment) ) { echo ' align-'.$list_alignment;} if(isset($ol_list_style) ) { echo ' ' . $ol_list_style; } if(isset($ul_list_style) ) { echo ' ' . $ul_list_style;}?> " id="designedList-<?php echo $node_id; ?>">
						<?php foreach ($atts["list_items"] as $key => $item) : ?>
						<?php $className = ($item->item_url == get_permalink()) ? ' class="current-page"' : ''; ?>
							<?php if ($item->item_title) : ?>
								<li>
									<span role="presentation" aria-hidden="true"></span>
									<?php if ($item->item_url) : ?> <a <?php echo (isset($item->target) && ('new' == $item->target)) ? ' target="_blank" ' : '' ?> href="<?php echo $item->item_url ?>"<?php echo $className;?>> <?php endif; ?>
										<?php echo $item->item_title ?>
										<?php if ($item->item_url) : ?> </a> <?php endif; ?>
								</li>
							<?php endif; ?>
						<?php endforeach ?>
					</<?php echo $atts['list_tag'] ?>>
				</div>
			</div>
		</div>
	</div>
</div>
