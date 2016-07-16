<?php echo $header;
echo $sidebar ?>
    <div class="span10 content">    <?php echo $hugemenu ?>
        <h1><?php echo lang('set_core_title'); ?></h1>    <?php if ($message) { ?>
            <div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">x</a><?php echo $message; ?>
            </div>    <?php } ?>    <?php if ($success) { ?>
            <div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">x</a><?php echo $success; ?>
            </div>    <?php } ?>
        <div class="contentArea">        <?php echo form_open(current_url(), array('class' => 'form-horizontal')); ?>
            <div class="widget">
                <div class="widget-head"><h3 class="heading"><?php echo lang('set_core_widget_title') ?></h3></div>
                <div class="widget-body">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group"><label
                                    class="control-label"><?php echo lang('set_core_min_password_length', 'min_password_length'); ?></label>

                                <div
                                    class="controls">                                <?php $min_password_length['class'] = 'span12';
                                    echo form_input($min_password_length); ?>                            </div>
                            </div>
                            <div class="control-group"><label
                                    class="control-label"><?php echo lang('set_core_max_password_length', 'max_password_length'); ?></label>

                                <div
                                    class="controls">                                <?php $max_password_length['class'] = 'span12';
                                    echo form_input($max_password_length); ?>                            </div>
                            </div>
                            <div class="control-group"><label
                                    class="control-label"><?php echo lang('set_core_default_group', 'default_group'); ?></label>

                                <div
                                    class="controls">                                <?php echo form_dropdown('default_group', $default_group, $default_group_ac, "class='span12'"); ?>                            </div>
                            </div>
                            <div class="control-group"><label
                                    class="control-label"><?php echo lang('set_core_email_activation', 'email_activation'); ?></label>

                                <div
                                    class="controls">                                <?php $email_activation['class'] = 'span12';
                                    echo form_checkbox($email_activation); ?>                            </div>
                            </div>
                            <div class="control-group"><label class="control-label"></label>

                                <div
                                    class="controls">                                <?php echo form_submit('submit', lang('set_genaral_btn'), 'class="btn btn-info span6"'); ?>                            </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group"><label
                                    class="control-label"><?php echo lang('set_core_login_attempts', 'login_attempts'); ?></label>

                                <div
                                    class="controls">                                <?php $login_attempts['class'] = 'span12';
                                    echo form_checkbox($login_attempts); ?>                            </div>
                            </div>
                            <div class="control-group"><label
                                    class="control-label"><?php echo lang('set_core_maximum_login_attempts', 'maximum_login_attempts'); ?></label>

                                <div
                                    class="controls">                                <?php $maximum_login_attempts['class'] = 'span12';
                                    echo form_input($maximum_login_attempts); ?>                            </div>
                            </div>
                            <div class="control-group"><label
                                    class="control-label"><?php echo lang('set_core_cache_timeout', 'cache_timeout'); ?></label>

                                <div
                                    class="controls">                                <?php $cache_timeout['class'] = 'span12';
                                    echo form_input($cache_timeout); ?>                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        <?php echo form_close(); ?>        </div>
    </div><!--/content--><?php echo $footer ?>