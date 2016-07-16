<?php
echo $header;
echo $sidebar
?>		
<div class="span10 content">
    <?php echo $hugemenu ?>

    <h1><?php echo lang('set_contact_title'); ?></h1>

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
            <div class="widget-head"><h3 class="heading"><?php echo lang('set_contact_widget_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_contact_site_eposta', 'site_eposta'); ?></label>
                            <div class="controls">
                                <?php $site_eposta['class'] = 'span12'; echo form_input($site_eposta); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_contact_phone', 'phone'); ?></label>
                            <div class="controls">
                                <?php $phone['class'] = 'span12'; echo form_input($phone); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_contact_mobile_phone', 'mobile_phone'); ?></label>
                            <div class="controls">
                                <?php $mobile_phone['class'] = 'span12'; echo form_input($mobile_phone); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_contact_adress', 'adress'); ?></label>
                            <div class="controls">
                                <?php $adress['class'] = 'span12'; echo form_input($adress); ?>
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
                            <label class="control-label"><?php echo lang('set_contact_facebook', 'facebook'); ?></label>
                            <div class="controls">
                                <?php $facebook['class'] = 'span12'; echo form_input($facebook); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_contact_twitter', 'twitter'); ?></label>
                            <div class="controls">
                                <?php $twitter['class'] = 'span12'; echo form_input($twitter); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_contact_google', 'google'); ?></label>
                            <div class="controls">
                                <?php $google['class'] = 'span12'; echo form_input($google); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_contact_linkedin', 'linkedin'); ?></label>
                            <div class="controls">
                                <?php $linkedin['class'] = 'span12'; echo form_input($linkedin); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_contact_pinterest', 'pinterest'); ?></label>
                            <div class="controls">
                                <?php $pinterest['class'] = 'span12'; echo form_input($pinterest); ?>
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