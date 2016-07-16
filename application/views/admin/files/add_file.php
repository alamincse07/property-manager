<?php
echo $header; ?>
<?php echo $sidebar; ?>

<div class="span10 content">

    <?php echo $hugemenu; ?>
    <form class="form-horizontal" action="<?= site_url() ?>admin/files/upload_file" id="upload_file" method="post"
          enctype="multipart/form-data">

        <div class="control-group">
            <label class="control-label" for="form-field-1">File Name</label>

            <div class="controls">
                <input type="text" class="span6" name="file_name" id="form-field-1" required="required"
                       placeholder="Sample Document">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="form-field-1"><?= lang('file') ?></label>

            <div class="controls">
                <div class="ace-file-input span6">
                    <input type="file" name="userfile" id="id-input-file-1">
                    <a class="remove" href="#"><i class="icon-remove"></i></a></div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="form-field-1"><?php echo lang('memberships') ?> </label>

            <div class="controls">
                <select name="memberships[]" multiple="multiple" class="multiple" required="required"
                        title="Click to Select a Label" style="width: 550px;">
                    <?php
                    $memberships = $this->files_m->memberships();
                    ?>
                    <?php foreach ($memberships as $membership): ?>
                        <option value="<?= $membership['m_id']; ?>"><?= $membership['membership'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="form-field-1"><?= lang('private') ?> </label>

            <div class="controls">
                <label><input name="private" class="ace-switch ace-switch-5" type="checkbox"><span
                        class="lbl"></span></label>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="form-field-1"><?= lang('notes') ?></label>

            <div class="controls">
				<span class="span6 input-icon input-icon-right">
											<textarea name="notes" class="span12" id="form-field-8">No
                                                notes..</textarea>
										</span>

            </div>
        </div>
        <div class="form-actions">

            <button class="btn btn-info" type="submit"><i class="icon-upload"></i> <?= lang('upload') ?></button>
            &nbsp; &nbsp; &nbsp;
            <button class="btn" type="reset"><i class="icon-undo"></i> <?= lang('reset') ?></button>
        </div>
    </form>
    <a style="color:#fff;" href='<?= site_url() ?>admin/files'>
        <button class="btn btn-danger">
            <i class="icon icon-folder-open"></i> <?= lang('files') ?></button>
    </a>

</div> <!-- .content -->

<?php echo $footer; ?>
				