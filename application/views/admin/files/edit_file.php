<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>
    
<form class="form-horizontal" action="<?=site_url()?>admin/files/edit"  method="post" >
	<?php
	$file_info = $this->mdl_files->file_info($this->uri->segment(3));
	?>
	<input type="hidden" name="file_id" value="<?=$file_info['file_id'];?>" />
	<div class="control-group">
			<label class="control-label" for="form-field-1"><?=lang('file')?></label>
			<div class="controls">
				<input type="text" readonly="readonly" class="span6"  id="form-field-1" required="required" value="<?=$file_info['file'];?>">
				<span class="help-inline">You cannot edit this field</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="form-field-1"><?=lang('file')?> <?=lang('name')?></label>
			<div class="controls">
				<input type="text" class="span6" name="name" id="form-field-1" required="required" value="<?=$file_info['name'];?>">
				<span class="help-inline">Give the file a friendly name</span>
			</div>
		</div>
		
<div class="control-group">
			<label class="control-label" for="form-field-1"><?=lang('memberships')?> </label>
			<div class="controls">
					 <select name="memberships[]" multiple="multiple" class="multiple span6" required="required" title="Click to Select Package">
                            	<?php
                            	$memberships = $this->mdl_files->memberships();
								?>
								
                            <?php foreach ($memberships as $membership): ?>
                            	
                            <option value="<?=$membership['m_id']?>" <?php
						   foreach (unserialize($file_info['packages']) as $key => $value) {
                           if ($membership['m_id'] == $value) { ?>
                               selected = "selected"
                           <?php } else {
                               
                           }
						   } ?> ><?=$membership['membership']?></option>
                            <?php endforeach ?>
                            </select>
                            <span class="help-inline">The Membership category that can view the file</span>
				</div>
		</div>
	
	<div class="control-group">
			<label class="control-label" for="form-field-1"><?=lang('private')?> </label>
			<div class="controls">
				<label><input name="private" <?php
				if ($file_info['private'] == '1') { ?>
					checked = "checked"
				<?php }	?> class="ace-switch ace-switch-5" type="checkbox"><span class="lbl"></span></label>
				<span class="help-inline">Will the file be visible to others?</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="form-field-1"><?=lang('notes')?></label>
			<div class="controls">
				<span class="span6 input-icon input-icon-right">
											<textarea name="notes" class="span12" id="form-field-8"><?=$file_info['notes'];?></textarea>
										</span>
				
			</div>
		</div>
		<div class="form-actions">
		
			<button class="btn btn-info" type="submit"><i class="icon-upload"></i> <?=lang('save')?></button>
			&nbsp; &nbsp; &nbsp;
			<button class="btn" type="reset"><i class="icon-undo"></i> <?=lang('reset')?></button>
		</div>
	 </form>
	 <a style="color:#fff;" href='<?=site_url()?>admin/files'>
			<button class="btn btn-danger">
				<i class="icon icon-folder-open"></i> <?=lang('files')?></button>
				</a>
    
    </div> <!-- .content -->

<?php echo $footer; ?>
				