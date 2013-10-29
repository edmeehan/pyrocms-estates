<h2><?= sprintf(lang('estate_detail_header'), $estate->title); ?></h2>
<!-- Div containing google map -->
<div id="map_canvas" class="map_canvas"></div>
<!-- Div containing all estate details -->
<div  class="estate_detail">
	<?php if ( $estate->thumbnail_id != 0): ?>
		<?= img(array('src' => site_url() . 'files/thumb/' . $estate->thumbnail_id . '/250/250', 'alt' => $estate_images[0]->name, 'class' => 'primary_image')); ?>
	<?php endif; ?>
	<div class="estate_body"><?= $estate->body ?></div>
	<hr style=" clear:both;" />
	<div class="estate_address">
		<h6><?= lang('estate_address_label'); ?></h6>
		<p>
			<?= $estate->address_1 ?><br />
			<?php if ($estate->address_2 != ''): ?>
				<?= $estate->address_2 ?><br />
			<?php endif; ?>
			<?= $estate->city ?>,<?= $estate->state ?> <?= $estate->zip ?>
		</p>
	</div>
	<div class="estate_id">
		<h6><?= lang('estate_id_label'); ?></h6>
		<p><?= $estate->listing_id ?></p>
	</div>
	<div class="estate_details">
		<h6><?= lang('estate_detail_label'); ?></h6>
		<table>
			<tr><td><?= lang('estate_detail_price_label'); ?>:</td><td><?= $currency . number_format($estate->listing_price,2); ?></td></tr>
			<tr><td><?= lang('estate_detail_size_label'); ?>:</td><td><?= $estate->listing_size ?> <?= $measure_units[$estate->listing_size_m] ?></td></tr>
			<tr><td><?= lang('estate_detail_lotsize_label'); ?>:</td><td><?= $estate->listing_lot_size ?> <?= $measure_units[$estate->listing_lot_size_m] ?></td></tr>
			<? if ($estate->listing_type): ?>
			<tr><td><?= lang('estate_detail_type_label'); ?>:</td><td><?= $estate_type[$estate->listing_type] ?></td></tr>
			<?php endif; ?>
		</table>
	</div>
	<div class="estate_images">
		<h6><?= lang('estate_image_label'); ?></h6>
		<?php if ($estate_images): ?>
		<ul class="estate_image_list">
		<?php foreach ( $estate_images as $image): ?>
		<li>
				<a href="<?php echo base_url().'uploads/files/' . $image->filename; ?>" class="gallery-image" data-src="<?php echo base_url().'uploads/files/' . $image->filename; ?>" title="<?php echo $image->name; ?>">
				<?php echo img(array('src' => site_url() . 'files/thumb/' . $image->file_id . '/100/100', 'alt' => $image->name)); ?>
				</a>
		</li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</div>