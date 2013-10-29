<h3><?php echo lang('estate_group_list');?></h3>

<?php echo form_open('admin/estate/group/delete'); ?>
	<table border="0" class="table-list">
		<thead>
		<tr>
			<th style="width: 20px;"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
			<th><?php echo lang('estate_admin_title');?></th>
            <th><?php echo lang('estate_admin_slug');?></th>
            <th><?php echo lang('estate_admin_disc');?></th>
            <th><?php echo lang('estate_admin_groupimg');?></th>
			<th style="width:10em"><span><?php echo lang('estate_admin_action');?></span></th>
		</tr>
		</thead>
		<tbody>
		<?php if ($groups): ?>
			<?php foreach ($groups as $group): ?>
			<tr>
				<td><?php echo form_checkbox('action_to[]', $group->id); ?></td>
				<td><?php echo $group->title;?></td>
                <td><?php echo $group->slug;?></td>
                <td><?php echo character_limiter(strip_tags($group->body),100);?></td>
                <td>
					<?php if ($group->file_id == false) :?>
					No
					<?php else: ?>
					Yes
					<?php endif; ?>
				</td>
				<td>
					<?php echo anchor('admin/estate/group/edit/' . $group->id, lang('estate_admin_edit')) . ' | '; ?>
					<?php echo anchor('admin/estate/group/delete/' . $group->id, lang('estate_admin_delete'), array('class'=>'confirm'));?>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="6"><h3><?php echo lang('estate_group_empty');?></h3></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<div class="buttons float-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
	</div>
<?php echo form_close(); ?>