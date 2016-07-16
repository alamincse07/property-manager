<?php echo $header;
echo $sidebar ?>		
<div class="span10 content">
<?php echo $hugemenu ?>
    <h1><?php echo ($name['value']) ? lang('estate_lanlord_update_head') : lang('estate_lanlord_add_head') ;?></h1>

<?php if ($message) { ?>
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $message; ?>
        </div>
    <?php } ?>
<?php if ($success) { ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $success; ?>
        </div>
<?php } ?>
    <div class="alert alert-info"><?php echo lang('estate_lanlord_add_message'); ?></div>

    <div class="contentArea">
        <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo lang('lanlord_add_h2_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
<?php echo form_open(current_url(), array('class' => 'form-horizontal')); ?>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('estate_lanlord_add_name', 'name'); ?></label>
							<div class="controls">
                                <?php $name['class'] = 'span12';
                                echo form_input($name);
                                ?>
                            </div><br>
							<label class="control-label"><?php echo lang('estate_lanlord_add_phone_number', 'phone number'); ?></label>
                            <div class="controls">
                                <?php $phone_number['class'] = 'span12';
                                echo form_input($phone_number);
                                ?>
                            </div><br>
							<label class="control-label"><?php echo lang('estate_lanlord_add_email', 'email'); ?></label>
							<div class="controls">
                                <?php $email['class'] = 'span12';
                                echo form_input($email);
                                ?>
                            </div><br>
                            <label class="control-label"><?php echo lang('estate_lanlord_add_fax', 'fax'); ?></label>
                            <div class="controls">
                                <?php $fax['class'] = 'span12';
                                echo form_input($fax);
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"></label>
                            <div class="controls">
<?php echo form_submit('submit',($name['value']) ? lang('estate_lanlord_update_head') : lang('estate_lanlord_add_head'), 'class="btn btn-info span6"'); ?>
                            </div>
                        </div>          
                    </div>
                </div>
            </div>
        </div>
<?php echo form_close(); ?>	
    </div>

</div>
<!--/content-->

<?php echo $footer ?>