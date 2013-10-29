<?php if ($this->method == 'create'): ?>
	<h3><?= lang('estate_prop_create');?></h3>
<?php else: ?>
	<h3><?= sprintf(lang('estate_message_group_edit'), $post->title); ?></h3>
<?php endif; ?>

<?= form_open(uri_string(), 'class="crud"'); ?>
<div class="tabs">

	<ul class="tab-menu">
		<li><a href="#page-content"><span><?= lang('estate_admin_content');?></span></a></li>
		<li><a href="#page-details"><span><?= lang('estate_admin_details');?></span></a></li>
        <li><a class="map-tab" href="#page-location"><span><?= lang('estate_admin_location');?></span></a></li>
        <li><a href="#page-images"><span><?= lang('estate_admin_gallery');?></span></a></li>
	</ul>
    <div id="page-content">
        <fieldset>
            <ul>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <?= form_label(lang('estate_admin_title'), 'title'); ?>
                    <?= form_input('title', $post->title, 'maxlength="50"'); ?>
                    <span class="required-icon tooltip"><?= lang('required_label'); ?></span>
                </li>
        
                <li class="<?php echo alternator('', 'even'); ?>">
                    <?= form_label(lang('estate_admin_slug'), 'slug'); ?>
                    <?= form_input('slug', $post->slug, 'maxlength="50"'); ?>
                    <span class="required-icon tooltip"><?= lang('required_label'); ?></span>
                </li>
				<li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_listing_status'), 'listing_status'); ?>                  
					<?= form_dropdown('listing_status', $estate_status, $post->listing_status); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
					<?= form_label(lang('estate_admin_group'), 'group_id'); ?>
					<?= form_dropdown('group_id', $estate_groups, $post->group_id); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <?= form_label(lang('estate_admin_intro'), 'intro'); ?>
                    <br class="clear-both"/>
					<?= form_textarea(array('id'=>'intro', 'name'=>'intro','value' => $post->intro, 'class'=>'wysiwyg-simple')); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <?= form_label(lang('estate_admin_disc'), 'body'); ?>
                    <br class="clear-both"/>
					<?= form_textarea(array('id'=>'body', 'name'=>'body','value' => $post->body, 'class'=>'wysiwyg-advanced')); ?>
                </li>
            </ul>
        </fieldset>
    </div>
    <div id="page-details">
        <fieldset>
            <ul>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <?= form_label(lang('estate_prop_listing_id'), 'listing_id'); ?>
					<?= form_input('listing_id', $post->listing_id, 'maxlength="50"'); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_listing_type'), 'listing_type'); ?>                  
					<?= form_dropdown('listing_type', $estate_type, $post->listing_type); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <?= form_label(lang('estate_prop_listing_price') .' '. $currency, 'listing_price'); ?>
                    <?= form_input('listing_price', $post->listing_price, 'class="width-10" maxlength="11"'); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_listing_size'), 'listing_size'); ?>
                    <?= form_input('listing_size', $post->listing_size, 'class="width-10" maxlength="11"'); ?>
					<?php
						for ($i=0; $i<count($estate_measure); $i++)
						{
							$x = FALSE;
							if($post->listing_size_m == $i) $x = TRUE;
							echo form_radio('listing_size_m', $i, $x).$estate_measure[$i];
						}
					?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_listing_lot_size'), 'listing_lot_size'); ?>
                    <?= form_input('listing_lot_size', $post->listing_lot_size, 'class="width-10" maxlength="11"'); ?>
					<?php
						for ($i=0; $i<count($estate_measure); $i++)
						{
							$x = FALSE;
							if($post->listing_lot_size_m == $i) $x = TRUE;
							echo form_radio('listing_lot_size_m', $i, $x).$estate_measure[$i];
						}
					?>
                </li>
            </ul>
       	</fieldset>
    </div>
    <div id="page-location">
        <fieldset>
            <ul>
                <li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_address_1'), 'address_1'); ?>
                    <?= form_input('address_1', $post->address_1, 'maxlength="150" class="width-25"'); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_address_2'), 'address_2'); ?>
                    <?= form_input('address_2', $post->address_2, 'maxlength="150" class="width-25"'); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_city'), 'city'); ?>
                    <?= form_input('city', $post->city, 'maxlength="30" class="width-15"'); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_state'), 'state'); ?>
					<?= form_dropdown('state',$estate_state, $post->state); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_zip'), 'zip'); ?>
                    <?= form_input('zip', $post->zip, 'maxlength="5" class="width-5"'); ?>
                </li>
            </ul>
            <hr />
            <h4><?= lang('estate_prop_mapclient'); ?></h4>
            <ul>
            	<li class="<?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_listing_map_marker'), 'listing_map_marker'); ?>
                    <?= form_dropdown('listing_map_marker', $estate_map_marker , $post->listing_map_marker); ?>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <?= form_hidden('listing_lat', $post->listing_lat); ?>
                    <?= form_hidden('listing_lng', $post->listing_lng); ?>
                    <div id="map_canvas" style="width:100%; height:350px;"></div>
                </li>
            </ul>
       	</fieldset>
    </div>
    <div id="page-images">
        <fieldset>
            <ul>
            	<li class="<?php echo alternator('', 'even'); ?>">
					<?= form_label(lang('estate_prop_folder'), 'folder_id'); ?>
					<?= form_dropdown('folder_id', $folder_options, $post->folder_id, 'id="folder_id"'); ?>
                </li>
                <li class="thumbnail-manage <?php echo alternator('', 'even'); ?>">
                	<?= form_label(lang('estate_prop_thumbnail'), 'thumbnail_id'); ?>
					<select name="thumbnail_id" id="thumbnail_id">

						<?php if ( ! empty($post->thumbnail_id) ): ?>
						<!-- Current thumbnail -->
						<optgroup label="Current">
							<?php foreach ( $estate_images as $image ): if ( $image->file_id == $post->thumbnail_id ): ?>
							<option value="<?= $post->thumbnail_id; ?>">
								<?= $image->name; ?>
							</option>
							<?php break; endif; endforeach; ?>
						</optgroup>
						<?php endif; ?>

						<!-- Available thumbnails -->
						<optgroup label="Thumbnails">
							<option value="0"><?php echo lang('estate_prop_no_thumb'); ?></option>
							<?php foreach ( $estate_images as $image ): ?>
							<option value="<?php echo $image->file_id; ?>">
								<?php echo $image->name; ?>
							</option>
							<?php endforeach; ?>
						</optgroup>

					</select>
					
					<?php /*?><?= form_dropdown('thumbnail_id', $estate_groups, $post->thumbnail_id); ?><?php */?>
                </li>
                <li class="images-manage <?php echo alternator('', 'even'); ?>">
                    <?= form_label(lang('estate_prop_images')); ?>
                    <br class="clear-both"/>
                    <ul id="estate_images_list">
						<?php if ( $estate_images !== FALSE ): ?>
                        <?php foreach ( $estate_images as $image ): ?>
                        <li>
                            <a href="<?= base_url() . 'uploads/files/' . $image->filename; ?>" class="modal">
                                <?= img(array('src' => base_url() . 'files/thumb/' . $image->file_id, 'alt' => $image->name, 'title' => 'File: ' . $image->filename . ' Title: ' . $image->name)); ?>
                                <?= form_hidden('action_to[]', $image->id); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <li>
                            <?= lang('estate_prop_no_images'); ?>
                        </li>
                        <?php endif; ?>
                    </ul>
					<div class="clear-both"></div>
                </li>
				<li style="display: none;" class="images-placeholder">
					<strong><?php echo lang('estate_gallery_preview'); ?></strong>
					<div class="clear-both"></div>
					<ul id="estate_images_list">

					</ul>
					<div class="clear-both"></div>
				</li>
            </ul>
       	</fieldset>
    </div>    
</div>

<div class="buttons float-right padding-top">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel') )); ?>
</div>

<?= form_close(); ?>
