<div class="component_<?php esc_attr_e($this->component); ?> <?php echo $atts['classes']; ?>" role="region" aria-labelledby="factsHeading-<?php echo $node_id; ?>">
	<div class="safety-container">
		<div class="sm-standerd-container">
			<?php if (!empty($atts['heading'])) : ?>
				<<?php echo $heading_tag ?> id="factsHeading-<?php echo $node_id; ?>"><?php echo $atts['heading']; ?></<?php echo $heading_tag ?>>
			<?php endif; ?>
			<div class="facts-main">
				<?php
				$facts = $atts["facts"];
				foreach ($facts as $key => $fact) : ?>
					<div class="fact-box accent-bar-center">
						<?php if (!empty($fact->fact_heading)) : ?>
							<<?php echo $fact_heading_tag ?> class="heading"><?php echo $fact->fact_heading; ?>
						<?php endif; ?>
						<?php if (!empty($fact->fact_sub_heading)) : ?>
								<span><?php echo $fact->fact_sub_heading; ?></span>
						<?php endif; ?>
						<?php if (!empty($fact->fact_heading)) : ?>
							</<?php echo $fact_heading_tag ?>>
						<?php endif; ?>
						<?php if (!empty($fact->fact_content)) : ?>
							<div class="content field_editor"><?php echo $fact->fact_content; ?></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>