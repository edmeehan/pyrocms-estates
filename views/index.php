<h2><?= lang('estate_default_header'); ?></h2>
<!-- Div containing all estate groups - default view -->
<div class="estate_list">
	<?php if ( ! empty($estate_groups)): foreach ($estate_groups as $estate_group): ?>
	<div class="estate_group_item">
		<!-- Heading -->
		<h3><?= anchor('/estate/' . $estate_group->slug, $estate_group->title); ?></h3>
		<?php if ( $estate_group->file_id != 0): ?>
			<?= anchor('/estate/' . $estate_group->slug, img(array('src' => site_url() . 'files/thumb/' . $estate_group->file_id . '/150/150', 'alt' => $estate_group->title, 'class' => 'primary_image')));  ?>
		<?php endif; ?>
		<div class="estate_body"><?= $estate_group->body ?></div>
		<hr />
	</div>
	<?php endforeach; else: ?>
	<p><?= lang('estate_default_empty'); ?></p>
	<?php endif; ?>
</div>