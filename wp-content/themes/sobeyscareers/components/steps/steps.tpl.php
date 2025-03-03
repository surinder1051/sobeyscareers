<div <?php $this->component_class() ?> data-js-steps>
	<?php if (!empty($title)) : ?>
		<<?php echo $title_tag ?> class="title text-<?php echo $title_align ?>"><?php esc_attr_e($title); ?></<?php echo $title_tag ?>>
	<?php endif; ?>

	<?php if (!empty($steps)) : ?>
		<table>
			<?php foreach ($steps as $index =>  $step) : ?>
				<tr>
					<td><b>Step <?php echo $index + 1 ?></b></td>
					<td><span><?php echo $step['step'] ?></span></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>

</div>