<h3><?= lang('estate_prop_list');?></h3>
<?= form_open('admin/estate/delete'); ?>
	<table border="0" class="table-list">
		<thead>
		<tr>
			<th style="width: 20px;"><?= form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
			<th><?= lang('estate_admin_title');?></th>
            <th><?= lang('estate_admin_slug');?></th>
            <th><?= lang('estate_admin_group');?></th>
            <th><?= lang('estate_admin_stat');?></th>
			<th><?= lang('estate_admin_img');?></th>
			<th style="width:10em"><span><?= lang('estate_admin_action');?></span></th>
		</tr>
		</thead>
		<tbody>
		<?php if ($estates): ?>
			<?php foreach ($estates as $estate): ?>
			<tr>
				<td><?= form_checkbox('action_to[]', $estate->id); ?></td>
				<td><?= $estate->title;?></td>
                <td><?= $estate->slug;?></td>
                <td><?= $estate_groups[$estate->group_id];?></td>
                <td><?= $estate_status[$estate->listing_status];?></td>
				<td><?= $estate->image_count ?></td>
				<td>
					<?= anchor('admin/estate/edit/' . $estate->id, lang('estate_admin_edit')) . ' | '; ?>
					<?= anchor('admin/estate/delete/' . $estate->id, lang('estate_admin_delete'), array('class'=>'confirm'));?>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="6"><h3><?= lang('estate_prop_empty');?></h3></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<div class="buttons float-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
	</div>
<?= form_close(); ?>