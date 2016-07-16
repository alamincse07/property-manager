<?php
echo $header;
echo $sidebar
?>		
<div class="span10 content">
    <?php echo $hugemenu ?>

    <h1><?php echo lang('set_email_title'); ?></h1>

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

    <div class="contentArea">
        <?php echo form_open(current_url(), array('class' => 'form-horizontal')); ?>
        <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo lang('set_email_widget_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_from_email', 'from_email'); ?></label>
                            <div class="controls">
                                <?php $from_email['class'] = 'span12'; echo form_input($from_email); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_from_name', 'from_name'); ?></label>
                            <div class="controls">
                                <?php $from_name['class'] = 'span12'; echo form_input($from_name); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_charset', 'mail_charset'); ?></label>
                            <div class="controls">
                                <?php $mail_charset['class'] = 'span12'; echo form_input($mail_charset); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_type', 'mail_encoding'); ?></label>
                            <div class="controls">
                                <?php $mail_encoding['class'] = 'span12'; echo form_input($mail_encoding); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_mail_type', 'mail_type'); ?></label>
                            <div class="controls">
                                <label for="mail_type"><?php $mail_type['class'] = 'span12'; echo form_radio($mail_type);?>smtp</label>
                                <label for="mail_type2"><?php $mail_type['class'] = 'span12'; echo form_radio($mail_type2); ?>mail</label>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"></label>
                            <div class="controls">
                                <?php echo form_submit('submit', lang('set_genaral_btn'), 'class="btn btn-info span6"'); ?>
                            </div>
                        </div>  
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_host', 'smtp_host'); ?></label>
                            <div class="controls">
                                <?php $smtp_host['class'] = 'span12'; echo form_input($smtp_host); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_smtp_username', 'smtp_username'); ?></label>
                            <div class="controls">
                                <?php $smtp_username['class'] = 'span12'; echo form_input($smtp_username); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_smtp_password', 'smtp_password'); ?></label>
                            <div class="controls">
                                <?php $smtp_password['class'] = 'span12'; echo form_input($smtp_password); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_smtp_port', 'smtp_port'); ?></label>
                            <div class="controls">
                                <?php $smtp_port['class'] = 'span12'; echo form_input($smtp_port); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_smtp_ssl', 'smtp_ssl'); ?></label>
                            <div class="controls">
                                <label for="smtp_ssl"><?php echo form_radio($smtp_ssl); ?> SSL</label>
                                <label for="smtp_ssl2"><?php echo form_radio($smtp_ssl2); ?> TLS</label>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_email_smtp_auth', 'smtp_auth'); ?></label>
                            <div class="controls">
                                <?php $smtp_auth['class'] = 'span12'; echo form_checkbox($smtp_auth); ?>
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