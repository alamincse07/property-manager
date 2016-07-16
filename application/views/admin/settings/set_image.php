<?php
echo $header;
echo $sidebar
?>		
<div class="span10 content">
    <?php echo $hugemenu ?>

    <h1><?php echo lang('set_image_title'); ?></h1>

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
            <div class="widget-head"><h3 class="heading"><?php echo lang('set_image_widget_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_width', 'image_width'); ?></label>
                            <div class="controls">
                                <?php $image_width['class'] = 'span12'; echo form_input($image_width); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_height', 'image_height'); ?></label>
                            <div class="controls">
                                <?php $image_height['class'] = 'span12'; echo form_input($image_height); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_quality', 'image_quality'); ?></label>
                            <div class="controls">
                                <?php $image_quality['class'] = 'span12'; echo form_input($image_quality); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_maintain_ratio', 'image_maintain_ratio'); ?></label>
                            <div class="controls">
                                <?php $image_maintain_ratio['class'] = 'span12'; echo form_checkbox($image_maintain_ratio); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_watermarking', 'image_watermarking'); ?></label>
                            <div class="controls">
                                <?php $image_watermarking['class'] = 'span12'; echo form_checkbox($image_watermarking); ?>
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
                            <label class="control-label"><?php echo lang('set_image_wm_text', 'image_wm_text'); ?></label>
                            <div class="controls">
                                <?php $image_wm_text['class'] = 'span12'; echo form_input($image_wm_text); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_wm_font_path', 'image_wm_font_path'); ?></label>
                            <div class="controls">
                                <?php $image_wm_font_path['class'] = 'span12'; echo form_input($image_wm_font_path); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_wm_font_size', 'image_wm_font_size'); ?></label>
                            <div class="controls">
                                <?php $image_wm_font_size['class'] = 'span12'; echo form_input($image_wm_font_size); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_wm_font_color', 'image_wm_font_color'); ?></label>
                            <div class="controls">
                                <?php $image_wm_font_color['class'] = 'span12'; echo form_input($image_wm_font_color); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_wm_shadow_color', 'image_wm_shadow_color'); ?></label>
                            <div class="controls">
                                <?php $image_wm_shadow_color['class'] = 'span12'; echo form_input($image_wm_shadow_color); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_wm_shadow_distance', 'image_wm_shadow_distance'); ?></label>
                            <div class="controls">
                                <?php $image_wm_shadow_distance['class'] = 'span12'; echo form_input($image_wm_shadow_distance); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_wm_hor_offset', 'image_wm_hor_offset'); ?></label>
                            <div class="controls">
                                <?php $image_wm_hor_offset['class'] = 'span12'; echo form_input($image_wm_hor_offset); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_wm_vrt_alignment', 'image_wm_vrt_alignment'); ?></label>
                            <div class="controls">
                                <?php echo form_dropdown('image_wm_vrt_alignment',$image_wm_vrt_alignment,$image_wm_vrt_alignment_value,'class="span12"'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('set_image_wm_hor_alignment', 'image_wm_hor_alignment'); ?></label>
                            <div class="controls">
                                <?php echo form_dropdown('image_wm_hor_alignment',$image_wm_hor_alignment,$image_wm_hor_alignment_value,'class="span12"'); ?>
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