<?php
echo $header;
echo $sidebar
?>		
<div class="span10 content">
    <?php echo $hugemenu ?>

    <h1><?php echo lang('set_genaral_title'); ?></h1>

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
            <div class="widget-head"><h3 class="heading"><?php echo lang('set_genaral_widget_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_genaral_site_title', 'site_title'); ?></label>
                            <div class="controls">
                                <?php $site_title['class'] = 'span12'; echo form_input($site_title); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_genaral_slogan', 'slogan'); ?></label>
                            <div class="controls">
                                <?php $slogan['class'] = 'span12'; echo form_input($slogan); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_genaral_keyword', 'site_keyword'); ?></label>
                            <div class="controls">
                                <?php $site_keyword['class'] = 'span12'; echo form_input($site_keyword); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_genaral_author', 'site_author'); ?></label>
                            <div class="controls">
                                <?php $site_author['class'] = 'span12'; echo form_input($site_author); ?>
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
                            <label class="control-label"><?php echo lang('set_genaral_estate_count', 'estate_count'); ?></label>
                            <div class="controls">
                                <?php $estate_count['class'] = 'span12'; echo form_input($estate_count); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_genaral_blog_count', 'blog_count'); ?></label>
                            <div class="controls">
                                <?php $blog_count['class'] = 'span12'; echo form_input($blog_count); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_genaral_analytics', 'site_analytics'); ?></label>
                            <div class="controls">
                                <?php $site_analytics['class'] = 'span12'; echo form_textarea($site_analytics,$site_analytics_value); ?>
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