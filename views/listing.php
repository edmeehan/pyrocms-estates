<h2><?= sprintf(lang('estate_listing_header'), $group->title); ?></h2>
<!-- Div containing google map -->
<div id="map_canvas" class="map_canvas"></div>
<!-- Div containing all estate lists -->
<div class="estate_list">
	<?php if ( ! empty($estates)): foreach ($estates as $estate): ?>
	<div class="estate_list_item">
		<!-- Heading -->
		<h3><?= anchor('/estate/' . $estate->group_slug . '/' . $estate->slug, $estate->title); ?></h3>
		<?php if ( $estate->thumbnail_id != 0): ?>
			<?= anchor('/estate/' . $estate->group_slug . '/' . $estate->slug, img(array('src' => site_url() . 'files/thumb/' . $estate->thumbnail_id . '/150/150', 'alt' => $estate->title, 'class' => 'primary_image')));  ?>
		<?php endif; ?>
		<div class="estate_intro"><?= $estate->intro ?></div>
		<table>
			<tr><td><?= lang('estate_detail_price_label'); ?>:</td><td><?= $currency . number_format($estate->listing_price,2); ?></td></tr>
			<tr><td><?= lang('estate_id_label'); ?>:</td><td><?= $estate->listing_id ?></td></tr>
		</table>
		<hr style="clear:both" />
	</div>
	<?php endforeach; else: ?>
	<p><?= lang('estate_default_empty'); ?></p>
	<?php endif; ?>
</div>