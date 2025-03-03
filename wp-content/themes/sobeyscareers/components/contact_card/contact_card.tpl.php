<div class="item-location_content mob_bottom_border">

	<?php if (!empty($title)) : ?>
		<<?php echo $title_tag ?> class="title"><?php esc_attr_e($title); ?></<?php echo $title_tag ?>>
	<?php endif; ?>

	<?php if (!empty($title2)) : ?>
		<<?php echo $title_tag ?> class="title2"><?php esc_attr_e($title2); ?></<?php echo $title_tag ?>>
	<?php endif; ?>

	<div class="item-location_summary">

		<?php if (!empty($contacts) && is_array($contacts) && count($contacts) > 0) : ?>
			<?php foreach ($contacts as $contact) : ?>
				<h4><?php //echo $contact['location'] 
					?></h4>
				<p class="manager_name"><?php //echo $contact['manager_name'] 
										?></p>
				<p class="phone"><?php //echo $contact['phone'] 
									?></p>
				<p class="mail"><?php //echo $contact['email'] 
								?></p>
			<?php endforeach; ?>
		<?php endif; ?>

	</div>
</div>