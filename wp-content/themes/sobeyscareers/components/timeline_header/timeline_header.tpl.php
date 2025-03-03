<div <?php $this->component_class($classes) ?> data-js-timeline_header <?php echo $data; ?>>
	<div class="timeline-header-container">
		<div class="timeline-header-inner-container">
			<div class="timeline-header-content">
				<?php if (isset($atts['timeline_brand_img'])) : ?>
					<div class="timeline-icon"><img src="<?php echo $atts['timeline_brand_img'];?>" alt="" width="115" height="150" /></div>
				<?php endif; ?>
				<?php if (!empty($timeline_heading)) : ?>
					<<?php echo $timeline_heading_tag ?> class="timeline-header"><?php esc_attr_e($timeline_heading); ?></<?php echo $timeline_heading_tag ?>>
				<?php endif; ?>
				<?php if (!empty($timeline_content)) : ?>
					<div class="text-box"><?php echo $timeline_content;?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="timeline-scoll-container clearfix">
		<button aria-label="<?php _e('Scroll down to the timeline', 'fp');?>" data-scroll="<?php echo $timeline_header_scroll;?>">
			<span></span>
		</button>
	</div>
</div>