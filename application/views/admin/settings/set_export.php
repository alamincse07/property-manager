<?php
/**
 * Created by PhpStorm.
 * User: Raaz Ahmed
 * Date: 6/13/14
 * Time: 12:37 PM
 */
echo $header;
echo $sidebar
?>
    <div class="span10 content">
        <?php echo $hugemenu ?>

        <h1><?php echo lang('set_export_title'); ?></h1>

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
                <div class="widget-head"><h3 class="heading"><?php echo lang('set_export_title') ?></h3></div>
                <div class="widget-body">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <div class="control-group">
                                    <label class="control-label"><?php echo lang('export_driver_label', 'set_export'); ?></label>
                                    <div class="controls">
                                        <?php $set_export['class'] = 'span12'; echo form_input($set_export,$set_export_value); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo lang('export_driver_module_label', 'set_export_module'); ?></label>
                                    <div class="controls">
                                        <?php $set_export_module['class'] = 'span12'; echo form_input($set_export_module,$set_export_module_value); ?>
                                    </div>
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
                        </div>


                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
    <!--/content-->


<?php echo $footer ?>