<?php if ($this->method == 'create'): ?>
	<h3><?php echo lang('estate_group_create');?></h3>
<?php else: ?>
	<h3><?php echo sprintf(lang('estate_message_group_edit'), $post->title); ?></h3>
<?php endif; ?>

<?php echo form_open(uri_string(), 'class="crud"'); ?>
<fieldset>
		<ul>
			<li>
                <label for="title"><?php echo lang('estate_admin_title'); ?></label>
                <?php echo form_input('title', $post->title, 'class="width-15"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>
    
            <li class="even">
                <label for="slug"><?php echo lang('estate_admin_slug'); ?></label>
                <?php echo form_input('slug', $post->slug, 'class="width-15"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>
            <li>
            	<label for="file_id"><?php echo lang('estate_admin_groupimg'); ?></label>
                <select name="file_id">
					<?php if ( ! empty($post->file_id) ): ?>
					<!-- Current thumbnail -->
					<optgroup label="Current">
						<?php foreach ( $group_images as $image ): if ( $image->id == $post->file_id ): ?>
						<option value="<?= $post->file_id; ?>">
							<?= $image->filename; ?>
						</option>
						<?php break; endif; endforeach; ?>
					</optgroup>
					<?php endif; ?>

					<!-- Available thumbnails -->
					<optgroup label="Thumbnails">
						<option value="0"><?php echo lang('estate_prop_no_thumb'); ?></option>
						<?php foreach ( $group_images as $image ): ?>
						<option value="<?php echo $image->id; ?>">
							<?php echo $image->filename; ?>
						</option>
						<?php endforeach; ?>
					</optgroup>
				</select>
            </li>
			<li class="even">
				<label for="body"><?php echo lang('estate_admin_disc'); ?></label><br class="clear-both"/>
				<?php echo form_textarea(array('id'=>'body', 'name'=>'body', 'value' => $post->body, 'rows' => 20, 'class'=>'wysiwyg-simple')); ?>
			</li>
		</ul>

</fieldset>


<div class="buttons float-right padding-top">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel') )); ?>
</div>

<?php echo form_close(); ?>
