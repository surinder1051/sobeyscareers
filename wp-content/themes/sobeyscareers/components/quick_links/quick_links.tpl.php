<div <?php $this->component_class() ?> data-js-quick_links>
	<div class="quicklinks-inner">
		<span class="quicklinks-label"><?php echo $fp_quick_link_title; ?> &mdash;</span>
		<ul>
			<?php foreach ($fp_quick_link_item as $qlIndex => $item) : ?>
				<li>
					<a href="<?php echo $item->link_url; ?>" target="_self"><?php echo trim($item->link_label); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>