<?php
echo $header;
echo $sidebar
?>		
<div class="span10 content">
    <?php echo $hugemenu ?>

    <h1><?php echo lang('edit_group_heading'); ?></h1>
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
    <div class="alert alert-info"><?php echo lang('edit_group_subheading'); ?></div>
    <div class="contentArea">
                <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo $group_name['value'] . lang('editgroup_add_h2_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
        <?php echo form_open(current_url(), array('class' => 'form-horizontal')); ?>
        <div class="control-group">
            <label class="control-label"><?php echo lang('create_group_name_label', 'group_name'); ?></label>
            <div class="controls">
                <?php
                $group_name['class'] = 'span';
                echo form_input($group_name);
                ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><?php echo lang('edit_group_desc_label', 'group_description'); ?></label>
            <div class="controls">
                <?php
                $group_description['class'] = 'span';
                echo form_input($group_description);
                ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><?php echo lang('group_creat_level', 'level'); ?></label>
            <div class="controls">
                <?php echo form_dropdown('level', $level, $group->level,'class="span12"'); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"></label>
            <div class="controls">
                <?php echo form_submit('submit', lang('edit_group_submit_btn'), 'class="btn btn-info span6"'); ?>
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